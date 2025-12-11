<x-app-layout>
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
    async function loadClientForm(clientId) {
        const form = document.getElementById('clientForm');
        const offcanvasTitle = document.querySelector('#client-offcanvas h2');
        const methodInput = document.getElementById('client_method');
        
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
                    
                    if (client.cpf) document.getElementById('cpf').value = client.cpf;
                    if (client.cnpj) document.getElementById('cnpj').value = client.cnpj;
                    if (client.name) document.getElementById('name').value = client.name;
                    if (client.trading_name) document.getElementById('trading_name').value = client.trading_name;
                    if (client.email) document.getElementById('email').value = client.email;
                    if (client.phone) document.getElementById('phone').value = client.phone;
                    if (client.address) document.getElementById('address').value = client.address;
                    if (client.address_number) document.getElementById('address_number').value = client.address_number;
                    if (client.address_complement) document.getElementById('address_complement').value = client.address_complement;
                    if (client.neighborhood) document.getElementById('neighborhood').value = client.neighborhood;
                    if (client.city) document.getElementById('city').value = client.city;
                    if (client.state) document.getElementById('state').value = client.state;
                    if (client.zip_code) document.getElementById('zip_code').value = client.zip_code;
                    if (client.notes) document.getElementById('notes').value = client.notes;
                    document.getElementById('is_active').checked = client.is_active;
                    
                    // Atualizar tipo de cliente
                    toggleClientType();
                } else {
                    // Fallback: redirecionar para página de edição
                    window.location.href = `/clients/${clientId}/edit`;
                    return;
                }
            } catch (error) {
                console.error('Erro ao carregar cliente:', error);
                // Fallback: redirecionar para página de edição
                window.location.href = `/clients/${clientId}/edit`;
                return;
            }
        } else {
            // Modo criação
            offcanvasTitle.textContent = 'Novo Cliente';
            methodInput.value = 'POST';
            form.action = '{{ route("clients.store") }}';
            toggleClientType();
        }
        
        openOffcanvas('client-offcanvas');
    }
    
    function clearErrors() {
        document.querySelectorAll('[id$="_error"]').forEach(el => {
            el.classList.add('hidden');
            el.querySelector('p').textContent = '';
        });
    }
    
    // Interceptar submissão do formulário
    document.getElementById('clientForm').addEventListener('submit', async function(e) {
        e.preventDefault();
        
        const form = e.target;
        const formData = new FormData(form);
        const method = document.getElementById('client_method').value;
        const submitButton = form.querySelector('button[type="submit"]');
        const originalText = submitButton.innerHTML;
        
        // Desabilitar botão
        submitButton.disabled = true;
        submitButton.innerHTML = '<i class="bi bi-hourglass-split mr-2"></i>Salvando...';
        
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
                // Sucesso
                if (data.redirect) {
                    window.location.href = data.redirect;
                } else {
                    window.location.reload();
                }
            } else if (response.status === 422) {
                // Erros de validação
                clearErrors();
                
                Object.keys(data.errors || {}).forEach(field => {
                    const errorDiv = document.getElementById(`${field}_error`);
                    if (errorDiv) {
                        errorDiv.classList.remove('hidden');
                        errorDiv.querySelector('p').textContent = data.errors[field][0];
                    }
                });
                
                // Reabilitar botão
                submitButton.disabled = false;
                submitButton.innerHTML = originalText;
            } else {
                alert(data.message || 'Erro ao salvar cliente');
                submitButton.disabled = false;
                submitButton.innerHTML = originalText;
            }
        } catch (error) {
            console.error('Erro:', error);
            alert('Erro ao salvar cliente');
            submitButton.disabled = false;
            submitButton.innerHTML = originalText;
        }
    });
</script>
@endpush



