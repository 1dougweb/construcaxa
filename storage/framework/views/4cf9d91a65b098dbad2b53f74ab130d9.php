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
    <?php $__env->startPush('employee-functions'); ?>
    <script>
        // Definir funções IMEDIATAMENTE - antes do Livewire renderizar
        (function() {
            'use strict';
            
            window.loadEmployeeForm = async function(employeeId) {
        // Abrir offcanvas primeiro
        const offcanvas = document.getElementById('employee-offcanvas');
        const backdrop = document.getElementById('employee-offcanvas-backdrop');
        
        if (!offcanvas || !backdrop) {
            console.error('Offcanvas employee-offcanvas não encontrado no DOM');
            return;
        }
        
        // Abrir offcanvas usando a função global
        if (typeof window.openOffcanvas === 'function') {
            window.openOffcanvas('employee-offcanvas');
        } else if (typeof openOffcanvas === 'function') {
            openOffcanvas('employee-offcanvas');
        } else {
            // Fallback manual
            backdrop.style.display = 'block';
            offcanvas.style.display = 'block';
            setTimeout(() => {
                offcanvas.classList.remove('translate-x-full');
                offcanvas.classList.add('translate-x-0');
                backdrop.classList.remove('opacity-0', 'pointer-events-none');
                backdrop.classList.add('opacity-100');
                document.body.style.overflow = 'hidden';
            }, 10);
        }
        
        // Aguardar um pouco para garantir que o offcanvas está aberto
        await new Promise(resolve => setTimeout(resolve, 100));
        
        const form = document.getElementById('employeeForm');
        if (!form) {
            console.error('Formulário employeeForm não encontrado');
            return;
        }
        const offcanvasTitle = document.querySelector('#employee-offcanvas h2');
        const methodInput = document.getElementById('employee_method');
        
        form.reset();
        if (typeof clearEmployeeErrors === 'function') clearEmployeeErrors();
        
        // Resetar preview do componente photo-upload-simple
        const photoUploadComponent = document.querySelector('[x-data*="preview"]');
        if (photoUploadComponent && window.Alpine) {
            const alpineData = window.Alpine.$data(photoUploadComponent);
            if (alpineData) {
                alpineData.preview = null;
            }
        }
        
        // Esconder foto existente inicialmente e resetar preview
        const existingPhotoDiv = document.getElementById('existing-photo-profile_photo');
        if (existingPhotoDiv) {
            existingPhotoDiv.style.display = 'none';
        }
        
        // Limpar campo de remoção se existir
        const removeInput = document.getElementById('remove_profile_photo');
        if (removeInput) {
            removeInput.remove();
        }
        
        // Limpar input de arquivo
        const fileInput = document.getElementById('profile_photo');
        if (fileInput) {
            fileInput.value = '';
        }
        
        if (employeeId) {
            if (offcanvasTitle) offcanvasTitle.textContent = 'Editar Funcionário';
            if (methodInput) methodInput.value = 'PUT';
            form.action = `/employees/${employeeId}`;
            
            try {
                const response = await fetch(`/employees/${employeeId}/edit`, {
                    headers: {
                        'X-Requested-With': 'XMLHttpRequest',
                        'Accept': 'application/json'
                    }
                });
                
                if (response.ok) {
                    const data = await response.json();
                    const employee = data.employee;
                    
                    const setValue = (id, value) => {
                        const el = document.getElementById(id);
                        if (el && value) el.value = value;
                    };
                    
                    setValue('employee_name', employee.name);
                    setValue('employee_email', employee.email);
                    setValue('employee_position', employee.position);
                    setValue('employee_department', employee.department);
                    setValue('employee_hire_date', employee.hire_date);
                    setValue('employee_phone', employee.phone);
                    setValue('employee_cellphone', employee.cellphone);
                    setValue('employee_address', employee.address);
                    setValue('employee_birth_date', employee.birth_date);
                    setValue('employee_cpf', employee.cpf);
                    setValue('employee_rg', employee.rg);
                    setValue('employee_cnpj', employee.cnpj);
                    setValue('employee_emergency_contact', employee.emergency_contact);
                    setValue('employee_notes', employee.notes);
                    
                    // Atualizar foto existente no componente photo-upload-simple
                    if (employee.profile_photo_path) {
                        // Aguardar um pouco para garantir que o Alpine.js está pronto
                        setTimeout(() => {
                            const existingPhotoDiv = document.getElementById('existing-photo-profile_photo');
                            if (existingPhotoDiv) {
                                const img = existingPhotoDiv.querySelector('img');
                                if (img) {
                                    // Usar Storage::url() equivalente - garantir que o caminho está correto
                                    const photoPath = employee.profile_photo_path.startsWith('http') 
                                        ? employee.profile_photo_path 
                                        : `/storage/${employee.profile_photo_path}`;
                                    img.src = photoPath;
                                }
                                existingPhotoDiv.style.display = 'block';
                                
                                // Esconder preview se estiver visível
                                const photoUploadComponent = document.querySelector('[x-data*="preview"]');
                                if (photoUploadComponent && window.Alpine) {
                                    const alpineData = window.Alpine.$data(photoUploadComponent);
                                    if (alpineData) {
                                        alpineData.preview = null;
                                    }
                                }
                            } else {
                                // Se o div não existe, criar dinamicamente
                                const photoContainer = document.querySelector('#photo-upload-container [x-data*="preview"]');
                                if (photoContainer) {
                                    const label = photoContainer.querySelector('label');
                                    if (label) {
                                        const existingDiv = document.createElement('div');
                                        existingDiv.id = 'existing-photo-profile_photo';
                                        existingDiv.className = 'relative w-full h-full group';
                                        existingDiv.style.display = 'block';
                                        const photoPath = employee.profile_photo_path.startsWith('http') 
                                            ? employee.profile_photo_path 
                                            : `/storage/${employee.profile_photo_path}`;
                                        existingDiv.innerHTML = `
                                            <img src="${photoPath}" alt="Foto" class="w-full h-full object-cover">
                                            <div class="absolute inset-0 bg-black bg-opacity-0 group-hover:bg-opacity-30 transition-all duration-200 flex items-center justify-center">
                                                <span class="text-white opacity-0 group-hover:opacity-100 text-sm font-medium">Clique para trocar</span>
                                            </div>
                                        `;
                                        label.appendChild(existingDiv);
                                    }
                                }
                            }
                        }, 100);
                    } else {
                        // Se não há foto, esconder o div existente
                        const existingPhotoDiv = document.getElementById('existing-photo-profile_photo');
                        if (existingPhotoDiv) {
                            existingPhotoDiv.style.display = 'none';
                        }
                    }
                } else {
                    window.location.href = `/employees/${employeeId}/edit`;
                    return;
                }
            } catch (error) {
                console.error('Erro ao carregar funcionário:', error);
                window.location.href = `/employees/${employeeId}/edit`;
                return;
            }
        } else {
            if (offcanvasTitle) offcanvasTitle.textContent = 'Novo Funcionário';
            if (methodInput) methodInput.value = 'POST';
            form.action = '<?php echo e(route("employees.store")); ?>';
            
            // Garantir que a foto existente esteja escondida no modo create
            const existingPhotoDiv = document.getElementById('existing-photo-profile_photo');
            if (existingPhotoDiv) {
                existingPhotoDiv.style.display = 'none';
            }
            
            // Resetar preview do Alpine.js
            setTimeout(() => {
                const photoUploadComponent = document.querySelector('[x-data*="preview"]');
                if (photoUploadComponent && window.Alpine) {
                    const alpineData = window.Alpine.$data(photoUploadComponent);
                    if (alpineData) {
                        alpineData.preview = null;
                    }
                }
            }, 50);
        }
        
        // Não abrir offcanvas aqui - será aberto pelo botão ou pela função loadEmployeeForm quando chamada externamente
    };
    
    window.loadProposalForm = async function(employeeId) {
        if (!employeeId) {
            alert('É necessário selecionar um funcionário');
            return;
        }
        
        // Abrir offcanvas primeiro
        const offcanvas = document.getElementById('proposal-offcanvas');
        const backdrop = document.getElementById('proposal-offcanvas-backdrop');
        
        if (offcanvas && backdrop) {
            if (offcanvas.style.display === 'none' || !offcanvas.classList.contains('translate-x-0')) {
                if (typeof window.openOffcanvas === 'function') {
                    window.openOffcanvas('proposal-offcanvas');
                } else if (typeof openOffcanvas === 'function') {
                    openOffcanvas('proposal-offcanvas');
                } else {
                    // Fallback manual
                    backdrop.style.display = 'block';
                    offcanvas.style.display = 'block';
                    setTimeout(() => {
                        offcanvas.classList.remove('translate-x-full');
                        offcanvas.classList.add('translate-x-0');
                        backdrop.classList.remove('opacity-0', 'pointer-events-none');
                        backdrop.classList.add('opacity-100');
                        document.body.style.overflow = 'hidden';
                    }, 10);
                }
            }
        } else {
            console.error('Offcanvas de proposta não encontrado no DOM');
        }
        
        try {
            const response = await fetch(`/employees/${employeeId}/proposals/create`, {
                headers: {
                    'X-Requested-With': 'XMLHttpRequest',
                    'Accept': 'application/json'
                }
            });
            
            if (response.ok) {
                const data = await response.json();
                const employee = data.employee;
                const projects = data.projects || [];
                const laborTypes = data.laborTypes || [];
                const services = data.services || [];
                
                const empIdEl = document.getElementById('proposal_employee_id');
                const empNameEl = document.getElementById('proposal_employee_name');
                if (empIdEl) empIdEl.value = employeeId;
                if (empNameEl) empNameEl.textContent = employee.name;
                
                const projectSelect = document.getElementById('proposal_project_id');
                if (projectSelect) {
                    projectSelect.innerHTML = '<option value="">Selecione uma obra</option>';
                    projects.forEach(project => {
                        const option = document.createElement('option');
                        option.value = project.id;
                        option.textContent = project.name;
                        projectSelect.appendChild(option);
                    });
                }
                
                window.proposalData = { laborTypes, services };
                
                // Adicionar primeiro item se o container estiver vazio
                setTimeout(() => {
                    const itemsContainer = document.getElementById('proposal-items-container');
                    if (itemsContainer && itemsContainer.children.length === 0) {
                        if (typeof window.addProposalItem === 'function') {
                            window.addProposalItem();
                        }
                    }
                }, 200);
            } else {
                window.location.href = `/employees/${employeeId}/proposals/create`;
            }
        } catch (error) {
            console.error('Erro ao carregar formulário de proposta:', error);
            window.location.href = `/employees/${employeeId}/proposals/create`;
        }
    };
        
        // Função previewEmployeePhoto não é mais necessária - o componente photo-upload-simple cuida disso
        
        window.clearEmployeeErrors = function() {
            document.querySelectorAll('[id$="_error"]').forEach(el => {
                el.classList.add('hidden');
                const p = el.querySelector('p');
                if (p) p.textContent = '';
            });
        };
        
        window.toggleContractTypeFields = function() {
            const contractType = document.getElementById('proposal_contract_type');
            if (!contractType) return;
            
            const contractTypeValue = contractType.value;
            const fixedDaysFields = document.getElementById('fixed-days-fields');
            
            if (contractTypeValue === 'fixed_days') {
                if (fixedDaysFields) fixedDaysFields.classList.remove('hidden');
                const days = document.getElementById('proposal_days');
                const startDate = document.getElementById('proposal_start_date');
                const endDate = document.getElementById('proposal_end_date');
                if (days) days.required = true;
                if (startDate) startDate.required = true;
                if (endDate) endDate.required = true;
            } else {
                if (fixedDaysFields) fixedDaysFields.classList.add('hidden');
                const days = document.getElementById('proposal_days');
                const startDate = document.getElementById('proposal_start_date');
                const endDate = document.getElementById('proposal_end_date');
                if (days) days.required = false;
                if (startDate) startDate.required = false;
                if (endDate) endDate.required = false;
            }
        };
        
        window.proposalItemIndex = 0;
        
        window.addProposalItem = function(itemType = null, laborTypeId = null, serviceId = null, quantity = '', unitPrice = '') {
            const container = document.getElementById('proposal-items-container');
            if (!container) return;
            
            const data = window.proposalData || { laborTypes: [], services: [] };
            
            const itemDiv = document.createElement('div');
            itemDiv.className = 'item-row mb-4 p-4 border border-gray-300 dark:border-gray-600 rounded-lg bg-white dark:bg-gray-700';
            itemDiv.dataset.index = window.proposalItemIndex;

            itemDiv.innerHTML = `
                <div class="grid grid-cols-1 md:grid-cols-5 gap-4">
                    <div>
                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Tipo</label>
                        <select name="items[${window.proposalItemIndex}][item_type]" class="item-type-select w-full border-gray-300 dark:border-gray-600 rounded-md bg-white dark:bg-gray-700 text-gray-900 dark:text-gray-100 focus:ring-indigo-500 focus:border-indigo-500" required onchange="if(typeof window.toggleProposalItemFields === 'function') { window.toggleProposalItemFields(this); } else if(typeof toggleProposalItemFields === 'function') { toggleProposalItemFields(this); }">
                            <option value="labor" ${itemType === 'labor' ? 'selected' : ''}>Mão de Obra</option>
                            <option value="service" ${itemType === 'service' ? 'selected' : ''}>Serviço</option>
                        </select>
                    </div>
                    <div class="labor-type-field">
                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Tipo de Mão de Obra</label>
                        <select name="items[${window.proposalItemIndex}][labor_type_id]" class="w-full border-gray-300 dark:border-gray-600 rounded-md bg-white dark:bg-gray-700 text-gray-900 dark:text-gray-100 focus:ring-indigo-500 focus:border-indigo-500">
                            <option value="">Selecione</option>
                            ${data.laborTypes.map(lt => `<option value="${lt.id}" ${laborTypeId == lt.id ? 'selected' : ''}>${lt.name}</option>`).join('')}
                        </select>
                    </div>
                    <div class="service-field" style="display: none;">
                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Serviço</label>
                        <select name="items[${window.proposalItemIndex}][service_id]" class="w-full border-gray-300 dark:border-gray-600 rounded-md bg-white dark:bg-gray-700 text-gray-900 dark:text-gray-100 focus:ring-indigo-500 focus:border-indigo-500">
                            <option value="">Selecione</option>
                            ${data.services.map(s => `<option value="${s.id}" ${serviceId == s.id ? 'selected' : ''}>${s.name}</option>`).join('')}
                        </select>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Quantidade</label>
                        <input type="number" step="0.01" name="items[${window.proposalItemIndex}][quantity]" value="${quantity}" 
                               class="w-full border-gray-300 dark:border-gray-600 rounded-md bg-white dark:bg-gray-700 text-gray-900 dark:text-gray-100 focus:ring-indigo-500 focus:border-indigo-500" required>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Preço Unitário (R$)</label>
                        <input type="number" step="0.01" name="items[${window.proposalItemIndex}][unit_price]" value="${unitPrice}" 
                               class="w-full border-gray-300 dark:border-gray-600 rounded-md bg-white dark:bg-gray-700 text-gray-900 dark:text-gray-100 focus:ring-indigo-500 focus:border-indigo-500" required>
                    </div>
                </div>
                <div class="mt-2 flex justify-end">
                    <button type="button" onclick="if(typeof window.removeProposalItem === 'function') { window.removeProposalItem(this); } else if(typeof removeProposalItem === 'function') { removeProposalItem(this); }" class="text-red-600 dark:text-red-400 hover:text-red-900 dark:hover:text-red-300 text-sm">
                        Remover
                    </button>
                </div>
            `;

            container.appendChild(itemDiv);
            if (typeof toggleProposalItemFields === 'function') {
                toggleProposalItemFields(itemDiv.querySelector('.item-type-select'));
            }
            window.proposalItemIndex++;
        };
        
        window.toggleProposalItemFields = function(select) {
            if (!select) return;
            const itemDiv = select.closest('.item-row');
            if (!itemDiv) return;
            
            const laborField = itemDiv.querySelector('.labor-type-field');
            const serviceField = itemDiv.querySelector('.service-field');
            const laborSelect = itemDiv.querySelector('[name*="[labor_type_id]"]');
            const serviceSelect = itemDiv.querySelector('[name*="[service_id]"]');

            if (select.value === 'labor') {
                if (laborField) laborField.style.display = 'block';
                if (serviceField) serviceField.style.display = 'none';
                if (laborSelect) laborSelect.required = true;
                if (serviceSelect) {
                    serviceSelect.required = false;
                    serviceSelect.value = '';
                }
            } else {
                if (laborField) laborField.style.display = 'none';
                if (serviceField) serviceField.style.display = 'block';
                if (laborSelect) {
                    laborSelect.required = false;
                    laborSelect.value = '';
                }
                if (serviceSelect) serviceSelect.required = true;
            }
        };
        
        window.removeProposalItem = function(button) {
            if (button && button.closest) {
                button.closest('.item-row')?.remove();
            }
            };
        })();
    </script>
    <?php $__env->stopPush(); ?>

    <div class="p-6">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="flex justify-between items-center mb-6">
                <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
                    <?php echo e(__('Funcionários')); ?>

                </h2>
                <button onclick="loadEmployeeForm(null)" class="inline-flex items-center px-4 py-2 bg-indigo-600 dark:bg-indigo-700 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-indigo-700 dark:hover:bg-indigo-600 focus:bg-indigo-700 active:bg-indigo-900 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 transition ease-in-out duration-150">
                    <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
                    </svg>
                    <?php echo e(__('Novo Funcionário')); ?>

                </button>
            </div>
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-xl sm:rounded-lg">
                <div class="p-6 lg:p-8 bg-white dark:bg-gray-800 border-b border-gray-200 dark:border-gray-700">

                    <?php
$__split = function ($name, $params = []) {
    return [$name, $params];
};
[$__name, $__params] = $__split('employee-list', []);

$key = null;

$key ??= \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::generateKey('lw-4167232842-0', null);

$__html = app('livewire')->mount($__name, $__params, $key);

echo $__html;

unset($__html);
unset($__name);
unset($__params);
unset($__split);
if (isset($__slots)) unset($__slots);
?>
                </div>
            </div>
        </div>
    </div>

    <!-- Offcanvas para Novo/Editar Funcionário -->
    <?php if (isset($component)) { $__componentOriginal5fd361cc9f4aafccfd6aee776cbb14bc = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal5fd361cc9f4aafccfd6aee776cbb14bc = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.offcanvas','data' => ['id' => 'employee-offcanvas','title' => 'Novo Funcionário','width' => 'w-full md:w-[900px]']] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('offcanvas'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['id' => 'employee-offcanvas','title' => 'Novo Funcionário','width' => 'w-full md:w-[900px]']); ?>
        <form action="<?php echo e(route('employees.store')); ?>" method="POST" enctype="multipart/form-data" id="employeeForm">
            <?php echo csrf_field(); ?>
            <input type="hidden" name="_method" id="employee_method" value="POST">

            <!-- Foto e Nome/Email -->
            <div class="flex gap-6 items-start mb-6">
                <div class="flex-shrink-0" id="photo-upload-container">
                    <?php if (isset($component)) { $__componentOriginalf4bf6ae31c5a257db986ede2c7ab1be5 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginalf4bf6ae31c5a257db986ede2c7ab1be5 = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.photo-upload-simple','data' => ['name' => 'profile_photo','label' => 'Foto de Perfil','existingPhotoPath' => null]] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('photo-upload-simple'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['name' => 'profile_photo','label' => 'Foto de Perfil','existingPhotoPath' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute(null)]); ?>
<?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginalf4bf6ae31c5a257db986ede2c7ab1be5)): ?>
<?php $attributes = $__attributesOriginalf4bf6ae31c5a257db986ede2c7ab1be5; ?>
<?php unset($__attributesOriginalf4bf6ae31c5a257db986ede2c7ab1be5); ?>
<?php endif; ?>
<?php if (isset($__componentOriginalf4bf6ae31c5a257db986ede2c7ab1be5)): ?>
<?php $component = $__componentOriginalf4bf6ae31c5a257db986ede2c7ab1be5; ?>
<?php unset($__componentOriginalf4bf6ae31c5a257db986ede2c7ab1be5); ?>
<?php endif; ?>
                    <div id="profile_photo_error" class="hidden">
                        <p class="text-red-500 dark:text-red-400 text-xs mt-1"></p>
                    </div>
                </div>
                
                <div class="flex-1 space-y-4">
                    <div>
                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Nome *</label>
                        <input type="text" name="name" id="employee_name" required 
                               class="w-full border-gray-300 dark:border-gray-600 rounded-md bg-white dark:bg-gray-700 text-gray-900 dark:text-gray-100 focus:ring-indigo-500 focus:border-indigo-500">
                        <div id="name_error" class="hidden">
                            <p class="text-red-500 dark:text-red-400 text-xs mt-1"></p>
                        </div>
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Email *</label>
                        <input type="email" name="email" id="employee_email" required 
                               class="w-full border-gray-300 dark:border-gray-600 rounded-md bg-white dark:bg-gray-700 text-gray-900 dark:text-gray-100 focus:ring-indigo-500 focus:border-indigo-500">
                        <div id="email_error" class="hidden">
                            <p class="text-red-500 dark:text-red-400 text-xs mt-1"></p>
                        </div>
                    </div>
                </div>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">
                <div>
                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Cargo *</label>
                    <input type="text" name="position" id="employee_position" required 
                           class="w-full border-gray-300 dark:border-gray-600 rounded-md bg-white dark:bg-gray-700 text-gray-900 dark:text-gray-100 focus:ring-indigo-500 focus:border-indigo-500">
                    <div id="position_error" class="hidden">
                        <p class="text-red-500 dark:text-red-400 text-xs mt-1"></p>
                    </div>
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Departamento *</label>
                    <input type="text" name="department" id="employee_department" required 
                           class="w-full border-gray-300 dark:border-gray-600 rounded-md bg-white dark:bg-gray-700 text-gray-900 dark:text-gray-100 focus:ring-indigo-500 focus:border-indigo-500">
                    <div id="department_error" class="hidden">
                        <p class="text-red-500 dark:text-red-400 text-xs mt-1"></p>
                    </div>
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Data de Contratação *</label>
                    <input type="date" name="hire_date" id="employee_hire_date" required 
                           class="w-full border-gray-300 dark:border-gray-600 rounded-md bg-white dark:bg-gray-700 text-gray-900 dark:text-gray-100 focus:ring-indigo-500 focus:border-indigo-500">
                    <div id="hire_date_error" class="hidden">
                        <p class="text-red-500 dark:text-red-400 text-xs mt-1"></p>
                    </div>
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Telefone</label>
                    <input type="text" name="phone" id="employee_phone" 
                           class="w-full border-gray-300 dark:border-gray-600 rounded-md bg-white dark:bg-gray-700 text-gray-900 dark:text-gray-100 focus:ring-indigo-500 focus:border-indigo-500 mask-phone">
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Celular</label>
                    <input type="text" name="cellphone" id="employee_cellphone" 
                           class="w-full border-gray-300 dark:border-gray-600 rounded-md bg-white dark:bg-gray-700 text-gray-900 dark:text-gray-100 focus:ring-indigo-500 focus:border-indigo-500 mask-cellphone">
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Endereço</label>
                    <input type="text" name="address" id="employee_address" 
                           class="w-full border-gray-300 dark:border-gray-600 rounded-md bg-white dark:bg-gray-700 text-gray-900 dark:text-gray-100 focus:ring-indigo-500 focus:border-indigo-500">
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Data de Nascimento</label>
                    <input type="date" name="birth_date" id="employee_birth_date" 
                           class="w-full border-gray-300 dark:border-gray-600 rounded-md bg-white dark:bg-gray-700 text-gray-900 dark:text-gray-100 focus:ring-indigo-500 focus:border-indigo-500">
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">CPF *</label>
                    <input type="text" name="cpf" id="employee_cpf" required 
                           class="w-full border-gray-300 dark:border-gray-600 rounded-md bg-white dark:bg-gray-700 text-gray-900 dark:text-gray-100 focus:ring-indigo-500 focus:border-indigo-500 mask-cpf">
                    <div id="cpf_error" class="hidden">
                        <p class="text-red-500 dark:text-red-400 text-xs mt-1"></p>
                    </div>
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">RG</label>
                    <input type="text" name="rg" id="employee_rg" 
                           class="w-full border-gray-300 dark:border-gray-600 rounded-md bg-white dark:bg-gray-700 text-gray-900 dark:text-gray-100 focus:ring-indigo-500 focus:border-indigo-500 mask-rg">
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">CNPJ (MEI)</label>
                    <input type="text" name="cnpj" id="employee_cnpj" 
                           class="w-full border-gray-300 dark:border-gray-600 rounded-md bg-white dark:bg-gray-700 text-gray-900 dark:text-gray-100 focus:ring-indigo-500 focus:border-indigo-500 mask-cnpj">
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Contato de Emergência (Celular)</label>
                    <input type="text" name="emergency_contact" id="employee_emergency_contact" 
                           class="w-full border-gray-300 dark:border-gray-600 rounded-md bg-white dark:bg-gray-700 text-gray-900 dark:text-gray-100 focus:ring-indigo-500 focus:border-indigo-500 mask-cellphone">
                </div>
            </div>

            <div class="mb-6">
                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Observações</label>
                <textarea name="notes" id="employee_notes" rows="3" 
                          class="w-full border-gray-300 dark:border-gray-600 rounded-md bg-white dark:bg-gray-700 text-gray-900 dark:text-gray-100 focus:ring-indigo-500 focus:border-indigo-500"></textarea>
            </div>

            <div class="flex justify-end gap-4 pt-4 border-t border-gray-200 dark:border-gray-700">
                <button type="button" onclick="if(typeof window.closeOffcanvas === 'function') { window.closeOffcanvas('employee-offcanvas'); } else if(typeof closeOffcanvas === 'function') { closeOffcanvas('employee-offcanvas'); }" 
                       class="px-4 py-2 bg-gray-300 dark:bg-gray-600 text-gray-700 dark:text-gray-200 rounded-md hover:bg-gray-400 dark:hover:bg-gray-500 transition-colors">
                    Cancelar
                </button>
                <?php if (isset($component)) { $__componentOriginal635944f67ec1864e436b88f435140e07 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal635944f67ec1864e436b88f435140e07 = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.button-loading','data' => ['variant' => 'primary','type' => 'submit']] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('button-loading'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['variant' => 'primary','type' => 'submit']); ?>
                    Salvar Funcionário
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
    <!-- Offcanvas para Nova Proposta -->
    <?php if (isset($component)) { $__componentOriginal5fd361cc9f4aafccfd6aee776cbb14bc = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal5fd361cc9f4aafccfd6aee776cbb14bc = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.offcanvas','data' => ['id' => 'proposal-offcanvas','title' => 'Nova Proposta','width' => 'w-full md:w-[900px]']] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('offcanvas'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['id' => 'proposal-offcanvas','title' => 'Nova Proposta','width' => 'w-full md:w-[900px]']); ?>
        <form action="" method="POST" id="proposalForm">
            <?php echo csrf_field(); ?>
            <input type="hidden" name="employee_id" id="proposal_employee_id">
            
            <div class="mb-4">
                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Funcionário</label>
                <p class="text-sm text-gray-900 dark:text-gray-100 font-medium" id="proposal_employee_name"></p>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-4">
                <div>
                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Obra (Opcional)</label>
                    <select name="project_id" id="proposal_project_id" 
                            class="w-full border-gray-300 dark:border-gray-600 rounded-md bg-white dark:bg-gray-700 text-gray-900 dark:text-gray-100 focus:ring-indigo-500 focus:border-indigo-500">
                        <option value="">Selecione uma obra</option>
                    </select>
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Valor da Hora (R$) *</label>
                    <input type="number" name="hourly_rate" id="proposal_hourly_rate" step="0.01" required 
                           class="w-full border-gray-300 dark:border-gray-600 rounded-md bg-white dark:bg-gray-700 text-gray-900 dark:text-gray-100 focus:ring-indigo-500 focus:border-indigo-500">
                    <div id="hourly_rate_error" class="hidden">
                        <p class="text-red-500 dark:text-red-400 text-xs mt-1"></p>
                    </div>
                </div>
            </div>

            <div class="mb-4">
                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Tipo de Contrato *</label>
                <select name="contract_type" id="proposal_contract_type" required 
                        class="w-full border-gray-300 dark:border-gray-600 rounded-md bg-white dark:bg-gray-700 text-gray-900 dark:text-gray-100 focus:ring-indigo-500 focus:border-indigo-500"
                        onchange="if(typeof window.toggleContractTypeFields === 'function') { window.toggleContractTypeFields(); } else if(typeof toggleContractTypeFields === 'function') { toggleContractTypeFields(); }"
                    <option value="fixed_days">Dias Determinados</option>
                    <option value="indefinite">Indeterminado</option>
                </select>
                <div id="contract_type_error" class="hidden">
                    <p class="text-red-500 dark:text-red-400 text-xs mt-1"></p>
                </div>
            </div>

            <div id="fixed-days-fields" class="grid grid-cols-1 md:grid-cols-3 gap-4 mb-4 hidden">
                <div>
                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Número de Dias</label>
                    <input type="number" name="days" id="proposal_days" 
                           class="w-full border-gray-300 dark:border-gray-600 rounded-md bg-white dark:bg-gray-700 text-gray-900 dark:text-gray-100 focus:ring-indigo-500 focus:border-indigo-500">
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Data de Início</label>
                    <input type="date" name="start_date" id="proposal_start_date" 
                           class="w-full border-gray-300 dark:border-gray-600 rounded-md bg-white dark:bg-gray-700 text-gray-900 dark:text-gray-100 focus:ring-indigo-500 focus:border-indigo-500">
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Data de Término</label>
                    <input type="date" name="end_date" id="proposal_end_date" 
                           class="w-full border-gray-300 dark:border-gray-600 rounded-md bg-white dark:bg-gray-700 text-gray-900 dark:text-gray-100 focus:ring-indigo-500 focus:border-indigo-500">
                </div>
            </div>

            <div class="mb-6">
                <div class="flex justify-between items-center mb-4">
                    <h3 class="text-lg font-medium text-gray-900 dark:text-gray-100">Itens da Proposta</h3>
                    <button type="button" onclick="if(typeof window.addProposalItem === 'function') { window.addProposalItem(); } else if(typeof addProposalItem === 'function') { addProposalItem(); }" class="inline-flex items-center px-3 py-2 bg-green-600 dark:bg-green-700 text-white rounded-md hover:bg-green-700 dark:hover:bg-green-600 transition-colors text-sm">
                        <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
                        </svg>
                        Adicionar Item
                    </button>
                </div>

                <div id="proposal-items-container"></div>
            </div>

            <div class="mb-6">
                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Observações</label>
                <textarea name="observations" id="proposal_observations" rows="4" 
                          class="w-full border-gray-300 dark:border-gray-600 rounded-md bg-white dark:bg-gray-700 text-gray-900 dark:text-gray-100 focus:ring-indigo-500 focus:border-indigo-500"></textarea>
            </div>

            <div class="flex justify-end gap-4 pt-4 border-t border-gray-200 dark:border-gray-700">
                <button type="button" onclick="if(typeof window.closeOffcanvas === 'function') { window.closeOffcanvas('proposal-offcanvas'); } else if(typeof closeOffcanvas === 'function') { closeOffcanvas('proposal-offcanvas'); }" 
                       class="px-4 py-2 bg-gray-300 dark:bg-gray-600 text-gray-700 dark:text-gray-200 rounded-md hover:bg-gray-400 dark:hover:bg-gray-500 transition-colors">
                    Cancelar
                </button>
                <?php if (isset($component)) { $__componentOriginal635944f67ec1864e436b88f435140e07 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal635944f67ec1864e436b88f435140e07 = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.button-loading','data' => ['variant' => 'primary','type' => 'submit']] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('button-loading'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['variant' => 'primary','type' => 'submit']); ?>
                    Criar Proposta e Enviar Email
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

    <?php $__env->startPush('scripts'); ?>
<script>
    // Event listeners - executar após DOM estar pronto
    document.addEventListener('DOMContentLoaded', function() {
        const employeeForm = document.getElementById('employeeForm');
        if (!employeeForm) return;
        
        employeeForm.addEventListener('submit', async function(e) {
            e.preventDefault();
            
            const form = e.target;
            const formData = new FormData(form);
            const method = document.getElementById('employee_method').value;
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
                    if (typeof clearEmployeeErrors === 'function') {
                        clearEmployeeErrors();
                    }
                    
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
                    alert(data.message || 'Erro ao salvar funcionário');
                    submitButton.disabled = false;
                    submitButton.innerHTML = originalText;
                }
            } catch (error) {
                console.error('Erro:', error);
                alert('Erro ao salvar funcionário');
                submitButton.disabled = false;
                submitButton.innerHTML = originalText;
            }
        });
    });
    
    // Event listeners para proposta - executar após DOM estar pronto
    document.addEventListener('DOMContentLoaded', function() {
        const proposalOffcanvas = document.getElementById('proposal-offcanvas');
        if (proposalOffcanvas) {
            // Usar MutationObserver para detectar quando o offcanvas é aberto
            const observer = new MutationObserver(function(mutations) {
                mutations.forEach(function(mutation) {
                    if (mutation.type === 'attributes' && mutation.attributeName === 'style') {
                        const isOpen = proposalOffcanvas.style.display !== 'none' && proposalOffcanvas.style.display !== '';
                        if (isOpen) {
                            const container = document.getElementById('proposal-items-container');
                            if (container && container.children.length === 0) {
                                if (typeof window.addProposalItem === 'function') {
                                    window.addProposalItem();
                                } else if (typeof addProposalItem === 'function') {
                                    addProposalItem();
                                }
                            }
                        }
                    }
                });
            });
            observer.observe(proposalOffcanvas, { attributes: true, attributeFilter: ['style'] });
        }
        
        const proposalForm = document.getElementById('proposalForm');
        if (proposalForm) {
            proposalForm.addEventListener('submit', async function(e) {
                e.preventDefault();
                
                const form = e.target;
                const formData = new FormData(form);
                const employeeId = document.getElementById('proposal_employee_id').value;
                const submitButton = form.querySelector('button[type="submit"]');
                const originalText = submitButton.innerHTML;
                
                submitButton.disabled = true;
                submitButton.innerHTML = 'Criando...';
                
                try {
                    const response = await fetch(`/employees/${employeeId}/proposals`, {
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
                        alert('Erro de validação. Verifique os campos.');
                        submitButton.disabled = false;
                        submitButton.innerHTML = originalText;
                    } else {
                        alert(data.message || 'Erro ao criar proposta');
                        submitButton.disabled = false;
                        submitButton.innerHTML = originalText;
                    }
                } catch (error) {
                    console.error('Erro:', error);
                    alert('Erro ao criar proposta');
                    submitButton.disabled = false;
                    submitButton.innerHTML = originalText;
                }
            });
        }
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
<?php endif; ?>
<?php /**PATH C:\Users\Douglas\Documents\Projetos\stock-master\resources\views/employees/index.blade.php ENDPATH**/ ?>