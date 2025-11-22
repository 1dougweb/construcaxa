<?php

namespace App\Http\Controllers;

use App\Models\Inspection;
use App\Models\Client;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Barryvdh\DomPDF\Facade\Pdf;

class InspectionController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $query = Inspection::with(['client', 'inspector']);

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
                $q->where('number', 'like', "%{$search}%")
                  ->orWhere('description', 'like', "%{$search}%")
                  ->orWhereHas('client', function($q) use ($search) {
                      $q->where('name', 'like', "%{$search}%");
                  });
            });
        }

        $inspections = $query->latest('inspection_date')
            ->latest('created_at')
            ->paginate(15);

        $clients = Client::active()->orderBy('name')->get();

        return view('inspections.index', compact('inspections', 'clients'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $clients = Client::active()->orderBy('name')->get();
        $inspectors = User::orderBy('name')->get();

        return view('inspections.create', compact('clients', 'inspectors'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'client_id' => 'required|exists:clients,id',
            'inspection_date' => 'required|date',
            'address' => 'nullable|string|max:500',
            'description' => 'nullable|string',
            'inspector_id' => 'required|exists:users,id',
            'status' => 'required|in:draft,pending,approved,rejected',
            'photos' => 'nullable|array',
            'photos.*' => 'image|mimes:jpeg,png,jpg,gif,webp|max:2048',
            'notes' => 'nullable|string',
        ]);

        DB::beginTransaction();
        try {
            // Gerar número e versão
            $number = Inspection::generateNumber();
            $client = Client::find($validated['client_id']);
            
            // Calcular próxima versão
            $lastVersion = Inspection::where('client_id', $validated['client_id'])->max('version');
            $version = ($lastVersion ?? 0) + 1;

            // Processar fotos
            $photos = [];
            if ($request->hasFile('photos')) {
                foreach ($request->file('photos') as $photo) {
                    $path = $photo->store('inspections/photos', 'public');
                    $photos[] = $path;
                }
            }

            $inspection = Inspection::create([
                'client_id' => $validated['client_id'],
                'number' => $number,
                'version' => $version,
                'inspection_date' => $validated['inspection_date'],
                'address' => $validated['address'] ?? null,
                'description' => $validated['description'] ?? null,
                'inspector_id' => $validated['inspector_id'],
                'status' => $validated['status'],
                'photos' => !empty($photos) ? $photos : null,
                'notes' => $validated['notes'] ?? null,
            ]);

            DB::commit();

            return redirect()->route('inspections.show', $inspection)
                ->with('success', 'Vistoria criada com sucesso!');
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->withInput()->with('error', 'Erro ao criar vistoria: ' . $e->getMessage());
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(Inspection $inspection)
    {
        $inspection->load(['client', 'inspector', 'approvedBy', 'budget']);
        
        return view('inspections.show', compact('inspection'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Inspection $inspection)
    {
        $clients = Client::active()->orderBy('name')->get();
        $inspectors = User::orderBy('name')->get();

        return view('inspections.edit', compact('inspection', 'clients', 'inspectors'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Inspection $inspection)
    {
        $validated = $request->validate([
            'client_id' => 'required|exists:clients,id',
            'inspection_date' => 'required|date',
            'address' => 'nullable|string|max:500',
            'description' => 'nullable|string',
            'inspector_id' => 'required|exists:users,id',
            'status' => 'required|in:draft,pending,approved,rejected',
            'photos' => 'nullable|array',
            'photos.*' => 'image|mimes:jpeg,png,jpg,gif,webp|max:2048',
            'notes' => 'nullable|string',
        ]);

        DB::beginTransaction();
        try {
            // Processar novas fotos
            $existingPhotos = $inspection->photos ?? [];
            $newPhotos = [];
            
            if ($request->hasFile('photos')) {
                foreach ($request->file('photos') as $photo) {
                    $path = $photo->store('inspections/photos', 'public');
                    $newPhotos[] = $path;
                }
            }

            $photos = array_merge($existingPhotos, $newPhotos);

            $inspection->update([
                'client_id' => $validated['client_id'],
                'inspection_date' => $validated['inspection_date'],
                'address' => $validated['address'] ?? null,
                'description' => $validated['description'] ?? null,
                'inspector_id' => $validated['inspector_id'],
                'status' => $validated['status'],
                'photos' => !empty($photos) ? $photos : null,
                'notes' => $validated['notes'] ?? null,
            ]);

            DB::commit();

            return redirect()->route('inspections.show', $inspection)
                ->with('success', 'Vistoria atualizada com sucesso!');
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->withInput()->with('error', 'Erro ao atualizar vistoria: ' . $e->getMessage());
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Inspection $inspection)
    {
        try {
            // Deletar fotos
            if ($inspection->photos) {
                foreach ($inspection->photos as $photo) {
                    Storage::disk('public')->delete($photo);
                }
            }

            // Deletar PDF
            if ($inspection->pdf_path) {
                Storage::disk('public')->delete($inspection->pdf_path);
            }

            // Deletar documento assinado
            if ($inspection->signed_document_path) {
                Storage::disk('public')->delete($inspection->signed_document_path);
            }

            $inspection->delete();

            return redirect()->route('inspections.index')
                ->with('success', 'Vistoria excluída com sucesso!');
        } catch (\Exception $e) {
            return back()->with('error', 'Erro ao excluir vistoria: ' . $e->getMessage());
        }
    }

    /**
     * Generate PDF for inspection
     */
    public function generatePDF(Inspection $inspection)
    {
        $inspection->load(['client', 'inspector']);
        
        $pdf = Pdf::loadView('inspections.pdf', compact('inspection'));
        
        // Salvar PDF
        $filename = 'inspections/' . $inspection->number . '.pdf';
        Storage::disk('public')->put($filename, $pdf->output());
        
        $inspection->update(['pdf_path' => $filename]);
        
        return $pdf->stream("vistoria-{$inspection->number}.pdf");
    }

    /**
     * Approve inspection
     */
    public function approve(Inspection $inspection)
    {
        try {
            $inspection->approve(auth()->id());

            return back()->with('success', 'Vistoria aprovada com sucesso!');
        } catch (\Exception $e) {
            return back()->with('error', 'Erro ao aprovar vistoria: ' . $e->getMessage());
        }
    }

    /**
     * Upload signed document
     */
    public function uploadSignedDocument(Request $request, Inspection $inspection)
    {
        $validated = $request->validate([
            'signed_document' => 'required|file|mimes:pdf|max:10240',
        ]);

        try {
            // Deletar documento anterior se existir
            if ($inspection->signed_document_path) {
                Storage::disk('public')->delete($inspection->signed_document_path);
            }

            // Upload do novo documento
            $path = $request->file('signed_document')->store('inspections/signed', 'public');
            
            $inspection->update(['signed_document_path' => $path]);

            // Criar registro em client_documents
            \App\Models\ClientDocument::create([
                'client_id' => $inspection->client_id,
                'document_type' => 'signed_inspection',
                'name' => "Vistoria {$inspection->number} - Assinada",
                'file_path' => $path,
                'related_id' => $inspection->id,
                'related_type' => Inspection::class,
                'uploaded_by' => auth()->id(),
            ]);

            return back()->with('success', 'Documento assinado anexado com sucesso!');
        } catch (\Exception $e) {
            return back()->with('error', 'Erro ao anexar documento: ' . $e->getMessage());
        }
    }

    /**
     * Get last inspection for client (API endpoint for AJAX)
     */
    public function getLastInspection(Client $client)
    {
        $inspection = Inspection::where('client_id', $client->id)
            ->approved()
            ->latest('inspection_date')
            ->latest('created_at')
            ->first();

        if (!$inspection) {
            return response()->json([
                'success' => false,
                'message' => 'Nenhuma vistoria aprovada encontrada para este cliente',
            ]);
        }

        return response()->json([
            'success' => true,
            'inspection' => [
                'id' => $inspection->id,
                'number' => $inspection->number,
                'version' => $inspection->version,
                'inspection_date' => $inspection->inspection_date->format('d/m/Y'),
                'description' => $inspection->description,
            ],
        ]);
    }
}
