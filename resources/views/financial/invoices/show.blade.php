<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">{{ __('Detalhes da Nota Fiscal') }}</h2>
            @can('manage finances')
            <div class="flex space-x-2">
                <a href="{{ route('financial.invoices.edit', $invoice) }}" class="px-4 py-2 bg-indigo-600 text-white rounded-md hover:bg-indigo-700">Editar</a>
                <a href="{{ route('financial.invoices.pdf', $invoice) }}" target="_blank" class="px-4 py-2 bg-green-600 text-white rounded-md hover:bg-green-700">PDF</a>
            </div>
            @endcan
        </div>
    </x-slot>
    <div class="py-6">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-xl sm:rounded-lg">
                <div class="p-6">
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div><label class="block text-sm font-medium text-gray-700">Número</label><p class="mt-1 text-sm text-gray-900">{{ $invoice->number }}</p></div>
                        <div><label class="block text-sm font-medium text-gray-700">Status</label><span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full {{ $invoice->status_color }}">{{ $invoice->status_label }}</span></div>
                        <div><label class="block text-sm font-medium text-gray-700">Cliente</label><p class="mt-1 text-sm text-gray-900">{{ optional($invoice->client)->name ?? '-' }}</p></div>
                        <div><label class="block text-sm font-medium text-gray-700">Obra</label><p class="mt-1 text-sm text-gray-900">{{ optional($invoice->project)->name ?? '-' }}</p></div>
                        <div><label class="block text-sm font-medium text-gray-700">Subtotal</label><p class="mt-1 text-sm text-gray-900">R$ {{ number_format($invoice->subtotal, 2, ',', '.') }}</p></div>
                        <div><label class="block text-sm font-medium text-gray-700">Impostos</label><p class="mt-1 text-sm text-gray-900">R$ {{ number_format($invoice->tax_amount, 2, ',', '.') }}</p></div>
                        <div><label class="block text-sm font-medium text-gray-700">Total</label><p class="mt-1 text-lg font-semibold text-gray-900">R$ {{ number_format($invoice->total_amount, 2, ',', '.') }}</p></div>
                        <div><label class="block text-sm font-medium text-gray-700">Data de Emissão</label><p class="mt-1 text-sm text-gray-900">{{ $invoice->issue_date->format('d/m/Y') }}</p></div>
                        <div><label class="block text-sm font-medium text-gray-700">Data de Vencimento</label><p class="mt-1 text-sm text-gray-900">{{ $invoice->due_date->format('d/m/Y') }}</p></div>
                    </div>
                    <div class="mt-6"><a href="{{ route('financial.invoices.index') }}" class="text-indigo-600 hover:text-indigo-900">← Voltar</a></div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>

