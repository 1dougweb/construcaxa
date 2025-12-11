<?php

namespace App\Http\Controllers;

use App\Models\ClientDocument;
use App\Models\Client;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class ClientDocumentController extends Controller
{
    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request, Client $client)
    {
        $validated = $request->validate([
            'document_type' => 'required|string|max:255',
            'name' => 'required|string|max:255',
            'file' => 'required|file|mimes:pdf,jpg,jpeg,png|max:10240',
            'related_id' => 'nullable|integer',
            'related_type' => 'nullable|string',
        ]);

        try {
            $path = $request->file('file')->store('client-documents', 'public');

            $document = ClientDocument::create([
                'client_id' => $client->id,
                'document_type' => $validated['document_type'],
                'name' => $validated['name'],
                'file_path' => $path,
                'related_id' => $validated['related_id'] ?? null,
                'related_type' => $validated['related_type'] ?? null,
                'uploaded_by' => auth()->id(),
            ]);

            if (request()->wantsJson() || request()->ajax()) {
                return response()->json([
                    'success' => true,
                    'message' => 'Documento anexado com sucesso!',
                    'document' => [
                        'id' => $document->id,
                        'name' => $document->name,
                        'document_type' => $document->document_type,
                        'file_path' => $document->file_path,
                        'created_at' => $document->created_at->format('d/m/Y H:i'),
                    ]
                ]);
            }

            return back()->with('success', 'Documento anexado com sucesso!');
        } catch (\Exception $e) {
            return back()->with('error', 'Erro ao anexar documento: ' . $e->getMessage());
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(ClientDocument $clientDocument)
    {
        try {
            // Deletar arquivo
            if ($clientDocument->file_path) {
                Storage::disk('public')->delete($clientDocument->file_path);
            }

            $clientDocument->delete();

            if (request()->wantsJson() || request()->ajax()) {
                return response()->json([
                    'success' => true,
                    'message' => 'Documento removido com sucesso!'
                ]);
            }

            return back()->with('success', 'Documento removido com sucesso!');
        } catch (\Exception $e) {
            return back()->with('error', 'Erro ao remover documento: ' . $e->getMessage());
        }
    }
}
