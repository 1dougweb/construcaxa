<?php

namespace App\Http\Livewire;

use App\Models\AccountPayable;
use App\Models\AccountReceivable;
use App\Models\Invoice;
use App\Models\Receipt;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use Livewire\Component;

class FinancialDashboard extends Component
{
    public $period = 'month';
    public $startDate;
    public $endDate;
    public $financialData = [];

    protected $listeners = ['refreshFinancialDashboard' => '$refresh'];

    public function mount()
    {
        $this->setDefaultDates();
        $this->loadData();
    }

    public function updatedPeriod()
    {
        $this->setDefaultDates();
        $this->loadData();
    }

    public function updatedStartDate()
    {
        $this->loadData();
    }

    public function updatedEndDate()
    {
        $this->loadData();
    }

    protected function setDefaultDates()
    {
        switch ($this->period) {
            case 'today':
                $this->startDate = Carbon::today()->toDateString();
                $this->endDate = Carbon::today()->toDateString();
                break;
            case 'week':
                $this->startDate = Carbon::now()->startOfWeek()->toDateString();
                $this->endDate = Carbon::now()->endOfWeek()->toDateString();
                break;
            case 'month':
                $this->startDate = Carbon::now()->startOfMonth()->toDateString();
                $this->endDate = Carbon::now()->endOfMonth()->toDateString();
                break;
            case 'quarter':
                $this->startDate = Carbon::now()->startOfQuarter()->toDateString();
                $this->endDate = Carbon::now()->endOfQuarter()->toDateString();
                break;
            case 'year':
                $this->startDate = Carbon::now()->startOfYear()->toDateString();
                $this->endDate = Carbon::now()->endOfYear()->toDateString();
                break;
            default:
                $this->startDate = Carbon::now()->startOfMonth()->toDateString();
                $this->endDate = Carbon::now()->endOfMonth()->toDateString();
        }
    }

    protected function loadData()
    {
        $start = Carbon::parse($this->startDate)->startOfDay();
        $end = Carbon::parse($this->endDate)->endOfDay();

        // Total a Receber
        $totalReceivables = AccountReceivable::whereBetween('due_date', [$start, $end])
            ->where('status', '!=', 'cancelled')
            ->sum('amount');
        
        $receivedReceivables = AccountReceivable::whereBetween('received_date', [$start, $end])
            ->where('status', 'received')
            ->sum('amount');
        
        $pendingReceivables = AccountReceivable::whereBetween('due_date', [$start, $end])
            ->where('status', 'pending')
            ->sum('amount');

        // Total a Pagar
        $totalPayables = AccountPayable::whereBetween('due_date', [$start, $end])
            ->where('status', '!=', 'cancelled')
            ->sum('amount');
        
        $paidPayables = AccountPayable::whereBetween('paid_date', [$start, $end])
            ->where('status', 'paid')
            ->sum('amount');
        
        $pendingPayables = AccountPayable::whereBetween('due_date', [$start, $end])
            ->where('status', 'pending')
            ->sum('amount');

        // Notas Fiscais
        $totalInvoices = Invoice::whereBetween('issue_date', [$start, $end])
            ->where('status', '!=', 'cancelled')
            ->sum('total_amount');
        
        $paidInvoices = Invoice::whereBetween('issue_date', [$start, $end])
            ->where('status', 'paid')
            ->sum('total_amount');

        // Recibos
        $totalReceipts = Receipt::whereBetween('issue_date', [$start, $end])
            ->sum('amount');

        // Saldo
        $balance = $receivedReceivables - $paidPayables;

        // Gráfico de Receitas vs Despesas por mês
        $monthsData = $this->getMonthsData($start, $end);

        // Contas por status
        $payablesByStatus = $this->getPayablesByStatus($start, $end);
        $receivablesByStatus = $this->getReceivablesByStatus($start, $end);

        // Previsão de faturamento
        $forecast30 = $this->calculateRevenueForecast(30);
        $forecast60 = $this->calculateRevenueForecast(60);
        $forecast90 = $this->calculateRevenueForecast(90);

        // Crescimento mensal
        $growthData = $this->calculateGrowth();

        $this->financialData = [
            'total_receivables' => (float) $totalReceivables,
            'received_receivables' => (float) $receivedReceivables,
            'pending_receivables' => (float) $pendingReceivables,
            'total_payables' => (float) $totalPayables,
            'paid_payables' => (float) $paidPayables,
            'pending_payables' => (float) $pendingPayables,
            'total_invoices' => (float) $totalInvoices,
            'paid_invoices' => (float) $paidInvoices,
            'total_receipts' => (float) $totalReceipts,
            'balance' => (float) $balance,
            'months_data' => $monthsData,
            'payables_by_status' => $payablesByStatus,
            'receivables_by_status' => $receivablesByStatus,
            'forecast_30' => $forecast30,
            'forecast_60' => $forecast60,
            'forecast_90' => $forecast90,
            'growth' => $growthData,
        ];

        // Dispatch event for chart updates
        $this->dispatch('updateFinancialCharts', [
            'months_data' => $monthsData,
            'payables_by_status' => $payablesByStatus,
            'receivables_by_status' => $receivablesByStatus,
        ]);
    }

    protected function getMonthsData($start, $end)
    {
        $data = [];
        $current = Carbon::parse($start)->startOfMonth();
        $endDate = Carbon::parse($end)->endOfMonth();

        while ($current->lte($endDate)) {
            $monthStart = $current->copy()->startOfMonth();
            $monthEnd = $current->copy()->endOfMonth();

            $income = Receipt::whereBetween('issue_date', [$monthStart, $monthEnd])
                ->sum('amount');
            $income += AccountReceivable::whereBetween('received_date', [$monthStart, $monthEnd])
                ->where('status', 'received')
                ->sum('amount');
            
            $expense = AccountPayable::whereBetween('paid_date', [$monthStart, $monthEnd])
                ->where('status', 'paid')
                ->sum('amount');
            
            $data[] = [
                'month' => $current->format('M/Y'),
                'income' => (float) $income,
                'expense' => (float) $expense,
            ];

            $current->addMonth();
        }

        return $data;
    }

    protected function getPayablesByStatus($start, $end)
    {
        return [
            'pending' => (float) AccountPayable::whereBetween('due_date', [$start, $end])
                ->where('status', 'pending')
                ->sum('amount'),
            'paid' => (float) AccountPayable::whereBetween('paid_date', [$start, $end])
                ->where('status', 'paid')
                ->sum('amount'),
            'overdue' => (float) AccountPayable::whereBetween('due_date', [$start, $end])
                ->where('status', 'overdue')
                ->sum('amount'),
        ];
    }

    protected function getReceivablesByStatus($start, $end)
    {
        return [
            'pending' => (float) AccountReceivable::whereBetween('due_date', [$start, $end])
                ->where('status', 'pending')
                ->sum('amount'),
            'received' => (float) AccountReceivable::whereBetween('received_date', [$start, $end])
                ->where('status', 'received')
                ->sum('amount'),
            'overdue' => (float) AccountReceivable::whereBetween('due_date', [$start, $end])
                ->where('status', 'overdue')
                ->sum('amount'),
        ];
    }

    protected function calculateRevenueForecast(int $days): float
    {
        // Baseado em orçamentos aprovados não faturados
        $approvedBudgets = \App\Models\ProjectBudget::where('status', \App\Models\ProjectBudget::STATUS_APPROVED)
            ->whereDoesntHave('invoice')
            ->sum('total');
        
        // Taxa de conversão estimada
        $conversionRate = 0.7;
        
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

    protected function calculateGrowth()
    {
        $currentMonth = AccountReceivable::whereMonth('received_date', Carbon::now()->month)
            ->whereYear('received_date', Carbon::now()->year)
            ->where('status', 'received')
            ->sum('amount');
        
        $lastMonth = AccountReceivable::whereMonth('received_date', Carbon::now()->subMonth()->month)
            ->whereYear('received_date', Carbon::now()->subMonth()->year)
            ->where('status', 'received')
            ->sum('amount');
        
        $growth = $lastMonth > 0 ? (($currentMonth - $lastMonth) / $lastMonth) * 100 : 0;

        return [
            'current' => (float) $currentMonth,
            'last' => (float) $lastMonth,
            'percentage' => round($growth, 2),
        ];
    }

    public function render()
    {
        return view('livewire.financial-dashboard');
    }
}
