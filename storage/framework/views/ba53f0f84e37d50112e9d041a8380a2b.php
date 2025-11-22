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
     <?php $__env->slot('header', null, []); ?> 
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            <?php echo e(__('Obras')); ?>

        </h2>
     <?php $__env->endSlot(); ?>

    <div class="p-4">
        <div class="flex items-center justify-between mb-4">
            <h1 class="text-2xl font-semibold text-gray-900">Obras</h1>
            <?php if(auth()->user()->can('create projects') || auth()->user()->hasAnyRole(['manager','admin'])): ?>
            <a href="<?php echo e(route('projects.create')); ?>" class="inline-flex items-center px-4 py-2 bg-indigo-600 text-white rounded-md">Nova Obra</a>
            <?php endif; ?>
        </div>
        <div class="bg-white shadow overflow-hidden sm:rounded-md">
        <ul class="divide-y divide-gray-200">
            <?php $__empty_1 = true; $__currentLoopData = $projects; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $project): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
            <li class="relative overflow-hidden rounded-md">
                <?php
                    $totalTasks = $project->tasks()->count();
                    $doneTasks = $project->tasks()->where('status','done')->count();
                    $computedProgress = $totalTasks > 0 ? (int) round(($doneTasks / max(1,$totalTasks)) * 100) : (int) $project->progress_percentage;
                ?>
                <a href="<?php echo e(route('projects.show', $project)); ?>" class="block px-4 py-4">
                    <div class="absolute inset-0 bg-white rounded-md" style="width: 100%;"></div>
                    <div class="absolute inset-y-0 left-0 bg-green-100 rounded-md" style="width: <?php echo e($computedProgress); ?>%;"></div>
                    <div class="relative flex items-center justify-between">
                        <p class="text-sm font-medium text-green-700"><?php echo e($project->name); ?> <span class="text-gray-500">(<?php echo e($project->code); ?>)</span></p>
                        <p class="text-sm text-indigo-700"><?php echo e($computedProgress); ?>% <?php if($totalTasks>0): ?>· <?php echo e($doneTasks); ?>/<?php echo e($totalTasks); ?> tarefas <?php endif; ?></p>
                    </div>
                </a>
            </li>
            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
            <li class="px-4 py-6 text-sm text-gray-500">Nenhuma obra cadastrada.</li>
            <?php endif; ?>
        </ul>
        </div>
        <div class="mt-4"><?php echo e($projects->links()); ?></div>
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