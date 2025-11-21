<?php

namespace App\Http\Controllers;

use App\Models\Contract;
use App\Models\Client;
use App\Models\Project;
use App\Models\ProjectBudget;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class ContractController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $query = Contract::with(['client', 'project', 'budget']);

        // Filtros
        if ($request->filled('client_id')) {
            $query->where('client_id', $request->client_id);
        }

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('contract_number', 'like', "%{$search}%")
                  ->orWhere('title', 'like', "%{$search}%")
                  ->orWhereHas('client', function($q) use ($search) {
                      $q->where('name', 'like', "%{$search}%");
                  });
            });
        }

        $contracts = $query->latest()->paginate(15);
        $clients = Client::active()->orderBy('name')->get();

        return view('contracts.index', compact('contracts', 'clients'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create(Request $request)
    {
        $clients = Client::active()->orderBy('name')->get();
        $projects = Project::orderBy('name')->get();
        $budgets = ProjectBudget::where('status', 'approved')->orderBy('created_at', 'desc')->get();
        
        $selectedClient = $request->get('client_id') ? Client::find($request->get('client_id')) : null;

        return view('contracts.create', compact('clients', 'projects', 'budgets', 'selectedClient'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'client_id' => 'required|exists:clients,id',
            'project_id' => 'nullable|exists:projects,id',
            'budget_id' => 'nullable|exists:project_budgets,id',
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
            'start_date' => 'nullable|date',
            'end_date' => 'nullable|date|after_or_equal:start_date',
            'value' => 'nullable|numeric|min:0',
            'status' => 'required|in:draft,active,expired,cancelled',
            'file' => 'nullable|mimes:pdf|max:10240', // 10MB
            'signed_at' => 'nullable|date',
            'notes' => 'nullable|string',
        ]);

        // Gerar número do contrato
        $validated['contract_number'] = Contract::generateContractNumber();

        // Upload do arquivo PDF
        if ($request->hasFile('file')) {
            $file = $request->file('file');
            $fileName = time() . '_' . $file->getClientOriginalName();
            $validated['file_path'] = $file->storeAs('contracts', $fileName, 'local');
        }

        try {
            Contract::create($validated);
            return redirect()->route('contracts.index')->with('success', 'Contrato criado com sucesso!');
        } catch (\Exception $e) {
            return back()->withInput()->with('error', 'Erro ao criar contrato: ' . $e->getMessage());
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(Contract $contract)
    {
        $contract->load(['client', 'project', 'budget']);
        return view('contracts.show', compact('contract'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Contract $contract)
    {
        $clients = Client::active()->orderBy('name')->get();
        $projects = Project::orderBy('name')->get();
        $budgets = ProjectBudget::where('status', 'approved')->orderBy('created_at', 'desc')->get();
        
        return view('contracts.edit', compact('contract', 'clients', 'projects', 'budgets'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Contract $contract)
    {
        $validated = $request->validate([
            'client_id' => 'required|exists:clients,id',
            'project_id' => 'nullable|exists:projects,id',
            'budget_id' => 'nullable|exists:project_budgets,id',
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
            'start_date' => 'nullable|date',
            'end_date' => 'nullable|date|after_or_equal:start_date',
            'value' => 'nullable|numeric|min:0',
            'status' => 'required|in:draft,active,expired,cancelled',
            'file' => 'nullable|mimes:pdf|max:10240', // 10MB
            'signed_at' => 'nullable|date',
            'notes' => 'nullable|string',
        ]);

        // Upload do arquivo PDF (se novo arquivo foi enviado)
        if ($request->hasFile('file')) {
            // Deletar arquivo antigo se existir
            if ($contract->file_path && Storage::disk('local')->exists($contract->file_path)) {
                Storage::disk('local')->delete($contract->file_path);
            }

            $file = $request->file('file');
            $fileName = time() . '_' . $file->getClientOriginalName();
            $validated['file_path'] = $file->storeAs('contracts', $fileName, 'local');
        }

        try {
            $contract->update($validated);
            return redirect()->route('contracts.show', $contract)->with('success', 'Contrato atualizado com sucesso!');
        } catch (\Exception $e) {
            return back()->withInput()->with('error', 'Erro ao atualizar contrato: ' . $e->getMessage());
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Contract $contract)
    {
        // Deletar arquivo se existir
        if ($contract->file_path && Storage::disk('local')->exists($contract->file_path)) {
            Storage::disk('local')->delete($contract->file_path);
        }

        $contract->delete();
        return redirect()->route('contracts.index')->with('success', 'Contrato excluído com sucesso!');
    }

    /**
     * Download do arquivo PDF do contrato
     */
    public function download(Contract $contract)
    {
        if (!$contract->file_path || !Storage::disk('local')->exists($contract->file_path)) {
            return back()->with('error', 'Arquivo não encontrado.');
        }

        return Storage::disk('local')->download($contract->file_path, $contract->contract_number . '.pdf');
    }
}
