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
        <div class="flex items-center justify-between">
            <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
                <?php echo e(__('Bater Ponto')); ?>

            </h2>
        </div>
     <?php $__env->endSlot(); ?>

    <div class="py-6">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-xl sm:rounded-lg border border-gray-200 dark:border-gray-700">
                <div class="p-6 lg:p-8 bg-white dark:bg-gray-800 border-b border-gray-200 dark:border-gray-700">

                    <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($errors->any()): ?>
                        <div class="mb-4 rounded-md bg-red-50 dark:bg-red-900/20 p-4 border border-red-200 dark:border-red-800">
                            <div class="text-sm text-red-700 dark:text-red-300">
                                <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php $__currentLoopData = $errors->all(); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $error): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                    <div><?php echo e($error); ?></div>
                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                            </div>
                        </div>
                    <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>

                    <div class="space-y-4">
                        <div>
                            <p class="text-sm text-gray-600 dark:text-gray-400">Próximo registro permitido: <span class="font-medium text-gray-900 dark:text-gray-100"><?php echo e($nextType === 'entry' ? 'Entrada' : 'Saída'); ?></span></p>
                        </div>

                        <form id="punch-form" action="<?php echo e(route('attendance.punch')); ?>" method="POST" class="space-y-2">
                            <?php echo csrf_field(); ?>
                            <input type="hidden" id="latitude" name="latitude">
                            <input type="hidden" id="longitude" name="longitude">
                            <input type="hidden" id="accuracy" name="accuracy">

                            <div class="text-sm" id="geo-status"></div>

                            <div class="flex gap-2">
                                <?php if (isset($component)) { $__componentOriginald0f1fd2689e4bb7060122a5b91fe8561 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginald0f1fd2689e4bb7060122a5b91fe8561 = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.button','data' => ['id' => 'punch-button','type' => 'button','disabled' => true]] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('button'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['id' => 'punch-button','type' => 'button','disabled' => true]); ?>
                                    <?php echo e($nextType === 'entry' ? 'Bater Entrada' : 'Bater Saída'); ?>

                                 <?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginald0f1fd2689e4bb7060122a5b91fe8561)): ?>
<?php $attributes = $__attributesOriginald0f1fd2689e4bb7060122a5b91fe8561; ?>
<?php unset($__attributesOriginald0f1fd2689e4bb7060122a5b91fe8561); ?>
<?php endif; ?>
<?php if (isset($__componentOriginald0f1fd2689e4bb7060122a5b91fe8561)): ?>
<?php $component = $__componentOriginald0f1fd2689e4bb7060122a5b91fe8561; ?>
<?php unset($__componentOriginald0f1fd2689e4bb7060122a5b91fe8561); ?>
<?php endif; ?>
                                <button type="button" id="retry-location-btn" class="hidden px-4 py-2 text-sm font-medium text-gray-700 dark:text-gray-300 bg-white dark:bg-gray-700 border border-gray-300 dark:border-gray-600 rounded-md hover:bg-gray-50 dark:hover:bg-gray-600 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                                    Tentar Novamente
                                </button>
                            </div>
                        </form>

                        <div class="pt-4">
                            <h3 class="text-sm font-semibold text-gray-700 dark:text-gray-300">Registros de hoje</h3>
                            <div class="mt-2 border border-gray-200 dark:border-gray-700 rounded-md divide-y divide-gray-200 dark:divide-gray-700">
                                <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php $__empty_1 = true; $__currentLoopData = $todayEntries; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $entry): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                                    <div class="p-3 flex items-center justify-between text-sm bg-white dark:bg-gray-800">
                                        <div>
                                            <span class="inline-flex items-center px-2 py-0.5 rounded text-xs font-medium <?php echo e($entry->type === 'entry' ? 'bg-green-100 dark:bg-green-900/30 text-green-800 dark:text-green-300' : 'bg-indigo-100 dark:bg-indigo-900/30 text-indigo-800 dark:text-indigo-300'); ?>">
                                                <?php echo e($entry->type === 'entry' ? 'Entrada' : 'Saída'); ?>

                                            </span>
                                            <span class="ml-2 text-gray-700 dark:text-gray-300"><?php echo e($entry->punched_at->format('H:i')); ?></span>
                                        </div>
                                        <div class="text-gray-500 dark:text-gray-400 flex items-center gap-3">
                                            <span><?php echo e(number_format($entry->latitude, 5)); ?>, <?php echo e(number_format($entry->longitude, 5)); ?></span>
                                            <button type="button" class="text-indigo-600 dark:text-indigo-400 hover:text-indigo-800 dark:hover:text-indigo-300 hover:underline" onclick="openLocationModal(<?php echo e($entry->latitude); ?>, <?php echo e($entry->longitude); ?>)">Localização</button>
                                        </div>
                                    </div>
                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                                    <div class="p-3 text-sm text-gray-500 dark:text-gray-400">Nenhum registro hoje.</div>
                                <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Modal de Confirmação de Localização com Google Maps -->
    <div id="confirm-location-modal" 
         x-data="{ 
             open: false, 
             lat: null, 
             lng: null, 
             accuracy: null,
             address: null,
             loadingAddress: false,
             map: null,
             marker: null,
             circle: null
         }"
         x-show="open"
         x-cloak
         x-transition:enter="transition ease-out duration-300"
         x-transition:enter-start="opacity-0"
         x-transition:enter-end="opacity-100"
         x-transition:leave="transition ease-in duration-200"
         x-transition:leave-start="opacity-100"
         x-transition:leave-end="opacity-0"
         class="fixed inset-0 z-50 flex items-center justify-center"
         style="background-color: rgba(0, 0, 0, 0.75); backdrop-filter: blur(4px); -webkit-backdrop-filter: blur(4px); display: none;"
         @click.self="open = false; closeConfirmModal()"
         @keydown.escape.window="open = false; closeConfirmModal()">
        <div x-show="open"
             x-transition:enter="transition ease-out duration-300"
             x-transition:enter-start="opacity-0 scale-95"
             x-transition:enter-end="opacity-100 scale-100"
             x-transition:leave="transition ease-in duration-200"
             x-transition:leave-start="opacity-100 scale-100"
             x-transition:leave-end="opacity-0 scale-95"
             class="relative bg-white dark:bg-gray-800 rounded-lg shadow-2xl p-6 w-full max-w-4xl mx-4 border border-gray-200 dark:border-gray-700"
             style="max-height: calc(100vh - 3rem); overflow-y: auto;"
             @click.stop>
            <div class="flex items-center justify-between mb-4">
                <div>
                    <h3 class="font-semibold text-gray-900 dark:text-gray-100 text-lg">Confirmar Localização</h3>
                    <p class="mt-1 text-sm text-gray-600 dark:text-gray-400">Verifique se a localização está correta antes de bater o ponto</p>
                </div>
                <button @click="open = false; closeConfirmModal()" class="text-gray-400 dark:text-gray-500 hover:text-gray-600 dark:hover:text-gray-400 transition-colors p-1 rounded-full hover:bg-gray-100 dark:hover:bg-gray-700 focus:outline-none">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                    </svg>
                </button>
            </div>
            
            <!-- Busca de endereço para melhorar precisão -->
            <div class="mb-4">
                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                    Buscar endereço para melhorar a precisão
                </label>
                <div class="flex gap-2">
                    <input 
                        type="text" 
                        id="address-search-input"
                        placeholder="Digite um endereço ou local..."
                        class="flex-1 px-3 py-2 border border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 text-sm">
                    <button 
                        type="button"
                        id="search-address-btn"
                        class="px-4 py-2 bg-indigo-600 dark:bg-indigo-700 text-white rounded-md hover:bg-indigo-700 dark:hover:bg-indigo-600 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 text-sm font-medium">
                        Buscar
                    </button>
                    <button 
                        type="button"
                        id="use-map-center-btn"
                        class="px-4 py-2 bg-gray-600 dark:bg-gray-700 text-white rounded-md hover:bg-gray-700 dark:hover:bg-gray-600 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-gray-500 text-sm font-medium">
                        Usar Centro do Mapa
                    </button>
                </div>
                <p class="mt-1 text-xs text-gray-500 dark:text-gray-400">Busque por endereço ou arraste o marcador para ajustar a localização</p>
            </div>

            <div x-show="loadingAddress" class="mb-4 p-3 bg-blue-50 dark:bg-blue-900/20 rounded-md border border-blue-200 dark:border-blue-800">
                <p class="text-sm text-blue-700 dark:text-blue-300">Carregando endereço...</p>
            </div>
            <div x-show="address && !loadingAddress" class="mb-4 p-3 bg-gray-50 dark:bg-gray-700/50 rounded-md border border-gray-200 dark:border-gray-700">
                <p class="text-sm text-gray-700 dark:text-gray-300"><strong class="text-gray-900 dark:text-gray-100">Endereço:</strong> <span x-text="address"></span></p>
            </div>
            <div x-show="!address && !loadingAddress" class="mb-4 p-3 bg-yellow-50 dark:bg-yellow-900/20 rounded-md border border-yellow-200 dark:border-yellow-800">
                <p class="text-sm text-yellow-700 dark:text-yellow-300">Endereço não disponível</p>
            </div>

            <div id="confirm-map-container" class="w-full h-96 rounded-md border border-gray-300 dark:border-gray-600 mb-4" style="min-height: 384px;"></div>

            <div class="flex items-center justify-between pt-4 border-t border-gray-200 dark:border-gray-700">
                <div class="text-sm text-gray-600 dark:text-gray-400">
                    <div x-show="accuracy">
                        <span>Precisão GPS: <span x-text="accuracy" class="font-medium text-gray-900 dark:text-gray-100"></span> metros</span>
                    </div>
                    <div class="mt-1 text-xs text-blue-600 dark:text-blue-400">
                        <i class="bi bi-info-circle"></i> Arraste o marcador no mapa para ajustar a localização
                    </div>
                </div>
                <div class="flex gap-3">
                    <button 
                        @click="open = false; closeConfirmModal()" 
                        class="px-4 py-2 text-sm font-medium text-gray-700 dark:text-gray-300 bg-white dark:bg-gray-700 border border-gray-300 dark:border-gray-600 rounded-md hover:bg-gray-50 dark:hover:bg-gray-600 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                        Cancelar
                    </button>
                    <button 
                        @click="confirmLocation()" 
                        class="px-4 py-2 text-sm font-medium text-white bg-indigo-600 dark:bg-indigo-700 rounded-md hover:bg-indigo-700 dark:hover:bg-indigo-600 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                        Confirmar e Bater Ponto
                    </button>
                </div>
            </div>
        </div>
    </div>

    <!-- Modal de Localização (para histórico) -->
    <div id="location-modal" class="fixed inset-0 z-50 hidden">
        <div class="absolute inset-0 bg-black/40 dark:bg-black/60" onclick="closeLocationModal()"></div>
        <div class="absolute inset-0 flex items-center justify-center p-4">
            <div class="bg-white dark:bg-gray-800 w-full max-w-lg rounded-lg shadow-lg overflow-hidden border border-gray-200 dark:border-gray-700">
                <div class="px-4 py-3 border-b border-gray-200 dark:border-gray-700 flex items-center justify-between">
                    <h3 class="text-sm font-medium text-gray-900 dark:text-gray-100">Localização</h3>
                    <button class="text-gray-500 dark:text-gray-400 hover:text-gray-700 dark:hover:text-gray-300" onclick="closeLocationModal()">✕</button>
                </div>
                <div class="p-4 space-y-3 bg-white dark:bg-gray-800" id="location-modal-body">
                    <div class="text-sm text-gray-700 dark:text-gray-300" id="loc-address"></div>
                    <div>
                        <a id="loc-map-link" href="#" target="_blank" class="text-indigo-600 dark:text-indigo-400 hover:text-indigo-800 dark:hover:text-indigo-300 text-sm underline">Abrir no Google Maps</a>
                    </div>
                    <div class="mt-2">
                        <div class="w-full overflow-hidden rounded-md border border-gray-200 dark:border-gray-700">
                            <div class="relative" style="padding-bottom: 56.25%; height: 0;">
                                <iframe id="loc-map-iframe" title="Mapa"
                                    class="absolute top-0 left-0 w-full h-full border-0"
                                    src="about:blank" allowfullscreen loading="lazy" referrerpolicy="no-referrer-when-downgrade"></iframe>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    
    <script>
        const completedToday = <?php echo e($todayEntries->count() >= 2 ? 'true' : 'false'); ?>;
        let button, retryBtn, statusEl, latEl, lngEl, accEl;
        
        // Aguardar DOM estar pronto
        document.addEventListener('DOMContentLoaded', function() {
            button = document.getElementById('punch-button');
            retryBtn = document.getElementById('retry-location-btn');
            statusEl = document.getElementById('geo-status');
            latEl = document.getElementById('latitude');
            lngEl = document.getElementById('longitude');
            accEl = document.getElementById('accuracy');
            
            // Se o botão não foi encontrado, tentar encontrar pelo componente
            if (!button) {
                button = document.querySelector('#punch-button, button[type="button"]#punch-button, .punch-button, button[id*="punch"]');
            }
            
            // Debug: verificar se o botão foi encontrado
            if (button) {
                console.log('Botão encontrado:', button);
                initButtonHandlers();
            } else {
                console.error('Botão não encontrado! Tentando novamente...');
                // Tentar novamente após um pequeno delay
                setTimeout(() => {
                    button = document.getElementById('punch-button') || 
                             document.querySelector('button[id*="punch"], button[type="button"]');
                    if (button) {
                        console.log('Botão encontrado no segundo tentativa:', button);
                        initButtonHandlers();
                    } else {
                        console.error('Botão ainda não encontrado após segunda tentativa');
                    }
                }, 500);
            }
            
            // Inicializar botão de retry
            initRetryButton();
            
            // Request location after a small delay to ensure everything is initialized
            setTimeout(() => {
                requestLocation();
            }, 100);
        });
        
        // Funções auxiliares (acessíveis globalmente)
        const enableButton = () => { 
            if (button) {
                button.removeAttribute('disabled');
                button.disabled = false;
            }
            if (retryBtn) retryBtn.classList.add('hidden');
            console.log('Botão habilitado');
        };
        const disableButton = () => { 
            if (button) {
                button.setAttribute('disabled', 'disabled');
                button.disabled = true;
            }
        };
        const showRetryButton = () => {
            if (retryBtn) retryBtn.classList.remove('hidden');
        };
        const hideRetryButton = () => {
            if (retryBtn) retryBtn.classList.add('hidden');
        };

        const setStatus = (msg, ok = false) => {
            if (statusEl) {
                statusEl.textContent = msg;
                statusEl.className = `text-sm ${ok ? 'text-green-700 dark:text-green-400' : 'text-gray-600 dark:text-gray-400'}`;
            }
        };

        const maxAccuracyMeters = 50; // precisão ideal
        const minAccuracyMeters = 200; // precisão mínima aceitável
        let geoWatchId = null;
        
        // Funções de geolocalização (definidas antes de serem usadas)
        const onSuccess = (pos) => {
            const { latitude, longitude, accuracy } = pos.coords;
            if (latEl) latEl.value = latitude;
            if (lngEl) lngEl.value = longitude;
            if (accEl) accEl.value = accuracy;
            
            if (Number.isFinite(accuracy)) {
                if (accuracy <= maxAccuracyMeters) {
                    setStatus(`Precisão: ${accuracy.toFixed(0)} m • pronto para bater ponto`, true);
                    enableButton();
                } else if (accuracy <= minAccuracyMeters) {
                    setStatus(`Precisão: ${accuracy.toFixed(0)} m • você pode bater o ponto (precisão moderada)`, true);
                    enableButton();
                } else {
                    // Mesmo com precisão baixa, permitir abrir o modal para ajuste manual
                    setStatus(`Precisão: ${accuracy.toFixed(0)} m • você pode ajustar a localização no mapa`, true);
                    enableButton();
                }
            } else {
                setStatus('Localização obtida. Você pode bater o ponto.', true);
                enableButton();
            }
        };

        const onError = (err) => {
            disableButton();
            
            // Handle different error types
            switch (err.code) {
                case err.PERMISSION_DENIED:
                    setStatus('Permissão de localização negada. Clique em "Tentar Novamente" após permitir a localização nas configurações do navegador.');
                    // Stop watching if permission is denied
                    if (geoWatchId !== null) {
                        try {
                            navigator.geolocation.clearWatch(geoWatchId);
                            geoWatchId = null;
                        } catch (e) {}
                    }
                    // Show retry button
                    showRetryButton();
                    // Only log if not already handled (avoid spam)
                    if (!window.geoErrorLogged) {
                        console.warn('Geolocation permission denied by user');
                        window.geoErrorLogged = true;
                    }
                    break;
                case err.POSITION_UNAVAILABLE:
                    setStatus('Localização indisponível. Verifique se o GPS está ativado. Tentando novamente...');
                    // Continue tentando para este tipo de erro
                    break;
                case err.TIMEOUT:
                    setStatus('Tempo esgotado ao buscar localização. Tentando novamente...');
                    // Continue tentando para timeout
                    break;
                default:
                    setStatus('Erro ao obter localização. Tentando novamente...');
                    // Continue tentando para outros erros
                    break;
            }
        };

        const requestLocation = () => {
            if (!('geolocation' in navigator)) {
                setStatus('Seu navegador não suporta geolocalização.');
                return;
            }
            if (completedToday) {
                setStatus('Você já registrou entrada e saída hoje.');
                disableButton();
                return;
            }
            
            // Reset error log flag when retrying
            window.geoErrorLogged = false;
            
            setStatus('Buscando localização com alta precisão...');
            try { if (geoWatchId !== null) { navigator.geolocation.clearWatch(geoWatchId); } } catch (e) {}
            geoWatchId = navigator.geolocation.watchPosition(onSuccess, onError, {
                enableHighAccuracy: true,
                timeout: 20000,
                maximumAge: 0,
            });
        };
        
        function initRetryButton() {
            if (retryBtn) {
                retryBtn.addEventListener('click', () => {
                    hideRetryButton();
                    window.geoErrorLogged = false;
                    requestLocation();
                });
            }
        }
        
        // Flag para evitar múltiplos handlers
        let buttonHandlerAttached = false;
        
        function initButtonHandlers() {
            // Handler do botão de bater ponto - agora abre o modal
            if (button && !buttonHandlerAttached) {
                buttonHandlerAttached = true;
                
                button.addEventListener('click', function(e) {
                    e.preventDefault();
                    e.stopPropagation();
                    handlePunchButtonClick();
                });
            }
        }
        
        // Also re-check when the user focuses the page (after enabling GPS)
        window.addEventListener('focus', requestLocation);

        // Location modal logic
        const modal = document.getElementById('location-modal');
        const locAddress = document.getElementById('loc-address');
        const locMapLink = document.getElementById('loc-map-link');
        const locMapIframe = document.getElementById('loc-map-iframe');

        window.openLocationModal = (lat, lng) => {
            locAddress.textContent = '';
            locMapLink.href = `https://www.google.com/maps?q=${lat},${lng}`;
            locMapIframe.src = `https://www.google.com/maps?q=${lat},${lng}&z=16&output=embed`;
            modal.classList.remove('hidden');
            fetch(`<?php echo e(route('attendance.reverse-geocode')); ?>?lat=${lat}&lng=${lng}`)
                .then(r => r.json())
                .then(d => {
                    locAddress.textContent = d.address || 'Endereço não disponível';
                    if (d.mapUrl) locMapLink.href = d.mapUrl;
                    if (d.mapUrl) locMapIframe.src = `${d.mapUrl}&z=16&output=embed`;
                })
                .catch(() => {
                    locAddress.textContent = 'Falha ao obter endereço';
                });
        };
        window.closeLocationModal = () => {
            modal.classList.add('hidden');
        };

        // Variáveis globais para o modal de confirmação
        let confirmMapInstance = null;
        let confirmMarkerInstance = null;
        let confirmCircleInstance = null;
        let mapThemeObserver = null;

        // Função para abrir modal de confirmação
        function openConfirmModal(lat, lng, accuracy) {
            const modal = document.getElementById('confirm-location-modal');
            if (!modal) return;

            if (typeof Alpine !== 'undefined') {
                const alpineData = Alpine.$data(modal);
                if (alpineData) {
                    alpineData.lat = parseFloat(lat);
                    alpineData.lng = parseFloat(lng);
                    alpineData.accuracy = accuracy ? parseFloat(accuracy).toFixed(1) : null;
                    alpineData.address = null;
                    alpineData.loadingAddress = true;
                    alpineData.open = true;
                    modal.style.display = 'flex';

                    // Carregar endereço via reverse geocoding
                    fetch(`<?php echo e(route('attendance.reverse-geocode')); ?>?lat=${lat}&lng=${lng}`)
                        .then(r => r.json())
                        .then(d => {
                            alpineData.address = d.address || null;
                            alpineData.loadingAddress = false;
                        })
                        .catch(() => {
                            alpineData.address = null;
                            alpineData.loadingAddress = false;
                        });

                    // Inicializar mapa após um pequeno delay
                    setTimeout(() => {
                        initConfirmMap(parseFloat(lat), parseFloat(lng), accuracy ? parseFloat(accuracy) : null);
                    }, 100);
                }
            }
        }

        // Função para fechar modal de confirmação
        window.closeConfirmModal = function() {
            const modal = document.getElementById('confirm-location-modal');
            if (!modal) return;

            if (typeof Alpine !== 'undefined') {
                const alpineData = Alpine.$data(modal);
                if (alpineData) {
                    alpineData.open = false;
                }
            }

            if (confirmMapInstance) {
                if (confirmMarkerInstance) {
                    confirmMarkerInstance.setMap(null);
                    confirmMarkerInstance = null;
                }
                if (confirmCircleInstance) {
                    confirmCircleInstance.setMap(null);
                    confirmCircleInstance = null;
                }
                confirmMapInstance = null;
            }
            
            // Limpar observer do tema
            if (mapThemeObserver) {
                mapThemeObserver.disconnect();
                mapThemeObserver = null;
            }
            
            modal.style.display = 'none';
        };

        // Função para confirmar e enviar formulário
        window.confirmLocation = function() {
            const form = document.getElementById('punch-form');
            const lat = latEl.value;
            const lng = lngEl.value;
            const acc = accEl.value;
            
            // Validar novamente antes de enviar
            if (!lat || !lng || lat === '' || lng === '') {
                setStatus('Localização não disponível.', false);
                return;
            }
            
            if (form) {
                // Garantir que os valores estão nos campos hidden
                latEl.value = lat;
                lngEl.value = lng;
                if (acc) accEl.value = acc;
                
                // Fechar modal antes de enviar
                closeConfirmModal();
                
                // Enviar formulário
                form.submit();
            }
        };

        // Variável para rastrear se o script do Google Maps já foi adicionado
        let googleMapsScriptAdded = false;
        
        // Função para inicializar mapa de confirmação
        function initConfirmMap(lat, lng, accuracy) {
            const container = document.getElementById('confirm-map-container');
            if (!container) return;

            // Verificar se Google Maps está carregado
            if (typeof google === 'undefined' || typeof google.maps === 'undefined') {
                <?php if($googleMapsApiKey): ?>
                // Verificar se o script já foi adicionado
                if (!googleMapsScriptAdded) {
                    // Verificar se já existe um script do Google Maps
                    const existingScript = document.querySelector('script[src*="maps.googleapis.com"]');
                    if (existingScript) {
                        // Aguardar o script existente carregar
                        existingScript.addEventListener('load', () => {
                            setTimeout(() => createConfirmMap(lat, lng, accuracy), 100);
                        });
                        if (existingScript.complete || existingScript.readyState === 'complete') {
                            setTimeout(() => createConfirmMap(lat, lng, accuracy), 100);
                        }
                    } else {
                        // Carregar Google Maps API apenas se não existir (com Places para busca)
                        googleMapsScriptAdded = true;
                        const script = document.createElement('script');
                        script.src = `https://maps.googleapis.com/maps/api/js?key=<?php echo e($googleMapsApiKey); ?>&libraries=geometry,places&loading=async`;
                        script.async = true;
                        script.defer = true;
                        script.onload = () => {
                            setTimeout(() => {
                                createConfirmMap(lat, lng, accuracy);
                                initAddressSearch();
                            }, 100);
                        };
                        script.onerror = () => {
                            console.error('Erro ao carregar Google Maps API');
                            googleMapsScriptAdded = false;
                        };
                        document.head.appendChild(script);
                    }
                } else {
                    // Script já foi adicionado, aguardar carregamento
                    const checkGoogleMaps = setInterval(() => {
                        if (typeof google !== 'undefined' && typeof google.maps !== 'undefined') {
                            clearInterval(checkGoogleMaps);
                            createConfirmMap(lat, lng, accuracy);
                        }
                    }, 100);
                    // Timeout após 10 segundos
                    setTimeout(() => clearInterval(checkGoogleMaps), 10000);
                }
                <?php else: ?>
                console.warn('Google Maps API key not configured');
                <?php endif; ?>
            } else {
                createConfirmMap(lat, lng, accuracy);
                // Inicializar busca de endereço se Places já estiver disponível
                if (typeof google !== 'undefined' && typeof google.maps !== 'undefined' && typeof google.maps.places !== 'undefined') {
                    setTimeout(() => initAddressSearch(), 200);
                }
            }
        }

        // Função para criar mapa de confirmação
        function createConfirmMap(lat, lng, accuracy) {
            const container = document.getElementById('confirm-map-container');
            if (!container) return;

            // Limpar instância anterior se existir
            if (confirmMapInstance) {
                if (confirmMarkerInstance) {
                    confirmMarkerInstance.setMap(null);
                    confirmMarkerInstance = null;
                }
                if (confirmCircleInstance) {
                    confirmCircleInstance.setMap(null);
                    confirmCircleInstance = null;
                }
                confirmMapInstance = null;
            }

            const position = { lat: parseFloat(lat), lng: parseFloat(lng) };

            // Verificar se está em dark mode
            const isDarkMode = document.documentElement.classList.contains('dark');
            
            // Estilos do mapa para dark mode
            const darkMapStyles = [
                {elementType: 'geometry', stylers: [{color: '#242f3e'}]},
                {elementType: 'labels.text.stroke', stylers: [{color: '#242f3e'}]},
                {elementType: 'labels.text.fill', stylers: [{color: '#746855'}]},
                {
                    featureType: 'administrative.locality',
                    elementType: 'labels.text.fill',
                    stylers: [{color: '#d59563'}]
                },
                {
                    featureType: 'poi',
                    elementType: 'labels.text.fill',
                    stylers: [{color: '#d59563'}]
                },
                {
                    featureType: 'poi.park',
                    elementType: 'geometry',
                    stylers: [{color: '#263c3f'}]
                },
                {
                    featureType: 'poi.park',
                    elementType: 'labels.text.fill',
                    stylers: [{color: '#6b9a76'}]
                },
                {
                    featureType: 'road',
                    elementType: 'geometry',
                    stylers: [{color: '#38414e'}]
                },
                {
                    featureType: 'road',
                    elementType: 'geometry.stroke',
                    stylers: [{color: '#212a37'}]
                },
                {
                    featureType: 'road',
                    elementType: 'labels.text.fill',
                    stylers: [{color: '#9ca5b3'}]
                },
                {
                    featureType: 'road.highway',
                    elementType: 'geometry',
                    stylers: [{color: '#746855'}]
                },
                {
                    featureType: 'road.highway',
                    elementType: 'geometry.stroke',
                    stylers: [{color: '#1f2835'}]
                },
                {
                    featureType: 'road.highway',
                    elementType: 'labels.text.fill',
                    stylers: [{color: '#f3d19c'}]
                },
                {
                    featureType: 'transit',
                    elementType: 'geometry',
                    stylers: [{color: '#2f3948'}]
                },
                {
                    featureType: 'transit.station',
                    elementType: 'labels.text.fill',
                    stylers: [{color: '#d59563'}]
                },
                {
                    featureType: 'water',
                    elementType: 'geometry',
                    stylers: [{color: '#17263c'}]
                },
                {
                    featureType: 'water',
                    elementType: 'labels.text.fill',
                    stylers: [{color: '#515c6d'}]
                },
                {
                    featureType: 'water',
                    elementType: 'labels.text.stroke',
                    stylers: [{color: '#17263c'}]
                }
            ];

            confirmMapInstance = new google.maps.Map(container, {
                zoom: accuracy && accuracy > 0 ? Math.max(15, 17 - Math.log10(accuracy)) : 17,
                center: position,
                mapTypeId: 'roadmap',
                styles: isDarkMode ? darkMapStyles : [],
            });

            // Adicionar marcador (arrastável para ajuste manual)
            confirmMarkerInstance = new google.maps.Marker({
                position: position,
                map: confirmMapInstance,
                title: 'Sua Localização - Arraste para ajustar',
                animation: google.maps.Animation.DROP,
                draggable: true, // Permitir arrastar para ajuste manual
            });
            
            // Atualizar campos quando marcador for arrastado
            confirmMarkerInstance.addListener('dragend', function(event) {
                const newPosition = event.latLng;
                const newLat = newPosition.lat();
                const newLng = newPosition.lng();
                
                // Atualizar campos hidden
                if (latEl) latEl.value = newLat;
                if (lngEl) lngEl.value = newLng;
                
                // Atualizar dados do Alpine
                const modal = document.getElementById('confirm-location-modal');
                if (modal && typeof Alpine !== 'undefined') {
                    const alpineData = Alpine.$data(modal);
                    if (alpineData) {
                        alpineData.lat = newLat;
                        alpineData.lng = newLng;
                        alpineData.loadingAddress = true;
                        
                        // Buscar novo endereço
                        fetch(`<?php echo e(route('attendance.reverse-geocode')); ?>?lat=${newLat}&lng=${newLng}`)
                            .then(r => r.json())
                            .then(d => {
                                alpineData.address = d.address || null;
                                alpineData.loadingAddress = false;
                            })
                            .catch(() => {
                                alpineData.address = null;
                                alpineData.loadingAddress = false;
                            });
                    }
                }
                
                // Atualizar círculo de precisão se existir
                if (confirmCircleInstance && accuracy && accuracy > 0) {
                    confirmCircleInstance.setCenter(newPosition);
                }
                
                console.log('Localização ajustada manualmente:', { lat: newLat, lng: newLng });
            });

            // Adicionar círculo de precisão se disponível
            if (accuracy && accuracy > 0) {
                confirmCircleInstance = new google.maps.Circle({
                    strokeColor: '#3B82F6',
                    strokeOpacity: 0.4,
                    strokeWeight: 2,
                    fillColor: '#3B82F6',
                    fillOpacity: 0.15,
                    map: confirmMapInstance,
                    center: position,
                    radius: accuracy,
                });
            }

            // Info window
            const updateInfoWindow = () => {
                const currentPos = confirmMarkerInstance.getPosition();
                const currentDarkMode = document.documentElement.classList.contains('dark');
                const infoWindow = new google.maps.InfoWindow({
                    content: `
                        <div class="p-2 ${currentDarkMode ? 'dark' : ''}">
                            <p class="font-semibold text-gray-900 dark:text-gray-100">Sua Localização</p>
                            <p class="text-sm text-gray-600 dark:text-gray-400 mt-1">Lat: ${currentPos.lat().toFixed(5)}, Lng: ${currentPos.lng().toFixed(5)}</p>
                            ${accuracy ? `<p class="text-sm text-gray-600 dark:text-gray-400">Precisão GPS: ${accuracy.toFixed(1)} m</p>` : ''}
                            <p class="text-xs text-blue-600 dark:text-blue-400 mt-2"><i>Arraste o marcador para ajustar</i></p>
                        </div>
                    `
                });
                return infoWindow;
            };

            let currentInfoWindow = null;
            confirmMarkerInstance.addListener('click', () => {
                if (currentInfoWindow) {
                    currentInfoWindow.close();
                }
                currentInfoWindow = updateInfoWindow();
                currentInfoWindow.open(confirmMapInstance, confirmMarkerInstance);
            });
            
            // Atualizar info window quando arrastar
            confirmMarkerInstance.addListener('drag', () => {
                if (currentInfoWindow) {
                    currentInfoWindow.close();
                }
            });

            // Forçar redimensionamento
            setTimeout(() => {
                google.maps.event.trigger(confirmMapInstance, 'resize');
                confirmMapInstance.setCenter(position);
            }, 100);
            
            // Observar mudanças no tema para atualizar o mapa
            if (mapThemeObserver) {
                mapThemeObserver.disconnect();
            }
            
            mapThemeObserver = new MutationObserver(() => {
                const currentDarkMode = document.documentElement.classList.contains('dark');
                if (confirmMapInstance) {
                    confirmMapInstance.setOptions({
                        styles: currentDarkMode ? darkMapStyles : []
                    });
                }
            });
            
            mapThemeObserver.observe(document.documentElement, {
                attributes: true,
                attributeFilter: ['class']
            });
        }
        
        // Inicializar busca de endereço com Places Autocomplete
        let autocomplete = null;
        let geocoder = null;
        
        function initAddressSearch() {
            const addressInput = document.getElementById('address-search-input');
            const searchBtn = document.getElementById('search-address-btn');
            const useCenterBtn = document.getElementById('use-map-center-btn');
            
            if (!addressInput || typeof google === 'undefined' || typeof google.maps === 'undefined') {
                return;
            }
            
            // Inicializar Geocoder
            geocoder = new google.maps.Geocoder();
            
            // Inicializar Autocomplete
            if (typeof google.maps.places !== 'undefined') {
                autocomplete = new google.maps.places.Autocomplete(addressInput, {
                    componentRestrictions: { country: 'br' },
                    fields: ['geometry', 'formatted_address', 'name'],
                    types: ['address', 'establishment']
                });
                
                autocomplete.addListener('place_changed', function() {
                    const place = autocomplete.getPlace();
                    if (place.geometry) {
                        const newLat = place.geometry.location.lat();
                        const newLng = place.geometry.location.lng();
                        
                        // Atualizar mapa e marcador
                        if (confirmMapInstance && confirmMarkerInstance) {
                            const newPosition = { lat: newLat, lng: newLng };
                            confirmMarkerInstance.setPosition(newPosition);
                            confirmMapInstance.setCenter(newPosition);
                            confirmMapInstance.setZoom(18); // Zoom alto para precisão
                            
                            // Atualizar campos
                            if (latEl) latEl.value = newLat;
                            if (lngEl) lngEl.value = newLng;
                            
                            // Atualizar Alpine
                            const modal = document.getElementById('confirm-location-modal');
                            if (modal && typeof Alpine !== 'undefined') {
                                const alpineData = Alpine.$data(modal);
                                if (alpineData) {
                                    alpineData.lat = newLat;
                                    alpineData.lng = newLng;
                                    alpineData.address = place.formatted_address || place.name;
                                }
                            }
                            
                            // Atualizar círculo de precisão
                            if (confirmCircleInstance) {
                                confirmCircleInstance.setCenter(newPosition);
                                confirmCircleInstance.setRadius(10); // Precisão alta quando buscado
                            }
                            
                            console.log('Localização atualizada via busca:', { lat: newLat, lng: newLng });
                        }
                    }
                });
            }
            
            // Handler do botão de busca
            if (searchBtn) {
                searchBtn.addEventListener('click', function() {
                    const query = addressInput.value.trim();
                    if (!query) return;
                    
                    if (geocoder) {
                        geocoder.geocode({ 
                            address: query,
                            region: 'br'
                        }, function(results, status) {
                            if (status === 'OK' && results[0]) {
                                const location = results[0].geometry.location;
                                const newLat = location.lat();
                                const newLng = location.lng();
                                
                                // Atualizar mapa
                                if (confirmMapInstance && confirmMarkerInstance) {
                                    const newPosition = { lat: newLat, lng: newLng };
                                    confirmMarkerInstance.setPosition(newPosition);
                                    confirmMapInstance.setCenter(newPosition);
                                    confirmMapInstance.setZoom(18);
                                    
                                    // Atualizar campos
                                    if (latEl) latEl.value = newLat;
                                    if (lngEl) lngEl.value = newLng;
                                    
                                    // Atualizar Alpine
                                    const modal = document.getElementById('confirm-location-modal');
                                    if (modal && typeof Alpine !== 'undefined') {
                                        const alpineData = Alpine.$data(modal);
                                        if (alpineData) {
                                            alpineData.lat = newLat;
                                            alpineData.lng = newLng;
                                            alpineData.loadingAddress = true;
                                            
                                            // Buscar endereço formatado
                                            fetch(`<?php echo e(route('attendance.reverse-geocode')); ?>?lat=${newLat}&lng=${newLng}`)
                                                .then(r => r.json())
                                                .then(d => {
                                                    alpineData.address = d.address || results[0].formatted_address;
                                                    alpineData.loadingAddress = false;
                                                })
                                                .catch(() => {
                                                    alpineData.address = results[0].formatted_address;
                                                    alpineData.loadingAddress = false;
                                                });
                                        }
                                    }
                                    
                                    // Atualizar círculo de precisão
                                    if (confirmCircleInstance) {
                                        confirmCircleInstance.setCenter(newPosition);
                                        confirmCircleInstance.setRadius(10);
                                    }
                                }
                            } else {
                                alert('Endereço não encontrado. Tente novamente.');
                            }
                        });
                    }
                });
            }
            
            // Handler do botão "Usar Centro do Mapa"
            if (useCenterBtn && confirmMapInstance) {
                useCenterBtn.addEventListener('click', function() {
                    if (confirmMapInstance) {
                        const center = confirmMapInstance.getCenter();
                        const newLat = center.lat();
                        const newLng = center.lng();
                        
                        // Atualizar marcador
                        if (confirmMarkerInstance) {
                            confirmMarkerInstance.setPosition(center);
                        }
                        
                        // Atualizar campos
                        if (latEl) latEl.value = newLat;
                        if (lngEl) lngEl.value = newLng;
                        
                        // Atualizar Alpine
                        const modal = document.getElementById('confirm-location-modal');
                        if (modal && typeof Alpine !== 'undefined') {
                            const alpineData = Alpine.$data(modal);
                            if (alpineData) {
                                alpineData.lat = newLat;
                                alpineData.lng = newLng;
                                alpineData.loadingAddress = true;
                                
                                // Buscar endereço
                                fetch(`<?php echo e(route('attendance.reverse-geocode')); ?>?lat=${newLat}&lng=${newLng}`)
                                    .then(r => r.json())
                                    .then(d => {
                                        alpineData.address = d.address || null;
                                        alpineData.loadingAddress = false;
                                    })
                                    .catch(() => {
                                        alpineData.address = null;
                                        alpineData.loadingAddress = false;
                                    });
                            }
                        }
                        
                        // Atualizar círculo
                        if (confirmCircleInstance) {
                            confirmCircleInstance.setCenter(center);
                        }
                    }
                });
            }
        }
        
        function handlePunchButtonClick() {
            console.log('handlePunchButtonClick chamado');
            if (!button || !latEl || !lngEl) {
                console.error('Elementos não encontrados', { button: !!button, latEl: !!latEl, lngEl: !!lngEl });
                return false;
            }
            
            const lat = latEl.value;
            const lng = lngEl.value;
            
            console.log('Valores de localização:', { lat, lng, buttonDisabled: button.disabled });
            
            if (!lat || !lng || lat === '' || lng === '') {
                if (statusEl) setStatus('Localização não disponível. Aguarde a obtenção da localização ou ative o GPS.', false);
                return false;
            }
            
            if (button.disabled) {
                if (statusEl) setStatus('Aguarde a localização ser obtida.', false);
                return false;
            }
            
            // Abrir modal de confirmação
            const accuracy = accEl && accEl.value ? parseFloat(accEl.value) : null;
            console.log('Abrindo modal de confirmação com:', { lat, lng, accuracy });
            openConfirmModal(lat, lng, accuracy);
            return true;
        }
    </script>
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


<?php /**PATH C:\Users\Douglas\Documents\Projetos\stock-master\resources\views/attendance/index.blade.php ENDPATH**/ ?>