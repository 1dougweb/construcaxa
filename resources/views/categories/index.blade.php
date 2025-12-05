<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('Categorias') }}
        </h2>
    </x-slot>

    <div class="py-6">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-xl sm:rounded-lg">
                <div class="p-6 lg:p-8 bg-white dark:bg-gray-800 border-b border-gray-200 dark:border-gray-700">
                    @livewire('category-list')
                </div>
            </div>
        </div>
    </div>

    <!-- Offcanvas para Nova/Editar Categoria -->
    <x-offcanvas id="category-offcanvas" title="Nova Categoria" width="w-full md:w-[500px]">
        @livewire('category-form', ['category' => null], key('category-form'))
    </x-offcanvas>
</x-app-layout>

@push('scripts')
<script>
    document.addEventListener('livewire:init', () => {
        Livewire.on('categorySaved', () => {
            closeOffcanvas('category-offcanvas');
            // Recarregar a lista de categorias
            Livewire.dispatch('refresh');
        });
    });

    // Escutar evento de edição
    window.addEventListener('edit-category', (event) => {
        const categoryId = event.detail.id;
        const offcanvas = document.getElementById('category-offcanvas');
        const title = offcanvas.querySelector('h2');
        if (title) {
            title.textContent = 'Editar Categoria';
        }
        // Encontrar o componente Livewire e carregar a categoria
        const livewireComponent = document.querySelector('[wire\\:id]');
        if (livewireComponent) {
            const componentId = livewireComponent.getAttribute('wire:id');
            Livewire.find(componentId).call('loadCategory', categoryId);
        }
    });

    // Resetar título quando abrir para novo
    document.addEventListener('click', (e) => {
        if (e.target.closest('[onclick*="category-offcanvas"]') && !e.target.closest('[onclick*="edit-category"]')) {
            const offcanvas = document.getElementById('category-offcanvas');
            const title = offcanvas.querySelector('h2');
            if (title) {
                title.textContent = 'Nova Categoria';
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
