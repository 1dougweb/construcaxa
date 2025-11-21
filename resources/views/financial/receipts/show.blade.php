<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">{{ __('Detalhes do Recibo') }}</h2>
            @can('manage finances')
            <div class="flex space-x-2">
                <a href="{{ route('financial.receipts.edit', $receipt) }}" class="px-4 py-2 bg-indigo-600 text-white rounded-md hover:bg-indigo-700">Editar</a>
                <a href="{{ route('financial.receipts.pdf', $receipt) }}" target="_blank" class="px-4 py-2 bg-green-600 text-white rounded-md hover:bg-green-700">PDF</a>
            </div>
            @endcan
        </div>
    </x-slot>
    <div class="py-6">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-xl sm:rounded-lg">
                <div class="p-6">
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div><label class="block text-sm font-medium text-gray-700">Número</label><p class="mt-1 text-sm text-gray-900">{{ $receipt->number }}</p></div>
                        <div><label class="block text-sm font-medium text-gray-700">Cliente</label><p class="mt-1 text-sm text-gray-900">{{ optional($receipt->client)->name ?? '-' }}</p></div>
                        <div><label class="block text-sm font-medium text-gray-700">Obra</label><p class="mt-1 text-sm text-gray-900">{{ optional($receipt->project)->name ?? '-' }}</p></div>
                        <div><label class="block text-sm font-medium text-gray-700">Nota Fiscal</label><p class="mt-1 text-sm text-gray-900">{{ optional($receipt->invoice)->number ?? '-' }}</p></div>
                        <div><label class="block text-sm font-medium text-gray-700">Valor</label><p class="mt-1 text-lg font-semibold text-gray-900">R$ {{ number_format($receipt->amount, 2, ',', '.') }}</p></div>
                        <div><label class="block text-sm font-medium text-gray-700">Forma de Pagamento</label><p class="mt-1 text-sm text-gray-900">{{ $receipt->payment_method_label }}</p></div>
                        <div><label class="block text-sm font-medium text-gray-700">Data</label><p class="mt-1 text-sm text-gray-900">{{ $receipt->issue_date->format('d/m/Y') }}</p></div>
                        @if($receipt->description)
                        <div><label class="block text-sm font-medium text-gray-700">Descrição</label><p class="mt-1 text-sm text-gray-900">{{ $receipt->description }}</p></div>
                        @endif
                    </div>
                    <div class="mt-6"><a href="{{ route('financial.receipts.index') }}" class="text-indigo-600 hover:text-indigo-900">← Voltar</a></div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>

