<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
                {{ __('Equipamentos') }}
            </h2>
            <div class="flex items-center space-x-4">
                @can('view categories')
                <a href="{{ route('equipment-categories.index') }}" class="inline-flex items-center px-4 py-2 bg-gray-800 dark:bg-gray-700 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-gray-700 dark:hover:bg-gray-600 focus:bg-gray-700 active:bg-gray-900 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 transition ease-in-out duration-150">
                    <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 7h.01M7 3h5c.512 0 1.024.195 1.414.586l7 7a2 2 0 010 2.828l-7 7a2 2 0 01-2.828 0l-7-7A1.994 1.994 0 013 12V7a4 4 0 014-4z" />
                    </svg>
                    {{ __('Gerenciar Categorias') }}
                </a>
                @endcan
                @can('create products')
                <button 
                    onclick="openOffcanvas('equipment-offcanvas')"
                    class="inline-flex items-center px-4 py-2 bg-indigo-600 dark:bg-indigo-700 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-indigo-700 dark:hover:bg-indigo-600 focus:bg-indigo-700 active:bg-indigo-900 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 transition ease-in-out duration-150"
                >
                    <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
                    </svg>
                    {{ __('Novo Equipamento') }}
                </button>
                @endcan
            </div>
        </div>
    </x-slot>

    <div class="py-6">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-xl sm:rounded-lg">
                <div class="p-6 lg:p-8 bg-white dark:bg-gray-800 border-b border-gray-200 dark:border-gray-700">
                    <!-- Filtros -->
                    <div class="mb-6">
                        <form method="GET" class="flex flex-wrap gap-4">
                            <div class="flex-1 min-w-64">
                                <input type="text" name="search" value="{{ request('search') }}" 
                                       placeholder="Buscar por nome ou número de série..." 
                                       class="w-full rounded-md border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-gray-300 dark:placeholder-gray-400 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 dark:focus:border-indigo-400 dark:focus:ring-indigo-400">
                            </div>
                            <div>
                                <select name="status" class="rounded-md border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 dark:focus:border-indigo-400 dark:focus:ring-indigo-400">
                                    <option value="">Todos os status</option>
                                    <option value="available" {{ request('status') === 'available' ? 'selected' : '' }}>Disponível</option>
                                    <option value="borrowed" {{ request('status') === 'borrowed' ? 'selected' : '' }}>Emprestado</option>
                                    <option value="maintenance" {{ request('status') === 'maintenance' ? 'selected' : '' }}>Manutenção</option>
                                    <option value="retired" {{ request('status') === 'retired' ? 'selected' : '' }}>Aposentado</option>
                                </select>
                            </div>
                            <div>
                                <select name="equipment_category_id" class="rounded-md border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 dark:focus:border-indigo-400 dark:focus:ring-indigo-400">
                                    <option value="">Todas as categorias</option>
                                    @foreach($equipmentCategories as $category)
                                        <option value="{{ $category->id }}" {{ request('equipment_category_id') == $category->id ? 'selected' : '' }}>
                                            {{ $category->name }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                            <button type="submit" class="inline-flex items-center px-4 py-2 bg-gray-800 dark:bg-gray-700 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-gray-700 dark:hover:bg-gray-600 focus:bg-gray-700 active:bg-gray-900 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 transition ease-in-out duration-150">
                                Filtrar
                            </button>
                            <a href="{{ route('equipment.index') }}" class="inline-flex items-center px-4 py-2 bg-gray-300 dark:bg-gray-600 border border-transparent rounded-md font-semibold text-xs text-gray-700 dark:text-gray-300 uppercase tracking-widest hover:bg-gray-400 dark:hover:bg-gray-500 focus:bg-gray-400 active:bg-gray-500 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 transition ease-in-out duration-150">
                                Limpar
                            </a>
                        </form>
                    </div>

                    @if($equipment->isEmpty())
                        <div class="text-center py-8">
                            <svg class="mx-auto h-12 w-12 text-gray-400 dark:text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19.428 15.428a2 2 0 00-1.022-.547l-2.387-.477a6 6 0 00-3.86.517l-.318.158a6 6 0 01-3.86.517L6.05 15.21a2 2 0 00-1.806.547M8 4h8l-1 1v5.172a2 2 0 00.586 1.414l5 5c1.26 1.26.367 3.414-1.415 3.414H4.828c-1.782 0-2.674-2.154-1.414-3.414l5-5A2 2 0 009 10.172V5L8 4z" />
                            </svg>
                            <h3 class="mt-2 text-sm font-medium text-gray-900 dark:text-gray-100">Nenhum equipamento encontrado</h3>
                            <p class="mt-1 text-sm text-gray-500 dark:text-gray-400">Comece cadastrando um novo equipamento.</p>
                            <div class="mt-6">
                                @can('create products')
                                <button 
                                    onclick="openOffcanvas('equipment-offcanvas')"
                                    class="inline-flex items-center px-4 py-2 bg-indigo-600 dark:bg-indigo-700 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-indigo-700 dark:hover:bg-indigo-600 focus:bg-indigo-700 active:bg-indigo-900 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 transition ease-in-out duration-150"
                                >
                                    <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
                                    </svg>
                                    {{ __('Novo Equipamento') }}
                                </button>
                                @endcan
                            </div>
                        </div>
                    @else
                        <table class="min-w-full divide-y divide-gray-200 dark:divide-gray-700">
                            <thead>
                                <tr>
                                    <th class="px-6 py-3 bg-gray-50 dark:bg-gray-700/50 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">Foto</th>
                                    <th class="px-6 py-3 bg-gray-50 dark:bg-gray-700/50 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">Nome</th>
                                    <th class="px-6 py-3 bg-gray-50 dark:bg-gray-700/50 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">Número de Série</th>
                                    <th class="px-6 py-3 bg-gray-50 dark:bg-gray-700/50 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">Categoria</th>
                                    <th class="px-6 py-3 bg-gray-50 dark:bg-gray-700/50 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">Status</th>
                                    <th class="px-6 py-3 bg-gray-50 dark:bg-gray-700/50 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">Funcionário</th>
                                    @if(auth()->user()->can('view products') || auth()->user()->can('edit products'))
                                    <th class="px-6 py-3 bg-gray-50 dark:bg-gray-700/50 text-right text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider"></th>
                                    @endif
                                </tr>
                            </thead>
                            <tbody class="bg-white dark:bg-gray-800 divide-y divide-gray-200 dark:divide-gray-700">
                                @foreach($equipment as $item)
                                    <tr>
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            @php
                                                $photos = $item->photos ?? [];
                                                if (is_string($photos)) {
                                                    $photos = json_decode($photos, true) ?? [];
                                                }
                                                $photos = is_array($photos) ? $photos : [];
                                            @endphp
                                            @if(!empty($photos))
                                                <div x-data="{ 
                                                    open: false, 
                                                    currentIndex: 0,
                                                    images: @js(array_map(function($photo) { return asset('storage/' . $photo); }, $photos)),
                                                    openLightbox(index) {
                                                        this.currentIndex = index;
                                                        this.open = true;
                                                        document.body.style.overflow = 'hidden';
                                                    },
                                                    closeLightbox() {
                                                        this.open = false;
                                                        document.body.style.overflow = '';
                                                    },
                                                    nextImage() {
                                                        this.currentIndex = (this.currentIndex + 1) % this.images.length;
                                                    },
                                                    prevImage() {
                                                        this.currentIndex = (this.currentIndex - 1 + this.images.length) % this.images.length;
                                                    }
                                                }" @keydown.escape="closeLightbox()" @keydown.arrow-left="prevImage()" @keydown.arrow-right="nextImage()">
                                                    <div class="w-16 h-16 rounded overflow-hidden cursor-pointer hover:opacity-80 transition-opacity">
                                                        <img 
                                                            src="{{ asset('storage/' . $photos[0]) }}" 
                                                            alt="{{ $item->name }}"
                                                            class="w-full h-full object-cover border border-gray-200 dark:border-gray-700 rounded-md"
                                                            @click="openLightbox(0)"
                                                        >
                                                    </div>
                                                    
                                                    <!-- Lightbox -->
                                                    <div 
                                                        x-show="open"
                                                        x-cloak
                                                        @click.self="closeLightbox()"
                                                        class="fixed inset-0 z-50 flex items-center justify-center bg-black bg-opacity-90"
                                                        x-transition:enter="transition ease-out duration-300"
                                                        x-transition:enter-start="opacity-0"
                                                        x-transition:enter-end="opacity-100"
                                                        x-transition:leave="transition ease-in duration-200"
                                                        x-transition:leave-start="opacity-100"
                                                        x-transition:leave-end="opacity-0"
                                                    >
                                                        <!-- Botão Fechar (redondo) -->
                                                        <button 
                                                            @click="closeLightbox()"
                                                            class="absolute top-4 right-4 w-12 h-12 rounded-full bg-white bg-opacity-20 hover:bg-opacity-30 text-white flex items-center justify-center transition-all duration-200 z-10"
                                                        >
                                                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                                                            </svg>
                                                        </button>
                                                        
                                                        <!-- Botão Anterior (redondo) -->
                                                        <button 
                                                            @click="prevImage()"
                                                            x-show="images.length > 1"
                                                            class="absolute left-4 w-12 h-12 rounded-full bg-white bg-opacity-20 hover:bg-opacity-30 text-white flex items-center justify-center transition-all duration-200 z-10"
                                                        >
                                                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"></path>
                                                            </svg>
                                                        </button>
                                                        
                                                        <!-- Botão Próximo (redondo) -->
                                                        <button 
                                                            @click="nextImage()"
                                                            x-show="images.length > 1"
                                                            class="absolute right-4 w-12 h-12 rounded-full bg-white bg-opacity-20 hover:bg-opacity-30 text-white flex items-center justify-center transition-all duration-200 z-10"
                                                        >
                                                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path>
                                                            </svg>
                                                        </button>
                                                        
                                                        <!-- Imagem -->
                                                        <div 
                                                            class="max-w-7xl max-h-[90vh] mx-4"
                                                            x-transition:enter="transition ease-out duration-300"
                                                            x-transition:enter-start="opacity-0 scale-95"
                                                            x-transition:enter-end="opacity-100 scale-100"
                                                            x-transition:leave="transition ease-in duration-200"
                                                            x-transition:leave-start="opacity-100 scale-100"
                                                            x-transition:leave-end="opacity-0 scale-95"
                                                        >
                                                            <img 
                                                                :src="images[currentIndex]" 
                                                                :alt="'{{ $item->name }} - ' + (currentIndex + 1)"
                                                                class="max-w-full max-h-[90vh] object-contain rounded-lg shadow-2xl"
                                                            >
                                                        </div>
                                                        
                                                        <!-- Indicador de imagem -->
                                                        <div 
                                                            x-show="images.length > 1"
                                                            class="absolute bottom-4 left-1/2 transform -translate-x-1/2 bg-black bg-opacity-50 text-white px-4 py-2 rounded-full text-sm"
                                                        >
                                                            <span x-text="currentIndex + 1"></span> / <span x-text="images.length"></span>
                                                        </div>
                                                    </div>
                                                </div>
                                            @else
                                                <div class="w-16 h-16 bg-gray-200 dark:bg-gray-700 rounded flex items-center justify-center">
                                                    <svg class="w-8 h-8 text-gray-400 dark:text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19.428 15.428a2 2 0 00-1.022-.547l-2.387-.477a6 6 0 00-3.86.517l-.318.158a6 6 0 01-3.86.517L6.05 15.21a2 2 0 00-1.806.547M8 4h8l-1 1v5.172a2 2 0 00.586 1.414l5 5c1.26 1.26.367 3.414-1.415 3.414H4.828c-1.782 0-2.674-2.154-1.414-3.414l5-5A2 2 0 009 10.172V5L8 4z" />
                                                    </svg>
                                                </div>
                                            @endif
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900 dark:text-gray-100">
                                            <a href="{{ route('equipment.show', $item) }}" class="hover:text-indigo-600 dark:hover:text-indigo-400">
                                                {{ Str::limit($item->name, 30) }}
                                            </a>
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500 dark:text-gray-400">
                                            {{ $item->serial_number }}
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500 dark:text-gray-400">
                                            {{ $item->equipmentCategory ? $item->equipmentCategory->name : '-' }}
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full 
                                                @switch($item->status)
                                                    @case('available') bg-green-100 dark:bg-green-900/50 text-green-800 dark:text-green-300 @break
                                                    @case('borrowed') bg-yellow-100 dark:bg-yellow-900/50 text-yellow-800 dark:text-yellow-300 @break
                                                    @case('maintenance') bg-red-100 dark:bg-red-900/50 text-red-800 dark:text-red-300 @break
                                                    @case('retired') bg-gray-100 dark:bg-gray-700 text-gray-800 dark:text-gray-300 @break
                                                @endswitch">
                                                @switch($item->status)
                                                    @case('available') Disponível @break
                                                    @case('borrowed') Emprestado @break
                                                    @case('maintenance') Manutenção @break
                                                    @case('retired') Aposentado @break
                                                @endswitch
                                            </span>
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500 dark:text-gray-400">
                                            {{ $item->currentEmployee ? $item->currentEmployee->name : '-' }}
                                        </td>
                                        @if(auth()->user()->can('view products') || auth()->user()->can('edit products'))
                                        <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                                            <div class="flex items-center justify-end space-x-4">
                                                @can('view products')
                                                <a href="{{ route('equipment.show', $item) }}" class="text-gray-600 dark:text-gray-400 hover:text-gray-900 dark:hover:text-gray-100">
                                                    <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                                                    </svg>
                                                </a>
                                                @endcan
                                                @can('edit products')
                                                <button 
                                                    onclick="openOffcanvas('equipment-offcanvas'); window.dispatchEvent(new CustomEvent('edit-equipment', { detail: { id: {{ $item->id }} } }));" 
                                                    type="button"
                                                    class="text-indigo-600 dark:text-indigo-400 hover:text-indigo-900 dark:hover:text-indigo-300"
                                                >
                                                    <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                                                    </svg>
                                                </button>
                                                @endcan
                                                @can('view products')
                                                <a href="{{ route('equipment.history', $item) }}" class="text-indigo-600 dark:text-indigo-400 hover:text-indigo-900 dark:hover:text-indigo-300">
                                                    <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                                                    </svg>
                                                </a>
                                                @endcan
                                            </div>
                                        </td>
                                        @endif
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>

                        <div class="mt-4">
                            {{ $equipment->withQueryString()->links() }}
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>

    <!-- Offcanvas para Novo/Editar Equipamento -->
    <x-offcanvas id="equipment-offcanvas" title="Novo Equipamento" width="w-full md:w-[700px]">
        @livewire('equipment-form', ['equipment' => null], key('equipment-form'))
    </x-offcanvas>
</x-app-layout>

@push('scripts')
<script>
    (function () {
        const getEquipmentFormComponent = () => {
            const offcanvas = document.getElementById('equipment-offcanvas');
            if (!offcanvas) return null;
            const componentEl = offcanvas.querySelector('[wire\\:id]');
            return componentEl ? Livewire.find(componentEl.getAttribute('wire:id')) : null;
        };

        document.addEventListener('livewire:init', () => {
            Livewire.on('equipmentSaved', () => {
                closeOffcanvas('equipment-offcanvas');
                // Recarregar a página para atualizar a lista
                window.location.reload();
            });
        });

        // Escutar evento de edição
        window.addEventListener('edit-equipment', (event) => {
            const equipmentId = event.detail.id;
            const offcanvas = document.getElementById('equipment-offcanvas');
            const title = offcanvas.querySelector('h2');
            if (title) {
                title.textContent = 'Editar Equipamento';
            }
            // Abrir o offcanvas antes de carregar os dados
            openOffcanvas('equipment-offcanvas');

            // Aguardar o componente dentro do offcanvas estar disponível
            setTimeout(() => {
                const component = getEquipmentFormComponent();
                if (component) {
                    // Chamada direta ao método Livewire
                    component.call('loadEquipment', equipmentId);
                    // Fallback: disparar evento Livewire para componentes ouvintes
                    if (window.Livewire?.dispatch) {
                        window.Livewire.dispatch('edit-equipment', { id: equipmentId });
                    }
                } else {
                    console.warn('[equipment] componente equipment-form não encontrado dentro do offcanvas');
                }
            }, 200);
        });

        // Resetar título e formulário quando abrir para novo
        document.addEventListener('click', (e) => {
            if (e.target.closest('[onclick*="equipment-offcanvas"]') && !e.target.closest('[onclick*="edit-equipment"]')) {
                const offcanvas = document.getElementById('equipment-offcanvas');
                const title = offcanvas.querySelector('h2');
                if (title) {
                    title.textContent = 'Novo Equipamento';
                }

                // Resetar o formulário apenas do componente dentro do offcanvas
                setTimeout(() => {
                    const component = getEquipmentFormComponent();
                    if (component) {
                        component.call('resetForm');
                        if (window.Livewire?.dispatch) {
                            window.Livewire.dispatch('reset-equipment-form');
                        }
                    }
                }, 100);
            }
        });
    })();
</script>
@endpush
