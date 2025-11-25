<?php

namespace App\Http\Controllers;

use App\Models\Employee;
use App\Models\EmployeeProposal;
use App\Models\EmployeeProposalItem;
use App\Models\Project;
use App\Models\LaborType;
use App\Models\Service;
use App\Models\ServiceCategory;
use App\Models\AccountPayable;
use App\Mail\EmployeeProposalNotification;
use App\Services\NotificationService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Mail;
use Illuminate\Validation\ValidationException;

class EmployeeProposalController extends Controller
{
    public function index(Request $request, Employee $employee = null)
    {
        if ($employee) {
            $proposals = $employee->proposals()
                ->with(['project', 'createdBy', 'items.laborType', 'items.service'])
                ->orderBy('created_at', 'desc')
                ->paginate(10);

            return view('employees.proposals.index', compact('employee', 'proposals'));
        }

        // Listagem geral de todas as propostas
        $proposals = EmployeeProposal::with(['employee.user', 'project', 'createdBy', 'items.laborType', 'items.service'])
            ->orderBy('created_at', 'desc')
            ->paginate(10);

        return view('proposals.index', compact('proposals'));
    }

    public function create(Employee $employee)
    {
        $projects = Project::orderBy('name')->get();
        $laborTypes = LaborType::active()->orderBy('name')->get();
        $services = Service::active()->with('category')->orderBy('name')->get();
        $serviceCategories = ServiceCategory::active()->orderBy('name')->get();

        return view('employees.proposals.create', compact(
            'employee',
            'projects',
            'laborTypes',
            'services',
            'serviceCategories'
        ));
    }

    public function store(Request $request, Employee $employee)
    {
        $validated = $request->validate([
            'project_id' => 'nullable|exists:projects,id',
            'hourly_rate' => 'required|numeric|min:0',
            'contract_type' => 'required|in:fixed_days,indefinite',
            'days' => 'nullable|integer|min:1|required_if:contract_type,fixed_days',
            'start_date' => 'nullable|date|required_if:contract_type,fixed_days',
            'end_date' => 'nullable|date|after_or_equal:start_date|required_if:contract_type,fixed_days',
            'observations' => 'nullable|string',
            'items' => 'required|array|min:1',
            'items.*.item_type' => 'required|in:labor,service',
            'items.*.labor_type_id' => 'nullable|required_if:items.*.item_type,labor|exists:labor_types,id',
            'items.*.service_id' => 'nullable|required_if:items.*.item_type,service|exists:services,id',
            'items.*.quantity' => 'required|numeric|min:0.01',
            'items.*.unit_price' => 'required|numeric|min:0',
        ]);

        DB::beginTransaction();
        try {
            // Criar proposta
            $proposal = EmployeeProposal::create([
                'employee_id' => $employee->id,
                'project_id' => $validated['project_id'] ?? null,
                'hourly_rate' => $validated['hourly_rate'],
                'contract_type' => $validated['contract_type'],
                'days' => $validated['days'] ?? null,
                'start_date' => $validated['start_date'] ?? null,
                'end_date' => $validated['end_date'] ?? null,
                'observations' => $validated['observations'] ?? null,
                'created_by' => auth()->id(),
            ]);

            // Criar itens
            foreach ($validated['items'] as $itemData) {
                EmployeeProposalItem::create([
                    'proposal_id' => $proposal->id,
                    'item_type' => $itemData['item_type'],
                    'labor_type_id' => $itemData['labor_type_id'] ?? null,
                    'service_id' => $itemData['service_id'] ?? null,
                    'quantity' => $itemData['quantity'],
                    'unit_price' => $itemData['unit_price'],
                ]);
            }

            // Recalcular total
            $proposal->refresh();
            $proposal->total_amount = $proposal->calculateTotalAmount();
            $proposal->save();

            // Enviar email
            try {
                Mail::to($employee->user->email)->send(new EmployeeProposalNotification($proposal));
            } catch (\Exception $e) {
                \Log::error('Erro ao enviar email de proposta: ' . $e->getMessage());
                // Não falhar a criação se o email falhar
            }

            DB::commit();
            return redirect()->route('employees.proposals.index', $employee)
                ->with('success', 'Proposta criada e enviada por email com sucesso!');
        } catch (\Exception $e) {
            DB::rollBack();
            throw ValidationException::withMessages(['error' => 'Erro ao criar proposta. ' . $e->getMessage()]);
        }
    }

    public function show(Employee $employee, EmployeeProposal $proposal)
    {
        if ($proposal->employee_id !== $employee->id) {
            abort(404);
        }

        $proposal->load(['items.laborType', 'items.service', 'project', 'createdBy', 'employee.user']);

        return view('employees.proposals.show', compact('employee', 'proposal'));
    }

    public function viewByToken($token)
    {
        $proposal = EmployeeProposal::where('token', $token)
            ->with(['items.laborType', 'items.service', 'project', 'employee.user'])
            ->firstOrFail();

        if ($proposal->isExpired() && !$proposal->isAccepted() && !$proposal->isRejected()) {
            $proposal->update(['status' => EmployeeProposal::STATUS_EXPIRED]);
        }

        return view('proposals.view', compact('proposal'));
    }

    public function accept(Request $request, $token)
    {
        $proposal = EmployeeProposal::where('token', $token)->firstOrFail();

        if (!$proposal->isPending()) {
            return redirect()->route('proposals.view', $token)
                ->with('error', 'Esta proposta já foi processada.');
        }

        if ($proposal->isExpired()) {
            return redirect()->route('proposals.view', $token)
                ->with('error', 'Esta proposta expirou.');
        }

        DB::beginTransaction();
        try {
            // Aceitar proposta
            $proposal->accept();

            // Vincular funcionário à obra se houver project_id
            if ($proposal->project_id) {
                $project = $proposal->project;
                if (!$project->employees()->where('employees.id', $proposal->employee_id)->exists()) {
                    $project->employees()->attach($proposal->employee_id, [
                        'role_on_project' => 'worker'
                    ]);
                }
            }

            // Criar AccountPayable
            $accountPayable = AccountPayable::create([
                'supplier_id' => null, // Funcionário não é fornecedor
                'project_id' => $proposal->project_id,
                'number' => (new AccountPayable)->generateNumber(),
                'description' => "Proposta aceita - Funcionário: {$proposal->employee->user->name}",
                'category' => 'labor',
                'amount' => $proposal->total_amount,
                'due_date' => $proposal->contract_type === EmployeeProposal::CONTRACT_TYPE_FIXED_DAYS 
                    ? ($proposal->end_date ?? now()->addDays(30))
                    : now()->addDays(30),
                'status' => AccountPayable::STATUS_PENDING,
                'notes' => "Proposta #{$proposal->id} - {$proposal->observations}",
                'user_id' => auth()->id() ?? $proposal->created_by,
            ]);

            DB::commit();
            
            // Criar notificação para o funcionário
            NotificationService::createProposalApprovalNotification($proposal);
            
            return redirect()->route('proposals.view', $token)
                ->with('success', 'Proposta aceita com sucesso! Você foi vinculado à obra.');
        } catch (\Exception $e) {
            DB::rollBack();
            \Log::error('Erro ao aceitar proposta: ' . $e->getMessage());
            return redirect()->route('proposals.view', $token)
                ->with('error', 'Erro ao processar aceitação da proposta.');
        }
    }

    public function reject(Request $request, $token)
    {
        $proposal = EmployeeProposal::where('token', $token)->firstOrFail();

        if (!$proposal->isPending()) {
            return redirect()->route('proposals.view', $token)
                ->with('error', 'Esta proposta já foi processada.');
        }

        $proposal->reject();

        return redirect()->route('proposals.view', $token)
            ->with('success', 'Proposta rejeitada.');
    }
}

