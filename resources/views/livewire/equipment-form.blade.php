<div>
    <form wire:submit.prevent="save">
        <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">
            <!-- Nome -->
            <div>
                <label for="name" class="block text-sm font-medium text-gray-700">Nome do Equipamento *</label>
                <input type="text" wire:model="name" id="name" 
                       class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                @error('name') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
            </div>

            <!-- Número de Série -->
            <div>
                <label for="serial_number" class="block text-sm font-medium text-gray-700">Número de Série *</label>
                <input type="text" wire:model="serial_number" id="serial_number" 
                       class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                @error('serial_number') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
            </div>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">
            <!-- Categoria -->
            <div>
                <label for="category_id" class="block text-sm font-medium text-gray-700">Categoria</label>
                <select wire:model="category_id" id="category_id" 
                        class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                    <option value="">Selecione uma categoria</option>
                    @foreach($categories as $category)
                        <option value="{{ $category->id }}">{{ $category->name }}</option>
                    @endforeach
                </select>
                @error('category_id') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
            </div>

            <!-- Status -->
            @if($equipment)
            <div>
                <label for="status" class="block text-sm font-medium text-gray-700">Status *</label>
                <select wire:model="status" id="status" 
                        class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                    <option value="available">Disponível</option>
                    <option value="borrowed">Emprestado</option>
                    <option value="maintenance">Manutenção</option>
                    <option value="retired">Aposentado</option>
                </select>
                @error('status') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
            </div>
            @endif
        </div>

        <!-- Descrição -->
        <div class="mb-6">
            <label for="description" class="block text-sm font-medium text-gray-700">Descrição</label>
            <textarea wire:model="description" id="description" rows="3" 
                      class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500"
                      placeholder="Descreva o equipamento..."></textarea>
            @error('description') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
        </div>

        <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">
            <!-- Preço de Compra -->
            <div>
                <label for="purchase_price" class="block text-sm font-medium text-gray-700">Preço de Compra</label>
                <div class="mt-1 relative rounded-md shadow-sm">
                    <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                        <span class="text-gray-500 sm:text-sm">R$</span>
                    </div>
                    <input type="number" wire:model="purchase_price" id="purchase_price" step="0.01" min="0"
                           class="pl-8 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                </div>
                @error('purchase_price') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
            </div>

            <!-- Data de Compra -->
            <div>
                <label for="purchase_date" class="block text-sm font-medium text-gray-700">Data de Compra</label>
                <input type="date" wire:model="purchase_date" id="purchase_date" 
                       class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                @error('purchase_date') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
            </div>
        </div>

        <!-- Fotos Existentes -->
        @if(count($existingPhotos) > 0)
        <div class="mb-6">
            <label class="block text-sm font-medium text-gray-700 mb-3">Fotos Atuais</label>
            <div class="grid grid-cols-2 md:grid-cols-4 gap-4">
                @foreach($existingPhotos as $index => $photo)
                    <div class="relative">
                        <img src="{{ asset('storage/' . $photo) }}" alt="Foto do equipamento" 
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
            <label for="photos" class="block text-sm font-medium text-gray-700">
                {{ count($existingPhotos) > 0 ? 'Adicionar Mais Fotos' : 'Fotos do Equipamento' }}
            </label>
            <div class="mt-1 flex justify-center px-6 pt-5 pb-6 border-2 border-gray-300 border-dashed rounded-md">
                <div class="space-y-1 text-center">
                    <svg class="mx-auto h-12 w-12 text-gray-400" stroke="currentColor" fill="none" viewBox="0 0 48 48">
                        <path d="M28 8H12a4 4 0 00-4 4v20m32-12v8m0 0v8a4 4 0 01-4 4H12a4 4 0 01-4-4v-4m32-4l-3.172-3.172a4 4 0 00-5.656 0L28 28M8 32l9.172-9.172a4 4 0 015.656 0L28 28m0 0l4 4m4-24h8m-4-4v8m-12 4h.02" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" />
                    </svg>
                    <div class="flex text-sm text-gray-600">
                        <label for="photos" class="relative cursor-pointer bg-white rounded-md font-medium text-indigo-600 hover:text-indigo-500 focus-within:outline-none focus-within:ring-2 focus-within:ring-offset-2 focus-within:ring-indigo-500">
                            <span>Carregar fotos</span>
                            <input id="photos" wire:model="photos" type="file" multiple accept="image/*" class="sr-only">
                        </label>
                        <p class="pl-1">ou arraste e solte</p>
                    </div>
                    <p class="text-xs text-gray-500">PNG, JPG, GIF até 2MB cada</p>
                </div>
            </div>
            @error('photos.*') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
        </div>

        <!-- Preview das Novas Fotos -->
        @if(count($photos) > 0)
        <div class="mb-6">
            <label class="block text-sm font-medium text-gray-700 mb-3">Novas Fotos</label>
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

        <!-- Observações -->
        <div class="mb-6">
            <label for="notes" class="block text-sm font-medium text-gray-700">Observações</label>
            <textarea wire:model="notes" id="notes" rows="3" 
                      class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500"
                      placeholder="Observações adicionais sobre o equipamento..."></textarea>
            @error('notes') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
        </div>

        <!-- Botões -->
        <div class="flex justify-end space-x-3">
            <a href="{{ $equipment ? route('equipment.show', $equipment) : route('equipment.index') }}" 
               class="bg-gray-300 hover:bg-gray-400 text-gray-700 font-bold py-2 px-4 rounded">
                Cancelar
            </a>
            <button type="submit" 
                    class="bg-indigo-600 hover:bg-indigo-700 text-white font-bold py-2 px-4 rounded">
                {{ $equipment ? 'Atualizar' : 'Cadastrar' }} Equipamento
            </button>
        </div>
    </form>
</div>