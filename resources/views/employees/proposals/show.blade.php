<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('Proposta #') }}{{ $proposal->id }} - {{ $employee->user->name }}
        </h2>
    </x-slot>

    <div class="py-6">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-xl sm:rounded-lg">
                <div class="p-6 lg:p-8">
                    <div class="mb-6">
                        <span class="px-3 py-1 text-sm font-semibold rounded-full {{ $proposal->status_color }}">
                            {{ $proposal->status_label }}
                        </span>
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">
                        <div>
                            <h3 class="text-sm font-medium text-gray-500 dark:text-gray-400">Funcionário</h3>
                            <p class="mt-1 text-lg text-gray-900 dark:text-gray-100">{{ $proposal->employee->user->name }}</p>
                        </div>
                        <div>
                            <h3 class="text-sm font-medium text-gray-500 dark:text-gray-400">Obra</h3>
                            <p class="mt-1 text-lg text-gray-900 dark:text-gray-100">
                                {{ $proposal->project ? $proposal->project->name : 'Não vinculada' }}
                            </p>
                        </div>
                        <div>
                            <h3 class="text-sm font-medium text-gray-500 dark:text-gray-400">Valor da Hora</h3>
                            <p class="mt-1 text-lg text-gray-900 dark:text-gray-100">
                                R$ {{ number_format($proposal->hourly_rate, 2, ',', '.') }}
                            </p>
                        </div>
                        <div>
                            <h3 class="text-sm font-medium text-gray-500 dark:text-gray-400">Tipo de Contrato</h3>
                            <p class="mt-1 text-lg text-gray-900 dark:text-gray-100">{{ $proposal->contract_type_label }}</p>
                        </div>
                        @if($proposal->contract_type === 'fixed_days')
                            <div>
                                <h3 class="text-sm font-medium text-gray-500 dark:text-gray-400">Dias</h3>
                                <p class="mt-1 text-lg text-gray-900 dark:text-gray-100">{{ $proposal->days }}</p>
                            </div>
                            <div>
                                <h3 class="text-sm font-medium text-gray-500 dark:text-gray-400">Período</h3>
                                <p class="mt-1 text-lg text-gray-900 dark:text-gray-100">
                                    {{ $proposal->start_date ? $proposal->start_date->format('d/m/Y') : 'N/A' }} 
                                    até 
                                    {{ $proposal->end_date ? $proposal->end_date->format('d/m/Y') : 'N/A' }}
                                </p>
                            </div>
                        @endif
                        <div>
                            <h3 class="text-sm font-medium text-gray-500 dark:text-gray-400">Valor Total</h3>
                            <p class="mt-1 text-lg font-bold text-gray-900 dark:text-gray-100">
                                R$ {{ number_format($proposal->total_amount, 2, ',', '.') }}
                            </p>
                        </div>
                        <div>
                            <h3 class="text-sm font-medium text-gray-500 dark:text-gray-400">Criada em</h3>
                            <p class="mt-1 text-lg text-gray-900 dark:text-gray-100">
                                {{ $proposal->created_at->format('d/m/Y H:i') }}
                            </p>
                        </div>
                    </div>

                    @if($proposal->observations)
                        <div class="mb-6">
                            <h3 class="text-sm font-medium text-gray-500 dark:text-gray-400 mb-2">Observações</h3>
                            <p class="text-gray-900 dark:text-gray-100 bg-gray-50 dark:bg-gray-700 p-4 rounded-lg">
                                {{ $proposal->observations }}
                            </p>
                        </div>
                    @endif

                    @if($proposal->items->count() > 0)
                        <div class="mb-6">
                            <h3 class="text-lg font-medium text-gray-900 dark:text-gray-100 mb-4">Itens da Proposta</h3>
                            <div class="overflow-x-auto">
                                <table class="min-w-full divide-y divide-gray-200 dark:divide-gray-700">
                                    <thead class="bg-gray-50 dark:bg-gray-700">
                                        <tr>
                                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase">Tipo</th>
                                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase">Item</th>
                                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase">Quantidade</th>
                                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase">Preço Unitário</th>
                                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase">Total</th>
                                        </tr>
                                    </thead>
                                    <tbody class="bg-white dark:bg-gray-800 divide-y divide-gray-200 dark:divide-gray-700">
                                        @foreach($proposal->items as $item)
                                            <tr>
                                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900 dark:text-gray-100">
                                                    {{ $item->item_type_label }}
                                                </td>
                                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900 dark:text-gray-100">
                                                    {{ $item->item_name }}
                                                </td>
                                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900 dark:text-gray-100">
                                                    {{ number_format($item->quantity, 2, ',', '.') }}
                                                </td>
                                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900 dark:text-gray-100">
                                                    R$ {{ number_format($item->unit_price, 2, ',', '.') }}
                                                </td>
                                                <td class="px-6 py-4 whitespace-nowrap text-sm font-semibold text-gray-900 dark:text-gray-100">
                                                    R$ {{ number_format($item->total_price, 2, ',', '.') }}
                                                </td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    @endif

                    <div class="flex justify-between items-center mt-6">
                        <a href="{{ route('employees.proposals.index', $employee) }}" class="inline-flex items-center px-4 py-2 bg-gray-300 dark:bg-gray-600 border border-transparent rounded-md font-semibold text-xs text-gray-700 dark:text-gray-200 uppercase tracking-widest hover:bg-gray-400 dark:hover:bg-gray-500">
                            Voltar
                        </a>
                        <div>
                            <span class="text-sm text-gray-500 dark:text-gray-400">
                                Link da proposta: 
                                <a href="{{ route('proposals.view', $proposal->token) }}" target="_blank" class="text-indigo-600 dark:text-indigo-400 hover:underline">
                                    {{ route('proposals.view', $proposal->token) }}
                                </a>
                            </span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>

