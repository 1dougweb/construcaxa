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
    <script>
        // Define functions immediately so they're available for onclick handlers
        window.loadServiceCategoryForm = async function(categoryId) {
            const form = document.getElementById('serviceCategoryForm');
            const offcanvasTitle = document.querySelector('#service-category-offcanvas h2');
            const methodInput = document.getElementById('service_category_method');
            
            if (!form || !offcanvasTitle || !methodInput) return;
            
            form.reset();
            if (window.clearServiceCategoryErrors) window.clearServiceCategoryErrors();
            
            if (categoryId) {
                offcanvasTitle.textContent = 'Editar Categoria de Serviço';
                methodInput.value = 'PUT';
                form.action = `/service-categories/${categoryId}`;
                
                try {
                    const response = await fetch(`/service-categories/${categoryId}/edit`, {
                        headers: {
                            'X-Requested-With': 'XMLHttpRequest',
                            'Accept': 'application/json'
                        }
                    });
                    
                    if (response.ok) {
                        const data = await response.json();
                        const category = data.serviceCategory;
                        
                        if (category.name) document.getElementById('service_category_name').value = category.name;
                        if (category.description) document.getElementById('service_category_description').value = category.description;
                        if (category.color) {
                            document.getElementById('service_category_color').value = category.color;
                            document.getElementById('service_category_color_text').value = category.color;
                        }
                        document.getElementById('service_category_is_active').checked = category.is_active;
                    } else {
                        window.location.href = `/service-categories/${categoryId}/edit`;
                        return;
                    }
                } catch (error) {
                    console.error('Erro ao carregar categoria:', error);
                    window.location.href = `/service-categories/${categoryId}/edit`;
                    return;
                }
            } else {
                offcanvasTitle.textContent = 'Nova Categoria de Serviço';
                methodInput.value = 'POST';
                form.action = '<?php echo e(route("service-categories.store")); ?>';
            }
            
            if (window.syncColorPicker) window.syncColorPicker();
            if (window.openOffcanvas) window.openOffcanvas('service-category-offcanvas');
        };
    </script>
    
     <?php $__env->slot('header', null, []); ?> 
        <div class="flex items-center justify-between">
            <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
                <?php echo e(__('Categorias de Serviços')); ?>

            </h2>
            <button onclick="loadServiceCategoryForm(null)" 
               class="inline-flex items-center px-4 py-2 bg-indigo-600 dark:bg-indigo-700 text-white rounded-md hover:bg-indigo-700 dark:hover:bg-indigo-600 transition-colors">
                <i class="bi bi-plus-circle mr-2"></i>
                Nova Categoria
            </button>
        </div>
     <?php $__env->endSlot(); ?>

    <div class="py-6">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white dark:bg-gray-800 rounded-lg shadow overflow-hidden">
                <table class="min-w-full divide-y divide-gray-200 dark:divide-gray-700">
                    <thead class="bg-gray-50 dark:bg-gray-700">
                        <tr>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">Nome</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">Cor</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">Serviços</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">Status</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">Ações</th>
                        </tr>
                    </thead>
                    <tbody class="bg-white dark:bg-gray-800 divide-y divide-gray-200 dark:divide-gray-700">
                        <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php $__empty_1 = true; $__currentLoopData = $categories; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $category): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                            <tr class="hover:bg-gray-50 dark:hover:bg-gray-700 transition-colors">
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <div class="text-sm font-medium text-gray-900 dark:text-gray-100"><?php echo e($category->name); ?></div>
                                    <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($category->description): ?>
                                        <div class="text-sm text-gray-500 dark:text-gray-400"><?php echo e(Str::limit($category->description, 50)); ?></div>
                                    <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <div class="flex items-center">
                                        <span class="inline-block w-6 h-6 rounded-full border border-gray-300 dark:border-gray-600" 
                                              style="background-color: <?php echo e($category->color); ?>"></span>
                                        <span class="ml-2 text-sm text-gray-600 dark:text-gray-400"><?php echo e($category->color); ?></span>
                                    </div>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900 dark:text-gray-100">
                                    <?php echo e($category->services_count); ?>

                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <span class="px-2 py-1 text-xs rounded-full <?php echo e($category->is_active ? 'bg-green-100 dark:bg-green-900/30 text-green-800 dark:text-green-300' : 'bg-red-100 dark:bg-red-900/30 text-red-800 dark:text-red-300'); ?>">
                                        <?php echo e($category->is_active ? 'Ativa' : 'Inativa'); ?>

                                    </span>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                                    <div class="flex space-x-2">
                                        <a href="<?php echo e(route('service-categories.show', $category)); ?>" 
                                           class="text-indigo-600 dark:text-indigo-400 hover:text-indigo-900 dark:hover:text-indigo-300 transition-colors">
                                            <i class="bi bi-eye"></i>
                                        </a>
                                        <button onclick="loadServiceCategoryForm(<?php echo e($category->id); ?>)" 
                                           class="text-blue-600 dark:text-blue-400 hover:text-blue-900 dark:hover:text-blue-300 transition-colors">
                                            <i class="bi bi-pencil"></i>
                                        </button>
                                        <form action="<?php echo e(route('service-categories.destroy', $category)); ?>" 
                                              method="POST" 
                                              class="inline"
                                              onsubmit="return confirm('Tem certeza que deseja excluir esta categoria?');">
                                            <?php echo csrf_field(); ?>
                                            <?php echo method_field('DELETE'); ?>
                                            <button type="submit" class="text-red-600 dark:text-red-400 hover:text-red-900 dark:hover:text-red-300 transition-colors">
                                                <i class="bi bi-trash"></i>
                                            </button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                            <tr>
                                <td colspan="5" class="px-6 py-4 text-center text-gray-500 dark:text-gray-400">
                                    <div class="py-8">
                                        <i class="bi bi-folder-x text-4xl text-gray-400 dark:text-gray-500 mb-2"></i>
                                        <p class="text-lg font-medium text-gray-900 dark:text-gray-100">Nenhuma categoria encontrada</p>
                                        <p class="text-sm text-gray-600 dark:text-gray-400">Comece criando sua primeira categoria de serviço.</p>
                                    </div>
                                </td>
                            </tr>
                        <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                    </tbody>
                </table>

                <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($categories->hasPages()): ?>
                    <div class="px-6 py-4 border-t border-gray-200 dark:border-gray-700">
                        <?php echo e($categories->links()); ?>

                    </div>
                <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
            </div>

            <!-- Quick Links -->
            <div class="mt-6 flex space-x-4">
                <a href="<?php echo e(route('services.index')); ?>" 
                   class="inline-flex items-center px-4 py-2 bg-gray-600 dark:bg-gray-700 text-white rounded-md hover:bg-gray-700 dark:hover:bg-gray-600 transition-colors">
                    <i class="bi bi-tools mr-2"></i>
                    Gerenciar Serviços
                </a>
                <a href="<?php echo e(route('labor-types.index')); ?>" 
                   class="inline-flex items-center px-4 py-2 bg-purple-600 dark:bg-purple-700 text-white rounded-md hover:bg-purple-700 dark:hover:bg-purple-600 transition-colors">
                    <i class="bi bi-people mr-2"></i>
                    Tipos de Mão de Obra
                </a>
            </div>
        </div>
    </div>

    <!-- Offcanvas para Nova/Editar Categoria de Serviço -->
    <?php if (isset($component)) { $__componentOriginal5fd361cc9f4aafccfd6aee776cbb14bc = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal5fd361cc9f4aafccfd6aee776cbb14bc = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.offcanvas','data' => ['id' => 'service-category-offcanvas','title' => 'Nova Categoria de Serviço','width' => 'w-full md:w-[600px]']] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('offcanvas'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['id' => 'service-category-offcanvas','title' => 'Nova Categoria de Serviço','width' => 'w-full md:w-[600px]']); ?>
        <form method="POST" action="<?php echo e(route('service-categories.store')); ?>" id="serviceCategoryForm">
            <?php echo csrf_field(); ?>
            <input type="hidden" name="_method" id="service_category_method" value="POST">

            <div class="mb-4">
                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Nome *</label>
                <input type="text" name="name" id="service_category_name" required 
                       class="w-full border-gray-300 dark:border-gray-600 rounded-md bg-white dark:bg-gray-700 text-gray-900 dark:text-gray-100 focus:ring-indigo-500 focus:border-indigo-500">
                <div id="name_error" class="hidden">
                    <p class="text-red-500 dark:text-red-400 text-xs mt-1"></p>
                </div>
            </div>

            <div class="mb-4">
                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Descrição</label>
                <textarea name="description" id="service_category_description" rows="3" 
                          class="w-full border-gray-300 dark:border-gray-600 rounded-md bg-white dark:bg-gray-700 text-gray-900 dark:text-gray-100 focus:ring-indigo-500 focus:border-indigo-500"></textarea>
            </div>

            <div class="mb-4">
                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Cor</label>
                <div class="flex items-center space-x-4 mt-1">
                    <input type="color" 
                           id="service_category_color" 
                           name="color" 
                           value="#6B7280" 
                           class="h-10 w-20 border border-gray-300 dark:border-gray-600 rounded cursor-pointer bg-white dark:bg-gray-700">
                    <input type="text" 
                           id="service_category_color_text" 
                           name="color" 
                           value="#6B7280" 
                           pattern="^#[0-9A-Fa-f]{6}$"
                           placeholder="#6B7280"
                           class="block w-full border-gray-300 dark:border-gray-600 rounded-md bg-white dark:bg-gray-700 text-gray-900 dark:text-gray-100 focus:ring-indigo-500 focus:border-indigo-500">
                </div>
                <p class="text-sm text-gray-500 dark:text-gray-400 mt-1">Escolha uma cor para identificar esta categoria (formato hexadecimal: #RRGGBB)</p>
                <div id="color_error" class="hidden">
                    <p class="text-red-500 dark:text-red-400 text-xs mt-1"></p>
                </div>
            </div>

            <div class="mb-6 flex items-center">
                <input type="checkbox" name="is_active" id="service_category_is_active" value="1" checked 
                       class="rounded border-gray-300 dark:border-gray-600 text-indigo-600 focus:ring-indigo-500">
                <label for="service_category_is_active" class="ml-2 text-sm text-gray-700 dark:text-gray-300">Categoria ativa</label>
            </div>

            <div class="flex justify-end gap-3 pt-4 border-t border-gray-200 dark:border-gray-700">
                <button type="button" onclick="closeOffcanvas('service-category-offcanvas')" 
                       class="px-4 py-2 border border-gray-300 dark:border-gray-600 rounded-md text-gray-700 dark:text-gray-200 hover:bg-gray-50 dark:hover:bg-gray-700 transition-colors">
                    Cancelar
                </button>
                <button type="submit" 
                       class="px-4 py-2 bg-indigo-600 dark:bg-indigo-700 text-white rounded-md hover:bg-indigo-700 dark:hover:bg-indigo-600 transition-colors">
                    Salvar Categoria
                </button>
            </div>
        </form>
     <?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginal5fd361cc9f4aafccfd6aee776cbb14bc)): ?>
<?php $attributes = $__attributesOriginal5fd361cc9f4aafccfd6aee776cbb14bc; ?>
<?php unset($__attributesOriginal5fd361cc9f4aafccfd6aee776cbb14bc); ?>
<?php endif; ?>
<?php if (isset($__componentOriginal5fd361cc9f4aafccfd6aee776cbb14bc)): ?>
<?php $component = $__componentOriginal5fd361cc9f4aafccfd6aee776cbb14bc; ?>
<?php unset($__componentOriginal5fd361cc9f4aafccfd6aee776cbb14bc); ?>
<?php endif; ?>
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

<?php $__env->startPush('scripts'); ?>
<script>
    window.clearServiceCategoryErrors = function() {
        document.querySelectorAll('[id$="_error"]').forEach(el => {
            el.classList.add('hidden');
            const p = el.querySelector('p');
            if (p) p.textContent = '';
        });
    };
    
    window.syncColorPicker = function() {
        const colorPicker = document.getElementById('service_category_color');
        const colorText = document.getElementById('service_category_color_text');
        
        if (colorPicker && colorText) {
            // Remove existing listeners to avoid duplicates
            const newColorPicker = colorPicker.cloneNode(true);
            colorPicker.parentNode.replaceChild(newColorPicker, colorPicker);
            const newColorText = colorText.cloneNode(true);
            colorText.parentNode.replaceChild(newColorText, colorText);
            
            newColorPicker.addEventListener('input', function(e) {
                newColorText.value = e.target.value;
            });
            
            newColorText.addEventListener('input', function(e) {
                if (/^#[0-9A-Fa-f]{6}$/.test(e.target.value)) {
                    newColorPicker.value = e.target.value;
                }
            });
        }
    };
    
    // Update the function with full implementation
    window.loadServiceCategoryForm = async function(categoryId) {
        const form = document.getElementById('serviceCategoryForm');
        const offcanvasTitle = document.querySelector('#service-category-offcanvas h2');
        const methodInput = document.getElementById('service_category_method');
        
        if (!form || !offcanvasTitle || !methodInput) return;
        
        form.reset();
        window.clearServiceCategoryErrors();
        
        if (categoryId) {
            offcanvasTitle.textContent = 'Editar Categoria de Serviço';
            methodInput.value = 'PUT';
            form.action = `/service-categories/${categoryId}`;
            
            try {
                const response = await fetch(`/service-categories/${categoryId}/edit`, {
                    headers: {
                        'X-Requested-With': 'XMLHttpRequest',
                        'Accept': 'application/json'
                    }
                });
                
                if (response.ok) {
                    const data = await response.json();
                    const category = data.serviceCategory;
                    
                    if (category.name) document.getElementById('service_category_name').value = category.name;
                    if (category.description) document.getElementById('service_category_description').value = category.description;
                    if (category.color) {
                        document.getElementById('service_category_color').value = category.color;
                        document.getElementById('service_category_color_text').value = category.color;
                    }
                    document.getElementById('service_category_is_active').checked = category.is_active;
                } else {
                    window.location.href = `/service-categories/${categoryId}/edit`;
                    return;
                }
            } catch (error) {
                console.error('Erro ao carregar categoria:', error);
                window.location.href = `/service-categories/${categoryId}/edit`;
                return;
            }
        } else {
            offcanvasTitle.textContent = 'Nova Categoria de Serviço';
            methodInput.value = 'POST';
            form.action = '<?php echo e(route("service-categories.store")); ?>';
        }
        
        window.syncColorPicker();
        if (window.openOffcanvas) window.openOffcanvas('service-category-offcanvas');
    };
    
    // Form submit handler
    document.addEventListener('DOMContentLoaded', function() {
        const form = document.getElementById('serviceCategoryForm');
        if (!form) return;
        
        form.addEventListener('submit', async function(e) {
            e.preventDefault();
            
            const formData = new FormData(form);
            const method = document.getElementById('service_category_method').value;
            const submitButton = form.querySelector('button[type="submit"]');
            const originalText = submitButton.innerHTML;
            
            submitButton.disabled = true;
            submitButton.innerHTML = 'Salvando...';
            
            let url = form.action;
            if (method === 'PUT') {
                formData.append('_method', 'PUT');
            }
            
            try {
                const response = await fetch(url, {
                    method: 'POST',
                    body: formData,
                    headers: {
                        'X-Requested-With': 'XMLHttpRequest',
                        'Accept': 'application/json',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
                    }
                });
                
                const data = await response.json();
                
                if (response.ok && data.success) {
                    if (data.redirect) {
                        window.location.href = data.redirect;
                    } else {
                        window.location.reload();
                    }
                } else if (response.status === 422) {
                    window.clearServiceCategoryErrors();
                    
                    Object.keys(data.errors || {}).forEach(field => {
                        const errorDiv = document.getElementById(`${field}_error`);
                        if (errorDiv) {
                            errorDiv.classList.remove('hidden');
                            const p = errorDiv.querySelector('p');
                            if (p) p.textContent = data.errors[field][0];
                        }
                    });
                    
                    submitButton.disabled = false;
                    submitButton.innerHTML = originalText;
                } else {
                    alert(data.message || 'Erro ao salvar categoria');
                    submitButton.disabled = false;
                    submitButton.innerHTML = originalText;
                }
            } catch (error) {
                console.error('Erro:', error);
                alert('Erro ao salvar categoria');
                submitButton.disabled = false;
                submitButton.innerHTML = originalText;
            }
        });
        
        // Initialize color picker sync
        window.syncColorPicker();
    });
</script>
<?php $__env->stopPush(); ?>


<?php /**PATH C:\Users\Douglas\Documents\Projetos\stock-master\resources\views/service-categories/index.blade.php ENDPATH**/ ?>