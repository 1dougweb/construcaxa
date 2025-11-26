<?php

namespace App\Http\Controllers;

use App\Models\TechnicalInspection;
use App\Services\GoogleMapsService;
use Illuminate\Http\Request;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Support\Facades\Storage;

class TechnicalInspectionController extends Controller
{
    public function index(Request $request)
    {
        $query = TechnicalInspection::with(['user', 'client', 'project']);

        // Filtros
        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('number', 'like', "%{$search}%")
                  ->orWhere('address', 'like', "%{$search}%")
                  ->orWhere('responsible_name', 'like', "%{$search}%");
            });
        }

        if ($request->filled('date_from')) {
            $query->whereDate('inspection_date', '>=', $request->date_from);
        }

        if ($request->filled('date_to')) {
            $query->whereDate('inspection_date', '<=', $request->date_to);
        }

        if ($request->filled('responsible')) {
            $query->where('responsible_name', 'like', "%{$request->responsible}%");
        }

        $inspections = $query->latest()->paginate(15)->withQueryString();

        $mapsService = app(GoogleMapsService::class);
        $mapsApiKey = $mapsService->getApiKey();

        return view('technical-inspections.index', compact('inspections', 'mapsApiKey'));
    }

    public function create()
    {
        return view('technical-inspections.create');
    }

    public function store(Request $request)
    {
        // O salvamento é feito pelo componente Livewire
        return redirect()->route('technical-inspections.index');
    }

    public function show(TechnicalInspection $technicalInspection)
    {
        $technicalInspection->load(['environments.elements', 'user', 'client', 'project']);
        
        return view('technical-inspections.show', compact('technicalInspection'));
    }

    public function edit(TechnicalInspection $technicalInspection)
    {
        return view('technical-inspections.edit', compact('technicalInspection'));
    }

    public function update(Request $request, TechnicalInspection $technicalInspection)
    {
        // A atualização é feita pelo componente Livewire
        return redirect()->route('technical-inspections.show', $technicalInspection);
    }

    public function destroy(TechnicalInspection $technicalInspection)
    {
        // Deletar arquivos relacionados
        if ($technicalInspection->map_image_path) {
            Storage::disk('public')->delete($technicalInspection->map_image_path);
        }

        if ($technicalInspection->pdf_path) {
            Storage::disk('public')->delete($technicalInspection->pdf_path);
        }

        // Deletar fotos dos ambientes e elementos
        foreach ($technicalInspection->environments as $environment) {
            if ($environment->photos) {
                foreach ($environment->photos as $photo) {
                    Storage::disk('public')->delete($photo);
                }
            }
            if ($environment->qr_code_path) {
                Storage::disk('public')->delete($environment->qr_code_path);
            }

            foreach ($environment->elements as $element) {
                if ($element->photos) {
                    foreach ($element->photos as $photo) {
                        Storage::disk('public')->delete($photo);
                    }
                }
            }
        }

        $technicalInspection->delete();

        return redirect()->route('technical-inspections.index')
            ->with('success', 'Vistoria técnica excluída com sucesso!');
    }

    public function generatePDF(TechnicalInspection $technicalInspection)
    {
        $technicalInspection->load(['environments.elements', 'user', 'client', 'project']);

        $pdf = Pdf::loadView('technical-inspections.pdf', [
            'inspection' => $technicalInspection
        ])->setPaper('a4', 'portrait');

        $filename = "inspections/{$technicalInspection->number}.pdf";
        Storage::disk('public')->put($filename, $pdf->output());

        $technicalInspection->update(['pdf_path' => $filename]);

        return $pdf->download("vistoria-{$technicalInspection->number}.pdf");
    }

    public function viewPDF(TechnicalInspection $technicalInspection)
    {
        $technicalInspection->load(['environments.elements', 'user', 'client', 'project']);

        // Se o PDF já existe, retornar ele
        if ($technicalInspection->pdf_path && Storage::disk('public')->exists($technicalInspection->pdf_path)) {
            return response()->file(Storage::disk('public')->path($technicalInspection->pdf_path));
        }

        // Caso contrário, gerar e mostrar
        $pdf = Pdf::loadView('technical-inspections.pdf', [
            'inspection' => $technicalInspection
        ])->setPaper('a4', 'portrait');

        $filename = "inspections/{$technicalInspection->number}.pdf";
        Storage::disk('public')->put($filename, $pdf->output());

        $technicalInspection->update(['pdf_path' => $filename]);

        return $pdf->stream("vistoria-{$technicalInspection->number}.pdf");
    }
}
