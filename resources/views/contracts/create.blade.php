<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center justify-between">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                {{ __('Novo Contrato') }}
            </h2>
            <a href="{{ route('contracts.index') }}" class="text-gray-600 hover:text-gray-900">
                <i class="bi bi-arrow-left mr-2"></i>Voltar
            </a>
        </div>
    </x-slot>

    <div class="py-6">
        <div class="max-w-4xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-xl sm:rounded-lg">
                <div class="p-6">
                    <form method="POST" action="{{ route('contracts.store') }}" enctype="multipart/form-data">
                        @csrf

                        <div class="mb-4">
                            <label class="block text-sm font-medium text-gray-700 mb-1">Cliente *</label>
                            <select name="client_id" required class="w-full border-gray-300 rounded-md">
                                <option value="">Selecione um cliente</option>
                                @foreach($clients as $client)
                                    <option value="{{ $client->id }}" {{ (old('client_id') == $client->id || ($selectedClient && $selectedClient->id == $client->id)) ? 'selected' : '' }}>
                                        {{ $client->name }}
                                    </option>
                                @endforeach
                            </select>
                            @error('client_id')
                                <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-4">
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-1">Projeto (opcional)</label>
                                <select name="project_id" class="w-full border-gray-300 rounded-md">
                                    <option value="">Nenhum</option>
                                    @foreach($projects as $project)
                                        <option value="{{ $project->id }}" {{ old('project_id') == $project->id ? 'selected' : '' }}>
                                            {{ $project->name }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-1">Orçamento (opcional)</label>
                                <select name="budget_id" class="w-full border-gray-300 rounded-md">
                                    <option value="">Nenhum</option>
                                    @foreach($budgets as $budget)
                                        <option value="{{ $budget->id }}" {{ old('budget_id') == $budget->id ? 'selected' : '' }}>
                                            {{ $budget->id }} - {{ $budget->client->name ?? 'N/A' }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                        </div>

                        <div class="mb-4">
                            <label class="block text-sm font-medium text-gray-700 mb-1">Título *</label>
                            <input type="text" name="title" value="{{ old('title') }}" required class="w-full border-gray-300 rounded-md">
                            @error('title')
                                <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        <div class="mb-4">
                            <label class="block text-sm font-medium text-gray-700 mb-1">Descrição</label>
                            <textarea name="description" rows="3" class="w-full border-gray-300 rounded-md">{{ old('description') }}</textarea>
                        </div>

                        <div class="grid grid-cols-1 md:grid-cols-3 gap-4 mb-4">
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-1">Data de Início</label>
                                <input type="date" name="start_date" value="{{ old('start_date') }}" class="w-full border-gray-300 rounded-md">
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-1">Data de Término</label>
                                <input type="date" name="end_date" value="{{ old('end_date') }}" class="w-full border-gray-300 rounded-md">
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-1">Valor</label>
                                <input type="number" name="value" step="0.01" value="{{ old('value') }}" class="w-full border-gray-300 rounded-md" placeholder="0.00">
                            </div>
                        </div>

                        <div class="mb-4">
                            <label class="block text-sm font-medium text-gray-700 mb-1">Status *</label>
                            <select name="status" required class="w-full border-gray-300 rounded-md">
                                <option value="draft" {{ old('status') === 'draft' ? 'selected' : '' }}>Rascunho</option>
                                <option value="active" {{ old('status') === 'active' ? 'selected' : '' }}>Ativo</option>
                                <option value="expired" {{ old('status') === 'expired' ? 'selected' : '' }}>Expirado</option>
                                <option value="cancelled" {{ old('status') === 'cancelled' ? 'selected' : '' }}>Cancelado</option>
                            </select>
                            @error('status')
                                <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        <div class="mb-4">
                            <label class="block text-sm font-medium text-gray-700 mb-1">Arquivo PDF (máx. 10MB)</label>
                            <input type="file" name="file" accept=".pdf" class="w-full border-gray-300 rounded-md">
                            @error('file')
                                <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        <div class="mb-4">
                            <label class="block text-sm font-medium text-gray-700 mb-1">Data de Assinatura</label>
                            <input type="datetime-local" name="signed_at" value="{{ old('signed_at') }}" class="w-full border-gray-300 rounded-md">
                        </div>

                        <div class="mb-6">
                            <label class="block text-sm font-medium text-gray-700 mb-1">Observações</label>
                            <textarea name="notes" rows="3" class="w-full border-gray-300 rounded-md">{{ old('notes') }}</textarea>
                        </div>

                        <div class="flex justify-end space-x-3">
                            <a href="{{ route('contracts.index') }}" class="px-4 py-2 bg-gray-300 text-gray-700 rounded-md hover:bg-gray-400">
                                Cancelar
                            </a>
                            <button type="submit" class="px-4 py-2 bg-indigo-600 text-white rounded-md hover:bg-indigo-700">
                                Salvar Contrato
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>



