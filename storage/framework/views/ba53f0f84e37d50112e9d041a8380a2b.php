<?php if (isset($component)) { $__componentOriginal4619374cef299e94fd7263111d0abc69 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal4619374cef299e94fd7263111d0abc69 = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.app-layout','data' => []] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('app-layout'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes([]); ?>
    <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 py-6">
        <!-- Cabeçalho com título e botão de criar -->
        <div class="flex items-center justify-between mb-6">
            <div>
                <h1 class="text-2xl font-bold text-gray-900 dark:text-gray-100">Obras</h1>
            </div>
            <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if(auth()->user()->can('create projects') || auth()->user()->hasAnyRole(['manager','admin'])): ?>
            
            <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
        </div>

        <!-- Cards de Estatísticas -->
        <div class="grid grid-cols-1 md:grid-cols-3 lg:grid-cols-6 gap-4 mb-6">
            <div class="bg-white dark:bg-gray-800 rounded-lg shadow p-4 border border-gray-200 dark:border-gray-700">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-sm font-medium text-gray-600 dark:text-gray-400">Total</p>
                        <p class="text-2xl font-bold text-gray-900 dark:text-gray-100 mt-1"><?php echo e($stats['total']); ?></p>
                    </div>
                        <i class="fi fi-rr-folder text-2xl text-indigo-600 dark:text-indigo-400"></i>
                </div>
            </div>
            
            <div class="bg-white dark:bg-gray-800 rounded-lg shadow p-4 border border-gray-200 dark:border-gray-700">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-sm font-medium text-gray-600 dark:text-gray-400">Em Andamento</p>
                        <p class="text-2xl font-bold text-blue-600 dark:text-blue-400 mt-1"><?php echo e($stats['in_progress']); ?></p>
                    </div>
                    <i class="fi fi-rr-play text-2xl text-blue-600 dark:text-blue-400"></i>
                </div>
            </div>
            
            <div class="bg-white dark:bg-gray-800 rounded-lg shadow p-4 border border-gray-200 dark:border-gray-700">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-sm font-medium text-gray-600 dark:text-gray-400">Concluídas</p>
                        <p class="text-2xl font-bold text-green-600 dark:text-green-400 mt-1"><?php echo e($stats['completed']); ?></p>
                    </div>
                    <i class="fi fi-rr-check-circle text-2xl text-green-600 dark:text-green-400"></i>
                </div>
            </div>
            
            <div class="bg-white dark:bg-gray-800 rounded-lg shadow p-4 border border-gray-200 dark:border-gray-700">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-sm font-medium text-gray-600 dark:text-gray-400">Planejadas</p>
                        <p class="text-2xl font-bold text-gray-600 dark:text-gray-400 mt-1"><?php echo e($stats['planned']); ?></p>
                    </div>
                    <i class="fi fi-rr-calendar text-2xl text-gray-600 dark:text-gray-400"></i>
                </div>
            </div>
            
            <div class="bg-white dark:bg-gray-800 rounded-lg shadow p-4 border border-gray-200 dark:border-gray-700">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-sm font-medium text-gray-600 dark:text-gray-400">Pausadas</p>
                        <p class="text-2xl font-bold text-yellow-600 dark:text-yellow-400 mt-1"><?php echo e($stats['paused']); ?></p>
                    </div>
                    <i class="fi fi-rr-pause text-2xl text-yellow-600 dark:text-yellow-400"></i>
                </div>
            </div>
            
            <div class="bg-white dark:bg-gray-800 rounded-lg shadow p-4 border border-gray-200 dark:border-gray-700">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-sm font-medium text-gray-600 dark:text-gray-400">Canceladas</p>
                        <p class="text-2xl font-bold text-red-600 dark:text-red-400 mt-1"><?php echo e($stats['cancelled']); ?></p>
                    </div>
                    <i class="fi fi-rr-cross-circle text-2xl text-red-600 dark:text-red-400"></i>
                </div>
            </div>
        </div>

        <!-- Filtros e Busca -->
        <div class="bg-white dark:bg-gray-800 rounded-lg shadow p-4 border border-gray-200 dark:border-gray-700 mb-6">
            <form method="GET" action="<?php echo e(route('projects.index')); ?>" class="flex flex-col md:flex-row gap-4">
                <div class="flex-1">
                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Buscar</label>
                    <input 
                        type="text" 
                        name="search" 
                        value="<?php echo e(request('search')); ?>"
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
                        <option value="planned" <?php echo e(request('status') === 'planned' ? 'selected' : ''); ?>>Planejada</option>
                        <option value="in_progress" <?php echo e(request('status') === 'in_progress' ? 'selected' : ''); ?>>Em Andamento</option>
                        <option value="paused" <?php echo e(request('status') === 'paused' ? 'selected' : ''); ?>>Pausada</option>
                        <option value="completed" <?php echo e(request('status') === 'completed' ? 'selected' : ''); ?>>Concluída</option>
                        <option value="cancelled" <?php echo e(request('status') === 'cancelled' ? 'selected' : ''); ?>>Cancelada</option>
                    </select>
                </div>
                <div class="flex items-end">
                    <button 
                        type="submit" 
                        class="px-4 py-2 bg-indigo-600 dark:bg-indigo-700 text-white rounded-md hover:bg-indigo-700 dark:hover:bg-indigo-600 transition-colors"
                    >
                        <i class="bi bi-search mr-2"></i>Filtrar
                    </button>
                    <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if(request('search') || request('status')): ?>
                    <a 
                        href="<?php echo e(route('projects.index')); ?>" 
                        class="ml-2 px-4 py-2 bg-gray-200 dark:bg-gray-700 text-gray-700 dark:text-gray-300 rounded-md hover:bg-gray-300 dark:hover:bg-gray-600 transition-colors"
                    >
                        Limpar
                    </a>
                    <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                </div>
            </form>
        </div>

        <!-- Lista de Obras -->
        <div class="bg-white dark:bg-gray-800 rounded-lg shadow border border-gray-200 dark:border-gray-700 overflow-hidden">
            <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php $__empty_1 = true; $__currentLoopData = $projects; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $project): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                <?php
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
                ?>
                <a href="<?php echo e(route('projects.show', $project)); ?>" class="block border-b border-gray-200 dark:border-gray-700 hover:bg-gray-50 dark:hover:bg-gray-700/50 transition-colors">
                    <div class="p-6">
                        <div class="flex items-start justify-between">
                            <div class="flex-1 min-w-0">
                                <div class="flex items-center gap-3 mb-2">
                                    <h3 class="text-lg font-semibold text-gray-900 dark:text-gray-100 truncate">
                                        <?php echo e($project->name); ?>

                                    </h3>
                                    <span class="px-2 py-1 text-xs font-medium rounded-full <?php echo e($statusColors[$project->status] ?? $statusColors['planned']); ?>">
                                        <?php echo e($statusLabels[$project->status] ?? ucfirst($project->status)); ?>

                                    </span>
                                </div>
                                <p class="text-sm text-gray-600 dark:text-gray-400 mb-4">
                                    <span class="font-medium">Código:</span> <?php echo e($project->code); ?>

                                    <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($project->address): ?>
                                        <span class="ml-4"><i class="bi bi-geo-alt mr-1"></i><?php echo e(Str::limit($project->address, 50)); ?></span>
                                    <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                                </p>
                                
                                <!-- Barra de Progresso -->
                                <div class="mb-3">
                                    <div class="flex items-center justify-between text-sm mb-1">
                                        <span class="text-gray-600 dark:text-gray-400">Progresso</span>
                                        <span class="font-medium text-gray-900 dark:text-gray-100"><?php echo e($computedProgress); ?>%</span>
                                    </div>
                                    <div class="w-full bg-gray-200 dark:bg-gray-700 rounded-full h-3 overflow-hidden">
                                        <div 
                                            class="h-full bg-gradient-to-r from-indigo-500 to-indigo-600 dark:from-indigo-600 dark:to-indigo-700 rounded-full transition-all duration-300"
                                            style="width: <?php echo e($computedProgress); ?>%"
                                        ></div>
                                    </div>
                                </div>
                                
                                <!-- Informações Adicionais -->
                                <div class="flex items-center gap-4 text-xs text-gray-500 dark:text-gray-400">
                                    <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($totalTasks > 0): ?>
                                    <span class="flex items-center gap-1">
                                        <i class="bi bi-check2-square"></i>
                                        <?php echo e($doneTasks); ?>/<?php echo e($totalTasks); ?> tarefas
                                    </span>
                                    <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                                    <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($project->start_date): ?>
                                    <span class="flex items-center gap-1">
                                        <i class="bi bi-calendar-event"></i>
                                        Início: <?php echo e($project->start_date->format('d/m/Y')); ?>

                                    </span>
                                    <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                                    <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($project->end_date_estimated): ?>
                                    <span class="flex items-center gap-1">
                                        <i class="bi bi-calendar-check"></i>
                                        Previsão: <?php echo e($project->end_date_estimated->format('d/m/Y')); ?>

                                    </span>
                                    <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                                </div>
                            </div>
                            <div class="ml-4 flex-shrink-0">
                                <i class="bi bi-chevron-right text-gray-400 dark:text-gray-500 text-xl"></i>
                            </div>
                        </div>
                    </div>
                </a>
            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                <div class="p-12 text-center">
                    <i class="bi bi-folder-x text-6xl text-gray-400 dark:text-gray-500 mb-4"></i>
                    <p class="text-lg font-medium text-gray-900 dark:text-gray-100 mb-2">Nenhuma obra encontrada</p>
                    <p class="text-sm text-gray-500 dark:text-gray-400">
                        <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if(request('search') || request('status')): ?>
                            Tente ajustar os filtros de busca.
                        <?php else: ?>
                            Comece criando sua primeira obra.
                        <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                    </p>
                </div>
            <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
        </div>

        <!-- Paginação -->
        <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($projects->hasPages()): ?>
        <div class="mt-6">
            <?php echo e($projects->links()); ?>

        </div>
        <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
    </div>
 <?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginal4619374cef299e94fd7263111d0abc69)): ?>
<?php $attributes = $__attributesOriginal4619374cef299e94fd7263111d0abc69; ?>
<?php unset($__attributesOriginal4619374cef299e94fd7263111d0abc69); ?>
<?php endif; ?>
<?php if (isset($__componentOriginal4619374cef299e94fd7263111d0abc69)): ?>
<?php $component = $__componentOriginal4619374cef299e94fd7263111d0abc69; ?>
<?php unset($__componentOriginal4619374cef299e94fd7263111d0abc69); ?>
<?php endif; ?>
<?php /**PATH C:\Users\Douglas\Documents\Projetos\stock-master\resources\views/projects/index.blade.php ENDPATH**/ ?>