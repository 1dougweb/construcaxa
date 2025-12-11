<x-app-layout>
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
                form.action = '{{ route("clients.store") }}';
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
    </script>
    
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
                {{ __('Clientes') }}
            </h2>
            @can('create clients')
            <button onclick="loadClientForm(null)" class="inline-flex items-center px-4 py-2 bg-indigo-600 dark:bg-indigo-700 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-indigo-700 dark:hover:bg-indigo-600 transition-colors">
                <i class="bi bi-plus-circle mr-2"></i>
                Novo Cliente
            </button>
            @endcan
        </div>
    </x-slot>

    <div class="py-6">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-xl sm:rounded-lg">
                <div class="p-6">
                    <!-- Filtros -->
                    <form method="GET" action="{{ route('clients.index') }}" class="mb-6">
                        <div class="grid grid-cols-1 md:grid-cols-4 gap-4">
                            <div>
                                <input type="text" name="search" value="{{ request('search') }}"
                                       placeholder="Buscar por nome, email, CPF ou CNPJ..."
                                       class="w-full border-gray-300 dark:border-gray-600 rounded-md bg-white dark:bg-gray-700 text-gray-900 dark:text-gray-100 focus:ring-indigo-500 focus:border-indigo-500">
                            </div>
                            <div>
                                <select name="type" class="w-full border-gray-300 dark:border-gray-600 rounded-md bg-white dark:bg-gray-700 text-gray-900 dark:text-gray-100 focus:ring-indigo-500 focus:border-indigo-500">
                                    <option value="">Todos os tipos</option>
                                    <option value="individual" {{ request('type') === 'individual' ? 'selected' : '' }}>Pessoa Física</option>
                                    <option value="company" {{ request('type') === 'company' ? 'selected' : '' }}>Pessoa Jurídica</option>
                                </select>
                            </div>
                            <div>
                                <select name="is_active" class="w-full border-gray-300 dark:border-gray-600 rounded-md bg-white dark:bg-gray-700 text-gray-900 dark:text-gray-100 focus:ring-indigo-500 focus:border-indigo-500">
                                    <option value="">Todos</option>
                                    <option value="1" {{ request('is_active') === '1' ? 'selected' : '' }}>Ativos</option>
                                    <option value="0" {{ request('is_active') === '0' ? 'selected' : '' }}>Inativos</option>
                                </select>
                            </div>
                            <div>
                                <x-button-loading variant="primary" type="submit" class="w-full">
                                    Filtrar
                                </x-button-loading>
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
                                @forelse($clients as $client)
                                    <tr class="hover:bg-gray-50 dark:hover:bg-gray-700 transition-colors">
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            <div class="text-sm font-medium text-gray-900 dark:text-gray-100">{{ $client->name }}</div>
                                            @if($client->trading_name)
                                                <div class="text-sm text-gray-500 dark:text-gray-400">{{ $client->trading_name }}</div>
                                            @endif
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            <span class="px-2 py-1 text-xs rounded-full {{ $client->type === 'individual' ? 'bg-blue-100 dark:bg-blue-900/30 text-blue-800 dark:text-blue-300' : 'bg-purple-100 dark:bg-purple-900/30 text-purple-800 dark:text-purple-300' }}">
                                                {{ $client->type_label }}
                                            </span>
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500 dark:text-gray-400">
                                            @if($client->cpf)
                                                {{ $client->formatted_cpf }}
                                            @elseif($client->cnpj)
                                                {{ $client->formatted_cnpj }}
                                            @else
                                                -
                                            @endif
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500 dark:text-gray-400">{{ $client->email }}</td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500 dark:text-gray-400">
                                            <span class="px-2 py-1 bg-gray-100 dark:bg-gray-700 rounded text-gray-900 dark:text-gray-100">{{ $client->projects_count }}</span>
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            <span class="px-2 py-1 text-xs rounded-full {{ $client->is_active ? 'bg-green-100 dark:bg-green-900/30 text-green-800 dark:text-green-300' : 'bg-red-100 dark:bg-red-900/30 text-red-800 dark:text-red-300' }}">
                                                {{ $client->is_active ? 'Ativo' : 'Inativo' }}
                                            </span>
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                                            <a href="{{ route('clients.show', $client) }}" class="text-indigo-600 dark:text-indigo-400 hover:text-indigo-900 dark:hover:text-indigo-300 mr-3">Ver</a>
                                            @can('edit clients')
                                            <button onclick="loadClientForm({{ $client->id }})" class="text-yellow-600 dark:text-yellow-400 hover:text-yellow-900 dark:hover:text-yellow-300 mr-3">Editar</button>
                                            @endcan
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="7" class="px-6 py-4 text-center text-gray-500 dark:text-gray-400">
                                            Nenhum cliente encontrado.
                                        </td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>

                    <!-- Paginação -->
                    <div class="mt-6">
                        {{ $clients->links() }}
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Offcanvas para Novo/Editar Cliente -->
    <x-offcanvas id="client-offcanvas" title="Novo Cliente" width="w-full md:w-[700px]">
        <form method="POST" action="{{ route('clients.store') }}" id="clientForm">
            @csrf
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
                <x-button-loading variant="primary" type="submit">
                    Salvar Cliente
                </x-button-loading>
            </div>
        </form>
    </x-offcanvas>
</x-app-layout>

@push('scripts')
<script src="{{ asset('js/client-form.js') }}"></script>
<script>
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
            form.action = '{{ route("clients.store") }}';
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
        
        let url = form.action || '{{ route("clients.store") }}';
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
    });
})();
</script>
@endpush
