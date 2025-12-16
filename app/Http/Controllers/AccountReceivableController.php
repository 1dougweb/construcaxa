<?php

namespace App\Http\Controllers;

use App\Models\AccountReceivable;
use App\Models\Project;
use App\Models\Client;
use App\Events\AccountReceivableChanged;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\File;

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
        $clients = Client::active()->orderBy('name')->get();
        $projects = Project::orderBy('name')->get();
        return view('financial.accounts-receivable.create', compact('clients', 'projects'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'client_id' => 'nullable|exists:clients,id',
            'project_id' => 'nullable|exists:projects,id',
            'description' => 'required|string|max:255',
            'amount' => 'required|numeric|min:0',
            'due_date' => 'required|date',
            'status' => 'required|in:pending,received,overdue,cancelled',
            'notes' => 'nullable|string',
            'document_file' => 'nullable|file|mimes:pdf,doc,docx,xls,xlsx,jpg,jpeg,png,zip,rar|max:10240',
        ]);

        DB::beginTransaction();
        try {
            $documentPath = null;
            if ($request->hasFile('document_file')) {
                $file = $request->file('document_file');
                $directory = public_path('documents/accounts-receivable');
                if (!File::exists($directory)) {
                    File::makeDirectory($directory, 0755, true);
                }
                $filename = time() . '_' . uniqid() . '.' . $file->getClientOriginalExtension();
                $destinationPath = $directory . '/' . $filename;
                File::copy($file->getRealPath(), $destinationPath);
                $documentPath = 'documents/accounts-receivable/' . $filename;
            }

            $accountReceivable = AccountReceivable::create([
                ...$validated,
                'document_file' => $documentPath,
                'number' => (new AccountReceivable())->generateNumber(),
                'user_id' => auth()->id(),
                'received_date' => $validated['status'] === 'received' ? now() : null,
            ]);

            DB::commit();
            
            // Broadcast event
            event(new AccountReceivableChanged(
                $accountReceivable->id,
                'created',
                'Nova conta a receber criada',
                [
                    'number' => $accountReceivable->number,
                    'description' => $accountReceivable->description,
                    'amount' => $accountReceivable->amount,
                    'status' => $accountReceivable->status,
                ]
            ));
            
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
        $clients = Client::active()->orderBy('name')->get();
        $projects = Project::orderBy('name')->get();
        return view('financial.accounts-receivable.edit', compact('accountReceivable', 'clients', 'projects'));
    }

    public function editData(AccountReceivable $accountReceivable)
    {
        try {
            return response()->json([
                'id' => $accountReceivable->id,
                'client_id' => $accountReceivable->client_id,
                'project_id' => $accountReceivable->project_id,
                'description' => $accountReceivable->description,
                'amount' => $accountReceivable->amount,
                'due_date' => $accountReceivable->due_date ? $accountReceivable->due_date->format('Y-m-d') : null,
                'status' => $accountReceivable->status,
                'notes' => $accountReceivable->notes,
                'document_file' => $accountReceivable->document_file,
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'error' => 'Erro ao carregar dados: ' . $e->getMessage()
            ], 500);
        }
    }

    public function update(Request $request, AccountReceivable $accountReceivable)
    {
        $validated = $request->validate([
            'client_id' => 'nullable|exists:clients,id',
            'project_id' => 'nullable|exists:projects,id',
            'description' => 'required|string|max:255',
            'amount' => 'required|numeric|min:0',
            'due_date' => 'required|date',
            'status' => 'required|in:pending,received,overdue,cancelled',
            'notes' => 'nullable|string',
            'document_file' => 'nullable|file|mimes:pdf,doc,docx,xls,xlsx,jpg,jpeg,png,zip,rar|max:10240',
        ]);

        DB::beginTransaction();
        try {
            // Remover documento se solicitado
            if ($request->has('remove_document_file') && $request->remove_document_file == '1') {
                if ($accountReceivable->document_file) {
                    $oldPath = public_path($accountReceivable->document_file);
                    if (File::exists($oldPath)) {
                        File::delete($oldPath);
                    }
                }
                $validated['document_file'] = null;
            } elseif ($request->hasFile('document_file')) {
                // Remover documento antigo se existir
                if ($accountReceivable->document_file) {
                    $oldPath = public_path($accountReceivable->document_file);
                    if (File::exists($oldPath)) {
                        File::delete($oldPath);
                    }
                }
                
                // Salvar novo documento
                $file = $request->file('document_file');
                $directory = public_path('documents/accounts-receivable');
                if (!File::exists($directory)) {
                    File::makeDirectory($directory, 0755, true);
                }
                $filename = time() . '_' . uniqid() . '.' . $file->getClientOriginalExtension();
                $destinationPath = $directory . '/' . $filename;
                File::copy($file->getRealPath(), $destinationPath);
                $validated['document_file'] = 'documents/accounts-receivable/' . $filename;
            } else {
                // Manter documento existente
                unset($validated['document_file']);
            }

            $accountReceivable->update([
                ...$validated,
                'received_date' => $validated['status'] === 'received' ? ($accountReceivable->received_date ?? now()) : null,
            ]);

            DB::commit();
            
            // Broadcast event
            event(new AccountReceivableChanged(
                $accountReceivable->id,
                'updated',
                'Conta a receber atualizada',
                [
                    'number' => $accountReceivable->number,
                    'description' => $accountReceivable->description,
                    'amount' => $accountReceivable->amount,
                    'status' => $accountReceivable->status,
                ]
            ));
            
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
