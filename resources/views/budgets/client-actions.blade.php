<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('Orçamento #' . $budget->id . ' - v' . $budget->version) }}
        </h2>
    </x-slot>

    <div class="max-w-4xl mx-auto sm:px-6 lg:px-8 py-6">
        @if(session('success'))
            <div class="mb-6 bg-green-100 dark:bg-green-900/30 border border-green-400 dark:border-green-700 text-green-700 dark:text-green-300 px-4 py-3 rounded-lg">
                {{ session('success') }}
            </div>
        @endif

        @if(session('error'))
            <div class="mb-6 bg-red-100 dark:bg-red-900/30 border border-red-400 dark:border-red-700 text-red-700 dark:text-red-300 px-4 py-3 rounded-lg">
                {{ session('error') }}
            </div>
        @endif

        <!-- Informações do Orçamento -->
        <div class="bg-white dark:bg-gray-800 rounded-lg shadow-lg border border-gray-200 dark:border-gray-700 mb-6">
            <div class="px-6 py-4 border-b border-gray-200 dark:border-gray-700">
                <h3 class="text-lg font-semibold text-gray-900 dark:text-gray-100">Detalhes do Orçamento</h3>
            </div>
            <div class="p-6">
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">
                    <div>
                        <p class="text-sm text-gray-600 dark:text-gray-400 mb-1">ID do Orçamento</p>
                        <p class="text-lg font-semibold text-gray-900 dark:text-gray-100">#{{ $budget->id }}</p>
                    </div>
                    <div>
                        <p class="text-sm text-gray-600 dark:text-gray-400 mb-1">Versão</p>
                        <p class="text-lg font-semibold text-gray-900 dark:text-gray-100">v{{ $budget->version }}</p>
                    </div>
                    <div>
                        <p class="text-sm text-gray-600 dark:text-gray-400 mb-1">Status</p>
                        <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium {{ $budget->status_color }}">
                            {{ $budget->status_label }}
                        </span>
                    </div>
                    @if($budget->inspection)
                    <div>
                        <p class="text-sm text-gray-600 dark:text-gray-400 mb-1">Vistoria Relacionada</p>
                        <p class="text-lg font-semibold text-gray-900 dark:text-gray-100">#{{ $budget->inspection->number }}</p>
                    </div>
                    @endif
                </div>

                @if($budget->address)
                <div class="mb-6">
                    <p class="text-sm text-gray-600 dark:text-gray-400 mb-1">Endereço da Obra</p>
                    <p class="text-base text-gray-900 dark:text-gray-100">
                        <i class="bi bi-geo-alt mr-2"></i>{{ $budget->address }}
                    </p>
                </div>
                @endif

                @if($budget->notes)
                <div class="mb-6">
                    <p class="text-sm text-gray-600 dark:text-gray-400 mb-2">Observações</p>
                    <div class="bg-gray-50 dark:bg-gray-700/50 rounded-lg p-4 border border-gray-200 dark:border-gray-600">
                        <p class="text-sm text-gray-900 dark:text-gray-100 whitespace-pre-wrap">{{ $budget->notes }}</p>
                    </div>
                </div>
                @endif
            </div>
        </div>

        <!-- Itens do Orçamento -->
        <div class="bg-white dark:bg-gray-800 rounded-lg shadow-lg border border-gray-200 dark:border-gray-700 mb-6">
            <div class="px-6 py-4 border-b border-gray-200 dark:border-gray-700">
                <h3 class="text-lg font-semibold text-gray-900 dark:text-gray-100">Itens do Orçamento</h3>
            </div>
            <div class="p-6">
                <div class="overflow-x-auto">
                    <table class="min-w-full divide-y divide-gray-200 dark:divide-gray-700">
                        <thead class="bg-gray-50 dark:bg-gray-700/50">
                            <tr>
                                <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">Tipo</th>
                                <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">Descrição</th>
                                <th class="px-4 py-3 text-right text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">Quantidade</th>
                                <th class="px-4 py-3 text-right text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">Preço Unit.</th>
                                <th class="px-4 py-3 text-right text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">Total</th>
                            </tr>
                        </thead>
                        <tbody class="bg-white dark:bg-gray-800 divide-y divide-gray-200 dark:divide-gray-700">
                            @foreach($budget->items as $item)
                            <tr>
                                <td class="px-4 py-3 whitespace-nowrap text-sm text-gray-900 dark:text-gray-100">
                                    <span class="px-2 py-1 text-xs font-medium rounded-full 
                                        {{ $item->item_type === 'product' ? 'bg-blue-100 dark:bg-blue-900/30 text-blue-800 dark:text-blue-300' : '' }}
                                        {{ $item->item_type === 'service' ? 'bg-green-100 dark:bg-green-900/30 text-green-800 dark:text-green-300' : '' }}
                                        {{ $item->item_type === 'labor' ? 'bg-purple-100 dark:bg-purple-900/30 text-purple-800 dark:text-purple-300' : '' }}
                                    ">
                                        {{ ucfirst($item->item_type) }}
                                    </span>
                                </td>
                                <td class="px-4 py-3 text-sm text-gray-900 dark:text-gray-100">{{ $item->description }}</td>
                                <td class="px-4 py-3 whitespace-nowrap text-sm text-gray-900 dark:text-gray-100 text-right">
                                    {{ number_format($item->quantity ?? 0, 2, ',', '.') }}
                                </td>
                                <td class="px-4 py-3 whitespace-nowrap text-sm text-gray-900 dark:text-gray-100 text-right">
                                    R$ {{ number_format($item->unit_price ?? 0, 2, ',', '.') }}
                                </td>
                                <td class="px-4 py-3 whitespace-nowrap text-sm font-medium text-gray-900 dark:text-gray-100 text-right">
                                    R$ {{ number_format($item->total ?? 0, 2, ',', '.') }}
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

        <!-- Totais -->
        <div class="bg-white dark:bg-gray-800 rounded-lg shadow-lg border border-gray-200 dark:border-gray-700 mb-6">
            <div class="p-6">
                <div class="flex justify-end">
                    <div class="w-64 space-y-3">
                        <div class="flex justify-between text-base text-gray-700 dark:text-gray-300">
                            <span>Subtotal:</span>
                            <span class="font-medium">R$ {{ number_format($budget->subtotal, 2, ',', '.') }}</span>
                        </div>
                        <div class="flex justify-between text-base text-gray-700 dark:text-gray-300">
                            <span>Desconto:</span>
                            <span class="font-medium">R$ {{ number_format($budget->discount ?? 0, 2, ',', '.') }}</span>
                        </div>
                        <div class="flex justify-between text-xl font-bold text-gray-900 dark:text-gray-100 pt-3 border-t border-gray-200 dark:border-gray-700">
                            <span>Total:</span>
                            <span class="text-indigo-600 dark:text-indigo-400">R$ {{ number_format($budget->total, 2, ',', '.') }}</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Ações do Cliente -->
        @if($budget->status !== 'approved' && $budget->status !== 'cancelled')
        <div class="bg-white dark:bg-gray-800 rounded-lg shadow-lg border border-gray-200 dark:border-gray-700">
            <div class="px-6 py-4 border-b border-gray-200 dark:border-gray-700">
                <h3 class="text-lg font-semibold text-gray-900 dark:text-gray-100">Ações</h3>
                <p class="text-sm text-gray-500 dark:text-gray-400 mt-1">Escolha uma ação para este orçamento</p>
            </div>
            <div class="p-6">
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <!-- Aprovar -->
                    <div class="border-2 border-green-200 dark:border-green-800 rounded-lg p-6 bg-green-50 dark:bg-green-900/20">
                        <div class="flex items-start gap-4">
                            <div class="flex-shrink-0">
                                <div class="w-12 h-12 bg-green-500 rounded-full flex items-center justify-center">
                                    <i class="bi bi-check-circle text-white text-2xl"></i>
                                </div>
                            </div>
                            <div class="flex-1">
                                <h4 class="text-lg font-semibold text-gray-900 dark:text-gray-100 mb-2">Aprovar Orçamento</h4>
                                <p class="text-sm text-gray-600 dark:text-gray-400 mb-4">
                                    Ao aprovar este orçamento, um projeto será criado automaticamente e você receberá um número de OS.
                                </p>
                                <form action="{{ route('client.budgets.approve', $budget) }}" method="POST" onsubmit="return confirm('Tem certeza que deseja aprovar este orçamento? Um projeto será criado automaticamente.');">
                                    @csrf
                                    @method('PATCH')
                                    <button type="submit" class="w-full px-4 py-3 bg-green-600 dark:bg-green-700 text-white rounded-lg hover:bg-green-700 dark:hover:bg-green-600 transition-colors font-medium">
                                        <i class="bi bi-check-circle mr-2"></i>Aprovar Orçamento
                                    </button>
                                </form>
                            </div>
                        </div>
                    </div>

                    <!-- Rejeitar -->
                    <div class="border-2 border-red-200 dark:border-red-800 rounded-lg p-6 bg-red-50 dark:bg-red-900/20">
                        <div class="flex items-start gap-4">
                            <div class="flex-shrink-0">
                                <div class="w-12 h-12 bg-red-500 rounded-full flex items-center justify-center">
                                    <i class="bi bi-x-circle text-white text-2xl"></i>
                                </div>
                            </div>
                            <div class="flex-1">
                                <h4 class="text-lg font-semibold text-gray-900 dark:text-gray-100 mb-2">Rejeitar Orçamento</h4>
                                <p class="text-sm text-gray-600 dark:text-gray-400 mb-4">
                                    Se você não concorda com este orçamento, pode rejeitá-lo e informar o motivo.
                                </p>
                                <form action="{{ route('client.budgets.reject', $budget) }}" method="POST" id="rejectForm" onsubmit="return confirm('Tem certeza que deseja rejeitar este orçamento?');">
                                    @csrf
                                    @method('PATCH')
                                    <div class="mb-4">
                                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                                            Motivo da Rejeição (opcional)
                                        </label>
                                        <textarea 
                                            name="rejection_reason" 
                                            rows="3" 
                                            class="w-full border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-gray-300 rounded-md shadow-sm focus:border-red-500 focus:ring-red-500"
                                            placeholder="Descreva o motivo da rejeição ou contestação..."
                                            maxlength="1000"
                                        ></textarea>
                                        <p class="text-xs text-gray-500 dark:text-gray-400 mt-1">Máximo de 1000 caracteres</p>
                                    </div>
                                    <button type="submit" class="w-full px-4 py-3 bg-red-600 dark:bg-red-700 text-white rounded-lg hover:bg-red-700 dark:hover:bg-red-600 transition-colors font-medium">
                                        <i class="bi bi-x-circle mr-2"></i>Rejeitar Orçamento
                                    </button>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        @else
        <div class="bg-white dark:bg-gray-800 rounded-lg shadow-lg border border-gray-200 dark:border-gray-700">
            <div class="p-6 text-center">
                <div class="mb-4">
                    @if($budget->status === 'approved')
                        <i class="bi bi-check-circle text-6xl text-green-500 mb-4"></i>
                        <p class="text-lg font-semibold text-gray-900 dark:text-gray-100 mb-2">Orçamento Aprovado</p>
                        <p class="text-sm text-gray-600 dark:text-gray-400">
                            Este orçamento foi aprovado em {{ $budget->approved_at->format('d/m/Y H:i') }}
                        </p>
                    @elseif($budget->status === 'cancelled')
                        <i class="bi bi-x-circle text-6xl text-red-500 mb-4"></i>
                        <p class="text-lg font-semibold text-gray-900 dark:text-gray-100 mb-2">Orçamento Cancelado</p>
                        <p class="text-sm text-gray-600 dark:text-gray-400">
                            Este orçamento foi cancelado e não pode mais ser aprovado ou rejeitado.
                        </p>
                    @endif
                </div>
            </div>
        </div>
        @endif

        <!-- Botão Voltar -->
        <div class="mt-6 text-center">
            <a href="{{ route('client.dashboard') }}" class="inline-flex items-center px-4 py-2 bg-gray-200 dark:bg-gray-700 text-gray-700 dark:text-gray-300 rounded-lg hover:bg-gray-300 dark:hover:bg-gray-600 transition-colors">
                <i class="bi bi-arrow-left mr-2"></i>Voltar ao Dashboard
            </a>
        </div>
    </div>
</x-app-layout>

