<?php

namespace App\Http\Controllers;

use App\Models\Receipt;
use App\Models\Invoice;
use App\Models\Project;
use App\Models\Client;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ReceiptController extends Controller
{
    public function index()
    {
        $receipts = Receipt::with(['client', 'project', 'invoice', 'user'])
            ->latest()
            ->paginate(15);

        return view('financial.receipts.index', compact('receipts'));
    }

    public function create()
    {
        $clients = Client::active()->orderBy('name')->get();
        $projects = Project::orderBy('name')->get();
        $invoices = Invoice::where('status', '!=', 'cancelled')
            ->with('client')
            ->orderBy('created_at', 'desc')
            ->get();
        return view('financial.receipts.create', compact('clients', 'projects', 'invoices'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'project_id' => 'nullable|exists:projects,id',
            'client_id' => 'required|exists:clients,id',
            'invoice_id' => 'nullable|exists:invoices,id',
            'issue_date' => 'required|date',
            'amount' => 'required|numeric|min:0',
            'payment_method' => 'required|in:cash,pix,credit_card,debit_card,bank_transfer,check,other',
            'description' => 'nullable|string|max:255',
            'notes' => 'nullable|string',
        ]);

        DB::beginTransaction();
        try {
            $receipt = Receipt::create([
                ...$validated,
                'number' => (new Receipt())->generateNumber(),
                'user_id' => auth()->id(),
            ]);

            // Se vinculado a uma nota fiscal, atualizar status
            if ($receipt->invoice_id) {
                $invoice = Invoice::find($receipt->invoice_id);
                if ($invoice && $receipt->amount >= $invoice->total_amount) {
                    $invoice->update(['status' => Invoice::STATUS_PAID]);
                }
            }

            DB::commit();
            return redirect()->route('financial.receipts.index')
                ->with('success', 'Recibo criado com sucesso!');

        } catch (\Exception $e) {
            DB::rollBack();
            return back()->withInput()->with('error', 'Erro ao criar recibo: ' . $e->getMessage());
        }
    }

    public function show(Receipt $receipt)
    {
        $receipt->load(['client', 'project', 'invoice', 'user']);
        return view('financial.receipts.show', compact('receipt'));
    }

    public function edit(Receipt $receipt)
    {
        $clients = Client::active()->orderBy('name')->get();
        $projects = Project::orderBy('name')->get();
        $invoices = Invoice::where('status', '!=', 'cancelled')
            ->with('client')
            ->orderBy('created_at', 'desc')
            ->get();
        return view('financial.receipts.edit', compact('receipt', 'clients', 'projects', 'invoices'));
    }

    public function update(Request $request, Receipt $receipt)
    {
        $validated = $request->validate([
            'project_id' => 'nullable|exists:projects,id',
            'client_id' => 'required|exists:clients,id',
            'invoice_id' => 'nullable|exists:invoices,id',
            'issue_date' => 'required|date',
            'amount' => 'required|numeric|min:0',
            'payment_method' => 'required|in:cash,pix,credit_card,debit_card,bank_transfer,check,other',
            'description' => 'nullable|string|max:255',
            'notes' => 'nullable|string',
        ]);

        DB::beginTransaction();
        try {
            $receipt->update($validated);

            // Se vinculado a uma nota fiscal, atualizar status
            if ($receipt->invoice_id) {
                $invoice = Invoice::find($receipt->invoice_id);
                if ($invoice && $receipt->amount >= $invoice->total_amount) {
                    $invoice->update(['status' => Invoice::STATUS_PAID]);
                }
            }

            DB::commit();
            return redirect()->route('financial.receipts.index')
                ->with('success', 'Recibo atualizado com sucesso!');

        } catch (\Exception $e) {
            DB::rollBack();
            return back()->withInput()->with('error', 'Erro ao atualizar recibo: ' . $e->getMessage());
        }
    }

    public function destroy(Receipt $receipt)
    {
        try {
            $receipt->delete();
            return redirect()->route('financial.receipts.index')
                ->with('success', 'Recibo excluído com sucesso!');
        } catch (\Exception $e) {
            return back()->with('error', 'Erro ao excluir recibo: ' . $e->getMessage());
        }
    }

    public function generatePDF(Receipt $receipt)
    {
        $receipt->load(['client', 'project', 'invoice']);
        
        $pdf = Pdf::loadView('financial.receipts.pdf', compact('receipt'));
        
        return $pdf->download("recibo-{$receipt->number}.pdf");
    }
}
