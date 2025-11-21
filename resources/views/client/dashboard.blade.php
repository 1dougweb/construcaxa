<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Painel do Cliente') }}
        </h2>
    </x-slot>

    <div class="py-6">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <!-- Estatísticas -->
            <div class="grid grid-cols-1 md:grid-cols-4 gap-6 mb-6">
                <div class="bg-white overflow-hidden shadow rounded-lg">
                    <div class="p-5">
                        <div class="flex items-center">
                            <div class="flex-shrink-0 bg-indigo-500 rounded-md p-3">
                                <i class="bi bi-folder text-white text-2xl"></i>
                            </div>
                            <div class="ml-5 w-0 flex-1">
                                <dl>
                                    <dt class="text-sm font-medium text-gray-500 truncate">Total de Projetos</dt>
                                    <dd class="text-lg font-medium text-gray-900">{{ $stats['total_projects'] }}</dd>
                                </dl>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="bg-white overflow-hidden shadow rounded-lg">
                    <div class="p-5">
                        <div class="flex items-center">
                            <div class="flex-shrink-0 bg-green-500 rounded-md p-3">
                                <i class="bi bi-check-circle text-white text-2xl"></i>
                            </div>
                            <div class="ml-5 w-0 flex-1">
                                <dl>
                                    <dt class="text-sm font-medium text-gray-500 truncate">Projetos Ativos</dt>
                                    <dd class="text-lg font-medium text-gray-900">{{ $stats['active_projects'] }}</dd>
                                </dl>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="bg-white overflow-hidden shadow rounded-lg">
                    <div class="p-5">
                        <div class="flex items-center">
                            <div class="flex-shrink-0 bg-purple-500 rounded-md p-3">
                                <i class="bi bi-file-earmark-text text-white text-2xl"></i>
                            </div>
                            <div class="ml-5 w-0 flex-1">
                                <dl>
                                    <dt class="text-sm font-medium text-gray-500 truncate">Total de Contratos</dt>
                                    <dd class="text-lg font-medium text-gray-900">{{ $stats['total_contracts'] }}</dd>
                                </dl>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="bg-white overflow-hidden shadow rounded-lg">
                    <div class="p-5">
                        <div class="flex items-center">
                            <div class="flex-shrink-0 bg-blue-500 rounded-md p-3">
                                <i class="bi bi-file-check text-white text-2xl"></i>
                            </div>
                            <div class="ml-5 w-0 flex-1">
                                <dl>
                                    <dt class="text-sm font-medium text-gray-500 truncate">Contratos Ativos</dt>
                                    <dd class="text-lg font-medium text-gray-900">{{ $stats['active_contracts'] }}</dd>
                                </dl>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
                <!-- Projetos Recentes -->
                <div class="bg-white shadow rounded-lg">
                    <div class="p-6 border-b border-gray-200">
                        <div class="flex items-center justify-between">
                            <h3 class="text-lg font-medium text-gray-900">Projetos Recentes</h3>
                            <a href="{{ route('client.projects.index') }}" class="text-sm text-indigo-600 hover:text-indigo-900">
                                Ver todos →
                            </a>
                        </div>
                    </div>
                    <div class="p-6">
                        @if($projects->count() > 0)
                            <div class="space-y-4">
                                @foreach($projects as $project)
                                    <div class="border-b border-gray-200 pb-4 last:border-0 last:pb-0">
                                        <div class="flex items-start justify-between">
                                            <div class="flex-1">
                                                <h4 class="text-sm font-medium text-gray-900">
                                                    <a href="{{ route('client.projects.show', $project) }}" class="hover:text-indigo-600">
                                                        {{ $project->name }}
                                                    </a>
                                                </h4>
                                                <p class="text-sm text-gray-500 mt-1">{{ $project->code }}</p>
                                                @if($project->progress_percentage !== null)
                                                    <div class="mt-2">
                                                        <div class="flex justify-between text-xs mb-1">
                                                            <span>Progresso</span>
                                                            <span>{{ $project->progress_percentage }}%</span>
                                                        </div>
                                                        <div class="w-full bg-gray-200 rounded-full h-2">
                                                            <div class="bg-indigo-600 h-2 rounded-full" style="width: {{ $project->progress_percentage }}%"></div>
                                                        </div>
                                                    </div>
                                                @endif
                                            </div>
                                            <span class="ml-4 px-2 py-1 text-xs rounded-full bg-blue-100 text-blue-800">
                                                {{ ucfirst(str_replace('_', ' ', $project->status)) }}
                                            </span>
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                        @else
                            <p class="text-gray-500 text-sm">Nenhum projeto encontrado.</p>
                        @endif
                    </div>
                </div>

                <!-- Contratos Recentes -->
                <div class="bg-white shadow rounded-lg">
                    <div class="p-6 border-b border-gray-200">
                        <div class="flex items-center justify-between">
                            <h3 class="text-lg font-medium text-gray-900">Contratos Recentes</h3>
                        </div>
                    </div>
                    <div class="p-6">
                        @if($contracts->count() > 0)
                            <div class="space-y-4">
                                @foreach($contracts as $contract)
                                    <div class="border-b border-gray-200 pb-4 last:border-0 last:pb-0">
                                        <div class="flex items-start justify-between">
                                            <div class="flex-1">
                                                <h4 class="text-sm font-medium text-gray-900">{{ $contract->contract_number }}</h4>
                                                <p class="text-sm text-gray-500 mt-1">{{ $contract->title }}</p>
                                                @if($contract->value)
                                                    <p class="text-sm text-gray-700 mt-1">{{ $contract->formatted_value }}</p>
                                                @endif
                                            </div>
                                            <span class="ml-4 px-2 py-1 text-xs rounded-full {{ $contract->status_color }}">
                                                {{ $contract->status_label }}
                                            </span>
                                        </div>
                                        @if($contract->file_path)
                                            <a href="{{ route('contracts.download', $contract) }}" 
                                               class="mt-2 inline-flex items-center text-sm text-indigo-600 hover:text-indigo-900">
                                                <i class="bi bi-download mr-1"></i>
                                                Baixar PDF
                                            </a>
                                        @endif
                                    </div>
                                @endforeach
                            </div>
                        @else
                            <p class="text-gray-500 text-sm">Nenhum contrato encontrado.</p>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>



