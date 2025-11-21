<?php

namespace App\Http\Controllers;

use App\Models\AccountPayable;
use App\Models\AccountReceivable;
use App\Models\Invoice;
use App\Models\Receipt;
use Carbon\Carbon;
use Illuminate\Http\Request;

class FinancialDashboardController extends Controller
{
    public function index()
    {
        return view('financial.dashboard');
    }

    public function getFinancialData(Request $request)
    {
        $startDate = $request->input('start_date', Carbon::now()->startOfMonth()->toDateString());
        $endDate = $request->input('end_date', Carbon::now()->endOfMonth()->toDateString());

        // Contas a pagar
        $totalPayables = AccountPayable::whereBetween('due_date', [$startDate, $endDate])
            ->where('status', '!=', 'cancelled')
            ->sum('amount');
        
        $paidPayables = AccountPayable::whereBetween('paid_date', [$startDate, $endDate])
            ->where('status', 'paid')
            ->sum('amount');
        
        $pendingPayables = AccountPayable::whereBetween('due_date', [$startDate, $endDate])
            ->where('status', 'pending')
            ->sum('amount');

        // Contas a receber
        $totalReceivables = AccountReceivable::whereBetween('due_date', [$startDate, $endDate])
            ->where('status', '!=', 'cancelled')
            ->sum('amount');
        
        $receivedReceivables = AccountReceivable::whereBetween('received_date', [$startDate, $endDate])
            ->where('status', 'received')
            ->sum('amount');
        
        $pendingReceivables = AccountReceivable::whereBetween('due_date', [$startDate, $endDate])
            ->where('status', 'pending')
            ->sum('amount');

        // Notas fiscais
        $totalInvoices = Invoice::whereBetween('issue_date', [$startDate, $endDate])
            ->where('status', '!=', 'cancelled')
            ->sum('total_amount');
        
        $paidInvoices = Invoice::whereBetween('issue_date', [$startDate, $endDate])
            ->where('status', 'paid')
            ->sum('total_amount');

        // Recibos
        $totalReceipts = Receipt::whereBetween('issue_date', [$startDate, $endDate])
            ->sum('amount');

        // Gráfico de receitas vs despesas por mês
        $monthsData = [];
        $start = Carbon::parse($startDate)->startOfMonth();
        $end = Carbon::parse($endDate)->endOfMonth();
        
        while ($start->lte($end)) {
            $monthStart = $start->copy()->startOfMonth();
            $monthEnd = $start->copy()->endOfMonth();
            
            $income = Receipt::whereBetween('issue_date', [$monthStart, $monthEnd])
                ->sum('amount');
            $income += AccountReceivable::whereBetween('received_date', [$monthStart, $monthEnd])
                ->where('status', 'received')
                ->sum('amount');
            
            $expense = AccountPayable::whereBetween('paid_date', [$monthStart, $monthEnd])
                ->where('status', 'paid')
                ->sum('amount');
            
            $monthsData[] = [
                'month' => $start->format('M/Y'),
                'income' => (float) $income,
                'expense' => (float) $expense,
            ];
            
            $start->addMonth();
        }

        // Previsão de faturamento (próximos 30, 60, 90 dias)
        $forecast30 = $this->calculateRevenueForecast(30);
        $forecast60 = $this->calculateRevenueForecast(60);
        $forecast90 = $this->calculateRevenueForecast(90);

        // Crescimento mensal
        $currentMonth = AccountReceivable::whereMonth('received_date', Carbon::now()->month)
            ->whereYear('received_date', Carbon::now()->year)
            ->where('status', 'received')
            ->sum('amount');
        
        $lastMonth = AccountReceivable::whereMonth('received_date', Carbon::now()->subMonth()->month)
            ->whereYear('received_date', Carbon::now()->subMonth()->year)
            ->where('status', 'received')
            ->sum('amount');
        
        $growth = $lastMonth > 0 ? (($currentMonth - $lastMonth) / $lastMonth) * 100 : 0;

        return response()->json([
            'total_payables' => (float) $totalPayables,
            'paid_payables' => (float) $paidPayables,
            'pending_payables' => (float) $pendingPayables,
            'total_receivables' => (float) $totalReceivables,
            'received_receivables' => (float) $receivedReceivables,
            'pending_receivables' => (float) $pendingReceivables,
            'total_invoices' => (float) $totalInvoices,
            'paid_invoices' => (float) $paidInvoices,
            'total_receipts' => (float) $totalReceipts,
            'months_data' => $monthsData,
            'forecast_30' => $forecast30,
            'forecast_60' => $forecast60,
            'forecast_90' => $forecast90,
            'growth_percentage' => round($growth, 2),
            'current_month_revenue' => (float) $currentMonth,
            'last_month_revenue' => (float) $lastMonth,
        ]);
    }

    private function calculateRevenueForecast(int $days): float
    {
        // Baseado em orçamentos aprovados não faturados
        $approvedBudgets = \App\Models\ProjectBudget::where('status', \App\Models\ProjectBudget::STATUS_APPROVED)
            ->whereDoesntHave('invoice')
            ->sum('total');
        
        // Histórico de conversão (simplificado - pode ser melhorado)
        $conversionRate = 0.7; // 70% de conversão estimada
        
        // Projeção baseada em média dos últimos meses
        $lastMonths = AccountReceivable::where('received_date', '>=', Carbon::now()->subMonths(3))
            ->where('status', 'received')
            ->get()
            ->groupBy(function($item) {
                return Carbon::parse($item->received_date)->format('Y-m');
            })
            ->map(function($group) {
                return $group->sum('amount');
            });
        
        $averageMonthly = $lastMonths->count() > 0 ? $lastMonths->avg() : 0;
        $daysInPeriod = $days / 30;
        
        return ($approvedBudgets * $conversionRate) + ($averageMonthly * $daysInPeriod);
    }
}
