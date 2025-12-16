<?php

namespace App\Http\Controllers;

use App\Models\Receipt;
use App\Models\Invoice;
use App\Models\Project;
use App\Models\Client;
use App\Events\ReceiptChanged;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Support\Facades\File;
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
            $documentPath = null;
            if ($request->hasFile('document_file')) {
                $file = $request->file('document_file');
                $directory = public_path('documents/receipts');
                if (!File::exists($directory)) {
                    File::makeDirectory($directory, 0755, true);
                }
                $filename = time() . '_' . uniqid() . '.' . $file->getClientOriginalExtension();
                $destinationPath = $directory . '/' . $filename;
                File::copy($file->getRealPath(), $destinationPath);
                $documentPath = 'documents/receipts/' . $filename;
            }

            $receipt = Receipt::create([
                ...$validated,
                'document_file' => $documentPath,
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
            
            // Broadcast event
            event(new ReceiptChanged(
                $receipt->id,
                'created',
                'Novo recibo criado',
                [
                    'number' => $receipt->number,
                    'client_id' => $receipt->client_id,
                    'amount' => $receipt->amount,
                    'payment_method' => $receipt->payment_method,
                ]
            ));
            
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

    public function editData(Receipt $receipt)
    {
        try {
            return response()->json([
                'id' => $receipt->id,
                'client_id' => $receipt->client_id,
                'project_id' => $receipt->project_id,
                'invoice_id' => $receipt->invoice_id,
                'issue_date' => $receipt->issue_date ? $receipt->issue_date->format('Y-m-d') : null,
                'amount' => $receipt->amount,
                'payment_method' => $receipt->payment_method,
                'description' => $receipt->description,
                'notes' => $receipt->notes,
                'document_file' => $receipt->document_file,
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'error' => 'Erro ao carregar dados: ' . $e->getMessage()
            ], 500);
        }
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
            'document_file' => 'nullable|file|mimes:pdf,doc,docx,xls,xlsx,jpg,jpeg,png,zip,rar|max:10240',
        ]);

        DB::beginTransaction();
        try {
            // Remover documento se solicitado
            if ($request->has('remove_document_file') && $request->remove_document_file == '1') {
                if ($receipt->document_file) {
                    $oldPath = public_path($receipt->document_file);
                    if (File::exists($oldPath)) {
                        File::delete($oldPath);
                    }
                }
                $validated['document_file'] = null;
            } elseif ($request->hasFile('document_file')) {
                // Remover documento antigo se existir
                if ($receipt->document_file) {
                    $oldPath = public_path($receipt->document_file);
                    if (File::exists($oldPath)) {
                        File::delete($oldPath);
                    }
                }
                
                // Salvar novo documento
                $file = $request->file('document_file');
                $directory = public_path('documents/receipts');
                if (!File::exists($directory)) {
                    File::makeDirectory($directory, 0755, true);
                }
                $filename = time() . '_' . uniqid() . '.' . $file->getClientOriginalExtension();
                $destinationPath = $directory . '/' . $filename;
                File::copy($file->getRealPath(), $destinationPath);
                $validated['document_file'] = 'documents/receipts/' . $filename;
            } else {
                // Manter documento existente
                unset($validated['document_file']);
            }

            $receipt->update($validated);

            // Se vinculado a uma nota fiscal, atualizar status
            if ($receipt->invoice_id) {
                $invoice = Invoice::find($receipt->invoice_id);
                if ($invoice && $receipt->amount >= $invoice->total_amount) {
                    $invoice->update(['status' => Invoice::STATUS_PAID]);
                }
            }

            DB::commit();
            
            // Broadcast event
            event(new ReceiptChanged(
                $receipt->id,
                'updated',
                'Recibo atualizado',
                [
                    'number' => $receipt->number,
                    'client_id' => $receipt->client_id,
                    'amount' => $receipt->amount,
                    'payment_method' => $receipt->payment_method,
                ]
            ));
            
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
