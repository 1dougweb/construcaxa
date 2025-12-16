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
        window.loadLaborTypeForm = async function(laborTypeId) {
            const form = document.getElementById('laborTypeForm');
            const offcanvasTitle = document.querySelector('#labor-type-offcanvas h2');
            const methodInput = document.getElementById('labor_type_method');
            
            if (!form || !offcanvasTitle || !methodInput) return;
            
            form.reset();
            if (window.clearLaborTypeErrors) window.clearLaborTypeErrors();
            
            if (laborTypeId) {
                offcanvasTitle.textContent = 'Editar Tipo de Mão de Obra';
                methodInput.value = 'PUT';
                form.action = `/labor-types/${laborTypeId}`;
                
                try {
                    const response = await fetch(`/labor-types/${laborTypeId}/edit`, {
                        headers: {
                            'X-Requested-With': 'XMLHttpRequest',
                            'Accept': 'application/json'
                        }
                    });
                    
                    if (response.ok) {
                        const data = await response.json();
                        const laborType = data.laborType;
                        
                        if (laborType.name) document.getElementById('labor_type_name').value = laborType.name;
                        if (laborType.skill_level) document.getElementById('labor_type_skill_level').value = laborType.skill_level;
                        if (laborType.hourly_rate) document.getElementById('labor_type_hourly_rate').value = laborType.hourly_rate;
                        if (laborType.overtime_rate) document.getElementById('labor_type_overtime_rate').value = laborType.overtime_rate;
                        if (laborType.description) document.getElementById('labor_type_description').value = laborType.description;
                        document.getElementById('labor_type_is_active').checked = laborType.is_active;
                    } else {
                        window.location.href = `/labor-types/${laborTypeId}/edit`;
                        return;
                    }
                } catch (error) {
                    console.error('Erro ao carregar tipo de mão de obra:', error);
                    window.location.href = `/labor-types/${laborTypeId}/edit`;
                    return;
                }
            } else {
                offcanvasTitle.textContent = 'Novo Tipo de Mão de Obra';
                methodInput.value = 'POST';
                form.action = '<?php echo e(route("labor-types.store")); ?>';
            }
            
            if (window.openOffcanvas) window.openOffcanvas('labor-type-offcanvas');
        };
    </script>
    
     <?php $__env->slot('header', null, []); ?> 
        <div class="flex justify-between items-center">
            <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
                <?php echo e(__('Tipos de Mão de Obra')); ?>

            </h2>
            <button onclick="loadLaborTypeForm(null)" class="inline-flex items-center px-4 py-2 bg-purple-600 dark:bg-purple-700 text-white rounded-md hover:bg-purple-700 dark:hover:bg-purple-600 transition-colors">
                <i class="bi bi-plus-circle mr-2"></i>
                Novo Tipo
            </button>
        </div>
     <?php $__env->endSlot(); ?>

    <div class="py-6">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <!-- Filters -->
            <div class="bg-white dark:bg-gray-800 rounded-lg shadow p-4 mb-6">
                <form method="GET" class="grid grid-cols-1 md:grid-cols-4 gap-4">
                    <div>
                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Buscar</label>
                        <input type="text" name="search" value="<?php echo e(request('search')); ?>" 
                               placeholder="Nome do tipo..." 
                               class="w-full border-gray-300 dark:border-gray-600 rounded-md bg-white dark:bg-gray-700 text-gray-900 dark:text-gray-100 focus:ring-indigo-500 focus:border-indigo-500">
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Nível</label>
                        <select name="skill_level" class="w-full border-gray-300 dark:border-gray-600 rounded-md bg-white dark:bg-gray-700 text-gray-900 dark:text-gray-100 focus:ring-indigo-500 focus:border-indigo-500">
                            <option value="">Todos os níveis</option>
                            <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php $__currentLoopData = $skillLevels; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $value => $label): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                <option value="<?php echo e($value); ?>" <?php echo e(request('skill_level') == $value ? 'selected' : ''); ?>>
                                    <?php echo e($label); ?>

                                </option>
                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                        </select>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Status</label>
                        <select name="status" class="w-full border-gray-300 dark:border-gray-600 rounded-md bg-white dark:bg-gray-700 text-gray-900 dark:text-gray-100 focus:ring-indigo-500 focus:border-indigo-500">
                            <option value="">Todos</option>
                            <option value="active" <?php echo e(request('status') === 'active' ? 'selected' : ''); ?>>Ativo</option>
                            <option value="inactive" <?php echo e(request('status') === 'inactive' ? 'selected' : ''); ?>>Inativo</option>
                        </select>
                    </div>
                    <div class="flex items-end">
                        <button type="submit" class="w-full px-4 py-2 bg-gray-600 dark:bg-gray-700 text-white rounded-md hover:bg-gray-700 dark:hover:bg-gray-600 transition-colors">
                            Filtrar
                        </button>
                    </div>
                </form>
            </div>

            <!-- Labor Types Grid -->
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php $__empty_1 = true; $__currentLoopData = $laborTypes; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $laborType): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                    <div class="bg-white dark:bg-gray-800 rounded-lg shadow-md border border-gray-200 dark:border-gray-700 hover:shadow-lg transition-shadow duration-200">
                        <div class="p-6">
                            <!-- Header -->
                            <div class="flex items-start justify-between mb-4">
                                <div class="flex-1">
                                    <h3 class="font-semibold text-lg text-gray-900 dark:text-gray-100 mb-1"><?php echo e($laborType->name); ?></h3>
                                    <span class="px-2 py-1 text-xs rounded-full <?php echo e($laborType->skill_level_color); ?>">
                                        <?php echo e($laborType->skill_level_label); ?>

                                    </span>
                                </div>
                                <div class="flex items-center space-x-2">
                                    <span class="px-2 py-1 text-xs rounded-full <?php echo e($laborType->is_active ? 'bg-green-100 dark:bg-green-900/30 text-green-800 dark:text-green-300' : 'bg-red-100 dark:bg-red-900/30 text-red-800 dark:text-red-300'); ?>">
                                        <?php echo e($laborType->is_active ? 'Ativo' : 'Inativo'); ?>

                                    </span>
                                </div>
                            </div>

                            <!-- Description -->
                            <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($laborType->description): ?>
                                <p class="text-sm text-gray-600 dark:text-gray-400 mb-4"><?php echo e(Str::limit($laborType->description, 100)); ?></p>
                            <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>

                            <!-- Pricing Info -->
                            <div class="bg-gray-50 dark:bg-gray-700 rounded-lg p-3 mb-4 space-y-2">
                                <div class="flex justify-between">
                                    <span class="text-sm text-gray-600 dark:text-gray-400">Hora Normal:</span>
                                    <span class="font-medium text-purple-600 dark:text-purple-400"><?php echo e($laborType->formatted_hourly_rate); ?></span>
                                </div>
                                <div class="flex justify-between">
                                    <span class="text-sm text-gray-600 dark:text-gray-400">Hora Extra:</span>
                                    <span class="font-medium text-orange-600 dark:text-orange-400"><?php echo e($laborType->formatted_overtime_rate); ?></span>
                                </div>
                            </div>

                            <!-- Actions -->
                            <div class="flex space-x-2">
                                <button onclick="loadLaborTypeForm(<?php echo e($laborType->id); ?>)" 
                                   class="flex-1 text-center px-3 py-2 text-sm bg-purple-600 dark:bg-purple-700 text-white rounded-md hover:bg-purple-700 dark:hover:bg-purple-600 transition-colors">
                                    Editar
                                </button>
                                <a href="<?php echo e(route('labor-types.show', $laborType)); ?>" 
                                   class="px-3 py-2 text-sm bg-gray-600 dark:bg-gray-700 text-white rounded-md hover:bg-gray-700 dark:hover:bg-gray-600 transition-colors">
                                    Ver
                                </a>
                            </div>
                        </div>
                    </div>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                    <div class="col-span-full">
                        <div class="text-center py-12">
                            <div class="text-gray-400 dark:text-gray-500 text-6xl mb-4">
                                <i class="bi bi-people" style="font-size: 4rem;"></i>
                            </div>
                            <h3 class="text-lg font-medium text-gray-900 dark:text-gray-100 mb-2">Nenhum tipo de mão de obra encontrado</h3>
                            <p class="text-gray-600 dark:text-gray-400 mb-4">Comece criando seu primeiro tipo de mão de obra.</p>
                            <button onclick="loadLaborTypeForm(null)" 
                               class="inline-flex items-center px-4 py-2 bg-purple-600 dark:bg-purple-700 text-white rounded-md hover:bg-purple-700 dark:hover:bg-purple-600 transition-colors">
                                <i class="bi bi-plus-circle mr-2"></i>
                                Criar Tipo
                            </button>
                        </div>
                    </div>
                <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
            </div>

        <!-- Pagination -->
        <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($laborTypes->hasPages()): ?>
            <div class="mt-6"><?php echo e($laborTypes->links()); ?></div>
        <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>

            <!-- Quick Links -->
            <div class="mt-8 border-t border-gray-200 dark:border-gray-700 pt-6">
                <div class="flex space-x-4">
                    <a href="<?php echo e(route('services.index')); ?>" 
                       class="inline-flex items-center px-4 py-2 bg-indigo-600 dark:bg-indigo-700 text-white rounded-md hover:bg-indigo-700 dark:hover:bg-indigo-600 transition-colors">
                        <i class="bi bi-tools mr-2"></i>
                        Gerenciar Serviços
                    </a>
                    <a href="<?php echo e(route('service-categories.index')); ?>" 
                       class="inline-flex items-center px-4 py-2 bg-gray-600 dark:bg-gray-700 text-white rounded-md hover:bg-gray-700 dark:hover:bg-gray-600 transition-colors">
                        <i class="bi bi-folder mr-2"></i>
                        Categorias de Serviços
                    </a>
                </div>
            </div>
        </div>
    </div>

    <!-- Offcanvas para Novo/Editar Tipo de Mão de Obra -->
    <?php if (isset($component)) { $__componentOriginal5fd361cc9f4aafccfd6aee776cbb14bc = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal5fd361cc9f4aafccfd6aee776cbb14bc = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.offcanvas','data' => ['id' => 'labor-type-offcanvas','title' => 'Novo Tipo de Mão de Obra','width' => 'w-full md:w-[700px]']] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('offcanvas'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['id' => 'labor-type-offcanvas','title' => 'Novo Tipo de Mão de Obra','width' => 'w-full md:w-[700px]']); ?>
        <form method="POST" action="<?php echo e(route('labor-types.store')); ?>" id="laborTypeForm">
            <?php echo csrf_field(); ?>
            <input type="hidden" name="_method" id="labor_type_method" value="POST">

            <div class="mb-4">
                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Nome *</label>
                <input type="text" name="name" id="labor_type_name" required 
                       class="w-full border-gray-300 dark:border-gray-600 rounded-md bg-white dark:bg-gray-700 text-gray-900 dark:text-gray-100 focus:ring-indigo-500 focus:border-indigo-500">
                <div id="name_error" class="hidden">
                    <p class="text-red-500 dark:text-red-400 text-xs mt-1"></p>
                </div>
            </div>

            <div class="mb-4">
                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Nível de Habilidade *</label>
                <select name="skill_level" id="labor_type_skill_level" required class="w-full border-gray-300 dark:border-gray-600 rounded-md bg-white dark:bg-gray-700 text-gray-900 dark:text-gray-100 focus:ring-indigo-500 focus:border-indigo-500">
                    <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php $__currentLoopData = $skillLevels; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $value => $label): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                        <option value="<?php echo e($value); ?>"><?php echo e($label); ?></option>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                </select>
                <div id="skill_level_error" class="hidden">
                    <p class="text-red-500 dark:text-red-400 text-xs mt-1"></p>
                </div>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-4">
                <div>
                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Valor Hora Normal (R$) *</label>
                    <input type="number" name="hourly_rate" id="labor_type_hourly_rate" step="0.01" min="0" required 
                           class="w-full border-gray-300 dark:border-gray-600 rounded-md bg-white dark:bg-gray-700 text-gray-900 dark:text-gray-100 focus:ring-indigo-500 focus:border-indigo-500">
                    <div id="hourly_rate_error" class="hidden">
                        <p class="text-red-500 dark:text-red-400 text-xs mt-1"></p>
                    </div>
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Valor Hora Extra (R$) *</label>
                    <input type="number" name="overtime_rate" id="labor_type_overtime_rate" step="0.01" min="0" required 
                           class="w-full border-gray-300 dark:border-gray-600 rounded-md bg-white dark:bg-gray-700 text-gray-900 dark:text-gray-100 focus:ring-indigo-500 focus:border-indigo-500">
                    <div id="overtime_rate_error" class="hidden">
                        <p class="text-red-500 dark:text-red-400 text-xs mt-1"></p>
                    </div>
                </div>
            </div>

            <div class="mb-4">
                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Descrição</label>
                <textarea name="description" id="labor_type_description" rows="3" 
                          class="w-full border-gray-300 dark:border-gray-600 rounded-md bg-white dark:bg-gray-700 text-gray-900 dark:text-gray-100 focus:ring-indigo-500 focus:border-indigo-500"></textarea>
            </div>

            <div class="mb-6 flex items-center">
                <input type="checkbox" name="is_active" id="labor_type_is_active" value="1" checked 
                       class="rounded border-gray-300 dark:border-gray-600 text-indigo-600 focus:ring-indigo-500">
                <label for="labor_type_is_active" class="ml-2 text-sm text-gray-700 dark:text-gray-300">Tipo ativo</label>
            </div>

            <div class="flex justify-end gap-3 pt-4 border-t border-gray-200 dark:border-gray-700">
                <button type="button" onclick="closeOffcanvas('labor-type-offcanvas')" 
                       class="px-4 py-2 border border-gray-300 dark:border-gray-600 rounded-md text-gray-700 dark:text-gray-200 hover:bg-gray-50 dark:hover:bg-gray-700 transition-colors">
                    Cancelar
                </button>
                <button type="submit" 
                       class="px-4 py-2 bg-purple-600 dark:bg-purple-700 text-white rounded-md hover:bg-purple-700 dark:hover:bg-purple-600 transition-colors">
                    Salvar Tipo
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
    window.clearLaborTypeErrors = function() {
        document.querySelectorAll('[id$="_error"]').forEach(el => {
            el.classList.add('hidden');
            const p = el.querySelector('p');
            if (p) p.textContent = '';
        });
    };
    
    // Update the function with full implementation
    window.loadLaborTypeForm = async function(laborTypeId) {
        const form = document.getElementById('laborTypeForm');
        const offcanvasTitle = document.querySelector('#labor-type-offcanvas h2');
        const methodInput = document.getElementById('labor_type_method');
        
        if (!form || !offcanvasTitle || !methodInput) return;
        
        form.reset();
        window.clearLaborTypeErrors();
        
        if (laborTypeId) {
            offcanvasTitle.textContent = 'Editar Tipo de Mão de Obra';
            methodInput.value = 'PUT';
            form.action = `/labor-types/${laborTypeId}`;
            
            try {
                const response = await fetch(`/labor-types/${laborTypeId}/edit`, {
                    headers: {
                        'X-Requested-With': 'XMLHttpRequest',
                        'Accept': 'application/json'
                    }
                });
                
                if (response.ok) {
                    const data = await response.json();
                    const laborType = data.laborType;
                    
                    if (laborType.name) document.getElementById('labor_type_name').value = laborType.name;
                    if (laborType.skill_level) document.getElementById('labor_type_skill_level').value = laborType.skill_level;
                    if (laborType.hourly_rate) document.getElementById('labor_type_hourly_rate').value = laborType.hourly_rate;
                    if (laborType.overtime_rate) document.getElementById('labor_type_overtime_rate').value = laborType.overtime_rate;
                    if (laborType.description) document.getElementById('labor_type_description').value = laborType.description;
                    document.getElementById('labor_type_is_active').checked = laborType.is_active;
                } else {
                    window.location.href = `/labor-types/${laborTypeId}/edit`;
                    return;
                }
            } catch (error) {
                console.error('Erro ao carregar tipo de mão de obra:', error);
                window.location.href = `/labor-types/${laborTypeId}/edit`;
                return;
            }
        } else {
            offcanvasTitle.textContent = 'Novo Tipo de Mão de Obra';
            methodInput.value = 'POST';
            form.action = '<?php echo e(route("labor-types.store")); ?>';
        }
        
        if (window.openOffcanvas) window.openOffcanvas('labor-type-offcanvas');
    };
    
    // Form submit handler
    document.addEventListener('DOMContentLoaded', function() {
        const form = document.getElementById('laborTypeForm');
        if (!form) return;
        
        form.addEventListener('submit', async function(e) {
            e.preventDefault();
            
            const formData = new FormData(form);
            const method = document.getElementById('labor_type_method').value;
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
                    window.clearLaborTypeErrors();
                    
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
                    alert(data.message || 'Erro ao salvar tipo de mão de obra');
                    submitButton.disabled = false;
                    submitButton.innerHTML = originalText;
                }
            } catch (error) {
                console.error('Erro:', error);
                alert('Erro ao salvar tipo de mão de obra');
                submitButton.disabled = false;
                submitButton.innerHTML = originalText;
            }
        });
    });
</script>
<?php $__env->stopPush(); ?>
<?php /**PATH C:\Users\Douglas\Documents\Projetos\stock-master\resources\views/labor-types/index.blade.php ENDPATH**/ ?>