<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                {{ __('Detalhes da Conta a Receber') }}
            </h2>
            @can('manage finances')
            <div class="flex space-x-2">
                <a href="{{ route('financial.accounts-receivable.edit', $accountReceivable) }}" class="inline-flex items-center px-4 py-2 bg-indigo-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-indigo-700">
                    Editar
                </a>
                <form method="POST" action="{{ route('financial.accounts-receivable.destroy', $accountReceivable) }}" class="inline">
                    @csrf
                    @method('DELETE')
                    <button type="submit" onclick="return confirm('Tem certeza que deseja excluir esta conta a receber?')" class="inline-flex items-center px-4 py-2 bg-red-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-red-700">
                        Excluir
                    </button>
                </form>
            </div>
            @endcan
        </div>
    </x-slot>

    <div class="py-6">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-xl sm:rounded-lg">
                <div class="p-6 lg:p-8 bg-white border-b border-gray-200">
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div>
                            <label class="block text-sm font-medium text-gray-700">Número</label>
                            <p class="mt-1 text-sm text-gray-900">{{ $accountReceivable->number }}</p>
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-gray-700">Status</label>
                            <span class="mt-1 px-2 inline-flex text-xs leading-5 font-semibold rounded-full {{ $accountReceivable->status_color }}">
                                {{ $accountReceivable->status_label }}
                            </span>
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-gray-700">Cliente</label>
                            <p class="mt-1 text-sm text-gray-900">{{ optional($accountReceivable->client)->name ?? '-' }}</p>
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-gray-700">Obra</label>
                            <p class="mt-1 text-sm text-gray-900">{{ optional($accountReceivable->project)->name ?? '-' }}</p>
                        </div>

                        <div class="md:col-span-2">
                            <label class="block text-sm font-medium text-gray-700">Descrição</label>
                            <p class="mt-1 text-sm text-gray-900">{{ $accountReceivable->description }}</p>
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-gray-700">Valor</label>
                            <p class="mt-1 text-lg font-semibold text-gray-900">R$ {{ number_format($accountReceivable->amount, 2, ',', '.') }}</p>
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-gray-700">Data de Vencimento</label>
                            <p class="mt-1 text-sm text-gray-900">{{ $accountReceivable->due_date->format('d/m/Y') }}</p>
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-gray-700">Data de Recebimento</label>
                            <p class="mt-1 text-sm text-gray-900">{{ $accountReceivable->received_date ? $accountReceivable->received_date->format('d/m/Y') : '-' }}</p>
                        </div>

                        @if($accountReceivable->notes)
                        <div class="md:col-span-2">
                            <label class="block text-sm font-medium text-gray-700">Observações</label>
                            <p class="mt-1 text-sm text-gray-900">{{ $accountReceivable->notes }}</p>
                        </div>
                        @endif

                        <div>
                            <label class="block text-sm font-medium text-gray-700">Criado por</label>
                            <p class="mt-1 text-sm text-gray-900">{{ optional($accountReceivable->user)->name ?? '-' }}</p>
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-gray-700">Criado em</label>
                            <p class="mt-1 text-sm text-gray-900">{{ $accountReceivable->created_at->format('d/m/Y H:i') }}</p>
                        </div>
                    </div>

                    <div class="mt-6">
                        <a href="{{ route('financial.accounts-receivable.index') }}" class="text-indigo-600 hover:text-indigo-900">
                            ← Voltar para a lista
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>

