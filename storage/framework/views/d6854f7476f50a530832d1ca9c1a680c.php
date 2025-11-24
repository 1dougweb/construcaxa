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
                <?php echo e(__('Vistorias')); ?>

            </h2>
            <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('create inspections')): ?>
            <a href="<?php echo e(route('inspections.create')); ?>" class="inline-flex items-center px-4 py-2 bg-indigo-600 dark:bg-indigo-700 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-indigo-700 dark:hover:bg-indigo-600">
                <i class="bi bi-plus-circle mr-2"></i>
                Nova Vistoria
            </a>
            <?php endif; ?>
        </div>
     <?php $__env->endSlot(); ?>

    <div class="py-6">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-xl sm:rounded-lg border border-gray-200 dark:border-gray-700">
                <div class="p-6">
                    <!-- Filtros -->
                    <form method="GET" action="<?php echo e(route('inspections.index')); ?>" class="mb-6">
                        <div class="grid grid-cols-1 md:grid-cols-4 gap-4">
                            <div>
                                <input type="text" name="search" value="<?php echo e(request('search')); ?>" 
                                       placeholder="Buscar por número, descrição ou cliente..." 
                                       class="w-full border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-gray-300 rounded-md">
                            </div>
                            <div>
                                <select name="client_id" class="w-full border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-gray-300 rounded-md">
                                    <option value="">Todos os clientes</option>
                                    <?php $__currentLoopData = $clients; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $client): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                        <option value="<?php echo e($client->id); ?>" <?php echo e(request('client_id') == $client->id ? 'selected' : ''); ?>>
                                            <?php echo e($client->name); ?>

                                        </option>
                                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                </select>
                            </div>
                            <div>
                                <select name="status" class="w-full border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-gray-300 rounded-md">
                                    <option value="">Todos os status</option>
                                    <option value="draft" <?php echo e(request('status') === 'draft' ? 'selected' : ''); ?>>Rascunho</option>
                                    <option value="pending" <?php echo e(request('status') === 'pending' ? 'selected' : ''); ?>>Pendente</option>
                                    <option value="approved" <?php echo e(request('status') === 'approved' ? 'selected' : ''); ?>>Aprovada</option>
                                    <option value="rejected" <?php echo e(request('status') === 'rejected' ? 'selected' : ''); ?>>Rejeitada</option>
                                </select>
                            </div>
                            <div>
                                <button type="submit" class="w-full px-4 py-2 bg-indigo-600 dark:bg-indigo-700 text-white rounded-md hover:bg-indigo-700 dark:hover:bg-indigo-600">
                                    Filtrar
                                </button>
                            </div>
                        </div>
                    </form>

                    <!-- Tabela de Vistorias -->
                    <div class="overflow-x-auto">
                        <table class="min-w-full divide-y divide-gray-200 dark:divide-gray-700">
                            <thead class="bg-gray-50 dark:bg-gray-700/50">
                                <tr>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">Número</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">Cliente</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">Data</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">Versão</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">Status</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">Responsável</th>
                                    <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">Ações</th>
                                </tr>
                            </thead>
                            <tbody class="bg-white dark:bg-gray-800 divide-y divide-gray-200 dark:divide-gray-700">
                                <?php $__empty_1 = true; $__currentLoopData = $inspections; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $inspection): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                                    <tr class="hover:bg-gray-50 dark:hover:bg-gray-700/50">
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            <div class="text-sm font-medium text-gray-900 dark:text-gray-100"><?php echo e($inspection->number); ?></div>
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            <div class="text-sm text-gray-900 dark:text-gray-100"><?php echo e($inspection->client->name); ?></div>
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500 dark:text-gray-400">
                                            <?php echo e($inspection->inspection_date->format('d/m/Y')); ?>

                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500 dark:text-gray-400">
                                            v<?php echo e($inspection->version); ?>

                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            <span class="px-2 py-1 text-xs rounded-full border <?php echo e($inspection->status_color); ?>">
                                                <?php echo e($inspection->status_label); ?>

                                            </span>
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500 dark:text-gray-400">
                                            <?php echo e($inspection->inspector->name ?? '-'); ?>

                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                                            <div class="flex justify-end space-x-2">
                                                <a href="<?php echo e(route('inspections.show', $inspection)); ?>" 
                                                   class="text-indigo-600 dark:text-indigo-400 hover:text-indigo-900 dark:hover:text-indigo-300" title="Visualizar">
                                                    <i class="bi bi-eye"></i>
                                                </a>
                                                <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('edit inspections')): ?>
                                                <a href="<?php echo e(route('inspections.edit', $inspection)); ?>" 
                                                   class="text-yellow-600 dark:text-yellow-400 hover:text-yellow-900 dark:hover:text-yellow-300" title="Editar">
                                                    <i class="bi bi-pencil"></i>
                                                </a>
                                                <?php endif; ?>
                                                <a href="<?php echo e(route('inspections.pdf', $inspection)); ?>" 
                                                   class="text-blue-600 dark:text-blue-400 hover:text-blue-900 dark:hover:text-blue-300" target="_blank" title="PDF">
                                                    <i class="bi bi-file-pdf"></i>
                                                </a>
                                            </div>
                                        </td>
                                    </tr>
                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                                    <tr>
                                        <td colspan="7" class="px-6 py-4 text-center text-sm text-gray-500 dark:text-gray-400">
                                            Nenhuma vistoria encontrada.
                                        </td>
                                    </tr>
                                <?php endif; ?>
                            </tbody>
                        </table>
                    </div>

                    <!-- Paginação -->
                    <div class="mt-4">
                        <?php echo e($inspections->links()); ?>

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

<?php /**PATH C:\Users\drdes\OneDrive\Documentos\Projetos\2025\Novembro\construcaxa\resources\views/inspections/index.blade.php ENDPATH**/ ?>