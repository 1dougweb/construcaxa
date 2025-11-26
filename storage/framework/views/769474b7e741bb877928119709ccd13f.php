<?php if (isset($component)) { $__componentOriginal4619374cef299e94fd7263111d0abc69 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal4619374cef299e94fd7263111d0abc69 = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.app-layout','data' => []] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('app-layout'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes([]); ?>
     <?php $__env->slot('header', null, []); ?> 
        <div class="flex justify-between items-center">
            <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
                <?php echo e(__('Vistorias Técnicas')); ?>

            </h2>
            <div class="flex items-center space-x-4">
                <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('edit service-orders')): ?>
                <a href="<?php echo e(route('technical-inspections.create')); ?>" class="inline-flex items-center px-4 py-2 bg-indigo-600 dark:bg-indigo-700 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-indigo-700 dark:hover:bg-indigo-600 focus:bg-indigo-700 active:bg-indigo-900 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 transition ease-in-out duration-150">
                    <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
                    </svg>
                    <?php echo e(__('Nova Vistoria')); ?>

                </a>
                <?php endif; ?>
            </div>
        </div>
     <?php $__env->endSlot(); ?>

    <div class="py-6">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-xl sm:rounded-lg">
                <div class="p-6">
                    <!-- Filtros -->
                    <div class="mb-6">
                        <form method="GET" action="<?php echo e(route('technical-inspections.index')); ?>" class="flex flex-wrap gap-4">
                            <div class="flex-1 min-w-64">
                                <input 
                                    type="text" 
                                    name="search" 
                                    value="<?php echo e(request('search')); ?>" 
                                    placeholder="Buscar por número, endereço ou responsável..." 
                                    class="w-full rounded-md border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 dark:focus:border-indigo-400 dark:focus:ring-indigo-400"
                                />
                            </div>
                            <div>
                                <select name="status" class="rounded-md border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 dark:focus:border-indigo-400 dark:focus:ring-indigo-400">
                                    <option value="">Todos os status</option>
                                    <option value="draft" <?php echo e(request('status') === 'draft' ? 'selected' : ''); ?>>Rascunho</option>
                                    <option value="in_progress" <?php echo e(request('status') === 'in_progress' ? 'selected' : ''); ?>>Em Andamento</option>
                                    <option value="completed" <?php echo e(request('status') === 'completed' ? 'selected' : ''); ?>>Concluída</option>
                                </select>
                            </div>
                            <div>
                                <input 
                                    type="date" 
                                    name="date_from" 
                                    value="<?php echo e(request('date_from')); ?>"
                                    placeholder="Data Inicial"
                                    class="rounded-md border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 dark:focus:border-indigo-400 dark:focus:ring-indigo-400"
                                />
                            </div>
                            <div>
                                <input 
                                    type="date" 
                                    name="date_to" 
                                    value="<?php echo e(request('date_to')); ?>"
                                    placeholder="Data Final"
                                    class="rounded-md border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 dark:focus:border-indigo-400 dark:focus:ring-indigo-400"
                                />
                            </div>
                            <button type="submit" class="inline-flex items-center px-4 py-2 bg-gray-800 dark:bg-gray-700 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-gray-700 dark:hover:bg-gray-600 focus:bg-gray-700 active:bg-gray-900 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 transition ease-in-out duration-150">
                                Filtrar
                            </button>
                            <a href="<?php echo e(route('technical-inspections.index')); ?>" class="inline-flex items-center px-4 py-2 bg-gray-300 dark:bg-gray-600 border border-transparent rounded-md font-semibold text-xs text-gray-700 dark:text-gray-300 uppercase tracking-widest hover:bg-gray-400 dark:hover:bg-gray-500 focus:bg-gray-400 active:bg-gray-500 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 transition ease-in-out duration-150">
                                Limpar
                            </a>
                        </form>
                    </div>
                    <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($inspections->isEmpty()): ?>
                        <div class="text-center py-8">
                            <svg class="mx-auto h-12 w-12 text-gray-400 dark:text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                            </svg>
                            <h3 class="mt-2 text-sm font-medium text-gray-900 dark:text-gray-100">Nenhuma vistoria encontrada</h3>
                            <p class="mt-1 text-sm text-gray-500 dark:text-gray-400">Comece criando uma nova vistoria técnica.</p>
                            <div class="mt-6">
                                <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('edit service-orders')): ?>
                                <a href="<?php echo e(route('technical-inspections.create')); ?>" class="inline-flex items-center px-4 py-2 bg-indigo-600 dark:bg-indigo-700 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-indigo-700 dark:hover:bg-indigo-600">
                                    <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
                                    </svg>
                                    <?php echo e(__('Nova Vistoria')); ?>

                                </a>
                                <?php endif; ?>
                            </div>
                        </div>
                    <?php else: ?>
                        <table class="min-w-full divide-y divide-gray-200 dark:divide-gray-700">
                            <thead>
                                <tr>
                                    <th class="px-6 py-3 bg-gray-50 dark:bg-gray-700/50 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">Número</th>
                                    <th class="px-6 py-3 bg-gray-50 dark:bg-gray-700/50 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">Data</th>
                                    <th class="px-6 py-3 bg-gray-50 dark:bg-gray-700/50 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">Responsável</th>
                                    <th class="px-6 py-3 bg-gray-50 dark:bg-gray-700/50 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">Status</th>
                                    <th class="px-6 py-3 bg-gray-50 dark:bg-gray-700/50 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">Fotos</th>
                                    <th class="px-6 py-3 bg-gray-50 dark:bg-gray-700/50 text-right text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">Ações</th>
                                </tr>
                            </thead>
                            <tbody class="bg-white dark:bg-gray-800 divide-y divide-gray-200 dark:divide-gray-700">
                                <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php $__currentLoopData = $inspections; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $inspection): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                    <tr>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900 dark:text-gray-100">
                                            <a href="<?php echo e(route('technical-inspections.show', $inspection)); ?>" class="hover:text-indigo-600 dark:hover:text-indigo-400">
                                                <?php echo e($inspection->number); ?>

                                            </a>
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500 dark:text-gray-400">
                                            <?php echo e($inspection->inspection_date->format('d/m/Y')); ?>

                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500 dark:text-gray-400">
                                            <?php echo e($inspection->responsible_name); ?>

                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full <?php echo e($inspection->status_color); ?>">
                                                <?php echo e($inspection->status_label); ?>

                                            </span>
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500 dark:text-gray-400">
                                            <?php echo e($inspection->total_photos_count); ?> foto(s)
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                                            <div class="flex items-center justify-end space-x-2">
                                                <!-- Visualizar -->
                                                <a href="<?php echo e(route('technical-inspections.show', $inspection)); ?>" 
                                                   class="p-2 text-indigo-600 dark:text-indigo-400 hover:text-indigo-900 dark:hover:text-indigo-300 hover:bg-indigo-50 dark:hover:bg-indigo-900/30 rounded transition-colors"
                                                   title="Visualizar">
                                                    <i class="fi fi-rr-eye text-lg"></i>
                                                </a>
                                                
                                                <!-- Mapa -->
                                                <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($inspection->address): ?>
                                                <button 
                                                   type="button"
                                                   x-data
                                                   @click="$dispatch('open-map-modal', { 
                                                       address: <?php echo \Illuminate\Support\Js::from($inspection->address)->toHtml() ?>, 
                                                       coordinates: <?php echo \Illuminate\Support\Js::from($inspection->coordinates)->toHtml() ?> 
                                                   })"
                                                   class="p-2 text-purple-600 dark:text-purple-400 hover:text-purple-900 dark:hover:text-purple-300 hover:bg-purple-50 dark:hover:bg-purple-900/30 rounded transition-colors"
                                                   title="Ver no Mapa">
                                                    <i class="fi fi-rr-map text-lg"></i>
                                                </button>
                                                <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                                                
                                                <!-- PDF - Visualizar no navegador -->
                                                <a href="<?php echo e(route('technical-inspections.view-pdf', $inspection)); ?>" 
                                                   target="_blank"
                                                   class="p-2 text-green-600 dark:text-green-400 hover:text-green-900 dark:hover:text-green-300 hover:bg-green-50 dark:hover:bg-green-900/30 rounded transition-colors"
                                                   title="Visualizar PDF">
                                                    <i class="fi fi-rr-file-pdf text-lg"></i>
                                                </a>
                                                
                                                <!-- PDF - Download -->
                                                <a href="<?php echo e(route('technical-inspections.pdf', $inspection)); ?>" 
                                                   class="p-2 text-blue-600 dark:text-blue-400 hover:text-blue-900 dark:hover:text-blue-300 hover:bg-blue-50 dark:hover:bg-blue-900/30 rounded transition-colors"
                                                   title="Baixar PDF">
                                                    <i class="fi fi-rr-download text-lg"></i>
                                                </a>
                                                
                                                <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('edit service-orders')): ?>
                                                <!-- Deletar -->
                                                <button 
                                                    type="button"
                                                    x-data
                                                    @click="$dispatch('open-delete-modal', { 
                                                        id: <?php echo e($inspection->id); ?>, 
                                                        number: <?php echo \Illuminate\Support\Js::from($inspection->number)->toHtml() ?>,
                                                        route: <?php echo \Illuminate\Support\Js::from(route('technical-inspections.destroy', $inspection))->toHtml() ?>
                                                    })"
                                                    class="p-2 text-red-600 dark:text-red-400 hover:text-red-900 dark:hover:text-red-300 hover:bg-red-50 dark:hover:bg-red-900/30 rounded transition-colors"
                                                    title="Excluir">
                                                    <i class="fi fi-rr-trash text-lg"></i>
                                                </button>
                                                <?php endif; ?>
                                            </div>
                                        </td>
                                    </tr>
                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                            </tbody>
                        </table>

                        <div class="mt-4">
                            <?php echo e($inspections->links()); ?>

                        </div>
                    <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                </div>
            </div>
        </div>
    </div>

    <!-- Modal de Confirmação de Exclusão -->
    <div x-data="{ 
        show: false, 
        inspectionId: null,
        inspectionNumber: '',
        deleteRoute: '',
        open(data) {
            this.inspectionId = data.id;
            this.inspectionNumber = data.number;
            this.deleteRoute = data.route;
            this.show = true;
        },
        close() {
            this.show = false;
            this.inspectionId = null;
            this.inspectionNumber = '';
            this.deleteRoute = '';
        },
        confirmDelete() {
            if (this.deleteRoute) {
                const form = document.createElement('form');
                form.method = 'POST';
                form.action = this.deleteRoute;
                
                // CSRF Token
                const csrfInput = document.createElement('input');
                csrfInput.type = 'hidden';
                csrfInput.name = '_token';
                csrfInput.value = document.querySelector('meta[name="csrf-token"]')?.getAttribute('content') || '';
                form.appendChild(csrfInput);
                
                // Method Spoofing
                const methodInput = document.createElement('input');
                methodInput.type = 'hidden';
                methodInput.name = '_method';
                methodInput.value = 'DELETE';
                form.appendChild(methodInput);
                
                document.body.appendChild(form);
                form.submit();
            }
        }
    }" 
    x-show="show" 
    x-cloak
    class="fixed inset-0 z-50 overflow-y-auto"
    style="display: none;"
    @keydown.escape.window="close()"
    @click.away="close()"
    @open-delete-modal.window="open($event.detail)">
        <!-- Backdrop -->
        <div 
            class="fixed inset-0 bg-gray-900 bg-opacity-50 backdrop-blur-sm transition-opacity"
            style="backdrop-filter: blur(4px); -webkit-backdrop-filter: blur(4px);"
            @click="close()"
        ></div>

        <!-- Modal -->
        <div class="flex min-h-full items-end justify-center p-4 text-center sm:items-center sm:p-0">
            <div 
                class="relative transform overflow-hidden rounded-lg bg-white dark:bg-gray-800 text-left shadow-xl transition-all sm:my-8 sm:w-full sm:max-w-lg"
                @click.stop
            >
                <div class="bg-white dark:bg-gray-800 px-4 pt-5 pb-4 sm:p-6 sm:pb-4">
                    <div class="sm:flex sm:items-start">
                        <div class="mx-auto flex h-12 w-12 flex-shrink-0 items-center justify-center rounded-full bg-red-100 dark:bg-red-900/30 sm:mx-0 sm:h-10 sm:w-10">
                            <svg class="h-6 w-6 text-red-600 dark:text-red-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" />
                            </svg>
                        </div>
                        <div class="mt-3 text-center sm:mt-0 sm:ml-4 sm:text-left">
                            <h3 class="text-lg font-medium leading-6 text-gray-900 dark:text-gray-100" id="modal-title">
                                Excluir Vistoria Técnica
                            </h3>
                            <div class="mt-2">
                                <p class="text-sm text-gray-500 dark:text-gray-400">
                                    Tem certeza que deseja excluir a vistoria técnica <strong class="text-gray-900 dark:text-gray-100" x-text="inspectionNumber"></strong>? Esta ação não pode ser desfeita.
                                </p>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="bg-gray-50 dark:bg-gray-700 px-4 py-3 sm:flex sm:flex-row-reverse sm:px-6">
                    <button 
                        type="button" 
                        class="inline-flex w-full justify-center rounded-md border border-transparent bg-red-600 hover:bg-red-700 focus:ring-red-500 px-4 py-2 text-base font-medium text-white shadow-sm focus:outline-none focus:ring-2 focus:ring-offset-2 sm:ml-3 sm:w-auto sm:text-sm"
                        @click="confirmDelete()"
                    >
                        Excluir
                    </button>
                    <button 
                        type="button" 
                        class="mt-3 inline-flex w-full justify-center rounded-md border border-gray-300 bg-white dark:bg-gray-600 dark:border-gray-500 dark:text-gray-300 px-4 py-2 text-base font-medium text-gray-700 dark:text-gray-200 shadow-sm hover:bg-gray-50 dark:hover:bg-gray-500 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 sm:mt-0 sm:ml-3 sm:w-auto sm:text-sm"
                        @click="close()"
                    >
                        Cancelar
                    </button>
                </div>
            </div>
        </div>
    </div>

    <!-- Modal do Mapa -->
    <div x-data="{ 
        show: false, 
        address: '', 
        coordinates: null,
        open(address, coords) {
            this.address = address;
            this.coordinates = coords;
            this.show = true;
            setTimeout(() => {
                if (typeof google !== 'undefined' && google.maps) {
                    initModalMap(address, coords);
                }
            }, 300);
        },
        close() {
            this.show = false;
            this.address = '';
            this.coordinates = null;
            if (typeof modalMap !== 'undefined' && modalMap) {
                modalMap = null;
                modalMarker = null;
            }
        }
    }" 
    x-show="show" 
    x-cloak
    class="fixed inset-0 bg-gray-600 bg-opacity-30 overflow-y-auto h-full w-full z-50 backdrop-blur-md transition-all duration-300" 
    x-show="show"
    @keydown.escape.window="close()"
    @click.away="close()"
    @open-map-modal.window="open($event.detail.address, $event.detail.coordinates)">
        <div class="relative top-20 mx-auto p-5 border-none w-11/12 md:w-3/4 lg:w-1/2 shadow-lg rounded-md bg-white dark:bg-gray-800" @click.stop>
            <div class="flex items-center justify-between mb-4">
                <h3 class="text-lg font-semibold text-gray-900 dark:text-gray-100">Localização no Mapa</h3>
                <button @click="close()" class="text-gray-400 hover:text-gray-600 dark:hover:text-gray-300">
                    <i class="fi fi-rr-cross text-xl"></i>
                </button>
            </div>
            <div class="mb-3 text-sm text-gray-600 dark:text-gray-400" x-text="address"></div>
            <div id="modalMapContainer" class="w-full h-96 border border-gray-300 dark:border-gray-600 rounded-lg"></div>
        </div>
    </div>

    <?php $__env->startPush('scripts'); ?>
    <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if(!empty($mapsApiKey)): ?>
    <script src="https://maps.googleapis.com/maps/api/js?key=<?php echo e($mapsApiKey); ?>&libraries=places&language=pt-BR&region=BR" async defer></script>
    <?php else: ?>
    <script>
        console.warn('Google Maps API key não configurada');
    </script>
    <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
    <script>
        let modalMap;
        let modalMarker;

        function initModalMap(address, coordinates) {
            const mapContainer = document.getElementById('modalMapContainer');
            if (!mapContainer) return;

            // Limpar mapa anterior se existir
            if (modalMap) {
                modalMap = null;
                modalMarker = null;
            }

            let center;
            let zoom = 15;

            if (coordinates && coordinates.lat && coordinates.lng) {
                center = { lat: parseFloat(coordinates.lat), lng: parseFloat(coordinates.lng) };
                createModalMap(center, zoom, address);
            } else {
                // Geocodificar o endereço
                const geocoder = new google.maps.Geocoder();
                geocoder.geocode({ 
                    address: address,
                    region: 'BR'
                }, function(results, status) {
                    if (status === 'OK' && results[0]) {
                        center = results[0].geometry.location;
                        createModalMap(center, zoom, address);
                    } else {
                        // Usar coordenadas padrão (Brasil)
                        center = { lat: -23.5505, lng: -46.6333 };
                        zoom = 10;
                        createModalMap(center, zoom, address);
                    }
                });
            }
        }

        function createModalMap(center, zoom, address) {
            const mapContainer = document.getElementById('modalMapContainer');
            
            modalMap = new google.maps.Map(mapContainer, {
                center: center,
                zoom: zoom,
                mapTypeId: 'roadmap',
            });

            modalMarker = new google.maps.Marker({
                position: center,
                map: modalMap,
                title: address,
            });
        }
    </script>
    <?php $__env->stopPush(); ?>
 <?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginal4619374cef299e94fd7263111d0abc69)): ?>
<?php $attributes = $__attributesOriginal4619374cef299e94fd7263111d0abc69; ?>
<?php unset($__attributesOriginal4619374cef299e94fd7263111d0abc69); ?>
<?php endif; ?>
<?php if (isset($__componentOriginal4619374cef299e94fd7263111d0abc69)): ?>
<?php $component = $__componentOriginal4619374cef299e94fd7263111d0abc69; ?>
<?php unset($__componentOriginal4619374cef299e94fd7263111d0abc69); ?>
<?php endif; ?>

<?php /**PATH C:\Users\Douglas\Documents\Projetos\stock-master\resources\views/technical-inspections/index.blade.php ENDPATH**/ ?>