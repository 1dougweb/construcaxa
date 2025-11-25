<?php

namespace App\Http\Controllers;

use App\Models\Client;
use App\Models\Employee;
use App\Models\Project;
use App\Models\ProjectBudget;
use App\Models\ProjectPhoto;
use App\Models\ProjectFile;
use App\Models\ProjectUpdate;
use App\Models\ProjectTask;
use App\Models\Service;
use App\Models\LaborType;
use App\Services\GoogleMapsService;
use App\Services\NotificationService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;

class ProjectController extends Controller
{
    use AuthorizesRequests;

    private function canViewProject(Project $project): bool
    {
        $user = auth()->user();
        if ($user->can('view projects') || $user->hasAnyRole(['manager','admin'])) return true;
        if ($user->can('view client-projects') && $project->client_id === $user->id) return true;
        if ($user->hasRole('employee')) {
            $employee = Employee::where('user_id', $user->id)->first();
            if ($employee && $project->employees()->where('employees.id', $employee->id)->exists()) return true;
        }
        return false;
    }
    public function index()
    {
        // Access controlled by route middleware (role_or_permission)
        $projects = Project::latest()->paginate(15);
        return view('projects.index', compact('projects'));
    }

    public function create()
    {
        // Access controlled by route middleware (role_or_permission)
        $employees = Employee::with('user')->orderByDesc('id')->get();
        return view('projects.create', compact('employees'));
    }

    public function store(Request $request)
    {
        // Double-check permission/role
        abort_unless(auth()->user()->can('create projects') || auth()->user()->hasAnyRole(['manager','admin']), 403);
        $data = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'code' => ['required', 'string', 'max:50', 'unique:projects,code'],
            'client_id' => ['nullable', 'exists:users,id'],
            'address' => ['nullable', 'string', 'max:255'],
            'start_date' => ['nullable', 'date'],
            'end_date_estimated' => ['nullable', 'date', 'after_or_equal:start_date'],
            'status' => ['required', 'in:planned,in_progress,paused,completed,cancelled'],
            'progress_percentage' => ['nullable', 'integer', 'between:0,100'],
            'notes' => ['nullable', 'string'],
            'employee_ids' => ['array'],
            'employee_ids.*' => ['exists:employees,id'],
        ]);

        // Geocode address if provided
        if (!empty($data['address'])) {
            $googleMapsService = new GoogleMapsService();
            $coordinates = $googleMapsService->geocodeAddress($data['address']);
            
            if ($coordinates) {
                $data['latitude'] = $coordinates['latitude'];
                $data['longitude'] = $coordinates['longitude'];
            }
        }

        $project = Project::create($data);
        if (!empty($data['employee_ids'])) {
            $project->employees()->sync($data['employee_ids']);
        }

        return redirect()->route('projects.show', $project)->with('success', 'Obra criada com sucesso.');
    }

    public function show(Project $project)
    {
        abort_unless($this->canViewProject($project), 403);
        $project->load([
            'employees.user', 
            'updates.user', 
            'photos.user', 
            'files' => function($query) {
                $query->latest()->with('user');
            },
            'budgets.approver' => function($query) {
                $query->latest();
            }
        ]);
        return view('projects.show', compact('project'));
    }

    public function edit(Project $project)
    {
        abort_unless(auth()->user()->can('edit projects') || auth()->user()->hasAnyRole(['manager','admin']), 403);
        $employees = Employee::with('user')->orderByDesc('id')->get();
        return view('projects.edit', compact('project', 'employees'));
    }

    public function update(Request $request, Project $project)
    {
        abort_unless(auth()->user()->can('edit projects') || auth()->user()->hasAnyRole(['manager','admin']), 403);
        $data = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'code' => ['required', 'string', 'max:50', 'unique:projects,code,' . $project->id],
            'client_id' => ['nullable', 'exists:users,id'],
            'address' => ['nullable', 'string', 'max:255'],
            'start_date' => ['nullable', 'date'],
            'end_date_estimated' => ['nullable', 'date', 'after_or_equal:start_date'],
            'status' => ['required', 'in:planned,in_progress,paused,completed,cancelled'],
            'progress_percentage' => ['nullable', 'integer', 'between:0,100'],
            'notes' => ['nullable', 'string'],
            'employee_ids' => ['array'],
            'employee_ids.*' => ['exists:employees,id'],
        ]);

        // Geocode address if it has changed
        if (!empty($data['address']) && $data['address'] !== $project->address) {
            $googleMapsService = new GoogleMapsService();
            $coordinates = $googleMapsService->geocodeAddress($data['address']);
            
            if ($coordinates) {
                $data['latitude'] = $coordinates['latitude'];
                $data['longitude'] = $coordinates['longitude'];
            }
        }

        $project->update($data);
        $project->employees()->sync($data['employee_ids'] ?? []);

        return redirect()->route('projects.show', $project)->with('success', 'Obra atualizada com sucesso.');
    }

    public function destroy(Project $project)
    {
        abort_unless(auth()->user()->can('delete projects') || auth()->user()->hasAnyRole(['manager','admin']), 403);
        $project->delete();
        return redirect()->route('projects.index')->with('success', 'Obra excluída.');
    }

    public function storeUpdate(Request $request, Project $project)
    {
        abort_unless($this->canViewProject($project), 403);
        abort_unless(auth()->user()->can('post project-updates'), 403);

        // Funcionário só pode postar se fizer parte da obra
        if (auth()->user()->hasRole('employee')) {
            $employee = Employee::where('user_id', auth()->id())->first();
            abort_unless($employee && $project->employees()->where('employees.id', $employee->id)->exists(), 403);
        }

        $data = $request->validate([
            'type' => ['required', 'in:note,issue,material_missing,progress'],
            'message' => ['required', 'string', 'max:5000'],
            'progress_delta' => ['nullable', 'integer', 'between:-100,100'],
        ]);

        $data['user_id'] = auth()->id();
        $data['project_id'] = $project->id;
        ProjectUpdate::create($data);

        // Atualizar progresso quando aplicável
        if ($data['type'] === 'progress' && isset($data['progress_delta'])) {
            $newProgress = max(0, min(100, (int) $project->progress_percentage + (int) $data['progress_delta']));
            $project->update(['progress_percentage' => $newProgress]);
        }

        return redirect()->route('projects.show', $project)->with('success', 'Atualização registrada.');
    }

    public function uploadPhoto(Request $request, Project $project)
    {
        abort_unless($this->canViewProject($project), 403);
        abort_unless(auth()->user()->can('post project-updates'), 403);

        // Funcionário só pode postar se fizer parte da obra
        if (auth()->user()->hasRole('employee')) {
            $employee = Employee::where('user_id', auth()->id())->first();
            abort_unless($employee && $project->employees()->where('employees.id', $employee->id)->exists(), 403);
        }

        $data = $request->validate([
            'photo' => ['required', 'image', 'max:5120'], // 5MB
            'caption' => ['nullable', 'string', 'max:255'],
        ]);

        $path = $request->file('photo')->store('project-photos', 'public');

        ProjectPhoto::create([
            'project_id' => $project->id,
            'user_id' => auth()->id(),
            'path' => $path,
            'caption' => $data['caption'] ?? null,
        ]);

        return redirect()->route('projects.show', $project)->with('success', 'Foto enviada.');
    }

    public function uploadFile(Request $request, Project $project)
    {
        abort_unless($this->canViewProject($project), 403);
        abort_unless(auth()->user()->can('post project-updates'), 403);

        // Funcionário só pode postar se fizer parte da obra
        if (auth()->user()->hasRole('employee')) {
            $employee = Employee::where('user_id', auth()->id())->first();
            abort_unless($employee && $project->employees()->where('employees.id', $employee->id)->exists(), 403);
        }

        $request->validate([
            'files.*' => [
                'required',
                'file',
                'max:10240', // 10MB
                'mimes:jpg,jpeg,png,gif,webp,pdf,doc,docx,xls,xlsx,ppt,pptx,txt,csv,zip,rar'
            ],
            'description' => ['nullable', 'string', 'max:500'],
        ]);

        $uploadedFiles = [];
        
        if ($request->hasFile('files')) {
            foreach ($request->file('files') as $file) {
                // Gerar nome único para o arquivo
                $originalName = $file->getClientOriginalName();
                $extension = $file->getClientOriginalExtension();
                $fileName = time() . '_' . uniqid() . '.' . $extension;
                
                // Armazenar arquivo
                $path = $file->storeAs('project-files/' . $project->id, $fileName, 'public');
                
                // Criar registro no banco
                $projectFile = ProjectFile::create([
                    'project_id' => $project->id,
                    'user_id' => auth()->id(),
                    'name' => $fileName,
                    'original_name' => $originalName,
                    'path' => $path,
                    'mime_type' => $file->getMimeType(),
                    'size' => $file->getSize(),
                    'description' => $request->input('description'),
                ]);
                
                $uploadedFiles[] = $projectFile;
            }
        }

        $message = count($uploadedFiles) > 1 
            ? count($uploadedFiles) . ' arquivos enviados com sucesso.'
            : 'Arquivo enviado com sucesso.';

        return redirect()->route('projects.show', $project)->with('success', $message);
    }

    public function downloadFile(Project $project, ProjectFile $file)
    {
        abort_unless($this->canViewProject($project), 403);
        abort_unless($file->project_id === $project->id, 404);

        $filePath = Storage::disk('public')->path($file->path);
        
        if (!Storage::disk('public')->exists($file->path)) {
            abort(404, 'Arquivo não encontrado.');
        }

        return response()->download($filePath, $file->original_name);
    }

    public function deleteFile(Project $project, ProjectFile $file)
    {
        abort_unless($this->canViewProject($project), 403);
        abort_unless($file->project_id === $project->id, 404);

        // Apenas o uploader, admin ou manager podem deletar
        $user = auth()->user();
        $canDelete = $file->user_id === $user->id 
            || $user->can('edit projects') 
            || $user->hasAnyRole(['manager', 'admin']);

        abort_unless($canDelete, 403, 'Você não tem permissão para deletar este arquivo.');

        // Deletar arquivo do storage
        if (Storage::disk('public')->exists($file->path)) {
            Storage::disk('public')->delete($file->path);
        }

        // Deletar registro do banco
        $file->delete();

        return redirect()->route('projects.show', $project)->with('success', 'Arquivo removido com sucesso.');
    }

    public function storeTask(Request $request, Project $project)
    {
        abort_unless($this->canViewProject($project), 403);
        abort_unless(auth()->user()->can('edit projects') || auth()->user()->hasAnyRole(['manager','admin']) || auth()->user()->hasRole('employee'), 403);

        $data = $request->validate([
            'title' => ['required','string','max:255'],
            'description' => ['nullable','string'],
            'due_date' => ['nullable','date'],
        ]);

        ProjectTask::create([
            'project_id' => $project->id,
            'title' => $data['title'],
            'description' => $data['description'] ?? null,
            'due_date' => $data['due_date'] ?? null,
            'status' => 'todo',
        ]);

        return redirect()->route('projects.show', $project)->with('success', 'Tarefa adicionada.');
    }

    public function updateTaskStatus(Request $request, Project $project, ProjectTask $task)
    {
        abort_unless($this->canViewProject($project), 403);
        abort_unless($task->project_id === $project->id, 404);
        abort_unless(auth()->user()->can('edit projects') || auth()->user()->hasAnyRole(['manager','admin']) || auth()->user()->hasRole('employee'), 403);

        $request->validate([
            'status' => ['required','in:todo,in_progress,done'],
        ]);
        $task->update(['status' => $request->string('status')]);

        return redirect()->route('projects.show', $project)->with('success', 'Tarefa atualizada.');
    }

    public function deleteTask(Project $project, ProjectTask $task)
    {
        abort_unless($this->canViewProject($project), 403);
        abort_unless($task->project_id === $project->id, 404);
        abort_unless(auth()->user()->can('edit projects') || auth()->user()->hasAnyRole(['manager','admin']), 403);
        $task->delete();
        return redirect()->route('projects.show', $project)->with('success', 'Tarefa removida.');
    }

    public function updateTask(Request $request, Project $project, ProjectTask $task)
    {
        abort_unless($this->canViewProject($project), 403);
        abort_unless($task->project_id === $project->id, 404);
        abort_unless(auth()->user()->can('edit projects') || auth()->user()->hasAnyRole(['manager','admin']) || auth()->user()->hasRole('employee'), 403);

        $data = $request->validate([
            'title' => ['required','string','max:255'],
            'description' => ['nullable','string'],
            'due_date' => ['nullable','date'],
        ]);

        $task->update($data);

        return redirect()->route('projects.show', $project)->with('success', 'Tarefa atualizada.');
    }

    public function budgetsIndex()
    {
        // Access controlled by route middleware
        $budgets = ProjectBudget::with(['project', 'client', 'items', 'approver'])->latest()->paginate(15);
        return view('budgets.index', compact('budgets'));
    }

    public function budgetsCreate()
    {
        abort_unless(auth()->user()->can('manage budgets') || auth()->user()->hasAnyRole(['manager','admin']), 403);
        
        // Get clients
        $clients = Client::active()->orderBy('name')->get();
        
        // Load active services with categories
        $services = Service::active()->with('category')->orderBy('name')->get();
        
        // Load active labor types
        $laborTypes = LaborType::active()->orderBy('name')->get();
        
        return view('budgets.create', compact('clients', 'services', 'laborTypes'));
    }

    public function budgetsStore(Request $request)
    {
        abort_unless(auth()->user()->can('manage budgets') || auth()->user()->hasAnyRole(['manager','admin']), 403);
        
        $data = $request->validate([
            'client_id' => ['required', 'exists:clients,id'],
            'inspection_id' => ['nullable', 'exists:inspections,id'],
            'version' => ['required', 'integer', 'min:1'],
            'discount' => ['nullable', 'numeric', 'min:0'],
            'status' => ['required', 'in:pending,under_review,approved,rejected,cancelled'],
            'notes' => ['nullable', 'string'],
            'items' => ['required', 'array', 'min:1'],
            'items.*.item_type' => ['required', 'in:product,service,labor'],
            'items.*.product_id' => ['nullable', 'required_if:items.*.item_type,product', 'exists:products,id'],
            'items.*.service_id' => ['nullable', 'required_if:items.*.item_type,service', 'exists:services,id'],
            'items.*.labor_type_id' => ['nullable', 'required_if:items.*.item_type,labor', 'exists:labor_types,id'],
            'items.*.description' => ['required', 'string', 'max:255'],
            'items.*.quantity' => ['nullable', 'numeric', 'min:0'],
            'items.*.hours' => ['nullable', 'numeric', 'min:0'],
            'items.*.overtime_hours' => ['nullable', 'numeric', 'min:0'],
            'items.*.unit_price' => ['required', 'numeric', 'min:0'],
        ]);

        // Calculate subtotal and total based on item type
        $subtotal = collect($data['items'])->sum(function ($item) {
            if ($item['item_type'] === 'labor') {
                $hours = ($item['hours'] ?? 0);
                $overtimeHours = ($item['overtime_hours'] ?? 0);
                return ($hours * ($item['unit_price'] ?? 0)) + ($overtimeHours * ($item['unit_price'] ?? 0) * 1.5);
            } else {
                return ($item['quantity'] ?? 0) * ($item['unit_price'] ?? 0);
            }
        });
        $discount = $data['discount'] ?? 0;
        $total = $subtotal - $discount;

        DB::beginTransaction();
        try {
            $budget = ProjectBudget::create([
                'client_id' => $data['client_id'],
                'inspection_id' => $data['inspection_id'] ?? null,
                'project_id' => null, // Will be set when approved
                'version' => $data['version'],
                'subtotal' => $subtotal,
                'discount' => $discount,
                'total' => $total,
                'status' => $data['status'],
                'notes' => $data['notes'] ?? null,
                'sent_at' => $data['status'] === 'under_review' ? now() : null,
                'approved_at' => $data['status'] === 'approved' ? now() : null,
                'approved_by' => $data['status'] === 'approved' ? auth()->id() : null,
            ]);

            // Create project automatically if budget is approved
            if ($data['status'] === 'approved') {
                $project = Project::createFromApprovedBudget($budget);
            }

            // Create budget items
            foreach ($data['items'] as $itemData) {
                $itemTotal = 0;
                if ($itemData['item_type'] === 'labor') {
                    $hours = ($itemData['hours'] ?? 0);
                    $overtimeHours = ($itemData['overtime_hours'] ?? 0);
                    $itemTotal = ($hours * ($itemData['unit_price'] ?? 0)) + ($overtimeHours * ($itemData['unit_price'] ?? 0) * 1.5);
                } else {
                    $itemTotal = ($itemData['quantity'] ?? 0) * ($itemData['unit_price'] ?? 0);
                }
                
                $budget->items()->create([
                    'item_type' => $itemData['item_type'],
                    'product_id' => $itemData['product_id'] ?? null,
                    'service_id' => $itemData['service_id'] ?? null,
                    'labor_type_id' => $itemData['labor_type_id'] ?? null,
                    'description' => $itemData['description'],
                    'quantity' => $itemData['quantity'] ?? 0,
                    'hours' => $itemData['hours'] ?? 0,
                    'overtime_hours' => $itemData['overtime_hours'] ?? 0,
                    'unit_price' => $itemData['unit_price'],
                    'total' => $itemTotal,
                ]);
            }

            DB::commit();
            $message = 'Orçamento criado com sucesso!';
            if ($data['status'] === 'approved' && isset($project)) {
                $message .= ' Projeto criado automaticamente com OS número ' . $project->os_number . '.';
            }
            return redirect()->route('budgets.index')->with('success', $message);
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->withInput()->with('error', 'Erro ao criar orçamento: ' . $e->getMessage());
        }
    }

    public function budgetsEdit(ProjectBudget $budget)
    {
        abort_unless(auth()->user()->can('manage budgets') || auth()->user()->hasAnyRole(['manager','admin']), 403);
        $budget->load(['items.product', 'items.service', 'items.laborType', 'project', 'client']);
        // Get clients
        $clients = Client::active()->orderBy('name')->get();
        
        // Load active services with categories
        $services = Service::active()->with('category')->orderBy('name')->get();
        
        // Load active labor types
        $laborTypes = LaborType::active()->orderBy('name')->get();
        
        return view('budgets.edit', compact('budget', 'clients', 'services', 'laborTypes'));
    }

    public function budgetsUpdate(Request $request, ProjectBudget $budget)
    {
        abort_unless(auth()->user()->can('manage budgets') || auth()->user()->hasAnyRole(['manager','admin']), 403);
        
        $data = $request->validate([
            'client_id' => ['required', 'exists:clients,id'],
            'inspection_id' => ['nullable', 'exists:inspections,id'],
            'version' => ['required', 'integer', 'min:1'],
            'discount' => ['nullable', 'numeric', 'min:0'],
            'status' => ['required', 'in:pending,under_review,approved,rejected,cancelled'],
            'notes' => ['nullable', 'string'],
            'items' => ['required', 'array', 'min:1'],
            'items.*.item_type' => ['required', 'in:product,service,labor'],
            'items.*.product_id' => ['nullable', 'required_if:items.*.item_type,product', 'exists:products,id'],
            'items.*.service_id' => ['nullable', 'required_if:items.*.item_type,service', 'exists:services,id'],
            'items.*.labor_type_id' => ['nullable', 'required_if:items.*.item_type,labor', 'exists:labor_types,id'],
            'items.*.description' => ['required', 'string', 'max:255'],
            'items.*.quantity' => ['nullable', 'numeric', 'min:0'],
            'items.*.hours' => ['nullable', 'numeric', 'min:0'],
            'items.*.overtime_hours' => ['nullable', 'numeric', 'min:0'],
            'items.*.unit_price' => ['required', 'numeric', 'min:0'],
        ]);

        // Calculate subtotal and total based on item type
        $subtotal = collect($data['items'])->sum(function ($item) {
            if ($item['item_type'] === 'labor') {
                $hours = ($item['hours'] ?? 0);
                $overtimeHours = ($item['overtime_hours'] ?? 0);
                return ($hours * ($item['unit_price'] ?? 0)) + ($overtimeHours * ($item['unit_price'] ?? 0) * 1.5);
            } else {
                return ($item['quantity'] ?? 0) * ($item['unit_price'] ?? 0);
            }
        });
        $discount = $data['discount'] ?? 0;
        $total = $subtotal - $discount;

        DB::beginTransaction();
        try {
            // Check if status changed to approved
            $wasApproved = $budget->status !== 'approved' && $data['status'] === 'approved';
            
            $budget->update([
                'client_id' => $data['client_id'],
                'inspection_id' => $data['inspection_id'] ?? null,
                'version' => $data['version'],
                'subtotal' => $subtotal,
                'discount' => $discount,
                'total' => $total,
                'status' => $data['status'],
                'notes' => $data['notes'] ?? null,
                'sent_at' => $data['status'] === 'under_review' && !$budget->sent_at ? now() : $budget->sent_at,
                'approved_at' => $data['status'] === 'approved' && !$budget->approved_at ? now() : $budget->approved_at,
                'approved_by' => $data['status'] === 'approved' && !$budget->approved_by ? auth()->id() : $budget->approved_by,
            ]);

            // Create project automatically if budget was just approved
            if ($wasApproved && !$budget->project_id) {
                $project = Project::createFromApprovedBudget($budget);
            }

            // Remove old items and create new ones
            $budget->items()->delete();
            foreach ($data['items'] as $itemData) {
                $itemTotal = 0;
                if ($itemData['item_type'] === 'labor') {
                    $hours = ($itemData['hours'] ?? 0);
                    $overtimeHours = ($itemData['overtime_hours'] ?? 0);
                    $itemTotal = ($hours * ($itemData['unit_price'] ?? 0)) + ($overtimeHours * ($itemData['unit_price'] ?? 0) * 1.5);
                } else {
                    $itemTotal = ($itemData['quantity'] ?? 0) * ($itemData['unit_price'] ?? 0);
                }
                
                $budget->items()->create([
                    'item_type' => $itemData['item_type'],
                    'product_id' => $itemData['product_id'] ?? null,
                    'service_id' => $itemData['service_id'] ?? null,
                    'labor_type_id' => $itemData['labor_type_id'] ?? null,
                    'description' => $itemData['description'],
                    'quantity' => $itemData['quantity'] ?? 0,
                    'hours' => $itemData['hours'] ?? 0,
                    'overtime_hours' => $itemData['overtime_hours'] ?? 0,
                    'unit_price' => $itemData['unit_price'],
                    'total' => $itemTotal,
                ]);
            }

            DB::commit();
            
            $message = 'Orçamento atualizado com sucesso!';
            if ($wasApproved && isset($project)) {
                $message .= ' Projeto criado automaticamente com OS número ' . $project->os_number . '.';
            }
            
            return redirect()->route('budgets.index')->with('success', $message);
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->withInput()->with('error', 'Erro ao atualizar orçamento: ' . $e->getMessage());
        }
    }

    /**
     * Approve a budget and create project automatically
     */
    public function budgetsApprove(ProjectBudget $budget)
    {
        abort_unless(auth()->user()->can('manage budgets') || auth()->user()->hasAnyRole(['manager','admin']), 403);
        
        if ($budget->status === 'approved') {
            return back()->with('error', 'Este orçamento já está aprovado.');
        }

        DB::beginTransaction();
        try {
            $budget->update([
                'status' => 'approved',
                'approved_at' => now(),
                'approved_by' => auth()->id(),
            ]);

            // Create project automatically if it doesn't exist
            if (!$budget->project_id) {
                $project = Project::createFromApprovedBudget($budget);
                $message = 'Orçamento aprovado com sucesso! Projeto criado automaticamente com OS número ' . $project->os_number . '.';
            } else {
                // If project already exists, just assign OS number
                $budget->project->assignOsNumber();
                $message = 'Orçamento aprovado com sucesso! OS número ' . $budget->project->os_number . ' foi gerado.';
            }

            DB::commit();
            
            // Criar notificação para o cliente
            NotificationService::createBudgetApprovalNotification($budget);
            
            return back()->with('success', $message);
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with('error', 'Erro ao aprovar orçamento: ' . $e->getMessage());
        }
    }

    /**
     * Reject a budget
     */
    public function budgetsReject(ProjectBudget $budget)
    {
        abort_unless(auth()->user()->can('manage budgets') || auth()->user()->hasAnyRole(['manager','admin']), 403);
        
        if ($budget->status === 'approved') {
            return back()->with('error', 'Não é possível rejeitar um orçamento já aprovado.');
        }

        $budget->update(['status' => 'rejected']);
        return back()->with('success', 'Orçamento rejeitado.');
    }

    /**
     * Cancel a budget
     */
    public function budgetsCancel(ProjectBudget $budget)
    {
        abort_unless(auth()->user()->can('manage budgets') || auth()->user()->hasAnyRole(['manager','admin']), 403);
        
        $budget->update(['status' => 'cancelled']);
        return back()->with('success', 'Orçamento cancelado.');
    }

    /**
     * Generate PDF for budget
     */
    public function budgetsPdf(ProjectBudget $budget)
    {
        abort_unless(auth()->user()->can('view budgets') || auth()->user()->hasAnyRole(['manager','admin']), 403);
        
        $budget->load(['project.client', 'items.product', 'approver']);
        
        $pdf = \Barryvdh\DomPDF\Facade\Pdf::loadView('budgets.pdf', compact('budget'));
        
        return $pdf->stream("orcamento-{$budget->id}-v{$budget->version}.pdf");
    }
}


