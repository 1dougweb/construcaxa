<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
                {{ __('Fornecedores') }}
            </h2>
            <div class="flex items-center space-x-4">
                @can('view suppliers')
                <a href="{{ route('supplier-categories.index') }}" class="inline-flex items-center px-4 py-2 bg-gray-800 dark:bg-gray-700 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-gray-700 dark:hover:bg-gray-600 focus:bg-gray-700 active:bg-gray-900 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 transition ease-in-out duration-150">
                    <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 7h.01M7 3h5c.512 0 1.024.195 1.414.586l7 7a2 2 0 010 2.828l-7 7a2 2 0 01-2.828 0l-7-7A1.994 1.994 0 013 12V7a4 4 0 014-4z" />
                    </svg>
                    {{ __('Gerenciar Categorias') }}
                </a>
                @endcan
                @can('create suppliers')
            <button 
                onclick="openOffcanvas('supplier-offcanvas')"
                class="inline-flex items-center px-4 py-2 bg-indigo-600 dark:bg-indigo-700 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-indigo-700 dark:hover:bg-indigo-600 focus:bg-indigo-700 active:bg-indigo-900 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 transition ease-in-out duration-150"
            >
                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
                </svg>
                {{ __('Novo Fornecedor') }}
            </button>
                @endcan
            </div>
        </div>
    </x-slot>

    <div class="py-6">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-xl sm:rounded-lg">
                <div class="p-6 lg:p-8 bg-white dark:bg-gray-800 border-b border-gray-200 dark:border-gray-700">
                    @livewire('supplier-list')
                </div>
            </div>
        </div>
    </div>

    <!-- Offcanvas para Novo/Editar Fornecedor -->
    <x-offcanvas id="supplier-offcanvas" title="Novo Fornecedor" width="w-full md:w-[700px]">
        @livewire('supplier-form', ['supplier' => null], key('supplier-form'))
    </x-offcanvas>
</x-app-layout>

@push('scripts')
<script>
    (function() {
        const ensureNotificationContainer = () => {
            if (!document.getElementById('notifications')) {
                const container = document.createElement('div');
                container.id = 'notifications';
                container.className = 'fixed bottom-0 right-0 m-6 z-50';
                document.body.appendChild(container);
            }
        };

        const showNotification = (message, type = 'info') => {
            ensureNotificationContainer();
            const container = document.getElementById('notifications');
            const notification = document.createElement('div');
            let bgColor = 'bg-blue-500';
            if (type === 'success') bgColor = 'bg-green-500';
            if (type === 'error') bgColor = 'bg-red-500';
            if (type === 'warning') bgColor = 'bg-yellow-500';

            notification.className = `${bgColor} text-white px-6 py-4 rounded-lg shadow-lg mb-3 opacity-100 transition-opacity duration-500`;
            notification.textContent = message || 'Operação concluída';

            container.appendChild(notification);
            setTimeout(() => {
                notification.style.opacity = '0';
                setTimeout(() => notification.remove(), 500);
            }, 3000);
        };

        const getSupplierFormComponent = () => {
            const offcanvas = document.getElementById('supplier-offcanvas');
            if (!offcanvas) return null;
            const componentEl = offcanvas.querySelector('[wire\\:id]');
            return componentEl ? Livewire.find(componentEl.getAttribute('wire:id')) : null;
        };

        window.addEventListener('supplier-saved', (event) => {
            const detail = event.detail || {};
            if (window.closeOffcanvas) {
                window.closeOffcanvas('supplier-offcanvas');
            } else if (typeof closeOffcanvas === 'function') {
                closeOffcanvas('supplier-offcanvas');
            }
            Livewire.dispatch('refresh');
            if (detail.message) showNotification(detail.message, detail.type || 'success');
        });

        window.addEventListener('edit-supplier', (event) => {
            const supplierId = event.detail.id;
            const offcanvas = document.getElementById('supplier-offcanvas');
            const title = offcanvas ? offcanvas.querySelector('h2') : null;
            if (title) title.textContent = 'Editar Fornecedor';

            // Abrir offcanvas antes de carregar
            if (window.openOffcanvas) {
                window.openOffcanvas('supplier-offcanvas');
            } else if (typeof openOffcanvas === 'function') {
                openOffcanvas('supplier-offcanvas');
            }

            // Aguardar o componente montar
            setTimeout(() => {
                const component = getSupplierFormComponent();
                if (component) {
                    component.call('loadSupplier', supplierId);
                    // Fallback: emitir evento Livewire
                    if (window.Livewire?.dispatch) {
                        window.Livewire.dispatch('edit-supplier', { id: supplierId });
                    }
                }
            }, 200);
        });

        document.addEventListener('click', (e) => {
            if (e.target.closest('[onclick*="supplier-offcanvas"]') && !e.target.closest('[onclick*="edit-supplier"]')) {
                const offcanvas = document.getElementById('supplier-offcanvas');
                const title = offcanvas ? offcanvas.querySelector('h2') : null;
                if (title) title.textContent = 'Novo Fornecedor';

                const component = getSupplierFormComponent();
                if (component) {
                    component.call('resetForm');
                    if (window.Livewire?.dispatch) {
                        window.Livewire.dispatch('reset-supplier-form');
                    }
                }
            }
        });
    })();
</script>
@endpush
