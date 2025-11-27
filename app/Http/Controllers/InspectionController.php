<?php

namespace App\Http\Controllers;

use App\Models\Inspection;
use App\Models\ProjectBudget;
use Illuminate\Http\Request;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Support\Facades\Storage;

class InspectionController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $inspections = Inspection::with(['client', 'user'])
            ->latest()
            ->paginate(15);

        return view('inspections.index', compact('inspections'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('inspections.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        // O store é feito pelo Livewire component
        return redirect()->route('inspections.create');
    }

    /**
     * Display the specified resource.
     */
    public function show(Inspection $inspection)
    {
        $inspection->load([
            'client',
            'user',
            'inspector',
            'environments.template',
            'environments.items.photos',
            'environments.items.subItems',
            'budget',
        ]);

        return view('inspections.show', compact('inspection'));
    }

    /**
     * Public view of inspection (via token).
     */
    public function publicView($token)
    {
        $inspection = Inspection::where('public_token', $token)->firstOrFail();
        
        $inspection->load([
            'client',
            'environments.template',
            'environments.items.photos',
            'environments.items.subItems',
            'clientRequests.environmentItem',
            'clientRequests.subItem',
        ]);

        return view('inspections.public', compact('inspection'));
    }

    /**
     * Store client request for inspection.
     */
    public function storeClientRequest(Request $request, $token)
    {
        $inspection = Inspection::where('public_token', $token)->firstOrFail();
        
        $validated = $request->validate([
            'inspection_environment_item_id' => 'nullable|exists:inspection_environment_items,id',
            'inspection_item_sub_item_id' => 'nullable|exists:inspection_item_sub_items,id',
            'request_type' => 'required|in:alter_quality,add_observation,request_change,other',
            'message' => 'required|string|max:2000',
        ]);

        $validated['inspection_id'] = $inspection->id;
        $validated['status'] = 'pending';

        \App\Models\InspectionClientRequest::create($validated);

        return back()->with('success', 'Solicitação enviada com sucesso!');
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Inspection $inspection)
    {
        return view('inspections.edit', compact('inspection'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Inspection $inspection)
    {
        // O update é feito pelo Livewire component
        return redirect()->route('inspections.edit', $inspection);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Inspection $inspection)
    {
        // Deletar fotos
        foreach ($inspection->environments as $environment) {
            foreach ($environment->items as $item) {
                foreach ($item->photos as $photo) {
                    if (Storage::disk('public')->exists($photo->photo_path)) {
                        Storage::disk('public')->delete($photo->photo_path);
                    }
                }
            }
        }

        // Deletar PDF se existir
        if ($inspection->pdf_path && Storage::disk('public')->exists($inspection->pdf_path)) {
            Storage::disk('public')->delete($inspection->pdf_path);
        }

        $inspection->delete();

        return redirect()->route('inspections.index')
            ->with('success', 'Vistoria excluída com sucesso!');
    }

    /**
     * Generate PDF for the inspection.
     */
    public function generatePDF(Inspection $inspection)
    {
        $inspection->load([
            'client',
            'user',
            'inspector',
            'environments.template',
            'environments.items.photos',
            'environments.items.subItems',
        ]);

        $pdf = Pdf::loadView('inspections.pdf', compact('inspection'))
            ->setPaper('a4', 'portrait')
            ->setOption('isHtml5ParserEnabled', true)
            ->setOption('isRemoteEnabled', true)
            ->setOption('defaultFont', 'DejaVu Sans')
            ->setOption('enableFontSubsetting', true);
        
        // Salvar PDF
        $pdfPath = 'inspections/pdfs/' . $inspection->number . '.pdf';
        Storage::disk('public')->put($pdfPath, $pdf->output());
        $inspection->update(['pdf_path' => $pdfPath]);
        
        return $pdf->stream("vistoria-{$inspection->number}.pdf");
    }

    /**
     * Link inspection to a budget.
     */
    public function linkBudget(Request $request, Inspection $inspection)
    {
        $request->validate([
            'budget_id' => 'required|exists:project_budgets,id',
        ]);

        $budget = ProjectBudget::findOrFail($request->budget_id);
        
        // Verificar se o orçamento já está vinculado a outra vistoria
        if ($budget->inspection_id && $budget->inspection_id !== $inspection->id) {
            return back()->with('error', 'Este orçamento já está vinculado a outra vistoria.');
        }

        // Se a vistoria já tinha um orçamento vinculado, desvincular o antigo
        if ($inspection->budget_id && $inspection->budget_id !== $request->budget_id) {
            $oldBudget = ProjectBudget::find($inspection->budget_id);
            if ($oldBudget) {
                $oldBudget->update(['inspection_id' => null]);
            }
        }

        $inspection->update(['budget_id' => $request->budget_id]);
        $budget->update(['inspection_id' => $inspection->id]);

        return back()->with('success', 'Vistoria vinculada ao orçamento com sucesso!');
    }

    /**
     * Unlink inspection from budget.
     */
    public function unlinkBudget(Inspection $inspection)
    {
        if ($inspection->budget_id) {
            $budget = ProjectBudget::find($inspection->budget_id);
            if ($budget) {
                $budget->update(['inspection_id' => null]);
            }
            $inspection->update(['budget_id' => null]);
        }

        return back()->with('success', 'Vínculo com orçamento removido com sucesso!');
    }

    /**
     * Complete inspection.
     */
    public function complete(Inspection $inspection)
    {
        $inspection->update(['status' => 'completed']);
        
        return back()->with('success', 'Vistoria marcada como concluída!');
    }
}
