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
        // Define function immediately so it's available for onclick handlers
        window.loadClientForm = async function(clientId) {
            const form = document.getElementById('clientForm');
            const offcanvasTitle = document.querySelector('#client-offcanvas h2');
            const methodInput = document.getElementById('client_method');
            
            if (!form || !offcanvasTitle || !methodInput) {
                console.error('Elementos do formulário não encontrados');
                return;
            }
            
            form.reset();
            if (window.clearErrors) window.clearErrors();
            
            if (clientId) {
                offcanvasTitle.textContent = 'Editar Cliente';
                methodInput.value = 'PUT';
                form.action = `/clients/${clientId}`;
                
                try {
                    const response = await fetch(`/clients/${clientId}/edit`, {
                        headers: {
                            'X-Requested-With': 'XMLHttpRequest',
                            'Accept': 'application/json'
                        }
                    });
                    
                    if (response.ok) {
                        const data = await response.json();
                        const client = data.client;
                        
                        if (client.type === 'individual') {
                            document.getElementById('type_individual').checked = true;
                        } else {
                            document.getElementById('type_company').checked = true;
                        }
                        
                        // Preencher campos com formatação
                        if (client.cpf) {
                            const cpfInput = document.getElementById('cpf');
                            if (cpfInput) cpfInput.value = window.formatCPF ? window.formatCPF(client.cpf) : client.cpf;
                        }
                        if (client.cnpj) {
                            const cnpjInput = document.getElementById('cnpj');
                            if (cnpjInput) cnpjInput.value = window.formatCNPJ ? window.formatCNPJ(client.cnpj) : client.cnpj;
                        }
                        if (client.name) document.getElementById('name').value = client.name;
                        if (client.trading_name) document.getElementById('trading_name').value = client.trading_name;
                        if (client.email) document.getElementById('email').value = client.email;
                        if (client.phone) {
                            const phoneInput = document.getElementById('phone');
                            if (phoneInput) phoneInput.value = window.formatPhone ? window.formatPhone(client.phone) : client.phone;
                        }
                        if (client.address) document.getElementById('address').value = client.address;
                        if (client.address_number) document.getElementById('address_number').value = client.address_number;
                        if (client.address_complement) document.getElementById('address_complement').value = client.address_complement;
                        if (client.neighborhood) document.getElementById('neighborhood').value = client.neighborhood;
                        if (client.city) document.getElementById('city').value = client.city;
                        if (client.state) document.getElementById('state').value = client.state;
                        if (client.zip_code) {
                            const zipCodeInput = document.getElementById('zip_code');
                            if (zipCodeInput) zipCodeInput.value = window.formatCEP ? window.formatCEP(client.zip_code) : client.zip_code;
                        }
                        if (client.notes) document.getElementById('notes').value = client.notes;
                        document.getElementById('is_active').checked = client.is_active;
                        
                        // Atualizar tipo de cliente e campos
                        if (window.toggleClientType) {
                            window.toggleClientType();
                        }
                    } else {
                        window.location.href = `/clients/${clientId}/edit`;
                        return;
                    }
                } catch (error) {
                    console.error('Erro ao carregar cliente:', error);
                    window.location.href = `/clients/${clientId}/edit`;
                    return;
                }
            } else {
                offcanvasTitle.textContent = 'Novo Cliente';
                methodInput.value = 'POST';
                form.action = '<?php echo e(route("clients.store")); ?>';
                // Inicializar tipo de cliente (Pessoa Física por padrão)
                if (window.toggleClientType) {
                    // Garantir que Pessoa Física está selecionada
                    const typeIndividual = document.getElementById('type_individual');
                    const typeCompany = document.getElementById('type_company');
                    if (typeIndividual && typeCompany) {
                        typeIndividual.checked = true;
                        typeCompany.checked = false;
                    }
                    window.toggleClientType();
                }
            }
            
            if (window.openOffcanvas) {
                window.openOffcanvas('client-offcanvas');
                // Garantir que toggleClientType e máscaras sejam aplicados após o offcanvas abrir completamente
                setTimeout(() => {
                    if (window.toggleClientType) {
                        window.toggleClientType();
                    }
                    if (window.applyClientMasks) {
                        window.applyClientMasks();
                    }
                }, 300);
            }
        };
        
        // Função toggleClientType - garantir que está disponível imediatamente
        window.toggleClientType = function() {
            const typeIndividual = document.getElementById('type_individual');
            const typeCompany = document.getElementById('type_company');
            const cpfField = document.getElementById('cpf_field');
            const cnpjField = document.getElementById('cnpj_field');
            const tradingNameField = document.getElementById('trading_name_field');
            const nameLabel = document.getElementById('name_label');
            const cpfInput = document.getElementById('cpf');
            const cnpjInput = document.getElementById('cnpj');
            
            if (!typeIndividual || !typeCompany) {
                console.warn('Radio buttons não encontrados');
                return;
            }
            
            if (!cpfField || !cnpjField || !tradingNameField || !nameLabel) {
                console.warn('Campos do formulário não encontrados');
                return;
            }
            
            const isIndividual = typeIndividual.checked;
            
            if (isIndividual) {
                // Pessoa Física
                cpfField.classList.remove('hidden');
                cnpjField.classList.add('hidden');
                tradingNameField.classList.add('hidden');
                nameLabel.textContent = 'Nome Completo *';
                if (cpfInput) {
                    cpfInput.required = true;
                }
                if (cnpjInput) {
                    cnpjInput.required = false;
                    if (!cnpjInput.value) {
                        cnpjInput.value = '';
                    }
                }
            } else {
                // Pessoa Jurídica
                cpfField.classList.add('hidden');
                cnpjField.classList.remove('hidden');
                tradingNameField.classList.remove('hidden');
                nameLabel.textContent = 'Razão Social *';
                if (cpfInput) {
                    cpfInput.required = false;
                    if (!cpfInput.value) {
                        cpfInput.value = '';
                    }
                }
                if (cnpjInput) {
                    cnpjInput.required = true;
                }
            }
        };
        
        // Funções de formatação - garantir que estão disponíveis imediatamente
        window.formatCPF = function(value) {
            value = value.replace(/\D/g, '');
            if (value.length <= 11) {
                value = value.replace(/(\d{3})(\d)/, '$1.$2');
                value = value.replace(/(\d{3})(\d)/, '$1.$2');
                value = value.replace(/(\d{3})(\d{1,2})$/, '$1-$2');
            }
            return value;
        };
        
        window.formatCNPJ = function(value) {
            value = value.replace(/\D/g, '');
            if (value.length <= 14) {
                value = value.replace(/(\d{2})(\d)/, '$1.$2');
                value = value.replace(/(\d{3})(\d)/, '$1.$2');
                value = value.replace(/(\d{3})(\d)/, '$1/$2');
                value = value.replace(/(\d{4})(\d{1,2})$/, '$1-$2');
            }
            return value;
        };
        
        window.formatCEP = function(value) {
            value = value.replace(/\D/g, '');
            if (value.length <= 8) {
                value = value.replace(/(\d{5})(\d)/, '$1-$2');
            }
            return value;
        };
        
        window.formatPhone = function(value) {
            value = value.replace(/\D/g, '');
            if (value.length <= 11) {
                if (value.length <= 10) {
                    value = value.replace(/(\d{2})(\d)/, '($1) $2');
                    value = value.replace(/(\d{4})(\d)/, '$1-$2');
                } else {
                    value = value.replace(/(\d{2})(\d)/, '($1) $2');
                    value = value.replace(/(\d{5})(\d)/, '$1-$2');
                }
            }
            return value;
        };
        
        // Função para aplicar máscaras aos campos
        window.applyClientMasks = function() {
            const cpfInput = document.getElementById('cpf');
            const cnpjInput = document.getElementById('cnpj');
            const zipCodeInput = document.getElementById('zip_code');
            const phoneInput = document.getElementById('phone');
            
            if (cpfInput) {
                // Remover listeners antigos se existirem
                const newCpfInput = cpfInput.cloneNode(true);
                cpfInput.parentNode.replaceChild(newCpfInput, cpfInput);
                newCpfInput.addEventListener('input', function(e) {
                    e.target.value = window.formatCPF(e.target.value);
                });
            }
            
            if (cnpjInput) {
                const newCnpjInput = cnpjInput.cloneNode(true);
                cnpjInput.parentNode.replaceChild(newCnpjInput, cnpjInput);
                newCnpjInput.addEventListener('input', function(e) {
                    e.target.value = window.formatCNPJ(e.target.value);
                });
                // Buscar CNPJ ao pressionar Enter
                newCnpjInput.addEventListener('keypress', function(e) {
                    if (e.key === 'Enter') {
                        e.preventDefault();
                        if (window.searchCNPJ) {
                            window.searchCNPJ();
                        }
                    }
                });
            }
            
            if (zipCodeInput) {
                const newZipCodeInput = zipCodeInput.cloneNode(true);
                zipCodeInput.parentNode.replaceChild(newZipCodeInput, zipCodeInput);
                newZipCodeInput.addEventListener('input', function(e) {
                    e.target.value = window.formatCEP(e.target.value);
                });
            }
            
            if (phoneInput) {
                const newPhoneInput = phoneInput.cloneNode(true);
                phoneInput.parentNode.replaceChild(newPhoneInput, phoneInput);
                newPhoneInput.addEventListener('input', function(e) {
                    e.target.value = window.formatPhone(e.target.value);
                });
            }
        };
        
        // Função searchCNPJ - garantir que está disponível imediatamente
        window.searchCNPJ = async function() {
            const cnpjInput = document.getElementById('cnpj');
            if (!cnpjInput) {
                console.error('Campo CNPJ não encontrado');
                return;
            }
            
            const cnpj = cnpjInput.value.replace(/\D/g, '');
            const loadingDiv = document.getElementById('cnpj_loading');
            const searchBtn = document.getElementById('search_cnpj_btn');
            
            if (cnpj.length !== 14) {
                alert('CNPJ deve ter 14 dígitos');
                return;
            }
            
            if (loadingDiv) loadingDiv.classList.remove('hidden');
            if (searchBtn) {
                searchBtn.disabled = true;
                searchBtn.innerHTML = '<i class="bi bi-hourglass-split mr-2"></i>Buscando...';
            }
            
            try {
                const response = await fetch(`/api/clients/fetch-cnpj?cnpj=${cnpj}`, {
                    headers: {
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                        'Accept': 'application/json'
                    }
                });
                
                const data = await response.json();
                
                if (data.success) {
                    // Funções de formatação (podem estar no client-form.js)
                    const formatCEP = window.formatCEP || function(value) {
                        value = value.replace(/\D/g, '');
                        if (value.length <= 8) {
                            value = value.replace(/(\d{5})(\d)/, '$1-$2');
                        }
                        return value;
                    };
                    
                    const formatPhone = window.formatPhone || function(value) {
                        value = value.replace(/\D/g, '');
                        if (value.length <= 11) {
                            if (value.length <= 10) {
                                value = value.replace(/(\d{2})(\d)/, '($1) $2');
                                value = value.replace(/(\d{4})(\d)/, '$1-$2');
                            } else {
                                value = value.replace(/(\d{2})(\d)/, '($1) $2');
                                value = value.replace(/(\d{5})(\d)/, '$1-$2');
                            }
                        }
                        return value;
                    };
                    
                    // Preencher campos
                    const nameInput = document.getElementById('name');
                    const tradingNameInput = document.getElementById('trading_name');
                    const addressInput = document.getElementById('address');
                    const neighborhoodInput = document.getElementById('neighborhood');
                    const cityInput = document.getElementById('city');
                    const stateInput = document.getElementById('state');
                    const zipCodeInput = document.getElementById('zip_code');
                    const phoneInput = document.getElementById('phone');
                    const emailInput = document.getElementById('email');
                    
                    if (nameInput) nameInput.value = data.data.name || '';
                    if (tradingNameInput) tradingNameInput.value = data.data.trading_name || '';
                    if (addressInput) addressInput.value = data.data.address || '';
                    if (neighborhoodInput) neighborhoodInput.value = data.data.neighborhood || '';
                    if (cityInput) cityInput.value = data.data.city || '';
                    if (stateInput) stateInput.value = data.data.state || '';
                    if (zipCodeInput) zipCodeInput.value = formatCEP(data.data.zip_code || '');
                    if (phoneInput) phoneInput.value = formatPhone(data.data.phone || '');
                    if (emailInput) emailInput.value = data.data.email || '';
                } else {
                    alert('Erro ao buscar CNPJ: ' + (data.message || 'Erro desconhecido'));
                }
            } catch (error) {
                console.error('Erro ao buscar CNPJ:', error);
                alert('Erro ao buscar CNPJ: ' + error.message);
            } finally {
                if (loadingDiv) loadingDiv.classList.add('hidden');
                if (searchBtn) {
                    searchBtn.disabled = false;
                    searchBtn.innerHTML = '<i class="bi bi-search mr-2"></i>Buscar';
                }
            }
        };

        // Função para carregar visualização de cliente no offcanvas - definida cedo para uso imediato
        window.loadClientView = async function(clientId) {
            const contentDiv = document.getElementById('client-view-content');
            const offcanvasTitle = document.querySelector('#client-view-offcanvas h2');
            
            if (!contentDiv || !offcanvasTitle) return;
            
            // Loading
            contentDiv.innerHTML = '<div class="flex justify-center items-center py-12"><div class="animate-spin rounded-full h-8 w-8 border-b-2 border-indigo-600"></div></div>';
            
            if (window.openOffcanvas) {
                window.openOffcanvas('client-view-offcanvas');
            } else if (typeof openOffcanvas === 'function') {
                openOffcanvas('client-view-offcanvas');
            }
            
            try {
                const response = await fetch(`/clients/${clientId}`, {
                    headers: {
                        'X-Requested-With': 'XMLHttpRequest',
                        'Accept': 'application/json'
                    }
                });
                
                if (!response.ok) {
                    console.error('Falha na requisição de cliente:', response.status, response.statusText);
                    throw new Error('Erro ao carregar cliente');
                }
                
                const responseText = await response.text();
                let data;
                try {
                    data = JSON.parse(responseText);
                } catch (e) {
                    console.error('Resposta não é JSON válido. Response:', responseText);
                    throw new Error('Resposta do servidor inválida ao carregar cliente');
                }
                const client = data.client || data;
                
                offcanvasTitle.textContent = 'Detalhes do Cliente';
                
                let html = `
                    <div class="p-4 space-y-6">
                        <!-- Header -->
                        <div class="flex items-start justify-between">
                            <div class="flex-1">
                                <div class="flex items-center space-x-3 mb-2">
                                    <h1 class="text-2xl font-bold text-gray-900 dark:text-gray-100">
                                        ${client.name || ''}
                                    </h1>
                                    <span class="px-3 py-1 text-sm rounded-full ${client.is_active ? 'bg-green-100 dark:bg-green-900/30 text-green-800 dark:text-green-300' : 'bg-red-100 dark:bg-red-900/30 text-red-800 dark:text-red-300'}">
                                        ${client.is_active ? 'Ativo' : 'Inativo'}
                                    </span>
                                </div>
                                ${client.trading_name ? `
                                    <p class="text-gray-600 dark:text-gray-400">${client.trading_name}</p>
                                ` : ''}
                                <p class="text-sm text-gray-500 dark:text-gray-400">
                                    ${client.type === 'individual' ? 'Pessoa Física' : 'Pessoa Jurídica'}
                                </p>
                            </div>
                        </div>

                        <!-- Info Cards -->
                        <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                            <div class="bg-gray-50 dark:bg-gray-700 rounded-lg p-4">
                                <div class="text-sm text-gray-600 dark:text-gray-400 mb-1">CPF/CNPJ</div>
                                <div class="font-medium text-gray-900 dark:text-gray-100">
                                    ${client.formatted_cpf || client.formatted_cnpj || '-'}
                                </div>
                            </div>
                            <div class="bg-gray-50 dark:bg-gray-700 rounded-lg p-4">
                                <div class="text-sm text-gray-600 dark:text-gray-400 mb-1">Email</div>
                                <div class="font-medium text-gray-900 dark:text-gray-100">
                                    ${client.email || '-'}
                                </div>
                            </div>
                            <div class="bg-gray-50 dark:bg-gray-700 rounded-lg p-4">
                                <div class="text-sm text-gray-600 dark:text-gray-400 mb-1">Telefone</div>
                                <div class="font-medium text-gray-900 dark:text-gray-100">
                                    ${client.formatted_phone || client.phone || '-'}
                                </div>
                            </div>
                        </div>

                        <!-- Endereço -->
                        ${(client.address || client.city) ? `
                        <div>
                            <h3 class="text-lg font-semibold text-gray-900 dark:text-gray-100 mb-2">Endereço</h3>
                            <div class="bg-gray-50 dark:bg-gray-700 rounded-lg p-4">
                                <p class="text-gray-700 dark:text-gray-300">
                                    ${client.address ? `
                                        ${client.address}${client.address_number ? ', ' + client.address_number : ''}${client.address_complement ? ' - ' + client.address_complement : ''}<br>
                                    ` : ''}
                                    ${client.neighborhood ? `
                                        ${client.neighborhood}<br>
                                    ` : ''}
                                    ${(client.city || client.state) ? `
                                        ${client.city || ''}${client.state ? ' - ' + client.state : ''}${client.zip_code ? ' - ' + (client.formatted_zip_code || client.zip_code) : ''}
                                    ` : ''}
                                </p>
                            </div>
                        </div>
                        ` : ''}

                        <!-- Observações -->
                        ${client.notes ? `
                        <div>
                            <h3 class="text-lg font-semibold text-gray-900 dark:text-gray-100 mb-2">Observações</h3>
                            <div class="bg-gray-50 dark:bg-gray-700 rounded-lg p-4">
                                <p class="text-gray-700 dark:text-gray-300 whitespace-pre-wrap">
                                    ${client.notes}
                                </p>
                            </div>
                        </div>
                        ` : ''}

                        <!-- Estatísticas -->
                        <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                            <div class="bg-blue-50 dark:bg-blue-900/30 rounded-lg p-4">
                                <div class="text-sm text-blue-600 dark:text-blue-400 mb-1">Projetos</div>
                                <div class="text-2xl font-bold text-blue-900 dark:text-blue-100">
                                    ${client.projects_count ?? 0}
                                </div>
                            </div>
                            <div class="bg-purple-50 dark:bg-purple-900/30 rounded-lg p-4">
                                <div class="text-sm text-purple-600 dark:text-purple-400 mb-1">Contratos</div>
                                <div class="text-2xl font-bold text-purple-900 dark:text-purple-100">
                                    ${client.contracts_count ?? 0}
                                </div>
                            </div>
                            <div class="bg-green-50 dark:bg-green-900/30 rounded-lg p-4">
                                <div class="text-sm text-green-600 dark:text-green-400 mb-1">Orçamentos</div>
                                <div class="text-2xl font-bold text-green-900 dark:text-green-100">
                                    ${client.budgets_count ?? 0}
                                </div>
                            </div>
                        </div>
                    </div>
                `;
                
                contentDiv.innerHTML = html;
            } catch (error) {
                console.error('Erro ao carregar cliente:', error);
                contentDiv.innerHTML = `
                    <div class="py-8 text-center">
                        <p class="text-sm text-red-600 dark:text-red-400">Erro ao carregar os dados do cliente.</p>
                    </div>
                `;
            }
        };
    </script>
    
     <?php $__env->slot('header', null, []); ?> 
        <div class="flex justify-between items-center">
            <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
                <?php echo e(__('Clientes')); ?>

            </h2>
            <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('create clients')): ?>
            <button onclick="loadClientForm(null)" class="inline-flex items-center px-4 py-2 bg-indigo-600 dark:bg-indigo-700 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-indigo-700 dark:hover:bg-indigo-600 transition-colors">
                <i class="bi bi-plus-circle mr-2"></i>
                Novo Cliente
            </button>
            <?php endif; ?>
        </div>
     <?php $__env->endSlot(); ?>

    <div class="py-6">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-xl sm:rounded-lg">
                <div class="p-6">
                    <!-- Filtros -->
                    <form method="GET" action="<?php echo e(route('clients.index')); ?>" class="mb-6">
                        <div class="grid grid-cols-1 md:grid-cols-4 gap-4">
                            <div>
                                <input type="text" name="search" value="<?php echo e(request('search')); ?>"
                                       placeholder="Buscar por nome, email, CPF ou CNPJ..."
                                       class="w-full border-gray-300 dark:border-gray-600 rounded-md bg-white dark:bg-gray-700 text-gray-900 dark:text-gray-100 focus:ring-indigo-500 focus:border-indigo-500">
                            </div>
                            <div>
                                <select name="type" class="w-full border-gray-300 dark:border-gray-600 rounded-md bg-white dark:bg-gray-700 text-gray-900 dark:text-gray-100 focus:ring-indigo-500 focus:border-indigo-500">
                                    <option value="">Todos os tipos</option>
                                    <option value="individual" <?php echo e(request('type') === 'individual' ? 'selected' : ''); ?>>Pessoa Física</option>
                                    <option value="company" <?php echo e(request('type') === 'company' ? 'selected' : ''); ?>>Pessoa Jurídica</option>
                                </select>
                            </div>
                            <div>
                                <select name="is_active" class="w-full border-gray-300 dark:border-gray-600 rounded-md bg-white dark:bg-gray-700 text-gray-900 dark:text-gray-100 focus:ring-indigo-500 focus:border-indigo-500">
                                    <option value="">Todos</option>
                                    <option value="1" <?php echo e(request('is_active') === '1' ? 'selected' : ''); ?>>Ativos</option>
                                    <option value="0" <?php echo e(request('is_active') === '0' ? 'selected' : ''); ?>>Inativos</option>
                                </select>
                            </div>
                            <div>
                                <?php if (isset($component)) { $__componentOriginal635944f67ec1864e436b88f435140e07 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal635944f67ec1864e436b88f435140e07 = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.button-loading','data' => ['variant' => 'primary','type' => 'submit','class' => 'w-full']] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('button-loading'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['variant' => 'primary','type' => 'submit','class' => 'w-full']); ?>
                                    Filtrar
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

                    <!-- Tabela de Clientes -->
                    <div class="overflow-x-auto">
                        <table class="min-w-full divide-y divide-gray-200 dark:divide-gray-700">
                            <thead class="bg-gray-50 dark:bg-gray-700">
                                <tr>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">Nome</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">Tipo</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">CPF/CNPJ</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">Email</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">Projetos</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">Status</th>
                                    <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">Ações</th>
                                </tr>
                            </thead>
                            <tbody class="bg-white dark:bg-gray-800 divide-y divide-gray-200 dark:divide-gray-700">
                                <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php $__empty_1 = true; $__currentLoopData = $clients; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $client): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                                    <tr class="hover:bg-gray-50 dark:hover:bg-gray-700 transition-colors">
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            <div class="text-sm font-medium text-gray-900 dark:text-gray-100"><?php echo e($client->name); ?></div>
                                            <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($client->trading_name): ?>
                                                <div class="text-sm text-gray-500 dark:text-gray-400"><?php echo e($client->trading_name); ?></div>
                                            <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            <span class="px-2 py-1 text-xs rounded-full <?php echo e($client->type === 'individual' ? 'bg-blue-100 dark:bg-blue-900/30 text-blue-800 dark:text-blue-300' : 'bg-purple-100 dark:bg-purple-900/30 text-purple-800 dark:text-purple-300'); ?>">
                                                <?php echo e($client->type_label); ?>

                                            </span>
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500 dark:text-gray-400">
                                            <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($client->cpf): ?>
                                                <?php echo e($client->formatted_cpf); ?>

                                            <?php elseif($client->cnpj): ?>
                                                <?php echo e($client->formatted_cnpj); ?>

                                            <?php else: ?>
                                                -
                                            <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500 dark:text-gray-400"><?php echo e($client->email); ?></td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500 dark:text-gray-400">
                                            <span class="px-2 py-1 bg-gray-100 dark:bg-gray-700 rounded text-gray-900 dark:text-gray-100"><?php echo e($client->projects_count); ?></span>
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            <span class="px-2 py-1 text-xs rounded-full <?php echo e($client->is_active ? 'bg-green-100 dark:bg-green-900/30 text-green-800 dark:text-green-300' : 'bg-red-100 dark:bg-red-900/30 text-red-800 dark:text-red-300'); ?>">
                                                <?php echo e($client->is_active ? 'Ativo' : 'Inativo'); ?>

                                            </span>
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                                            <div class="flex items-center justify-end space-x-4">
                                                <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('view clients')): ?>
                                                <button
                                                    type="button"
                                                    onclick="if (window.openOffcanvas) { window.openOffcanvas('client-view-offcanvas'); } else if (typeof openOffcanvas === 'function') { openOffcanvas('client-view-offcanvas'); } if (typeof window.loadClientView === 'function') { window.loadClientView(<?php echo e($client->id); ?>); }"
                                                    class="text-gray-600 dark:text-gray-400 hover:text-gray-900 dark:hover:text-gray-100"
                                                    title="Ver detalhes"
                                                >
                                                    <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                                                    </svg>
                                                </button>
                                                <?php endif; ?>
                                                <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('edit clients')): ?>
                                                <button 
                                                    type="button"
                                                    onclick="loadClientForm(<?php echo e($client->id); ?>)"
                                                    class="text-indigo-600 dark:text-indigo-400 hover:text-indigo-900 dark:hover:text-indigo-300"
                                                    title="Editar cliente"
                                                >
                                                    <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                                                    </svg>
                                                </button>
                                                <?php endif; ?>
                                            </div>
                                        </td>
                                    </tr>
                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                                    <tr>
                                        <td colspan="7" class="px-6 py-4 text-center text-gray-500 dark:text-gray-400">
                                            Nenhum cliente encontrado.
                                        </td>
                                    </tr>
                                <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                            </tbody>
                        </table>
                    </div>

                    <!-- Paginação -->
                    <div class="mt-6">
                        <?php echo e($clients->links()); ?>

                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Offcanvas para Novo/Editar Cliente -->
    <?php if (isset($component)) { $__componentOriginal5fd361cc9f4aafccfd6aee776cbb14bc = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal5fd361cc9f4aafccfd6aee776cbb14bc = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.offcanvas','data' => ['id' => 'client-offcanvas','title' => 'Novo Cliente','width' => 'w-full md:w-[700px]']] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('offcanvas'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['id' => 'client-offcanvas','title' => 'Novo Cliente','width' => 'w-full md:w-[700px]']); ?>
        <form method="POST" action="<?php echo e(route('clients.store')); ?>" id="clientForm">
            <?php echo csrf_field(); ?>
            <input type="hidden" name="_method" id="client_method" value="POST">

            <!-- Tipo de Cliente -->
            <div class="mb-6">
                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Tipo de Cliente *</label>
                <div class="flex space-x-4">
                    <label class="flex items-center text-gray-700 dark:text-gray-300">
                        <input type="radio" name="type" value="individual" id="type_individual" class="mr-2 text-indigo-600 focus:ring-indigo-500" checked onchange="toggleClientType()">
                        <span>Pessoa Física</span>
                    </label>
                    <label class="flex items-center text-gray-700 dark:text-gray-300">
                        <input type="radio" name="type" value="company" id="type_company" class="mr-2 text-indigo-600 focus:ring-indigo-500" onchange="toggleClientType()">
                        <span>Pessoa Jurídica</span>
                    </label>
                </div>
                <div id="type_error" class="hidden">
                    <p class="text-red-500 dark:text-red-400 text-xs mt-1"></p>
                </div>
            </div>

            <!-- CPF (Pessoa Física) -->
            <div id="cpf_field" class="mb-4">
                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">CPF *</label>
                <input type="text" name="cpf" id="cpf"
                       class="w-full border-gray-300 dark:border-gray-600 rounded-md bg-white dark:bg-gray-700 text-gray-900 dark:text-gray-100 focus:ring-indigo-500 focus:border-indigo-500"
                       placeholder="000.000.000-00"
                       maxlength="14">
                <div id="cpf_error" class="hidden">
                    <p class="text-red-500 dark:text-red-400 text-xs mt-1"></p>
                </div>
            </div>

            <!-- CNPJ (Pessoa Jurídica) -->
            <div id="cnpj_field" class="mb-4 hidden">
                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">CNPJ *</label>
                <div class="flex space-x-2">
                    <input type="text" name="cnpj" id="cnpj"
                           class="flex-1 border-gray-300 dark:border-gray-600 rounded-md bg-white dark:bg-gray-700 text-gray-900 dark:text-gray-100 focus:ring-indigo-500 focus:border-indigo-500"
                           placeholder="00.000.000/0000-00"
                           maxlength="18">
                    <button type="button" id="search_cnpj_btn"
                            class="px-4 py-2 bg-indigo-600 dark:bg-indigo-700 text-white rounded-md hover:bg-indigo-700 dark:hover:bg-indigo-600 transition-colors"
                            onclick="searchCNPJ()">
                        <i class="bi bi-search mr-2"></i>Buscar
                    </button>
                </div>
                <div id="cnpj_error" class="hidden">
                    <p class="text-red-500 dark:text-red-400 text-xs mt-1"></p>
                </div>
                <div id="cnpj_loading" class="hidden mt-2 text-sm text-gray-600 dark:text-gray-400">
                    <i class="bi bi-hourglass-split mr-2"></i>Buscando dados...
                </div>
            </div>

            <!-- Nome / Razão Social -->
            <div class="mb-4">
                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1" id="name_label">Nome Completo *</label>
                <input type="text" name="name" id="name"
                       class="w-full border-gray-300 dark:border-gray-600 rounded-md bg-white dark:bg-gray-700 text-gray-900 dark:text-gray-100 focus:ring-indigo-500 focus:border-indigo-500" required>
                <div id="name_error" class="hidden">
                    <p class="text-red-500 dark:text-red-400 text-xs mt-1"></p>
                </div>
            </div>

            <!-- Nome Fantasia (Pessoa Jurídica) -->
            <div id="trading_name_field" class="mb-4 hidden">
                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Nome Fantasia</label>
                <input type="text" name="trading_name" id="trading_name"
                       class="w-full border-gray-300 dark:border-gray-600 rounded-md bg-white dark:bg-gray-700 text-gray-900 dark:text-gray-100 focus:ring-indigo-500 focus:border-indigo-500">
                <div id="trading_name_error" class="hidden">
                    <p class="text-red-500 dark:text-red-400 text-xs mt-1"></p>
                </div>
            </div>

            <!-- Email -->
            <div class="mb-4">
                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Email *</label>
                <input type="email" name="email" id="email"
                       autocomplete="email"
                       class="w-full border-gray-300 dark:border-gray-600 rounded-md bg-white dark:bg-gray-700 text-gray-900 dark:text-gray-100 focus:ring-indigo-500 focus:border-indigo-500" required>
                <div id="email_error" class="hidden">
                    <p class="text-red-500 dark:text-red-400 text-xs mt-1"></p>
                </div>
            </div>

            <!-- Telefone -->
            <div class="mb-4">
                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Telefone</label>
                <input type="text" name="phone" id="phone"
                       class="w-full border-gray-300 dark:border-gray-600 rounded-md bg-white dark:bg-gray-700 text-gray-900 dark:text-gray-100 focus:ring-indigo-500 focus:border-indigo-500"
                       placeholder="(00) 00000-0000"
                       maxlength="15">
                <div id="phone_error" class="hidden">
                    <p class="text-red-500 dark:text-red-400 text-xs mt-1"></p>
                </div>
            </div>

            <!-- Endereço -->
            <div class="grid grid-cols-1 md:grid-cols-3 gap-4 mb-4">
                <div class="md:col-span-2">
                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Endereço</label>
                    <input type="text" name="address" id="address"
                           class="w-full border-gray-300 dark:border-gray-600 rounded-md bg-white dark:bg-gray-700 text-gray-900 dark:text-gray-100 focus:ring-indigo-500 focus:border-indigo-500">
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Número</label>
                    <input type="text" name="address_number" id="address_number"
                           class="w-full border-gray-300 dark:border-gray-600 rounded-md bg-white dark:bg-gray-700 text-gray-900 dark:text-gray-100 focus:ring-indigo-500 focus:border-indigo-500">
                </div>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-4">
                <div>
                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Complemento</label>
                    <input type="text" name="address_complement" id="address_complement"
                           class="w-full border-gray-300 dark:border-gray-600 rounded-md bg-white dark:bg-gray-700 text-gray-900 dark:text-gray-100 focus:ring-indigo-500 focus:border-indigo-500">
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Bairro</label>
                    <input type="text" name="neighborhood" id="neighborhood"
                           class="w-full border-gray-300 dark:border-gray-600 rounded-md bg-white dark:bg-gray-700 text-gray-900 dark:text-gray-100 focus:ring-indigo-500 focus:border-indigo-500">
                </div>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-3 gap-4 mb-4">
                <div>
                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Cidade</label>
                    <input type="text" name="city" id="city"
                           class="w-full border-gray-300 dark:border-gray-600 rounded-md bg-white dark:bg-gray-700 text-gray-900 dark:text-gray-100 focus:ring-indigo-500 focus:border-indigo-500">
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Estado</label>
                    <input type="text" name="state" id="state"
                           class="w-full border-gray-300 dark:border-gray-600 rounded-md bg-white dark:bg-gray-700 text-gray-900 dark:text-gray-100 focus:ring-indigo-500 focus:border-indigo-500"
                           placeholder="UF"
                           maxlength="2">
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">CEP</label>
                    <input type="text" name="zip_code" id="zip_code"
                           class="w-full border-gray-300 dark:border-gray-600 rounded-md bg-white dark:bg-gray-700 text-gray-900 dark:text-gray-100 focus:ring-indigo-500 focus:border-indigo-500"
                           placeholder="00000-000"
                           maxlength="10">
                </div>
            </div>

            <!-- Observações -->
            <div class="mb-6">
                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Observações</label>
                <textarea name="notes" id="notes" rows="3"
                          class="w-full border-gray-300 dark:border-gray-600 rounded-md bg-white dark:bg-gray-700 text-gray-900 dark:text-gray-100 focus:ring-indigo-500 focus:border-indigo-500"></textarea>
                <div id="notes_error" class="hidden">
                    <p class="text-red-500 dark:text-red-400 text-xs mt-1"></p>
                </div>
            </div>

            <!-- Status -->
            <div class="mb-6">
                <label class="flex items-center text-gray-700 dark:text-gray-300">
                    <input type="checkbox" name="is_active" value="1" id="is_active" checked class="mr-2 rounded border-gray-300 dark:border-gray-600 text-indigo-600 focus:ring-indigo-500">
                    <span>Cliente ativo</span>
                </label>
            </div>

            <!-- Botões -->
            <div class="flex justify-end space-x-3">
                <button type="button" onclick="closeOffcanvas('client-offcanvas')" class="px-4 py-2 bg-gray-300 dark:bg-gray-600 text-gray-700 dark:text-gray-200 rounded-md hover:bg-gray-400 dark:hover:bg-gray-500 transition-colors">
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
                    Salvar Cliente
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

    <!-- Offcanvas para Visualizar Cliente -->
    <?php if (isset($component)) { $__componentOriginal5fd361cc9f4aafccfd6aee776cbb14bc = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal5fd361cc9f4aafccfd6aee776cbb14bc = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.offcanvas','data' => ['id' => 'client-view-offcanvas','title' => 'Detalhes do Cliente','width' => 'w-full md:w-[700px]']] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('offcanvas'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['id' => 'client-view-offcanvas','title' => 'Detalhes do Cliente','width' => 'w-full md:w-[700px]']); ?>
        <div id="client-view-content">
            <div class="flex justify-center items-center py-12">
                <div class="animate-spin rounded-full h-8 w-8 border-b-2 border-indigo-600"></div>
            </div>
        </div>
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
<script src="<?php echo e(asset('js/client-form.js')); ?>"></script>
<script>
// Garantir que loadClientView exista antes de qualquer chamada inline
window.loadClientView = async function(clientId) {
    const contentDiv = document.getElementById('client-view-content');
    const offcanvasTitle = document.querySelector('#client-view-offcanvas h2');
    
    if (!contentDiv || !offcanvasTitle) return;
    
    // Loading
    contentDiv.innerHTML = '<div class="flex justify-center items-center py-12"><div class="animate-spin rounded-full h-8 w-8 border-b-2 border-indigo-600"></div></div>';
    
    if (window.openOffcanvas) {
        window.openOffcanvas('client-view-offcanvas');
    } else if (typeof openOffcanvas === 'function') {
        openOffcanvas('client-view-offcanvas');
    }
    
    try {
        const response = await fetch(`/clients/${clientId}`, {
            headers: {
                'X-Requested-With': 'XMLHttpRequest',
                'Accept': 'application/json'
            }
        });
        
        if (!response.ok) {
            console.error('Falha na requisição de cliente:', response.status, response.statusText);
            throw new Error('Erro ao carregar cliente');
        }
        
        const responseText = await response.text();
        let data;
        try {
            data = JSON.parse(responseText);
        } catch (e) {
            console.error('Resposta não é JSON válido. Response:', responseText);
            throw new Error('Resposta do servidor inválida ao carregar cliente');
        }
        const client = data.client || data;
        
        offcanvasTitle.textContent = 'Detalhes do Cliente';
        
        let html = `
            <div class="space-y-6">
                <div class="flex items-start justify-between pb-4 border-b border-gray-200 dark:border-gray-700">
                    <div>
                        <h3 class="text-xl font-semibold text-gray-900 dark:text-gray-100 mb-1">
                            ${client.name || ''}
                        </h3>
                        ${client.trading_name ? `
                            <p class="text-sm text-gray-500 dark:text-gray-400">${client.trading_name}</p>
                        ` : ''}
                        <p class="mt-2 text-sm text-gray-500 dark:text-gray-400">
                            ${client.type === 'individual' ? 'Pessoa Física' : 'Pessoa Jurídica'}
                        </p>
                    </div>
                    <span class="px-3 py-1 text-sm rounded-full ${client.is_active ? 'bg-green-100 dark:bg-green-900/30 text-green-800 dark:text-green-300' : 'bg-red-100 dark:bg-red-900/30 text-red-800 dark:text-red-300'}">
                        ${client.is_active ? 'Ativo' : 'Inativo'}
                    </span>
                </div>
                
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div>
                        <h4 class="text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Documento</h4>
                        <p class="text-sm text-gray-900 dark:text-gray-100">
                            ${client.formatted_cpf || client.formatted_cnpj || '-'}
                        </p>
                    </div>
                    <div>
                        <h4 class="text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Email</h4>
                        <p class="text-sm text-gray-900 dark:text-gray-100">
                            ${client.email || '-'}
                        </p>
                    </div>
                    <div>
                        <h4 class="text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Telefone</h4>
                        <p class="text-sm text-gray-900 dark:text-gray-100">
                            ${client.formatted_phone || client.phone || '-'}
                        </p>
                    </div>
                    <div>
                        <h4 class="text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Projetos</h4>
                        <p class="text-sm text-gray-900 dark:text-gray-100">
                            ${client.projects_count ?? 0}
                        </p>
                    </div>
                </div>
                
                <div>
                    <h4 class="text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Endereço</h4>
                    <p class="text-sm text-gray-900 dark:text-gray-100">
                        ${client.address || ''}${client.address_number ? ', ' + client.address_number : ''}${client.address_complement ? ' - ' + client.address_complement : ''}
                    </p>
                    <p class="text-sm text-gray-900 dark:text-gray-100">
                        ${client.neighborhood || ''}${client.city ? ' - ' + client.city : ''}${client.state ? ' / ' + client.state : ''}
                    </p>
                    <p class="text-sm text-gray-900 dark:text-gray-100">
                        ${client.formatted_zip_code || client.zip_code || ''}
                    </p>
                </div>
                
                ${client.notes ? `
                <div>
                    <h4 class="text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Observações</h4>
                    <p class="text-sm text-gray-600 dark:text-gray-400 whitespace-pre-wrap">
                        ${client.notes}
                    </p>
                </div>
                ` : ''}
            </div>
        `;
        
        contentDiv.innerHTML = html;
    } catch (error) {
        console.error('Erro ao carregar cliente:', error);
        contentDiv.innerHTML = `
            <div class="py-8 text-center">
                <p class="text-sm text-red-600 dark:text-red-400">Erro ao carregar os dados do cliente.</p>
            </div>
        `;
    }
};

(function() {
    'use strict';
    
    function clearErrors() {
        document.querySelectorAll('[id$="_error"]').forEach(el => {
            el.classList.add('hidden');
            const p = el.querySelector('p');
            if (p) p.textContent = '';
        });
    }
    
    window.loadClientForm = async function(clientId) {
        const form = document.getElementById('clientForm');
        const offcanvasTitle = document.querySelector('#client-offcanvas h2');
        const methodInput = document.getElementById('client_method');
        
        if (!form || !offcanvasTitle || !methodInput) {
            console.error('Elementos do formulário não encontrados');
            return;
        }
        
        // Limpar formulário
        form.reset();
        clearErrors();
        
        if (clientId) {
            // Modo edição
            offcanvasTitle.textContent = 'Editar Cliente';
            methodInput.value = 'PUT';
            form.action = `/clients/${clientId}`;
            
            try {
                const response = await fetch(`/clients/${clientId}/edit`, {
                    headers: {
                        'X-Requested-With': 'XMLHttpRequest',
                        'Accept': 'application/json'
                    }
                });
                
                if (response.ok) {
                    const data = await response.json();
                    const client = data.client;
                    
                    // Preencher campos
                    if (client.type === 'individual') {
                        document.getElementById('type_individual').checked = true;
                    } else {
                        document.getElementById('type_company').checked = true;
                    }
                    
                    // Preencher campos com formatação
                    if (client.cpf) {
                        const cpfInput = document.getElementById('cpf');
                        if (cpfInput) cpfInput.value = window.formatCPF ? window.formatCPF(client.cpf) : client.cpf;
                    }
                    if (client.cnpj) {
                        const cnpjInput = document.getElementById('cnpj');
                        if (cnpjInput) cnpjInput.value = window.formatCNPJ ? window.formatCNPJ(client.cnpj) : client.cnpj;
                    }
                    if (client.name) document.getElementById('name').value = client.name;
                    if (client.trading_name) document.getElementById('trading_name').value = client.trading_name;
                    if (client.email) document.getElementById('email').value = client.email;
                    if (client.phone) {
                        const phoneInput = document.getElementById('phone');
                        if (phoneInput) phoneInput.value = window.formatPhone ? window.formatPhone(client.phone) : client.phone;
                    }
                    if (client.address) document.getElementById('address').value = client.address;
                    if (client.address_number) document.getElementById('address_number').value = client.address_number;
                    if (client.address_complement) document.getElementById('address_complement').value = client.address_complement;
                    if (client.neighborhood) document.getElementById('neighborhood').value = client.neighborhood;
                    if (client.city) document.getElementById('city').value = client.city;
                    if (client.state) document.getElementById('state').value = client.state;
                    if (client.zip_code) {
                        const zipCodeInput = document.getElementById('zip_code');
                        if (zipCodeInput) zipCodeInput.value = window.formatCEP ? window.formatCEP(client.zip_code) : client.zip_code;
                    }
                    if (client.notes) document.getElementById('notes').value = client.notes;
                    document.getElementById('is_active').checked = client.is_active;
                    
                    // Atualizar tipo de cliente - chamar após um pequeno delay para garantir que o DOM está pronto
                    setTimeout(() => {
                        if (window.toggleClientType) {
                            window.toggleClientType();
                        }
                    }, 50);
                } else {
                    window.location.href = `/clients/${clientId}/edit`;
                    return;
                }
            } catch (error) {
                console.error('Erro ao carregar cliente:', error);
                window.location.href = `/clients/${clientId}/edit`;
                return;
            }
        } else {
            // Modo criação
            offcanvasTitle.textContent = 'Novo Cliente';
            methodInput.value = 'POST';
            form.action = '<?php echo e(route("clients.store")); ?>';
            // Garantir que Pessoa Física está selecionada
            const typeIndividual = document.getElementById('type_individual');
            const typeCompany = document.getElementById('type_company');
            if (typeIndividual && typeCompany) {
                typeIndividual.checked = true;
                typeCompany.checked = false;
            }
        }
        
        if (window.openOffcanvas) {
            window.openOffcanvas('client-offcanvas');
            // Garantir que toggleClientType e máscaras sejam aplicados após o offcanvas abrir completamente
            setTimeout(() => {
                if (window.toggleClientType) {
                    window.toggleClientType();
                }
                if (window.applyClientMasks) {
                    window.applyClientMasks();
                }
                // Anexar handler de submit após o offcanvas abrir
                attachClientFormSubmitHandler();
            }, 300);
        }
    };
    
    // Função para fazer submit do formulário via AJAX
    async function submitClientForm(form, submitButton) {
        const formData = new FormData(form);
        const methodInput = document.getElementById('client_method');
        const method = methodInput ? methodInput.value : 'POST';
        
        if (!submitButton) {
            console.error('Botão de submit não encontrado');
            alert('Erro: Botão de submit não encontrado');
            return;
        }
        
        // Salvar o texto original do botão
        const buttonText = submitButton.querySelector('.button-text');
        const originalText = buttonText ? buttonText.textContent : submitButton.innerHTML;
        
        submitButton.disabled = true;
        // Atualizar texto do botão
        if (buttonText) {
            buttonText.textContent = 'Salvando...';
        } else {
            submitButton.innerHTML = '<i class="bi bi-hourglass-split mr-2"></i>Salvando...';
        }
        
        // Limpar erros anteriores
        document.querySelectorAll('[id$="_error"]').forEach(el => {
            el.classList.add('hidden');
            const p = el.querySelector('p');
            if (p) p.textContent = '';
        });
        
        let url = form.action || '<?php echo e(route("clients.store")); ?>';
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
            
            let data;
            try {
                const responseText = await response.text();
                data = JSON.parse(responseText);
            } catch (jsonError) {
                console.error('Erro ao parsear JSON:', jsonError);
                alert('Erro ao processar resposta do servidor. Verifique o console para mais detalhes.');
                submitButton.disabled = false;
                const btnText = submitButton.querySelector('.button-text');
                if (btnText) {
                    btnText.textContent = originalText;
                } else {
                    submitButton.innerHTML = originalText;
                }
                return;
            }
            
            if (response.ok && data.success) {
                // Cliente salvo com sucesso
                alert(data.message || 'Cliente salvo com sucesso!');
                if (data.redirect) {
                    window.location.href = data.redirect;
                } else {
                    window.location.reload();
                }
            } else if (response.status === 422) {
                // Limpar erros anteriores
                document.querySelectorAll('[id$="_error"]').forEach(el => {
                    el.classList.add('hidden');
                    const p = el.querySelector('p');
                    if (p) p.textContent = '';
                });
                
                // Mostrar novos erros
                Object.keys(data.errors || {}).forEach(field => {
                    const errorDiv = document.getElementById(`${field}_error`);
                    if (errorDiv) {
                        errorDiv.classList.remove('hidden');
                        const p = errorDiv.querySelector('p');
                        if (p) p.textContent = data.errors[field][0];
                    }
                });
                
                submitButton.disabled = false;
                if (buttonText) {
                    buttonText.textContent = originalText;
                } else {
                    submitButton.innerHTML = originalText;
                }
            } else {
                const errorMessage = data.message || data.error || 'Erro ao salvar cliente';
                alert(errorMessage);
                submitButton.disabled = false;
                if (buttonText) {
                    buttonText.textContent = originalText;
                } else {
                    submitButton.innerHTML = originalText;
                }
            }
        } catch (error) {
            console.error('Erro ao salvar cliente:', error);
            alert('Erro ao salvar cliente: ' + error.message);
            submitButton.disabled = false;
            if (buttonText) {
                buttonText.textContent = originalText;
            } else {
                submitButton.innerHTML = originalText;
            }
        }
    }
    
    // Função para anexar handler de submit do formulário de cliente
    function attachClientFormSubmitHandler() {
        const form = document.getElementById('clientForm');
        if (!form) {
            console.error('Formulário clientForm não encontrado');
            return;
        }
        
        const submitButton = form.querySelector('[data-loading-button]') || form.querySelector('button[type="submit"]');
        if (!submitButton) {
            console.error('Botão de submit não encontrado');
            return;
        }
        
        // Remover qualquer listener antigo do form
        if (form._clientFormSubmitHandler) {
            form.removeEventListener('submit', form._clientFormSubmitHandler, { capture: true });
        }
        
        // Remover qualquer listener antigo do botão
        if (submitButton._clientFormClickHandler) {
            submitButton.removeEventListener('click', submitButton._clientFormClickHandler, { capture: true });
        }
        
        // Marcar que já tem handler customizado
        form.setAttribute('data-submit-handler-attached', 'true');
        form.setAttribute('data-custom-submit-handler', 'true');
        
        // Criar handler de submit do formulário
        const submitHandler = async function(e) {
            e.preventDefault();
            e.stopImmediatePropagation();
            e.stopPropagation();
            
            await submitClientForm(form, submitButton);
        };
        
        // Salvar referência do handler
        form._clientFormSubmitHandler = submitHandler;
        
        // Adicionar handler com capture: true para executar antes de outros handlers
        form.addEventListener('submit', submitHandler, { capture: true });
        
        // Também interceptar o clique no botão diretamente como backup
        const clickHandler = async function(e) {
            e.preventDefault();
            e.stopImmediatePropagation();
            e.stopPropagation();
            
            await submitClientForm(form, submitButton);
        };
        
        submitButton._clientFormClickHandler = clickHandler;
        submitButton.addEventListener('click', clickHandler, { capture: true });
    }
    
    // Anexar handler do formulário de cliente quando o DOM estiver pronto
    document.addEventListener('DOMContentLoaded', function() {
        // O formulário está dentro do offcanvas, então será anexado quando o offcanvas abrir

        // Delegação de clique para botões de visualizar cliente (evita depender de onclick global)
        document.addEventListener('click', function(e) {
            const btn = e.target.closest('[data-client-view]');
            if (!btn) return;
            const clientId = btn.getAttribute('data-client-view');
            if (!clientId) return;
            if (typeof window.loadClientView === 'function') {
                window.loadClientView(clientId);
            } else {
                console.warn('loadClientView não está definido');
            }
        });
    });
})();
</script>
<?php $__env->stopPush(); ?>
<?php /**PATH C:\Users\Douglas\Documents\Projetos\stock-master\resources\views/clients/index.blade.php ENDPATH**/ ?>