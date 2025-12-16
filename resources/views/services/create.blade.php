<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Novo Serviço') }}
        </h2>
    </x-slot>

    <div class="p-4">
        <div class="max-w-2xl mx-auto">
            <form action="{{ route('services.store') }}" method="POST">
                @csrf

                <div class="bg-white shadow rounded-md p-6 space-y-6">
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <!-- Name -->
                        <div class="md:col-span-2">
                            <label class="block text-sm font-medium text-gray-700 mb-1">Nome do Serviço *</label>
                            <input type="text" name="name" value="{{ old('name') }}" required
                                   class="w-full border-gray-300 rounded-md"
                                   placeholder="Ex: Instalação Elétrica">
                            @error('name')<p class="text-red-500 text-xs mt-1">{{ $message }}</p>@enderror
                        </div>

                        <!-- Category -->
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Categoria *</label>
                            <select name="category_id" required class="w-full border-gray-300 rounded-md">
                                <option value="">Selecione uma categoria</option>
                                @foreach($categories as $category)
                                    <option value="{{ $category->id }}" {{ old('category_id') == $category->id ? 'selected' : '' }}>
                                        {{ $category->name }}
                                    </option>
                                @endforeach
                            </select>
                            @error('category_id')<p class="text-red-500 text-xs mt-1">{{ $message }}</p>@enderror
                        </div>

                        <!-- Unit Type -->
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Tipo de Cobrança *</label>
                            <select name="unit_type" required class="w-full border-gray-300 rounded-md" onchange="function updatePriceLabel() {

                            }
                            updatePriceLabel()">
                                @foreach($unitTypes as $value => $label)
                                    <option value="{{ $value }}" {{ old('unit_type', 'hour') == $value ? 'selected' : '' }}>
                                        {{ $label }}
                                    </option>
                                @endforeach
                            </select>
                            @error('unit_type')<p class="text-red-500 text-xs mt-1">{{ $message }}</p>@enderror
                        </div>

                        <!-- Default Price -->
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">
                                <span id="price-label">Preço Padrão (R$) *</span>
                            </label>
                            <input type="number" name="default_price" value="{{ old('default_price') }}"
                                   step="0.01" min="0" required class="w-full border-gray-300 rounded-md">
                            @error('default_price')<p class="text-red-500 text-xs mt-1">{{ $message }}</p>@enderror
                        </div>

                        <!-- Minimum Price -->
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Preço Mínimo (R$)</label>
                            <input type="number" name="minimum_price" value="{{ old('minimum_price') }}"
                                   step="0.01" min="0" class="w-full border-gray-300 rounded-md">
                            @error('minimum_price')<p class="text-red-500 text-xs mt-1">{{ $message }}</p>@enderror
                        </div>

                        <!-- Maximum Price -->
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Preço Máximo (R$)</label>
                            <input type="number" name="maximum_price" value="{{ old('maximum_price') }}"
                                   step="0.01" min="0" class="w-full border-gray-300 rounded-md">
                            @error('maximum_price')<p class="text-red-500 text-xs mt-1">{{ $message }}</p>@enderror
                        </div>
                    </div>

                    <!-- Description -->
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Descrição</label>
                        <textarea name="description" rows="3" class="w-full border-gray-300 rounded-md"
                                  placeholder="Descreva o serviço, incluindo o que está incluído...">{{ old('description') }}</textarea>
                        @error('description')<p class="text-red-500 text-xs mt-1">{{ $message }}</p>@enderror
                    </div>

                    <!-- Active Status -->
                    <div class="flex items-center">
                        <input type="checkbox" name="is_active" id="is_active" value="1"
                               {{ old('is_active', true) ? 'checked' : '' }} class="rounded border-gray-300">
                        <label for="is_active" class="ml-2 text-sm text-gray-700">Serviço ativo</label>
                    </div>

                    <!-- Buttons -->
                    <div class="flex justify-end gap-3 pt-4 border-t">
                        <a href="{{ route('services.index') }}"
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

    @push('scripts')
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
    @endpush
</x-app-layout>
