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
                <?php echo e(__('Equipamentos')); ?>

            </h2>
            <div class="flex items-center space-x-4">
                <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('view categories')): ?>
                <a href="<?php echo e(route('equipment-categories.index')); ?>" class="inline-flex items-center px-4 py-2 bg-gray-800 dark:bg-gray-700 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-gray-700 dark:hover:bg-gray-600 focus:bg-gray-700 active:bg-gray-900 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 transition ease-in-out duration-150">
                    <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 7h.01M7 3h5c.512 0 1.024.195 1.414.586l7 7a2 2 0 010 2.828l-7 7a2 2 0 01-2.828 0l-7-7A1.994 1.994 0 013 12V7a4 4 0 014-4z" />
                    </svg>
                    <?php echo e(__('Gerenciar Categorias')); ?>

                </a>
                <?php endif; ?>
                <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('create products')): ?>
                <a href="<?php echo e(route('equipment.create')); ?>" class="inline-flex items-center px-4 py-2 bg-indigo-600 dark:bg-indigo-700 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-indigo-700 dark:hover:bg-indigo-600 focus:bg-indigo-700 active:bg-indigo-900 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 transition ease-in-out duration-150">
                    <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
                    </svg>
                    <?php echo e(__('Novo Equipamento')); ?>

                </a>
                <?php endif; ?>
            </div>
        </div>
     <?php $__env->endSlot(); ?>

    <div class="py-6">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-xl sm:rounded-lg">
                <div class="p-6 lg:p-8 bg-white dark:bg-gray-800 border-b border-gray-200 dark:border-gray-700">
                    <!-- Filtros -->
                    <div class="mb-6">
                        <form method="GET" class="flex flex-wrap gap-4">
                            <div class="flex-1 min-w-64">
                                <input type="text" name="search" value="<?php echo e(request('search')); ?>" 
                                       placeholder="Buscar por nome ou número de série..." 
                                       class="w-full rounded-md border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-gray-300 dark:placeholder-gray-400 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 dark:focus:border-indigo-400 dark:focus:ring-indigo-400">
                            </div>
                            <div>
                                <select name="status" class="rounded-md border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 dark:focus:border-indigo-400 dark:focus:ring-indigo-400">
                                    <option value="">Todos os status</option>
                                    <option value="available" <?php echo e(request('status') === 'available' ? 'selected' : ''); ?>>Disponível</option>
                                    <option value="borrowed" <?php echo e(request('status') === 'borrowed' ? 'selected' : ''); ?>>Emprestado</option>
                                    <option value="maintenance" <?php echo e(request('status') === 'maintenance' ? 'selected' : ''); ?>>Manutenção</option>
                                    <option value="retired" <?php echo e(request('status') === 'retired' ? 'selected' : ''); ?>>Aposentado</option>
                                </select>
                            </div>
                            <div>
                                <select name="equipment_category_id" class="rounded-md border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 dark:focus:border-indigo-400 dark:focus:ring-indigo-400">
                                    <option value="">Todas as categorias</option>
                                    <?php $__currentLoopData = $equipmentCategories; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $category): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                        <option value="<?php echo e($category->id); ?>" <?php echo e(request('equipment_category_id') == $category->id ? 'selected' : ''); ?>>
                                            <?php echo e($category->name); ?>

                                        </option>
                                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                </select>
                            </div>
                            <button type="submit" class="inline-flex items-center px-4 py-2 bg-gray-800 dark:bg-gray-700 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-gray-700 dark:hover:bg-gray-600 focus:bg-gray-700 active:bg-gray-900 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 transition ease-in-out duration-150">
                                Filtrar
                            </button>
                            <a href="<?php echo e(route('equipment.index')); ?>" class="inline-flex items-center px-4 py-2 bg-gray-300 dark:bg-gray-600 border border-transparent rounded-md font-semibold text-xs text-gray-700 dark:text-gray-300 uppercase tracking-widest hover:bg-gray-400 dark:hover:bg-gray-500 focus:bg-gray-400 active:bg-gray-500 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 transition ease-in-out duration-150">
                                Limpar
                            </a>
                        </form>
                    </div>

                    <?php if($equipment->isEmpty()): ?>
                        <div class="text-center py-8">
                            <svg class="mx-auto h-12 w-12 text-gray-400 dark:text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19.428 15.428a2 2 0 00-1.022-.547l-2.387-.477a6 6 0 00-3.86.517l-.318.158a6 6 0 01-3.86.517L6.05 15.21a2 2 0 00-1.806.547M8 4h8l-1 1v5.172a2 2 0 00.586 1.414l5 5c1.26 1.26.367 3.414-1.415 3.414H4.828c-1.782 0-2.674-2.154-1.414-3.414l5-5A2 2 0 009 10.172V5L8 4z" />
                            </svg>
                            <h3 class="mt-2 text-sm font-medium text-gray-900 dark:text-gray-100">Nenhum equipamento encontrado</h3>
                            <p class="mt-1 text-sm text-gray-500 dark:text-gray-400">Comece cadastrando um novo equipamento.</p>
                            <div class="mt-6">
                                <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('create products')): ?>
                                <a href="<?php echo e(route('equipment.create')); ?>" class="inline-flex items-center px-4 py-2 bg-indigo-600 dark:bg-indigo-700 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-indigo-700 dark:hover:bg-indigo-600 focus:bg-indigo-700 active:bg-indigo-900 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 transition ease-in-out duration-150">
                                    <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
                                    </svg>
                                    <?php echo e(__('Novo Equipamento')); ?>

                                </a>
                                <?php endif; ?>
                            </div>
                        </div>
                    <?php else: ?>
                        <table class="min-w-full divide-y divide-gray-200 dark:divide-gray-700">
                            <thead>
                                <tr>
                                    <th class="px-6 py-3 bg-gray-50 dark:bg-gray-700/50 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">Foto</th>
                                    <th class="px-6 py-3 bg-gray-50 dark:bg-gray-700/50 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">Nome</th>
                                    <th class="px-6 py-3 bg-gray-50 dark:bg-gray-700/50 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">Número de Série</th>
                                    <th class="px-6 py-3 bg-gray-50 dark:bg-gray-700/50 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">Categoria</th>
                                    <th class="px-6 py-3 bg-gray-50 dark:bg-gray-700/50 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">Status</th>
                                    <th class="px-6 py-3 bg-gray-50 dark:bg-gray-700/50 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">Funcionário</th>
                                    <?php if(auth()->user()->can('view products') || auth()->user()->can('edit products')): ?>
                                    <th class="px-6 py-3 bg-gray-50 dark:bg-gray-700/50 text-right text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider"></th>
                                    <?php endif; ?>
                                </tr>
                            </thead>
                            <tbody class="bg-white dark:bg-gray-800 divide-y divide-gray-200 dark:divide-gray-700">
                                <?php $__currentLoopData = $equipment; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $item): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                    <tr>
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            <?php if($item->photos && count($item->photos) > 0): ?>
                                                <img src="<?php echo e(asset('storage/' . $item->photos[0])); ?>" 
                                                     alt="<?php echo e($item->name); ?>" 
                                                     class="h-12 w-12 rounded object-cover border border-gray-200 dark:border-gray-700">
                                            <?php else: ?>
                                                <div class="h-12 w-12 rounded bg-gray-200 dark:bg-gray-700 flex items-center justify-center">
                                                    <svg class="w-6 h-6 text-gray-400 dark:text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19.428 15.428a2 2 0 00-1.022-.547l-2.387-.477a6 6 0 00-3.86.517l-.318.158a6 6 0 01-3.86.517L6.05 15.21a2 2 0 00-1.806.547M8 4h8l-1 1v5.172a2 2 0 00.586 1.414l5 5c1.26 1.26.367 3.414-1.415 3.414H4.828c-1.782 0-2.674-2.154-1.414-3.414l5-5A2 2 0 009 10.172V5L8 4z" />
                                                    </svg>
                                                </div>
                                            <?php endif; ?>
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900 dark:text-gray-100">
                                            <a href="<?php echo e(route('equipment.show', $item)); ?>" class="hover:text-indigo-600 dark:hover:text-indigo-400">
                                                <?php echo e($item->name); ?>

                                            </a>
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500 dark:text-gray-400">
                                            <?php echo e($item->serial_number); ?>

                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500 dark:text-gray-400">
                                            <?php echo e($item->equipmentCategory ? $item->equipmentCategory->name : '-'); ?>

                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full 
                                                <?php switch($item->status):
                                                    case ('available'): ?> bg-green-100 dark:bg-green-900/50 text-green-800 dark:text-green-300 <?php break; ?>
                                                    <?php case ('borrowed'): ?> bg-yellow-100 dark:bg-yellow-900/50 text-yellow-800 dark:text-yellow-300 <?php break; ?>
                                                    <?php case ('maintenance'): ?> bg-red-100 dark:bg-red-900/50 text-red-800 dark:text-red-300 <?php break; ?>
                                                    <?php case ('retired'): ?> bg-gray-100 dark:bg-gray-700 text-gray-800 dark:text-gray-300 <?php break; ?>
                                                <?php endswitch; ?>">
                                                <?php switch($item->status):
                                                    case ('available'): ?> Disponível <?php break; ?>
                                                    <?php case ('borrowed'): ?> Emprestado <?php break; ?>
                                                    <?php case ('maintenance'): ?> Manutenção <?php break; ?>
                                                    <?php case ('retired'): ?> Aposentado <?php break; ?>
                                                <?php endswitch; ?>
                                            </span>
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500 dark:text-gray-400">
                                            <?php echo e($item->currentEmployee ? $item->currentEmployee->name : '-'); ?>

                                        </td>
                                        <?php if(auth()->user()->can('view products') || auth()->user()->can('edit products')): ?>
                                        <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                                            <div class="flex items-center justify-end space-x-4">
                                                <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('view products')): ?>
                                                <a href="<?php echo e(route('equipment.show', $item)); ?>" class="text-gray-600 dark:text-gray-400 hover:text-gray-900 dark:hover:text-gray-100">
                                                    <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                                                    </svg>
                                                </a>
                                                <?php endif; ?>
                                                <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('edit products')): ?>
                                                <a href="<?php echo e(route('equipment.edit', $item)); ?>" class="text-indigo-600 dark:text-indigo-400 hover:text-indigo-900 dark:hover:text-indigo-300">
                                                    <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                                                    </svg>
                                                </a>
                                                <?php endif; ?>
                                                <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('view products')): ?>
                                                <a href="<?php echo e(route('equipment.history', $item)); ?>" class="text-indigo-600 dark:text-indigo-400 hover:text-indigo-900 dark:hover:text-indigo-300">
                                                    <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                                                    </svg>
                                                </a>
                                                <?php endif; ?>
                                            </div>
                                        </td>
                                        <?php endif; ?>
                                    </tr>
                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                            </tbody>
                        </table>

                        <div class="mt-4">
                            <?php echo e($equipment->withQueryString()->links()); ?>

                        </div>
                    <?php endif; ?>
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
<?php /**PATH C:\Users\drdes\OneDrive\Documentos\Projetos\2025\Novembro\construcaxa\resources\views/equipment/index.blade.php ENDPATH**/ ?>