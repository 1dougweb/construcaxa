<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
                {{ __('Balanço Financeiro - ') }}{{ $project->name }}
            </h2>
            <div class="flex space-x-2">
                <form method="POST" action="{{ route('projects.financial-balance.sync', $project) }}">
                    @csrf
                    <button type="submit" class="inline-flex items-center px-4 py-2 bg-blue-600 dark:bg-blue-700 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-blue-700 dark:hover:bg-blue-600">
                        <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15" />
                        </svg>
                        Sincronizar
                    </button>
                </form>
                <a href="{{ route('projects.show', $project) }}" class="inline-flex items-center px-4 py-2 bg-gray-600 dark:bg-gray-700 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-gray-700 dark:hover:bg-gray-600">
                    Voltar à Obra
                </a>
            </div>
        </div>
    </x-slot>

    <div class="py-6">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <!-- Resumo -->
            <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-8">
                <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-xl sm:rounded-lg p-6 border border-gray-200 dark:border-gray-700">
                    <h3 class="text-sm font-medium text-gray-500 dark:text-gray-400">Custo Total de Materiais</h3>
                    <p class="text-2xl font-bold text-gray-900 dark:text-gray-100 mt-2">R$ {{ number_format($totalCost, 2, ',', '.') }}</p>
                </div>
                <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-xl sm:rounded-lg p-6 border border-gray-200 dark:border-gray-700">
                    <h3 class="text-sm font-medium text-gray-500 dark:text-gray-400">Orçamento Aprovado</h3>
                    <p class="text-2xl font-bold text-gray-900 dark:text-gray-100 mt-2">R$ {{ number_format($budgetTotal, 2, ',', '.') }}</p>
                </div>
                <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-xl sm:rounded-lg p-6 border border-gray-200 dark:border-gray-700">
                    <h3 class="text-sm font-medium text-gray-500 dark:text-gray-400">% do Orçamento</h3>
                    <p class="text-2xl font-bold {{ $budgetVariance > 100 ? 'text-red-600 dark:text-red-400' : ($budgetVariance > 80 ? 'text-yellow-600 dark:text-yellow-400' : 'text-green-600 dark:text-green-400') }} mt-2">
                        {{ number_format($budgetVariance, 2, ',', '.') }}%
                    </p>
                </div>
            </div>

            <!-- Totalizadores por Categoria -->
            @if(count($totalsByCategory) > 0)
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-xl sm:rounded-lg mb-8 border border-gray-200 dark:border-gray-700">
                <div class="p-6 border-b border-gray-200 dark:border-gray-700">
                    <h2 class="text-lg font-semibold text-gray-900 dark:text-gray-100">Total por Categoria</h2>
                </div>
                <div class="p-6">
                    <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                        @foreach($totalsByCategory as $category => $total)
                            <div class="p-4 bg-gray-50 dark:bg-gray-700/50 rounded-lg">
                                <h3 class="text-sm font-medium text-gray-700 dark:text-gray-300">{{ $category }}</h3>
                                <p class="text-xl font-bold text-gray-900 dark:text-gray-100 mt-1">R$ {{ number_format($total, 2, ',', '.') }}</p>
                            </div>
                        @endforeach
                    </div>
                </div>
            </div>
            @endif

            <!-- Tabela de Materiais Utilizados -->
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-xl sm:rounded-lg border border-gray-200 dark:border-gray-700">
                <div class="p-6 border-b border-gray-200 dark:border-gray-700">
                    <h2 class="text-lg font-semibold text-gray-900 dark:text-gray-100">Materiais Utilizados na Obra</h2>
                </div>
                <div class="p-6">
                    @if(empty($balances))
                        <div class="text-center py-8">
                            <svg class="mx-auto h-12 w-12 text-gray-400 dark:text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 17v-2m3 2v-4m3 4v-6m2 10H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                            </svg>
                            <h3 class="mt-2 text-sm font-medium text-gray-900 dark:text-gray-100">Nenhum material utilizado ainda</h3>
                            <p class="mt-1 text-sm text-gray-500 dark:text-gray-400">Os materiais serão registrados aqui quando houver requisições com saída de estoque.</p>
                        </div>
                    @else
                        <table class="min-w-full divide-y divide-gray-200 dark:divide-gray-700">
                            <thead>
                                <tr>
                                    <th class="px-6 py-3 bg-gray-50 dark:bg-gray-700 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">Produto</th>
                                    <th class="px-6 py-3 bg-gray-50 dark:bg-gray-700 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">Categoria</th>
                                    <th class="px-6 py-3 bg-gray-50 dark:bg-gray-700 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">Requisição</th>
                                    <th class="px-6 py-3 bg-gray-50 dark:bg-gray-700 text-right text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">Quantidade</th>
                                    <th class="px-6 py-3 bg-gray-50 dark:bg-gray-700 text-right text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">Custo Unitário</th>
                                    <th class="px-6 py-3 bg-gray-50 dark:bg-gray-700 text-right text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">Total</th>
                                    <th class="px-6 py-3 bg-gray-50 dark:bg-gray-700 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">Data de Uso</th>
                                </tr>
                            </thead>
                            <tbody class="bg-white dark:bg-gray-800 divide-y divide-gray-200 dark:divide-gray-700">
                                @foreach($balances as $balance)
                                    <tr class="hover:bg-gray-50 dark:hover:bg-gray-700/50">
                                        <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900 dark:text-gray-100">
                                            {{ $balance['product']->name }}
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500 dark:text-gray-400">
                                            {{ $balance['category'] ?? '-' }}
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500 dark:text-gray-400">
                                            #{{ $balance['request_number'] }}
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500 dark:text-gray-400 text-right">
                                            {{ number_format($balance['quantity_used'], 3, ',', '.') }} {{ $balance['product']->unit_label ?? 'un' }}
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500 dark:text-gray-400 text-right">
                                            R$ {{ number_format($balance['unit_cost'], 2, ',', '.') }}
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm font-semibold text-gray-900 dark:text-gray-100 text-right">
                                            R$ {{ number_format($balance['total_cost'], 2, ',', '.') }}
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500 dark:text-gray-400">
                                            {{ $balance['usage_date'] }}
                                        </td>
                                    </tr>
                                @endforeach
                                <tr class="bg-gray-50 dark:bg-gray-700/50">
                                    <td colspan="5" class="px-6 py-4 text-right text-sm font-bold text-gray-900 dark:text-gray-100">
                                        TOTAL:
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-right text-lg font-bold text-gray-900 dark:text-gray-100">
                                        R$ {{ number_format($totalCost, 2, ',', '.') }}
                                    </td>
                                    <td></td>
                                </tr>
                            </tbody>
                        </table>
                    @endif
                </div>
            </div>
        </div>
    </div>
</x-app-layout>

