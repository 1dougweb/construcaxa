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
                <?php echo e(__('Detalhes da Conta a Pagar')); ?>

            </h2>
            <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('manage finances')): ?>
            <div class="flex space-x-2">
                <a href="<?php echo e(route('financial.accounts-payable.edit', $accountPayable)); ?>" class="inline-flex items-center px-4 py-2 bg-indigo-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-indigo-700">
                    Editar
                </a>
                <form method="POST" action="<?php echo e(route('financial.accounts-payable.destroy', $accountPayable)); ?>" class="inline">
                    <?php echo csrf_field(); ?>
                    <?php echo method_field('DELETE'); ?>
                    <button type="submit" onclick="return confirm('Tem certeza que deseja excluir esta conta a pagar?')" class="inline-flex items-center px-4 py-2 bg-red-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-red-700">
                        Excluir
                    </button>
                </form>
            </div>
            <?php endif; ?>
        </div>
     <?php $__env->endSlot(); ?>

    <div class="py-6">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-xl sm:rounded-lg">
                <div class="p-6 lg:p-8 bg-white border-b border-gray-200">
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div>
                            <label class="block text-sm font-medium text-gray-700">Número</label>
                            <p class="mt-1 text-sm text-gray-900"><?php echo e($accountPayable->number); ?></p>
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-gray-700">Status</label>
                            <span class="mt-1 px-2 inline-flex text-xs leading-5 font-semibold rounded-full <?php echo e($accountPayable->status_color); ?>">
                                <?php echo e($accountPayable->status_label); ?>

                            </span>
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-gray-700">Fornecedor</label>
                            <p class="mt-1 text-sm text-gray-900"><?php echo e(optional($accountPayable->supplier)->company_name ?? '-'); ?></p>
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-gray-700">Obra</label>
                            <p class="mt-1 text-sm text-gray-900"><?php echo e(optional($accountPayable->project)->name ?? '-'); ?></p>
                        </div>

                        <div class="md:col-span-2">
                            <label class="block text-sm font-medium text-gray-700">Descrição</label>
                            <p class="mt-1 text-sm text-gray-900"><?php echo e($accountPayable->description); ?></p>
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-gray-700">Categoria</label>
                            <p class="mt-1 text-sm text-gray-900"><?php echo e($accountPayable->category ?? '-'); ?></p>
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-gray-700">Valor</label>
                            <p class="mt-1 text-lg font-semibold text-gray-900">R$ <?php echo e(number_format($accountPayable->amount, 2, ',', '.')); ?></p>
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-gray-700">Data de Vencimento</label>
                            <p class="mt-1 text-sm text-gray-900"><?php echo e($accountPayable->due_date->format('d/m/Y')); ?></p>
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-gray-700">Data de Pagamento</label>
                            <p class="mt-1 text-sm text-gray-900"><?php echo e($accountPayable->paid_date ? $accountPayable->paid_date->format('d/m/Y') : '-'); ?></p>
                        </div>

                        <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($accountPayable->notes): ?>
                        <div class="md:col-span-2">
                            <label class="block text-sm font-medium text-gray-700">Observações</label>
                            <p class="mt-1 text-sm text-gray-900"><?php echo e($accountPayable->notes); ?></p>
                        </div>
                        <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>

                        <div>
                            <label class="block text-sm font-medium text-gray-700">Criado por</label>
                            <p class="mt-1 text-sm text-gray-900"><?php echo e(optional($accountPayable->user)->name ?? '-'); ?></p>
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-gray-700">Criado em</label>
                            <p class="mt-1 text-sm text-gray-900"><?php echo e($accountPayable->created_at->format('d/m/Y H:i')); ?></p>
                        </div>
                    </div>

                    <div class="mt-6">
                        <a href="<?php echo e(route('financial.accounts-payable.index')); ?>" class="text-indigo-600 hover:text-indigo-900">
                            ← Voltar para a lista
                        </a>
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

<?php /**PATH C:\Users\Douglas\Documents\Projetos\stock-master\resources\views/financial/accounts-payable/show.blade.php ENDPATH**/ ?>