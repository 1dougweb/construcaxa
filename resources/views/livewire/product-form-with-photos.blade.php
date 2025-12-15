<div>
    <form wire:submit.prevent="save">
        <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">
            <!-- Nome -->
            <div>
                <label for="name" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Nome do Produto *</label>
                <input type="text" wire:model="name" id="name" 
                       class="mt-1 block w-full rounded-md border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 dark:focus:border-indigo-400 dark:focus:ring-indigo-400">
                @error('name') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
            </div>

            <!-- SKU -->
            <div>
                <label for="sku" class="block text-sm font-medium text-gray-700 dark:text-gray-300">SKU</label>
                <div class="mt-1 flex rounded-md shadow-sm">
                    <input type="text" wire:model="sku" id="sku" 
                           class="flex-1 block w-full rounded-none rounded-l-md border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-gray-300 focus:border-indigo-500 focus:ring-indigo-500 dark:focus:border-indigo-400 dark:focus:ring-indigo-400">
                    <button type="button" wire:click="generateSku" 
                            class="inline-flex items-center px-3 py-2 border border-l-0 border-gray-300 dark:border-gray-600 rounded-r-md bg-gray-50 dark:bg-gray-700 text-gray-500 dark:text-gray-300 text-sm hover:bg-gray-100 dark:hover:bg-gray-600">
                        Gerar
                    </button>
                </div>
                @error('sku') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
            </div>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">
            <!-- Categoria -->
            <div>
                <label for="category_id" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Categoria *</label>
                <select wire:model="category_id" id="category_id" 
                        class="mt-1 block w-full rounded-md border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 dark:focus:border-indigo-400 dark:focus:ring-indigo-400">
                    <option value="">Selecione uma categoria</option>
                    @foreach($categories as $category)
                        <option value="{{ $category->id }}">{{ $category->name }}</option>
                    @endforeach
                </select>
                @error('category_id') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
            </div>

            <!-- Fornecedor -->
            <div>
                <label for="supplier_id" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Fornecedor</label>
                <select wire:model="supplier_id" id="supplier_id" 
                        class="mt-1 block w-full rounded-md border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 dark:focus:border-indigo-400 dark:focus:ring-indigo-400">
                    <option value="">Selecione um fornecedor</option>
                    @foreach($suppliers as $supplier)
                        <option value="{{ $supplier->id }}">{{ $supplier->name }}</option>
                    @endforeach
                </select>
                @error('supplier_id') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
            </div>
        </div>

        <!-- Descrição -->
        <div class="mb-6">
            <label for="description" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Descrição</label>
            <textarea wire:model="description" id="description" rows="3" 
                      class="mt-1 block w-full rounded-md border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 dark:focus:border-indigo-400 dark:focus:ring-indigo-400"
                      placeholder="Descreva o produto..."></textarea>
            @error('description') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
        </div>

        <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-6">
            <!-- Preço -->
            <div>
                <label for="price" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Preço *</label>
                <div class="mt-1 relative rounded-md shadow-sm">
                    <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                        <span class="text-gray-500 dark:text-gray-400 sm:text-sm">R$</span>
                    </div>
                    <input type="number" wire:model="price" id="price" step="0.01" min="0"
                           class="pl-8 block w-full rounded-md border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 dark:focus:border-indigo-400 dark:focus:ring-indigo-400">
                </div>
                @error('price') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
            </div>

            <!-- Estoque -->
            <div>
                <label for="stock" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Estoque *</label>
                <input type="number" wire:model="stock" id="stock" step="0.01" min="0"
                       class="mt-1 block w-full rounded-md border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 dark:focus:border-indigo-400 dark:focus:ring-indigo-400">
                @error('stock') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
            </div>

            <!-- Estoque Mínimo -->
            <div>
                <label for="min_stock" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Estoque Mínimo *</label>
                <input type="number" wire:model="min_stock" id="min_stock" step="0.01" min="0"
                       class="mt-1 block w-full rounded-md border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 dark:focus:border-indigo-400 dark:focus:ring-indigo-400">
                @error('min_stock') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
            </div>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">
            <!-- Unidade de Medida -->
            <div>
                <label for="measurement_unit" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Unidade de Medida *</label>
                <select wire:model="measurement_unit" id="measurement_unit" 
                        class="mt-1 block w-full rounded-md border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 dark:focus:border-indigo-400 dark:focus:ring-indigo-400">
                    <option value="unit">Unidade</option>
                    <option value="weight">Peso</option>
                    <option value="length">Metragem</option>
                </select>
                @error('measurement_unit') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
            </div>

            <!-- Rótulo da Unidade -->
            <div>
                <label for="unit_label" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Rótulo da Unidade *</label>
                <input type="text" wire:model="unit_label" id="unit_label" 
                       class="mt-1 block w-full rounded-md border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 dark:focus:border-indigo-400 dark:focus:ring-indigo-400">
                @error('unit_label') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
            </div>
        </div>

        <!-- Fotos Existentes -->
        @if(count($existingPhotos) > 0)
        <div class="mb-6">
            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-3">Fotos Atuais</label>
            <div class="grid grid-cols-2 md:grid-cols-4 gap-4">
                @foreach($existingPhotos as $index => $photo)
                    <div class="relative">
                        <img src="/{{ ltrim($photo, '/') }}" alt="Foto do produto" 
                             class="w-full h-24 object-cover rounded-lg">
                        <button type="button" wire:click="removeExistingPhoto({{ $index }})" 
                                class="absolute -top-2 -right-2 bg-red-500 text-white rounded-full w-6 h-6 flex items-center justify-center text-xs hover:bg-red-600">
                            ×
                        </button>
                    </div>
                @endforeach
            </div>
        </div>
        @endif

        <!-- Upload de Fotos -->
        <div class="mb-6">
            <label for="photos" class="block text-sm font-medium text-gray-700 dark:text-gray-300">
                {{ count($existingPhotos) > 0 ? 'Adicionar Mais Fotos' : 'Fotos do Produto' }}
            </label>
            <div class="mt-1 flex justify-center px-6 pt-5 pb-6 border-2 border-gray-300 dark:border-gray-600 border-dashed rounded-md">
                <div class="space-y-1 text-center">
                    <svg class="mx-auto h-12 w-12 text-gray-400 dark:text-gray-500" stroke="currentColor" fill="none" viewBox="0 0 48 48">
                        <path d="M28 8H12a4 4 0 00-4 4v20m32-12v8m0 0v8a4 4 0 01-4 4H12a4 4 0 01-4-4v-4m32-4l-3.172-3.172a4 4 0 00-5.656 0L28 28M8 32l9.172-9.172a4 4 0 015.656 0L28 28m0 0l4 4m4-24h8m-4-4v8m-12 4h.02" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" />
                    </svg>
                    <div class="flex text-sm text-gray-600 dark:text-gray-400">
                        <label for="photos" class="relative cursor-pointer bg-white dark:bg-gray-800 rounded-md font-medium text-indigo-600 dark:text-indigo-400 hover:text-indigo-500 dark:hover:text-indigo-300 focus-within:outline-none focus-within:ring-2 focus-within:ring-offset-2 focus-within:ring-indigo-500">
                            <span>Carregar fotos</span>
                            <input id="photos" wire:model="photos" type="file" multiple accept="image/*" class="sr-only">
                        </label>
                        <p class="pl-1">ou arraste e solte</p>
                    </div>
                    <p class="text-xs text-gray-500 dark:text-gray-400">PNG, JPG, GIF até 2MB cada</p>
                </div>
            </div>
            @error('photos.*') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
        </div>

        <!-- Preview das Novas Fotos -->
        @if(count($photos) > 0)
        <div class="mb-6">
            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-3">Novas Fotos</label>
            <div class="grid grid-cols-2 md:grid-cols-4 gap-4">
                @foreach($photos as $index => $photo)
                    <div class="relative">
                        @if($photo)
                            <img src="{{ $photo->temporaryUrl() }}" alt="Preview" 
                                 class="w-full h-24 object-cover rounded-lg">
                            <button type="button" wire:click="removePhoto({{ $index }})" 
                                    class="absolute -top-2 -right-2 bg-red-500 text-white rounded-full w-6 h-6 flex items-center justify-center text-xs hover:bg-red-600">
                                ×
                            </button>
                        @endif
                    </div>
                @endforeach
            </div>
        </div>
        @endif

        <!-- Botões -->
        <div class="flex justify-end space-x-3">
            <a href="{{ route('products.index') }}" 
               class="bg-gray-300 dark:bg-gray-600 hover:bg-gray-400 dark:hover:bg-gray-500 text-gray-700 dark:text-gray-300 font-bold py-2 px-4 rounded">
                Cancelar
            </a>
            <button type="submit" 
                    class="bg-indigo-600 dark:bg-indigo-700 hover:bg-indigo-700 dark:hover:bg-indigo-600 text-white font-bold py-2 px-4 rounded">
                {{ $product ? 'Atualizar' : 'Cadastrar' }} Produto
            </button>
        </div>
    </form>
</div>