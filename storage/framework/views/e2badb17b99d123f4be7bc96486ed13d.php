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
        <div class="flex justify-between items-center">
            <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
                <?php echo e(__('Vistoria')); ?> #<?php echo e($inspection->number); ?>

            </h2>
            <div class="flex gap-4">
                <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($inspection->status !== 'completed'): ?>
                    <a href="<?php echo e(route('inspections.edit', $inspection)); ?>" class="inline-flex items-center px-4 py-2 bg-indigo-600 dark:bg-indigo-700 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-indigo-700 dark:hover:bg-indigo-600">
                        <?php echo e(__('Editar')); ?>

                    </a>
                    <form method="POST" action="<?php echo e(route('inspections.complete', $inspection)); ?>" class="inline">
                        <?php echo csrf_field(); ?>
                        <button type="submit" class="inline-flex items-center px-4 py-2 bg-green-600 dark:bg-green-700 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-green-700 dark:hover:bg-green-600">
                            <?php echo e(__('Marcar como Concluída')); ?>

                        </button>
                    </form>
                <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                <a href="<?php echo e(route('inspections.pdf', $inspection)); ?>" class="inline-flex items-center px-4 py-2 bg-gray-800 dark:bg-gray-700 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-gray-700 dark:hover:bg-gray-600" target="_blank">
                    <?php echo e(__('Gerar PDF')); ?>

                </a>
                <a href="<?php echo e(route('inspections.index')); ?>" class="inline-flex items-center px-4 py-2 bg-gray-800 dark:bg-gray-700 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-gray-700 dark:hover:bg-gray-600">
                    <?php echo e(__('Voltar')); ?>

                </a>
            </div>
        </div>
     <?php $__env->endSlot(); ?>

    <div class="py-6">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if(session('success')): ?>
                <div class="mb-4 bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded relative">
                    <?php echo e(session('success')); ?>

                </div>
            <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
            <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if(session('error')): ?>
                <div class="mb-4 bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded relative">
                    <?php echo e(session('error')); ?>

                </div>
            <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-xl sm:rounded-lg">
                <div class="p-6 lg:p-8 bg-white dark:bg-gray-800 border-b border-gray-200 dark:border-gray-700">
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">
                        <div>
                            <h3 class="text-lg font-medium text-gray-900 dark:text-gray-100"><?php echo e(__('Informações da Vistoria')); ?></h3>
                            <div class="mt-4 space-y-4">
                                <div>
                                    <span class="text-sm font-medium text-gray-500 dark:text-gray-400"><?php echo e(__('Número')); ?>:</span>
                                    <span class="ml-2 text-sm text-gray-900 dark:text-gray-100"><?php echo e($inspection->number); ?></span>
                                </div>
                                <div>
                                    <span class="text-sm font-medium text-gray-500 dark:text-gray-400"><?php echo e(__('Data')); ?>:</span>
                                    <span class="ml-2 text-sm text-gray-900 dark:text-gray-100"><?php echo e($inspection->inspection_date->format('d/m/Y')); ?></span>
                                </div>
                                <div>
                                    <span class="text-sm font-medium text-gray-500 dark:text-gray-400"><?php echo e(__('Status')); ?>:</span>
                                    <span class="ml-2 px-2 py-1 text-xs rounded-full 
                                        <?php if($inspection->status === 'completed'): ?> bg-green-100 text-green-800
                                        <?php elseif($inspection->status === 'in_progress'): ?> bg-blue-100 text-blue-800
                                        <?php else: ?> bg-gray-100 text-gray-800
                                        <?php endif; ?>">
                                        <?php echo e(ucfirst(str_replace('_', ' ', $inspection->status))); ?>

                                    </span>
                                </div>
                            </div>
                        </div>

                        <div>
                            <h3 class="text-lg font-medium text-gray-900 dark:text-gray-100"><?php echo e(__('Informações do Cliente')); ?></h3>
                            <div class="mt-4 space-y-4">
                                <div>
                                    <span class="text-sm font-medium text-gray-500 dark:text-gray-400"><?php echo e(__('Nome')); ?>:</span>
                                    <span class="ml-2 text-sm text-gray-900 dark:text-gray-100"><?php echo e($inspection->client->name ?? $inspection->client->trading_name); ?></span>
                                </div>
                                <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($inspection->address): ?>
                                    <div>
                                        <span class="text-sm font-medium text-gray-500 dark:text-gray-400"><?php echo e(__('Endereço')); ?>:</span>
                                        <span class="ml-2 text-sm text-gray-900 dark:text-gray-100"><?php echo e($inspection->address); ?></span>
                                    </div>
                                <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                            </div>
                        </div>

                        <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($inspection->description): ?>
                            <div class="col-span-2">
                                <h3 class="text-lg font-medium text-gray-900 dark:text-gray-100"><?php echo e(__('Descrição')); ?></h3>
                                <div class="mt-4">
                                    <p class="text-sm text-gray-900 dark:text-gray-100"><?php echo e($inspection->description); ?></p>
                                </div>
                            </div>
                        <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>

                        <div class="col-span-2">
                            <h3 class="text-lg font-medium text-gray-900 dark:text-gray-100 mb-4"><?php echo e(__('Orçamento')); ?></h3>
                            <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($inspection->budget): ?>
                                <div class="bg-green-50 dark:bg-green-900/20 border border-green-200 dark:border-green-800 rounded-lg p-4">
                                    <div class="flex items-center justify-between">
                                        <div>
                                            <p class="text-sm font-medium text-gray-900 dark:text-gray-100">
                                                Orçamento #<?php echo e($inspection->budget->id); ?> vinculado
                                            </p>
                                            <p class="text-xs text-gray-600 dark:text-gray-400 mt-1">
                                                Status: <span class="capitalize"><?php echo e($inspection->budget->status_label); ?></span>
                                            </p>
                                        </div>
                                        <form method="POST" action="<?php echo e(route('inspections.unlink-budget', $inspection)); ?>" class="inline">
                                            <?php echo csrf_field(); ?>
                                            <button type="submit" class="text-red-600 dark:text-red-400 hover:text-red-800 dark:hover:text-red-300 text-sm">
                                                Desvincular
                                            </button>
                                        </form>
                                    </div>
                                </div>
                            <?php else: ?>
                                <form method="POST" action="<?php echo e(route('inspections.link-budget', $inspection)); ?>" class="space-y-4">
                                    <?php echo csrf_field(); ?>
                                    <div>
                                        <label for="budget_id" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                                            Vincular a um Orçamento
                                        </label>
                                        <select name="budget_id" id="budget_id" required class="block w-full rounded-md border-gray-300 dark:border-gray-600 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 dark:bg-gray-700 dark:text-gray-300">
                                            <option value="">Selecione um orçamento</option>
                                            <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php $__currentLoopData = \App\Models\ProjectBudget::where(function($query) use ($inspection) {
                                                $query->whereNull('inspection_id')
                                                      ->orWhere('inspection_id', $inspection->id);
                                            })->with('client')->latest()->get(); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $budget): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                                <option value="<?php echo e($budget->id); ?>">
                                                    Orçamento #<?php echo e($budget->id); ?> - <?php echo e($budget->client->name ?? $budget->client->trading_name ?? 'N/A'); ?> - R$ <?php echo e(number_format($budget->total ?? 0, 2, ',', '.')); ?>

                                                </option>
                                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                                        </select>
                                    </div>
                                    <button type="submit" class="px-4 py-2 bg-indigo-600 text-white rounded-md hover:bg-indigo-700">
                                        Vincular Orçamento
                                    </button>
                                </form>
                            <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                        </div>
                    </div>

                    <!-- Ambientes -->
                    <div class="mt-8">
                        <h3 class="text-lg font-medium text-gray-900 dark:text-gray-100 mb-4"><?php echo e(__('Ambientes')); ?></h3>
                        <div class="space-y-6">
                            <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php $__currentLoopData = $inspection->environments; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $environment): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                <div class="border border-gray-300 dark:border-gray-600 rounded-lg p-4">
                                    <h4 class="text-md font-medium text-gray-900 dark:text-gray-100 mb-4"><?php echo e($environment->name); ?></h4>
                                    
                                    <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($environment->items->count() > 0): ?>
                                        <div class="space-y-4">
                                            <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php $__currentLoopData = $environment->items; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $item): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                                <div class="bg-gray-50 dark:bg-gray-700 p-4 rounded">
                                                    <div class="flex justify-between items-start mb-2">
                                                        <h5 class="font-medium text-gray-900 dark:text-gray-100"><?php echo e($item->title); ?></h5>
                                                        <span class="px-2 py-1 text-xs rounded-full 
                                                            <?php if($item->quality_rating === 'excellent'): ?> bg-green-100 text-green-800
                                                            <?php elseif($item->quality_rating === 'good'): ?> bg-blue-100 text-blue-800
                                                            <?php elseif($item->quality_rating === 'regular'): ?> bg-yellow-100 text-yellow-800
                                                            <?php else: ?> bg-red-100 text-red-800
                                                            <?php endif; ?>">
                                                            <?php echo e($item->quality_label); ?>

                                                        </span>
                                                    </div>
                                                    
                                                    <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($item->observations): ?>
                                                        <p class="text-sm text-gray-600 dark:text-gray-400 mb-2"><?php echo e($item->observations); ?></p>
                                                    <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>

                                                    <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($item->photos->count() > 0): ?>
                                                        <div class="grid grid-cols-4 gap-2 mt-2">
                                                            <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php $__currentLoopData = $item->photos; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $photo): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                                                <img src="<?php echo e(asset('storage/' . $photo->photo_path)); ?>" alt="Foto" class="w-full h-24 object-cover rounded">
                                                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                                                        </div>
                                                    <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                                                </div>
                                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                                        </div>
                                    <?php else: ?>
                                        <p class="text-sm text-gray-500 dark:text-gray-400">Nenhum item cadastrado neste ambiente.</p>
                                    <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                                </div>
                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                        </div>
                    </div>
                </div>
            </div>
        </div>
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

<?php /**PATH C:\Users\Douglas\Documents\Projetos\stock-master\resources\views/inspections/show.blade.php ENDPATH**/ ?>