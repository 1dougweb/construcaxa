<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center justify-between">
            <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
                {{ __('Detalhes do Cliente') }}
            </h2>
            <div class="flex space-x-2">
                @can('edit clients')
                <a href="{{ route('clients.edit', $client) }}"
                   class="inline-flex items-center px-4 py-2 bg-indigo-600 dark:bg-indigo-700 text-white rounded-md hover:bg-indigo-700 dark:hover:bg-indigo-600 transition-colors">
                    <i class="bi bi-pencil mr-2"></i>
                    Editar
                </a>
                @endcan
                <a href="{{ route('clients.index') }}"
                   class="inline-flex items-center px-4 py-2 bg-gray-600 dark:bg-gray-700 text-white rounded-md hover:bg-gray-700 dark:hover:bg-gray-600 transition-colors">
                    <i class="bi bi-arrow-left mr-2"></i>
                    Voltar
                </a>
            </div>
        </div>
    </x-slot>

    <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
        <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-xl sm:rounded-lg">
            <div class="p-6">
                <!-- Header -->
                <div class="flex items-start justify-between mb-6">
                    <div class="flex-1">
                        <div class="flex items-center space-x-3 mb-2">
                            <h1 class="text-2xl font-bold text-gray-900 dark:text-gray-100">{{ $client->name }}</h1>
                            <span class="px-3 py-1 text-sm rounded-full {{ $client->is_active ? 'bg-green-100 dark:bg-green-900/30 text-green-800 dark:text-green-300' : 'bg-red-100 dark:bg-red-900/30 text-red-800 dark:text-red-300' }}">
                                {{ $client->is_active ? 'Ativo' : 'Inativo' }}
                            </span>
                        </div>
                        @if($client->trading_name)
                            <p class="text-gray-600 dark:text-gray-400">{{ $client->trading_name }}</p>
                        @endif
                        <p class="text-sm text-gray-500 dark:text-gray-400">{{ $client->type_label }}</p>
                    </div>
                </div>

                <!-- Info Cards -->
                <div class="grid grid-cols-1 md:grid-cols-3 gap-4 mb-6">
                    <div class="bg-gray-50 dark:bg-gray-700 rounded-lg p-4">
                        <div class="text-sm text-gray-600 dark:text-gray-400 mb-1">CPF/CNPJ</div>
                        <div class="font-medium text-gray-900 dark:text-gray-100">
                            @if($client->cpf)
                                {{ $client->formatted_cpf }}
                            @elseif($client->cnpj)
                                {{ $client->formatted_cnpj }}
                            @else
                                -
                            @endif
                        </div>
                    </div>
                    <div class="bg-gray-50 dark:bg-gray-700 rounded-lg p-4">
                        <div class="text-sm text-gray-600 dark:text-gray-400 mb-1">Email</div>
                        <div class="font-medium text-gray-900 dark:text-gray-100">{{ $client->email }}</div>
                    </div>
                    <div class="bg-gray-50 dark:bg-gray-700 rounded-lg p-4">
                        <div class="text-sm text-gray-600 dark:text-gray-400 mb-1">Telefone</div>
                        <div class="font-medium text-gray-900 dark:text-gray-100">{{ $client->phone ?: '-' }}</div>
                    </div>
                </div>

                <!-- Endereço -->
                @if($client->address || $client->city)
                <div class="mb-6">
                    <h3 class="text-lg font-semibold text-gray-900 dark:text-gray-100 mb-2">Endereço</h3>
                    <div class="bg-gray-50 dark:bg-gray-700 rounded-lg p-4">
                        <p class="text-gray-700 dark:text-gray-300">
                            @if($client->address)
                                {{ $client->address }}
                                @if($client->address_number), {{ $client->address_number }}@endif
                                @if($client->address_complement) - {{ $client->address_complement }}@endif
                                <br>
                            @endif
                            @if($client->neighborhood)
                                {{ $client->neighborhood }}
                                <br>
                            @endif
                            @if($client->city || $client->state)
                                {{ $client->city }}{{ $client->state ? ' - ' . $client->state : '' }}
                                @if($client->zip_code) - {{ $client->zip_code }}@endif
                            @endif
                        </p>
                    </div>
                </div>
                @endif

                <!-- Observações -->
                @if($client->notes)
                <div class="mb-6">
                    <h3 class="text-lg font-semibold text-gray-900 dark:text-gray-100 mb-2">Observações</h3>
                    <div class="bg-gray-50 dark:bg-gray-700 rounded-lg p-4">
                        <p class="text-gray-700 dark:text-gray-300 whitespace-pre-wrap">{{ $client->notes }}</p>
                    </div>
                </div>
                @endif

                <!-- Estatísticas -->
                <div class="grid grid-cols-1 md:grid-cols-3 gap-4 mb-6">
                    <div class="bg-blue-50 dark:bg-blue-900/30 rounded-lg p-4">
                        <div class="text-sm text-blue-600 dark:text-blue-400 mb-1">Projetos</div>
                        <div class="text-2xl font-bold text-blue-900 dark:text-blue-100">{{ $client->projects_count }}</div>
                    </div>
                    <div class="bg-purple-50 dark:bg-purple-900/30 rounded-lg p-4">
                        <div class="text-sm text-purple-600 dark:text-purple-400 mb-1">Contratos</div>
                        <div class="text-2xl font-bold text-purple-900 dark:text-purple-100">{{ $client->contracts_count }}</div>
                    </div>
                    <div class="bg-green-50 dark:bg-green-900/30 rounded-lg p-4">
                        <div class="text-sm text-green-600 dark:text-green-400 mb-1">Orçamentos</div>
                        <div class="text-2xl font-bold text-green-900 dark:text-green-100">{{ $client->budgets_count }}</div>
                    </div>
                </div>

                <!-- Contratos -->
                <div class="border-t border-gray-200 dark:border-gray-700 pt-6 mb-6">
                    <div class="flex items-center justify-between mb-4">
                        <h3 class="text-lg font-semibold text-gray-900 dark:text-gray-100">Contratos</h3>
                        @can('create contracts')
                        <a href="{{ route('contracts.create', ['client_id' => $client->id]) }}"
                           class="inline-flex items-center px-4 py-2 bg-indigo-600 dark:bg-indigo-700 text-white rounded-md hover:bg-indigo-700 dark:hover:bg-indigo-600 transition-colors">
                            <i class="bi bi-plus-circle mr-2"></i>
                            Novo Contrato
                        </a>
                        @endcan
                    </div>

                    @if($client->contracts->count() > 0)
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                            @foreach($client->contracts as $contract)
                                <div class="bg-gray-50 dark:bg-gray-700 rounded-lg p-4 border border-gray-200 dark:border-gray-600 hover:shadow-md transition-shadow">
                                    <div class="flex items-start justify-between mb-2">
                                        <div>
                                            <h4 class="font-semibold text-gray-900 dark:text-gray-100">{{ $contract->contract_number }}</h4>
                                            <p class="text-sm text-gray-600 dark:text-gray-400">{{ $contract->title }}</p>
                                        </div>
                                        <span class="px-2 py-1 text-xs rounded-full {{ $contract->status_color }}">
                                            {{ $contract->status_label }}
                                        </span>
                                    </div>
                                    @if($contract->value)
                                        <p class="text-sm text-gray-700 dark:text-gray-300 mb-2">{{ $contract->formatted_value }}</p>
                                    @endif
                                    <div class="flex space-x-2">
                                        <a href="{{ route('contracts.show', $contract) }}"
                                           class="text-sm text-indigo-600 dark:text-indigo-400 hover:text-indigo-900 dark:hover:text-indigo-300 transition-colors">
                                            Ver detalhes →
                                        </a>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    @else
                        <div class="text-center py-8 bg-gray-50 dark:bg-gray-700 rounded-lg">
                            <i class="bi bi-file-earmark-text text-4xl text-gray-400 dark:text-gray-500 mb-2"></i>
                            <p class="text-gray-600 dark:text-gray-400">Nenhum contrato cadastrado para este cliente.</p>
                        </div>
                    @endif
                </div>

                <!-- Projetos -->
                <div class="border-t border-gray-200 dark:border-gray-700 pt-6">
                    <div class="flex items-center justify-between mb-4">
                        <h3 class="text-lg font-semibold text-gray-900 dark:text-gray-100">Projetos</h3>
                    </div>

                    @if($client->projects->count() > 0)
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                            @foreach($client->projects as $project)
                                <div class="bg-gray-50 dark:bg-gray-700 rounded-lg p-4 border border-gray-200 dark:border-gray-600 hover:shadow-md transition-shadow">
                                    <div class="flex items-start justify-between mb-2">
                                        <div>
                                            <h4 class="font-semibold text-gray-900 dark:text-gray-100">{{ $project->name }}</h4>
                                            <p class="text-sm text-gray-600 dark:text-gray-400">{{ $project->code }}</p>
                                        </div>
                                        <span class="px-2 py-1 text-xs rounded-full bg-blue-100 dark:bg-blue-900/30 text-blue-800 dark:text-blue-300">
                                            {{ ucfirst(str_replace('_', ' ', $project->status)) }}
                                        </span>
                                    </div>
                                    @if($project->progress_percentage !== null)
                                        <div class="mt-2">
                                            <div class="flex justify-between text-sm mb-1 text-gray-700 dark:text-gray-300">
                                                <span>Progresso</span>
                                                <span>{{ $project->progress_percentage }}%</span>
                                            </div>
                                            <div class="w-full bg-gray-200 dark:bg-gray-600 rounded-full h-2">
                                                <div class="bg-indigo-600 dark:bg-indigo-500 h-2 rounded-full" style="width: {{ $project->progress_percentage }}%"></div>
                                            </div>
                                        </div>
                                    @endif
                                    <div class="mt-3">
                                        <a href="{{ route('projects.show', $project) }}"
                                           class="text-sm text-indigo-600 dark:text-indigo-400 hover:text-indigo-900 dark:hover:text-indigo-300 transition-colors">
                                            Ver projeto →
                                        </a>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    @else
                        <div class="text-center py-8 bg-gray-50 dark:bg-gray-700 rounded-lg">
                            <i class="bi bi-folder text-4xl text-gray-400 dark:text-gray-500 mb-2"></i>
                            <p class="text-gray-600 dark:text-gray-400">Nenhum projeto cadastrado para este cliente.</p>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</x-app-layout>



