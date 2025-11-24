<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('Relatórios') }}
        </h2>
    </x-slot>

    <div class="py-6">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="sm:rounded-lg">
                    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                        <!-- Relatório de Vendas -->
                        <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-xl rounded-lg border border-gray-200 dark:border-gray-700">
                            <div class="p-5">
                                <div class="flex items-center">
                                    <div class="flex-shrink-0 bg-indigo-500 rounded-md p-3">
                                        <svg class="h-6 w-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z" />
                                        </svg>
                                    </div>
                                    <div class="ml-5">
                                        <h3 class="text-lg leading-6 font-medium text-gray-900 dark:text-gray-100">Requisições</h3>
                                        <p class="mt-2 text-sm text-gray-500 dark:text-gray-400">
                                            Visualize as requisições de materiais por período.
                                        </p>
                                    </div>
                                </div>
                                <div class="mt-6">
                                    <a href="{{ route('reports.orders') }}" class="inline-flex items-center px-4 py-2 border border-transparent text-sm font-medium rounded-md text-indigo-700 dark:text-indigo-300 bg-indigo-100 dark:bg-indigo-900/50 hover:bg-indigo-200 dark:hover:bg-indigo-900/70">
                                        Gerar Relatório
                                    </a>
                                </div>
                            </div>
                        </div>

                        <!-- Relatório de Estoque -->
                        <div class="bg-white dark:bg-gray-800 shadow-xl rounded-lg border border-gray-200 dark:border-gray-700">
                            <div class="p-5">
                                <div class="flex items-center">
                                    <div class="flex-shrink-0 bg-green-500 rounded-md p-3">
                                        <svg class="h-6 w-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 13V6a2 2 0 00-2-2H6a2 2 0 00-2 2v7m16 0v5a2 2 0 01-2 2H6a2 2 0 01-2-2v-5m16 0h-2.586a1 1 0 00-.707.293l-2.414 2.414a1 1 0 01-.707.293h-3.172a1 1 0 01-.707-.293l-2.414-2.414A1 1 0 006.586 13H4" />
                                        </svg>
                                    </div>
                                    <div class="ml-5">
                                        <h3 class="text-lg leading-6 font-medium text-gray-900 dark:text-gray-100">Estoque</h3>
                                        <p class="mt-2 text-sm text-gray-500 dark:text-gray-400">
                                            Acompanhe o status atual do estoque e movimentações.
                                        </p>
                                    </div>
                                </div>
                                <div class="mt-6">
                                    <a href="{{ route('reports.stock') }}" class="inline-flex items-center px-4 py-2 border border-transparent text-sm font-medium rounded-md text-green-700 dark:text-green-300 bg-green-100 dark:bg-green-900/50 hover:bg-green-200 dark:hover:bg-green-900/70">
                                        Gerar Relatório
                                    </a>
                                </div>
                            </div>
                        </div>

                        <!-- Relatório de Movimentações -->
                        <div class="bg-white dark:bg-gray-800 shadow-xl rounded-lg border border-gray-200 dark:border-gray-700">
                            <div class="p-5">
                                <div class="flex items-center">
                                    <div class="flex-shrink-0 bg-yellow-500 rounded-md p-3">
                                        <svg class="h-6 w-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z" />
                                        </svg>
                                    </div>
                                    <div class="ml-5">
                                        <h3 class="text-lg leading-6 font-medium text-gray-900 dark:text-gray-100">Movimentações</h3>
                                        <p class="mt-2 text-sm text-gray-500 dark:text-gray-400">
                                            Analise as movimentações de produtos no estoque.
                                        </p>
                                    </div>
                                </div>
                                <div class="mt-6">
                                    <a href="{{ route('reports.movements') }}" class="inline-flex items-center px-4 py-2 border border-transparent text-sm font-medium rounded-md text-yellow-700 dark:text-yellow-300 bg-yellow-100 dark:bg-yellow-900/50 hover:bg-yellow-200 dark:hover:bg-yellow-900/70">
                                        Gerar Relatório
                                    </a>
                                </div>
                            </div>
                        </div>
                    </div>
            </div>
        </div>
    </div>
</x-app-layout>
