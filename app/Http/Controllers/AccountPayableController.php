<?php

namespace App\Http\Controllers;

use App\Models\AccountPayable;
use App\Models\Supplier;
use App\Models\Project;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class AccountPayableController extends Controller
{
    public function index()
    {
        $accountPayables = AccountPayable::with(['supplier', 'project', 'user'])
            ->latest()
            ->paginate(15);

        return view('financial.accounts-payable.index', compact('accountPayables'));
    }

    public function create()
    {
        $suppliers = Supplier::orderBy('company_name')->get();
        $projects = Project::orderBy('name')->get();
        return view('financial.accounts-payable.create', compact('suppliers', 'projects'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'supplier_id' => 'nullable|exists:suppliers,id',
            'project_id' => 'nullable|exists:projects,id',
            'description' => 'required|string|max:255',
            'category' => 'nullable|string|max:255',
            'amount' => 'required|numeric|min:0',
            'due_date' => 'required|date',
            'status' => 'required|in:pending,paid,overdue,cancelled',
            'notes' => 'nullable|string',
        ]);

        DB::beginTransaction();
        try {
            $accountPayable = AccountPayable::create([
                ...$validated,
                'number' => (new AccountPayable())->generateNumber(),
                'user_id' => auth()->id(),
                'paid_date' => $validated['status'] === 'paid' ? now() : null,
            ]);

            DB::commit();
            return redirect()->route('financial.accounts-payable.index')
                ->with('success', 'Conta a pagar criada com sucesso!');

        } catch (\Exception $e) {
            DB::rollBack();
            return back()->withInput()->with('error', 'Erro ao criar conta a pagar: ' . $e->getMessage());
        }
    }

    public function show(AccountPayable $accountPayable)
    {
        $accountPayable->load(['supplier', 'project', 'user']);
        return view('financial.accounts-payable.show', compact('accountPayable'));
    }

    public function edit(AccountPayable $accountPayable)
    {
        $suppliers = Supplier::orderBy('company_name')->get();
        $projects = Project::orderBy('name')->get();
        return view('financial.accounts-payable.edit', compact('accountPayable', 'suppliers', 'projects'));
    }

    public function update(Request $request, AccountPayable $accountPayable)
    {
        $validated = $request->validate([
            'supplier_id' => 'nullable|exists:suppliers,id',
            'project_id' => 'nullable|exists:projects,id',
            'description' => 'required|string|max:255',
            'category' => 'nullable|string|max:255',
            'amount' => 'required|numeric|min:0',
            'due_date' => 'required|date',
            'status' => 'required|in:pending,paid,overdue,cancelled',
            'notes' => 'nullable|string',
        ]);

        DB::beginTransaction();
        try {
            $accountPayable->update([
                ...$validated,
                'paid_date' => $validated['status'] === 'paid' ? ($accountPayable->paid_date ?? now()) : null,
            ]);

            DB::commit();
            return redirect()->route('financial.accounts-payable.index')
                ->with('success', 'Conta a pagar atualizada com sucesso!');

        } catch (\Exception $e) {
            DB::rollBack();
            return back()->withInput()->with('error', 'Erro ao atualizar conta a pagar: ' . $e->getMessage());
        }
    }

    public function destroy(AccountPayable $accountPayable)
    {
        try {
            $accountPayable->delete();
            return redirect()->route('financial.accounts-payable.index')
                ->with('success', 'Conta a pagar excluída com sucesso!');
        } catch (\Exception $e) {
            return back()->with('error', 'Erro ao excluir conta a pagar: ' . $e->getMessage());
        }
    }
}
