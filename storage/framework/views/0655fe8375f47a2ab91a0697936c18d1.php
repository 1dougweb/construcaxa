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
            <?php echo e(__('Novo Serviço')); ?>

        </h2>
     <?php $__env->endSlot(); ?>

    <div class="p-4">
        <div class="max-w-2xl mx-auto">
            <form action="<?php echo e(route('services.store')); ?>" method="POST">
                <?php echo csrf_field(); ?>

                <div class="bg-white shadow rounded-md p-6 space-y-6">
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <!-- Name -->
                        <div class="md:col-span-2">
                            <label class="block text-sm font-medium text-gray-700 mb-1">Nome do Serviço *</label>
                            <input type="text" name="name" value="<?php echo e(old('name')); ?>" required 
                                   class="w-full border-gray-300 rounded-md" 
                                   placeholder="Ex: Instalação Elétrica">
                            <?php $__errorArgs = ['name'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?><p class="text-red-500 text-xs mt-1"><?php echo e($message); ?></p><?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                        </div>

                        <!-- Category -->
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Categoria *</label>
                            <select name="category_id" required class="w-full border-gray-300 rounded-md">
                                <option value="">Selecione uma categoria</option>
                                <?php $__currentLoopData = $categories; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $category): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                    <option value="<?php echo e($category->id); ?>" <?php echo e(old('category_id') == $category->id ? 'selected' : ''); ?>>
                                        <?php echo e($category->name); ?>

                                    </option>
                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                            </select>
                            <?php $__errorArgs = ['category_id'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?><p class="text-red-500 text-xs mt-1"><?php echo e($message); ?></p><?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                        </div>

                        <!-- Unit Type -->
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Tipo de Cobrança *</label>
                            <select name="unit_type" required class="w-full border-gray-300 rounded-md" onchange="updatePriceLabel()">
                                <?php $__currentLoopData = $unitTypes; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $value => $label): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                    <option value="<?php echo e($value); ?>" <?php echo e(old('unit_type', 'hour') == $value ? 'selected' : ''); ?>>
                                        <?php echo e($label); ?>

                                    </option>
                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                            </select>
                            <?php $__errorArgs = ['unit_type'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?><p class="text-red-500 text-xs mt-1"><?php echo e($message); ?></p><?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                        </div>

                        <!-- Default Price -->
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">
                                <span id="price-label">Preço Padrão (R$) *</span>
                            </label>
                            <input type="number" name="default_price" value="<?php echo e(old('default_price')); ?>" 
                                   step="0.01" min="0" required class="w-full border-gray-300 rounded-md">
                            <?php $__errorArgs = ['default_price'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?><p class="text-red-500 text-xs mt-1"><?php echo e($message); ?></p><?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                        </div>

                        <!-- Minimum Price -->
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Preço Mínimo (R$)</label>
                            <input type="number" name="minimum_price" value="<?php echo e(old('minimum_price')); ?>" 
                                   step="0.01" min="0" class="w-full border-gray-300 rounded-md">
                            <?php $__errorArgs = ['minimum_price'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?><p class="text-red-500 text-xs mt-1"><?php echo e($message); ?></p><?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                        </div>

                        <!-- Maximum Price -->
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Preço Máximo (R$)</label>
                            <input type="number" name="maximum_price" value="<?php echo e(old('maximum_price')); ?>" 
                                   step="0.01" min="0" class="w-full border-gray-300 rounded-md">
                            <?php $__errorArgs = ['maximum_price'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?><p class="text-red-500 text-xs mt-1"><?php echo e($message); ?></p><?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                        </div>
                    </div>

                    <!-- Description -->
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Descrição</label>
                        <textarea name="description" rows="3" class="w-full border-gray-300 rounded-md" 
                                  placeholder="Descreva o serviço, incluindo o que está incluído..."><?php echo e(old('description')); ?></textarea>
                        <?php $__errorArgs = ['description'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?><p class="text-red-500 text-xs mt-1"><?php echo e($message); ?></p><?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                    </div>

                    <!-- Active Status -->
                    <div class="flex items-center">
                        <input type="checkbox" name="is_active" id="is_active" value="1" 
                               <?php echo e(old('is_active', true) ? 'checked' : ''); ?> class="rounded border-gray-300">
                        <label for="is_active" class="ml-2 text-sm text-gray-700">Serviço ativo</label>
                    </div>

                    <!-- Buttons -->
                    <div class="flex justify-end gap-3 pt-4 border-t">
                        <a href="<?php echo e(route('services.index')); ?>" 
                           class="px-4 py-2 border border-gray-300 rounded-md text-gray-700 hover:bg-gray-50">
                            Cancelar
                        </a>
                        <button type="submit" 
                                class="px-4 py-2 bg-indigo-600 text-white rounded-md hover:bg-indigo-700">
                            Criar Serviço
                        </button>
                    </div>
                </div>
            </form>
        </div>
    </div>

    <?php $__env->startPush('scripts'); ?>
    <script>
        function updatePriceLabel() {
            const unitType = document.querySelector('[name="unit_type"]').value;
            const label = document.getElementById('price-label');
            
            switch(unitType) {
                case 'hour':
                    label.textContent = 'Preço por Hora (R$) *';
                    break;
                case 'fixed':
                    label.textContent = 'Preço Fixo (R$) *';
                    break;
                case 'per_unit':
                    label.textContent = 'Preço por Unidade (R$) *';
                    break;
                default:
                    label.textContent = 'Preço Padrão (R$) *';
            }
        }

        // Update label on page load
        document.addEventListener('DOMContentLoaded', updatePriceLabel);
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
<?php /**PATH C:\Users\drdes\OneDrive\Documentos\Projetos\2025\Novembro\construcaxa\resources\views/services/create.blade.php ENDPATH**/ ?>