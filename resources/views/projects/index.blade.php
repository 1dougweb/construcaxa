<x-app-layout>
    <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 py-6">
        <!-- Cabeçalho com título e botão de criar -->
        <div class="flex items-center justify-between mb-6">
            <div>
                <h1 class="text-2xl font-bold text-gray-900 dark:text-gray-100">Obras</h1>
            </div>
            @if(auth()->user()->can('create projects') || auth()->user()->hasAnyRole(['manager','admin']))
            
            @endif
        </div>

        <!-- Cards de Estatísticas -->
        <div class="grid grid-cols-1 md:grid-cols-3 lg:grid-cols-6 gap-4 mb-6">
            <div class="bg-white dark:bg-gray-800 rounded-lg shadow p-4 border border-gray-200 dark:border-gray-700">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-sm font-medium text-gray-600 dark:text-gray-400">Total</p>
                        <p class="text-2xl font-bold text-gray-900 dark:text-gray-100 mt-1">{{ $stats['total'] }}</p>
                    </div>
                        <i class="fi fi-rr-folder text-2xl text-indigo-600 dark:text-indigo-400"></i>
                </div>
            </div>
            
            <div class="bg-white dark:bg-gray-800 rounded-lg shadow p-4 border border-gray-200 dark:border-gray-700">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-sm font-medium text-gray-600 dark:text-gray-400">Em Andamento</p>
                        <p class="text-2xl font-bold text-blue-600 dark:text-blue-400 mt-1">{{ $stats['in_progress'] }}</p>
                    </div>
                    <i class="fi fi-rr-play text-2xl text-blue-600 dark:text-blue-400"></i>
                </div>
            </div>
            
            <div class="bg-white dark:bg-gray-800 rounded-lg shadow p-4 border border-gray-200 dark:border-gray-700">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-sm font-medium text-gray-600 dark:text-gray-400">Concluídas</p>
                        <p class="text-2xl font-bold text-green-600 dark:text-green-400 mt-1">{{ $stats['completed'] }}</p>
                    </div>
                    <i class="fi fi-rr-check-circle text-2xl text-green-600 dark:text-green-400"></i>
                </div>
            </div>
            
            <div class="bg-white dark:bg-gray-800 rounded-lg shadow p-4 border border-gray-200 dark:border-gray-700">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-sm font-medium text-gray-600 dark:text-gray-400">Planejadas</p>
                        <p class="text-2xl font-bold text-gray-600 dark:text-gray-400 mt-1">{{ $stats['planned'] }}</p>
                    </div>
                    <i class="fi fi-rr-calendar text-2xl text-gray-600 dark:text-gray-400"></i>
                </div>
            </div>
            
            <div class="bg-white dark:bg-gray-800 rounded-lg shadow p-4 border border-gray-200 dark:border-gray-700">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-sm font-medium text-gray-600 dark:text-gray-400">Pausadas</p>
                        <p class="text-2xl font-bold text-yellow-600 dark:text-yellow-400 mt-1">{{ $stats['paused'] }}</p>
                    </div>
                    <i class="fi fi-rr-pause text-2xl text-yellow-600 dark:text-yellow-400"></i>
                </div>
            </div>
            
            <div class="bg-white dark:bg-gray-800 rounded-lg shadow p-4 border border-gray-200 dark:border-gray-700">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-sm font-medium text-gray-600 dark:text-gray-400">Canceladas</p>
                        <p class="text-2xl font-bold text-red-600 dark:text-red-400 mt-1">{{ $stats['cancelled'] }}</p>
                    </div>
                    <i class="fi fi-rr-cross-circle text-2xl text-red-600 dark:text-red-400"></i>
                </div>
            </div>
        </div>

        <!-- Filtros e Busca -->
        <div class="bg-white dark:bg-gray-800 rounded-lg shadow p-4 border border-gray-200 dark:border-gray-700 mb-6">
            <form method="GET" action="{{ route('projects.index') }}" class="flex flex-col md:flex-row gap-4">
                <div class="flex-1">
                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Buscar</label>
                    <input 
                        type="text" 
                        name="search" 
                        value="{{ request('search') }}"
                        placeholder="Buscar por nome ou código..."
                        class="w-full border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-gray-300 rounded-md shadow-sm focus:border-indigo-500 focus:ring-indigo-500"
                    >
                </div>
                <div class="md:w-48">
                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Status</label>
                    <select 
                        name="status" 
                        class="w-full border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-gray-300 rounded-md shadow-sm focus:border-indigo-500 focus:ring-indigo-500"
                        onchange="this.form.submit()"
                    >
                        <option value="">Todos</option>
                        <option value="planned" {{ request('status') === 'planned' ? 'selected' : '' }}>Planejada</option>
                        <option value="in_progress" {{ request('status') === 'in_progress' ? 'selected' : '' }}>Em Andamento</option>
                        <option value="paused" {{ request('status') === 'paused' ? 'selected' : '' }}>Pausada</option>
                        <option value="completed" {{ request('status') === 'completed' ? 'selected' : '' }}>Concluída</option>
                        <option value="cancelled" {{ request('status') === 'cancelled' ? 'selected' : '' }}>Cancelada</option>
                    </select>
                </div>
                <div class="flex items-end">
                    <button 
                        type="submit" 
                        class="px-4 py-2 bg-indigo-600 dark:bg-indigo-700 text-white rounded-md hover:bg-indigo-700 dark:hover:bg-indigo-600 transition-colors"
                    >
                        <i class="bi bi-search mr-2"></i>Filtrar
                    </button>
                    @if(request('search') || request('status'))
                    <a 
                        href="{{ route('projects.index') }}" 
                        class="ml-2 px-4 py-2 bg-gray-200 dark:bg-gray-700 text-gray-700 dark:text-gray-300 rounded-md hover:bg-gray-300 dark:hover:bg-gray-600 transition-colors"
                    >
                        Limpar
                    </a>
                    @endif
                </div>
            </form>
        </div>

        <!-- Lista de Obras -->
        <div class="bg-white dark:bg-gray-800 rounded-lg shadow border border-gray-200 dark:border-gray-700 overflow-hidden">
            @forelse($projects as $project)
                @php
                    $totalTasks = $project->tasks()->count();
                    $doneTasks = $project->tasks()->where('status','done')->count();
                    $computedProgress = $totalTasks > 0 ? (int) round(($doneTasks / max(1,$totalTasks)) * 100) : (int) $project->progress_percentage;
                    
                    $statusColors = [
                        'planned' => 'bg-gray-100 dark:bg-gray-700 text-gray-800 dark:text-gray-200',
                        'in_progress' => 'bg-blue-100 dark:bg-blue-900/30 text-blue-800 dark:text-blue-300',
                        'paused' => 'bg-yellow-100 dark:bg-yellow-900/30 text-yellow-800 dark:text-yellow-300',
                        'completed' => 'bg-green-100 dark:bg-green-900/30 text-green-800 dark:text-green-300',
                        'cancelled' => 'bg-red-100 dark:bg-red-900/30 text-red-800 dark:text-red-300',
                    ];
                    $statusLabels = [
                        'planned' => 'Planejada',
                        'in_progress' => 'Em Andamento',
                        'paused' => 'Pausada',
                        'completed' => 'Concluída',
                        'cancelled' => 'Cancelada',
                    ];
                @endphp
                <a href="{{ route('projects.show', $project) }}" class="block border-b border-gray-200 dark:border-gray-700 hover:bg-gray-50 dark:hover:bg-gray-700/50 transition-colors">
                    <div class="p-6">
                        <div class="flex items-start justify-between">
                            <div class="flex-1 min-w-0">
                                <div class="flex items-center gap-3 mb-2">
                                    <h3 class="text-lg font-semibold text-gray-900 dark:text-gray-100 truncate">
                                        {{ $project->name }}
                                    </h3>
                                    <span class="px-2 py-1 text-xs font-medium rounded-full {{ $statusColors[$project->status] ?? $statusColors['planned'] }}">
                                        {{ $statusLabels[$project->status] ?? ucfirst($project->status) }}
                                    </span>
                                </div>
                                <p class="text-sm text-gray-600 dark:text-gray-400 mb-4">
                                    <span class="font-medium">Código:</span> {{ $project->code }}
                                    @if($project->address)
                                        <span class="ml-4"><i class="bi bi-geo-alt mr-1"></i>{{ Str::limit($project->address, 50) }}</span>
                                    @endif
                                </p>
                                
                                <!-- Barra de Progresso -->
                                <div class="mb-3">
                                    <div class="flex items-center justify-between text-sm mb-1">
                                        <span class="text-gray-600 dark:text-gray-400">Progresso</span>
                                        <span class="font-medium text-gray-900 dark:text-gray-100">{{ $computedProgress }}%</span>
                                    </div>
                                    <div class="w-full bg-gray-200 dark:bg-gray-700 rounded-full h-3 overflow-hidden">
                                        <div 
                                            class="h-full bg-gradient-to-r from-indigo-500 to-indigo-600 dark:from-indigo-600 dark:to-indigo-700 rounded-full transition-all duration-300"
                                            style="width: {{ $computedProgress }}%"
                                        ></div>
                                    </div>
                                </div>
                                
                                <!-- Informações Adicionais -->
                                <div class="flex items-center gap-4 text-xs text-gray-500 dark:text-gray-400">
                                    @if($totalTasks > 0)
                                    <span class="flex items-center gap-1">
                                        <i class="bi bi-check2-square"></i>
                                        {{ $doneTasks }}/{{ $totalTasks }} tarefas
                                    </span>
                                    @endif
                                    @if($project->start_date)
                                    <span class="flex items-center gap-1">
                                        <i class="bi bi-calendar-event"></i>
                                        Início: {{ $project->start_date->format('d/m/Y') }}
                                    </span>
                                    @endif
                                    @if($project->end_date_estimated)
                                    <span class="flex items-center gap-1">
                                        <i class="bi bi-calendar-check"></i>
                                        Previsão: {{ $project->end_date_estimated->format('d/m/Y') }}
                                    </span>
                                    @endif
                                </div>
                            </div>
                            <div class="ml-4 flex-shrink-0">
                                <i class="bi bi-chevron-right text-gray-400 dark:text-gray-500 text-xl"></i>
                            </div>
                        </div>
                    </div>
                </a>
            @empty
                <div class="p-12 text-center">
                    <i class="bi bi-folder-x text-6xl text-gray-400 dark:text-gray-500 mb-4"></i>
                    <p class="text-lg font-medium text-gray-900 dark:text-gray-100 mb-2">Nenhuma obra encontrada</p>
                    <p class="text-sm text-gray-500 dark:text-gray-400">
                        @if(request('search') || request('status'))
                            Tente ajustar os filtros de busca.
                        @else
                            Comece criando sua primeira obra.
                        @endif
                    </p>
                </div>
            @endforelse
        </div>

        <!-- Paginação -->
        @if($projects->hasPages())
        <div class="mt-6">
            {{ $projects->links() }}
        </div>
        @endif
    </div>
</x-app-layout>
