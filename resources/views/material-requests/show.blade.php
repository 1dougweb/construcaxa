<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
                {{ __('Requisição de Material') }} #{{ $materialRequest->number }}
            </h2>
            <div class="flex gap-4">
                @can('edit service-orders')
                <a href="{{ route('material-requests.edit', $materialRequest) }}" class="inline-flex items-center px-4 py-2 bg-indigo-600 dark:bg-indigo-700 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-indigo-700 dark:hover:bg-indigo-600 focus:bg-indigo-700 active:bg-indigo-900 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 transition ease-in-out duration-150">
                    {{ __('Editar') }}
                </a>
                @endcan
                @can('view service-orders')
                <a href="{{ route('material-requests.pdf', $materialRequest) }}" class="inline-flex items-center px-4 py-2 bg-gray-800 dark:bg-gray-700 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-gray-700 dark:hover:bg-gray-600 focus:bg-gray-700 active:bg-gray-900 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 transition ease-in-out duration-150" target="_blank">
                    {{ __('Gerar PDF') }}
                </a>
                @endcan
                <a href="{{ route('material-requests.index') }}" class="inline-flex items-center px-4 py-2 bg-gray-800 dark:bg-gray-700 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-gray-700 dark:hover:bg-gray-600 focus:bg-gray-700 active:bg-gray-900 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 transition ease-in-out duration-150">
                    {{ __('Voltar') }}
                </a>
            </div>
        </div>
    </x-slot>

    <div class="py-6">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-xl sm:rounded-lg">
                <div class="p-6 lg:p-8 bg-white dark:bg-gray-800 border-b border-gray-200 dark:border-gray-700">
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div>
                            <h3 class="text-lg font-medium text-gray-900 dark:text-gray-100">{{ __('Informações da Requisição') }}</h3>
                            <div class="mt-4 space-y-4">
                                <div>
                                    <span class="text-sm font-medium text-gray-500 dark:text-gray-400">{{ __('Número') }}:</span>
                                    <span class="ml-2 text-sm text-gray-900 dark:text-gray-100">{{ $materialRequest->number }}</span>
                                </div>
                                <div>
                                    <span class="text-sm font-medium text-gray-500 dark:text-gray-400">{{ __('Data de Criação') }}:</span>
                                    <span class="ml-2 text-sm text-gray-900 dark:text-gray-100">{{ $materialRequest->created_at->format('d/m/Y H:i') }}</span>
                                </div>
                                <div>
                                    <span class="text-sm font-medium text-gray-500 dark:text-gray-400">{{ __('Obra') }}:</span>
                                    <span class="ml-2 text-sm text-gray-900 dark:text-gray-100">{{ optional($materialRequest->project)->name ? ($materialRequest->project->name . ' (' . $materialRequest->project->code . ')') : '-' }}</span>
                                </div>
                            </div>
                        </div>

                        <div>
                            <h3 class="text-lg font-medium text-gray-900 dark:text-gray-100">{{ __('Informações do Cliente') }}</h3>
                            <div class="mt-4 space-y-4">
                                <div>
                                    <span class="text-sm font-medium text-gray-500 dark:text-gray-400">{{ __('Nome') }}:</span>
                                    <span class="ml-2 text-sm text-gray-900 dark:text-gray-100">{{ $materialRequest->customer_name }}</span>
                                </div>
                                @if($materialRequest->customer_phone)
                                    <div>
                                        <span class="text-sm font-medium text-gray-500 dark:text-gray-400">{{ __('Telefone') }}:</span>
                                        <span class="ml-2 text-sm text-gray-900 dark:text-gray-100">{{ $materialRequest->customer_phone }}</span>
                                    </div>
                                @endif
                                @if($materialRequest->customer_email)
                                    <div>
                                        <span class="text-sm font-medium text-gray-500 dark:text-gray-400">{{ __('Email') }}:</span>
                                        <span class="ml-2 text-sm text-gray-900 dark:text-gray-100">{{ $materialRequest->customer_email }}</span>
                                    </div>
                                @endif
                            </div>
                        </div>

                        <div class="col-span-2">
                            <h3 class="text-lg font-medium text-gray-900 dark:text-gray-100">{{ __('Descrição') }}</h3>
                            <div class="mt-4">
                                <p class="text-sm text-gray-900 dark:text-gray-100">{{ $materialRequest->description }}</p>
                            </div>
                        </div>

                        @if($materialRequest->notes)
                            <div class="col-span-2">
                                <h3 class="text-lg font-medium text-gray-900 dark:text-gray-100">{{ __('Observações') }}</h3>
                                <div class="mt-4">
                                    <p class="text-sm text-gray-900 dark:text-gray-100">{{ $materialRequest->notes }}</p>
                                </div>
                            </div>
                        @endif

                        <div class="col-span-2">
                            <h3 class="text-lg font-medium text-gray-900 dark:text-gray-100">{{ __('Itens') }}</h3>
                            <div class="mt-4">
                                <table class="min-w-full divide-y divide-gray-200 dark:divide-gray-700">
                                    <thead>
                                        <tr>
                                            <th class="px-6 py-3 bg-gray-50 dark:bg-gray-700/50 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">{{ __('Produto') }}</th>
                                            <th class="px-6 py-3 bg-gray-50 dark:bg-gray-700/50 text-right text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">{{ __('Quantidade') }}</th>
                                            <th class="px-6 py-3 bg-gray-50 dark:bg-gray-700/50 text-right text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">{{ __('Preço') }}</th>
                                            <th class="px-6 py-3 bg-gray-50 dark:bg-gray-700/50 text-right text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">{{ __('Total') }}</th>
                                        </tr>
                                    </thead>
                                    <tbody class="bg-white dark:bg-gray-800 divide-y divide-gray-200 dark:divide-gray-700">
                                        @foreach($materialRequest->items as $item)
                                            <tr>
                                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900 dark:text-gray-100">
                                                    {{ $item->product->name }}
                                                </td>
                                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900 dark:text-gray-100 text-right">
                                                    {{ $item->quantity }}
                                                </td>
                                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900 dark:text-gray-100 text-right">
                                                    R$ {{ number_format($item->price, 2, ',', '.') }}
                                                </td>
                                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900 dark:text-gray-100 text-right">
                                                    R$ {{ number_format($item->quantity * $item->price, 2, ',', '.') }}
                                                </td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                    <tfoot>
                                        <tr>
                                            <td colspan="3" class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900 dark:text-gray-100 text-right">
                                                {{ __('Total Geral') }}:
                                            </td>
                                            <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900 dark:text-gray-100 text-right">
                                                R$ {{ number_format($materialRequest->total_amount, 2, ',', '.') }}
                                            </td>
                                        </tr>
                                    </tfoot>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

</x-app-layout>
