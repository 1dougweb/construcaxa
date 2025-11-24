<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center justify-between">
            <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
                {{ __('Editar Cliente') }}
            </h2>
            <a href="{{ route('clients.show', $client) }}" class="text-gray-600 dark:text-gray-400 hover:text-gray-900 dark:hover:text-gray-200 transition-colors">
                <i class="bi bi-arrow-left mr-2"></i>Voltar
            </a>
        </div>
    </x-slot>

    <div class="py-6">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-xl sm:rounded-lg">
                <div class="p-6">
                    <form method="POST" action="{{ route('clients.update', $client) }}" id="clientForm">
                        @csrf
                        @method('PUT')

                        <!-- Tipo de Cliente -->
                        <div class="mb-6">
                            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Tipo de Cliente *</label>
                            <div class="flex space-x-4">
                                <label class="flex items-center text-gray-700 dark:text-gray-300">
                                    <input type="radio" name="type" value="individual" id="type_individual" class="mr-2 text-indigo-600 focus:ring-indigo-500"
                                           {{ $client->type === 'individual' ? 'checked' : '' }} onchange="toggleClientType()">
                                    <span>Pessoa Física</span>
                                </label>
                                <label class="flex items-center text-gray-700 dark:text-gray-300">
                                    <input type="radio" name="type" value="company" id="type_company" class="mr-2 text-indigo-600 focus:ring-indigo-500"
                                           {{ $client->type === 'company' ? 'checked' : '' }} onchange="toggleClientType()">
                                    <span>Pessoa Jurídica</span>
                                </label>
                            </div>
                            @error('type')
                                <p class="text-red-500 dark:text-red-400 text-xs mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- CPF (Pessoa Física) -->
                        <div id="cpf_field" class="mb-4 {{ $client->type === 'company' ? 'hidden' : '' }}">
                            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">CPF *</label>
                            <input type="text" name="cpf" id="cpf"
                                   value="{{ old('cpf', $client->formatted_cpf) }}"
                                   class="w-full border-gray-300 dark:border-gray-600 rounded-md bg-white dark:bg-gray-700 text-gray-900 dark:text-gray-100 focus:ring-indigo-500 focus:border-indigo-500"
                                   placeholder="000.000.000-00"
                                   maxlength="14">
                            @error('cpf')
                                <p class="text-red-500 dark:text-red-400 text-xs mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- CNPJ (Pessoa Jurídica) -->
                        <div id="cnpj_field" class="mb-4 {{ $client->type === 'individual' ? 'hidden' : '' }}">
                            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">CNPJ *</label>
                            <div class="flex space-x-2">
                                <input type="text" name="cnpj" id="cnpj"
                                       value="{{ old('cnpj', $client->formatted_cnpj) }}"
                                       class="flex-1 border-gray-300 dark:border-gray-600 rounded-md bg-white dark:bg-gray-700 text-gray-900 dark:text-gray-100 focus:ring-indigo-500 focus:border-indigo-500"
                                       placeholder="00.000.000/0000-00"
                                       maxlength="18">
                                <button type="button" id="search_cnpj_btn"
                                        class="px-4 py-2 bg-indigo-600 dark:bg-indigo-700 text-white rounded-md hover:bg-indigo-700 dark:hover:bg-indigo-600 transition-colors"
                                        onclick="searchCNPJ()">
                                    <i class="bi bi-search mr-2"></i>Buscar
                                </button>
                            </div>
                            @error('cnpj')
                                <p class="text-red-500 dark:text-red-400 text-xs mt-1">{{ $message }}</p>
                            @enderror
                            <div id="cnpj_loading" class="hidden mt-2 text-sm text-gray-600 dark:text-gray-400">
                                <i class="bi bi-hourglass-split mr-2"></i>Buscando dados...
                            </div>
                        </div>

                        <!-- Nome / Razão Social -->
                        <div class="mb-4">
                            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1" id="name_label">
                                {{ $client->type === 'individual' ? 'Nome Completo *' : 'Razão Social *' }}
                            </label>
                            <input type="text" name="name" id="name"
                                   value="{{ old('name', $client->name) }}"
                                   class="w-full border-gray-300 dark:border-gray-600 rounded-md bg-white dark:bg-gray-700 text-gray-900 dark:text-gray-100 focus:ring-indigo-500 focus:border-indigo-500" required>
                            @error('name')
                                <p class="text-red-500 dark:text-red-400 text-xs mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Nome Fantasia (Pessoa Jurídica) -->
                        <div id="trading_name_field" class="mb-4 {{ $client->type === 'individual' ? 'hidden' : '' }}">
                            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Nome Fantasia</label>
                            <input type="text" name="trading_name" id="trading_name"
                                   value="{{ old('trading_name', $client->trading_name) }}"
                                   class="w-full border-gray-300 dark:border-gray-600 rounded-md bg-white dark:bg-gray-700 text-gray-900 dark:text-gray-100 focus:ring-indigo-500 focus:border-indigo-500">
                            @error('trading_name')
                                <p class="text-red-500 dark:text-red-400 text-xs mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Email -->
                        <div class="mb-4">
                            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Email *</label>
                            <input type="email" name="email" id="email"
                                   value="{{ old('email', $client->email) }}"
                                   autocomplete="email"
                                   class="w-full border-gray-300 dark:border-gray-600 rounded-md bg-white dark:bg-gray-700 text-gray-900 dark:text-gray-100 focus:ring-indigo-500 focus:border-indigo-500" required>
                            @error('email')
                                <p class="text-red-500 dark:text-red-400 text-xs mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Telefone -->
                        <div class="mb-4">
                            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Telefone</label>
                            <input type="text" name="phone" id="phone"
                                   value="{{ old('phone', $client->phone) }}"
                                   class="w-full border-gray-300 dark:border-gray-600 rounded-md bg-white dark:bg-gray-700 text-gray-900 dark:text-gray-100 focus:ring-indigo-500 focus:border-indigo-500"
                                   placeholder="(00) 00000-0000"
                                   maxlength="15">
                            @error('phone')
                                <p class="text-red-500 dark:text-red-400 text-xs mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Endereço -->
                        <div class="grid grid-cols-1 md:grid-cols-3 gap-4 mb-4">
                            <div class="md:col-span-2">
                                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Endereço</label>
                                <input type="text" name="address" id="address"
                                       value="{{ old('address', $client->address) }}"
                                       class="w-full border-gray-300 dark:border-gray-600 rounded-md bg-white dark:bg-gray-700 text-gray-900 dark:text-gray-100 focus:ring-indigo-500 focus:border-indigo-500">
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Número</label>
                                <input type="text" name="address_number" id="address_number"
                                       value="{{ old('address_number', $client->address_number) }}"
                                       class="w-full border-gray-300 dark:border-gray-600 rounded-md bg-white dark:bg-gray-700 text-gray-900 dark:text-gray-100 focus:ring-indigo-500 focus:border-indigo-500">
                            </div>
                        </div>

                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-4">
                            <div>
                                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Complemento</label>
                                <input type="text" name="address_complement" id="address_complement"
                                       value="{{ old('address_complement', $client->address_complement) }}"
                                       class="w-full border-gray-300 dark:border-gray-600 rounded-md bg-white dark:bg-gray-700 text-gray-900 dark:text-gray-100 focus:ring-indigo-500 focus:border-indigo-500">
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Bairro</label>
                                <input type="text" name="neighborhood" id="neighborhood"
                                       value="{{ old('neighborhood', $client->neighborhood) }}"
                                       class="w-full border-gray-300 dark:border-gray-600 rounded-md bg-white dark:bg-gray-700 text-gray-900 dark:text-gray-100 focus:ring-indigo-500 focus:border-indigo-500">
                            </div>
                        </div>

                        <div class="grid grid-cols-1 md:grid-cols-3 gap-4 mb-4">
                            <div>
                                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Cidade</label>
                                <input type="text" name="city" id="city"
                                       value="{{ old('city', $client->city) }}"
                                       class="w-full border-gray-300 dark:border-gray-600 rounded-md bg-white dark:bg-gray-700 text-gray-900 dark:text-gray-100 focus:ring-indigo-500 focus:border-indigo-500">
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Estado</label>
                                <input type="text" name="state" id="state"
                                       value="{{ old('state', $client->state) }}"
                                       class="w-full border-gray-300 dark:border-gray-600 rounded-md bg-white dark:bg-gray-700 text-gray-900 dark:text-gray-100 focus:ring-indigo-500 focus:border-indigo-500"
                                       placeholder="UF"
                                       maxlength="2">
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">CEP</label>
                                <input type="text" name="zip_code" id="zip_code"
                                       value="{{ old('zip_code', $client->zip_code) }}"
                                       class="w-full border-gray-300 dark:border-gray-600 rounded-md bg-white dark:bg-gray-700 text-gray-900 dark:text-gray-100 focus:ring-indigo-500 focus:border-indigo-500"
                                       placeholder="00000-000"
                                       maxlength="10">
                            </div>
                        </div>

                        <!-- Observações -->
                        <div class="mb-6">
                            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Observações</label>
                            <textarea name="notes" id="notes" rows="3"
                                      class="w-full border-gray-300 dark:border-gray-600 rounded-md bg-white dark:bg-gray-700 text-gray-900 dark:text-gray-100 focus:ring-indigo-500 focus:border-indigo-500">{{ old('notes', $client->notes) }}</textarea>
                            @error('notes')
                                <p class="text-red-500 dark:text-red-400 text-xs mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Status -->
                        <div class="mb-6">
                            <label class="flex items-center text-gray-700 dark:text-gray-300">
                                <input type="checkbox" name="is_active" value="1"
                                       {{ old('is_active', $client->is_active) ? 'checked' : '' }} class="mr-2 rounded border-gray-300 dark:border-gray-600 text-indigo-600 focus:ring-indigo-500">
                                <span>Cliente ativo</span>
                            </label>
                        </div>

                        <!-- Seção de Contratos -->
                        <div class="mb-6 border-t border-gray-200 dark:border-gray-700 pt-6">
                            <h3 class="text-lg font-semibold text-gray-900 dark:text-gray-100 mb-4">Contratos</h3>
                            @if($client->contracts->count() > 0)
                                <div class="space-y-2">
                                    @foreach($client->contracts as $contract)
                                        <div class="flex items-center justify-between p-3 bg-gray-50 dark:bg-gray-700 rounded-md">
                                            <div>
                                                <span class="font-medium text-gray-900 dark:text-gray-100">{{ $contract->contract_number }}</span>
                                                <span class="text-sm text-gray-600 dark:text-gray-400 ml-2">{{ $contract->title }}</span>
                                            </div>
                                            <a href="{{ route('contracts.show', $contract) }}"
                                               class="text-indigo-600 dark:text-indigo-400 hover:text-indigo-900 dark:hover:text-indigo-300 text-sm transition-colors">
                                                Ver contrato
                                            </a>
                                        </div>
                                    @endforeach
                                </div>
                            @else
                                <p class="text-gray-500 dark:text-gray-400 text-sm">Nenhum contrato vinculado a este cliente.</p>
                            @endif
                            <a href="{{ route('contracts.create', ['client_id' => $client->id]) }}"
                               class="mt-4 inline-flex items-center px-4 py-2 bg-indigo-600 dark:bg-indigo-700 text-white rounded-md hover:bg-indigo-700 dark:hover:bg-indigo-600 transition-colors">
                                <i class="bi bi-plus-circle mr-2"></i>
                                Adicionar Contrato
                            </a>
                        </div>

                        <!-- Botões -->
                        <div class="flex justify-end space-x-3">
                            <a href="{{ route('clients.show', $client) }}" class="px-4 py-2 bg-gray-300 dark:bg-gray-600 text-gray-700 dark:text-gray-200 rounded-md hover:bg-gray-400 dark:hover:bg-gray-500 transition-colors">
                                Cancelar
                            </a>
                            <x-button-loading variant="primary" type="submit">
                                Atualizar Cliente
                            </x-button-loading>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    @push('scripts')
    <script src="{{ asset('js/client-form.js') }}"></script>
    <script>
        // Garantir que a função searchCNPJ seja sobrescrita após qualquer código Livewire
        // Esta função NÃO usa @this - é para formulários HTML puros
        window.searchCNPJ = async function() {
            try {
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

                if (loadingDiv) {
                    loadingDiv.classList.remove('hidden');
                }
                if (searchBtn) {
                    searchBtn.disabled = true;
                    searchBtn.innerHTML = '<i class="bi bi-hourglass-split mr-2"></i>Buscando...';
                }

                const csrfToken = document.querySelector('meta[name="csrf-token"]');
                if (!csrfToken) {
                    throw new Error('Token CSRF não encontrado');
                }

                const response = await fetch(`/api/clients/fetch-cnpj?cnpj=${cnpj}`, {
                    headers: {
                        'X-CSRF-TOKEN': csrfToken.content,
                        'Accept': 'application/json'
                    }
                });

                if (!response.ok) {
                    throw new Error(`Erro na requisição: ${response.status}`);
                }

                const data = await response.json();

                if (data.success) {
                    // Preencher campos com verificações de existência
                    const fields = {
                        'name': data.data.name || '',
                        'trading_name': data.data.trading_name || '',
                        'address': data.data.address || '',
                        'neighborhood': data.data.neighborhood || '',
                        'city': data.data.city || '',
                        'state': data.data.state || '',
                        'zip_code': data.data.zip_code || '',
                        'phone': data.data.phone || '',
                        'email': data.data.email || ''
                    };

                    Object.keys(fields).forEach(fieldId => {
                        const field = document.getElementById(fieldId);
                        if (field) {
                            // Formatar valores se necessário
                            if (fieldId === 'zip_code' && fields[fieldId]) {
                                field.value = fields[fieldId].replace(/(\d{5})(\d)/, '$1-$2');
                            } else if (fieldId === 'phone' && fields[fieldId]) {
                                const phone = fields[fieldId].replace(/\D/g, '');
                                if (phone.length <= 10) {
                                    field.value = phone.replace(/(\d{2})(\d)/, '($1) $2').replace(/(\d{4})(\d)/, '$1-$2');
                                } else {
                                    field.value = phone.replace(/(\d{2})(\d)/, '($1) $2').replace(/(\d{5})(\d)/, '$1-$2');
                                }
                            } else {
                                field.value = fields[fieldId];
                            }
                        }
                    });
                } else {
                    alert('Erro ao buscar CNPJ: ' + (data.message || 'Erro desconhecido'));
                }
            } catch (error) {
                console.error('Erro ao buscar CNPJ:', error);
                alert('Erro ao buscar CNPJ: ' + error.message);
            } finally {
                const loadingDiv = document.getElementById('cnpj_loading');
                const searchBtn = document.getElementById('search_cnpj_btn');

                if (loadingDiv) {
                    loadingDiv.classList.add('hidden');
                }
                if (searchBtn) {
                    searchBtn.disabled = false;
                    searchBtn.innerHTML = '<i class="bi bi-search mr-2"></i>Buscar';
                }
            }
        };

        // Inicializar tipo de cliente ao carregar
        document.addEventListener('DOMContentLoaded', function() {
            toggleClientType();
        });
    </script>
    @endpush
</x-app-layout>


