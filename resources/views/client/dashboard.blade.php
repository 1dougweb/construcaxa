<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('Painel do Cliente') }}
        </h2>
    </x-slot>

    <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 py-6">
        <!-- Cabeçalho -->
        <div class="mb-6">
            <h1 class="text-3xl font-bold text-gray-900 dark:text-gray-100">Painel do Cliente</h1>
            <p class="text-sm text-gray-500 dark:text-gray-400 mt-1">Bem-vindo, {{ $client->name ?? 'Cliente' }}</p>
        </div>

        <!-- Cards de Estatísticas -->
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow rounded-lg border border-gray-200 dark:border-gray-700">
                <div class="p-6">
                    <div class="flex items-center">
                        <div class="flex-shrink-0 bg-indigo-500 rounded-lg p-4">
                            <i class="bi bi-folder text-white text-2xl"></i>
                        </div>
                        <div class="ml-5 w-0 flex-1">
                            <dl>
                                <dt class="text-sm font-medium text-gray-500 dark:text-gray-400 truncate">Total de Projetos</dt>
                                <dd class="text-2xl font-bold text-gray-900 dark:text-gray-100 mt-1">{{ $stats['total_projects'] }}</dd>
                            </dl>
                        </div>
                    </div>
                </div>
            </div>

            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow rounded-lg border border-gray-200 dark:border-gray-700">
                <div class="p-6">
                    <div class="flex items-center">
                        <div class="flex-shrink-0 bg-green-500 rounded-lg p-4">
                            <i class="bi bi-check-circle text-white text-2xl"></i>
                        </div>
                        <div class="ml-5 w-0 flex-1">
                            <dl>
                                <dt class="text-sm font-medium text-gray-500 dark:text-gray-400 truncate">Projetos Ativos</dt>
                                <dd class="text-2xl font-bold text-green-600 dark:text-green-400 mt-1">{{ $stats['active_projects'] }}</dd>
                            </dl>
                        </div>
                    </div>
                </div>
            </div>

            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow rounded-lg border border-gray-200 dark:border-gray-700">
                <div class="p-6">
                    <div class="flex items-center">
                        <div class="flex-shrink-0 bg-purple-500 rounded-lg p-4">
                            <i class="bi bi-file-earmark-text text-white text-2xl"></i>
                        </div>
                        <div class="ml-5 w-0 flex-1">
                            <dl>
                                <dt class="text-sm font-medium text-gray-500 dark:text-gray-400 truncate">Total de Contratos</dt>
                                <dd class="text-2xl font-bold text-gray-900 dark:text-gray-100 mt-1">{{ $stats['total_contracts'] }}</dd>
                            </dl>
                        </div>
                    </div>
                </div>
            </div>

            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow rounded-lg border border-gray-200 dark:border-gray-700">
                <div class="p-6">
                    <div class="flex items-center">
                        <div class="flex-shrink-0 bg-blue-500 rounded-lg p-4">
                            <i class="bi bi-file-check text-white text-2xl"></i>
                        </div>
                        <div class="ml-5 w-0 flex-1">
                            <dl>
                                <dt class="text-sm font-medium text-gray-500 dark:text-gray-400 truncate">Contratos Ativos</dt>
                                <dd class="text-2xl font-bold text-blue-600 dark:text-blue-400 mt-1">{{ $stats['active_contracts'] }}</dd>
                            </dl>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Segunda linha de estatísticas -->
        <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-8">
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow rounded-lg border border-gray-200 dark:border-gray-700">
                <div class="p-6">
                    <div class="flex items-center">
                        <div class="flex-shrink-0 bg-amber-500 rounded-lg p-4">
                            <i class="bi bi-receipt text-white text-2xl"></i>
                        </div>
                        <div class="ml-5 w-0 flex-1">
                            <dl>
                                <dt class="text-sm font-medium text-gray-500 dark:text-gray-400 truncate">Total de Orçamentos</dt>
                                <dd class="text-2xl font-bold text-gray-900 dark:text-gray-100 mt-1">{{ $stats['total_budgets'] }}</dd>
                                <dd class="text-xs text-gray-500 dark:text-gray-400 mt-2">
                                    Em análise: <span class="font-semibold text-gray-900 dark:text-gray-100">{{ $stats['under_review_budgets'] }}</span> · 
                                    Aprovados: <span class="font-semibold text-gray-900 dark:text-gray-100">{{ $stats['approved_budgets'] }}</span>
                                </dd>
                            </dl>
                        </div>
                    </div>
                </div>
            </div>

            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow rounded-lg border border-gray-200 dark:border-gray-700">
                <div class="p-6">
                    <div class="flex items-center">
                        <div class="flex-shrink-0 bg-teal-500 rounded-lg p-4">
                            <i class="bi bi-clipboard-check text-white text-2xl"></i>
                        </div>
                        <div class="ml-5 w-0 flex-1">
                            <dl>
                                <dt class="text-sm font-medium text-gray-500 dark:text-gray-400 truncate">Vistorias Técnicas</dt>
                                <dd class="text-2xl font-bold text-gray-900 dark:text-gray-100 mt-1">{{ $stats['total_inspections'] }}</dd>
                            </dl>
                        </div>
                    </div>
                </div>
            </div>

            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow rounded-lg border border-gray-200 dark:border-gray-700">
                <div class="p-6">
                    <div class="flex items-center">
                        <div class="flex-shrink-0 bg-pink-500 rounded-lg p-4">
                            <i class="bi bi-images text-white text-2xl"></i>
                        </div>
                        <div class="ml-5 w-0 flex-1">
                            <dl>
                                <dt class="text-sm font-medium text-gray-500 dark:text-gray-400 truncate">Fotos da Obra</dt>
                                <dd class="text-2xl font-bold text-gray-900 dark:text-gray-100 mt-1">{{ $photos->count() }}</dd>
                            </dl>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Seção Principal: Projetos e Contratos -->
        <div class="grid grid-cols-1 lg:grid-cols-2 gap-6 mb-6">
            <!-- Projetos Recentes -->
            <div class="bg-white dark:bg-gray-800 shadow rounded-lg border border-gray-200 dark:border-gray-700">
                <div class="px-6 py-4 border-b border-gray-200 dark:border-gray-700">
                    <div class="flex items-center justify-between">
                        <h3 class="text-lg font-semibold text-gray-900 dark:text-gray-100">Projetos Recentes</h3>
                        <a href="{{ route('client.projects.index') }}" class="text-sm text-indigo-600 dark:text-indigo-400 hover:text-indigo-900 dark:hover:text-indigo-300 font-medium flex items-center gap-1">
                            Ver todos
                            <i class="bi bi-arrow-right"></i>
                        </a>
                    </div>
                </div>
                <div class="p-6">
                    @if($projects->count() > 0)
                        <div class="space-y-4">
                            @foreach($projects as $project)
                                <div class="border-b border-gray-200 dark:border-gray-700 pb-4 last:border-0 last:pb-0">
                                    <div class="flex items-start justify-between">
                                        <div class="flex-1 min-w-0">
                                            <h4 class="text-sm font-semibold text-gray-900 dark:text-gray-100 mb-1">
                                                <a href="{{ route('client.projects.show', $project) }}" class="hover:text-indigo-600 dark:hover:text-indigo-400 transition-colors">
                                                    {{ $project->name }}
                                                </a>
                                            </h4>
                                            <p class="text-xs text-gray-500 dark:text-gray-400 mb-2">{{ $project->code }}</p>
                                            @if($project->progress_percentage !== null)
                                                <div class="mt-3">
                                                    <div class="flex justify-between text-xs mb-1">
                                                        <span class="text-gray-600 dark:text-gray-400">Progresso</span>
                                                        <span class="font-medium text-gray-900 dark:text-gray-100">{{ $project->progress_percentage }}%</span>
                                                    </div>
                                                    <div class="w-full bg-gray-200 dark:bg-gray-700 rounded-full h-2">
                                                        <div class="bg-indigo-600 dark:bg-indigo-500 h-2 rounded-full transition-all duration-300" style="width: {{ $project->progress_percentage }}%"></div>
                                                    </div>
                                                </div>
                                            @endif
                                        </div>
                                        <span class="ml-4 px-3 py-1 text-xs font-medium rounded-full bg-blue-100 dark:bg-blue-900/30 text-blue-800 dark:text-blue-300 whitespace-nowrap">
                                            {{ ucfirst(str_replace('_', ' ', $project->status)) }}
                                        </span>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    @else
                        <div class="text-center py-8">
                            <i class="bi bi-folder-x text-4xl text-gray-400 dark:text-gray-500 mb-3"></i>
                            <p class="text-sm text-gray-500 dark:text-gray-400">Nenhum projeto encontrado.</p>
                        </div>
                    @endif
                </div>
            </div>

            <!-- Contratos Recentes -->
            <div class="bg-white dark:bg-gray-800 shadow rounded-lg border border-gray-200 dark:border-gray-700">
                <div class="px-6 py-4 border-b border-gray-200 dark:border-gray-700">
                    <h3 class="text-lg font-semibold text-gray-900 dark:text-gray-100">Contratos Recentes</h3>
                </div>
                <div class="p-6">
                    @if($contracts->count() > 0)
                        <div class="space-y-4">
                            @foreach($contracts as $contract)
                                <div class="border-b border-gray-200 dark:border-gray-700 pb-4 last:border-0 last:pb-0">
                                    <div class="flex items-start justify-between">
                                        <div class="flex-1 min-w-0">
                                            <h4 class="text-sm font-semibold text-gray-900 dark:text-gray-100 mb-1">
                                                {{ $contract->contract_number }}
                                            </h4>
                                            <p class="text-xs text-gray-500 dark:text-gray-400 mb-2">{{ $contract->title }}</p>
                                            @if($contract->value)
                                                <p class="text-sm font-medium text-gray-700 dark:text-gray-300">
                                                    {{ $contract->formatted_value }}
                                                </p>
                                            @endif
                                            @if($contract->file_path)
                                                <a href="{{ route('contracts.download', $contract) }}" 
                                                   class="mt-2 inline-flex items-center text-xs text-indigo-600 dark:text-indigo-400 hover:text-indigo-900 dark:hover:text-indigo-300">
                                                    <i class="bi bi-download mr-1"></i>
                                                    Baixar PDF
                                                </a>
                                            @endif
                                        </div>
                                        <span class="ml-4 px-3 py-1 text-xs font-medium rounded-full {{ $contract->status_color ?? 'bg-gray-100 dark:bg-gray-700 text-gray-800 dark:text-gray-200' }} whitespace-nowrap">
                                            {{ $contract->status_label ?? ucfirst($contract->status) }}
                                        </span>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    @else
                        <div class="text-center py-8">
                            <i class="bi bi-file-x text-4xl text-gray-400 dark:text-gray-500 mb-3"></i>
                            <p class="text-sm text-gray-500 dark:text-gray-400">Nenhum contrato encontrado.</p>
                        </div>
                    @endif
                </div>
            </div>
        </div>

        <!-- Segunda Seção: Orçamentos e Vistorias -->
        <div class="grid grid-cols-1 lg:grid-cols-2 gap-6 mb-6">
            <!-- Orçamentos Recentes -->
            <div class="bg-white dark:bg-gray-800 shadow rounded-lg border border-gray-200 dark:border-gray-700">
                <div class="px-6 py-4 border-b border-gray-200 dark:border-gray-700">
                    <h3 class="text-lg font-semibold text-gray-900 dark:text-gray-100">Orçamentos Recentes</h3>
                </div>
                <div class="p-6">
                    @if($budgets->count() > 0)
                        <div class="space-y-4">
                            @foreach($budgets as $budget)
                                <div class="border-b border-gray-200 dark:border-gray-700 pb-4 last:border-0 last:pb-0">
                                    <div class="flex items-start justify-between">
                                        <div class="flex-1 min-w-0">
                                            <h4 class="text-sm font-semibold text-gray-900 dark:text-gray-100 mb-1">
                                                Orçamento #{{ $budget->id }} - v{{ $budget->version }}
                                            </h4>
                                            @if($budget->total)
                                                <p class="text-sm font-medium text-gray-700 dark:text-gray-300 mt-1">
                                                    Valor total: R$ {{ number_format($budget->total, 2, ',', '.') }}
                                                </p>
                                            @endif
                                            @if($budget->notes)
                                                <p class="text-xs text-gray-500 dark:text-gray-400 mt-2 line-clamp-2">
                                                    {{ $budget->notes }}
                                                </p>
                                            @endif
                                        </div>
                                        <span class="ml-4 px-3 py-1 text-xs font-medium rounded-full bg-gray-100 dark:bg-gray-700 text-gray-800 dark:text-gray-200 whitespace-nowrap">
                                            {{ $budget->status_label ?? ucfirst($budget->status) }}
                                        </span>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    @else
                        <div class="text-center py-8">
                            <i class="bi bi-receipt text-4xl text-gray-400 dark:text-gray-500 mb-3"></i>
                            <p class="text-sm text-gray-500 dark:text-gray-400">Nenhum orçamento encontrado.</p>
                        </div>
                    @endif
                </div>
            </div>

            <!-- Vistorias Técnicas -->
            <div class="bg-white dark:bg-gray-800 shadow rounded-lg border border-gray-200 dark:border-gray-700">
                <div class="px-6 py-4 border-b border-gray-200 dark:border-gray-700">
                    <h3 class="text-lg font-semibold text-gray-900 dark:text-gray-100">Vistorias Técnicas</h3>
                </div>
                <div class="p-6">
                    @if($inspections->count() > 0)
                        <div class="space-y-4">
                            @foreach($inspections as $inspection)
                                <div class="border-b border-gray-200 dark:border-gray-700 pb-4 last:border-0 last:pb-0">
                                    <div class="flex items-start justify-between">
                                        <div class="flex-1 min-w-0">
                                            <h4 class="text-sm font-semibold text-gray-900 dark:text-gray-100 mb-1">
                                                Vistoria #{{ $inspection->number }}
                                            </h4>
                                            <p class="text-xs text-gray-500 dark:text-gray-400 mb-2">
                                                {{ optional($inspection->inspection_date)->format('d/m/Y') }}
                                            </p>
                                            @if($inspection->address)
                                                <p class="text-xs text-gray-500 dark:text-gray-400 line-clamp-2">
                                                    <i class="bi bi-geo-alt mr-1"></i>{{ $inspection->address }}
                                                </p>
                                            @endif
                                        </div>
                                        <span class="ml-4 px-3 py-1 text-xs font-medium rounded-full bg-gray-100 dark:bg-gray-700 text-gray-800 dark:text-gray-200 whitespace-nowrap">
                                            {{ ucfirst(str_replace('_', ' ', $inspection->status)) }}
                                        </span>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    @else
                        <div class="text-center py-8">
                            <i class="bi bi-clipboard-x text-4xl text-gray-400 dark:text-gray-500 mb-3"></i>
                            <p class="text-sm text-gray-500 dark:text-gray-400">Nenhuma vistoria encontrada.</p>
                        </div>
                    @endif
                </div>
            </div>
        </div>

        <!-- Galeria de Fotos -->
        <div class="bg-white dark:bg-gray-800 shadow rounded-lg border border-gray-200 dark:border-gray-700">
            <div class="px-6 py-4 border-b border-gray-200 dark:border-gray-700">
                <h3 class="text-lg font-semibold text-gray-900 dark:text-gray-100">Galeria de Fotos da Obra</h3>
            </div>
            <div class="p-6">
                @if($photos->count() > 0)
                    <div class="grid grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-4">
                        @foreach($photos as $photo)
                            <div class="relative group cursor-pointer overflow-hidden rounded-lg aspect-square bg-gray-100 dark:bg-gray-700">
                                <img 
                                    src="{{ \Illuminate\Support\Facades\Storage::url($photo->path ?? $photo->photo_path ?? '') }}"
                                    alt="Foto da obra"
                                    class="w-full h-full object-cover transition-transform duration-300 group-hover:scale-110"
                                    loading="lazy"
                                >
                                <div class="absolute inset-0 bg-black/0 group-hover:bg-black/20 transition-colors duration-300"></div>
                            </div>
                        @endforeach
                    </div>
                @else
                    <div class="text-center py-12">
                        <i class="bi bi-images text-4xl text-gray-400 dark:text-gray-500 mb-3"></i>
                        <p class="text-sm text-gray-500 dark:text-gray-400">Nenhuma foto disponível ainda.</p>
                    </div>
                @endif
            </div>
        </div>
    </div>
</x-app-layout>
