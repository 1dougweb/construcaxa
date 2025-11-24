<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center justify-between">
            <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
                {{ __('Editar Contrato') }}
            </h2>
            <a href="{{ route('contracts.show', $contract) }}" class="text-gray-600 dark:text-gray-400 hover:text-gray-900 dark:hover:text-gray-200 transition-colors">
                <i class="bi bi-arrow-left mr-2"></i>Voltar
            </a>
        </div>
    </x-slot>

    <div class="py-6">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-xl sm:rounded-lg">
                <div class="p-6">
                    <form method="POST" action="{{ route('contracts.update', $contract) }}" enctype="multipart/form-data">
                        @csrf
                        @method('PUT')

                        <div class="mb-4">
                            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Cliente *</label>
                            <select name="client_id" required class="w-full border-gray-300 dark:border-gray-600 rounded-md bg-white dark:bg-gray-700 text-gray-900 dark:text-gray-100 focus:ring-indigo-500 focus:border-indigo-500">
                                @foreach($clients as $client)
                                    <option value="{{ $client->id }}" {{ old('client_id', $contract->client_id) == $client->id ? 'selected' : '' }}>
                                        {{ $client->name }}
                                    </option>
                                @endforeach
                            </select>
                            @error('client_id')
                                <p class="text-red-500 dark:text-red-400 text-xs mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-4">
                            <div>
                                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Projeto (opcional)</label>
                                <select name="project_id" class="w-full border-gray-300 dark:border-gray-600 rounded-md bg-white dark:bg-gray-700 text-gray-900 dark:text-gray-100 focus:ring-indigo-500 focus:border-indigo-500">
                                    <option value="">Nenhum</option>
                                    @foreach($projects as $project)
                                        <option value="{{ $project->id }}" {{ old('project_id', $contract->project_id) == $project->id ? 'selected' : '' }}>
                                            {{ $project->name }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Orçamento (opcional)</label>
                                <select name="budget_id" class="w-full border-gray-300 dark:border-gray-600 rounded-md bg-white dark:bg-gray-700 text-gray-900 dark:text-gray-100 focus:ring-indigo-500 focus:border-indigo-500">
                                    <option value="">Nenhum</option>
                                    @foreach($budgets as $budget)
                                        <option value="{{ $budget->id }}" {{ old('budget_id', $contract->budget_id) == $budget->id ? 'selected' : '' }}>
                                            {{ $budget->id }} - {{ $budget->client->name ?? 'N/A' }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                        </div>

                        <div class="mb-4">
                            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Título *</label>
                            <input type="text" name="title" value="{{ old('title', $contract->title) }}" required class="w-full border-gray-300 dark:border-gray-600 rounded-md bg-white dark:bg-gray-700 text-gray-900 dark:text-gray-100 focus:ring-indigo-500 focus:border-indigo-500">
                            @error('title')
                                <p class="text-red-500 dark:text-red-400 text-xs mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        <div class="mb-4">
                            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Descrição</label>
                            <textarea name="description" rows="3" class="w-full border-gray-300 dark:border-gray-600 rounded-md bg-white dark:bg-gray-700 text-gray-900 dark:text-gray-100 focus:ring-indigo-500 focus:border-indigo-500">{{ old('description', $contract->description) }}</textarea>
                        </div>

                        <div class="grid grid-cols-1 md:grid-cols-3 gap-4 mb-4">
                            <div>
                                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Data de Início</label>
                                <input type="date" name="start_date" value="{{ old('start_date', $contract->start_date?->format('Y-m-d')) }}" class="w-full border-gray-300 dark:border-gray-600 rounded-md bg-white dark:bg-gray-700 text-gray-900 dark:text-gray-100 focus:ring-indigo-500 focus:border-indigo-500">
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Data de Término</label>
                                <input type="date" name="end_date" value="{{ old('end_date', $contract->end_date?->format('Y-m-d')) }}" class="w-full border-gray-300 dark:border-gray-600 rounded-md bg-white dark:bg-gray-700 text-gray-900 dark:text-gray-100 focus:ring-indigo-500 focus:border-indigo-500">
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Valor</label>
                                <input type="number" name="value" step="0.01" value="{{ old('value', $contract->value) }}" class="w-full border-gray-300 dark:border-gray-600 rounded-md bg-white dark:bg-gray-700 text-gray-900 dark:text-gray-100 focus:ring-indigo-500 focus:border-indigo-500" placeholder="0.00">
                            </div>
                        </div>

                        <div class="mb-4">
                            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Status *</label>
                            <select name="status" required class="w-full border-gray-300 dark:border-gray-600 rounded-md bg-white dark:bg-gray-700 text-gray-900 dark:text-gray-100 focus:ring-indigo-500 focus:border-indigo-500">
                                <option value="draft" {{ old('status', $contract->status) === 'draft' ? 'selected' : '' }}>Rascunho</option>
                                <option value="active" {{ old('status', $contract->status) === 'active' ? 'selected' : '' }}>Ativo</option>
                                <option value="expired" {{ old('status', $contract->status) === 'expired' ? 'selected' : '' }}>Expirado</option>
                                <option value="cancelled" {{ old('status', $contract->status) === 'cancelled' ? 'selected' : '' }}>Cancelado</option>
                            </select>
                            @error('status')
                                <p class="text-red-500 dark:text-red-400 text-xs mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        <div class="mb-4">
                            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Arquivo PDF (máx. 10MB)</label>
                            @if($contract->file_path)
                                <p class="text-sm text-gray-600 dark:text-gray-400 mb-2">Arquivo atual: <a href="{{ route('contracts.download', $contract) }}" class="text-indigo-600 dark:text-indigo-400 hover:text-indigo-900 dark:hover:text-indigo-300 transition-colors">Baixar</a></p>
                                <p class="text-sm text-gray-500 dark:text-gray-400 mb-2">Envie um novo arquivo para substituir o atual.</p>
                            @endif
                            <input type="file" name="file" accept=".pdf" class="w-full border-gray-300 dark:border-gray-600 rounded-md bg-white dark:bg-gray-700 text-gray-900 dark:text-gray-100 file:mr-4 file:py-2 file:px-4 file:rounded-md file:border-0 file:text-sm file:font-semibold file:bg-indigo-50 dark:file:bg-indigo-900/30 file:text-indigo-700 dark:file:text-indigo-300 hover:file:bg-indigo-100 dark:hover:file:bg-indigo-900/50">
                            @error('file')
                                <p class="text-red-500 dark:text-red-400 text-xs mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        <div class="mb-4">
                            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Data de Assinatura</label>
                            <input type="datetime-local" name="signed_at" value="{{ old('signed_at', $contract->signed_at?->format('Y-m-d\TH:i')) }}" class="w-full border-gray-300 dark:border-gray-600 rounded-md bg-white dark:bg-gray-700 text-gray-900 dark:text-gray-100 focus:ring-indigo-500 focus:border-indigo-500">
                        </div>

                        <div class="mb-6">
                            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Observações</label>
                            <textarea name="notes" rows="3" class="w-full border-gray-300 dark:border-gray-600 rounded-md bg-white dark:bg-gray-700 text-gray-900 dark:text-gray-100 focus:ring-indigo-500 focus:border-indigo-500">{{ old('notes', $contract->notes) }}</textarea>
                        </div>

                        <div class="flex justify-end space-x-3">
                            <a href="{{ route('contracts.show', $contract) }}" class="px-4 py-2 bg-gray-300 dark:bg-gray-600 text-gray-700 dark:text-gray-200 rounded-md hover:bg-gray-400 dark:hover:bg-gray-500 transition-colors">
                                Cancelar
                            </a>
                            <x-button-loading variant="primary" type="submit">
                                Atualizar Contrato
                            </x-button-loading>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>



