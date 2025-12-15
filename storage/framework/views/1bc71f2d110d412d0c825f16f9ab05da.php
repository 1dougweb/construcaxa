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
<div class="p-4">
    <div class="flex items-end justify-between">
        <div>
            <h1 class="text-2xl font-semibold text-gray-900 dark:text-gray-100"><?php echo e($project->name); ?> 
            <p class="text-lg text-gray-400 dark:text-gray-500">(<?php echo e($project->code); ?>)</p></h1>
            <p class="text-sm text-gray-600 dark:text-gray-400 mt-1">Status: <?php echo e($project->status); ?> · Progresso: <?php echo e($project->progress_percentage); ?>%</p>
        </div>
        <div class="flex space-x-2">
            <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('manage finances')): ?>
            <a href="<?php echo e(route('projects.financial-balance', $project)); ?>" class="px-3 py-2 bg-green-600 dark:bg-green-700 text-white rounded-md hover:bg-green-700 dark:hover:bg-green-600">
                <i class="bi bi-cash-coin mr-1"></i> Balanço Financeiro
            </a>
            <?php endif; ?>
            <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('edit projects')): ?>
            <a href="<?php echo e(route('projects.edit', $project)); ?>" class="px-3 py-2 bg-indigo-600 dark:bg-indigo-700 text-white rounded-md hover:bg-indigo-700 dark:hover:bg-indigo-600">Editar</a>
            <?php endif; ?>
        </div>
    </div>

    <div class="mt-6 grid grid-cols-1 lg:grid-cols-3 gap-6">
        <div class="lg:col-span-2 space-y-6">
            <div class="bg-white dark:bg-gray-800 rounded-md shadow p-4 border border-gray-200 dark:border-gray-700">
                <h2 class="font-medium text-gray-900 dark:text-gray-100 mb-3">Resumo</h2>
                <div class="grid grid-cols-2 gap-4 text-sm text-gray-700 dark:text-gray-300">
                    <div>
                        <span class="text-gray-500 dark:text-gray-400">Endereço:</span>
                        <?php echo e($project->address ?: '-'); ?>

                    </div>
                    <div>
                        <span class="text-gray-500 dark:text-gray-400">Início:</span>
                        <?php echo e(optional($project->start_date)->format('d/m/Y') ?: '-'); ?>

                    </div>
                    <div>
                        <span class="text-gray-500 dark:text-gray-400">Previsão:</span>
                        <?php echo e(optional($project->end_date_estimated)->format('d/m/Y') ?: '-'); ?>

                    </div>
                    <div>
                        <span class="text-gray-500 dark:text-gray-400">Equipe:</span>
                        <?php echo e($project->employees->count()); ?> membros
                    </div>
                    <div class="col-span-2 border-t border-dashed border-gray-200 dark:border-gray-700 pt-3 mt-1">
                        <div class="grid grid-cols-1 sm:grid-cols-3 gap-4">
                            <div>
                                <p class="text-xs text-gray-500 dark:text-gray-400">Valor total da obra (orçamento aprovado)</p>
                                <p class="text-base font-semibold text-gray-900 dark:text-gray-100">
                                    R$ <?php echo e(number_format($totalBudgetedAmount, 2, ',', '.')); ?>

                                </p>
                            </div>
                            <div>
                                <p class="text-xs text-gray-500 dark:text-gray-400">Total recebido do cliente</p>
                                <p class="text-base font-semibold text-green-600 dark:text-green-400">
                                    R$ <?php echo e(number_format($totalPaidAmount, 2, ',', '.')); ?>

                                </p>
                            </div>
                            <div>
                                <p class="text-xs text-gray-500 dark:text-gray-400">Saldo a receber</p>
                                <p class="text-base font-semibold <?php echo e($remainingAmount > 0 ? 'text-amber-600 dark:text-amber-400' : 'text-green-600 dark:text-green-400'); ?>">
                                    R$ <?php echo e(number_format($remainingAmount, 2, ',', '.')); ?>

                                </p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="bg-white dark:bg-gray-800 rounded-md shadow p-6 border border-gray-200 dark:border-gray-700">
                <div class="flex items-center justify-between mb-6">
                    <div>
                        <h2 class="text-lg font-semibold text-gray-900 dark:text-gray-100">Timeline</h2>
                        <p class="text-sm text-gray-500 dark:text-gray-400 mt-1">Acompanhe todas as atualizações do projeto</p>
                    </div>
                </div>

                <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('post project-updates')): ?>
                <div class="mb-6 bg-gray-50 dark:bg-gray-700/50 rounded-lg p-4 border border-gray-200 dark:border-gray-600">
                    <form action="<?php echo e(route('projects.updates.store', $project)); ?>" method="POST" class="space-y-4">
                        <?php echo csrf_field(); ?>
                        <div class="grid grid-cols-1 md:grid-cols-4 gap-4">
                            <div>
                                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Tipo de Atualização</label>
                                <select name="type" class="w-full border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-gray-300 rounded-md text-sm shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                                    <option value="note">Nota</option>
                                    <option value="issue">Problema</option>
                                    <option value="material_missing">Material faltante</option>
                                    <option value="progress">Progresso</option>
                                </select>
                            </div>
                            <div class="md:col-span-2">
                                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Mensagem</label>
                                <input 
                                    name="message" 
                                    class="w-full border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-gray-300 rounded-md text-sm shadow-sm focus:border-indigo-500 focus:ring-indigo-500" 
                                    placeholder="Descreva a atualização..." 
                                    required
                                >
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Δ Progresso (%)</label>
                                <input 
                                    name="progress_delta" 
                                    type="number" 
                                    min="0" 
                                    max="100"
                                    class="w-full border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-gray-300 rounded-md text-sm shadow-sm focus:border-indigo-500 focus:ring-indigo-500" 
                                    placeholder="ex: 5"
                                >
                            </div>
                        </div>
                        <div class="flex justify-end">
                            <button class="px-4 py-2 bg-indigo-600 dark:bg-indigo-700 text-white rounded-md text-sm hover:bg-indigo-700 dark:hover:bg-indigo-600 transition-colors flex items-center gap-2">
                                <i class="bi bi-plus-circle"></i>
                                Adicionar Atualização
                            </button>
                        </div>
                    </form>
                </div>
                <?php endif; ?>

                <?php
                    $updates = $project->updates()->latest()->take(20)->get();
                    $typeConfigs = [
                        'note' => [
                            'icon' => 'bi-file-text',
                            'color' => 'text-blue-600 dark:text-blue-400',
                            'bg' => 'bg-blue-100 dark:bg-blue-900/30',
                            'border' => 'border-blue-300 dark:border-blue-600',
                            'label' => 'Nota',
                        ],
                        'issue' => [
                            'icon' => 'bi-exclamation-triangle',
                            'color' => 'text-red-600 dark:text-red-400',
                            'bg' => 'bg-red-100 dark:bg-red-900/30',
                            'border' => 'border-red-300 dark:border-red-600',
                            'label' => 'Problema',
                        ],
                        'material_missing' => [
                            'icon' => 'bi-box-seam',
                            'color' => 'text-yellow-600 dark:text-yellow-400',
                            'bg' => 'bg-yellow-100 dark:bg-yellow-900/30',
                            'border' => 'border-yellow-300 dark:border-yellow-600',
                            'label' => 'Material Faltante',
                        ],
                        'progress' => [
                            'icon' => 'bi-arrow-up-circle',
                            'color' => 'text-green-600 dark:text-green-400',
                            'bg' => 'bg-green-100 dark:bg-green-900/30',
                            'border' => 'border-green-300 dark:border-green-600',
                            'label' => 'Progresso',
                        ],
                    ];
                ?>

                <div class="space-y-4">
                    <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php $__empty_1 = true; $__currentLoopData = $updates; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $update): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                        <?php
                            $config = $typeConfigs[$update->type] ?? $typeConfigs['note'];
                        ?>
                        <div class="relative pl-8 pb-4 border-l-2 <?php echo e($config['border']); ?>">
                            <div class="absolute -left-3 top-0">
                                <div class="w-6 h-6 rounded-full <?php echo e($config['bg']); ?> flex items-center justify-center border-2 border-white dark:border-gray-800">
                                    <i class="<?php echo e($config['icon']); ?> <?php echo e($config['color']); ?> text-sm"></i>
                                </div>
                            </div>
                            <div class="bg-gray-50 dark:bg-gray-700/30 rounded-lg p-4 border border-gray-200 dark:border-gray-600">
                                <div class="flex items-start justify-between gap-3 mb-2">
                                    <div class="flex items-center gap-2">
                                        <span class="font-semibold text-gray-900 dark:text-gray-100"><?php echo e($update->user->name); ?></span>
                                        <span class="inline-flex items-center px-2 py-1 rounded-full text-xs font-medium <?php echo e($config['bg']); ?> <?php echo e($config['color']); ?>">
                                            <?php echo e($config['label']); ?>

                                        </span>
                                    </div>
                                    <span class="text-xs text-gray-500 dark:text-gray-400 whitespace-nowrap">
                                        <?php echo e($update->created_at->format('d/m/Y')); ?>

                                        <span class="text-gray-400 dark:text-gray-500">às</span>
                                        <?php echo e($update->created_at->format('H:i')); ?>

                                    </span>
                                </div>
                                <p class="text-sm text-gray-800 dark:text-gray-200 leading-relaxed"><?php echo e($update->message); ?></p>
                                <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($update->progress_delta): ?>
                                    <div class="mt-3 pt-3 border-t border-gray-200 dark:border-gray-600">
                                        <span class="text-xs font-medium text-gray-600 dark:text-gray-400">
                                            <i class="bi bi-arrow-up-circle mr-1"></i>
                                            Progresso alterado: 
                                            <span class="text-green-600 dark:text-green-400 font-semibold">
                                                +<?php echo e($update->progress_delta); ?>%
                                            </span>
                                        </span>
                                    </div>
                                <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                            </div>
                        </div>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                        <div class="text-center py-12 bg-gray-50 dark:bg-gray-700/30 rounded-lg border border-gray-200 dark:border-gray-600">
                            <i class="bi bi-clock-history text-4xl text-gray-400 dark:text-gray-500 mb-3"></i>
                            <p class="text-sm font-medium text-gray-900 dark:text-gray-100 mb-1">Nenhuma atualização ainda</p>
                            <p class="text-xs text-gray-500 dark:text-gray-400">
                                <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('post project-updates')): ?>
                                    Adicione a primeira atualização usando o formulário acima
                                <?php else: ?>
                                    Aguardando atualizações do projeto
                                <?php endif; ?>
                            </p>
                        </div>
                    <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                </div>
            </div>

            <div class="bg-white dark:bg-gray-800 rounded-md shadow p-6 border border-gray-200 dark:border-gray-700">
                <div class="flex items-center justify-between mb-6">
                    <div>
                        <h2 class="text-lg font-semibold text-gray-900 dark:text-gray-100">Tarefas</h2>
                        <p class="text-sm text-gray-500 dark:text-gray-400 mt-1">Gerencie as tarefas deste projeto</p>
                    </div>
                </div>

                <?php
                    $tasks = $project->tasks()
                        ->orderByRaw("CASE status WHEN 'todo' THEN 0 WHEN 'in_progress' THEN 1 ELSE 2 END")
                        ->orderBy('sort_order')
                        ->orderByDesc('id')
                        ->get();
                    
                    $todoCount = $tasks->where('status', 'todo')->count();
                    $inProgressCount = $tasks->where('status', 'in_progress')->count();
                    $doneCount = $tasks->where('status', 'done')->count();
                    $totalCount = $tasks->count();
                ?>

                <!-- Contadores e Filtros -->
                <div class="mb-6">
                    <div class="flex flex-wrap items-center gap-4 mb-4">
                        <div class="flex items-center gap-2">
                            <span class="px-3 py-1 text-xs font-medium rounded-full bg-gray-100 dark:bg-gray-700 text-gray-800 dark:text-gray-200">
                                Total: <?php echo e($totalCount); ?>

                            </span>
                            <span class="px-3 py-1 text-xs font-medium rounded-full bg-blue-100 dark:bg-blue-900/30 text-blue-800 dark:text-blue-300">
                                A fazer: <?php echo e($todoCount); ?>

                            </span>
                            <span class="px-3 py-1 text-xs font-medium rounded-full bg-yellow-100 dark:bg-yellow-900/30 text-yellow-800 dark:text-yellow-300">
                                Em progresso: <?php echo e($inProgressCount); ?>

                            </span>
                            <span class="px-3 py-1 text-xs font-medium rounded-full bg-green-100 dark:bg-green-900/30 text-green-800 dark:text-green-300">
                                Concluídas: <?php echo e($doneCount); ?>

                            </span>
                        </div>
                    </div>
                    
                    <!-- Formulário de adicionar tarefa -->
                    <form action="<?php echo e(route('projects.tasks.store', $project)); ?>" method="POST" class="bg-gray-50 dark:bg-gray-700/50 rounded-lg p-4 border border-gray-200 dark:border-gray-600">
                        <?php echo csrf_field(); ?>
                        <div class="grid grid-cols-1 md:grid-cols-3 gap-3">
                            <div class="md:col-span-2">
                                <label class="block text-xs font-medium text-gray-700 dark:text-gray-300 mb-1">Nova Tarefa</label>
                                <input 
                                    name="title" 
                                    class="w-full border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-gray-300 rounded-md text-sm shadow-sm focus:border-indigo-500 focus:ring-indigo-500" 
                                    placeholder="Digite o título da tarefa..." 
                                    required
                                >
                            </div>
                            <div>
                                <label class="block text-xs font-medium text-gray-700 dark:text-gray-300 mb-1">Data de Vencimento</label>
                                <input 
                                    type="date" 
                                    name="due_date" 
                                    class="w-full border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-gray-300 rounded-md text-sm shadow-sm focus:border-indigo-500 focus:ring-indigo-500"
                                >
                            </div>
                        </div>
                        <div class="mt-3 flex justify-end">
                            <button class="px-4 py-2 bg-indigo-600 dark:bg-indigo-700 text-white rounded-md text-sm hover:bg-indigo-700 dark:hover:bg-indigo-600 transition-colors flex items-center gap-2">
                                <i class="bi bi-plus-circle"></i>
                                Adicionar Tarefa
                            </button>
                        </div>
                    </form>
                </div>

                <!-- Lista de Tarefas -->
                <div class="space-y-3" x-data="{ filter: 'all' }">
                    <div class="flex items-center gap-2 mb-4">
                        <button 
                            @click="filter = 'all'"
                            :class="filter === 'all' ? 'bg-indigo-100 dark:bg-indigo-900/30 text-indigo-700 dark:text-indigo-300' : 'bg-gray-100 dark:bg-gray-700 text-gray-700 dark:text-gray-300'"
                            class="px-3 py-1 text-xs font-medium rounded-md transition-colors"
                        >
                            Todas
                        </button>
                        <button 
                            @click="filter = 'todo'"
                            :class="filter === 'todo' ? 'bg-blue-100 dark:bg-blue-900/30 text-blue-700 dark:text-blue-300' : 'bg-gray-100 dark:bg-gray-700 text-gray-700 dark:text-gray-300'"
                            class="px-3 py-1 text-xs font-medium rounded-md transition-colors"
                        >
                            A fazer
                        </button>
                        <button 
                            @click="filter = 'in_progress'"
                            :class="filter === 'in_progress' ? 'bg-yellow-100 dark:bg-yellow-900/30 text-yellow-700 dark:text-yellow-300' : 'bg-gray-100 dark:bg-gray-700 text-gray-700 dark:text-gray-300'"
                            class="px-3 py-1 text-xs font-medium rounded-md transition-colors"
                        >
                            Em progresso
                        </button>
                        <button 
                            @click="filter = 'done'"
                            :class="filter === 'done' ? 'bg-green-100 dark:bg-green-900/30 text-green-700 dark:text-green-300' : 'bg-gray-100 dark:bg-gray-700 text-gray-700 dark:text-gray-300'"
                            class="px-3 py-1 text-xs font-medium rounded-md transition-colors"
                        >
                            Concluídas
                        </button>
                    </div>

                    <div class="space-y-2">
                        <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php $__empty_1 = true; $__currentLoopData = $tasks; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $task): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                            <div 
                                x-show="filter === 'all' || filter === '<?php echo e($task->status); ?>'"
                                class="bg-gray-50 dark:bg-gray-700/30 rounded-lg p-4 border border-gray-200 dark:border-gray-600 hover:shadow-md transition-shadow"
                                x-data="{ open: false }"
                            >
                                <div class="flex items-start gap-4">
                                    <form action="<?php echo e(route('projects.tasks.status', [$project, $task])); ?>" method="POST" class="mt-1">
                                        <?php echo csrf_field(); ?>
                                        <?php echo method_field('PATCH'); ?>
                                        <input type="hidden" name="status" value="<?php echo e($task->status === 'done' ? 'todo' : 'done'); ?>">
                                        <input 
                                            type="checkbox" 
                                            <?php echo e($task->status === 'done' ? 'checked' : ''); ?> 
                                            onchange="this.form.submit()" 
                                            class="h-5 w-5 text-indigo-600 rounded border-gray-300 dark:border-gray-600 dark:bg-gray-700 focus:ring-indigo-500 cursor-pointer"
                                        >
                                    </form>
                                    <div class="flex-1 min-w-0">
                                        <div class="flex items-start justify-between gap-3">
                                            <div class="flex-1">
                                                <h3 class="font-medium <?php echo e($task->status === 'done' ? 'line-through text-gray-400 dark:text-gray-500' : 'text-gray-900 dark:text-gray-100'); ?> mb-1">
                                                    <?php echo e($task->title); ?>

                                                </h3>
                                                <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($task->description): ?>
                                                    <p class="text-sm text-gray-600 dark:text-gray-400 mt-1 line-clamp-2">
                                                        <?php echo e($task->description); ?>

                                                    </p>
                                                <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                                                <div class="flex items-center gap-3 mt-2">
                                                    <span class="inline-flex items-center px-2 py-1 text-xs font-medium rounded-full 
                                                        <?php echo e($task->status === 'todo' ? 'bg-blue-100 dark:bg-blue-900/30 text-blue-800 dark:text-blue-300' : ''); ?>

                                                        <?php echo e($task->status === 'in_progress' ? 'bg-yellow-100 dark:bg-yellow-900/30 text-yellow-800 dark:text-yellow-300' : ''); ?>

                                                        <?php echo e($task->status === 'done' ? 'bg-green-100 dark:bg-green-900/30 text-green-800 dark:text-green-300' : ''); ?>

                                                    ">
                                                        <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($task->status === 'in_progress'): ?>
                                                            Em progresso
                                                        <?php elseif($task->status === 'done'): ?>
                                                            Concluída
                                                        <?php else: ?>
                                                            A fazer
                                                        <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                                                    </span>
                                                    <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($task->due_date): ?>
                                                        <?php
                                                            $days = now()->startOfDay()->diffInDays($task->due_date, false);
                                                            $badgeClass = $days < 0
                                                                ? 'bg-red-100 dark:bg-red-900/30 text-red-700 dark:text-red-300'
                                                                : ($days <= 2
                                                                    ? 'bg-yellow-100 dark:bg-yellow-900/30 text-yellow-700 dark:text-yellow-300'
                                                                    : 'bg-green-100 dark:bg-green-900/30 text-green-700 dark:text-green-300');
                                                            $urgencyText = $days < 0 ? 'Atrasada' : ($days === 0 ? 'Vence hoje' : ($days <= 2 ? 'Vence em breve' : 'No prazo'));
                                                        ?>
                                                        <span class="inline-flex items-center px-2 py-1 text-xs font-medium rounded-full <?php echo e($badgeClass); ?>">
                                                            <i class="bi bi-calendar-event mr-1"></i>
                                                            <?php echo e($urgencyText); ?>: <?php echo e($task->due_date->format('d/m/Y')); ?>

                                                        </span>
                                                    <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                                                </div>
                                            </div>
                                            <div class="flex items-center gap-2">
                                                <button 
                                                    type="button" 
                                                    class="text-indigo-600 dark:text-indigo-400 hover:text-indigo-800 dark:hover:text-indigo-300 text-sm font-medium" 
                                                    @click="open = true"
                                                >
                                                    <i class="bi bi-pencil"></i>
                                                </button>
                                                <form action="<?php echo e(route('projects.tasks.delete', [$project, $task])); ?>" method="POST" onsubmit="return confirm('Tem certeza que deseja remover esta tarefa?')">
                                                    <?php echo csrf_field(); ?>
                                                    <?php echo method_field('DELETE'); ?>
                                                    <button type="submit" class="text-red-600 dark:text-red-400 hover:text-red-800 dark:hover:text-red-300 text-sm font-medium">
                                                        <i class="bi bi-trash"></i>
                                                    </button>
                                                </form>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <!-- Modal de edição -->
                                <div x-show="open" class="fixed inset-0 z-50 flex items-center justify-center bg-black/50" x-cloak @click.away="open = false">
                                    <div class="bg-white dark:bg-gray-800 rounded-lg shadow-xl p-6 w-full max-w-lg border border-gray-200 dark:border-gray-700 mx-4" @click.stop>
                                        <div class="flex items-center justify-between mb-4">
                                            <h3 class="text-lg font-semibold text-gray-900 dark:text-gray-100">Editar Tarefa</h3>
                                            <button class="text-gray-500 dark:text-gray-400 hover:text-gray-700 dark:hover:text-gray-200" @click="open = false">
                                                <i class="bi bi-x-lg"></i>
                                            </button>
                                        </div>
                                        <form action="<?php echo e(route('projects.tasks.update', [$project, $task])); ?>" method="POST" class="space-y-4">
                                            <?php echo csrf_field(); ?>
                                            <?php echo method_field('PATCH'); ?>
                                            <div>
                                                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Título</label>
                                                <input name="title" class="w-full border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-gray-300 rounded-md text-sm shadow-sm focus:border-indigo-500 focus:ring-indigo-500" value="<?php echo e($task->title); ?>" required>
                                            </div>
                                            <div>
                                                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Descrição</label>
                                                <textarea name="description" class="w-full border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-gray-300 rounded-md text-sm shadow-sm focus:border-indigo-500 focus:ring-indigo-500" rows="4"><?php echo e($task->description); ?></textarea>
                                            </div>
                                            <div>
                                                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Data de Vencimento</label>
                                                <input type="date" name="due_date" class="w-full border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-gray-300 rounded-md text-sm shadow-sm focus:border-indigo-500 focus:ring-indigo-500" value="<?php echo e(optional($task->due_date)->format('Y-m-d')); ?>">
                                            </div>
                                            <div class="flex items-center justify-end gap-3 pt-4 border-t border-gray-200 dark:border-gray-700">
                                                <button type="button" class="px-4 py-2 text-sm font-medium text-gray-700 dark:text-gray-300 bg-white dark:bg-gray-800 border border-gray-300 dark:border-gray-600 rounded-md hover:bg-gray-50 dark:hover:bg-gray-700" @click="open = false">Cancelar</button>
                                                <button type="submit" class="px-4 py-2 bg-indigo-600 dark:bg-indigo-700 text-white rounded-md text-sm hover:bg-indigo-700 dark:hover:bg-indigo-600 transition-colors">Salvar</button>
                                            </div>
                                        </form>
                                    </div>
                                </div>
                            </div>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                            <div class="text-center py-12 bg-gray-50 dark:bg-gray-700/30 rounded-lg border border-gray-200 dark:border-gray-600">
                                <i class="bi bi-check2-square text-4xl text-gray-400 dark:text-gray-500 mb-3"></i>
                                <p class="text-sm font-medium text-gray-900 dark:text-gray-100 mb-1">Nenhuma tarefa adicionada ainda</p>
                                <p class="text-xs text-gray-500 dark:text-gray-400">Adicione uma nova tarefa usando o formulário acima</p>
                            </div>
                        <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                    </div>
                </div>
            </div>

            <div class="bg-white dark:bg-gray-800 rounded-md shadow p-4 border border-gray-200 dark:border-gray-700">
                <div class="flex items-center justify-between mb-4">
                    <h2 class="font-medium text-gray-900 dark:text-gray-100">Fotos do Projeto</h2>
                    <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('post project-updates')): ?>
                    <button onclick="openPhotoUploadModal('<?php echo e(route('projects.photos.upload', $project)); ?>')" class="px-3 py-2 bg-indigo-600 dark:bg-indigo-700 text-white rounded-md text-sm hover:bg-indigo-700 dark:hover:bg-indigo-600">
                        Adicionar Foto
                    </button>
                    <?php endif; ?>
                </div>
                
                <?php
                    $photos = $project->photos()->with('user')->latest()->get();
                ?>
                
                <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($photos->count() > 0): ?>
                    <div class="grid grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-4" id="photo-gallery">
                        <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php $__currentLoopData = $photos; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $photo): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                            <div class="group relative cursor-pointer photo-item" 
                                 data-photo-url="<?php echo e(asset('storage/' . $photo->path)); ?>"
                                 data-photo-caption="<?php echo e($photo->caption ?? 'Sem legenda'); ?>"
                                 data-photo-date="<?php echo e($photo->created_at->format('d/m/Y H:i')); ?>"
                                 data-photo-user="<?php echo e($photo->user->name ?? 'Desconhecido'); ?>"
                                 data-photo-index="<?php echo e($loop->index); ?>">
                                <div class="relative overflow-hidden rounded-lg aspect-square bg-gray-100 dark:bg-gray-700">
                                    <img src="<?php echo e(asset('storage/' . $photo->path)); ?>" 
                                         alt="<?php echo e($photo->caption ?? 'Foto do projeto'); ?>" 
                                         class="w-full h-full object-cover transition-transform duration-300 group-hover:scale-110"
                                         loading="lazy">
                                    <div class="absolute inset-0 bg-black/0 group-hover:bg-black/20 transition-colors duration-300"></div>
                                    <div class="absolute bottom-0 left-0 right-0 p-2 bg-gradient-to-t from-black/60 to-transparent text-white text-xs opacity-0 group-hover:opacity-100 transition-opacity duration-300">
                                        <div class="font-medium truncate"><?php echo e($photo->caption ?? 'Sem legenda'); ?></div>
                                        <div class="text-xs text-gray-200 mt-1"><?php echo e($photo->created_at->format('d/m/Y H:i')); ?></div>
                                        <div class="text-xs text-gray-300"><?php echo e($photo->user->name ?? 'Desconhecido'); ?></div>
                                    </div>
                                </div>
                            </div>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                    </div>
                <?php else: ?>
                    <div class="text-center py-12 text-sm text-gray-500 dark:text-gray-400">
                        <svg class="mx-auto h-12 w-12 text-gray-400 dark:text-gray-500 mb-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                        </svg>
                        <p>Nenhuma foto enviada ainda.</p>
                        <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('post project-updates')): ?>
                        <p class="mt-2">Clique em "Adicionar Foto" para começar.</p>
                        <?php endif; ?>
                    </div>
                <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
            </div>

            <div class="bg-white dark:bg-gray-800 rounded-md shadow p-4 border border-gray-200 dark:border-gray-700">
                <div class="flex items-center justify-between mb-4">
                    <h2 class="font-medium text-gray-900 dark:text-gray-100">Arquivos</h2>
                    <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('post project-updates')): ?>
                    <button onclick="openFileUploadModal('<?php echo e(route('projects.files.upload', $project)); ?>')" class="px-3 py-2 bg-indigo-600 dark:bg-indigo-700 text-white rounded-md text-sm hover:bg-indigo-700 dark:hover:bg-indigo-600">
                        Adicionar Arquivo
                    </button>
                    <?php endif; ?>
                </div>

                <!-- Lista de Arquivos -->
                <div class="space-y-2">
                    <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php $__empty_1 = true; $__currentLoopData = $project->files; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $file): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                        <div class="flex items-center justify-between p-3 border border-gray-200 dark:border-gray-700 rounded-md hover:bg-gray-50 dark:hover:bg-gray-700/50">
                            <div class="flex items-center gap-3 flex-1 min-w-0">
                                <span class="text-2xl flex-shrink-0"><?php echo e($file->file_icon); ?></span>
                                <div class="flex-1 min-w-0">
                                    <div class="text-sm font-medium text-gray-900 dark:text-gray-100 truncate" title="<?php echo e($file->original_name); ?>">
                                        <?php echo e($file->original_name); ?>

                                    </div>
                                    <div class="text-xs text-gray-500 dark:text-gray-400 flex items-center gap-3 mt-1">
                                        <span><?php echo e($file->formatted_size); ?></span>
                                        <span>•</span>
                                        <span><?php echo e($file->created_at->format('d/m/Y H:i')); ?></span>
                                        <span>•</span>
                                        <span><?php echo e($file->user->name); ?></span>
                                        <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($file->description): ?>
                                            <span>•</span>
                                            <span class="italic"><?php echo e(Str::limit($file->description, 30)); ?></span>
                                        <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                                    </div>
                                </div>
                            </div>
                            <div class="flex items-center gap-2 flex-shrink-0 ml-4">
                                <a href="<?php echo e(route('projects.files.download', [$project, $file])); ?>" 
                                   class="px-3 py-1 text-xs font-medium text-indigo-600 dark:text-indigo-400 hover:text-indigo-800 dark:hover:text-indigo-300 hover:bg-indigo-50 dark:hover:bg-indigo-900/30 rounded">
                                    Download
                                </a>
                                <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($file->user_id === auth()->id() || auth()->user()->can('edit projects') || auth()->user()->hasAnyRole(['manager', 'admin'])): ?>
                                    <form action="<?php echo e(route('projects.files.delete', [$project, $file])); ?>" 
                                          method="POST" 
                                          onsubmit="return confirm('Tem certeza que deseja remover este arquivo?')"
                                          class="inline">
                                        <?php echo csrf_field(); ?>
                                        <?php echo method_field('DELETE'); ?>
                                        <button type="submit" 
                                                class="px-3 py-1 text-xs font-medium text-red-600 dark:text-red-400 hover:text-red-800 dark:hover:text-red-300 hover:bg-red-50 dark:hover:bg-red-900/30 rounded">
                                            Remover
                                        </button>
                                    </form>
                                <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                            </div>
                        </div>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                        <div class="text-center py-8 text-sm text-gray-500 dark:text-gray-400">
                            <p>Nenhum arquivo enviado ainda.</p>
                            <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('post project-updates')): ?>
                            <p class="mt-2">Clique em "Adicionar Arquivo" para começar.</p>
                            <?php endif; ?>
                        </div>
                    <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                </div>
            </div>
        </div>

        <div class="space-y-6">
            <!-- Budget Information -->
            <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($project->budgets->count() > 0): ?>
            <div class="bg-white dark:bg-gray-800 rounded-md shadow p-4 border border-gray-200 dark:border-gray-700">
                <div class="flex items-center justify-between mb-3">
                    <h2 class="font-medium text-gray-900 dark:text-gray-100">Orçamentos</h2>
                    <a href="<?php echo e(route('budgets.index', ['project_id' => $project->id])); ?>" class="text-xs text-indigo-600 dark:text-indigo-400 hover:text-indigo-800 dark:hover:text-indigo-300">
                        Ver todos
                    </a>
                </div>
                <div class="space-y-3">
                    <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php $__currentLoopData = $project->budgets->take(3); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $budget): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                        <div class="border border-gray-200 dark:border-gray-700 rounded-md p-3 hover:bg-gray-50 dark:hover:bg-gray-700/50 transition-colors">
                            <div class="flex items-start justify-between mb-2">
                                <div class="flex-1">
                                    <div class="font-medium text-sm text-gray-900 dark:text-gray-100">
                                        Orçamento #<?php echo e($budget->id); ?> - v<?php echo e($budget->version); ?>

                                    </div>
                                    <div class="text-xs text-gray-500 dark:text-gray-400 mt-1">
                                        Criado em <?php echo e($budget->created_at->format('d/m/Y')); ?>

                                    </div>
                                </div>
                                <span class="px-2 py-1 text-xs rounded-full <?php echo e($budget->status_color); ?>">
                                    <?php echo e($budget->status_label); ?>

                                </span>
                            </div>
                            <div class="text-sm text-gray-700 dark:text-gray-300 mb-2">
                                <span class="font-medium">Total:</span> R$ <?php echo e(number_format($budget->total, 2, ',', '.')); ?>

                            </div>
                            <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($budget->approved_at): ?>
                                <div class="text-xs text-gray-600 dark:text-gray-400 mb-1">
                                    <span class="font-medium">Aprovado em:</span> <?php echo e($budget->approved_at->format('d/m/Y H:i')); ?>

                                </div>
                                <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($budget->approver): ?>
                                    <div class="text-xs text-gray-600 dark:text-gray-400">
                                        <span class="font-medium">Por:</span> <?php echo e($budget->approver->name); ?>

                                    </div>
                                <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                            <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                            <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($project->os_number && $budget->status === 'approved'): ?>
                                <div class="mt-2 pt-2 border-t border-gray-200 dark:border-gray-700">
                                    <div class="text-xs font-medium text-indigo-600 dark:text-indigo-400">
                                        OS: <?php echo e($project->os_number); ?>

                                    </div>
                                </div>
                            <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                            <div class="mt-2 flex space-x-2">
                                <a href="<?php echo e(route('budgets.edit', $budget)); ?>" class="text-xs text-indigo-600 dark:text-indigo-400 hover:text-indigo-800 dark:hover:text-indigo-300">
                                    Editar
                                </a>
                                <a href="<?php echo e(route('budgets.pdf', $budget)); ?>" class="text-xs text-gray-600 dark:text-gray-400 hover:text-gray-800 dark:hover:text-gray-300">
                                    PDF
                                </a>
                            </div>
                        </div>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                </div>
            </div>
            <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>

            <div class="bg-white dark:bg-gray-800 rounded-md shadow p-4 border border-gray-200 dark:border-gray-700 space-y-4">
                <div class="flex items-center justify-between">
                    <h2 class="font-medium text-gray-900 dark:text-gray-100">Equipe</h2>
                </div>
                <div class="text-xs text-gray-500 dark:text-gray-400">
                    Adicione novos membros de equipe à obra (ex: mão de obra especializada, responsáveis por materiais, etc.).
                </div>

                <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('edit projects')): ?>
                <form action="<?php echo e(route('projects.members.attach', $project)); ?>" method="POST" class="bg-gray-50 dark:bg-gray-700/40 rounded-lg p-3 border border-gray-200 dark:border-gray-600 mb-3 space-y-3">
                    <?php echo csrf_field(); ?>
                    <div class="grid grid-cols-1 gap-3">
                        <div>
                            <label class="block text-xs font-medium text-gray-700 dark:text-gray-300 mb-1">Selecionar membro</label>
                            <select name="employee_id" class="w-full border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-gray-300 rounded-md text-sm" required>
                                <option value="">Selecione um colaborador</option>
                                <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php $__currentLoopData = $availableEmployees; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $employee): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                    <option value="<?php echo e($employee->id); ?>">
                                        <?php echo e($employee->user->name ?? 'Funcionário #'.$employee->id); ?> 
                                        <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($employee->position): ?> - <?php echo e($employee->position); ?> <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                                    </option>
                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                            </select>
                        </div>
                        <div>
                            <label class="block text-xs font-medium text-gray-700 dark:text-gray-300 mb-1">Função na obra (opcional)</label>
                            <input 
                                type="text" 
                                name="role_on_project" 
                                class="w-full border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-gray-300 rounded-md text-sm"
                                placeholder="Ex: Eletricista, Mestre de Obras..."
                            >
                        </div>
                        <div class="space-y-2" x-data="{ index: 0 }">
                            <div class="flex items-center justify-between">
                                <span class="text-xs font-semibold text-gray-700 dark:text-gray-300 uppercase tracking-wide">Serviços / Mão de Obra</span>
                                <button 
                                    type="button" 
                                    class="text-[11px] text-indigo-600 dark:text-indigo-400 hover:text-indigo-800 dark:hover:text-indigo-300"
                                    onclick="addServiceRow()"
                                >
                                    + Adicionar serviço
                                </button>
                            </div>
                            <div id="service-items" class="space-y-3">
                                <div class="service-item space-y-2">
                                    <div>
                                        <label class="block text-[11px] font-medium text-gray-700 dark:text-gray-300 mb-1">Serviço</label>
                                        <select name="items[0][labor_type_id]" class="w-full border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-gray-300 rounded-md text-xs" required>
                                            <option value="">Selecione</option>
                                            <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php $__currentLoopData = $laborTypes; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $laborType): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                                <option value="<?php echo e($laborType->id); ?>"><?php echo e($laborType->name); ?></option>
                                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                                        </select>
                                    </div>
                                    <div>
                                        <label class="block text-[11px] font-medium text-gray-700 dark:text-gray-300 mb-1">Valor unitário</label>
                                        <input 
                                            type="number" 
                                            name="items[0][unit_price]" 
                                            step="0.01"
                                            min="0"
                                            class="w-full border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-gray-300 rounded-md text-xs"
                                            placeholder="Ex: 150,00"
                                        >
                                    </div>
                                    <div class="flex justify-end">
                                        <button 
                                            type="button"
                                            class="px-2 py-1 text-[11px] text-red-500 hover:text-red-700"
                                            onclick="removeServiceRow(this)"
                                        >
                                            Remover
                                        </button>
                                    </div>
                                </div>
                            </div>
                            <template id="service-item-template">
                                <div class="service-item space-y-2">
                                    <div>
                                        <label class="block text-[11px] font-medium text-gray-700 dark:text-gray-300 mb-1">Serviço</label>
                                        <select class="service-labor-type w-full border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-gray-300 rounded-md text-xs" required>
                                            <option value="">Selecione</option>
                                            <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php $__currentLoopData = $laborTypes; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $laborType): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                                <option value="<?php echo e($laborType->id); ?>"><?php echo e($laborType->name); ?></option>
                                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                                        </select>
                                    </div>
                                    <div>
                                        <label class="block text-[11px] font-medium text-gray-700 dark:text-gray-300 mb-1">Valor unitário</label>
                                        <input 
                                            type="number" 
                                            class="service-unit-price w-full border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-gray-300 rounded-md text-xs"
                                            step="0.01"
                                            min="0"
                                            placeholder="Ex: 150,00"
                                        >
                                    </div>
                                    <div class="flex justify-end">
                                        <button 
                                            type="button"
                                            class="px-2 py-1 text-[11px] text-red-500 hover:text-red-700"
                                            onclick="removeServiceRow(this)"
                                        >
                                            Remover
                                        </button>
                                    </div>
                                </div>
                            </template>
                        </div>
                        <div>
                            <label class="block text-xs font-medium text-gray-700 dark:text-gray-300 mb-1">Observações (opcional)</label>
                            <textarea 
                                name="observations" 
                                rows="2"
                                class="w-full border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-gray-300 rounded-md text-sm"
                                placeholder="Detalhes do combinado com o colaborador para esta obra"
                            ></textarea>
                        </div>
                        <div class="flex justify-end">
                            <button class="px-3 py-2 bg-indigo-600 dark:bg-indigo-700 text-white rounded-md text-xs font-medium hover:bg-indigo-700 dark:hover:bg-indigo-600">
                                Enviar proposta para o colaborador
                            </button>
                        </div>
                    </div>
                </form>
                <?php endif; ?>

                <ul class="divide-y divide-gray-200 dark:divide-gray-700">
                    <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php $__empty_1 = true; $__currentLoopData = $project->employees; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $member): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                        <li class="py-2 text-sm flex items-center justify-between">
                            <div>
                                <div class="font-medium text-gray-900 dark:text-gray-100">
                                    <?php echo e($member->user->name ?? 'Funcionário #'.$member->id); ?>

                                </div>
                                <div class="text-xs text-gray-500 dark:text-gray-400">
                                    <?php echo e($member->pivot->role_on_project ?: ($member->position ?: '-')); ?>

                                </div>
                            </div>
                            <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('edit projects')): ?>
                            <form action="<?php echo e(route('projects.members.detach', [$project, $member])); ?>" method="POST" onsubmit="return confirm('Remover este membro da obra?');">
                                <?php echo csrf_field(); ?>
                                <?php echo method_field('DELETE'); ?>
                                <button type="submit" class="text-xs text-red-600 dark:text-red-400 hover:text-red-800 dark:hover:text-red-300">
                                    Remover
                                </button>
                            </form>
                            <?php endif; ?>
                        </li>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                        <li class="py-2 text-sm text-gray-500 dark:text-gray-400">Nenhum membro da equipe atribuído.</li>
                    <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                </ul>
            </div>

            <div class="bg-white dark:bg-gray-800 rounded-md shadow p-4 border border-gray-200 dark:border-gray-700 space-y-4">
                <div class="flex items-center justify-between">
                    <h2 class="font-medium text-gray-900 dark:text-gray-100">Financeiro da Obra</h2>
                </div>
                <p class="text-xs text-gray-500 dark:text-gray-400">
                    Registre pagamentos recebidos do cliente e visualize um resumo financeiro desta obra.
                </p>

                <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('manage finances')): ?>
                <form action="<?php echo e(route('projects.payments.store', $project)); ?>" method="POST" class="bg-gray-50 dark:bg-gray-700/40 rounded-lg p-3 border border-gray-200 dark:border-gray-600 space-y-3">
                    <?php echo csrf_field(); ?>
                    <div>
                        <label class="block text-xs font-medium text-gray-700 dark:text-gray-300 mb-1">Valor recebido</label>
                        <input 
                            type="number" 
                            name="amount" 
                            step="0.01"
                            min="0"
                            class="w-full border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-gray-300 rounded-md text-sm"
                            placeholder="Ex: 2000,00"
                            required
                        >
                    </div>
                    <div class="grid grid-cols-1 gap-3">
                        <div>
                            <label class="block text-xs font-medium text-gray-700 dark:text-gray-300 mb-1">Data do recebimento</label>
                            <input 
                                type="date" 
                                name="received_date" 
                                value="<?php echo e(now()->format('Y-m-d')); ?>"
                                class="w-full border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-gray-300 rounded-md text-sm"
                                required
                            >
                        </div>
                        <div>
                            <label class="block text-xs font-medium text-gray-700 dark:text-gray-300 mb-1">Descrição (opcional)</label>
                            <input 
                                type="text" 
                                name="description" 
                                class="w-full border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-gray-300 rounded-md text-sm"
                                placeholder="Ex: Parcela 1/5, pagamento à vista, etc."
                            >
                        </div>
                    </div>
                    <div class="flex justify-end">
                        <button class="px-3 py-2 bg-green-600 dark:bg-green-700 text-white rounded-md text-xs font-medium hover:bg-green-700 dark:hover:bg-green-600 flex items-center gap-1">
                            <i class="bi bi-cash-coin"></i>
                            Registrar pagamento
                        </button>
                    </div>
                </form>
                <?php endif; ?>

                <div class="border border-dashed border-gray-200 dark:border-gray-700 rounded-lg p-3">
                    <h3 class="text-xs font-semibold text-gray-700 dark:text-gray-300 mb-2 uppercase tracking-wide">Resumo rápido</h3>
                    <dl class="grid grid-cols-2 gap-3 text-xs text-gray-600 dark:text-gray-300">
                        <div>
                            <dt class="text-gray-500 dark:text-gray-400">Valor total da obra</dt>
                            <dd class="font-semibold text-gray-900 dark:text-gray-100">
                                R$ <?php echo e(number_format($totalBudgetedAmount, 2, ',', '.')); ?>

                            </dd>
                        </div>
                        <div>
                            <dt class="text-gray-500 dark:text-gray-400">Total recebido</dt>
                            <dd class="font-semibold text-green-600 dark:text-green-400">
                                R$ <?php echo e(number_format($totalPaidAmount, 2, ',', '.')); ?>

                            </dd>
                        </div>
                        <div>
                            <dt class="text-gray-500 dark:text-gray-400">Saldo em aberto</dt>
                            <dd class="font-semibold <?php echo e($remainingAmount > 0 ? 'text-amber-600 dark:text-amber-400' : 'text-green-600 dark:text-green-400'); ?>">
                                R$ <?php echo e(number_format($remainingAmount, 2, ',', '.')); ?>

                            </dd>
                        </div>
                    </dl>
                </div>

                <div class="border border-gray-200 dark:border-gray-700 rounded-lg p-3">
                    <h3 class="text-xs font-semibold text-gray-700 dark:text-gray-300 mb-2 uppercase tracking-wide">Principais materiais lançados</h3>
                    <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($materials->count() === 0): ?>
                        <p class="text-xs text-gray-500 dark:text-gray-400">Nenhum material lançado diretamente nesta obra ainda.</p>
                    <?php else: ?>
                        <ul class="divide-y divide-gray-200 dark:divide-gray-700 text-xs">
                            <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php $__currentLoopData = $materials; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $material): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                <li class="py-2 flex items-center justify-between">
                                    <div class="flex-1 min-w-0">
                                        <div class="font-medium text-gray-900 dark:text-gray-100 truncate">
                                            <?php echo e($material->product->name ?? 'Material'); ?>

                                        </div>
                                        <div class="text-[11px] text-gray-500 dark:text-gray-400">
                                            <?php echo e($material->description); ?> · 
                                            <?php echo e(number_format($material->quantity_used, 2, ',', '.')); ?> x 
                                            R$ <?php echo e(number_format($material->unit_cost, 2, ',', '.')); ?>

                                        </div>
                                    </div>
                                    <div class="ml-3 text-right">
                                        <div class="text-[11px] text-gray-500 dark:text-gray-400">
                                            <?php echo e(optional($material->usage_date)->format('d/m/Y')); ?>

                                        </div>
                                        <div class="text-xs font-semibold text-gray-900 dark:text-gray-100">
                                            R$ <?php echo e(number_format($material->total_cost, 2, ',', '.')); ?>

                                        </div>
                                    </div>
                                </li>
                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                        </ul>
                    <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                </div>
            </div>
        </div>
    </div>


<?php $__env->startPush('styles'); ?>
<style>
#lightbox {
    opacity: 0;
    transition: opacity 0.3s ease-in-out;
}
#lightbox.show {
    opacity: 1;
}
#lightbox.hidden {
    display: none !important;
}
#lightbox-backdrop {
    background-color: rgba(0, 0, 0, 0.9);
    backdrop-filter: blur(8px);
    -webkit-backdrop-filter: blur(8px);
}
#lightbox-image {
    transition: opacity 0.3s ease-in-out, transform 0.3s ease-in-out;
    max-width: 90vw;
    max-height: 85vh;
}
.prev-btn:disabled,
.next-btn:disabled {
    pointer-events: none;
    opacity: 0.3;
}
#lightbox-close-btn {
    z-index: 60;
}
#lightbox-close-btn:hover {
    background-color: rgba(255, 255, 255, 0.2);
}
</style>
<?php $__env->stopPush(); ?>

<?php $__env->startPush('scripts'); ?>
<script>
let serviceItemIndex = 1;

function addServiceRow() {
    const container = document.getElementById('service-items');
    const template = document.getElementById('service-item-template');
    if (!container || !template) return;

    const clone = template.content.cloneNode(true);
    const index = serviceItemIndex++;

    const laborSelect = clone.querySelector('.service-labor-type');
    const unitPriceInput = clone.querySelector('.service-unit-price');

    if (laborSelect) laborSelect.name = `items[${index}][labor_type_id]`;
    if (unitPriceInput) unitPriceInput.name = `items[${index}][unit_price]`;

    container.appendChild(clone);
}

function removeServiceRow(button) {
    const item = button.closest('.service-item');
    const container = document.getElementById('service-items');
    if (!item || !container) return;
    // Não permitir remover todos: manter pelo menos 1
    if (container.querySelectorAll('.service-item').length <= 1) return;
    item.remove();
}

function openFileUploadModal(uploadUrl) {
    const modalContent = document.getElementById('file-upload-modal-content');
    const csrfToken = document.querySelector('meta[name="csrf-token"]')?.getAttribute('content') || '<?php echo e(csrf_token()); ?>';
    modalContent.innerHTML = `
        <form action="${uploadUrl}" method="POST" enctype="multipart/form-data" id="fileUploadForm">
            <input type="hidden" name="_token" value="${csrfToken}">
            <div class="space-y-4">
                <div>
                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Selecionar Arquivos</label>
                    <input type="file" 
                           name="files[]" 
                           multiple 
                           accept=".jpg,.jpeg,.png,.gif,.webp,.pdf,.doc,.docx,.xls,.xlsx,.ppt,.pptx,.txt,.csv,.zip,.rar"
                           class="block w-full text-sm text-gray-500 dark:text-gray-400 file:mr-4 file:py-2 file:px-4 file:rounded-md file:border-0 file:text-sm file:font-semibold file:bg-indigo-50 dark:file:bg-indigo-900/30 file:text-indigo-700 dark:file:text-indigo-300 hover:file:bg-indigo-100 dark:hover:file:bg-indigo-900/50 border border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-gray-300 rounded-md p-2"
                           required>
                    <p class="mt-1 text-xs text-gray-500 dark:text-gray-400">Tipos permitidos: Imagens, PDF, Documentos, Planilhas, Arquivos de texto, ZIP, RAR (Máx: 10MB por arquivo)</p>
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Descrição (opcional)</label>
                    <textarea name="description" 
                              rows="3" 
                              class="block w-full border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-gray-300 rounded-md shadow-sm focus:border-indigo-500 dark:focus:border-indigo-400 focus:ring-indigo-500 dark:focus:ring-indigo-400 text-sm"
                              placeholder="Descrição dos arquivos..."></textarea>
                </div>
                <div class="flex items-center justify-end gap-3 pt-4">
                    <button type="button" 
                            onclick="closeFileUploadModal()"
                            class="px-4 py-2 text-sm font-medium text-gray-700 dark:text-gray-300 bg-white dark:bg-gray-800 border border-gray-300 dark:border-gray-600 rounded-md hover:bg-gray-50 dark:hover:bg-gray-700">
                        Cancelar
                    </button>
                    <button type="submit" 
                            class="px-4 py-2 text-sm font-medium text-white bg-indigo-600 dark:bg-indigo-700 rounded-md hover:bg-indigo-700 dark:hover:bg-indigo-600">
                        Enviar Arquivos
                    </button>
                </div>
            </div>
        </form>
    `;
    window.dispatchEvent(new CustomEvent('open-file-upload-modal'));
}

function closeFileUploadModal() {
    window.dispatchEvent(new CustomEvent('close-file-upload-modal'));
}

function openPhotoUploadModal(uploadUrl) {
    const modalContent = document.getElementById('file-upload-modal-content');
    const csrfToken = document.querySelector('meta[name="csrf-token"]')?.getAttribute('content') || '<?php echo e(csrf_token()); ?>';
    modalContent.innerHTML = `
        <form action="${uploadUrl}" method="POST" enctype="multipart/form-data" id="photoUploadForm">
            <input type="hidden" name="_token" value="${csrfToken}">
            <div class="space-y-4">
                <div>
                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Selecionar Foto</label>
                    <input type="file" 
                           name="photo" 
                           accept="image/*"
                           class="block w-full text-sm text-gray-500 dark:text-gray-400 file:mr-4 file:py-2 file:px-4 file:rounded-md file:border-0 file:text-sm file:font-semibold file:bg-indigo-50 dark:file:bg-indigo-900/30 file:text-indigo-700 dark:file:text-indigo-300 hover:file:bg-indigo-100 dark:hover:file:bg-indigo-900/50 border border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-gray-300 rounded-md p-2"
                           required>
                    <p class="mt-1 text-xs text-gray-500 dark:text-gray-400">Formatos: JPG, PNG, GIF, WebP (Máx: 5MB)</p>
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Legenda (opcional)</label>
                    <textarea name="caption" 
                              rows="3" 
                              class="block w-full border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-gray-300 rounded-md shadow-sm focus:border-indigo-500 dark:focus:border-indigo-400 focus:ring-indigo-500 dark:focus:ring-indigo-400 text-sm"
                              placeholder="Descreva a foto..."></textarea>
                </div>
                <div class="flex items-center justify-end gap-3 pt-4">
                    <button type="button" 
                            onclick="closeFileUploadModal()"
                            class="px-4 py-2 text-sm font-medium text-gray-700 dark:text-gray-300 bg-white dark:bg-gray-800 border border-gray-300 dark:border-gray-600 rounded-md hover:bg-gray-50 dark:hover:bg-gray-700">
                        Cancelar
                    </button>
                    <button type="submit" 
                            class="px-4 py-2 text-sm font-medium text-white bg-indigo-600 dark:bg-indigo-700 rounded-md hover:bg-indigo-700 dark:hover:bg-indigo-600">
                        Enviar Foto
                    </button>
                </div>
            </div>
        </form>
    `;
    window.dispatchEvent(new CustomEvent('open-file-upload-modal'));
}

// Lightbox para galeria de fotos
(function() {
    'use strict';
    
    let currentIndex = 0;
    let photos = [];
    let lightbox = null;
    let lightboxContent = null;
    let lightboxImage = null;
    let lightboxInfo = null;
    let isOpen = false;
    
    // Inicializar lightbox
    function initLightbox() {
        // Criar estrutura do lightbox
        lightbox = document.createElement('div');
        lightbox.id = 'lightbox';
        lightbox.className = 'fixed inset-0 z-50 hidden';
        lightbox.innerHTML = `
            <div id="lightbox-backdrop" class="absolute inset-0" onclick="PhotoLightbox.close()"></div>
            <button id="lightbox-close-btn" class="absolute top-4 right-4 text-white hover:text-gray-300 p-2 rounded-full transition-all duration-200" onclick="PhotoLightbox.close()" aria-label="Fechar">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                </svg>
            </button>
            <button class="absolute left-4 top-1/2 -translate-y-1/2 z-50 text-white hover:text-gray-300 p-2 rounded-full hover:bg-white/10 transition-all duration-200 prev-btn" onclick="PhotoLightbox.prev()" aria-label="Anterior">
                <svg class="w-7 h-7" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"></path>
                </svg>
            </button>
            <button class="absolute right-4 top-1/2 -translate-y-1/2 z-50 text-white hover:text-gray-300 p-2 rounded-full hover:bg-white/10 transition-all duration-200 next-btn" onclick="PhotoLightbox.next()" aria-label="Próxima">
                <svg class="w-7 h-7" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path>
                </svg>
            </button>
            <div class="absolute inset-0 flex items-center justify-center p-4 pointer-events-none">
                <div class="max-w-4xl w-full flex flex-col items-center pointer-events-auto">
                    <div class="relative w-full flex items-center justify-center mb-3">
                        <img id="lightbox-image" class="object-contain rounded-lg shadow-2xl" alt="" loading="eager" style="max-width: 85vw; max-height: 75vh;">
                        <div class="absolute inset-0 flex items-center justify-center">
                            <div class="animate-spin rounded-full h-10 w-10 border-t-2 border-b-2 border-white loading-spinner hidden"></div>
                        </div>
                    </div>
                    <div id="lightbox-info" class="text-white text-center px-4">
                        <div class="text-lg font-semibold mb-1 caption"></div>
                        <div class="text-sm text-gray-300 info"></div>
                        <div class="text-xs text-gray-400 mt-1 counter"></div>
                    </div>
                </div>
            </div>
        `;
        document.body.appendChild(lightbox);
        
        lightboxImage = document.getElementById('lightbox-image');
        lightboxInfo = document.getElementById('lightbox-info');
        
        // Event listeners para teclado
        document.addEventListener('keydown', handleKeydown);
    }
    
    // Coletar fotos da galeria
    function collectPhotos() {
        const gallery = document.getElementById('photo-gallery');
        if (!gallery) return [];
        
        const items = gallery.querySelectorAll('.photo-item');
        photos = Array.from(items).map(item => ({
            url: item.getAttribute('data-photo-url'),
            caption: item.getAttribute('data-photo-caption') || 'Sem legenda',
            date: item.getAttribute('data-photo-date'),
            user: item.getAttribute('data-photo-user'),
            index: parseInt(item.getAttribute('data-photo-index'))
        }));
        
        return photos;
    }
    
    // Atualizar conteúdo do lightbox
    function updateLightbox() {
        if (!lightbox || photos.length === 0) return;
        
        const photo = photos[currentIndex];
        if (!photo) return;
        
        // Mostrar loading
        const spinner = lightbox.querySelector('.loading-spinner');
        if (spinner) spinner.classList.remove('hidden');
        if (lightboxImage) {
            lightboxImage.style.opacity = '0';
            lightboxImage.style.transform = 'scale(0.95)';
        }
        
        // Carregar imagem
        const img = new Image();
        img.onload = function() {
            if (lightboxImage) {
                lightboxImage.src = photo.url;
                lightboxImage.alt = photo.caption;
                setTimeout(() => {
                    lightboxImage.style.opacity = '1';
                    lightboxImage.style.transform = 'scale(1)';
                }, 50);
            }
            if (spinner) spinner.classList.add('hidden');
        };
        img.onerror = function() {
            if (spinner) spinner.classList.add('hidden');
            if (lightboxImage) {
                lightboxImage.src = photo.url;
                lightboxImage.style.opacity = '1';
                lightboxImage.style.transform = 'scale(1)';
            }
        };
        img.src = photo.url;
        
        // Atualizar informações
        if (lightboxInfo) {
            const captionEl = lightboxInfo.querySelector('.caption');
            const infoEl = lightboxInfo.querySelector('.info');
            const counterEl = lightboxInfo.querySelector('.counter');
            
            if (captionEl) captionEl.textContent = photo.caption;
            if (infoEl) infoEl.textContent = `${photo.date} · ${photo.user}`;
            if (counterEl) counterEl.textContent = `${currentIndex + 1} de ${photos.length}`;
        }
        
        // Atualizar botões de navegação
        const prevBtn = lightbox.querySelector('.prev-btn');
        const nextBtn = lightbox.querySelector('.next-btn');
        
        if (prevBtn) {
            if (currentIndex === 0) {
                prevBtn.disabled = true;
            } else {
                prevBtn.disabled = false;
            }
        }
        
        if (nextBtn) {
            if (currentIndex === photos.length - 1) {
                nextBtn.disabled = true;
            } else {
                nextBtn.disabled = false;
            }
        }
    }
    
    // Abrir lightbox
    function open(index) {
        if (isOpen) return;
        
        collectPhotos();
        if (photos.length === 0) return;
        
        if (!lightbox) {
            initLightbox();
        }
        
        currentIndex = Math.max(0, Math.min(index, photos.length - 1));
        
        lightbox.classList.remove('hidden');
        document.body.style.overflow = 'hidden';
        isOpen = true;
        
        // Animação de entrada suave
        requestAnimationFrame(() => {
            setTimeout(() => {
                lightbox.classList.add('show');
                updateLightbox();
            }, 10);
        });
    }
    
    // Fechar lightbox
    function close() {
        if (!isOpen || !lightbox) return;
        
        lightbox.classList.remove('show');
        setTimeout(() => {
            lightbox.classList.add('hidden');
            document.body.style.overflow = '';
            isOpen = false;
        }, 300);
    }
    
    // Foto anterior
    function prev() {
        if (currentIndex > 0) {
            currentIndex--;
            updateLightbox();
        }
    }
    
    // Próxima foto
    function next() {
        if (currentIndex < photos.length - 1) {
            currentIndex++;
            updateLightbox();
        }
    }
    
    // Handler de teclado
    function handleKeydown(e) {
        if (!isOpen) return;
        
        switch(e.key) {
            case 'Escape':
                close();
                break;
            case 'ArrowLeft':
                prev();
                break;
            case 'ArrowRight':
                next();
                break;
        }
    }
    
    // Inicializar eventos dos itens da galeria
    function initGallery() {
        const gallery = document.getElementById('photo-gallery');
        if (!gallery) return;
        
        const items = gallery.querySelectorAll('.photo-item');
        items.forEach(item => {
            item.addEventListener('click', function() {
                const index = parseInt(this.getAttribute('data-photo-index'));
                open(index);
            });
        });
    }
    
    // API pública
    window.PhotoLightbox = {
        open: open,
        close: close,
        prev: prev,
        next: next
    };
    
    // Inicializar quando o DOM estiver pronto
    if (document.readyState === 'loading') {
        document.addEventListener('DOMContentLoaded', initGallery);
    } else {
        initGallery();
    }
})();
</script>
<?php $__env->stopPush(); ?>

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


<?php /**PATH C:\Users\Douglas\Documents\Projetos\stock-master\resources\views/projects/show.blade.php ENDPATH**/ ?>