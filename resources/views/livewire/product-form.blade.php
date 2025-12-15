<div>
    <form wire:submit.prevent="save" class="space-y-6">
        <!-- Foto destacada e campos nome/descrição -->
        <div class="flex gap-6 items-start">
            <!-- Foto destacada à esquerda -->
            <div class="flex-shrink-0">
                <x-photo-upload 
                    wire:model="featured_photo"
                    label="{{ __('Foto do Produto') }}"
                    delete-action="confirmDeletePhoto"
                    existing-photo-path="{{ $featured_photo_path }}"
                />
            </div>

            <!-- Nome e Descrição à direita -->
            <div class="flex-1 space-y-4">
        <div>
            <x-label for="name" value="{{ __('Nome') }}" />
            <x-input id="name" class="block mt-1 w-full" type="text" wire:model="name" required autofocus />
            @error('name')
                <p class="text-red-500 dark:text-red-400 text-xs mt-1">{{ $message }}</p>
            @enderror
        </div>

        <div>
            <x-label for="description" value="{{ __('Descrição') }}" />
            <textarea id="description" wire:model="description" class="border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-gray-300 focus:border-indigo-500 focus:ring-indigo-500 dark:focus:border-indigo-400 dark:focus:ring-indigo-400 rounded-md shadow-sm block mt-1 w-full" rows="3"></textarea>
            @error('description')
                <p class="text-red-500 dark:text-red-400 text-xs mt-1">{{ $message }}</p>
            @enderror
                </div>
            </div>
        </div>

        <div>
            <x-label for="measurement_unit" value="{{ __('Tipo de Produto') }}" />
            <select id="measurement_unit" wire:model="measurement_unit" class="border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-gray-300 focus:border-indigo-500 focus:ring-indigo-500 dark:focus:border-indigo-400 dark:focus:ring-indigo-400 rounded-md shadow-sm block mt-1 w-full">
                @if(isset($unitTypes) && is_array($unitTypes))
                @foreach($unitTypes as $value => $type)
                        <option value="{{ $value }}">{{ $type['label'] ?? $value }}</option>
                    @endforeach
                @else
                    @foreach(\App\Models\Product::UNIT_TYPES as $value => $type)
                    <option value="{{ $value }}">{{ $type['label'] }}</option>
                @endforeach
                @endif
            </select>
            @error('measurement_unit')
                <p class="text-red-500 dark:text-red-400 text-xs mt-1">{{ $message }}</p>
            @enderror
        </div>

        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
            <div>
                <x-label for="price" value="{{ __('Preço de Venda') }}" />
                <x-input id="price" class="block mt-1 w-full" type="number" wire:model="price" step="0.01" min="0" />
                <p class="text-xs text-gray-500 dark:text-gray-400 mt-1">Preço principal de venda do produto (valor antigo do sistema)</p>
                @error('price')
                    <p class="text-red-500 dark:text-red-400 text-xs mt-1">{{ $message }}</p>
                @enderror
            </div>

            <div>
                <x-label for="sale_price" value="{{ __('Preço de Revenda / Estoque') }}" />
                <x-input id="sale_price" class="block mt-1 w-full" type="number" wire:model="sale_price" step="0.01" min="0" />
                <p class="text-xs text-gray-500 dark:text-gray-400 mt-1">Preço padrão de revenda usado em orçamentos e cálculos com estoque</p>
                @error('sale_price')
                    <p class="text-red-500 dark:text-red-400 text-xs mt-1">{{ $message }}</p>
                @enderror
            </div>
        </div>

        <div class="grid grid-cols-1 gap-6">
            @if($measurement_unit === 'unit')
            <div>
                <x-label for="stock" value="{{ __('Quantidade em Estoque') }}" />
                <div class="flex items-center">
                    <x-input id="stock" class="block mt-1 w-full" type="number" wire:model="stock" step="1" min="0" required />
                    <span class="ml-2 text-gray-600 dark:text-gray-400">UN</span>
                </div>
                @error('stock')
                    <p class="text-red-500 dark:text-red-400 text-xs mt-1">{{ $message }}</p>
                @enderror
            </div>

            <div>
                <x-label for="min_stock" value="{{ __('Quantidade Mínima') }}" />
                <div class="flex items-center">
                    <x-input id="min_stock" class="block mt-1 w-full" type="number" wire:model="min_stock" step="1" min="0" required />
                    <span class="ml-2 text-gray-600 dark:text-gray-400">UN</span>
                </div>
                @error('min_stock')
                    <p class="text-red-500 dark:text-red-400 text-xs mt-1">{{ $message }}</p>
                @enderror
            </div>
            @endif

            @if($measurement_unit === 'weight')
            <div>
                <x-label for="stock" value="{{ __('Peso em Estoque') }}" />
                <div class="flex items-center">
                    <x-input id="stock" class="block mt-1 w-full" type="number" wire:model="stock" step="1" min="0" required />
                    <span class="ml-2 text-gray-600 dark:text-gray-400">KG</span>
                </div>
                @error('stock')
                    <p class="text-red-500 dark:text-red-400 text-xs mt-1">{{ $message }}</p>
                @enderror
            </div>

            <div>
                <x-label for="min_stock" value="{{ __('Peso Mínimo') }}" />
                <div class="flex items-center">
                    <x-input id="min_stock" class="block mt-1 w-full" type="number" wire:model="min_stock" step="1" min="0" required />
                    <span class="ml-2 text-gray-600 dark:text-gray-400">KG</span>
                </div>
                @error('min_stock')
                    <p class="text-red-500 dark:text-red-400 text-xs mt-1">{{ $message }}</p>
                @enderror
            </div>
            @endif

            @if($measurement_unit === 'length')
            <div>
                <x-label for="stock" value="{{ __('Metragem em Estoque') }}" />
                <div class="flex items-center">
                    <x-input id="stock" class="block mt-1 w-full" type="number" wire:model="stock" step="1" min="0" required />
                    <span class="ml-2 text-gray-600 dark:text-gray-400">M</span>
                </div>
                @error('stock')
                    <p class="text-red-500 dark:text-red-400 text-xs mt-1">{{ $message }}</p>
                @enderror
            </div>

            <div>
                <x-label for="min_stock" value="{{ __('Metragem Mínima') }}" />
                <div class="flex items-center">
                    <x-input id="min_stock" class="block mt-1 w-full" type="number" wire:model="min_stock" step="1" min="0" required />
                    <span class="ml-2 text-gray-600 dark:text-gray-400">M</span>
                </div>
                @error('min_stock')
                    <p class="text-red-500 dark:text-red-400 text-xs mt-1">{{ $message }}</p>
                @enderror
            </div>
            @endif
        </div>

        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
            <div>
                <x-label for="category_id" value="{{ __('Categoria') }}" />
                <select id="category_id" wire:model="category_id" class="border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-gray-300 focus:border-indigo-500 focus:ring-indigo-500 dark:focus:border-indigo-400 dark:focus:ring-indigo-400 rounded-md shadow-sm block mt-1 w-full">
                    <option value="">Selecione uma categoria</option>
                    @foreach($categories as $category)
                        <option value="{{ $category->id }}">{{ $category->name }}</option>
                    @endforeach
                </select>
                @error('category_id')
                    <p class="text-red-500 dark:text-red-400 text-xs mt-1">{{ $message }}</p>
                @enderror
            </div>

            <div>
                <x-label for="supplier_id" value="{{ __('Fornecedor') }}" />
                <select id="supplier_id" wire:model="supplier_id" class="border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-gray-300 focus:border-indigo-500 focus:ring-indigo-500 dark:focus:border-indigo-400 dark:focus:ring-indigo-400 rounded-md shadow-sm block mt-1 w-full">
                    <option value="">Selecione um fornecedor</option>
                    @foreach($suppliers as $supplier)
                        <option value="{{ $supplier->id }}">{{ $supplier->company_name }}</option>
                    @endforeach
                </select>
                @error('supplier_id')
                    <p class="text-red-500 dark:text-red-400 text-xs mt-1">{{ $message }}</p>
                @enderror
            </div>
        </div>

        <div class="flex items-center justify-end gap-4 mt-6 pt-4 border-t border-gray-200 dark:border-gray-700">
            <button 
                type="button"
                onclick="closeOffcanvas('product-offcanvas')"
                class="inline-flex items-center px-4 py-2 bg-gray-600 dark:bg-gray-700 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-gray-700 dark:hover:bg-gray-600 focus:bg-gray-700 active:bg-gray-900 focus:outline-none focus:ring-2 focus:ring-gray-500 focus:ring-offset-2 transition ease-in-out duration-150"
            >
                {{ __('Cancelar') }}
            </button>
            <x-button-loading>
                {{ $product ? __('Atualizar') : __('Salvar') }}
            </x-button-loading>
        </div>
    </form>

    <!-- Modal de Confirmação de Exclusão de Foto -->
    @if($showDeleteModal)
        <div class="fixed inset-0 z-50 overflow-y-auto" aria-labelledby="modal-title" role="dialog" aria-modal="true" style="display: block;">
            <!-- Backdrop com backdrop-filter -->
            <div 
                class="fixed inset-0 bg-gray-900 dark:bg-black bg-opacity-50 dark:bg-opacity-70 backdrop-blur-sm transition-opacity"
                style="backdrop-filter: blur(4px); -webkit-backdrop-filter: blur(4px);"
                wire:click="cancelDeletePhoto"
            ></div>

            <!-- Modal -->
            <div class="flex min-h-full items-end justify-center p-4 text-center sm:items-center sm:p-0">
                <div class="relative transform overflow-hidden rounded-lg bg-white dark:bg-gray-800 text-left shadow-xl transition-all sm:my-8 sm:w-full sm:max-w-lg">
                    <div class="bg-white dark:bg-gray-800 px-4 pt-5 pb-4 sm:p-6 sm:pb-4">
                        <div class="sm:flex sm:items-start">
                            <div class="mx-auto flex h-12 w-12 flex-shrink-0 items-center justify-center rounded-full bg-red-100 dark:bg-red-900/30 sm:mx-0 sm:h-10 sm:w-10">
                                <svg class="h-6 w-6 text-red-600 dark:text-red-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" />
                                </svg>
                            </div>
                            <div class="mt-3 text-center sm:mt-0 sm:ml-4 sm:text-left">
                                <h3 class="text-lg font-medium leading-6 text-gray-900 dark:text-gray-100" id="modal-title">
                                    Excluir Foto
                                </h3>
                                <div class="mt-2">
                                    <p class="text-sm text-gray-500 dark:text-gray-400">
                                        Tem certeza que deseja excluir esta foto? Esta ação não pode ser desfeita.
                                    </p>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="bg-gray-50 dark:bg-gray-700 px-4 py-3 sm:flex sm:flex-row-reverse sm:px-6">
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
                            class="mt-3 inline-flex w-full justify-center rounded-md border border-gray-300 dark:border-gray-600 bg-white dark:bg-gray-800 px-4 py-2 text-base font-medium text-gray-700 dark:text-gray-300 shadow-sm hover:bg-gray-50 dark:hover:bg-gray-700 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 sm:mt-0 sm:ml-3 sm:w-auto sm:text-sm"
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
