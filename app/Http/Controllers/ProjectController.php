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
use App\Models\AccountReceivable;
use App\Models\Product;
use App\Models\ProjectFinancialBalance;
use App\Models\FinancialTransaction;
use App\Models\EmployeeProposal;
use App\Models\EmployeeProposalItem;
use App\Models\Service;
use App\Models\LaborType;
use App\Models\Inspection;
use App\Models\ProductReservation;
use App\Mail\BudgetClientRequestNotification;
use App\Mail\BudgetApprovedByClientNotification;
use App\Mail\BudgetRejectedByClientNotification;
use App\Mail\EmployeeProposalNotification;
use App\Services\GoogleMapsService;
use App\Services\NotificationService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Log;
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

    /**
     * Criar conta a receber padrão para um orçamento aprovado (se ainda não existir).
     * Isso alimenta o painel financeiro com o total a receber da obra.
     */
    private function ensureReceivableForApprovedBudget(ProjectBudget $budget, Project $project): void
    {
        try {
            // Se orçamento não tem total ou é zero, nada a fazer
            if ($budget->total <= 0) {
                return;
            }

            // Se já existir uma conta a receber vinculada a este projeto e orçamento, não criar novamente
            $exists = AccountReceivable::where('project_id', $project->id)
                ->where('description', 'like', "Orçamento #{$budget->id}%")
                ->exists();

            if ($exists) {
                return;
            }

            // Quanto já foi efetivamente recebido nessa obra
            $receivedSoFar = AccountReceivable::where('project_id', $project->id)
                ->where('status', AccountReceivable::STATUS_RECEIVED)
                ->sum('amount');

            // Valor ainda em aberto deste orçamento
            $amountToReceive = max($budget->total - $receivedSoFar, 0);

            if ($amountToReceive <= 0) {
                return;
            }

            $clientUserId = $project->client?->user_id ?? null;
            if (!$clientUserId) {
                $clientUserId = $budget->client?->user_id ?? null;
            }

            // Gerar número da conta a receber
            $number = (new AccountReceivable())->generateNumber();

            AccountReceivable::create([
                'client_id' => $clientUserId,
                'project_id' => $project->id,
                'number' => $number,
                'description' => "Orçamento #{$budget->id} - Receita da obra {$project->code}",
                'amount' => $amountToReceive,
                'due_date' => $budget->approved_at ?? now(),
                'received_date' => null,
                'status' => AccountReceivable::STATUS_PENDING,
                'notes' => 'Gerado automaticamente a partir do orçamento aprovado',
                'user_id' => auth()->id(),
            ]);
        } catch (\Throwable $e) {
            Log::error('Erro ao criar conta a receber para orçamento aprovado', [
                'budget_id' => $budget->id,
                'project_id' => $project->id,
                'error' => $e->getMessage(),
            ]);
        }
    }
    public function index(Request $request)
    {
        // Access controlled by route middleware (role_or_permission)
        $query = Project::query();
        
        // Filtro por status
        if ($request->has('status') && $request->status !== '') {
            $query->where('status', $request->status);
        }
        
        // Busca por nome ou código
        if ($request->has('search') && $request->search !== '') {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('code', 'like', "%{$search}%");
            });
        }
        
        $projects = $query->latest()->paginate(15)->withQueryString();
        
        // Estatísticas
        $stats = [
            'total' => Project::count(),
            'in_progress' => Project::where('status', 'in_progress')->count(),
            'completed' => Project::where('status', 'completed')->count(),
            'planned' => Project::where('status', 'planned')->count(),
            'paused' => Project::where('status', 'paused')->count(),
            'cancelled' => Project::where('status', 'cancelled')->count(),
        ];
        
        return view('projects.index', compact('projects', 'stats'));
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
        
        // Resumo financeiro da obra
        $approvedBudget = $project->budgets()
            ->where('status', \App\Models\ProjectBudget::STATUS_APPROVED)
            ->latest()
            ->first();
        $totalBudgetedAmount = $approvedBudget?->total ?? 0;

        // Garantir que exista conta a receber para orçamento aprovado (backfill para orçamentos antigos)
        if ($approvedBudget && $totalBudgetedAmount > 0) {
            $this->ensureReceivableForApprovedBudget($approvedBudget, $project);
        }

        $totalPaidAmount = $project->accountReceivables()
            ->where('status', AccountReceivable::STATUS_RECEIVED)
            ->sum('amount');

        $remainingAmount = max($totalBudgetedAmount - $totalPaidAmount, 0);

        // Materiais já lançados no balanço financeiro da obra
        $materials = $project->financialBalances()
            ->with('product')
            ->orderByDesc('usage_date')
            ->take(20)
            ->get();

        // Tipos de mão de obra (serviços) para propostas de equipe
        $laborTypes = LaborType::active()->orderBy('name')->get();

        // Funcionários disponíveis para adicionar na obra
        $availableEmployees = Employee::with('user')
            ->orderBy('id', 'desc')
            ->get();

        return view('projects.show', compact(
            'project',
            'approvedBudget',
            'totalBudgetedAmount',
            'totalPaidAmount',
            'remainingAmount',
            'materials',
            'availableEmployees',
            'laborTypes'
        ));
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

        $wasInProgress = $project->status === 'in_progress';
        $project->update($data);
        $project->employees()->sync($data['employee_ids'] ?? []);

        // Se projeto acabou de entrar em execução, converter reservas em baixas de estoque
        if (!$wasInProgress && $project->status === 'in_progress') {
            $this->convertReservationsToStockMovements($project);
        }

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

    /**
     * Registrar pagamento recebido do cliente para esta obra
     */
    public function storeProjectPayment(Request $request, Project $project)
    {
        abort_unless(auth()->user()->can('manage finances') || auth()->user()->hasAnyRole(['manager','admin']), 403);

        $data = $request->validate([
            'amount' => ['required', 'numeric', 'min:0.01'],
            'received_date' => ['required', 'date'],
            'description' => ['nullable', 'string', 'max:255'],
        ]);

        // Identificar usuário cliente vinculado a esta obra (se existir)
        $clientUserId = null;
        if ($project->client) {
            $clientUserId = $project->client->user_id ?? null;
        }

        // Gerar número da conta a receber
        $number = method_exists(AccountReceivable::class, 'generateNumber')
            ? (new AccountReceivable())->generateNumber()
            : null;

        // Criar conta a receber já como recebida
        $receivable = AccountReceivable::create([
            'client_id' => $clientUserId,
            'project_id' => $project->id,
            'number' => $number,
            'description' => $data['description'] ?: "Pagamento recebido da obra {$project->code}",
            'amount' => $data['amount'],
            'due_date' => $data['received_date'],
            'received_date' => $data['received_date'],
            'status' => AccountReceivable::STATUS_RECEIVED,
            'notes' => 'Registro manual via tela da obra',
            'user_id' => auth()->id(),
        ]);

        // Registrar movimentação financeira atrelada
        FinancialTransaction::create([
            'transaction_type' => FinancialTransaction::TRANSACTION_TYPE_ACCOUNT_RECEIVABLE,
            'transaction_id' => $receivable->id,
            'type' => FinancialTransaction::TYPE_INCOME,
            'amount' => $data['amount'],
            'transaction_date' => $data['received_date'],
            'project_id' => $project->id,
            'description' => $receivable->description,
            'notes' => 'Gerado automaticamente a partir de pagamento de obra',
            'user_id' => auth()->id(),
        ]);

        return redirect()->route('projects.show', $project)
            ->with('success', 'Pagamento registrado e integrado ao financeiro.');
    }

    /**
     * Adicionar membro à equipe da obra
     */
    public function attachMember(Request $request, Project $project)
    {
        abort_unless(auth()->user()->can('edit projects') || auth()->user()->hasAnyRole(['manager','admin']), 403);

        $data = $request->validate([
            'employee_id' => ['required', 'exists:employees,id'],
            'role_on_project' => ['nullable', 'string', 'max:255'],
            'observations' => ['nullable', 'string', 'max:500'],
            'items' => ['required', 'array', 'min:1'],
            'items.*.labor_type_id' => ['required', 'exists:labor_types,id'],
            'items.*.unit_price' => ['nullable', 'numeric', 'min:0'],
        ]);

        DB::beginTransaction();
        try {
            $employee = Employee::findOrFail($data['employee_id']);
            $items = $data['items'];

            // Criar proposta para o funcionário vinculado a esta obra
            $proposal = EmployeeProposal::create([
                'employee_id' => $employee->id,
                'project_id' => $project->id,
                'hourly_rate' => $items[0]['unit_price'] ?? 0,
                'contract_type' => EmployeeProposal::CONTRACT_TYPE_INDEFINITE,
                'days' => null,
                'start_date' => null,
                'end_date' => null,
                'observations' => $data['observations'] 
                    ?? "Função na obra {$project->code}: " . ($data['role_on_project'] ?? 'Membro da equipe'),
                'created_by' => auth()->id(),
            ]);

            // Itens de mão de obra (serviços que ele vai fazer)
            foreach ($items as $item) {
                EmployeeProposalItem::create([
                    'proposal_id' => $proposal->id,
                    'item_type' => EmployeeProposalItem::ITEM_TYPE_LABOR,
                    'labor_type_id' => $item['labor_type_id'],
                    'service_id' => null,
                    'quantity' => 1,
                    'unit_price' => $item['unit_price'] ?? 0,
                ]);
            }

            // Recalcular total
            $proposal->refresh();
            $proposal->total_amount = $proposal->calculateTotalAmount();
            $proposal->save();

            // Enviar email de proposta para o funcionário (fluxo padrão já existente)
            try {
                Mail::to($employee->user->email)->send(new EmployeeProposalNotification($proposal));
            } catch (\Exception $e) {
                Log::error('Erro ao enviar email de proposta de equipe na obra', [
                    'proposal_id' => $proposal->id,
                    'employee_id' => $employee->id,
                    'error' => $e->getMessage(),
                ]);
            }

            DB::commit();

            return redirect()->route('projects.show', $project)
                ->with('success', 'Proposta enviada para o colaborador por e-mail. Ele só será adicionado à obra após aceitar a proposta.');
        } catch (\Exception $e) {
            DB::rollBack();

            return redirect()->route('projects.show', $project)
                ->with('error', 'Erro ao criar proposta para o colaborador: ' . $e->getMessage());
        }
    }

    /**
     * Remover membro da equipe da obra
     */
    public function detachMember(Project $project, Employee $employee)
    {
        abort_unless(auth()->user()->can('edit projects') || auth()->user()->hasAnyRole(['manager','admin']), 403);

        $project->employees()->detach($employee->id);

        return redirect()->route('projects.show', $project)
            ->with('success', 'Membro removido da equipe da obra.');
    }

    /**
     * Registrar material/produto usado diretamente na obra
     */
    public function storeMaterial(Request $request, Project $project)
    {
        abort_unless(auth()->user()->can('manage stock') || auth()->user()->hasAnyRole(['manager','admin']), 403);

        $data = $request->validate([
            'product_id' => ['nullable', 'exists:products,id'],
            'name' => ['required_without:product_id', 'string', 'max:255'],
            'quantity_used' => ['required', 'numeric', 'min:0.01'],
            'unit_cost' => ['required', 'numeric', 'min:0'],
            'usage_date' => ['required', 'date'],
            'category' => ['nullable', 'string', 'max:255'],
            'description' => ['nullable', 'string', 'max:255'],
        ]);

        // Criar produto se não existir e nome informado
        $productId = $data['product_id'] ?? null;
        if (!$productId && !empty($data['name'])) {
            $product = Product::create([
                'name' => $data['name'],
                'price' => $data['unit_cost'],
                'status' => 'active',
            ]);
            $productId = $product->id;
        }

        $totalCost = $data['unit_cost'] * $data['quantity_used'];

        DB::beginTransaction();
        try {
            // Se há product_id, diminuir estoque e criar movimento
            if ($productId) {
                $product = Product::findOrFail($productId);
                $previousStock = $product->stock;
                $quantityUsed = $data['quantity_used'];
                
                // Verificar se há estoque suficiente
                if ($previousStock < $quantityUsed) {
                    return back()->withInput()->with('error', "Estoque insuficiente. Disponível: {$previousStock} {$product->unit_label}");
                }
                
                // Diminuir estoque
                $product->stock = max(0, $previousStock - $quantityUsed);
                $product->save();
                
                // Criar movimento de saída
                \App\Models\StockMovement::create([
                    'product_id' => $productId,
                    'project_id' => $project->id,
                    'user_id' => auth()->id(),
                    'type' => 'saida',
                    'quantity' => $quantityUsed,
                    'cost_price' => $product->cost_price ?? $data['unit_cost'],
                    'previous_stock' => $previousStock,
                    'new_stock' => $product->stock,
                    'notes' => $data['description'] ?: "Uso de material na obra {$project->code}",
                ]);
            }

            ProjectFinancialBalance::create([
                'project_id' => $project->id,
                'product_id' => $productId,
                'material_request_id' => null,
                'quantity_used' => $data['quantity_used'],
                'unit_cost' => $data['unit_cost'],
                'total_cost' => $totalCost,
                'usage_date' => $data['usage_date'],
                'category' => $data['category'] ?? null,
                'description' => $data['description'] ?: 'Lançamento manual de material na obra',
            ]);

            DB::commit();
            return redirect()->route('projects.show', $project)
                ->with('success', 'Material registrado e estoque atualizado.');
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->withInput()->with('error', 'Erro ao registrar material: ' . $e->getMessage());
        }
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
        
        try {
            $data = $request->validate([
                'client_id' => ['required', 'exists:clients,id'],
                'inspection_id' => ['nullable', 'exists:inspections,id'],
                'version' => ['required', 'integer', 'min:1'],
                'address' => ['nullable', 'string', 'max:255'],
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
            ], [
                'client_id.required' => 'Por favor, selecione um cliente.',
                'client_id.exists' => 'O cliente selecionado não existe.',
                'version.required' => 'A versão do orçamento é obrigatória.',
                'version.integer' => 'A versão deve ser um número inteiro.',
                'version.min' => 'A versão deve ser pelo menos 1.',
                'status.required' => 'O status do orçamento é obrigatório.',
                'status.in' => 'O status selecionado é inválido.',
                'items.required' => 'É necessário adicionar pelo menos um item ao orçamento.',
                'items.min' => 'É necessário adicionar pelo menos um item ao orçamento.',
                'items.*.item_type.required' => 'O tipo do item é obrigatório.',
                'items.*.item_type.in' => 'O tipo do item deve ser produto, serviço ou mão de obra.',
                'items.*.description.required' => 'A descrição do item é obrigatória.',
                'items.*.unit_price.required' => 'O preço unitário do item é obrigatório.',
                'items.*.unit_price.numeric' => 'O preço unitário deve ser um número.',
                'items.*.unit_price.min' => 'O preço unitário deve ser maior ou igual a zero.',
            ]);
        } catch (\Illuminate\Validation\ValidationException $e) {
            Log::error('Erro de validação ao criar orçamento', [
                'errors' => $e->errors(),
                'request_data' => $request->all()
            ]);
            return back()->withInput()->withErrors($e->errors());
        }

        // Calculate subtotal e total com base no tipo de item
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
                'address' => $data['address'] ?? null,
                'subtotal' => $subtotal,
                'discount' => $discount,
                'total' => $total,
                'status' => $data['status'],
                'notes' => $data['notes'] ?? null,
                'sent_at' => $data['status'] === 'under_review' ? now() : null,
                'approved_at' => $data['status'] === 'approved' ? now() : null,
                'approved_by' => $data['status'] === 'approved' ? auth()->id() : null,
            ]);

            // Se uma vistoria foi selecionada, vincular também o orçamento à vistoria
            if (!empty($data['inspection_id'])) {
                $inspection = Inspection::find($data['inspection_id']);
                if ($inspection && !$inspection->budget_id) {
                    $inspection->update(['budget_id' => $budget->id]);
                }
            }

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

            // Sync product reservations for active budgets
            $this->syncProductReservations($budget, $data['items']);

            DB::commit();
            
            // Enviar e-mail para o cliente com o orçamento recém-criado
            try {
                Log::info('[BUDGET EMAIL] Iniciando processo de envio de email de orçamento', [
                    'budget_id' => $budget->id,
                    'client_id' => $budget->client_id,
                ]);
                
                $budget->loadMissing(['client', 'items', 'inspection']);
                $clientEmail = $budget->client?->email ?? $budget->client?->user?->email;
                
                Log::info('[BUDGET EMAIL] Email do cliente identificado', [
                    'budget_id' => $budget->id,
                    'client_email' => $clientEmail,
                    'client_id' => $budget->client_id,
                    'has_client' => $budget->client !== null,
                    'has_client_user' => $budget->client?->user !== null,
                ]);
                
                if ($clientEmail) {
                    // Aplicar configurações de email definidas em /admin/email
                    Log::info('[BUDGET EMAIL] Aplicando configurações de email', [
                        'budget_id' => $budget->id,
                    ]);
                    
                    if (class_exists(\App\Http\Controllers\AdminController::class) && method_exists(\App\Http\Controllers\AdminController::class, 'applyEmailSettings')) {
                        \App\Http\Controllers\AdminController::applyEmailSettings();
                        Log::info('[BUDGET EMAIL] Configurações de email aplicadas', [
                            'budget_id' => $budget->id,
                            'mail_default' => config('mail.default'),
                            'mail_from_address' => config('mail.from.address'),
                        ]);
                    } else {
                        Log::warning('[BUDGET EMAIL] AdminController::applyEmailSettings não encontrado', [
                            'budget_id' => $budget->id,
                        ]);
                    }
                    
                    Log::info('[BUDGET EMAIL] Criando instância do mailable', [
                        'budget_id' => $budget->id,
                        'email' => $clientEmail,
                    ]);
                    
                    $mailable = new BudgetClientRequestNotification($budget);
                    
                    Log::info('[BUDGET EMAIL] Enviando email via Mail::to()->send()', [
                        'budget_id' => $budget->id,
                        'email' => $clientEmail,
                    ]);
                    
                    Mail::to($clientEmail)->send($mailable);
                    
                    // Criar notificação para o cliente
                    NotificationService::createBudgetSentNotification($budget);
                    
                    Log::info('[BUDGET EMAIL] Email de orçamento enviado com SUCESSO para o cliente', [
                        'budget_id' => $budget->id,
                        'client_id' => $budget->client_id,
                        'email' => $clientEmail,
                    ]);
                } else {
                    Log::warning('[BUDGET EMAIL] Não foi possível enviar email de orçamento: cliente sem email definido', [
                        'budget_id' => $budget->id,
                        'client_id' => $budget->client_id,
                        'client_email' => $budget->client?->email,
                        'client_user_email' => $budget->client?->user?->email,
                    ]);
                }
            } catch (\Exception $mailException) {
                Log::error('[BUDGET EMAIL] ERRO ao enviar email de orçamento para o cliente', [
                    'budget_id' => $budget->id ?? null,
                    'client_id' => $data['client_id'] ?? null,
                    'email' => $clientEmail ?? null,
                    'error_message' => $mailException->getMessage(),
                    'error_file' => $mailException->getFile(),
                    'error_line' => $mailException->getLine(),
                    'error_trace' => $mailException->getTraceAsString(),
                ]);
            }
            
            Log::info('Orçamento criado com sucesso', [
                'budget_id' => $budget->id,
                'client_id' => $budget->client_id,
                'total' => $budget->total,
                'items_count' => $budget->items()->count()
            ]);
            
            $message = 'Orçamento criado com sucesso!';
            if ($data['status'] === 'approved' && isset($project)) {
                $message .= ' Projeto criado automaticamente com OS número ' . $project->os_number . '.';
            }
            return redirect()->route('budgets.index')->with('success', $message);
        } catch (\Exception $e) {
            DB::rollBack();
            
            Log::error('Erro ao criar orçamento', [
                'message' => $e->getMessage(),
                'trace' => $e->getTraceAsString(),
                'request_data' => $request->except(['_token'])
            ]);
            
            $errorMessage = 'Erro ao criar orçamento. ';
            if (config('app.debug')) {
                $errorMessage .= $e->getMessage();
            } else {
                $errorMessage .= 'Por favor, verifique os dados e tente novamente.';
            }
            
            return back()->withInput()->with('error', $errorMessage);
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
        $oldVersion = $budget->version;

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

        $oldVersion = $budget->version;

        DB::beginTransaction();
        try {
            // Check if status changed to approved
            $wasApproved = $budget->status !== 'approved' && $data['status'] === 'approved';
            
            $budget->update([
                'client_id' => $data['client_id'],
                'inspection_id' => $data['inspection_id'] ?? null,
                'version' => $data['version'],
                'address' => $data['address'] ?? null,
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
            $project = $budget->project;
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

            // Sync product reservations for active budgets
            $this->syncProductReservations($budget, $data['items']);

            // Se orçamento acabou de ser aprovado e há projeto, garantir conta a receber
            if ($wasApproved && $project) {
                $this->ensureReceivableForApprovedBudget($budget, $project);
            }

            DB::commit();
            
            // Reenviar automaticamente se a versão foi alterada
            if ((int) $data['version'] !== (int) $oldVersion) {
                try {
                    Log::info('[BUDGET EMAIL] Versão alterada, iniciando reenvio automático', [
                        'budget_id' => $budget->id,
                        'old_version' => $oldVersion,
                        'new_version' => $data['version'],
                    ]);
                    
                    $budget->loadMissing(['client', 'items', 'inspection']);
                    $clientEmail = $budget->client?->email ?? $budget->client?->user?->email;
                    
                    Log::info('[BUDGET EMAIL] Email do cliente identificado (reenvio automático)', [
                        'budget_id' => $budget->id,
                        'client_email' => $clientEmail,
                    ]);
                    
                    if ($clientEmail) {
                        // Aplicar configurações de email definidas em /admin/email
                        if (class_exists(\App\Http\Controllers\AdminController::class) && method_exists(\App\Http\Controllers\AdminController::class, 'applyEmailSettings')) {
                            \App\Http\Controllers\AdminController::applyEmailSettings();
                            Log::info('[BUDGET EMAIL] Configurações de email aplicadas (reenvio automático)', [
                                'budget_id' => $budget->id,
                                'mail_default' => config('mail.default'),
                            ]);
                        }
                        
                        Log::info('[BUDGET EMAIL] Enviando email via Mail::to()->send() (reenvio automático)', [
                            'budget_id' => $budget->id,
                            'email' => $clientEmail,
                        ]);
                        
                        Mail::to($clientEmail)->send(new BudgetClientRequestNotification($budget));
                        
                        // Criar notificação para o cliente
                        NotificationService::createBudgetSentNotification($budget);
                        
                        Log::info('[BUDGET EMAIL] Email de orçamento reenviado automaticamente com SUCESSO após alteração de versão', [
                            'budget_id' => $budget->id,
                            'client_id' => $budget->client_id,
                            'old_version' => $oldVersion,
                            'new_version' => $data['version'],
                            'email' => $clientEmail,
                        ]);
                    } else {
                        Log::warning('[BUDGET EMAIL] Não foi possível reenviar email de orçamento após alteração de versão: cliente sem email definido', [
                            'budget_id' => $budget->id,
                            'client_id' => $budget->client_id,
                            'old_version' => $oldVersion,
                            'new_version' => $data['version'],
                        ]);
                    }
                } catch (\Exception $mailException) {
                    Log::error('[BUDGET EMAIL] ERRO ao reenviar email de orçamento após alteração de versão', [
                        'budget_id' => $budget->id,
                        'client_id' => $data['client_id'] ?? null,
                        'old_version' => $oldVersion,
                        'new_version' => $data['version'],
                        'email' => $clientEmail ?? null,
                        'error_message' => $mailException->getMessage(),
                        'error_file' => $mailException->getFile(),
                        'error_line' => $mailException->getLine(),
                        'error_trace' => $mailException->getTraceAsString(),
                    ]);
                }
            }
            
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
     * Reenviar orçamento manualmente para o cliente.
     */
    public function budgetsResend(ProjectBudget $budget)
    {
        abort_unless(auth()->user()->can('manage budgets') || auth()->user()->hasAnyRole(['manager','admin']), 403);

        try {
            Log::info('[BUDGET EMAIL] Iniciando reenvio manual de email de orçamento', [
                'budget_id' => $budget->id,
                'client_id' => $budget->client_id,
                'user_id' => auth()->id(),
            ]);
            
            $budget->loadMissing(['client', 'items', 'inspection']);
            $clientEmail = $budget->client?->email ?? $budget->client?->user?->email;

            Log::info('[BUDGET EMAIL] Email do cliente identificado (reenvio manual)', [
                'budget_id' => $budget->id,
                'client_email' => $clientEmail,
                'has_client' => $budget->client !== null,
                'has_client_user' => $budget->client?->user !== null,
            ]);

            if (!$clientEmail) {
                Log::warning('[BUDGET EMAIL] Cliente sem email definido (reenvio manual)', [
                    'budget_id' => $budget->id,
                    'client_id' => $budget->client_id,
                ]);
                return back()->with('error', 'Não foi possível reenviar o orçamento: o cliente não possui e-mail configurado.');
            }

            // Aplicar configurações de email definidas em /admin/email
            Log::info('[BUDGET EMAIL] Aplicando configurações de email (reenvio manual)', [
                'budget_id' => $budget->id,
            ]);
            
            if (class_exists(\App\Http\Controllers\AdminController::class) && method_exists(\App\Http\Controllers\AdminController::class, 'applyEmailSettings')) {
                \App\Http\Controllers\AdminController::applyEmailSettings();
                Log::info('[BUDGET EMAIL] Configurações de email aplicadas (reenvio manual)', [
                    'budget_id' => $budget->id,
                    'mail_default' => config('mail.default'),
                    'mail_from_address' => config('mail.from.address'),
                ]);
            } else {
                Log::warning('[BUDGET EMAIL] AdminController::applyEmailSettings não encontrado (reenvio manual)', [
                    'budget_id' => $budget->id,
                ]);
            }

            Log::info('[BUDGET EMAIL] Criando instância do mailable (reenvio manual)', [
                'budget_id' => $budget->id,
                'email' => $clientEmail,
            ]);
            
            $mailable = new BudgetClientRequestNotification($budget);
            
            Log::info('[BUDGET EMAIL] Enviando email via Mail::to()->send() (reenvio manual)', [
                'budget_id' => $budget->id,
                'email' => $clientEmail,
            ]);

            Mail::to($clientEmail)->send($mailable);
            
            // Criar notificação para o cliente
            NotificationService::createBudgetSentNotification($budget);

            Log::info('[BUDGET EMAIL] Email de orçamento reenviado manualmente com SUCESSO para o cliente', [
                'budget_id' => $budget->id,
                'client_id' => $budget->client_id,
                'email' => $clientEmail,
            ]);

            return back()->with('success', 'Orçamento reenviado para o cliente com sucesso.');
        } catch (\Exception $e) {
            Log::error('[BUDGET EMAIL] ERRO ao reenviar orçamento para o cliente (reenvio manual)', [
                'budget_id' => $budget->id,
                'client_id' => $budget->client_id,
                'email' => $clientEmail ?? null,
                'error_message' => $e->getMessage(),
                'error_file' => $e->getFile(),
                'error_line' => $e->getLine(),
                'error_trace' => $e->getTraceAsString(),
            ]);

            return back()->with('error', 'Erro ao reenviar orçamento para o cliente: ' . $e->getMessage());
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

            $project = null;

            // Create project automatically if it doesn't exist
            if (!$budget->project_id) {
                $project = Project::createFromApprovedBudget($budget);
                $message = 'Orçamento aprovado com sucesso! Projeto criado automaticamente com OS número ' . $project->os_number . '.';
            } else {
                // If project already exists, just assign OS number
                $budget->project->assignOsNumber();
                $project = $budget->project;
                $message = 'Orçamento aprovado com sucesso! OS número ' . $budget->project->os_number . ' foi gerado.';
            }

            // Criar conta a receber padrão para alimentar o painel financeiro
            if ($project) {
                $this->ensureReceivableForApprovedBudget($budget, $project);
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
     * Show budget for client (with approve/reject actions)
     */
    public function budgetsShowForClient(ProjectBudget $budget)
    {
        $client = Client::where('user_id', auth()->id())->first();
        
        abort_unless($client && $budget->client_id === $client->id, 403);
        
        $budget->loadMissing(['client', 'items', 'inspection']);
        
        return view('budgets.client-actions', compact('budget', 'client'));
    }

    /**
     * Approve budget by client
     */
    public function budgetsApproveByClient(Request $request, ProjectBudget $budget)
    {
        $client = Client::where('user_id', auth()->id())->first();
        
        abort_unless($client && $budget->client_id === $client->id, 403);
        
        if ($budget->status === 'approved') {
            return back()->with('error', 'Este orçamento já está aprovado.');
        }

        if ($budget->status === 'cancelled') {
            return back()->with('error', 'Não é possível aprovar um orçamento cancelado.');
        }

        DB::beginTransaction();
        try {
            $budget->update([
                'status' => 'approved',
                'approved_at' => now(),
                'approved_by' => auth()->id(),
            ]);

            // Create project automatically if it doesn't exist
            $project = $budget->project;
            if (!$budget->project_id) {
                $project = Project::createFromApprovedBudget($budget);
            }

            // Garantir conta a receber vinculada ao orçamento aprovado
            if ($project) {
                $this->ensureReceivableForApprovedBudget($budget, $project);
            }

            DB::commit();
            
            // Criar notificação para admins/gerentes
            NotificationService::createBudgetApprovedByClientNotification($budget);
            
            // Enviar email para admins/gerentes
            try {
                if (class_exists(\App\Http\Controllers\AdminController::class) && method_exists(\App\Http\Controllers\AdminController::class, 'applyEmailSettings')) {
                    \App\Http\Controllers\AdminController::applyEmailSettings();
                }
                
                try {
                    $admins = \App\Models\User::role(['admin', 'manager'])->get();
                } catch (\Exception $e) {
                    $admins = \App\Models\User::whereHas('roles', function ($query) {
                        $query->whereIn('name', ['admin', 'manager']);
                    })->get();
                }
                
                foreach ($admins as $admin) {
                    if ($admin->email) {
                        Mail::to($admin->email)->send(new BudgetApprovedByClientNotification($budget));
                    }
                }
            } catch (\Exception $e) {
                Log::error('Erro ao enviar email de aprovação pelo cliente', [
                    'budget_id' => $budget->id,
                    'error' => $e->getMessage(),
                ]);
            }
            
            return redirect()->route('client.dashboard')->with('success', 'Orçamento aprovado com sucesso! O projeto será criado automaticamente.');
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Erro ao aprovar orçamento pelo cliente', [
                'budget_id' => $budget->id,
                'client_id' => $client->id,
                'error' => $e->getMessage(),
            ]);
            return back()->with('error', 'Erro ao aprovar orçamento: ' . $e->getMessage());
        }
    }

    /**
     * Reject budget by client
     */
    public function budgetsRejectByClient(Request $request, ProjectBudget $budget)
    {
        $client = Client::where('user_id', auth()->id())->first();
        
        abort_unless($client && $budget->client_id === $client->id, 403);
        
        if ($budget->status === 'approved') {
            return back()->with('error', 'Não é possível rejeitar um orçamento já aprovado.');
        }

        if ($budget->status === 'cancelled') {
            return back()->with('error', 'Não é possível rejeitar um orçamento cancelado.');
        }

        $validated = $request->validate([
            'rejection_reason' => 'nullable|string|max:1000',
        ]);

        DB::beginTransaction();
        try {
            $budget->update([
                'status' => 'rejected',
                'rejected_at' => now(),
                'rejected_by' => auth()->id(),
                'rejection_reason' => $validated['rejection_reason'] ?? null,
            ]);

            DB::commit();
            
            // Criar notificação para admins/gerentes
            NotificationService::createBudgetRejectedByClientNotification($budget);
            
            // Enviar email para admins/gerentes
            try {
                if (class_exists(\App\Http\Controllers\AdminController::class) && method_exists(\App\Http\Controllers\AdminController::class, 'applyEmailSettings')) {
                    \App\Http\Controllers\AdminController::applyEmailSettings();
                }
                
                try {
                    $admins = \App\Models\User::role(['admin', 'manager'])->get();
                } catch (\Exception $e) {
                    $admins = \App\Models\User::whereHas('roles', function ($query) {
                        $query->whereIn('name', ['admin', 'manager']);
                    })->get();
                }
                
                foreach ($admins as $admin) {
                    if ($admin->email) {
                        Mail::to($admin->email)->send(new BudgetRejectedByClientNotification($budget));
                    }
                }
            } catch (\Exception $e) {
                Log::error('Erro ao enviar email de rejeição pelo cliente', [
                    'budget_id' => $budget->id,
                    'error' => $e->getMessage(),
                ]);
            }
            
            return redirect()->route('client.dashboard')->with('success', 'Orçamento rejeitado. Sua contestação foi registrada.');
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Erro ao rejeitar orçamento pelo cliente', [
                'budget_id' => $budget->id,
                'client_id' => $client->id,
                'error' => $e->getMessage(),
            ]);
            return back()->with('error', 'Erro ao rejeitar orçamento: ' . $e->getMessage());
        }
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

    /**
     * Convert product reservations to stock movements when project starts.
     * This decreases physical stock and creates movement records.
     * 
     * @param Project $project
     */
    private function convertReservationsToStockMovements(Project $project): void
    {
        // Find approved budget for this project
        $budget = $project->budgets()
            ->where('status', ProjectBudget::STATUS_APPROVED)
            ->latest()
            ->first();

        if (!$budget) {
            return;
        }

        $reservations = ProductReservation::where('project_budget_id', $budget->id)
            ->with('product')
            ->get();

        DB::beginTransaction();
        try {
            foreach ($reservations as $reservation) {
                $product = $reservation->product;
                if (!$product) {
                    continue;
                }

                $quantity = $reservation->quantity_reserved;
                $previousStock = $product->stock;

                // Verificar se há estoque suficiente
                if ($previousStock < $quantity) {
                    Log::warning('Estoque insuficiente ao converter reserva em baixa', [
                        'project_id' => $project->id,
                        'product_id' => $product->id,
                        'reserved' => $quantity,
                        'available' => $previousStock,
                    ]);
                    // Continuar mesmo assim, mas registrar o problema
                }

                // Diminuir estoque
                $product->stock = max(0, $previousStock - $quantity);
                $product->save();

                // Criar movimento de saída
                \App\Models\StockMovement::create([
                    'product_id' => $product->id,
                    'project_id' => $project->id,
                    'user_id' => auth()->id(),
                    'type' => 'saida',
                    'quantity' => $quantity,
                    'cost_price' => $product->cost_price ?? 0,
                    'previous_stock' => $previousStock,
                    'new_stock' => $product->stock,
                    'notes' => "Baixa automática ao iniciar obra {$project->code} (reserva do orçamento #{$budget->id})",
                ]);
            }

            DB::commit();
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Erro ao converter reservas em baixas de estoque', [
                'project_id' => $project->id,
                'error' => $e->getMessage(),
            ]);
        }
    }

    /**
     * Sync product reservations for a budget.
     * Only reserves stock for active budgets (pending, under_review, approved).
     * 
     * @param ProjectBudget $budget
     * @param array $items Array of item data from the request
     */
    private function syncProductReservations(ProjectBudget $budget, array $items): void
    {
        // Only sync reservations for active budgets
        $activeStatuses = [
            ProjectBudget::STATUS_PENDING,
            ProjectBudget::STATUS_UNDER_REVIEW,
            ProjectBudget::STATUS_APPROVED,
        ];

        if (!in_array($budget->status, $activeStatuses)) {
            // If budget is not active, delete all reservations
            ProductReservation::where('project_budget_id', $budget->id)->delete();
            return;
        }

        // Delete all existing reservations for this budget
        ProductReservation::where('project_budget_id', $budget->id)->delete();

        // Group product items by product_id and sum quantities
        $productQuantities = [];
        foreach ($items as $item) {
            if ($item['item_type'] === 'product' && !empty($item['product_id'])) {
                $productId = $item['product_id'];
                $quantity = floatval($item['quantity'] ?? 0);
                
                if ($quantity > 0) {
                    if (!isset($productQuantities[$productId])) {
                        $productQuantities[$productId] = 0;
                    }
                    $productQuantities[$productId] += $quantity;
                }
            }
        }

        // Create reservations for each product
        foreach ($productQuantities as $productId => $totalQuantity) {
            ProductReservation::create([
                'product_id' => $productId,
                'project_budget_id' => $budget->id,
                'quantity_reserved' => $totalQuantity,
            ]);
        }
    }
}


