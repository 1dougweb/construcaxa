<?php

namespace App\Http\Controllers;

use App\Models\AccountReceivable;
use App\Models\Project;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class AccountReceivableController extends Controller
{
    public function index()
    {
        $accountReceivables = AccountReceivable::with(['client', 'project', 'user'])
            ->latest()
            ->paginate(15);

        return view('financial.accounts-receivable.index', compact('accountReceivables'));
    }

    public function create()
    {
        $clients = User::whereHas('roles', function($q) {
            $q->where('name', 'client');
        })->orderBy('name')->get();
        $projects = Project::orderBy('name')->get();
        return view('financial.accounts-receivable.create', compact('clients', 'projects'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'client_id' => 'nullable|exists:users,id',
            'project_id' => 'nullable|exists:projects,id',
            'description' => 'required|string|max:255',
            'amount' => 'required|numeric|min:0',
            'due_date' => 'required|date',
            'status' => 'required|in:pending,received,overdue,cancelled',
            'notes' => 'nullable|string',
        ]);

        DB::beginTransaction();
        try {
            $accountReceivable = AccountReceivable::create([
                ...$validated,
                'number' => (new AccountReceivable())->generateNumber(),
                'user_id' => auth()->id(),
                'received_date' => $validated['status'] === 'received' ? now() : null,
            ]);

            DB::commit();
            return redirect()->route('financial.accounts-receivable.index')
                ->with('success', 'Conta a receber criada com sucesso!');

        } catch (\Exception $e) {
            DB::rollBack();
            return back()->withInput()->with('error', 'Erro ao criar conta a receber: ' . $e->getMessage());
        }
    }

    public function show(AccountReceivable $accountReceivable)
    {
        $accountReceivable->load(['client', 'project', 'user']);
        return view('financial.accounts-receivable.show', compact('accountReceivable'));
    }

    public function edit(AccountReceivable $accountReceivable)
    {
        $clients = User::whereHas('roles', function($q) {
            $q->where('name', 'client');
        })->orderBy('name')->get();
        $projects = Project::orderBy('name')->get();
        return view('financial.accounts-receivable.edit', compact('accountReceivable', 'clients', 'projects'));
    }

    public function update(Request $request, AccountReceivable $accountReceivable)
    {
        $validated = $request->validate([
            'client_id' => 'nullable|exists:users,id',
            'project_id' => 'nullable|exists:projects,id',
            'description' => 'required|string|max:255',
            'amount' => 'required|numeric|min:0',
            'due_date' => 'required|date',
            'status' => 'required|in:pending,received,overdue,cancelled',
            'notes' => 'nullable|string',
        ]);

        DB::beginTransaction();
        try {
            $accountReceivable->update([
                ...$validated,
                'received_date' => $validated['status'] === 'received' ? ($accountReceivable->received_date ?? now()) : null,
            ]);

            DB::commit();
            return redirect()->route('financial.accounts-receivable.index')
                ->with('success', 'Conta a receber atualizada com sucesso!');

        } catch (\Exception $e) {
            DB::rollBack();
            return back()->withInput()->with('error', 'Erro ao atualizar conta a receber: ' . $e->getMessage());
        }
    }

    public function destroy(AccountReceivable $accountReceivable)
    {
        try {
            $accountReceivable->delete();
            return redirect()->route('financial.accounts-receivable.index')
                ->with('success', 'Conta a receber excluída com sucesso!');
        } catch (\Exception $e) {
            return back()->with('error', 'Erro ao excluir conta a receber: ' . $e->getMessage());
        }
    }
}
