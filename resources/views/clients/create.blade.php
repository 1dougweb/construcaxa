<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center justify-between">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                {{ __('Novo Cliente') }}
            </h2>
            <a href="{{ route('clients.index') }}" class="text-gray-600 hover:text-gray-900">
                <i class="bi bi-arrow-left mr-2"></i>Voltar
            </a>
        </div>
    </x-slot>

    <div class="py-6">
        <div class="max-w-4xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-xl sm:rounded-lg">
                <div class="p-6">
                    <form method="POST" action="{{ route('clients.store') }}" id="clientForm">
                        @csrf

                        <!-- Tipo de Cliente -->
                        <div class="mb-6">
                            <label class="block text-sm font-medium text-gray-700 mb-2">Tipo de Cliente *</label>
                            <div class="flex space-x-4">
                                <label class="flex items-center">
                                    <input type="radio" name="type" value="individual" id="type_individual" class="mr-2" checked onchange="toggleClientType()">
                                    <span>Pessoa Física</span>
                                </label>
                                <label class="flex items-center">
                                    <input type="radio" name="type" value="company" id="type_company" class="mr-2" onchange="toggleClientType()">
                                    <span>Pessoa Jurídica</span>
                                </label>
                            </div>
                            @error('type')
                                <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- CPF (Pessoa Física) -->
                        <div id="cpf_field" class="mb-4">
                            <label class="block text-sm font-medium text-gray-700 mb-1">CPF *</label>
                            <input type="text" name="cpf" id="cpf" 
                                   class="w-full border-gray-300 rounded-md" 
                                   placeholder="000.000.000-00"
                                   maxlength="14">
                            @error('cpf')
                                <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- CNPJ (Pessoa Jurídica) -->
                        <div id="cnpj_field" class="mb-4 hidden">
                            <label class="block text-sm font-medium text-gray-700 mb-1">CNPJ *</label>
                            <div class="flex space-x-2">
                                <input type="text" name="cnpj" id="cnpj" 
                                       class="flex-1 border-gray-300 rounded-md" 
                                       placeholder="00.000.000/0000-00"
                                       maxlength="18">
                                <button type="button" id="search_cnpj_btn" 
                                        class="px-4 py-2 bg-indigo-600 text-white rounded-md hover:bg-indigo-700"
                                        onclick="searchCNPJ()">
                                    <i class="bi bi-search mr-2"></i>Buscar
                                </button>
                            </div>
                            @error('cnpj')
                                <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                            @enderror
                            <div id="cnpj_loading" class="hidden mt-2 text-sm text-gray-600">
                                <i class="bi bi-hourglass-split mr-2"></i>Buscando dados...
                            </div>
                        </div>

                        <!-- Nome / Razão Social -->
                        <div class="mb-4">
                            <label class="block text-sm font-medium text-gray-700 mb-1" id="name_label">Nome Completo *</label>
                            <input type="text" name="name" id="name" 
                                   value="{{ old('name') }}"
                                   class="w-full border-gray-300 rounded-md" required>
                            @error('name')
                                <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Nome Fantasia (Pessoa Jurídica) -->
                        <div id="trading_name_field" class="mb-4 hidden">
                            <label class="block text-sm font-medium text-gray-700 mb-1">Nome Fantasia</label>
                            <input type="text" name="trading_name" id="trading_name" 
                                   value="{{ old('trading_name') }}"
                                   class="w-full border-gray-300 rounded-md">
                            @error('trading_name')
                                <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Email -->
                        <div class="mb-4">
                            <label class="block text-sm font-medium text-gray-700 mb-1">Email *</label>
                            <input type="email" name="email" id="email" 
                                   value="{{ old('email') }}"
                                   class="w-full border-gray-300 rounded-md" required>
                            @error('email')
                                <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Telefone -->
                        <div class="mb-4">
                            <label class="block text-sm font-medium text-gray-700 mb-1">Telefone</label>
                            <input type="text" name="phone" id="phone" 
                                   value="{{ old('phone') }}"
                                   class="w-full border-gray-300 rounded-md"
                                   placeholder="(00) 00000-0000"
                                   maxlength="15">
                            @error('phone')
                                <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Endereço -->
                        <div class="grid grid-cols-1 md:grid-cols-3 gap-4 mb-4">
                            <div class="md:col-span-2">
                                <label class="block text-sm font-medium text-gray-700 mb-1">Endereço</label>
                                <input type="text" name="address" id="address" 
                                       value="{{ old('address') }}"
                                       class="w-full border-gray-300 rounded-md">
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-1">Número</label>
                                <input type="text" name="address_number" id="address_number" 
                                       value="{{ old('address_number') }}"
                                       class="w-full border-gray-300 rounded-md">
                            </div>
                        </div>

                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-4">
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-1">Complemento</label>
                                <input type="text" name="address_complement" id="address_complement" 
                                       value="{{ old('address_complement') }}"
                                       class="w-full border-gray-300 rounded-md">
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-1">Bairro</label>
                                <input type="text" name="neighborhood" id="neighborhood" 
                                       value="{{ old('neighborhood') }}"
                                       class="w-full border-gray-300 rounded-md">
                            </div>
                        </div>

                        <div class="grid grid-cols-1 md:grid-cols-3 gap-4 mb-4">
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-1">Cidade</label>
                                <input type="text" name="city" id="city" 
                                       value="{{ old('city') }}"
                                       class="w-full border-gray-300 rounded-md">
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-1">Estado</label>
                                <input type="text" name="state" id="state" 
                                       value="{{ old('state') }}"
                                       class="w-full border-gray-300 rounded-md"
                                       placeholder="UF"
                                       maxlength="2">
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-1">CEP</label>
                                <input type="text" name="zip_code" id="zip_code" 
                                       value="{{ old('zip_code') }}"
                                       class="w-full border-gray-300 rounded-md"
                                       placeholder="00000-000"
                                       maxlength="10">
                            </div>
                        </div>

                        <!-- Observações -->
                        <div class="mb-6">
                            <label class="block text-sm font-medium text-gray-700 mb-1">Observações</label>
                            <textarea name="notes" id="notes" rows="3" 
                                      class="w-full border-gray-300 rounded-md">{{ old('notes') }}</textarea>
                            @error('notes')
                                <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Status -->
                        <div class="mb-6">
                            <label class="flex items-center">
                                <input type="checkbox" name="is_active" value="1" checked class="mr-2">
                                <span>Cliente ativo</span>
                            </label>
                        </div>

                        <!-- Botões -->
                        <div class="flex justify-end space-x-3">
                            <a href="{{ route('clients.index') }}" class="px-4 py-2 bg-gray-300 text-gray-700 rounded-md hover:bg-gray-400">
                                Cancelar
                            </a>
                            <button type="submit" class="px-4 py-2 bg-indigo-600 text-white rounded-md hover:bg-indigo-700">
                                Salvar Cliente
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    @push('scripts')
    <script src="{{ asset('js/client-form.js') }}"></script>
    @endpush
</x-app-layout>