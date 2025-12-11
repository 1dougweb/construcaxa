<div>
    <form wire:submit.prevent="save" class="space-y-6">
        <div>
            <x-label for="name" value="{{ __('Nome') }}" />
            <x-input 
                id="name" 
                class="block mt-1 w-full dark:bg-gray-700 dark:text-gray-300 dark:border-gray-600" 
                type="text" 
                wire:model="name" 
                required 
                autofocus 
            />
            @error('name')
                <p class="text-red-500 dark:text-red-400 text-xs mt-1">{{ $message }}</p>
            @enderror
        </div>

        <div>
            <x-label for="description" value="{{ __('Descrição') }}" />
            <textarea 
                id="description" 
                wire:model="description" 
                class="border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-gray-300 focus:border-indigo-500 focus:ring-indigo-500 dark:focus:border-indigo-400 dark:focus:ring-indigo-400 rounded-md shadow-sm block mt-1 w-full" 
                rows="3"
            ></textarea>
            @error('description')
                <p class="text-red-500 dark:text-red-400 text-xs mt-1">{{ $message }}</p>
            @enderror
        </div>

        <div>
            <x-label for="sku_prefix" value="{{ __('Prefixo SKU') }}" />
            <x-input 
                id="sku_prefix" 
                class="block mt-1 w-full dark:bg-gray-700 dark:text-gray-300 dark:border-gray-600" 
                type="text" 
                wire:model="sku_prefix" 
                required 
                maxlength="3"
            />
            <p class="text-sm text-gray-500 dark:text-gray-400 mt-1">
                O prefixo SKU deve ter exatamente 3 caracteres e será usado para gerar o SKU dos produtos desta categoria.
            </p>
            @error('sku_prefix')
                <p class="text-red-500 dark:text-red-400 text-xs mt-1">{{ $message }}</p>
            @enderror
        </div>

        <div>
            <x-label for="parent_id" value="{{ __('Categoria Pai (Opcional)') }}" />
            <select 
                id="parent_id" 
                wire:model="parent_id" 
                class="border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-gray-300 focus:border-indigo-500 focus:ring-indigo-500 dark:focus:border-indigo-400 dark:focus:ring-indigo-400 rounded-md shadow-sm block mt-1 w-full"
            >
                <option value="">Nenhuma (Categoria Principal)</option>
                @foreach($categories as $category)
                    <option value="{{ $category->id }}">{{ $category->name }}</option>
                @endforeach
            </select>
            @error('parent_id')
                <p class="text-red-500 dark:text-red-400 text-xs mt-1">{{ $message }}</p>
            @enderror
        </div>

        <div class="flex items-center justify-end gap-4 mt-6 pt-4 border-t border-gray-200 dark:border-gray-700">
            <button 
                type="button"
                onclick="closeOffcanvas('category-offcanvas')"
                class="inline-flex items-center px-4 py-2 bg-gray-600 dark:bg-gray-700 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-gray-700 dark:hover:bg-gray-600 focus:bg-gray-700 active:bg-gray-900 focus:outline-none focus:ring-2 focus:ring-gray-500 focus:ring-offset-2 transition ease-in-out duration-150"
            >
                {{ __('Cancelar') }}
            </button>
            <x-button-loading>
                {{ $category ? __('Atualizar') : __('Criar') }}
            </x-button-loading>
        </div>
    </form>
</div>


