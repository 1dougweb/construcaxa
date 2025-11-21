<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Dashboard') }}
        </h2>
    </x-slot>

    <div class="py-6">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-xl sm:rounded-lg">
                <div class="p-6">
                    <h1 class="text-2xl font-bold text-gray-900 mb-6">Bem-vindo ao Sistema de Gestão de Construcaxa</h1>
                    

                    @hasanyrole('manager|admin')
                    <!-- Cards Financeiros (Gestão) -->
                    @can('manage finances')
                    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">
                        @php
                            try {
                                $currentMonth = \Carbon\Carbon::now()->startOfMonth();
                                $lastMonth = \Carbon\Carbon::now()->subMonth()->startOfMonth();
                                
                                $totalReceivablesCurrent = \App\Models\AccountReceivable::whereMonth('received_date', $currentMonth->month)
                                    ->whereYear('received_date', $currentMonth->year)
                                    ->where('status', 'received')
                                    ->sum('amount') ?? 0;
                                
                                $totalReceivablesLast = \App\Models\AccountReceivable::whereMonth('received_date', $lastMonth->month)
                                    ->whereYear('received_date', $lastMonth->year)
                                    ->where('status', 'received')
                                    ->sum('amount') ?? 0;
                                
                                $totalPayablesCurrent = \App\Models\AccountPayable::whereMonth('paid_date', $currentMonth->month)
                                    ->whereYear('paid_date', $currentMonth->year)
                                    ->where('status', 'paid')
                                    ->sum('amount') ?? 0;
                                
                                $totalPayablesLast = \App\Models\AccountPayable::whereMonth('paid_date', $lastMonth->month)
                                    ->whereYear('paid_date', $lastMonth->year)
                                    ->where('status', 'paid')
                                    ->sum('amount') ?? 0;
                                
                                $balanceCurrent = $totalReceivablesCurrent - $totalPayablesCurrent;
                                $balanceLast = $totalReceivablesLast - $totalPayablesLast;
                                
                                $growthReceivables = $totalReceivablesLast > 0 ? (($totalReceivablesCurrent - $totalReceivablesLast) / $totalReceivablesLast) * 100 : 0;
                                $growthPayables = $totalPayablesLast > 0 ? (($totalPayablesCurrent - $totalPayablesLast) / $totalPayablesLast) * 100 : 0;
                                
                                // Previsão próximos 30 dias - Orçamentos aprovados sem invoice + contas a receber pendentes
                                $approvedBudgetsWithoutInvoice = \App\Models\ProjectBudget::where('status', \App\Models\ProjectBudget::STATUS_APPROVED)
                                    ->whereDoesntHave('invoice')
                                    ->sum('total') ?? 0;
                                
                                // Contas a receber pendentes que vencem nos próximos 30 dias
                                $pendingReceivablesNext30Days = \App\Models\AccountReceivable::where('status', 'pending')
                                    ->whereBetween('due_date', [\Carbon\Carbon::now(), \Carbon\Carbon::now()->addDays(30)])
                                    ->sum('amount') ?? 0;
                                
                                // Previsão = 70% dos orçamentos aprovados + 80% das contas a receber que vencem em 30 dias
                                $forecast30 = ($approvedBudgetsWithoutInvoice * 0.7) + ($pendingReceivablesNext30Days * 0.8);
                                
                                // Total pendente
                                $totalReceivablesPending = \App\Models\AccountReceivable::where('status', 'pending')->sum('amount') ?? 0;
                                $totalPayablesPending = \App\Models\AccountPayable::where('status', 'pending')->sum('amount') ?? 0;
                            } catch (\Exception $e) {
                                $totalReceivablesCurrent = 0;
                                $totalReceivablesLast = 0;
                                $totalPayablesCurrent = 0;
                                $totalPayablesLast = 0;
                                $balanceCurrent = 0;
                                $balanceLast = 0;
                                $growthReceivables = 0;
                                $growthPayables = 0;
                                $forecast30 = 0;
                                $totalReceivablesPending = 0;
                                $totalPayablesPending = 0;
                            }
                        @endphp

                        <!-- Total a Receber -->
                        <div class="bg-green-600 rounded-lg shadow-lg p-6">
                            <div class="flex justify-between items-center">
                                <div>
                                    <h3 class="text-white text-lg font-semibold">A Receber</h3>
                                    <p class="text-white text-3xl font-bold">R$ {{ number_format($totalReceivablesPending, 2, ',', '.') }}</p>
                                    <p class="text-white/80 mt-2 text-xs">
                                        @if($growthReceivables > 0)
                                            <span class="text-green-200">↑ {{ number_format(abs($growthReceivables), 2) }}%</span>
                                        @elseif($growthReceivables < 0)
                                            <span class="text-yellow-200">↓ {{ number_format(abs($growthReceivables), 2) }}%</span>
                                        @else
                                            <span class="text-white/60">Sem variação</span>
                                        @endif
                                        vs mês anterior
                                    </p>
                                </div>
                                <div class="p-3 bg-white/20 rounded-full">
                                    <svg class="w-8 h-8 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1"></path>
                                    </svg>
                                </div>
                            </div>
                        </div>

                        <!-- Total a Pagar -->
                        <div class="bg-red-600 rounded-lg shadow-lg p-6">
                            <div class="flex justify-between items-center">
                                <div>
                                    <h3 class="text-white text-lg font-semibold">A Pagar</h3>
                                    <p class="text-white text-3xl font-bold">R$ {{ number_format($totalPayablesPending, 2, ',', '.') }}</p>
                                    <p class="text-white/80 mt-2 text-xs">
                                        @if($growthPayables > 0)
                                            <span class="text-yellow-200">↑ {{ number_format(abs($growthPayables), 2) }}%</span>
                                        @elseif($growthPayables < 0)
                                            <span class="text-green-200">↓ {{ number_format(abs($growthPayables), 2) }}%</span>
                                        @else
                                            <span class="text-white/60">Sem variação</span>
                                        @endif
                                        vs mês anterior
                                    </p>
                                </div>
                                <div class="p-3 bg-white/20 rounded-full">
                                    <svg class="w-8 h-8 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 9V7a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2m2 4h10a2 2 0 002-2v-6a2 2 0 00-2-2H9a2 2 0 00-2 2v6a2 2 0 002 2zm7-5a2 2 0 11-4 0 2 2 0 014 0z"></path>
                                    </svg>
                                </div>
                            </div>
                        </div>

                        <!-- Saldo do Mês -->
                        <div class="bg-blue-500 rounded-lg shadow-lg p-6">
                            <div class="flex justify-between items-center">
                                <div>
                                    <h3 class="text-white text-lg font-semibold">Saldo do Mês</h3>
                                    <p class="text-white text-3xl font-bold">R$ {{ number_format($balanceCurrent, 2, ',', '.') }}</p>
                                    <p class="text-white/80 mt-2 text-xs">
                                        @php
                                            $balanceGrowth = $balanceLast > 0 ? (($balanceCurrent - $balanceLast) / $balanceLast) * 100 : 0;
                                        @endphp
                                        @if($balanceGrowth > 0)
                                            <span class="text-green-200">↑ {{ number_format(abs($balanceGrowth), 2) }}%</span>
                                        @elseif($balanceGrowth < 0)
                                            <span class="text-yellow-200">↓ {{ number_format(abs($balanceGrowth), 2) }}%</span>
                                        @else
                                            <span class="text-white/60">Sem variação</span>
                                        @endif
                                        vs mês anterior
                                    </p>
                                </div>
                                <div class="p-3 bg-white/20 rounded-full">
                                    <svg class="w-8 h-8 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"></path>
                                    </svg>
                                </div>
                            </div>
                        </div>

                        <!-- Previsão de Faturamento -->
                        <div class="bg-amber-400 rounded-lg shadow-lg p-6" style="opacity: 1; visibility: visible;">
                            <div class="flex justify-between items-center">
                                <div>
                                    <h3 class="text-white text-lg font-semibold">Previsão 30 dias</h3>
                                    <p class="text-white text-3xl font-bold">R$ {{ number_format($forecast30, 2, ',', '.') }}</p>
                                    <p class="text-white/80 mt-2 text-xs">
                                        @if($forecast30 > 0)
                                            Orçamentos aprovados + Contas a receber
                                        @else
                                            Sem previsão disponível
                                        @endif
                                    </p>
                                </div>
                                <div class="p-3 bg-white/20 rounded-full">
                                    <svg class="w-8 h-8 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7h8m0 0v8m0-8l-8 8-4-4-6 6"></path>
                                    </svg>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Gráficos Financeiros -->
                    @php
                        try {
                            // Dados para gráficos - últimos 6 meses
                            $monthsData = [];
                            $incomeData = [];
                            $expenseData = [];
                            
                            for ($i = 5; $i >= 0; $i--) {
                                $month = \Carbon\Carbon::now()->subMonths($i);
                                $monthsData[] = $month->format('M/Y');
                                
                                $income = \App\Models\AccountReceivable::whereMonth('received_date', $month->month)
                                    ->whereYear('received_date', $month->year)
                                    ->where('status', 'received')
                                    ->sum('amount') ?? 0;
                                $incomeData[] = $income;
                                
                                $expense = \App\Models\AccountPayable::whereMonth('paid_date', $month->month)
                                    ->whereYear('paid_date', $month->year)
                                    ->where('status', 'paid')
                                    ->sum('amount') ?? 0;
                                $expenseData[] = $expense;
                            }
                            
                            // Contas por status
                            $payablesByStatus = [
                                'pending' => \App\Models\AccountPayable::where('status', 'pending')->count(),
                                'paid' => \App\Models\AccountPayable::where('status', 'paid')->count(),
                                'overdue' => \App\Models\AccountPayable::where('status', 'overdue')->count(),
                            ];
                            
                            $receivablesByStatus = [
                                'pending' => \App\Models\AccountReceivable::where('status', 'pending')->count(),
                                'received' => \App\Models\AccountReceivable::where('status', 'received')->count(),
                                'overdue' => \App\Models\AccountReceivable::where('status', 'overdue')->count(),
                            ];
                        } catch (\Exception $e) {
                            $monthsData = [];
                            $incomeData = [];
                            $expenseData = [];
                            $payablesByStatus = ['pending' => 0, 'paid' => 0, 'overdue' => 0];
                            $receivablesByStatus = ['pending' => 0, 'received' => 0, 'overdue' => 0];
                        }
                    @endphp

                    <div class="grid grid-cols-1 lg:grid-cols-2 gap-8 mb-8">
                        <!-- Gráfico Receitas vs Despesas -->
                        <div class="bg-white overflow-hidden shadow-xl sm:rounded-lg p-6">
                            <h2 class="text-xl font-semibold text-gray-900 mb-4">Receitas vs Despesas</h2>
                            <div class="chart-container" style="position: relative; height:300px;">
                                <canvas id="incomeExpenseChart"></canvas>
                            </div>
                        </div>

                        <!-- Gráfico Contas a Pagar por Status -->
                        <div class="bg-white overflow-hidden shadow-xl sm:rounded-lg p-6">
                            <h2 class="text-xl font-semibold text-gray-900 mb-4">Contas a Pagar por Status</h2>
                            <div class="chart-container" style="position: relative; height:300px;">
                                <canvas id="payablesStatusChart"></canvas>
                            </div>
                        </div>
                    </div>

                    <div class="bg-white overflow-hidden shadow-xl sm:rounded-lg p-6 mb-8">
                        <h2 class="text-xl font-semibold text-gray-900 mb-4">Contas a Receber por Status</h2>
                        <div class="chart-container" style="position: relative; height:300px;">
                            <canvas id="receivablesStatusChart"></canvas>
                        </div>
                    </div>

                    @push('scripts')
                    <script src="https://cdn.jsdelivr.net/npm/chart.js@3.9.1/dist/chart.min.js"></script>
                    <script>
                        // Aguardar Chart.js carregar
                        function initFinancialCharts() {
                            if (typeof Chart === 'undefined') {
                                console.warn('Aguardando Chart.js...');
                                setTimeout(initFinancialCharts, 100);
                                return;
                            }

                            // Dados para os gráficos
                            const monthsData = @json($monthsData ?? []);
                            const incomeData = @json($incomeData ?? []);
                            const expenseData = @json($expenseData ?? []);
                            const payablesByStatus = @json($payablesByStatus ?? ['pending' => 0, 'paid' => 0, 'overdue' => 0]);
                            const receivablesByStatus = @json($receivablesByStatus ?? ['pending' => 0, 'received' => 0, 'overdue' => 0]);

                            console.log('Inicializando gráficos financeiros...', {
                                monthsData,
                                incomeData,
                                expenseData,
                                payablesByStatus,
                                receivablesByStatus
                            });

                            // Gráfico Receitas vs Despesas
                            const incomeExpenseCtx = document.getElementById('incomeExpenseChart');
                            if (incomeExpenseCtx) {
                                // Destruir gráfico existente se houver
                                if (window.incomeExpenseChartInstance) {
                                    window.incomeExpenseChartInstance.destroy();
                                }
                                
                                window.incomeExpenseChartInstance = new Chart(incomeExpenseCtx, {
                                    type: 'line',
                                    data: {
                                        labels: monthsData.length > 0 ? monthsData : ['Sem dados'],
                                        datasets: [
                                            {
                                                label: 'Receitas',
                                                data: incomeData.length > 0 ? incomeData : [0],
                                                borderColor: '#16a34a', // green-600
                                                backgroundColor: 'rgba(22, 163, 74, 0.1)', // green-600 com opacidade
                                                borderWidth: 3,
                                                tension: 0.4,
                                                fill: true,
                                                pointRadius: 5,
                                                pointHoverRadius: 7
                                            },
                                            {
                                                label: 'Despesas',
                                                data: expenseData.length > 0 ? expenseData : [0],
                                                borderColor: '#dc2626', // red-600
                                                backgroundColor: 'rgba(220, 38, 38, 0.1)', // red-600 com opacidade
                                                borderWidth: 3,
                                                tension: 0.4,
                                                fill: true,
                                                pointRadius: 5,
                                                pointHoverRadius: 7
                                            }
                                        ]
                                    },
                                    options: {
                                        responsive: true,
                                        maintainAspectRatio: false,
                                        plugins: {
                                            legend: {
                                                position: 'top',
                                            },
                                            title: {
                                                display: false
                                            },
                                            tooltip: {
                                                callbacks: {
                                                    label: function(context) {
                                                        return context.dataset.label + ': R$ ' + context.parsed.y.toLocaleString('pt-BR', {minimumFractionDigits: 2});
                                                    }
                                                }
                                            }
                                        },
                                        scales: {
                                            y: {
                                                beginAtZero: true,
                                                ticks: {
                                                    callback: function(value) {
                                                        return 'R$ ' + Math.round(value).toLocaleString('pt-BR');
                                                    }
                                                }
                                            }
                                        }
                                    }
                                });
                            } else {
                                console.error('Canvas incomeExpenseChart não encontrado');
                            }

                            // Gráfico Contas a Pagar por Status
                            const payablesStatusCtx = document.getElementById('payablesStatusChart');
                            if (payablesStatusCtx) {
                                // Destruir gráfico existente se houver
                                if (window.payablesStatusChartInstance) {
                                    window.payablesStatusChartInstance.destroy();
                                }
                                
                                window.payablesStatusChartInstance = new Chart(payablesStatusCtx, {
                                    type: 'line',
                                    data: {
                                        labels: ['Pendente', 'Pago', 'Atrasado'],
                                        datasets: [{
                                            label: 'Contas a Pagar',
                                            data: [
                                                payablesByStatus.pending || 0,
                                                payablesByStatus.paid || 0,
                                                payablesByStatus.overdue || 0
                                            ],
                                            borderColor: '#dc2626', // red-600
                                            backgroundColor: 'rgba(220, 38, 38, 0.1)', // red-600 com opacidade
                                            borderWidth: 3,
                                            tension: 0.4,
                                            fill: true,
                                            pointRadius: 6,
                                            pointHoverRadius: 8,
                                            pointBackgroundColor: '#dc2626',
                                            pointBorderColor: '#ffffff',
                                            pointBorderWidth: 2
                                        }]
                                    },
                                    options: {
                                        responsive: true,
                                        maintainAspectRatio: false,
                                        plugins: {
                                            legend: {
                                                display: false
                                            },
                                            tooltip: {
                                                callbacks: {
                                                    label: function(context) {
                                                        const label = context.label || '';
                                                        const value = context.parsed.y || 0;
                                                        return label + ': ' + value + ' contas';
                                                    }
                                                }
                                            }
                                        },
                                        scales: {
                                            y: {
                                                beginAtZero: true,
                                                ticks: {
                                                    stepSize: 1,
                                                    callback: function(value) {
                                                        return Math.round(value);
                                                    }
                                                },
                                                grid: {
                                                    color: 'rgba(0, 0, 0, 0.05)'
                                                }
                                            },
                                            x: {
                                                grid: {
                                                    display: false
                                                }
                                            }
                                        }
                                    }
                                });
                            } else {
                                console.error('Canvas payablesStatusChart não encontrado');
                            }

                            // Gráfico Contas a Receber por Status
                            const receivablesStatusCtx = document.getElementById('receivablesStatusChart');
                            if (receivablesStatusCtx) {
                                // Destruir gráfico existente se houver
                                if (window.receivablesStatusChartInstance) {
                                    window.receivablesStatusChartInstance.destroy();
                                }
                                
                                window.receivablesStatusChartInstance = new Chart(receivablesStatusCtx, {
                                    type: 'line',
                                    data: {
                                        labels: ['Pendente', 'Recebido', 'Atrasado'],
                                        datasets: [{
                                            label: 'Contas a Receber',
                                            data: [
                                                receivablesByStatus.pending || 0,
                                                receivablesByStatus.received || 0,
                                                receivablesByStatus.overdue || 0
                                            ],
                                            borderColor: '#16a34a', // green-600
                                            backgroundColor: 'rgba(22, 163, 74, 0.1)', // green-600 com opacidade
                                            borderWidth: 3,
                                            tension: 0.4,
                                            fill: true,
                                            pointRadius: 6,
                                            pointHoverRadius: 8,
                                            pointBackgroundColor: '#16a34a',
                                            pointBorderColor: '#ffffff',
                                            pointBorderWidth: 2
                                        }]
                                    },
                                    options: {
                                        responsive: true,
                                        maintainAspectRatio: false,
                                        plugins: {
                                            legend: {
                                                display: false
                                            },
                                            tooltip: {
                                                callbacks: {
                                                    label: function(context) {
                                                        const label = context.label || '';
                                                        const value = context.parsed.y || 0;
                                                        return label + ': ' + value + ' contas';
                                                    }
                                                }
                                            }
                                        },
                                        scales: {
                                            y: {
                                                beginAtZero: true,
                                                ticks: {
                                                    stepSize: 1,
                                                    callback: function(value) {
                                                        return Math.round(value);
                                                    }
                                                },
                                                grid: {
                                                    color: 'rgba(0, 0, 0, 0.05)'
                                                }
                                            },
                                            x: {
                                                grid: {
                                                    display: false
                                                }
                                            }
                                        }
                                    }
                                });
                            } else {
                                console.error('Canvas receivablesStatusChart não encontrado');
                            }
                        }

                        // Inicializar quando DOM estiver pronto e Chart.js carregado
                        if (document.readyState === 'loading') {
                            document.addEventListener('DOMContentLoaded', initFinancialCharts);
                        } else {
                            initFinancialCharts();
                        }
                    </script>
                    @endpush
                    @endcan

                    <!-- Cards de resumo (Gestão) -->
                    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">
                        <!-- Produtos cadastrados -->
                        <div class="bg-blue-500 rounded-lg shadow-lg p-6">
                            <div class="flex justify-between items-center">
                                <div>
                                    <h3 class="text-white text-lg font-semibold">Produtos</h3>
                                    <p class="text-white text-3xl font-bold">{{ \App\Models\Product::count() }}</p>
                                </div>
                                <div class="p-3 bg-white/20 rounded-full">
                                    <svg class="w-8 h-8 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"></path>
                                    </svg>
                                </div>
                            </div>
                            <p class="text-white/80 mt-2 text-sm">Total de produtos cadastrados</p>
                        </div>

                        <!-- Requisições pendentes -->
                        <div class="bg-amber-400 rounded-lg shadow-lg p-6">
                            <div class="flex justify-between items-center">
                                <div>
                                    <h3 class="text-white text-lg font-semibold">Requisições</h3>
                                    <p class="text-white text-3xl font-bold">{{ \App\Models\MaterialRequest::count() }}</p>
                                </div>
                                <div class="p-3 bg-white/20 rounded-full">
                                    <svg class="w-8 h-8 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"></path>
                                    </svg>
                                </div>
                            </div>
                            <p class="text-white/80 mt-2 text-sm">Total de requisições</p>
                        </div>

                        <!-- Alertas de Estoque Baixo -->
                        <div class="bg-red-600 rounded-lg shadow-lg p-6">
                            <div class="flex justify-between items-center">
                                <div>
                                    <h3 class="text-white text-lg font-semibold">Estoque Baixo</h3>
                                    <p class="text-white text-3xl font-bold">{{ \App\Models\Product::whereRaw('stock <= min_stock')->count() }}</p>
                                </div>
                                <div class="p-3 bg-white/20 rounded-full">
                                    <svg class="w-8 h-8 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"></path>
                                    </svg>
                                </div>
                            </div>
                            <p class="text-white/80 mt-2 text-sm">Produtos abaixo do estoque mínimo</p>
                        </div>

                        <!-- Equipamentos -->
                        <div class="bg-purple-400 rounded-lg shadow-lg p-6">
                            <div class="flex justify-between items-center">
                                <div>
                                    <h3 class="text-white text-lg font-semibold">Equipamentos</h3>
                                    <p class="text-white text-3xl font-bold">{{ \App\Models\Equipment::count() }}</p>
                                </div>
                                <div class="p-3 bg-white/20 rounded-full">
                                    <svg class="w-8 h-8 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19.428 15.428a2 2 0 00-1.022-.547l-2.387-.477a6 6 0 00-3.86.517l-.318.158a6 6 0 01-3.86.517L6.05 15.21a2 2 0 00-1.806.547M8 4h8l-1 1v5.172a2 2 0 00.586 1.414l5 5c1.26 1.26.367 3.414-1.415 3.414H4.828c-1.782 0-2.674-2.154-1.414-3.414l5-5A2 2 0 009 10.172V5L8 4z"></path>
                                    </svg>
                                </div>
                            </div>
                            <p class="text-white/80 mt-2 text-sm">Total de equipamentos cadastrados</p>
                        </div>

                        
                    </div>

                    <!-- Seções principais (Gestão) -->
                    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
                        <!-- Requisições recentes -->
                        <div class="lg:col-span-2 bg-white border border-gray-200 rounded-lg shadow-md">
                            <div class="p-4 border-b border-gray-200">
                                <h2 class="text-lg font-semibold text-gray-900">Requisições Recentes</h2>
                            </div>
                            <div class="p-4">
                                <div class="overflow-x-auto">
                                    <table class="min-w-full divide-y divide-gray-200">
                                        <thead class="bg-gray-50">
                                            <tr>
                                                <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Número</th>
                                                <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Data</th>
                                                <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Ações</th>
                                            </tr>
                                        </thead>
                                        <tbody class="bg-white divide-y divide-gray-200">
                                            @foreach(\App\Models\MaterialRequest::with('items')->latest()->take(5)->get() as $request)
                                                <tr>
                                                    <td class="px-6 py-4 whitespace-nowrap">
                                                        <div class="text-sm font-medium text-gray-900">
                                                            #{{ $request->number }}
                                                        </div>
                                                    </td>
                                                    <td class="px-6 py-4 whitespace-nowrap">
                                                        <div class="text-sm text-gray-500">
                                                            {{ $request->created_at->format('d/m/Y H:i') }}
                                                        </div>
                                                    </td>
                                                    <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                                                        <a href="{{ route('material-requests.show', $request) }}" class="text-indigo-600 hover:text-indigo-900">
                                                            Detalhes
                                                        </a>
                                                    </td>
                                                </tr>
                                            @endforeach
                                        </tbody>
                                    </table>
                                </div>
                                
                                <div class="mt-4 text-right">
                                    <a href="{{ route('material-requests.index') }}" class="text-indigo-600 hover:text-indigo-900 font-medium">
                                        Ver todas as requisições →
                                    </a>
                                </div>
                            </div>
                        </div>

                        <!-- Produtos em estoque baixo -->
                        <div class="bg-white border border-gray-200 rounded-lg shadow-md">
                            <div class="p-4 border-b border-gray-200">
                                <h2 class="text-lg font-semibold text-gray-900">Produtos com Estoque Baixo</h2>
                            </div>
                            <div class="p-4">
                                <ul class="divide-y divide-gray-200">
                                    @foreach(\App\Models\Product::whereRaw('stock <= min_stock')->orderBy('stock', 'asc')->take(5)->get() as $product)
                                        <li class="py-3">
                                            <div class="flex items-center justify-between">
                                                <div>
                                                    <h3 class="text-sm font-medium text-gray-900">{{ $product->name }}</h3>
                                                    <p class="text-xs text-gray-500">SKU: {{ $product->sku }}</p>
                                                </div>
                                                <div class="text-right">
                                                    <p class="text-sm font-medium text-red-600">{{ $product->stock }} / {{ $product->min_stock }}</p>
                                                    <p class="text-xs text-gray-500">Estoque / Mínimo</p>
                                                </div>
                                            </div>
                                            <div class="mt-2 w-full bg-gray-200 rounded-full h-2">
                                                @php
                                                    $percentage = $product->min_stock > 0 ? min(100, ($product->stock / $product->min_stock) * 100) : 0;
                                                    $colorClass = $percentage <= 30 ? 'bg-red-600' : ($percentage <= 70 ? 'bg-yellow-500' : 'bg-green-500');
                                                @endphp
                                                <div class="{{ $colorClass }} h-2 rounded-full" style="width: {{ $percentage }}%"></div>
                                            </div>
                                        </li>
                                    @endforeach
                                </ul>
                                
                                <div class="mt-4 text-right">
                                    <a href="{{ route('products.index') }}" class="text-indigo-600 hover:text-indigo-900 font-medium">
                                        Ver todos os produtos →
                                    </a>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Seção de Ações Rápidas (Gestão) -->
                    <div class="mt-8">
                        <h2 class="text-lg font-semibold text-gray-900 mb-4">Ações Rápidas</h2>
                        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-4">
                            @can('create service-orders')
                            <a href="{{ route('material-requests.create') }}" class="flex items-center p-4 bg-indigo-50 hover:bg-indigo-100 rounded-lg transition-all">
                                <div class="p-3 bg-indigo-100 rounded-full mr-4">
                                    <svg class="w-6 h-6 text-indigo-600" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 13h6m-3-3v6m5 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                                    </svg>
                                </div>
                                <div>
                                    <h3 class="text-sm font-medium text-gray-900">Nova Requisição</h3>
                                    <p class="text-xs text-gray-500">Criar nova requisição de material</p>
                                </div>
                            </a>
                            @endcan

                            @can('create products')
                            <a href="{{ route('products.create') }}" class="flex items-center p-4 bg-green-50 hover:bg-green-100 rounded-lg transition-all">
                                <div class="p-3 bg-green-100 rounded-full mr-4">
                                    <svg class="w-6 h-6 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v3m0 0v3m0-3h3m-3 0H9m12 0a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                    </svg>
                                </div>
                                <div>
                                    <h3 class="text-sm font-medium text-gray-900">Novo Produto</h3>
                                    <p class="text-xs text-gray-500">Adicionar produto ao estoque</p>
                                </div>
                            </a>
                            @endcan

                            @can('create products')
                            <a href="{{ route('equipment.create') }}" class="flex items-center p-4 bg-purple-50 hover:bg-purple-100 rounded-lg transition-all">
                                <div class="p-3 bg-purple-100 rounded-full mr-4">
                                    <svg class="w-6 h-6 text-purple-600" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19.428 15.428a2 2 0 00-1.022-.547l-2.387-.477a6 6 0 00-3.86.517l-.318.158a6 6 0 01-3.86.517L6.05 15.21a2 2 0 00-1.806.547M8 4h8l-1 1v5.172a2 2 0 00.586 1.414l5 5c1.26 1.26.367 3.414-1.415 3.414H4.828c-1.782 0-2.674-2.154-1.414-3.414l5-5A2 2 0 009 10.172V5L8 4z"></path>
                                    </svg>
                                </div>
                                <div>
                                    <h3 class="text-sm font-medium text-gray-900">Novo Equipamento</h3>
                                    <p class="text-xs text-gray-500">Cadastrar equipamento/ferramenta</p>
                                </div>
                            </a>
                            @endcan

                            @can('create service-orders')
                            <a href="{{ route('equipment-requests.create') }}" class="flex items-center p-4 bg-amber-100 hover:bg-orange-100 rounded-lg transition-all">
                                <div class="p-3 bg-orange-100 rounded-full mr-4">
                                    <svg class="w-6 h-6 text-orange-600" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 13h6m-3-3v6m5 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                                    </svg>
                                </div>
                                <div>
                                    <h3 class="text-sm font-medium text-gray-900">Requisição de Equipamento</h3>
                                    <p class="text-xs text-gray-500">Solicitar empréstimo/devolução</p>
                                </div>
                            </a>
                            @endcan

                            @can('manage stock')
                            <a href="{{ route('stock-movements.create') }}" class="flex items-center p-4 bg-blue-50 hover:bg-blue-100 rounded-lg transition-all">
                                <div class="p-3 bg-blue-100 rounded-full mr-4">
                                    <svg class="w-6 h-6 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 16V4m0 0L3 8m4-4l4 4m6 0v12m0 0l4-4m-4 4l-4-4"></path>
                                    </svg>
                                </div>
                                <div>
                                    <h3 class="text-sm font-medium text-gray-900">Movimentação</h3>
                                    <p class="text-xs text-gray-500">Registrar entrada/saída manual</p>
                                </div>
                            </a>
                            @endcan

                            @can('view reports')
                            <a href="{{ route('reports.index') }}" class="flex items-center p-4 bg-purple-50 hover:bg-purple-100 rounded-lg transition-all">
                                <div class="p-3 bg-purple-100 rounded-full mr-4">
                                    <svg class="w-6 h-6 text-purple-600" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 17v-2m3 2v-4m3 4v-6m2 10H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                                    </svg>
                                </div>
                                <div>
                                    <h3 class="text-sm font-medium text-gray-900">Relatórios</h3>
                                    <p class="text-xs text-gray-500">Gerar relatórios de estoque</p>
                                </div>
                            </a>
                            @endcan
                        </div>
                    </div>
                    @endhasanyrole

                    @role('employee')
                    <!-- Dashboard do Funcionário -->
                    @php
                        $userId = auth()->id();
                        $todayStart = now()->startOfDay();
                        $todayEnd = now()->endOfDay();
                        $monthStart = now()->startOfMonth();
                        $monthEnd = now()->endOfMonth();
                        $workedTodayMin = \App\Models\Attendance::sumWorkedMinutes($userId, $todayStart, $todayEnd);
                        $workedMonthMin = \App\Models\Attendance::sumWorkedMinutes($userId, $monthStart, $monthEnd);
                        $employee = \App\Models\Employee::where('user_id', $userId)->first();
                        $expectedDaily = (float)($employee->expected_daily_hours ?? 8);
                        $expectedTodayMin = (int)round($expectedDaily * 60);
                        $missingTodayMin = max(0, $expectedTodayMin - $workedTodayMin);
                        $hourlyRate = (float)($employee->hourly_rate ?? 0);
                        $payableMonth = $hourlyRate > 0 ? ($workedMonthMin / 60) * $hourlyRate : null;
                        $today = now()->toDateString();
                        $entries = \App\Models\Attendance::where('user_id', auth()->id())->whereDate('punched_date', $today)->orderBy('punched_at')->get();
                        $completedToday = $entries->count() >= 2;
                    @endphp

                    <!-- Cards de Estatísticas -->
                    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">
                        <!-- Horas Trabalhadas Hoje -->
                        <div class="bg-gradient-to-r from-blue-500 to-blue-600 rounded-xl shadow-lg p-6 text-white">
                            <div class="flex items-center justify-between">
                                <div>
                                    <p class="text-blue-100 text-sm font-medium">Horas Hoje</p>
                                    <p class="text-2xl font-bold">{{ sprintf('%02d:%02d', intdiv($workedTodayMin,60), $workedTodayMin%60) }}</p>
                                </div>
                                <div class="bg-blue-400 bg-opacity-30 rounded-full p-3">
                                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                    </svg>
                                </div>
                            </div>
                            <div class="mt-4">
                                <div class="flex items-center text-blue-100 text-sm">
                                    <span class="mr-1">Meta: {{ sprintf('%02d:%02d', intdiv($expectedTodayMin,60), $expectedTodayMin%60) }}</span>
                                </div>
                            </div>
                        </div>

                        <!-- Horas Faltantes -->
                        <div class="bg-gradient-to-r from-amber-500 to-orange-500 rounded-xl shadow-lg p-6 text-white">
                            <div class="flex items-center justify-between">
                                <div>
                                    <p class="text-amber-100 text-sm font-medium">Faltam Hoje</p>
                                    <p class="text-2xl font-bold">{{ sprintf('%02d:%02d', intdiv($missingTodayMin,60), $missingTodayMin%60) }}</p>
                                </div>
                                <div class="bg-amber-400 bg-opacity-30 rounded-full p-3">
                                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v3m0 0v3m0-3h3m-3 0H9m12 0a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                    </svg>
                                </div>
                            </div>
                            <div class="mt-4">
                                <div class="text-amber-100 text-sm">
                                    {{ $missingTodayMin > 0 ? 'Continue trabalhando!' : 'Meta atingida!' }}
                                </div>
                            </div>
                        </div>

                        <!-- Horas do Mês -->
                        <div class="bg-gradient-to-r from-green-500 to-emerald-500 rounded-xl shadow-lg p-6 text-white">
                            <div class="flex items-center justify-between">
                                <div>
                                    <p class="text-green-100 text-sm font-medium">Horas no Mês</p>
                                    <p class="text-2xl font-bold">{{ sprintf('%02d:%02d', intdiv($workedMonthMin,60), $workedMonthMin%60) }}</p>
                                </div>
                                <div class="bg-green-400 bg-opacity-30 rounded-full p-3">
                                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"></path>
                                    </svg>
                                </div>
                            </div>
                            <div class="mt-4">
                                <div class="text-green-100 text-sm">
                                    {{ now()->format('M/Y') }}
                                </div>
                            </div>
                        </div>

                        <!-- Valor a Receber -->
                        <div class="bg-gradient-to-r from-purple-500 to-indigo-500 rounded-xl shadow-lg p-6 text-white">
                            <div class="flex items-center justify-between">
                                <div>
                                    <p class="text-purple-100 text-sm font-medium">Valor do Mês</p>
                                    <p class="text-2xl font-bold">
                                        @if(!is_null($payableMonth))
                                            R$ {{ number_format($payableMonth, 2, ',', '.') }}
                                        @else
                                            --
                                        @endif
                                    </p>
                                </div>
                                <div class="bg-purple-400 bg-opacity-30 rounded-full p-3">
                                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1"></path>
                                    </svg>
                                </div>
                            </div>
                            <div class="mt-4">
                                <div class="text-purple-100 text-sm">
                                    @if($hourlyRate > 0)
                                        R$ {{ number_format($hourlyRate, 2, ',', '.') }}/hora
                                    @else
                                        Valor/hora não definido
                                    @endif
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Seção Principal -->
                    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6 mb-8">
                        <!-- Bater Ponto -->
                        <div class="lg:col-span-2 bg-white rounded-xl shadow-lg p-6">
                            <div class="flex items-center justify-between mb-6">
                                <h3 class="text-xl font-bold text-gray-900">Registro de Ponto</h3>
                                <div class="flex items-center space-x-2">
                                    <div class="w-3 h-3 rounded-full {{ $completedToday ? 'bg-green-400' : 'bg-yellow-400' }}"></div>
                                    <span class="text-sm font-medium text-gray-600">
                                        {{ $completedToday ? 'Completo' : 'Pendente' }}
                                    </span>
                                </div>
                            </div>

                            <div class="text-center py-8">
                                <div class="mb-6">
                                    <div class="inline-flex items-center justify-center w-20 h-20 bg-indigo-100 rounded-full mb-4">
                                        <svg class="w-10 h-10 text-indigo-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                        </svg>
                                    </div>
                                    <p class="text-gray-600 mb-2">{{ now()->format('d/m/Y - H:i') }}</p>
                                    <p class="text-sm text-gray-500">
                                        @if($completedToday)
                                            Você já registrou entrada e saída hoje
                                        @else
                                            {{ $entries->count() === 0 ? 'Registre sua entrada' : 'Registre sua saída' }}
                                        @endif
                                    </p>
                                </div>

                                <a href="{{ route('attendance.index') }}" 
                                   class="inline-flex items-center px-8 py-3 bg-indigo-600 text-white font-semibold rounded-lg shadow-md hover:bg-indigo-700 transition-colors duration-200 {{ $completedToday ? 'opacity-50 cursor-not-allowed' : '' }}">
                                    <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                    </svg>
                                    {{ $completedToday ? 'Ponto Completo' : 'Bater Ponto' }}
                                </a>
                            </div>

                            <!-- Registros de Hoje -->
                            @if($entries->count() > 0)
                                <div class="border-t pt-6">
                                    <h4 class="font-semibold text-gray-900 mb-4">Registros de Hoje</h4>
                                    <div class="space-y-3">
                                        @foreach($entries as $entry)
                                            <div class="flex items-center justify-between p-3 bg-gray-50 rounded-lg">
                                                <div class="flex items-center space-x-3">
                                                    <div class="w-2 h-2 rounded-full {{ $entry->type === 'entry' ? 'bg-green-500' : 'bg-red-500' }}"></div>
                                                    <span class="font-medium text-gray-900">
                                                        {{ $entry->type === 'entry' ? 'Entrada' : 'Saída' }}
                                                    </span>
                                                </div>
                                                <span class="text-lg font-bold text-gray-700">{{ $entry->punched_at->format('H:i') }}</span>
                                            </div>
                                        @endforeach
                                    </div>
                                </div>
                            @endif
                        </div>

                        <!-- Atalhos e Informações -->
                        <div class="space-y-6">
                            <!-- Atalhos Rápidos -->
                            <div class="bg-white rounded-xl shadow-lg p-6">
                                <h3 class="text-lg font-bold text-gray-900 mb-4">Atalhos Rápidos</h3>
                                <div class="space-y-3">
                                    <a href="{{ route('attendance.index') }}" 
                                       class="flex items-center p-3 rounded-lg bg-indigo-50 hover:bg-indigo-100 transition-colors duration-200">
                                        <div class="w-8 h-8 bg-indigo-200 rounded-lg flex items-center justify-center mr-3">
                                            <svg class="w-4 h-4 text-indigo-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                            </svg>
                                        </div>
                                        <span class="font-medium text-gray-900">Bater Ponto</span>
                                    </a>

                                    @can('view products')
                                    <a href="{{ route('products.index') }}" 
                                       class="flex items-center p-3 rounded-lg bg-gray-50 hover:bg-gray-100 transition-colors duration-200">
                                        <div class="w-8 h-8 bg-gray-200 rounded-lg flex items-center justify-center mr-3">
                                            <svg class="w-4 h-4 text-gray-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"></path>
                                            </svg>
                                        </div>
                                        <span class="font-medium text-gray-900">Produtos</span>
                                    </a>
                                    @endcan
                                </div>
                            </div>

                            <!-- Status do Funcionário -->
                            <div class="bg-white rounded-xl shadow-lg p-6">
                                <h3 class="text-lg font-bold text-gray-900 mb-4">Meu Status</h3>
                                <div class="space-y-4">
                                    <div class="flex items-center justify-between">
                                        <span class="text-sm text-gray-600">Cargo</span>
                                        <span class="font-medium text-gray-900">{{ $employee->position ?? 'Não definido' }}</span>
                                    </div>
                                    <div class="flex items-center justify-between">
                                        <span class="text-sm text-gray-600">Departamento</span>
                                        <span class="font-medium text-gray-900">{{ $employee->department ?? 'Não definido' }}</span>
                                    </div>
                                    <div class="flex items-center justify-between">
                                        <span class="text-sm text-gray-600">Data de Admissão</span>
                                        <span class="font-medium text-gray-900">
                                            {{ $employee->hire_date ? \Carbon\Carbon::parse($employee->hire_date)->format('d/m/Y') : 'Não definido' }}
                                        </span>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    @endrole
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
