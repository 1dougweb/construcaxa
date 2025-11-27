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
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            <?php echo e(__('Novo Orçamento')); ?>

        </h2>
     <?php $__env->endSlot(); ?>

    <div class="py-6">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <form action="<?php echo e(route('budgets.store')); ?>" method="POST" id="budgetForm">
                <?php echo csrf_field(); ?>

                <div class="bg-white dark:bg-gray-800 shadow rounded-md p-6 space-y-6 border border-gray-200 dark:border-gray-700">
                <!-- Informações básicas -->
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div>
                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Cliente *</label>
                        <select name="client_id" id="client_id" required class="w-full border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-gray-300 rounded-md">
                            <option value="">Selecione um cliente</option>
                            <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php $__currentLoopData = $clients; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $client): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                <option value="<?php echo e($client->id); ?>" <?php echo e(old('client_id') == $client->id ? 'selected' : ''); ?>>
                                    <?php echo e($client->name); ?> (<?php echo e($client->email); ?>)
                                </option>
                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                        </select>
                        <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php $__errorArgs = ['client_id'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?><p class="text-red-500 dark:text-red-400 text-xs mt-1"><?php echo e($message); ?></p><?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Vistoria Técnica</label>
                        <select name="inspection_id" id="inspection_id" class="w-full border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-gray-300 rounded-md" disabled>
                            <option value="">Nenhuma vistoria selecionada</option>
                        </select>
                        <p class="text-xs text-gray-500 dark:text-gray-400 mt-1">Nota: Este campo está desabilitado. Use Vistorias Técnicas para criar novas vistorias.</p>
                        <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php $__errorArgs = ['inspection_id'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?><p class="text-red-500 dark:text-red-400 text-xs mt-1"><?php echo e($message); ?></p><?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Versão *</label>
                        <input type="number" name="version" value="<?php echo e(old('version', 1)); ?>" min="1" required class="w-full border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-gray-300 rounded-md">
                        <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php $__errorArgs = ['version'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?><p class="text-red-500 dark:text-red-400 text-xs mt-1"><?php echo e($message); ?></p><?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Status *</label>
                        <select name="status" required class="w-full border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-gray-300 rounded-md">
                            <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php $__currentLoopData = \App\Models\ProjectBudget::getStatusOptions(); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $value => $label): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                <option value="<?php echo e($value); ?>" <?php echo e(old('status', 'pending') == $value ? 'selected' : ''); ?>>
                                    <?php echo e($label); ?>

                                </option>
                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                        </select>
                        <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php $__errorArgs = ['status'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?><p class="text-red-500 dark:text-red-400 text-xs mt-1"><?php echo e($message); ?></p><?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Desconto (R$)</label>
                        <input type="number" name="discount" value="<?php echo e(old('discount', 0)); ?>" step="0.01" min="0" class="w-full border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-gray-300 rounded-md">
                        <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php $__errorArgs = ['discount'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?><p class="text-red-500 dark:text-red-400 text-xs mt-1"><?php echo e($message); ?></p><?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                    </div>
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Observações</label>
                    <textarea name="notes" rows="3" class="w-full border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-gray-300 rounded-md"><?php echo e(old('notes')); ?></textarea>
                    <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php $__errorArgs = ['notes'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?><p class="text-red-500 dark:text-red-400 text-xs mt-1"><?php echo e($message); ?></p><?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                </div>

                <!-- Itens do orçamento -->
                <div>
                    <div class="flex items-center justify-between mb-4">
                        <h3 class="text-lg font-medium text-gray-900 dark:text-gray-100">Itens do Orçamento</h3>
                        <div class="flex space-x-2">
                            <button type="button" onclick="addItem('product')" class="px-3 py-1 bg-blue-600 dark:bg-blue-700 text-white rounded-md text-sm hover:bg-blue-700 dark:hover:bg-blue-600">
                                <i class="bi bi-box mr-1"></i> Adicionar Produto
                            </button>
                            <button type="button" onclick="addItem('service')" class="px-3 py-1 bg-green-600 dark:bg-green-700 text-white rounded-md text-sm hover:bg-green-700 dark:hover:bg-green-600">
                                <i class="bi bi-tools mr-1"></i> Adicionar Serviço
                            </button>
                            <button type="button" onclick="addItem('labor')" class="px-3 py-1 bg-amber-400 dark:bg-amber-600 text-white rounded-md text-sm hover:bg-amber-500 dark:hover:bg-amber-700">
                                <i class="bi bi-people mr-1"></i> Adicionar Mão de Obra
                            </button>
                        </div>
                    </div>

                    <div id="itemsContainer" class="space-y-4">
                        <!-- Itens serão adicionados aqui via JavaScript -->
                    </div>
                    <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php $__errorArgs = ['items'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?><p class="text-red-500 dark:text-red-400 text-xs mt-1"><?php echo e($message); ?></p><?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                </div>

                <!-- Totais -->
                <div class="border-t border-gray-200 dark:border-gray-700 pt-4">
                    <div class="flex justify-end">
                        <div class="w-64 space-y-2">
                            <div class="flex justify-between">
                                <span class="text-gray-600 dark:text-gray-400">Subtotal:</span>
                                <span id="subtotal" class="font-medium text-gray-900 dark:text-gray-100">R$ 0,00</span>
                            </div>
                            <div class="flex justify-between">
                                <span class="text-gray-600 dark:text-gray-400">Desconto:</span>
                                <span id="discount-display" class="font-medium text-gray-900 dark:text-gray-100">R$ 0,00</span>
                            </div>
                            <div class="flex justify-between border-t border-gray-200 dark:border-gray-700 pt-2">
                                <span class="font-semibold text-gray-900 dark:text-gray-100">Total:</span>
                                <span id="total" class="font-semibold text-lg text-indigo-600 dark:text-indigo-400">R$ 0,00</span>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Botões -->
                <div class="flex justify-end gap-3 pt-4 border-t border-gray-200 dark:border-gray-700">
                    <a href="<?php echo e(route('budgets.index')); ?>" class="px-4 py-2 border border-gray-300 dark:border-gray-600 rounded-md text-gray-700 dark:text-gray-300 hover:bg-gray-50 dark:hover:bg-gray-700">
                        Cancelar
                    </a>
                    <?php if (isset($component)) { $__componentOriginal635944f67ec1864e436b88f435140e07 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal635944f67ec1864e436b88f435140e07 = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.button-loading','data' => []] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('button-loading'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes([]); ?>
                        Salvar Orçamento
                     <?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginal635944f67ec1864e436b88f435140e07)): ?>
<?php $attributes = $__attributesOriginal635944f67ec1864e436b88f435140e07; ?>
<?php unset($__attributesOriginal635944f67ec1864e436b88f435140e07); ?>
<?php endif; ?>
<?php if (isset($__componentOriginal635944f67ec1864e436b88f435140e07)): ?>
<?php $component = $__componentOriginal635944f67ec1864e436b88f435140e07; ?>
<?php unset($__componentOriginal635944f67ec1864e436b88f435140e07); ?>
<?php endif; ?>
                </div>
                </div>
            </form>
        </div>
    </div>

    <?php
        $products = \App\Models\Product::orderBy('name')->get();
        
        // Map products for JavaScript
        $productsData = $products->map(function($p) {
            $photos = $p->photos ?? [];
            $firstPhoto = null;
            if (is_array($photos) && count($photos) > 0) {
                $firstPhoto = $photos[0];
            }
            return [
                'id' => $p->id, 
                'name' => $p->name,
                'sku' => $p->sku ?? '',
                'price' => $p->price ?? 0,
                'photo' => $firstPhoto
            ];
        })->values();
        
        // Map services for JavaScript (using data from controller)
        $servicesData = $services->map(function($s) {
            return [
                'id' => $s->id,
                'name' => $s->name,
                'description' => $s->description,
                'category' => $s->category ? $s->category->name : '',
                'unit_type' => $s->unit_type ?? 'hour',
                'unit_type_label' => $s->unit_type_label ?? 'Por Hora',
                'default_price' => $s->default_price ?? 0,
                'minimum_price' => $s->minimum_price ?? null,
                'maximum_price' => $s->maximum_price ?? null
            ];
        })->values();
        
        // Map labor types for JavaScript (using data from controller)
        $laborTypesData = $laborTypes->map(function($l) {
            return [
                'id' => $l->id,
                'name' => $l->name,
                'description' => $l->description,
                'skill_level' => $l->skill_level ?? 'junior',
                'skill_level_label' => $l->skill_level_label ?? 'Júnior',
                'hourly_rate' => $l->hourly_rate ?? 0,
                'overtime_rate' => $l->overtime_rate ?? 0
            ];
        })->values();
    ?>

    <?php $__env->startPush('scripts'); ?>
    <script>
        let itemIndex = 0;
        const products = <?php echo json_encode($productsData, 15, 512) ?>;
        const services = <?php echo json_encode($servicesData, 15, 512) ?>;
        const laborTypes = <?php echo json_encode($laborTypesData, 15, 512) ?>;

        function addItem(itemType = 'product', item = null) {
            const container = document.getElementById('itemsContainer');
            const index = itemIndex++;
            
            let itemHtml = '';
            const itemTypeValue = item ? item.item_type || itemType : itemType;
            
            if (itemTypeValue === 'product') {
                itemHtml = createProductItem(index, item);
            } else if (itemTypeValue === 'service') {
                itemHtml = createServiceItem(index, item);
            } else if (itemTypeValue === 'labor') {
                itemHtml = createLaborItem(index, item);
            }
            
            container.insertAdjacentHTML('beforeend', itemHtml);
            
            // Setup search for the new item
            const newItem = container.lastElementChild;
            if (itemTypeValue === 'product') {
                setupProductSearch(newItem);
            } else if (itemTypeValue === 'service') {
                setupServiceSearch(newItem);
            } else if (itemTypeValue === 'labor') {
                setupLaborSearch(newItem);
            }
        }

        function createProductItem(index, item = null) {
            return `
                <div class="border border-gray-200 dark:border-gray-700 rounded-md p-4 bg-blue-50 dark:bg-blue-900/20" data-item-index="${index}">
                    <div class="flex justify-between items-center mb-2">
                        <span class="text-sm font-medium text-blue-800 dark:text-blue-300"><i class="bi bi-box mr-1"></i> Produto</span>
                        <button type="button" onclick="removeItem(this)" class="text-red-600 dark:text-red-400 hover:text-red-800 dark:hover:text-red-300 text-sm">
                            <i class="bi bi-trash"></i> Remover
                        </button>
                    </div>
                    <input type="hidden" name="items[${index}][item_type]" value="product">
                    <div class="grid grid-cols-6 gap-3">
                        <div class="col-span-2">
                            <label class="block text-xs text-gray-600 dark:text-gray-400 mb-1">Produto</label>
                            <div class="relative">
                                <input type="text" 
                                       class="product-search w-full border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-gray-300 rounded-md text-sm" 
                                       placeholder="Buscar produto..."
                                       data-index="${index}">
                                <input type="hidden" name="items[${index}][product_id]" value="${item ? (item.product_id || '') : ''}">
                                <div class="product-results absolute z-10 w-full bg-white dark:bg-gray-800 border border-gray-300 dark:border-gray-700 rounded-md shadow-lg hidden max-h-64 overflow-y-auto mt-1"></div>
                            </div>
                        </div>
                        <div class="col-span-2">
                            <label class="block text-xs text-gray-600 dark:text-gray-400 mb-1">Descrição *</label>
                            <input type="text" name="items[${index}][description]" value="${item ? (item.description || '') : ''}" required class="w-full border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-gray-300 rounded-md text-sm">
                        </div>
                        <div>
                            <label class="block text-xs text-gray-600 dark:text-gray-400 mb-1">Quantidade *</label>
                            <input type="number" name="items[${index}][quantity]" value="${item ? (item.quantity || '') : ''}" step="0.001" min="0" required onchange="calculateTotals()" class="w-full border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-gray-300 rounded-md text-sm">
                        </div>
                        <div>
                            <label class="block text-xs text-gray-600 dark:text-gray-400 mb-1">Preço Unit. *</label>
                            <input type="number" name="items[${index}][unit_price]" value="${item ? (item.unit_price || '') : ''}" step="0.01" min="0" required onchange="calculateTotals()" class="w-full border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-gray-300 rounded-md text-sm">
                        </div>
                    </div>
                </div>
            `;
        }

        function createServiceItem(index, item = null) {
            return `
                <div class="border border-gray-200 dark:border-gray-700 rounded-md p-4 bg-green-50 dark:bg-green-900/20" data-item-index="${index}">
                    <div class="flex justify-between items-center mb-2">
                        <span class="text-sm font-medium text-green-800 dark:text-green-300"><i class="bi bi-tools mr-1"></i> Serviço</span>
                        <button type="button" onclick="removeItem(this)" class="text-red-600 dark:text-red-400 hover:text-red-800 dark:hover:text-red-300 text-sm">
                            <i class="bi bi-trash"></i> Remover
                        </button>
                    </div>
                    <input type="hidden" name="items[${index}][item_type]" value="service">
                    <div class="grid grid-cols-6 gap-3">
                        <div class="col-span-2">
                            <label class="block text-xs text-gray-600 dark:text-gray-400 mb-1">Serviço</label>
                            <div class="relative">
                                <input type="text" 
                                       class="service-search w-full border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-gray-300 rounded-md text-sm" 
                                       placeholder="Buscar serviço..."
                                       data-index="${index}">
                                <input type="hidden" name="items[${index}][service_id]" value="${item ? (item.service_id || '') : ''}">
                                <div class="service-results absolute z-10 w-full bg-white dark:bg-gray-800 border border-gray-300 dark:border-gray-700 rounded-md shadow-lg hidden max-h-48 overflow-y-auto mt-1"></div>
                            </div>
                        </div>
                        <div class="col-span-2">
                            <label class="block text-xs text-gray-600 dark:text-gray-400 mb-1">Descrição *</label>
                            <input type="text" name="items[${index}][description]" value="${item ? (item.description || '') : ''}" required class="w-full border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-gray-300 rounded-md text-sm">
                        </div>
                        <div>
                            <label class="block text-xs text-gray-600 dark:text-gray-400 mb-1">Quantidade *</label>
                            <input type="number" name="items[${index}][quantity]" value="${item ? (item.quantity || '') : ''}" step="0.001" min="0" required onchange="calculateTotals()" class="w-full border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-gray-300 rounded-md text-sm">
                        </div>
                        <div>
                            <label class="block text-xs text-gray-600 dark:text-gray-400 mb-1">Preço Unit. *</label>
                            <input type="number" name="items[${index}][unit_price]" value="${item ? (item.unit_price || '') : ''}" step="0.01" min="0" required onchange="calculateTotals()" class="w-full border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-gray-300 rounded-md text-sm">
                        </div>
                    </div>
                </div>
            `;
        }

        function createLaborItem(index, item = null) {
            return `
                <div class="border border-gray-200 dark:border-gray-700 rounded-md p-4 bg-purple-50 dark:bg-purple-900/20" data-item-index="${index}">
                    <div class="flex justify-between items-center mb-2">
                        <span class="text-sm font-medium text-purple-800 dark:text-purple-300"><i class="bi bi-people mr-1"></i> Mão de Obra</span>
                        <button type="button" onclick="removeItem(this)" class="text-red-600 dark:text-red-400 hover:text-red-800 dark:hover:text-red-300 text-sm">
                            <i class="bi bi-trash"></i> Remover
                        </button>
                    </div>
                    <input type="hidden" name="items[${index}][item_type]" value="labor">
                    <input type="hidden" name="items[${index}][unit_price]" value="${item ? (item.unit_price || '') : ''}" class="labor-unit-price">
                    <div class="grid grid-cols-1 md:grid-cols-6 gap-4">
                        <div class="md:col-span-2">
                            <label class="block text-xs text-gray-600 dark:text-gray-400 mb-1">Tipo de Mão de Obra</label>
                            <div class="relative">
                                <input type="text" 
                                       class="labor-search w-full border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-gray-300 rounded-md text-sm" 
                                       placeholder="Buscar tipo..."
                                       data-index="${index}">
                                <input type="hidden" name="items[${index}][labor_type_id]" value="${item ? (item.labor_type_id || '') : ''}">
                                <div class="labor-results absolute z-10 w-full bg-white dark:bg-gray-800 border border-gray-300 dark:border-gray-700 rounded-md shadow-lg hidden max-h-48 overflow-y-auto"></div>
                            </div>
                        </div>
                        <div class="md:col-span-2">
                            <label class="block text-xs text-gray-600 dark:text-gray-400 mb-1">Descrição *</label>
                            <input type="text" name="items[${index}][description]" value="${item ? (item.description || '') : ''}" required class="w-full border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-gray-300 rounded-md text-sm">
                        </div>
                        <div>
                            <label class="block text-xs text-gray-600 dark:text-gray-400 mb-1">Horas *</label>
                            <input type="number" name="items[${index}][hours]" value="${item ? (item.hours || '') : ''}" step="0.25" min="0" required onchange="calculateTotals()" class="w-full border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-gray-300 rounded-md text-sm">
                        </div>
                        <div>
                            <label class="block text-xs text-gray-600 dark:text-gray-400 mb-1">Horas Extra</label>
                            <input type="number" name="items[${index}][overtime_hours]" value="${item ? (item.overtime_hours || 0) : 0}" step="0.25" min="0" onchange="calculateTotals()" class="w-full border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-gray-300 rounded-md text-sm">
                        </div>
                    </div>
                </div>
            `;
        }

        function removeItem(button) {
            button.closest('[data-item-index]').remove();
            calculateTotals();
        }

        function calculateTotals() {
            const form = document.getElementById('budgetForm');
            const items = form.querySelectorAll('[data-item-index]');
            let subtotal = 0;

            items.forEach(item => {
                const itemType = item.querySelector('[name*="[item_type]"]')?.value || 'product';
                
                if (itemType === 'labor') {
                    const hours = parseFloat(item.querySelector('[name*="[hours]"]')?.value) || 0;
                    const overtimeHours = parseFloat(item.querySelector('[name*="[overtime_hours]"]')?.value) || 0;
                    const unitPrice = parseFloat(item.querySelector('[name*="[unit_price]"]')?.value) || 0;
                    const laborTypeId = item.querySelector('input[name*="[labor_type_id]"]')?.value;
                    
                    // Find labor type to get overtime rate
                    const laborType = laborTypes.find(lt => lt.id == laborTypeId);
                    const overtimeRate = laborType ? laborType.overtime_rate : unitPrice * 1.5;
                    
                    subtotal += (hours * unitPrice) + (overtimeHours * overtimeRate);
                } else if (itemType === 'service') {
                    const quantity = parseFloat(item.querySelector('[name*="[quantity]"]')?.value) || 0;
                    const unitPrice = parseFloat(item.querySelector('[name*="[unit_price]"]')?.value) || 0;
                    const serviceId = item.querySelector('input[name*="[service_id]"]')?.value;
                    
                    // Find service to get unit type
                    const service = services.find(s => s.id == serviceId);
                    if (service) {
                        // Calculate based on unit type
                        if (service.unit_type === 'fixed') {
                            // Fixed price regardless of quantity
                            subtotal += unitPrice;
                        } else {
                            // Per hour or per unit
                            subtotal += quantity * unitPrice;
                        }
                    } else {
                        // Fallback to simple calculation
                        subtotal += quantity * unitPrice;
                    }
                } else {
                    // Product
                    const quantity = parseFloat(item.querySelector('[name*="[quantity]"]')?.value) || 0;
                    const unitPrice = parseFloat(item.querySelector('[name*="[unit_price]"]')?.value) || 0;
                    subtotal += quantity * unitPrice;
                }
            });

            const discount = parseFloat(form.querySelector('[name="discount"]').value) || 0;
            const total = subtotal - discount;

            document.getElementById('subtotal').textContent = 'R$ ' + subtotal.toFixed(2).replace('.', ',');
            document.getElementById('discount-display').textContent = 'R$ ' + discount.toFixed(2).replace('.', ',');
            document.getElementById('total').textContent = 'R$ ' + total.toFixed(2).replace('.', ',');
        }

        // Product search functionality
        function setupProductSearch(container) {
            const searchInput = container.querySelector('.product-search');
            const hiddenInput = container.querySelector('input[type="hidden"]');
            const resultsDiv = container.querySelector('.product-results');
            
            let searchTimeout;
            
            searchInput.addEventListener('input', function() {
                const query = this.value.trim();
                
                clearTimeout(searchTimeout);
                
                if (query.length < 2) {
                    resultsDiv.classList.add('hidden');
                    return;
                }
                
                searchTimeout = setTimeout(() => {
                    // Filter products based on search query
                    const filteredProducts = products.filter(product => 
                        product.name.toLowerCase().includes(query.toLowerCase())
                    );
                    
                    if (filteredProducts.length > 0) {
                        let resultsHtml = '';
                        filteredProducts.slice(0, 10).forEach(product => {
                            const photoUrl = product.photo ? `/storage/${product.photo}` : null;
                            resultsHtml += `
                                <div class="product-option p-2 hover:bg-gray-100 dark:hover:bg-gray-700 cursor-pointer border-b border-gray-200 dark:border-gray-700 last:border-b-0 flex items-center gap-3" 
                                     data-id="${product.id}" 
                                     data-name="${product.name}"
                                     data-price="${product.price || 0}">
                                    ${photoUrl ? `
                                        <img src="${photoUrl}" alt="${product.name}" class="w-12 h-12 object-cover rounded border border-gray-200 dark:border-gray-700 flex-shrink-0">
                                    ` : `
                                        <div class="w-12 h-12 bg-gray-200 dark:bg-gray-700 rounded border border-gray-300 dark:border-gray-600 flex items-center justify-center flex-shrink-0">
                                            <svg class="w-6 h-6 text-gray-400 dark:text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                                            </svg>
                                        </div>
                                    `}
                                    <div class="flex-1 min-w-0">
                                        <div class="font-medium text-sm truncate text-gray-900 dark:text-gray-100">${product.name}</div>
                                        ${product.sku ? `<div class="text-xs text-gray-500 dark:text-gray-400">SKU: ${product.sku}</div>` : ''}
                                        <div class="text-xs font-semibold text-indigo-600 dark:text-indigo-400">R$ ${parseFloat(product.price || 0).toFixed(2).replace('.', ',')}</div>
                                    </div>
                                </div>
                            `;
                        });
                        resultsDiv.innerHTML = resultsHtml;
                        resultsDiv.classList.remove('hidden');
                        
                        // Add click handlers for results
                        resultsDiv.querySelectorAll('.product-option').forEach(option => {
                            option.addEventListener('click', function() {
                                const productId = this.dataset.id;
                                const productName = this.dataset.name;
                                const productPrice = parseFloat(this.dataset.price) || 0;
                                
                                searchInput.value = productName;
                                hiddenInput.value = productId;
                                resultsDiv.classList.add('hidden');
                                
                                // Auto-fill description, quantity and price
                                const descriptionInput = container.querySelector('input[name*="[description]"]');
                                const quantityInput = container.querySelector('input[name*="[quantity]"]');
                                const priceInput = container.querySelector('input[name*="[unit_price]"]');
                                
                                if (!descriptionInput.value) {
                                    descriptionInput.value = productName;
                                }
                                if (!quantityInput.value || quantityInput.value === '0') {
                                    quantityInput.value = '1';
                                }
                                if (!priceInput.value || priceInput.value === '0') {
                                    priceInput.value = productPrice.toFixed(2);
                                }
                                
                                calculateTotals();
                            });
                        });
                    } else {
                        resultsDiv.innerHTML = '<div class="p-2 text-gray-500 dark:text-gray-400 text-sm">Nenhum produto encontrado</div>';
                        resultsDiv.classList.remove('hidden');
                    }
                }, 300);
            });
            
            // Hide results when clicking outside
            document.addEventListener('click', function(e) {
                if (!container.contains(e.target)) {
                    resultsDiv.classList.add('hidden');
                }
            });
            
            // Set initial value if product is selected
            const initialProductId = hiddenInput.value;
            if (initialProductId) {
                const product = products.find(p => p.id == initialProductId);
                if (product) {
                    searchInput.value = product.name;
                }
            }
        }

        // Service search functionality
        function setupServiceSearch(container) {
            const searchInput = container.querySelector('.service-search');
            const hiddenInput = container.querySelector('input[name*="[service_id]"]');
            const resultsDiv = container.querySelector('.service-results');
            
            let searchTimeout;
            
            searchInput.addEventListener('input', function() {
                const query = this.value.trim();
                
                clearTimeout(searchTimeout);
                
                if (query.length < 2) {
                    resultsDiv.classList.add('hidden');
                    return;
                }
                
                searchTimeout = setTimeout(() => {
                    const filteredServices = services.filter(service => 
                        service.name.toLowerCase().includes(query.toLowerCase())
                    );
                    
                    if (filteredServices.length > 0) {
                        let resultsHtml = '';
                        filteredServices.slice(0, 10).forEach(service => {
                            const priceDisplay = service.unit_type === 'fixed' 
                                ? `R$ ${parseFloat(service.default_price).toFixed(2)} (Preço Fixo)`
                                : `R$ ${parseFloat(service.default_price).toFixed(2)}/${service.unit_type_label}`;
                            resultsHtml += `
                                <div class="service-option p-2 hover:bg-gray-100 dark:hover:bg-gray-700 cursor-pointer border-b border-gray-200 dark:border-gray-700 last:border-b-0" 
                                     data-id="${service.id}" 
                                     data-name="${service.name}" 
                                     data-price="${service.default_price}"
                                     data-unit-type="${service.unit_type}">
                                    <div class="font-medium text-sm text-gray-900 dark:text-gray-100">${service.name}</div>
                                    <div class="text-xs text-gray-500 dark:text-gray-400">${priceDisplay}</div>
                                    ${service.category ? `<div class="text-xs text-gray-400 dark:text-gray-500">${service.category}</div>` : ''}
                                </div>
                            `;
                        });
                        resultsDiv.innerHTML = resultsHtml;
                        resultsDiv.classList.remove('hidden');
                        
                        resultsDiv.querySelectorAll('.service-option').forEach(option => {
                            option.addEventListener('click', function() {
                                const serviceId = this.dataset.id;
                                const serviceName = this.dataset.name;
                                const servicePrice = this.dataset.price;
                                const unitType = this.dataset.unitType;
                                
                                searchInput.value = serviceName;
                                hiddenInput.value = serviceId;
                                resultsDiv.classList.add('hidden');
                                
                                const descriptionInput = container.querySelector('input[name*="[description]"]');
                                const priceInput = container.querySelector('input[name*="[unit_price]"]');
                                
                                if (!descriptionInput.value) {
                                    descriptionInput.value = serviceName;
                                }
                                if (!priceInput.value) {
                                    priceInput.value = servicePrice;
                                }
                                
                                calculateTotals();
                            });
                        });
                    } else {
                        resultsDiv.innerHTML = '<div class="p-2 text-gray-500 dark:text-gray-400 text-sm">Nenhum serviço encontrado</div>';
                        resultsDiv.classList.remove('hidden');
                    }
                }, 300);
            });
            
            document.addEventListener('click', function(e) {
                if (!container.contains(e.target)) {
                    resultsDiv.classList.add('hidden');
                }
            });
        }

        // Labor search functionality
        function setupLaborSearch(container) {
            const searchInput = container.querySelector('.labor-search');
            const hiddenInput = container.querySelector('input[name*="[labor_type_id]"]');
            const resultsDiv = container.querySelector('.labor-results');
            
            let searchTimeout;
            
            searchInput.addEventListener('input', function() {
                const query = this.value.trim();
                
                clearTimeout(searchTimeout);
                
                if (query.length < 2) {
                    resultsDiv.classList.add('hidden');
                    return;
                }
                
                searchTimeout = setTimeout(() => {
                    const filteredLaborTypes = laborTypes.filter(laborType => 
                        laborType.name.toLowerCase().includes(query.toLowerCase())
                    );
                    
                    if (filteredLaborTypes.length > 0) {
                        let resultsHtml = '';
                        filteredLaborTypes.slice(0, 10).forEach(laborType => {
                            resultsHtml += `
                                <div class="labor-option p-2 hover:bg-gray-100 dark:hover:bg-gray-700 cursor-pointer border-b border-gray-200 dark:border-gray-700 last:border-b-0" 
                                     data-id="${laborType.id}" 
                                     data-name="${laborType.name}" 
                                     data-rate="${laborType.hourly_rate}"
                                     data-overtime-rate="${laborType.overtime_rate}">
                                    <div class="font-medium text-sm text-gray-900 dark:text-gray-100">${laborType.name}</div>
                                    <div class="text-xs text-gray-500 dark:text-gray-400">
                                        Normal: R$ ${parseFloat(laborType.hourly_rate).toFixed(2)}/h | 
                                        Extra: R$ ${parseFloat(laborType.overtime_rate).toFixed(2)}/h
                                    </div>
                                    <div class="text-xs text-gray-400 dark:text-gray-500">${laborType.skill_level_label}</div>
                                </div>
                            `;
                        });
                        resultsDiv.innerHTML = resultsHtml;
                        resultsDiv.classList.remove('hidden');
                        
                        resultsDiv.querySelectorAll('.labor-option').forEach(option => {
                            option.addEventListener('click', function() {
                                const laborId = this.dataset.id;
                                const laborName = this.dataset.name;
                                const hourlyRate = this.dataset.rate;
                                
                                searchInput.value = laborName;
                                hiddenInput.value = laborId;
                                resultsDiv.classList.add('hidden');
                                
                                // Set unit_price from hourly rate
                                const unitPriceInput = container.querySelector('.labor-unit-price');
                                if (unitPriceInput) {
                                    unitPriceInput.value = hourlyRate;
                                }
                                
                                const descriptionInput = container.querySelector('input[name*="[description]"]');
                                if (!descriptionInput.value) {
                                    descriptionInput.value = laborName;
                                }
                                
                                calculateTotals();
                            });
                        });
                    } else {
                        resultsDiv.innerHTML = '<div class="p-2 text-gray-500 dark:text-gray-400 text-sm">Nenhum tipo encontrado</div>';
                        resultsDiv.classList.remove('hidden');
                    }
                }, 300);
            });
            
            document.addEventListener('click', function(e) {
                if (!container.contains(e.target)) {
                    resultsDiv.classList.add('hidden');
                }
            });
        }

        // Adicionar item inicial e calcular totais ao carregar
        document.addEventListener('DOMContentLoaded', function() {
            addItem('product');
            const discountInput = document.querySelector('[name="discount"]');
            discountInput.addEventListener('input', calculateTotals);
            
            // Funcionalidade de busca de vistoria removida - sistema antigo foi descontinuado
        });
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
<?php endif; ?><?php /**PATH C:\Users\Douglas\Documents\Projetos\stock-master\resources\views/budgets/create.blade.php ENDPATH**/ ?>