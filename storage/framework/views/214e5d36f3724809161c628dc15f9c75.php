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
            <?php echo e(__('Orçamentos')); ?>

        </h2>
     <?php $__env->endSlot(); ?>

    <?php $__env->startPush('styles'); ?>
    <style>
        /* Budget Status Animations */
        @keyframes pulse-dot {
            0%, 100% {
                opacity: 1;
                transform: scale(1);
            }
            50% {
                opacity: 0.7;
                transform: scale(1.1);
            }
        }

        @keyframes pending-pulse {
            0%, 100% {
                box-shadow: 0 0 5px rgba(251, 191, 36, 0.3);
            }
            50% {
                box-shadow: 0 0 15px rgba(251, 191, 36, 0.6);
            }
        }

        @keyframes under-review-pulse {
            0%, 100% {
                box-shadow: 0 0 5px rgba(59, 130, 246, 0.3);
            }
            50% {
                box-shadow: 0 0 15px rgba(59, 130, 246, 0.6);
            }
        }

        .status-dot {
            animation: pulse-dot 2s infinite ease-in-out;
        }

        .budget-card.status-pending {
            animation: pending-pulse 3s infinite ease-in-out;
        }

        .budget-card.status-under_review {
            animation: under-review-pulse 3s infinite ease-in-out;
        }

        .budget-card:hover {
            transform: translateY(-2px);
            transition: all 0.3s ease;
        }

        .action-button {
            transition: all 0.2s ease;
        }

        .action-button:hover {
            transform: translateY(-1px);
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.15);
        }

        @keyframes fadeInUp {
            from {
                opacity: 0;
                transform: translateY(20px);
            }
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        .budget-card {
            animation: fadeInUp 0.5s ease-out;
        }
    </style>
    <?php $__env->stopPush(); ?>

    <div class="p-4">
        <div class="flex items-center justify-between mb-6">
            <h1 class="text-2xl font-semibold text-gray-900">Orçamentos</h1>
            <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('manage budgets')): ?>
            <a href="<?php echo e(route('budgets.create')); ?>" class="inline-flex items-center px-4 py-2 bg-indigo-600 text-white rounded-md hover:bg-indigo-700">
                <i class="bi bi-plus-circle mr-2"></i>
                Novo Orçamento
            </a>
            <?php endif; ?>
        </div>

        <!-- Budget Grid -->
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-6 mb-8">
            <?php $__empty_1 = true; $__currentLoopData = $budgets; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $budget): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                <div class="budget-card status-<?php echo e($budget->status); ?> bg-white rounded-lg shadow-md border-2 <?php echo e($budget->status_color); ?> hover:shadow-lg transition-shadow duration-200">
                    <div class="p-6">
                        <!-- Status Indicator -->
                        <div class="flex items-center justify-between mb-4">
                            <div class="flex items-center">
                                <div class="w-3 h-3 rounded-full mr-2 <?php echo e($budget->status === 'pending' || $budget->status === 'under_review' ? 'status-dot' : ''); ?> 
                                    <?php echo e($budget->status === 'approved' ? 'bg-green-500' : ''); ?>

                                    <?php echo e($budget->status === 'rejected' ? 'bg-orange-500' : ''); ?>

                                    <?php echo e($budget->status === 'cancelled' ? 'bg-red-500' : ''); ?>

                                    <?php echo e($budget->status === 'under_review' ? 'bg-blue-500' : ''); ?>

                                    <?php echo e($budget->status === 'pending' ? 'bg-yellow-500' : ''); ?>

                                "></div>
                                <span class="text-sm font-medium <?php echo e(str_replace(['bg-', '-100'], ['text-', '-800'], explode(' ', $budget->status_color)[1])); ?>">
                                    <?php echo e($budget->status_label); ?>

                                </span>
                            </div>
                            <span class="text-xs text-gray-500">v<?php echo e($budget->version); ?></span>
                        </div>

                        <!-- Client/Project Info -->
                        <div class="mb-4">
                            <?php if($budget->project): ?>
                                <h3 class="font-semibold text-gray-900 text-lg mb-1">
                                    <a href="<?php echo e(route('projects.show', $budget->project)); ?>" class="hover:text-indigo-600 transition-colors">
                                        <?php echo e($budget->project->name); ?>

                                    </a>
                                </h3>
                                <p class="text-sm text-gray-600"><?php echo e($budget->project->code); ?></p>
                                <?php if($budget->project->os_number): ?>
                                    <p class="text-sm text-green-600 font-medium">OS: <?php echo e($budget->project->os_number); ?></p>
                                <?php endif; ?>
                            <?php elseif($budget->client): ?>
                                <h3 class="font-semibold text-gray-900 text-lg mb-1">
                                    <?php echo e($budget->client->name); ?>

                                </h3>
                                <p class="text-sm text-gray-600"><?php echo e($budget->client->email); ?></p>
                                <p class="text-xs text-yellow-600 font-medium">Aguardando aprovação</p>
                            <?php else: ?>
                                <h3 class="font-semibold text-gray-900 text-lg mb-1">Cliente não especificado</h3>
                            <?php endif; ?>
                        </div>

                        <!-- Budget Details -->
                        <div class="space-y-2 mb-4">
                            <div class="flex justify-between text-sm">
                                <span class="text-gray-600">Subtotal:</span>
                                <span class="font-medium">R$ <?php echo e(number_format($budget->subtotal, 2, ',', '.')); ?></span>
                            </div>
                            <?php if($budget->discount > 0): ?>
                                <div class="flex justify-between text-sm">
                                    <span class="text-gray-600">Desconto:</span>
                                    <span class="text-red-600">-R$ <?php echo e(number_format($budget->discount, 2, ',', '.')); ?></span>
                                </div>
                            <?php endif; ?>
                            <div class="flex justify-between text-sm font-semibold border-t pt-2">
                                <span class="text-gray-900">Total:</span>
                                <span class="text-indigo-600">R$ <?php echo e(number_format($budget->total, 2, ',', '.')); ?></span>
                            </div>
                        </div>

                        <!-- Timestamps -->
                        <div class="text-xs text-gray-500 mb-4">
                            <div>Criado: <?php echo e($budget->created_at->format('d/m/Y H:i')); ?></div>
                            <?php if($budget->approved_at): ?>
                                <div>Aprovado: <?php echo e($budget->approved_at->format('d/m/Y H:i')); ?></div>
                            <?php endif; ?>
                        </div>

                        <!-- Actions -->
                        <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('manage budgets')): ?>
                        <div class="space-y-2">
                            <div class="flex space-x-2">
                                <a href="<?php echo e(route('budgets.edit', $budget)); ?>" 
                                   class="action-button flex-1 text-center px-3 py-2 text-sm bg-indigo-600 text-white rounded-md hover:bg-indigo-700 transition-colors">
                                    Editar
                                </a>
                                <a href="<?php echo e(route('budgets.pdf', $budget)); ?>" 
                                   class="action-button px-3 py-2 text-sm bg-gray-600 text-white rounded-md hover:bg-gray-700 transition-colors"
                                   target="_blank" title="Baixar PDF">
                                    📄
                                </a>
                            </div>
                            
                            <?php if($budget->status !== 'approved' && $budget->status !== 'cancelled'): ?>
                            <div class="flex space-x-1">
                                <?php if($budget->status !== 'approved'): ?>
                                <form action="<?php echo e(route('budgets.approve', $budget)); ?>" method="POST" class="flex-1">
                                    <?php echo csrf_field(); ?>
                                    <?php echo method_field('PATCH'); ?>
                                    <button type="submit" 
                                            class="action-button w-full px-2 py-1 text-xs bg-green-600 text-white rounded hover:bg-green-700 transition-colors"
                                            onclick="return confirm('Aprovar este orçamento? Isso gerará um número de OS automaticamente.')">
                                        Aprovar
                                    </button>
                                </form>
                                <?php endif; ?>
                                
                                <?php if($budget->status !== 'rejected'): ?>
                                <form action="<?php echo e(route('budgets.reject', $budget)); ?>" method="POST" class="flex-1">
                                    <?php echo csrf_field(); ?>
                                    <?php echo method_field('PATCH'); ?>
                                    <button type="submit" 
                                            class="action-button w-full px-2 py-1 text-xs bg-orange-600 text-white rounded hover:bg-orange-700 transition-colors"
                                            onclick="return confirm('Rejeitar este orçamento?')">
                                        Rejeitar
                                    </button>
                                </form>
                                <?php endif; ?>
                                
                                <form action="<?php echo e(route('budgets.cancel', $budget)); ?>" method="POST" class="flex-1">
                                    <?php echo csrf_field(); ?>
                                    <?php echo method_field('PATCH'); ?>
                                    <button type="submit" 
                                            class="action-button w-full px-2 py-1 text-xs bg-red-600 text-white rounded hover:bg-red-700 transition-colors"
                                            onclick="return confirm('Cancelar este orçamento?')">
                                        Cancelar
                                    </button>
                                </form>
                            </div>
                            <?php endif; ?>
                        </div>
                        <?php endif; ?>
                    </div>
                </div>
            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                <div class="col-span-full">
                    <div class="text-center py-12">
                        <div class="text-gray-400 text-6xl mb-4">📋</div>
                        <h3 class="text-lg font-medium text-gray-900 mb-2">Nenhum orçamento encontrado</h3>
                        <p class="text-gray-600 mb-4">Comece criando seu primeiro orçamento.</p>
                        <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('manage budgets')): ?>
                        <a href="<?php echo e(route('budgets.create')); ?>" 
                           class="inline-flex items-center px-4 py-2 bg-indigo-600 text-white rounded-md hover:bg-indigo-700">
                            <i class="bi bi-plus-circle mr-2"></i>
                            Criar Orçamento
                        </a>
                        <?php endif; ?>
                    </div>
                </div>
            <?php endif; ?>
        </div>

        <!-- Pagination -->
        <?php if($budgets->hasPages()): ?>
            <div class="mt-6"><?php echo e($budgets->links()); ?></div>
        <?php endif; ?>

        <!-- Optional: Toggle between grid and table view -->
        <div class="mt-8 border-t pt-6">
            <details class="group">
                <summary class="cursor-pointer text-sm font-medium text-gray-700 hover:text-gray-900">
                    Ver como tabela
                </summary>
                <div class="mt-4 bg-white shadow overflow-hidden sm:rounded-md">
                    <table class="min-w-full divide-y divide-gray-200">
                        <thead class="bg-gray-50">
                            <tr>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Obra</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Versão</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Status</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Total</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Criado em</th>
                                <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('manage budgets')): ?>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Ações</th>
                                <?php endif; ?>
                            </tr>
                        </thead>
                        <tbody class="bg-white divide-y divide-gray-200">
                            <?php $__currentLoopData = $budgets; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $budget): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                            <tr class="hover:bg-gray-50">
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <?php if($budget->project): ?>
                                        <a href="<?php echo e(route('projects.show', $budget->project)); ?>" class="text-sm font-medium text-indigo-600 hover:text-indigo-900">
                                            <?php echo e($budget->project->name); ?>

                                        </a>
                                    <?php elseif($budget->client): ?>
                                        <span class="text-sm font-medium text-gray-700"><?php echo e($budget->client->name); ?></span>
                                    <?php else: ?>
                                        <span class="text-sm font-medium text-gray-500">N/A</span>
                                    <?php endif; ?>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                    v<?php echo e($budget->version); ?>

                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <span class="px-2 py-1 text-xs font-semibold rounded-full border <?php echo e($budget->status_color); ?>">
                                        <?php echo e($budget->status_label); ?>

                                    </span>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">
                                    R$ <?php echo e(number_format($budget->total, 2, ',', '.')); ?>

                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                    <?php echo e($budget->created_at->format('d/m/Y H:i')); ?>

                                </td>
                                <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('manage budgets')): ?>
                                <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                                    <a href="<?php echo e(route('budgets.edit', $budget)); ?>" class="text-indigo-600 hover:text-indigo-900">Editar</a>
                                </td>
                                <?php endif; ?>
                            </tr>
                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                        </tbody>
                    </table>
                </div>
            </details>
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

<?php /**PATH C:\Users\Douglas\Documents\Projetos\stock-master\resources\views/budgets/index.blade.php ENDPATH**/ ?>