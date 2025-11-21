<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">{{ __('Nova Nota Fiscal') }}</h2>
    </x-slot>
    <div class="py-6">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-xl sm:rounded-lg">
                <div class="p-6">
                    <form method="POST" action="{{ route('financial.invoices.store') }}">
                        @csrf
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <div><label class="block text-sm font-medium text-gray-700">Cliente *</label>
                                <select name="client_id" required class="mt-1 block w-full border-gray-300 rounded-md shadow-sm">
                                    @foreach($clients as $client)
                                        <option value="{{ $client->id }}">{{ $client->name }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div><label class="block text-sm font-medium text-gray-700">Obra</label>
                                <select name="project_id" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm">
                                    <option value="">Selecione</option>
                                    @foreach($projects as $project)
                                        <option value="{{ $project->id }}">{{ $project->name }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div><label class="block text-sm font-medium text-gray-700">Data de Emissão *</label>
                                <input type="date" name="issue_date" required value="{{ old('issue_date', date('Y-m-d')) }}" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm">
                            </div>
                            <div><label class="block text-sm font-medium text-gray-700">Data de Vencimento *</label>
                                <input type="date" name="due_date" required class="mt-1 block w-full border-gray-300 rounded-md shadow-sm">
                            </div>
                            <div><label class="block text-sm font-medium text-gray-700">Subtotal *</label>
                                <input type="number" name="subtotal" step="0.01" required class="mt-1 block w-full border-gray-300 rounded-md shadow-sm">
                            </div>
                            <div><label class="block text-sm font-medium text-gray-700">Impostos</label>
                                <input type="number" name="tax_amount" step="0.01" value="0" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm">
                            </div>
                            <div><label class="block text-sm font-medium text-gray-700">Status *</label>
                                <select name="status" required class="mt-1 block w-full border-gray-300 rounded-md shadow-sm">
                                    @foreach(\App\Models\Invoice::getStatusOptions() as $value => $label)
                                        <option value="{{ $value }}">{{ $label }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="md:col-span-2"><label class="block text-sm font-medium text-gray-700">Observações</label>
                                <textarea name="notes" rows="3" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm"></textarea>
                            </div>
                        </div>
                        <div class="flex items-center justify-end mt-6">
                            <a href="{{ route('financial.invoices.index') }}" class="mr-4 text-gray-600">Cancelar</a>
                            <button type="submit" class="px-4 py-2 bg-indigo-600 text-white rounded-md hover:bg-indigo-700">Salvar</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>

