<?php

namespace App\Http\Controllers;

use App\Models\Invoice;
use App\Models\Project;
use App\Models\ProjectBudget;
use App\Models\Client;
use App\Events\InvoiceChanged;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Support\Facades\File;
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
        $clients = Client::active()->orderBy('name')->get();
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
            'client_id' => 'required|exists:clients,id',
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
            $xmlPath = null;
            $pdfPath = null;
            
            if ($request->hasFile('xml_file')) {
                $file = $request->file('xml_file');
                $directory = public_path('documents/invoices');
                if (!File::exists($directory)) {
                    File::makeDirectory($directory, 0755, true);
                }
                $filename = time() . '_' . uniqid() . '.xml';
                $destinationPath = $directory . '/' . $filename;
                File::copy($file->getRealPath(), $destinationPath);
                $xmlPath = 'documents/invoices/' . $filename;
            }
            
            if ($request->hasFile('pdf_file')) {
                $file = $request->file('pdf_file');
                $directory = public_path('documents/invoices');
                if (!File::exists($directory)) {
                    File::makeDirectory($directory, 0755, true);
                }
                $filename = time() . '_' . uniqid() . '.pdf';
                $destinationPath = $directory . '/' . $filename;
                File::copy($file->getRealPath(), $destinationPath);
                $pdfPath = 'documents/invoices/' . $filename;
            }

            $invoice = Invoice::create([
                ...$validated,
                'xml_file' => $xmlPath,
                'pdf_file' => $pdfPath,
                'number' => (new Invoice())->generateNumber(),
                'total_amount' => $validated['subtotal'] + ($validated['tax_amount'] ?? 0),
                'user_id' => auth()->id(),
            ]);

            DB::commit();
            
            // Broadcast event
            event(new InvoiceChanged(
                $invoice->id,
                'created',
                'Nova nota fiscal criada',
                [
                    'number' => $invoice->number,
                    'client_id' => $invoice->client_id,
                    'total_amount' => $invoice->total_amount,
                    'status' => $invoice->status,
                ]
            ));
            
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
        $clients = Client::active()->orderBy('name')->get();
        $projects = Project::orderBy('name')->get();
        $budgets = ProjectBudget::where('status', ProjectBudget::STATUS_APPROVED)
            ->with('project')
            ->orderBy('created_at', 'desc')
            ->get();
        return view('financial.invoices.edit', compact('invoice', 'clients', 'projects', 'budgets'));
    }

    public function editData(Invoice $invoice)
    {
        try {
            return response()->json([
            'id' => $invoice->id,
            'client_id' => $invoice->client_id,
            'project_id' => $invoice->project_id,
            'budget_id' => $invoice->budget_id,
            'issue_date' => $invoice->issue_date ? $invoice->issue_date->format('Y-m-d') : null,
            'due_date' => $invoice->due_date ? $invoice->due_date->format('Y-m-d') : null,
            'subtotal' => $invoice->subtotal,
            'tax_amount' => $invoice->tax_amount,
            'status' => $invoice->status,
            'notes' => $invoice->notes,
            'xml_file' => $invoice->xml_file,
            'pdf_file' => $invoice->pdf_file,
        ]);
        } catch (\Exception $e) {
            return response()->json([
                'error' => 'Erro ao carregar dados: ' . $e->getMessage()
            ], 500);
        }
    }

    public function update(Request $request, Invoice $invoice)
    {
        $validated = $request->validate([
            'project_id' => 'nullable|exists:projects,id',
            'client_id' => 'required|exists:clients,id',
            'budget_id' => 'nullable|exists:project_budgets,id',
            'issue_date' => 'required|date',
            'due_date' => 'required|date|after_or_equal:issue_date',
            'subtotal' => 'required|numeric|min:0',
            'tax_amount' => 'nullable|numeric|min:0',
            'status' => 'required|in:draft,issued,paid,cancelled',
            'notes' => 'nullable|string',
            'xml_file' => 'nullable|file|mimes:xml|max:10240',
            'pdf_file' => 'nullable|file|mimes:pdf|max:10240',
        ]);

        DB::beginTransaction();
        try {
            // Processar XML
            if ($request->has('remove_xml_file') && $request->remove_xml_file == '1') {
                if ($invoice->xml_file) {
                    $oldPath = public_path($invoice->xml_file);
                    if (File::exists($oldPath)) {
                        File::delete($oldPath);
                    }
                }
                $validated['xml_file'] = null;
            } elseif ($request->hasFile('xml_file')) {
                if ($invoice->xml_file) {
                    $oldPath = public_path($invoice->xml_file);
                    if (File::exists($oldPath)) {
                        File::delete($oldPath);
                    }
                }
                $file = $request->file('xml_file');
                $directory = public_path('documents/invoices');
                if (!File::exists($directory)) {
                    File::makeDirectory($directory, 0755, true);
                }
                $filename = time() . '_' . uniqid() . '.xml';
                $destinationPath = $directory . '/' . $filename;
                File::copy($file->getRealPath(), $destinationPath);
                $validated['xml_file'] = 'documents/invoices/' . $filename;
            } else {
                unset($validated['xml_file']);
            }
            
            // Processar PDF
            if ($request->has('remove_pdf_file') && $request->remove_pdf_file == '1') {
                if ($invoice->pdf_file) {
                    $oldPath = public_path($invoice->pdf_file);
                    if (File::exists($oldPath)) {
                        File::delete($oldPath);
                    }
                }
                $validated['pdf_file'] = null;
            } elseif ($request->hasFile('pdf_file')) {
                if ($invoice->pdf_file) {
                    $oldPath = public_path($invoice->pdf_file);
                    if (File::exists($oldPath)) {
                        File::delete($oldPath);
                    }
                }
                $file = $request->file('pdf_file');
                $directory = public_path('documents/invoices');
                if (!File::exists($directory)) {
                    File::makeDirectory($directory, 0755, true);
                }
                $filename = time() . '_' . uniqid() . '.pdf';
                $destinationPath = $directory . '/' . $filename;
                File::copy($file->getRealPath(), $destinationPath);
                $validated['pdf_file'] = 'documents/invoices/' . $filename;
            } else {
                unset($validated['pdf_file']);
            }

            $invoice->update([
                ...$validated,
                'total_amount' => $validated['subtotal'] + ($validated['tax_amount'] ?? 0),
            ]);

            DB::commit();
            
            // Broadcast event
            event(new InvoiceChanged(
                $invoice->id,
                'updated',
                'Nota fiscal atualizada',
                [
                    'number' => $invoice->number,
                    'client_id' => $invoice->client_id,
                    'total_amount' => $invoice->total_amount,
                    'status' => $invoice->status,
                ]
            ));
            
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
