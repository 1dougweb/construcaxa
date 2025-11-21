<?php

namespace App\Http\Controllers;

use App\Models\Invoice;
use App\Models\Project;
use App\Models\ProjectBudget;
use App\Models\User;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;

class InvoiceController extends Controller
{
    public function index()
    {
        $invoices = Invoice::with(['client', 'project', 'budget', 'user'])
            ->latest()
            ->paginate(15);

        return view('financial.invoices.index', compact('invoices'));
    }

    public function create()
    {
        $clients = User::whereHas('roles', function($q) {
            $q->where('name', 'client');
        })->orderBy('name')->get();
        $projects = Project::orderBy('name')->get();
        $budgets = ProjectBudget::where('status', ProjectBudget::STATUS_APPROVED)
            ->with('project')
            ->orderBy('created_at', 'desc')
            ->get();
        return view('financial.invoices.create', compact('clients', 'projects', 'budgets'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'project_id' => 'nullable|exists:projects,id',
            'client_id' => 'required|exists:users,id',
            'budget_id' => 'nullable|exists:project_budgets,id',
            'issue_date' => 'required|date',
            'due_date' => 'required|date|after_or_equal:issue_date',
            'subtotal' => 'required|numeric|min:0',
            'tax_amount' => 'nullable|numeric|min:0',
            'status' => 'required|in:draft,issued,paid,cancelled',
            'notes' => 'nullable|string',
        ]);

        DB::beginTransaction();
        try {
            $invoice = Invoice::create([
                ...$validated,
                'number' => (new Invoice())->generateNumber(),
                'total_amount' => $validated['subtotal'] + ($validated['tax_amount'] ?? 0),
                'user_id' => auth()->id(),
            ]);

            DB::commit();
            return redirect()->route('financial.invoices.index')
                ->with('success', 'Nota fiscal criada com sucesso!');

        } catch (\Exception $e) {
            DB::rollBack();
            return back()->withInput()->with('error', 'Erro ao criar nota fiscal: ' . $e->getMessage());
        }
    }

    public function show(Invoice $invoice)
    {
        $invoice->load(['client', 'project', 'budget', 'user', 'receipts']);
        return view('financial.invoices.show', compact('invoice'));
    }

    public function edit(Invoice $invoice)
    {
        $clients = User::whereHas('roles', function($q) {
            $q->where('name', 'client');
        })->orderBy('name')->get();
        $projects = Project::orderBy('name')->get();
        $budgets = ProjectBudget::where('status', ProjectBudget::STATUS_APPROVED)
            ->with('project')
            ->orderBy('created_at', 'desc')
            ->get();
        return view('financial.invoices.edit', compact('invoice', 'clients', 'projects', 'budgets'));
    }

    public function update(Request $request, Invoice $invoice)
    {
        $validated = $request->validate([
            'project_id' => 'nullable|exists:projects,id',
            'client_id' => 'required|exists:users,id',
            'budget_id' => 'nullable|exists:project_budgets,id',
            'issue_date' => 'required|date',
            'due_date' => 'required|date|after_or_equal:issue_date',
            'subtotal' => 'required|numeric|min:0',
            'tax_amount' => 'nullable|numeric|min:0',
            'status' => 'required|in:draft,issued,paid,cancelled',
            'notes' => 'nullable|string',
        ]);

        DB::beginTransaction();
        try {
            $invoice->update([
                ...$validated,
                'total_amount' => $validated['subtotal'] + ($validated['tax_amount'] ?? 0),
            ]);

            DB::commit();
            return redirect()->route('financial.invoices.index')
                ->with('success', 'Nota fiscal atualizada com sucesso!');

        } catch (\Exception $e) {
            DB::rollBack();
            return back()->withInput()->with('error', 'Erro ao atualizar nota fiscal: ' . $e->getMessage());
        }
    }

    public function destroy(Invoice $invoice)
    {
        try {
            $invoice->delete();
            return redirect()->route('financial.invoices.index')
                ->with('success', 'Nota fiscal excluída com sucesso!');
        } catch (\Exception $e) {
            return back()->with('error', 'Erro ao excluir nota fiscal: ' . $e->getMessage());
        }
    }

    public function generatePDF(Invoice $invoice)
    {
        $invoice->load(['client', 'project', 'budget']);
        
        $pdf = Pdf::loadView('financial.invoices.pdf', compact('invoice'));
        
        return $pdf->download("nota-fiscal-{$invoice->number}.pdf");
    }
}
