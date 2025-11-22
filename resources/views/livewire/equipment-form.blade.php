<div>
    <form wire:submit.prevent="save" class="space-y-6">
        <!-- Foto destacada e campos nome/descrição -->
        <div class="flex gap-6 items-start">
            <!-- Foto destacada à esquerda -->
            <div class="flex-shrink-0">
                <x-photo-upload 
                    wire:model="featured_photo"
                    label="{{ __('Foto do Equipamento') }}"
                    delete-action="confirmDeletePhoto"
                    existing-photo-path="{{ $featured_photo_path }}"
                />
            </div>

            <!-- Nome e Descrição à direita -->
            <div class="flex-1 space-y-4">
                <div>
                    <x-label for="name" value="{{ __('Nome do Equipamento') }}" />
                    <x-input id="name" class="block mt-1 w-full" type="text" wire:model="name" required autofocus />
                    @error('name')
                        <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <x-label for="serial_number" value="{{ __('Número de Série') }}" />
                    <x-input id="serial_number" class="block mt-1 w-full" type="text" wire:model="serial_number" required />
                    @error('serial_number')
                        <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <x-label for="description" value="{{ __('Descrição') }}" />
                    <textarea id="description" wire:model="description" class="border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm block mt-1 w-full" rows="3"></textarea>
                    @error('description')
                        <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                    @enderror
                </div>
            </div>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
            <!-- Categoria de Equipamento -->
            <div>
                <x-label for="equipment_category_id" value="{{ __('Categoria') }}" />
                <select id="equipment_category_id" wire:model="equipment_category_id" class="border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm block mt-1 w-full">
                    <option value="">Selecione uma categoria</option>
                    @foreach($equipmentCategories as $category)
                        <option value="{{ $category->id }}">{{ $category->name }}</option>
                    @endforeach
                </select>
                @error('equipment_category_id')
                    <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                @enderror
            </div>

            <!-- Preço de Compra -->
            <div>
                <x-label for="purchase_price" value="{{ __('Preço de Compra') }}" />
                <x-input id="purchase_price" class="block mt-1 w-full" type="number" wire:model="purchase_price" step="0.01" min="0" />
                @error('purchase_price')
                    <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                @enderror
            </div>

            <!-- Data de Compra -->
            <div>
                <x-label for="purchase_date" value="{{ __('Data de Compra') }}" />
                <x-input id="purchase_date" class="block mt-1 w-full" type="date" wire:model="purchase_date" />
                @error('purchase_date')
                    <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                @enderror
            </div>

            <!-- Status -->
            @if($equipment)
            <div>
                <x-label for="status" value="{{ __('Status') }}" />
                <select id="status" wire:model="status" class="border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm block mt-1 w-full">
                    <option value="available">Disponível</option>
                    <option value="borrowed">Emprestado</option>
                    <option value="maintenance">Manutenção</option>
                    <option value="retired">Aposentado</option>
                </select>
                @error('status')
                    <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                @enderror
            </div>
            @endif
        </div>

        <!-- Observações -->
        <div>
            <x-label for="notes" value="{{ __('Observações') }}" />
            <textarea id="notes" wire:model="notes" class="border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm block mt-1 w-full" rows="3"></textarea>
            @error('notes')
                <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
            @enderror
        </div>

        <div class="flex justify-end space-x-4 pt-4">
            <a href="{{ $equipment ? route('equipment.show', $equipment) : route('equipment.index') }}" class="inline-flex items-center px-4 py-2 bg-gray-800 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-gray-700 focus:bg-gray-700 active:bg-gray-900 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 transition ease-in-out duration-150">
                {{ __('Cancelar') }}
            </a>
            <x-button-loading>
                {{ $equipment ? __('Salvar') : __('Salvar') }}
            </x-button-loading>
        </div>
    </form>

    <!-- Modal de Confirmação de Exclusão de Foto -->
    @if($showDeleteModal)
        <div class="fixed inset-0 z-50 overflow-y-auto" aria-labelledby="modal-title" role="dialog" aria-modal="true" style="display: block;">
            <div 
                class="fixed inset-0 bg-gray-900 bg-opacity-50 backdrop-blur-sm transition-opacity"
                style="backdrop-filter: blur(4px); -webkit-backdrop-filter: blur(4px);"
                wire:click="cancelDeletePhoto"
            ></div>

            <div class="flex min-h-full items-end justify-center p-4 text-center sm:items-center sm:p-0">
                <div class="relative transform overflow-hidden rounded-lg bg-white text-left shadow-xl transition-all sm:my-8 sm:w-full sm:max-w-lg">
                    <div class="bg-white px-4 pt-5 pb-4 sm:p-6 sm:pb-4">
                        <div class="sm:flex sm:items-start">
                            <div class="mx-auto flex h-12 w-12 flex-shrink-0 items-center justify-center rounded-full bg-red-100 sm:mx-0 sm:h-10 sm:w-10">
                                <svg class="h-6 w-6 text-red-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" />
                                </svg>
                            </div>
                            <div class="mt-3 text-center sm:mt-0 sm:ml-4 sm:text-left">
                                <h3 class="text-lg font-medium leading-6 text-gray-900" id="modal-title">
                                    Excluir Foto
                                </h3>
                                <div class="mt-2">
                                    <p class="text-sm text-gray-500">
                                        Tem certeza que deseja excluir esta foto? Esta ação não pode ser desfeita.
                                    </p>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="bg-gray-50 px-4 py-3 sm:flex sm:flex-row-reverse sm:px-6">
                        <button 
                            type="button" 
                            class="inline-flex items-center justify-center w-full rounded-md border border-transparent bg-red-600 hover:bg-red-700 focus:ring-red-500 px-4 py-2 text-base font-medium text-white shadow-sm focus:outline-none focus:ring-2 focus:ring-offset-2 sm:ml-3 sm:w-auto sm:text-sm disabled:opacity-50 disabled:cursor-not-allowed"
                            wire:click="deletePhoto"
                            wire:loading.attr="disabled"
                            wire:target="deletePhoto"
                        >
                            <span wire:loading.remove wire:target="deletePhoto">Excluir</span>
                            <span wire:loading wire:target="deletePhoto" class="inline-flex items-center">
                                <svg class="animate-spin h-4 w-4 text-white mr-2" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                                    <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                                    <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                                </svg>
                                Excluindo
                            </span>
                        </button>
                        <button 
                            type="button" 
                            class="mt-3 inline-flex w-full justify-center rounded-md border border-gray-300 bg-white px-4 py-2 text-base font-medium text-gray-700 shadow-sm hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 sm:mt-0 sm:ml-3 sm:w-auto sm:text-sm"
                            wire:click="cancelDeletePhoto"
                        >
                            Cancelar
                        </button>
                    </div>
                </div>
            </div>
        </div>
    @endif
</div>