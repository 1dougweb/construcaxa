<?php

namespace App\Http\Controllers;

use App\Models\AccountPayable;
use App\Models\Supplier;
use App\Models\Project;
use App\Events\AccountPayableChanged;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\File;

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
            'document_file' => 'nullable|file|mimes:pdf,doc,docx,xls,xlsx,jpg,jpeg,png,zip,rar|max:10240',
        ]);

        DB::beginTransaction();
        try {
            $documentPath = null;
            if ($request->hasFile('document_file')) {
                $file = $request->file('document_file');
                $directory = public_path('documents/accounts-payable');
                if (!File::exists($directory)) {
                    File::makeDirectory($directory, 0755, true);
                }
                $filename = time() . '_' . uniqid() . '.' . $file->getClientOriginalExtension();
                $destinationPath = $directory . '/' . $filename;
                File::copy($file->getRealPath(), $destinationPath);
                $documentPath = 'documents/accounts-payable/' . $filename;
            }

            $accountPayable = AccountPayable::create([
                ...$validated,
                'document_file' => $documentPath,
                'number' => (new AccountPayable())->generateNumber(),
                'user_id' => auth()->id(),
                'paid_date' => $validated['status'] === 'paid' ? now() : null,
            ]);

            DB::commit();
            
            // Broadcast event
            event(new AccountPayableChanged(
                $accountPayable->id,
                'created',
                'Nova conta a pagar criada',
                [
                    'number' => $accountPayable->number,
                    'description' => $accountPayable->description,
                    'amount' => $accountPayable->amount,
                    'status' => $accountPayable->status,
                ]
            ));
            
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

    public function editData(AccountPayable $accountPayable)
    {
        try {
            return response()->json([
                'id' => $accountPayable->id,
                'supplier_id' => $accountPayable->supplier_id,
                'project_id' => $accountPayable->project_id,
                'description' => $accountPayable->description,
                'category' => $accountPayable->category,
                'amount' => $accountPayable->amount,
                'due_date' => $accountPayable->due_date ? $accountPayable->due_date->format('Y-m-d') : null,
                'status' => $accountPayable->status,
                'notes' => $accountPayable->notes,
                'document_file' => $accountPayable->document_file,
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'error' => 'Erro ao carregar dados: ' . $e->getMessage()
            ], 500);
        }
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
            'document_file' => 'nullable|file|mimes:pdf,doc,docx,xls,xlsx,jpg,jpeg,png,zip,rar|max:10240',
        ]);

        DB::beginTransaction();
        try {
            // Remover documento se solicitado
            if ($request->has('remove_document_file') && $request->remove_document_file == '1') {
                if ($accountPayable->document_file) {
                    $oldPath = public_path($accountPayable->document_file);
                    if (File::exists($oldPath)) {
                        File::delete($oldPath);
                    }
                }
                $validated['document_file'] = null;
            } elseif ($request->hasFile('document_file')) {
                // Remover documento antigo se existir
                if ($accountPayable->document_file) {
                    $oldPath = public_path($accountPayable->document_file);
                    if (File::exists($oldPath)) {
                        File::delete($oldPath);
                    }
                }
                
                // Salvar novo documento
                $file = $request->file('document_file');
                $directory = public_path('documents/accounts-payable');
                if (!File::exists($directory)) {
                    File::makeDirectory($directory, 0755, true);
                }
                $filename = time() . '_' . uniqid() . '.' . $file->getClientOriginalExtension();
                $destinationPath = $directory . '/' . $filename;
                File::copy($file->getRealPath(), $destinationPath);
                $validated['document_file'] = 'documents/accounts-payable/' . $filename;
            } else {
                // Manter documento existente
                unset($validated['document_file']);
            }

            $accountPayable->update([
                ...$validated,
                'paid_date' => $validated['status'] === 'paid' ? ($accountPayable->paid_date ?? now()) : null,
            ]);

            DB::commit();
            
            // Broadcast event
            event(new AccountPayableChanged(
                $accountPayable->id,
                'updated',
                'Conta a pagar atualizada',
                [
                    'number' => $accountPayable->number,
                    'description' => $accountPayable->description,
                    'amount' => $accountPayable->amount,
                    'status' => $accountPayable->status,
                ]
            ));
            
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
