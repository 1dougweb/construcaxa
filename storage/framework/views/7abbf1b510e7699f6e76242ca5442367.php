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
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                <?php echo e($equipment->name); ?>

            </h2>
            <div class="flex items-center space-x-4">
                <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('edit products')): ?>
                <a href="<?php echo e(route('equipment.edit', $equipment)); ?>" class="inline-flex items-center px-4 py-2 bg-indigo-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-indigo-700 focus:bg-indigo-700 active:bg-indigo-900 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 transition ease-in-out duration-150">
                    <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                    </svg>
                    <?php echo e(__('Editar')); ?>

                </a>
                <?php endif; ?>
                <a href="<?php echo e(route('equipment.history', $equipment)); ?>" class="inline-flex items-center px-4 py-2 bg-gray-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-gray-700 focus:bg-gray-700 active:bg-gray-900 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 transition ease-in-out duration-150">
                    <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                    </svg>
                    <?php echo e(__('Histórico')); ?>

                </a>
                <a href="<?php echo e(route('equipment.index')); ?>" class="inline-flex items-center px-4 py-2 bg-gray-800 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-gray-700 focus:bg-gray-700 active:bg-gray-900 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 transition ease-in-out duration-150">
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
                <!-- Informações Principais -->
                <div class="lg:col-span-2 bg-white overflow-hidden shadow-xl sm:rounded-lg">
                    <div class="p-6 lg:p-8 bg-white border-b border-gray-200">
                        <!-- Fotos -->
                        <?php if($equipment->photos && count($equipment->photos) > 0): ?>
                        <div class="mb-6">
                            <h3 class="text-lg font-semibold text-gray-900 mb-3">Fotos</h3>
                            <div class="grid grid-cols-2 md:grid-cols-3 gap-4">
                                <?php $__currentLoopData = $equipment->photos; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $photo): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                    <div class="aspect-w-1 aspect-h-1">
                                        <img src="<?php echo e(asset('storage/' . $photo)); ?>" 
                                             alt="<?php echo e($equipment->name); ?>" 
                                             class="w-full h-32 object-cover rounded-lg cursor-pointer hover:opacity-75"
                                             onclick="openImageModal('<?php echo e(asset('storage/' . $photo)); ?>')">
                                    </div>
                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                            </div>
                        </div>
                        <?php endif; ?>

                        <!-- Informações Básicas -->
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">
                            <div>
                                <label class="block text-sm font-medium text-gray-700">Nome</label>
                                <p class="mt-1 text-sm text-gray-900"><?php echo e($equipment->name); ?></p>
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-700">Número de Série</label>
                                <p class="mt-1 text-sm text-gray-900"><?php echo e($equipment->serial_number); ?></p>
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-700">Status</label>
                                <span class="mt-1 px-2 inline-flex text-xs leading-5 font-semibold rounded-full 
                                    <?php switch($equipment->status):
                                        case ('available'): ?> bg-green-100 text-green-800 <?php break; ?>
                                        <?php case ('borrowed'): ?> bg-yellow-100 text-yellow-800 <?php break; ?>
                                        <?php case ('maintenance'): ?> bg-red-100 text-red-800 <?php break; ?>
                                        <?php case ('retired'): ?> bg-gray-100 text-gray-800 <?php break; ?>
                                    <?php endswitch; ?>">
                                    <?php switch($equipment->status):
                                        case ('available'): ?> Disponível <?php break; ?>
                                        <?php case ('borrowed'): ?> Emprestado <?php break; ?>
                                        <?php case ('maintenance'): ?> Manutenção <?php break; ?>
                                        <?php case ('retired'): ?> Aposentado <?php break; ?>
                                    <?php endswitch; ?>
                                </span>
                            </div>
                            <?php if($equipment->category): ?>
                            <div>
                                <label class="block text-sm font-medium text-gray-700">Categoria</label>
                                <p class="mt-1 text-sm text-gray-900"><?php echo e($equipment->category->name); ?></p>
                            </div>
                            <?php endif; ?>
                        </div>

                        <?php if($equipment->currentEmployee): ?>
                        <div class="mb-6">
                            <label class="block text-sm font-medium text-gray-700">Funcionário Atual</label>
                            <div class="mt-1 flex items-center">
                                <?php if($equipment->currentEmployee->user && $equipment->currentEmployee->user->profile_photo): ?>
                                    <img src="<?php echo e(asset('storage/' . $equipment->currentEmployee->user->profile_photo)); ?>" 
                                         alt="<?php echo e($equipment->currentEmployee->name); ?>" 
                                         class="h-8 w-8 rounded-full object-cover mr-3">
                                <?php else: ?>
                                    <div class="h-8 w-8 rounded-full bg-indigo-500 flex items-center justify-center text-white font-semibold text-xs mr-3">
                                        <?php echo e(strtoupper(substr($equipment->currentEmployee->name, 0, 1))); ?>

                                    </div>
                                <?php endif; ?>
                                <div>
                                    <p class="text-sm font-medium text-gray-900"><?php echo e($equipment->currentEmployee->name); ?></p>
                                    <?php if($equipment->currentEmployee->department): ?>
                                    <p class="text-xs text-gray-500"><?php echo e($equipment->currentEmployee->department); ?></p>
                                    <?php endif; ?>
                                </div>
                            </div>
                        </div>
                        <?php endif; ?>

                        <?php if($equipment->description): ?>
                        <div class="mb-6">
                            <label class="block text-sm font-medium text-gray-700">Descrição</label>
                            <p class="mt-1 text-sm text-gray-900"><?php echo e($equipment->description); ?></p>
                        </div>
                        <?php endif; ?>

                        <?php if($equipment->purchase_price || $equipment->purchase_date): ?>
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">
                            <?php if($equipment->purchase_price): ?>
                            <div>
                                <label class="block text-sm font-medium text-gray-700">Preço de Compra</label>
                                <p class="mt-1 text-sm text-gray-900">R$ <?php echo e(number_format($equipment->purchase_price, 2, ',', '.')); ?></p>
                            </div>
                            <?php endif; ?>
                            <?php if($equipment->purchase_date): ?>
                            <div>
                                <label class="block text-sm font-medium text-gray-700">Data de Compra</label>
                                <p class="mt-1 text-sm text-gray-900"><?php echo e($equipment->purchase_date->format('d/m/Y')); ?></p>
                            </div>
                            <?php endif; ?>
                        </div>
                        <?php endif; ?>

                        <?php if($equipment->notes): ?>
                        <div class="mb-6">
                            <label class="block text-sm font-medium text-gray-700">Observações</label>
                            <p class="mt-1 text-sm text-gray-900"><?php echo e($equipment->notes); ?></p>
                        </div>
                        <?php endif; ?>

                        <!-- Movimentações Recentes -->
                        <div>
                            <h3 class="text-lg font-semibold text-gray-900 mb-3">Movimentações Recentes</h3>
                            <?php if($equipment->movements->count() > 0): ?>
                                <div class="overflow-x-auto">
                                    <table class="min-w-full divide-y divide-gray-200">
                                        <thead class="bg-gray-50">
                                            <tr>
                                                <th class="px-4 py-2 text-left text-xs font-medium text-gray-500 uppercase">Data</th>
                                                <th class="px-4 py-2 text-left text-xs font-medium text-gray-500 uppercase">Tipo</th>
                                                <th class="px-4 py-2 text-left text-xs font-medium text-gray-500 uppercase">Funcionário</th>
                                                <th class="px-4 py-2 text-left text-xs font-medium text-gray-500 uppercase">Observações</th>
                                            </tr>
                                        </thead>
                                        <tbody class="bg-white divide-y divide-gray-200">
                                            <?php $__currentLoopData = $equipment->movements->take(5); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $movement): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                                <tr>
                                                    <td class="px-4 py-2 text-sm text-gray-900"><?php echo e($movement->created_at->format('d/m/Y H:i')); ?></td>
                                                    <td class="px-4 py-2">
                                                        <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full 
                                                            <?php echo e($movement->type === 'loan' ? 'bg-yellow-100 text-yellow-800' : 'bg-green-100 text-green-800'); ?>">
                                                            <?php echo e($movement->type === 'loan' ? 'Empréstimo' : 'Devolução'); ?>

                                                        </span>
                                                    </td>
                                                    <td class="px-4 py-2 text-sm text-gray-900"><?php echo e($movement->employee->name); ?></td>
                                                    <td class="px-4 py-2 text-sm text-gray-500"><?php echo e($movement->notes ?: '-'); ?></td>
                                                </tr>
                                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                        </tbody>
                                    </table>
                                </div>
                                <?php if($equipment->movements->count() > 5): ?>
                                    <div class="mt-3 text-center">
                                        <a href="<?php echo e(route('equipment.history', $equipment)); ?>" class="text-indigo-600 hover:text-indigo-900 text-sm font-medium">
                                            Ver histórico completo →
                                        </a>
                                    </div>
                                <?php endif; ?>
                            <?php else: ?>
                                <p class="text-sm text-gray-500">Nenhuma movimentação registrada.</p>
                            <?php endif; ?>
                        </div>
                    </div>
                </div>

                <!-- Sidebar -->
                <div class="space-y-6">
                    <!-- Ações Rápidas -->
                    <div class="bg-white overflow-hidden shadow-xl sm:rounded-lg">
                        <div class="p-6 lg:p-8 bg-white border-b border-gray-200">
                            <h3 class="text-lg font-semibold text-gray-900 mb-4">Ações Rápidas</h3>
                            <div class="space-y-3">
                                <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('create service-orders')): ?>
                                    <?php if($equipment->isAvailable()): ?>
                                    <a href="<?php echo e(route('equipment-requests.create')); ?>?equipment=<?php echo e($equipment->id); ?>" 
                                       class="w-full inline-flex items-center justify-center px-4 py-2 bg-indigo-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-indigo-700 focus:bg-indigo-700 active:bg-indigo-900 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 transition ease-in-out duration-150">
                                        Solicitar Empréstimo
                                    </a>
                                    <?php elseif($equipment->isBorrowed()): ?>
                                    <a href="<?php echo e(route('equipment-requests.create')); ?>?equipment=<?php echo e($equipment->id); ?>&type=return" 
                                       class="w-full inline-flex items-center justify-center px-4 py-2 bg-green-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-green-700 focus:bg-green-700 active:bg-green-900 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 transition ease-in-out duration-150">
                                        Solicitar Devolução
                                    </a>
                                    <?php endif; ?>
                                <?php endif; ?>
                                <a href="<?php echo e(route('equipment.history', $equipment)); ?>" 
                                   class="w-full inline-flex items-center justify-center px-4 py-2 bg-gray-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-gray-700 focus:bg-gray-700 active:bg-gray-900 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 transition ease-in-out duration-150">
                                    Ver Histórico Completo
                                </a>
                            </div>
                        </div>
                    </div>

                    <!-- Informações do Sistema -->
                    <div class="bg-white overflow-hidden shadow-xl sm:rounded-lg">
                        <div class="p-6 lg:p-8 bg-white border-b border-gray-200">
                            <h3 class="text-lg font-semibold text-gray-900 mb-4">Informações do Sistema</h3>
                            <div class="space-y-3">
                                <div>
                                    <label class="block text-sm font-medium text-gray-700">Cadastrado em</label>
                                    <p class="mt-1 text-sm text-gray-900"><?php echo e($equipment->created_at->format('d/m/Y H:i')); ?></p>
                                </div>
                                <div>
                                    <label class="block text-sm font-medium text-gray-700">Última atualização</label>
                                    <p class="mt-1 text-sm text-gray-900"><?php echo e($equipment->updated_at->format('d/m/Y H:i')); ?></p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Modal para visualizar imagens -->
    <div id="imageModal" class="fixed inset-0 bg-black bg-opacity-75 z-50 hidden flex items-center justify-center" onclick="closeImageModal()">
        <div class="max-w-4xl max-h-full p-4">
            <img id="modalImage" src="" alt="" class="max-w-full max-h-full object-contain">
        </div>
    </div>

    <?php $__env->startPush('scripts'); ?>
    <script>
        function openImageModal(src) {
            document.getElementById('modalImage').src = src;
            document.getElementById('imageModal').classList.remove('hidden');
        }

        function closeImageModal() {
            document.getElementById('imageModal').classList.add('hidden');
        }
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
<?php /**PATH C:\Users\Douglas\Documents\Projetos\stock-master\resources\views/equipment/show.blade.php ENDPATH**/ ?>