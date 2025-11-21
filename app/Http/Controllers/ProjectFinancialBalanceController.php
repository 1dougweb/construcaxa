<?php

namespace App\Http\Controllers;

use App\Models\Project;
use App\Models\ProjectFinancialBalance;
use App\Models\MaterialRequest;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ProjectFinancialBalanceController extends Controller
{
    public function show(Project $project)
    {
        // Calcular balanço baseado em MaterialRequests vinculados ao projeto
        $materialRequests = MaterialRequest::where('project_id', $project->id)
            ->where('has_stock_out', true)
            ->with(['items.product'])
            ->get();

        $balances = [];
        $totalCost = 0;

        foreach ($materialRequests as $request) {
            foreach ($request->items as $item) {
                if ($item->product) {
                    $unitCost = $item->price ?? $item->product->price ?? 0;
                    $quantityUsed = $item->quantity;
                    $itemTotalCost = $unitCost * $quantityUsed;
                    $totalCost += $itemTotalCost;

                    // Verificar se já existe um registro de balanço
                    $balance = ProjectFinancialBalance::where('project_id', $project->id)
                        ->where('material_request_id', $request->id)
                        ->where('product_id', $item->product_id)
                        ->first();

                    if (!$balance) {
                        // Criar registro de balanço
                        $balance = ProjectFinancialBalance::create([
                            'project_id' => $project->id,
                            'product_id' => $item->product_id,
                            'material_request_id' => $request->id,
                            'quantity_used' => $quantityUsed,
                            'unit_cost' => $unitCost,
                            'total_cost' => $itemTotalCost,
                            'usage_date' => $request->created_at->toDateString(),
                            'category' => optional($item->product->category)->name,
                            'description' => "Uso em requisição #{$request->number}",
                        ]);
                    }

                    $balances[] = [
                        'product' => $item->product,
                        'quantity_used' => $quantityUsed,
                        'unit_cost' => $unitCost,
                        'total_cost' => $itemTotalCost,
                        'usage_date' => $request->created_at->format('d/m/Y'),
                        'request_number' => $request->number,
                        'category' => optional($item->product->category)->name,
                    ];
                }
            }
        }

        // Ordenar por categoria e depois por data
        usort($balances, function($a, $b) {
            if ($a['category'] != $b['category']) {
                return strcmp($a['category'] ?? '', $b['category'] ?? '');
            }
            return strcmp($a['usage_date'], $b['usage_date']);
        });

        // Total por categoria
        $totalsByCategory = [];
        foreach ($balances as $balance) {
            $category = $balance['category'] ?? 'Sem categoria';
            if (!isset($totalsByCategory[$category])) {
                $totalsByCategory[$category] = 0;
            }
            $totalsByCategory[$category] += $balance['total_cost'];
        }

        // Comparação com orçamento aprovado
        $approvedBudget = $project->budgets()->where('status', \App\Models\ProjectBudget::STATUS_APPROVED)->latest()->first();
        $budgetTotal = $approvedBudget ? $approvedBudget->total : 0;
        $budgetVariance = $budgetTotal > 0 ? (($totalCost / $budgetTotal) * 100) : 0;

        return view('projects.financial-balance', compact(
            'project',
            'balances',
            'totalCost',
            'totalsByCategory',
            'approvedBudget',
            'budgetTotal',
            'budgetVariance'
        ));
    }

    public function sync(Request $request, Project $project)
    {
        // Sincronizar balanço financeiro para o projeto
        DB::beginTransaction();
        try {
            $materialRequests = MaterialRequest::where('project_id', $project->id)
                ->where('has_stock_out', true)
                ->with(['items.product'])
                ->get();

            foreach ($materialRequests as $request) {
                foreach ($request->items as $item) {
                    if ($item->product) {
                        $unitCost = $item->price ?? $item->product->price ?? 0;
                        $quantityUsed = $item->quantity;
                        $itemTotalCost = $unitCost * $quantityUsed;

                        ProjectFinancialBalance::updateOrCreate(
                            [
                                'project_id' => $project->id,
                                'material_request_id' => $request->id,
                                'product_id' => $item->product_id,
                            ],
                            [
                                'quantity_used' => $quantityUsed,
                                'unit_cost' => $unitCost,
                                'total_cost' => $itemTotalCost,
                                'usage_date' => $request->created_at->toDateString(),
                                'category' => optional($item->product->category)->name,
                                'description' => "Uso em requisição #{$request->number}",
                            ]
                        );
                    }
                }
            }

            DB::commit();
            return redirect()->route('projects.financial-balance', $project)
                ->with('success', 'Balanço financeiro sincronizado com sucesso!');

        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with('error', 'Erro ao sincronizar balanço: ' . $e->getMessage());
        }
    }
}
