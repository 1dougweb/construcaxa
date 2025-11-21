<x-app-layout>
    <x-slot name="header"><h2 class="font-semibold text-xl text-gray-800 leading-tight">{{ __('Editar Recibo') }}</h2></x-slot>
    <div class="py-6">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-xl sm:rounded-lg">
                <div class="p-6">
                    <form method="POST" action="{{ route('financial.receipts.update', $receipt) }}">
                        @csrf @method('PUT')
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <div><label class="block text-sm font-medium text-gray-700">Cliente *</label>
                                <select name="client_id" required class="mt-1 block w-full border-gray-300 rounded-md shadow-sm">
                                    @foreach($clients as $client)
                                        <option value="{{ $client->id }}" {{ $receipt->client_id == $client->id ? 'selected' : '' }}>{{ $client->name }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div><label class="block text-sm font-medium text-gray-700">Obra</label>
                                <select name="project_id" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm">
                                    <option value="">Selecione</option>
                                    @foreach($projects as $project)
                                        <option value="{{ $project->id }}" {{ $receipt->project_id == $project->id ? 'selected' : '' }}>{{ $project->name }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div><label class="block text-sm font-medium text-gray-700">Nota Fiscal</label>
                                <select name="invoice_id" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm">
                                    <option value="">Selecione</option>
                                    @foreach($invoices as $invoice)
                                        <option value="{{ $invoice->id }}" {{ $receipt->invoice_id == $invoice->id ? 'selected' : '' }}>{{ $invoice->number }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div><label class="block text-sm font-medium text-gray-700">Data *</label>
                                <input type="date" name="issue_date" required value="{{ old('issue_date', $receipt->issue_date->format('Y-m-d')) }}" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm">
                            </div>
                            <div><label class="block text-sm font-medium text-gray-700">Valor *</label>
                                <input type="number" name="amount" step="0.01" required value="{{ old('amount', $receipt->amount) }}" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm">
                            </div>
                            <div><label class="block text-sm font-medium text-gray-700">Forma de Pagamento *</label>
                                <select name="payment_method" required class="mt-1 block w-full border-gray-300 rounded-md shadow-sm">
                                    @foreach(\App\Models\Receipt::getPaymentMethodOptions() as $value => $label)
                                        <option value="{{ $value }}" {{ $receipt->payment_method == $value ? 'selected' : '' }}>{{ $label }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="md:col-span-2"><label class="block text-sm font-medium text-gray-700">Descrição</label>
                                <input type="text" name="description" value="{{ old('description', $receipt->description) }}" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm">
                            </div>
                            <div class="md:col-span-2"><label class="block text-sm font-medium text-gray-700">Observações</label>
                                <textarea name="notes" rows="3" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm">{{ old('notes', $receipt->notes) }}</textarea>
                            </div>
                        </div>
                        <div class="flex items-center justify-end mt-6">
                            <a href="{{ route('financial.receipts.index') }}" class="mr-4 text-gray-600">Cancelar</a>
                            <button type="submit" class="px-4 py-2 bg-indigo-600 text-white rounded-md hover:bg-indigo-700">Atualizar</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>

