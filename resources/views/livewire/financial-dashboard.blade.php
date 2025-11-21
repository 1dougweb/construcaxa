<div>
    <!-- Filtros de Período -->
    <div class="mb-6">
        <div class="flex flex-wrap items-center justify-between gap-4">
            <div class="flex items-center space-x-4">
                <select wire:model.live="period" class="border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm">
                    <option value="today">Hoje</option>
                    <option value="week">Esta Semana</option>
                    <option value="month">Este Mês</option>
                    <option value="quarter">Este Trimestre</option>
                    <option value="year">Este Ano</option>
                </select>
                <div class="flex items-center space-x-2">
                    <input type="date" wire:model.live="startDate" class="border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm">
                    <span class="text-gray-500">até</span>
                    <input type="date" wire:model.live="endDate" class="border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm">
                </div>
            </div>
        </div>
    </div>

    <!-- Cards de Resumo -->
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">
        <!-- Total a Receber -->
        <div class="bg-green-600 rounded-lg shadow-lg p-6 text-white">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-green-100 text-sm font-medium">Total a Receber</p>
                    <p class="text-2xl font-bold">R$ {{ number_format($financialData['total_receivables'] ?? 0, 2, ',', '.') }}</p>
                    <p class="text-green-100 text-xs mt-1">Pendente: R$ {{ number_format($financialData['pending_receivables'] ?? 0, 2, ',', '.') }}</p>
                </div>
                <div class="bg-green-400 bg-opacity-30 rounded-full p-3">
                    <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1"></path>
                    </svg>
                </div>
            </div>
        </div>

        <!-- Total a Pagar -->
        <div class="bg-red-600 rounded-lg shadow-lg p-6 text-white">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-red-100 text-sm font-medium">Total a Pagar</p>
                    <p class="text-2xl font-bold">R$ {{ number_format($financialData['total_payables'] ?? 0, 2, ',', '.') }}</p>
                    <p class="text-red-100 text-xs mt-1">Pendente: R$ {{ number_format($financialData['pending_payables'] ?? 0, 2, ',', '.') }}</p>
                </div>
                <div class="bg-red-400 bg-opacity-30 rounded-full p-3">
                    <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 9V7a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2m2 4h10a2 2 0 002-2v-6a2 2 0 00-2-2H9a2 2 0 00-2 2v6a2 2 0 002 2zm7-5a2 2 0 11-4 0 2 2 0 014 0z"></path>
                    </svg>
                </div>
            </div>
        </div>

        <!-- Saldo -->
        <div class="bg-blue-500 rounded-lg shadow-lg p-6 text-white">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-blue-100 text-sm font-medium">Saldo</p>
                    <p class="text-2xl font-bold">R$ {{ number_format($financialData['balance'] ?? 0, 2, ',', '.') }}</p>
                    <p class="text-blue-100 text-xs mt-1">
                        Recebido: R$ {{ number_format($financialData['received_receivables'] ?? 0, 2, ',', '.') }}
                    </p>
                </div>
                <div class="bg-blue-400 bg-opacity-30 rounded-full p-3">
                    <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"></path>
                    </svg>
                </div>
            </div>
        </div>

        <!-- Previsão de Faturamento (30 dias) -->
        <div class="bg-purple-400 rounded-lg shadow-lg p-6 text-white">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-purple-100 text-sm font-medium">Previsão 30 dias</p>
                    <p class="text-2xl font-bold">R$ {{ number_format($financialData['forecast_30'] ?? 0, 2, ',', '.') }}</p>
                    <p class="text-purple-100 text-xs mt-1">
                        Crescimento: {{ number_format($financialData['growth']['percentage'] ?? 0, 2, ',', '.') }}%
                    </p>
                </div>
                <div class="bg-purple-400 bg-opacity-30 rounded-full p-3">
                    <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7h8m0 0v8m0-8l-8 8-4-4-6 6"></path>
                    </svg>
                </div>
            </div>
        </div>
    </div>

    <!-- Gráficos -->
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6 mb-8">
        <!-- Gráfico de Receitas vs Despesas -->
        <div class="bg-white overflow-hidden shadow-xl sm:rounded-lg p-6">
            <h2 class="text-xl font-semibold text-gray-900 mb-4">Receitas vs Despesas</h2>
            <div class="chart-container" style="position: relative; height:300px;">
                <canvas id="incomeExpenseChart"></canvas>
            </div>
        </div>

        <!-- Gráfico de Contas a Pagar por Status -->
        <div class="bg-white overflow-hidden shadow-xl sm:rounded-lg p-6">
            <h2 class="text-xl font-semibold text-gray-900 mb-4">Contas a Pagar por Status</h2>
            <div class="chart-container" style="position: relative; height:300px;">
                <canvas id="payablesStatusChart"></canvas>
            </div>
        </div>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6 mb-8">
        <!-- Gráfico de Contas a Receber por Status -->
        <div class="bg-white overflow-hidden shadow-xl sm:rounded-lg p-6">
            <h2 class="text-xl font-semibold text-gray-900 mb-4">Contas a Receber por Status</h2>
            <div class="chart-container" style="position: relative; height:300px;">
                <canvas id="receivablesStatusChart"></canvas>
            </div>
        </div>

        <!-- Previsão de Faturamento -->
        <div class="bg-white overflow-hidden shadow-xl sm:rounded-lg p-6">
            <h2 class="text-xl font-semibold text-gray-900 mb-4">Previsão de Faturamento</h2>
            <div class="chart-container" style="position: relative; height:300px;">
                <canvas id="forecastChart"></canvas>
            </div>
        </div>
    </div>

    <!-- Chart.js Script -->
    <script src="https://cdn.jsdelivr.net/npm/chart.js@3.9.1/dist/chart.min.js"></script>
    <script>
        let incomeExpenseChart = null;
        let payablesStatusChart = null;
        let receivablesStatusChart = null;
        let forecastChart = null;

        document.addEventListener('DOMContentLoaded', function() {
            if (typeof Chart !== 'undefined') {
                initializeFinancialCharts();
            } else {
                const script = document.createElement('script');
                script.src = 'https://cdn.jsdelivr.net/npm/chart.js@3.9.1/dist/chart.min.js';
                script.onload = function() {
                    setTimeout(initializeFinancialCharts, 200);
                };
                document.head.appendChild(script);
            }
        });

        // Listen for Livewire events
        Livewire.on('updateFinancialCharts', (data) => {
            setTimeout(initializeFinancialCharts, 200);
        });

        function initializeFinancialCharts() {
            destroyFinancialCharts();

            const monthsData = @json($financialData['months_data'] ?? []);
            const payablesByStatus = @json($financialData['payables_by_status'] ?? []);
            const receivablesByStatus = @json($financialData['receivables_by_status'] ?? []);
            const forecast30 = @json($financialData['forecast_30'] ?? 0);
            const forecast60 = @json($financialData['forecast_60'] ?? 0);
            const forecast90 = @json($financialData['forecast_90'] ?? 0);

            // 1. Income vs Expense Line Chart
            const incomeExpenseCtx = document.getElementById('incomeExpenseChart');
            if (incomeExpenseCtx) {
                incomeExpenseChart = new Chart(incomeExpenseCtx, {
                    type: 'line',
                    data: {
                        labels: monthsData.map(d => d.month),
                        datasets: [{
                            label: 'Receitas',
                            data: monthsData.map(d => d.income),
                            borderColor: 'rgb(34, 197, 94)',
                            backgroundColor: 'rgba(34, 197, 94, 0.1)',
                            tension: 0.4
                        }, {
                            label: 'Despesas',
                            data: monthsData.map(d => d.expense),
                            borderColor: 'rgb(239, 68, 68)',
                            backgroundColor: 'rgba(239, 68, 68, 0.1)',
                            tension: 0.4
                        }]
                    },
                    options: {
                        responsive: true,
                        maintainAspectRatio: false,
                        plugins: {
                            legend: {
                                position: 'top',
                            },
                            tooltip: {
                                callbacks: {
                                    label: function(context) {
                                        return context.dataset.label + ': R$ ' + context.parsed.y.toFixed(2).replace('.', ',');
                                    }
                                }
                            }
                        },
                        scales: {
                            y: {
                                beginAtZero: true,
                                ticks: {
                                    callback: function(value) {
                                        return 'R$ ' + value.toFixed(2).replace('.', ',');
                                    }
                                }
                            }
                        }
                    }
                });
            }

            // 2. Payables by Status Pie Chart
            const payablesStatusCtx = document.getElementById('payablesStatusChart');
            if (payablesStatusCtx) {
                payablesStatusChart = new Chart(payablesStatusCtx, {
                    type: 'doughnut',
                    data: {
                        labels: ['Pendente', 'Paga', 'Vencida'],
                        datasets: [{
                            data: [
                                payablesByStatus.pending || 0,
                                payablesByStatus.paid || 0,
                                payablesByStatus.overdue || 0
                            ],
                            backgroundColor: [
                                'rgb(250, 204, 21)',
                                'rgb(34, 197, 94)',
                                'rgb(239, 68, 68)'
                            ]
                        }]
                    },
                    options: {
                        responsive: true,
                        maintainAspectRatio: false,
                        plugins: {
                            legend: {
                                position: 'bottom',
                            },
                            tooltip: {
                                callbacks: {
                                    label: function(context) {
                                        return context.label + ': R$ ' + context.parsed.toFixed(2).replace('.', ',');
                                    }
                                }
                            }
                        }
                    }
                });
            }

            // 3. Receivables by Status Pie Chart
            const receivablesStatusCtx = document.getElementById('receivablesStatusChart');
            if (receivablesStatusCtx) {
                receivablesStatusChart = new Chart(receivablesStatusCtx, {
                    type: 'doughnut',
                    data: {
                        labels: ['Pendente', 'Recebida', 'Vencida'],
                        datasets: [{
                            data: [
                                receivablesByStatus.pending || 0,
                                receivablesByStatus.received || 0,
                                receivablesByStatus.overdue || 0
                            ],
                            backgroundColor: [
                                'rgb(250, 204, 21)',
                                'rgb(34, 197, 94)',
                                'rgb(239, 68, 68)'
                            ]
                        }]
                    },
                    options: {
                        responsive: true,
                        maintainAspectRatio: false,
                        plugins: {
                            legend: {
                                position: 'bottom',
                            },
                            tooltip: {
                                callbacks: {
                                    label: function(context) {
                                        return context.label + ': R$ ' + context.parsed.toFixed(2).replace('.', ',');
                                    }
                                }
                            }
                        }
                    }
                });
            }

            // 4. Forecast Bar Chart
            const forecastCtx = document.getElementById('forecastChart');
            if (forecastCtx) {
                forecastChart = new Chart(forecastCtx, {
                    type: 'bar',
                    data: {
                        labels: ['30 dias', '60 dias', '90 dias'],
                        datasets: [{
                            label: 'Previsão de Faturamento',
                            data: [forecast30, forecast60, forecast90],
                            backgroundColor: 'rgb(147, 51, 234)'
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
                                        return 'R$ ' + context.parsed.y.toFixed(2).replace('.', ',');
                                    }
                                }
                            }
                        },
                        scales: {
                            y: {
                                beginAtZero: true,
                                ticks: {
                                    callback: function(value) {
                                        return 'R$ ' + value.toFixed(2).replace('.', ',');
                                    }
                                }
                            }
                        }
                    }
                });
            }
        }

        function destroyFinancialCharts() {
            const charts = [
                { chart: incomeExpenseChart, id: 'incomeExpenseChart' },
                { chart: payablesStatusChart, id: 'payablesStatusChart' },
                { chart: receivablesStatusChart, id: 'receivablesStatusChart' },
                { chart: forecastChart, id: 'forecastChart' }
            ];

            charts.forEach(item => {
                if (item.chart) {
                    item.chart.destroy();
                    item.chart = null;
                }
                const element = document.getElementById(item.id);
                if (element && typeof Chart !== 'undefined') {
                    const existingChart = Chart.getChart(element);
                    if (existingChart) {
                        existingChart.destroy();
                    }
                }
            });
        }

        // Reinitialize on Livewire updates
        Livewire.hook('message.processed', (message, component) => {
            if (component.__instance && component.__instance.constructor.name === 'FinancialDashboard') {
                setTimeout(initializeFinancialCharts, 200);
            }
        });
    </script>
</div>
