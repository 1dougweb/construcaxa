<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                {{ __('Clientes') }}
            </h2>
            @can('create clients')
            <a href="{{ route('clients.create') }}" class="inline-flex items-center px-4 py-2 bg-indigo-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-indigo-700">
                <i class="bi bi-plus-circle mr-2"></i>
                Novo Cliente
            </a>
            @endcan
        </div>
    </x-slot>

    <div class="py-6">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-xl sm:rounded-lg">
                <div class="p-6">
                    <!-- Filtros -->
                    <form method="GET" action="{{ route('clients.index') }}" class="mb-6">
                        <div class="grid grid-cols-1 md:grid-cols-4 gap-4">
                            <div>
                                <input type="text" name="search" value="{{ request('search') }}" 
                                       placeholder="Buscar por nome, email, CPF ou CNPJ..." 
                                       class="w-full border-gray-300 rounded-md">
                            </div>
                            <div>
                                <select name="type" class="w-full border-gray-300 rounded-md">
                                    <option value="">Todos os tipos</option>
                                    <option value="individual" {{ request('type') === 'individual' ? 'selected' : '' }}>Pessoa Física</option>
                                    <option value="company" {{ request('type') === 'company' ? 'selected' : '' }}>Pessoa Jurídica</option>
                                </select>
                            </div>
                            <div>
                                <select name="is_active" class="w-full border-gray-300 rounded-md">
                                    <option value="">Todos</option>
                                    <option value="1" {{ request('is_active') === '1' ? 'selected' : '' }}>Ativos</option>
                                    <option value="0" {{ request('is_active') === '0' ? 'selected' : '' }}>Inativos</option>
                                </select>
                            </div>
                            <div>
                                <button type="submit" class="w-full px-4 py-2 bg-indigo-600 text-white rounded-md hover:bg-indigo-700">
                                    Filtrar
                                </button>
                            </div>
                        </div>
                    </form>

                    <!-- Tabela de Clientes -->
                    <div class="overflow-x-auto">
                        <table class="min-w-full divide-y divide-gray-200">
                            <thead class="bg-gray-50">
                                <tr>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Nome</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Tipo</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">CPF/CNPJ</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Email</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Projetos</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Status</th>
                                    <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">Ações</th>
                                </tr>
                            </thead>
                            <tbody class="bg-white divide-y divide-gray-200">
                                @forelse($clients as $client)
                                    <tr class="hover:bg-gray-50">
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            <div class="text-sm font-medium text-gray-900">{{ $client->name }}</div>
                                            @if($client->trading_name)
                                                <div class="text-sm text-gray-500">{{ $client->trading_name }}</div>
                                            @endif
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            <span class="px-2 py-1 text-xs rounded-full {{ $client->type === 'individual' ? 'bg-blue-100 text-blue-800' : 'bg-purple-100 text-purple-800' }}">
                                                {{ $client->type_label }}
                                            </span>
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                            @if($client->cpf)
                                                {{ $client->formatted_cpf }}
                                            @elseif($client->cnpj)
                                                {{ $client->formatted_cnpj }}
                                            @else
                                                -
                                            @endif
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">{{ $client->email }}</td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                            <span class="px-2 py-1 bg-gray-100 rounded">{{ $client->projects_count }}</span>
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            <span class="px-2 py-1 text-xs rounded-full {{ $client->is_active ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800' }}">
                                                {{ $client->is_active ? 'Ativo' : 'Inativo' }}
                                            </span>
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                                            <a href="{{ route('clients.show', $client) }}" class="text-indigo-600 hover:text-indigo-900 mr-3">Ver</a>
                                            @can('edit clients')
                                            <a href="{{ route('clients.edit', $client) }}" class="text-yellow-600 hover:text-yellow-900 mr-3">Editar</a>
                                            @endcan
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="7" class="px-6 py-4 text-center text-gray-500">
                                            Nenhum cliente encontrado.
                                        </td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>

                    <!-- Paginação -->
                    <div class="mt-6">
                        {{ $clients->links() }}
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>



