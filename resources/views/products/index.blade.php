<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
                {{ __('Produtos') }}
            </h2>
            <div class="flex items-center space-x-4">
                @can('view categories')
                <a href="{{ route('categories.index') }}" class="inline-flex items-center px-4 py-2 bg-gray-800 dark:bg-gray-700 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-gray-700 dark:hover:bg-gray-600 focus:bg-gray-700 active:bg-gray-900 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 transition ease-in-out duration-150">
                    <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 7h.01M7 3h5c.512 0 1.024.195 1.414.586l7 7a2 2 0 010 2.828l-7 7a2 2 0 01-2.828 0l-7-7A1.994 1.994 0 013 12V7a4 4 0 014-4z" />
                    </svg>
                    {{ __('Gerenciar Categorias') }}
                </a>
                @endcan
                @can('create products')
                <button 
                    onclick="openOffcanvas('product-offcanvas')"
                    class="inline-flex items-center px-4 py-2 bg-indigo-600 dark:bg-indigo-700 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-indigo-700 dark:hover:bg-indigo-600 focus:bg-indigo-700 active:bg-indigo-900 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 transition ease-in-out duration-150"
                >
                    <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
                    </svg>
                    {{ __('Novo Produto') }}
                </button>
                @endcan
            </div>
        </div>
    </x-slot>

    <div class="py-6">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-xl sm:rounded-lg">
                
                    @livewire('product-list')
                
            </div>
        </div>
    </div>

    <!-- Offcanvas para Novo/Editar Produto -->
    <x-offcanvas id="product-offcanvas" title="Novo Produto" width="w-full md:w-[600px]">
        @livewire('product-form', ['product' => null], key('product-form'))
    </x-offcanvas>
</x-app-layout>

@push('scripts')
<script>
    document.addEventListener('livewire:init', () => {
        Livewire.on('productSaved', () => {
            closeOffcanvas('product-offcanvas');
            // Recarregar a lista de produtos
            Livewire.dispatch('refresh');
        });
    });

    // Escutar evento de edição
    window.addEventListener('edit-product', (event) => {
        const productId = event.detail.id;
        const offcanvas = document.getElementById('product-offcanvas');
        const title = offcanvas.querySelector('h2');
        if (title) {
            title.textContent = 'Editar Produto';
        }
        // Encontrar o componente Livewire e carregar o produto
        const livewireComponent = document.querySelector('[wire\\:id]');
        if (livewireComponent) {
            const componentId = livewireComponent.getAttribute('wire:id');
            Livewire.find(componentId).call('loadProduct', productId);
        }
    });

    // Resetar título quando abrir para novo
    document.addEventListener('click', (e) => {
        if (e.target.closest('[onclick*="product-offcanvas"]') && !e.target.closest('[onclick*="edit-product"]')) {
            const offcanvas = document.getElementById('product-offcanvas');
            const title = offcanvas.querySelector('h2');
            if (title) {
                title.textContent = 'Novo Produto';
            }
            // Resetar o formulário
            const livewireComponent = document.querySelector('[wire\\:id]');
            if (livewireComponent) {
                const componentId = livewireComponent.getAttribute('wire:id');
                Livewire.find(componentId).call('resetForm');
            }
        }
    });
</script>
@endpush
