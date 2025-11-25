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
                <?php echo e(__('Requisição de Equipamento #' . $equipmentRequest->number)); ?>

            </h2>
            <div class="flex items-center space-x-4">
                <a href="<?php echo e(route('equipment-requests.pdf', $equipmentRequest)); ?>" 
                   class="inline-flex items-center px-4 py-2 bg-green-600 dark:bg-green-500 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-green-700 dark:hover:bg-green-600 focus:bg-green-700 active:bg-green-900 focus:outline-none focus:ring-2 focus:ring-indigo-500 dark:focus:ring-indigo-400 focus:ring-offset-2 dark:focus:ring-offset-gray-800 transition ease-in-out duration-150" target="_blank">
                    <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                    </svg>
                    <?php echo e(__('PDF')); ?>

                </a>
                <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('edit service-orders')): ?>
                    <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($equipmentRequest->status === 'pending'): ?>
                        <a href="<?php echo e(route('equipment-requests.edit', $equipmentRequest)); ?>" 
                           class="inline-flex items-center px-4 py-2 bg-indigo-600 dark:bg-indigo-500 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-indigo-700 dark:hover:bg-indigo-600 focus:bg-indigo-700 active:bg-indigo-900 focus:outline-none focus:ring-2 focus:ring-indigo-500 dark:focus:ring-indigo-400 focus:ring-offset-2 dark:focus:ring-offset-gray-800 transition ease-in-out duration-150">
                            <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                            </svg>
                            <?php echo e(__('Editar')); ?>

                        </a>
                    <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                <?php endif; ?>
                <a href="<?php echo e(route('equipment-requests.index')); ?>" 
                   class="inline-flex items-center px-4 py-2 bg-gray-800 dark:bg-gray-700 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-gray-700 dark:hover:bg-gray-600 focus:bg-gray-700 active:bg-gray-900 focus:outline-none focus:ring-2 focus:ring-indigo-500 dark:focus:ring-indigo-400 focus:ring-offset-2 dark:focus:ring-offset-gray-800 transition ease-in-out duration-150">
                    <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18" />
                    </svg>
                    <?php echo e(__('Voltar')); ?>

                </a>
            </div>
        </div>
     <?php $__env->endSlot(); ?>

    <div class="py-6">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
                <!-- Informações da Requisição -->
                <div class="lg:col-span-2 bg-white dark:bg-gray-800 overflow-hidden shadow-xl sm:rounded-lg">
                    <div class="p-6 lg:p-8 bg-white dark:bg-gray-800 border-b border-gray-200 dark:border-gray-700">
                        <h3 class="text-lg font-semibold text-gray-900 dark:text-gray-100 mb-4">Informações da Requisição</h3>
                        
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-6">
                            <div>
                                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300">Número</label>
                                <p class="mt-1 text-sm text-gray-900 dark:text-gray-100">#<?php echo e($equipmentRequest->number); ?></p>
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300">Tipo</label>
                                <span class="mt-1 px-2 inline-flex text-xs leading-5 font-semibold rounded-full 
                                    <?php echo e($equipmentRequest->type === 'loan' ? 'bg-blue-100 dark:bg-blue-900/50 text-blue-800 dark:text-blue-300' : 'bg-green-100 dark:bg-green-900/50 text-green-800 dark:text-green-300'); ?>">
                                    <?php echo e($equipmentRequest->type === 'loan' ? 'Empréstimo' : 'Devolução'); ?>

                                </span>
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300">Status</label>
                                <span class="mt-1 px-2 inline-flex text-xs leading-5 font-semibold rounded-full 
                                    <?php switch($equipmentRequest->status):
                                        case ('pending'): ?> bg-yellow-100 dark:bg-yellow-900/50 text-yellow-800 dark:text-yellow-300 <?php break; ?>
                                        <?php case ('approved'): ?> bg-blue-100 dark:bg-blue-900/50 text-blue-800 dark:text-blue-300 <?php break; ?>
                                        <?php case ('rejected'): ?> bg-red-100 dark:bg-red-900/50 text-red-800 dark:text-red-300 <?php break; ?>
                                        <?php case ('completed'): ?> bg-green-100 dark:bg-green-900/50 text-green-800 dark:text-green-300 <?php break; ?>
                                    <?php endswitch; ?>">
                                    <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php switch($equipmentRequest->status):
                                        case ('pending'): ?> Pendente <?php break; ?>
                                        <?php case ('approved'): ?> Aprovado <?php break; ?>
                                        <?php case ('rejected'): ?> Rejeitado <?php break; ?>
                                        <?php case ('completed'): ?> Concluído <?php break; ?>
                                    <?php endswitch; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                                </span>
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300">Data de Criação</label>
                                <p class="mt-1 text-sm text-gray-900 dark:text-gray-100"><?php echo e($equipmentRequest->created_at->format('d/m/Y H:i')); ?></p>
                            </div>
                        </div>

                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-6">
                            <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($equipmentRequest->serviceOrder): ?>
                            <div>
                                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300">Ordem de Serviço</label>
                                <p class="mt-1 text-sm text-gray-900 dark:text-gray-100">#<?php echo e($equipmentRequest->serviceOrder->number); ?></p>
                                <p class="text-xs text-gray-500 dark:text-gray-400"><?php echo e($equipmentRequest->serviceOrder->client_name); ?></p>
                            </div>
                            <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                        </div>

                        <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($equipmentRequest->expected_return_date): ?>
                        <div class="mb-6">
                            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300">Data Prevista de Devolução</label>
                            <p class="mt-1 text-sm text-gray-900 dark:text-gray-100"><?php echo e($equipmentRequest->expected_return_date->format('d/m/Y')); ?></p>
                        </div>
                        <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>

                        <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($equipmentRequest->purpose): ?>
                        <div class="mb-6">
                            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300">Finalidade</label>
                            <p class="mt-1 text-sm text-gray-900 dark:text-gray-100"><?php echo e($equipmentRequest->purpose); ?></p>
                        </div>
                        <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>

                        <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($equipmentRequest->notes): ?>
                        <div class="mb-6">
                            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300">Observações</label>
                            <p class="mt-1 text-sm text-gray-900 dark:text-gray-100"><?php echo e($equipmentRequest->notes); ?></p>
                        </div>
                        <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>

                        <!-- Equipamentos -->
                        <div>
                            <h4 class="text-md font-semibold text-gray-900 dark:text-gray-100 mb-3">Equipamentos</h4>
                            <div class="overflow-x-auto">
                                <table class="min-w-full divide-y divide-gray-200 dark:divide-gray-700">
                                    <thead class="bg-gray-50 dark:bg-gray-700">
                                        <tr>
                                            <th class="px-4 py-2 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase">Equipamento</th>
                                            <th class="px-4 py-2 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase">Série</th>
                                            <th class="px-4 py-2 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase">Qtd</th>
                                            <th class="px-4 py-2 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase">Observações</th>
                                        </tr>
                                    </thead>
                                    <tbody class="bg-white dark:bg-gray-800 divide-y divide-gray-200 dark:divide-gray-700">
                                        <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php $__currentLoopData = $equipmentRequest->items; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $item): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                            <tr class="hover:bg-gray-50 dark:hover:bg-gray-700 transition-colors">
                                                <td class="px-4 py-2">
                                                    <div>
                                                        <div class="text-sm font-medium text-gray-900 dark:text-gray-100"><?php echo e($item->equipment->name); ?></div>
                                                        <div class="text-sm text-gray-500 dark:text-gray-400"><?php echo e($item->equipment->category->name ?? 'Sem categoria'); ?></div>
                                                    </div>
                                                </td>
                                                <td class="px-4 py-2 text-sm text-gray-900 dark:text-gray-100"><?php echo e($item->equipment->serial_number); ?></td>
                                                <td class="px-4 py-2 text-sm text-gray-900 dark:text-gray-100"><?php echo e($item->quantity); ?></td>
                                                <td class="px-4 py-2 text-sm text-gray-500 dark:text-gray-400"><?php echo e($item->condition_notes ?: '-'); ?></td>
                                            </tr>
                                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Ações e Status -->
                <div class="space-y-6">
                    <!-- Equipamentos -->
                    <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($equipmentRequest->items->count() > 0): ?>
                    <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-xl sm:rounded-lg">
                        <div class="p-6 bg-white dark:bg-gray-800 border-b border-gray-200 dark:border-gray-700">
                            <h3 class="text-lg font-semibold text-gray-900 dark:text-gray-100 mb-4">Equipamentos</h3>
                            <div class="space-y-4">
                                <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php $__currentLoopData = $equipmentRequest->items; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $item): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                    <?php
                                        // Buscar última requisição de empréstimo completada para este equipamento
                                        $lastLoanItem = $item->equipment->getLastLoan();
                                    ?>
                                    <div class="flex items-start">
                                        <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($item->equipment->photos && count($item->equipment->photos) > 0): ?>
                                            <img src="<?php echo e(asset('storage/' . $item->equipment->photos[0])); ?>" 
                                                 alt="<?php echo e($item->equipment->name); ?>" 
                                                 class="h-16 w-16 rounded-lg object-cover mr-3 flex-shrink-0">
                                        <?php else: ?>
                                            <div class="h-16 w-16 rounded-lg bg-gray-200 dark:bg-gray-600 flex items-center justify-center mr-3 flex-shrink-0">
                                                <svg class="w-8 h-8 text-gray-400 dark:text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19.428 15.428a2 2 0 00-1.022-.547l-2.387-.477a6 6 0 00-3.86.517l-.318.158a6 6 0 01-3.86.517L6.05 15.21a2 2 0 00-1.806.547M8 4h8l-1 1v5.172a2 2 0 00.586 1.414l5 5c1.26 1.26.367 3.414-1.415 3.414H4.828c-1.782 0-2.674-2.154-1.414-3.414l5-5A2 2 0 009 10.172V5L8 4z" />
                                                </svg>
                                            </div>
                                        <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                                        <div class="flex-1 min-w-0">
                                            <div class="text-sm font-semibold text-gray-900 dark:text-gray-100"><?php echo e($item->equipment->name); ?></div>
                                            <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($lastLoanItem && $lastLoanItem->equipmentRequest && $lastLoanItem->equipmentRequest->employee): ?>
                                                <div class="text-xs text-gray-500 dark:text-gray-400 mt-1">
                                                    Último uso: 
                                                    <a href="<?php echo e(route('employees.show', $lastLoanItem->equipmentRequest->employee)); ?>" 
                                                       class="text-indigo-600 dark:text-indigo-400 hover:text-indigo-900 dark:hover:text-indigo-300 hover:underline">
                                                        <?php echo e($lastLoanItem->equipmentRequest->employee->name); ?>

                                                    </a>
                                                </div>
                                                <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($lastLoanItem->equipmentRequest->created_at): ?>
                                                <div class="text-xs text-gray-400 dark:text-gray-500">
                                                    <?php echo e($lastLoanItem->equipmentRequest->created_at->format('d/m/Y')); ?>

                                                </div>
                                                <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                                            <?php elseif($item->equipment->currentEmployee): ?>
                                                <div class="text-xs text-gray-500 dark:text-gray-400 mt-1">
                                                    Em uso: 
                                                    <a href="<?php echo e(route('employees.show', $item->equipment->currentEmployee)); ?>" 
                                                       class="text-indigo-600 dark:text-indigo-400 hover:text-indigo-900 dark:hover:text-indigo-300 hover:underline">
                                                        <?php echo e($item->equipment->currentEmployee->name); ?>

                                                    </a>
                                                </div>
                                            <?php else: ?>
                                                <div class="text-xs text-gray-500 dark:text-gray-400 mt-1">
                                                    Nenhum uso registrado
                                                </div>
                                            <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                                        </div>
                                    </div>
                                    <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if(!$loop->last): ?>
                                        <div class="border-t border-gray-200 dark:border-gray-700"></div>
                                    <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                            </div>
                        </div>
                    </div>
                    <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>

                    <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('edit service-orders')): ?>
                        <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($equipmentRequest->status === 'pending'): ?>
                        <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-xl sm:rounded-lg">
                            <div class="p-6 lg:p-8 bg-white dark:bg-gray-800 border-b border-gray-200 dark:border-gray-700">
                                <h3 class="text-lg font-semibold text-gray-900 dark:text-gray-100 mb-4">Ações</h3>
                                <div class="space-y-3">
                                    <form method="POST" action="<?php echo e(route('equipment-requests.approve', $equipmentRequest)); ?>" class="w-full">
                                        <?php echo csrf_field(); ?>
                                        <button type="submit" 
                                                class="w-full inline-flex items-center justify-center px-4 py-2 bg-green-600 dark:bg-green-500 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-green-700 dark:hover:bg-green-600 focus:bg-green-700 active:bg-green-900 focus:outline-none focus:ring-2 focus:ring-indigo-500 dark:focus:ring-indigo-400 focus:ring-offset-2 dark:focus:ring-offset-gray-800 transition ease-in-out duration-150"
                                                onclick="return confirm('Tem certeza que deseja aprovar esta requisição?')">
                                            Aprovar
                                        </button>
                                    </form>
                                    <form method="POST" action="<?php echo e(route('equipment-requests.reject', $equipmentRequest)); ?>" class="w-full">
                                        <?php echo csrf_field(); ?>
                                        <button type="submit" 
                                                class="w-full inline-flex items-center justify-center px-4 py-2 bg-red-600 dark:bg-red-500 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-red-700 dark:hover:bg-red-600 focus:bg-red-700 active:bg-red-900 focus:outline-none focus:ring-2 focus:ring-indigo-500 dark:focus:ring-indigo-400 focus:ring-offset-2 dark:focus:ring-offset-gray-800 transition ease-in-out duration-150"
                                                onclick="return confirm('Tem certeza que deseja rejeitar esta requisição?')">
                                            Rejeitar
                                        </button>
                                    </form>
                                </div>
                            </div>
                        </div>
                        <?php elseif($equipmentRequest->status === 'approved'): ?>
                        <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-xl sm:rounded-lg">
                            <div class="p-6 lg:p-8 bg-white dark:bg-gray-800 border-b border-gray-200 dark:border-gray-700">
                                <h3 class="text-lg font-semibold text-gray-900 dark:text-gray-100 mb-4">Processar</h3>
                                <form method="POST" action="<?php echo e(route('equipment-requests.complete', $equipmentRequest)); ?>" class="w-full">
                                    <?php echo csrf_field(); ?>
                                    <button type="submit" 
                                            class="w-full inline-flex items-center justify-center px-4 py-2 bg-indigo-600 dark:bg-indigo-500 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-indigo-700 dark:hover:bg-indigo-600 focus:bg-indigo-700 active:bg-indigo-900 focus:outline-none focus:ring-2 focus:ring-indigo-500 dark:focus:ring-indigo-400 focus:ring-offset-2 dark:focus:ring-offset-gray-800 transition ease-in-out duration-150"
                                            onclick="return confirm('Tem certeza que deseja processar esta requisição?')">
                                        Processar <?php echo e($equipmentRequest->type === 'loan' ? 'Empréstimo' : 'Devolução'); ?>

                                    </button>
                                </form>
                            </div>
                        </div>
                        <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                    <?php endif; ?>

                    <!-- Informações do Usuário que Criou -->
                    <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-xl sm:rounded-lg">
                        <div class="p-6 bg-white dark:bg-gray-800 border-b border-gray-200 dark:border-gray-700">
                            <h3 class="text-lg font-semibold text-gray-900 dark:text-gray-100 mb-4">Criado por</h3>
                            <div class="flex items-center">
                                <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($equipmentRequest->user->profile_photo): ?>
                                    <img src="<?php echo e(asset('storage/' . $equipmentRequest->user->profile_photo)); ?>" 
                                         alt="<?php echo e($equipmentRequest->user->name); ?>" 
                                         class="h-10 w-10 rounded-full object-cover mr-3">
                                <?php else: ?>
                                    <div class="h-10 w-10 rounded-full bg-indigo-500 dark:bg-indigo-600 flex items-center justify-center text-white font-semibold text-sm mr-3">
                                        <?php echo e(strtoupper(substr($equipmentRequest->user->name, 0, 1))); ?>

                                    </div>
                                <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                                <div>
                                    <div class="text-sm font-medium text-gray-900 dark:text-gray-100"><?php echo e($equipmentRequest->user->name); ?></div>
                                    <div class="text-sm text-gray-500 dark:text-gray-400"><?php echo e($equipmentRequest->created_at->format('d/m/Y H:i')); ?></div>
                                </div>
                            </div>
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
<?php /**PATH C:\Users\Douglas\Documents\Projetos\stock-master\resources\views/equipment-requests/show.blade.php ENDPATH**/ ?>