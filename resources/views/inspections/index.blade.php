<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
                {{ __('Vistorias') }}
            </h2>
            @can('create inspections')
            <a href="{{ route('inspections.create') }}" class="inline-flex items-center px-4 py-2 bg-indigo-600 dark:bg-indigo-700 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-indigo-700 dark:hover:bg-indigo-600">
                <i class="bi bi-plus-circle mr-2"></i>
                Nova Vistoria
            </a>
            @endcan
        </div>
    </x-slot>

    <div class="py-6">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-xl sm:rounded-lg border border-gray-200 dark:border-gray-700">
                <div class="p-6">
                    <!-- Filtros -->
                    <form method="GET" action="{{ route('inspections.index') }}" class="mb-6">
                        <div class="grid grid-cols-1 md:grid-cols-4 gap-4">
                            <div>
                                <input type="text" name="search" value="{{ request('search') }}" 
                                       placeholder="Buscar por número, descrição ou cliente..." 
                                       class="w-full border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-gray-300 rounded-md">
                            </div>
                            <div>
                                <select name="client_id" class="w-full border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-gray-300 rounded-md">
                                    <option value="">Todos os clientes</option>
                                    @foreach($clients as $client)
                                        <option value="{{ $client->id }}" {{ request('client_id') == $client->id ? 'selected' : '' }}>
                                            {{ $client->name }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                            <div>
                                <select name="status" class="w-full border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-gray-300 rounded-md">
                                    <option value="">Todos os status</option>
                                    <option value="draft" {{ request('status') === 'draft' ? 'selected' : '' }}>Rascunho</option>
                                    <option value="pending" {{ request('status') === 'pending' ? 'selected' : '' }}>Pendente</option>
                                    <option value="approved" {{ request('status') === 'approved' ? 'selected' : '' }}>Aprovada</option>
                                    <option value="rejected" {{ request('status') === 'rejected' ? 'selected' : '' }}>Rejeitada</option>
                                </select>
                            </div>
                            <div>
                                <button type="submit" class="w-full px-4 py-2 bg-indigo-600 dark:bg-indigo-700 text-white rounded-md hover:bg-indigo-700 dark:hover:bg-indigo-600">
                                    Filtrar
                                </button>
                            </div>
                        </div>
                    </form>

                    <!-- Tabela de Vistorias -->
                    <div class="overflow-x-auto">
                        <table class="min-w-full divide-y divide-gray-200 dark:divide-gray-700">
                            <thead class="bg-gray-50 dark:bg-gray-700/50">
                                <tr>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">Número</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">Cliente</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">Data</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">Versão</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">Status</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">Responsável</th>
                                    <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">Ações</th>
                                </tr>
                            </thead>
                            <tbody class="bg-white dark:bg-gray-800 divide-y divide-gray-200 dark:divide-gray-700">
                                @forelse($inspections as $inspection)
                                    <tr class="hover:bg-gray-50 dark:hover:bg-gray-700/50">
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            <div class="text-sm font-medium text-gray-900 dark:text-gray-100">{{ $inspection->number }}</div>
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            <div class="text-sm text-gray-900 dark:text-gray-100">{{ $inspection->client->name }}</div>
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500 dark:text-gray-400">
                                            {{ $inspection->inspection_date->format('d/m/Y') }}
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500 dark:text-gray-400">
                                            v{{ $inspection->version }}
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            <span class="px-2 py-1 text-xs rounded-full border {{ $inspection->status_color }}">
                                                {{ $inspection->status_label }}
                                            </span>
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500 dark:text-gray-400">
                                            {{ $inspection->inspector->name ?? '-' }}
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                                            <div class="flex justify-end space-x-2">
                                                <a href="{{ route('inspections.show', $inspection) }}" 
                                                   class="text-indigo-600 dark:text-indigo-400 hover:text-indigo-900 dark:hover:text-indigo-300" title="Visualizar">
                                                    <i class="bi bi-eye"></i>
                                                </a>
                                                @can('edit inspections')
                                                <a href="{{ route('inspections.edit', $inspection) }}" 
                                                   class="text-yellow-600 dark:text-yellow-400 hover:text-yellow-900 dark:hover:text-yellow-300" title="Editar">
                                                    <i class="bi bi-pencil"></i>
                                                </a>
                                                @endcan
                                                <a href="{{ route('inspections.pdf', $inspection) }}" 
                                                   class="text-blue-600 dark:text-blue-400 hover:text-blue-900 dark:hover:text-blue-300" target="_blank" title="PDF">
                                                    <i class="bi bi-file-pdf"></i>
                                                </a>
                                            </div>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="7" class="px-6 py-4 text-center text-sm text-gray-500 dark:text-gray-400">
                                            Nenhuma vistoria encontrada.
                                        </td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>

                    <!-- Paginação -->
                    <div class="mt-4">
                        {{ $inspections->links() }}
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>

