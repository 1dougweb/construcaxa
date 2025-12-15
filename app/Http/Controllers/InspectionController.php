<?php

namespace App\Http\Controllers;

use App\Models\Inspection;
use App\Models\ProjectBudget;
use App\Models\Client;
use App\Mail\InspectionCompletedNotification;
use Illuminate\Http\Request;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Mail;

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
     * Cliente aprova a vistoria (via link público).
     */
    public function approveByClient(Request $request, string $token)
    {
        $inspection = Inspection::where('public_token', $token)->firstOrFail();

        if ($inspection->client_decision) {
            return redirect()->route('inspections.public', $token)
                ->with('error', 'Esta vistoria já foi respondida pelo cliente.');
        }

        $inspection->update([
            'client_decision' => 'approved',
            'client_decision_at' => now(),
            'client_comment' => $request->input('client_comment'),
        ]);

        return redirect()->route('inspections.public', $token)
            ->with('success', 'Obrigado! Sua aprovação da vistoria foi registrada.');
    }

    /**
     * Cliente contesta a vistoria (via link público).
     */
    public function contestByClient(Request $request, string $token)
    {
        $inspection = Inspection::where('public_token', $token)->firstOrFail();

        if ($inspection->client_decision) {
            return redirect()->route('inspections.public', $token)
                ->with('error', 'Esta vistoria já foi respondida pelo cliente.');
        }

        $validated = $request->validate([
            'client_comment' => ['required', 'string', 'max:2000'],
        ]);

        $inspection->update([
            'client_decision' => 'contested',
            'client_decision_at' => now(),
            'client_comment' => $validated['client_comment'],
        ]);

        return redirect()->route('inspections.public', $token)
            ->with('success', 'Sua contestação foi registrada com sucesso. Nossa equipe irá analisar e entrar em contato se necessário.');
    }

    /**
     * List inspections for a given client (used when creating budgets).
     */
    public function listByClient(Client $client)
    {
        $inspections = Inspection::where('client_id', $client->id)
            ->orderByDesc('inspection_date')
            ->get(['id', 'number', 'inspection_date', 'status']);

        $data = $inspections->map(fn ($inspection) => [
            'id' => $inspection->id,
            'label' => sprintf(
                '%s - %s (%s)',
                $inspection->number,
                optional($inspection->inspection_date)->format('d/m/Y') ?? 'sem data',
                ucfirst(str_replace('_', ' ', $inspection->status ?? ''))
            ),
        ]);

        return response()->json([
            'success' => true,
            'inspections' => $data,
        ]);
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
                    $filePath = public_path($photo->photo_path);
                    if (\Illuminate\Support\Facades\File::exists($filePath)) {
                        \Illuminate\Support\Facades\File::delete($filePath);
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
        // Evitar completar novamente
        if ($inspection->status === 'completed') {
            return back()->with('success', 'Esta vistoria já está marcada como concluída.');
        }

        $inspection->update(['status' => 'completed']);

        // Enviar email para o cliente com link público da vistoria
        try {
            if ($inspection->client && $inspection->client->email) {
                // Garantir que existe um token público
                $inspection->generatePublicToken();

                // Aplicar configurações de email definidas em /admin/email, se disponíveis
                if (class_exists(\App\Http\Controllers\AdminController::class) && method_exists(\App\Http\Controllers\AdminController::class, 'applyEmailSettings')) {
                    \App\Http\Controllers\AdminController::applyEmailSettings();
                }

                Mail::to($inspection->client->email)
                    ->send(new InspectionCompletedNotification($inspection));
            }
        } catch (\Throwable $e) {
            \Log::error('Erro ao enviar email de vistoria concluída para cliente: ' . $e->getMessage(), [
                'inspection_id' => $inspection->id,
                'client_id' => $inspection->client_id,
            ]);
        }

        return back()->with('success', 'Vistoria marcada como concluída e o cliente foi notificado por e-mail.');
    }

    /**
     * Reenviar email de vistoria concluída para o cliente.
     */
    public function resendEmail(Inspection $inspection)
    {
        if ($inspection->status !== 'completed') {
            return back()->with('error', 'Só é possível reenviar o e-mail após a vistoria estar concluída.');
        }

        try {
            if ($inspection->client && $inspection->client->email) {
                $inspection->generatePublicToken();

                if (class_exists(\App\Http\Controllers\AdminController::class) && method_exists(\App\Http\Controllers\AdminController::class, 'applyEmailSettings')) {
                    \App\Http\Controllers\AdminController::applyEmailSettings();
                }

                Mail::to($inspection->client->email)
                    ->send(new InspectionCompletedNotification($inspection));
            } else {
                return back()->with('error', 'Não há e-mail de cliente configurado para esta vistoria.');
            }
        } catch (\Throwable $e) {
            \Log::error('Erro ao reenviar email de vistoria concluída para cliente: ' . $e->getMessage(), [
                'inspection_id' => $inspection->id,
                'client_id' => $inspection->client_id,
            ]);

            return back()->with('error', 'Ocorreu um erro ao reenviar o e-mail da vistoria.');
        }

        return back()->with('success', 'E-mail da vistoria reenviado com sucesso para o cliente.');
    }
}
