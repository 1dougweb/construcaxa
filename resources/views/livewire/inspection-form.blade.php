<div>
    @if (session()->has('message'))
        <div class="mb-4 bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded">
            {{ session('message') }}
        </div>
    @endif

    @if (session()->has('error'))
        <div class="mb-4 bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded">
            {{ session('error') }}
        </div>
    @endif

    <!-- Progress Steps - 3 Steps -->
    <div class="mb-6">
        <div class="flex items-center justify-between">
            @for($i = 1; $i <= 3; $i++)
                <div class="flex items-center flex-1">
                    <div class="flex flex-col items-center">
                        <div class="w-10 h-10 rounded-full flex items-center justify-center border-2 {{ $i <= $currentStep ? 'bg-indigo-600 border-indigo-600 text-white' : 'bg-gray-200 border-gray-300 text-gray-500' }}">
                            {{ $i }}
                        </div>
                        <span class="mt-2 text-xs text-gray-600 dark:text-gray-400">
                            @if($i === 1)
                                Selecionar Ambientes
                            @elseif($i === 2)
                                Avaliar Itens
                            @else
                                Localização & QR
                            @endif
                        </span>
                    </div>
                    @if($i < 3)
                        <div class="flex-1 h-1 mx-2 {{ $i < $currentStep ? 'bg-indigo-600' : 'bg-gray-200' }}"></div>
                    @endif
                </div>
            @endfor
        </div>
    </div>

    <form wire:submit.prevent="save">
        <!-- Step 1: Seleção de Ambientes -->
        @if($currentStep === 1)
            <div class="space-y-6">
                <h3 class="text-lg font-medium text-gray-900 dark:text-gray-100 mb-4">Dados Básicos e Seleção de Ambientes</h3>
                
                <!-- Cliente -->
                <div>
                    <x-label for="clientSearch" value="Cliente *" />
                    <div class="mt-1 relative">
                        @if($selectedClient)
                            <div class="flex items-center justify-between p-3 bg-gray-50 dark:bg-gray-700 border border-gray-300 dark:border-gray-600 rounded-md">
                                <div>
                                    <div class="font-medium text-gray-900 dark:text-gray-100">{{ $selectedClient->name ?? $selectedClient->trading_name }}</div>
                                    <div class="text-sm text-gray-500 dark:text-gray-400">{{ $selectedClient->email }}</div>
                                </div>
                                <button type="button" wire:click="$set('selectedClient', null); $set('client_id', null); $set('clientSearch', '')" class="text-red-600 dark:text-red-400 hover:text-red-900 dark:hover:text-red-300">
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                                    </svg>
                                </button>
                            </div>
                        @else
                            <x-input 
                                id="clientSearch" 
                                type="text" 
                                class="block w-full" 
                                wire:model.live.debounce.300ms="clientSearch" 
                                placeholder="Digite para buscar cliente..." 
                            />
                            <input type="hidden" wire:model="client_id" />
                            
                            @if(count($clientSearchResults) > 0)
                                <div class="absolute z-10 mt-1 w-full bg-white dark:bg-gray-800 shadow-lg max-h-60 rounded-md py-1 text-base ring-1 ring-black dark:ring-gray-700 ring-opacity-5 overflow-auto focus:outline-none">
                                    @foreach($clientSearchResults as $client)
                                        <button 
                                            type="button"
                                            wire:click="selectClient({{ $client->id }})"
                                            class="w-full text-left px-4 py-2 hover:bg-indigo-50 dark:hover:bg-indigo-900/30 hover:text-indigo-900 dark:hover:text-indigo-300 cursor-pointer"
                                        >
                                            <div class="font-medium text-gray-900 dark:text-gray-100">{{ $client->name ?? $client->trading_name }}</div>
                                            <div class="text-sm text-gray-500 dark:text-gray-400">{{ $client->email }}</div>
                                        </button>
                                    @endforeach
                                </div>
                            @endif
                        @endif
                    </div>
                    <x-input-error for="client_id" class="mt-2" />
                </div>

                <!-- Data da Vistoria -->
                <div>
                    <x-label for="inspection_date" value="Data da Vistoria *" />
                    <x-input id="inspection_date" type="date" class="mt-1 block w-full" wire:model="inspection_date" />
                    <x-input-error for="inspection_date" class="mt-2" />
                </div>

                <!-- Descrição -->
                <div>
                    <x-label for="description" value="Descrição" />
                    <x-textarea id="description" class="mt-1 block w-full" wire:model="description" rows="3" />
                    <x-input-error for="description" class="mt-2" />
                </div>

                <!-- Seleção de Ambientes -->
                <div>
                    <x-label value="Selecione os Ambientes *" />
                    <div class="mt-2 grid grid-cols-2 md:grid-cols-3 gap-3">
                        @foreach($templates as $template)
                            <label class="flex items-center p-3 border-2 rounded-lg cursor-pointer hover:bg-gray-50 dark:hover:bg-gray-700 transition-colors {{ in_array($template->id, $selectedEnvironments ?? []) ? 'border-indigo-500 bg-indigo-50 dark:bg-indigo-900/20' : 'border-gray-300 dark:border-gray-600' }}">
                                <input 
                                    type="checkbox" 
                                    wire:click="toggleEnvironment({{ $template->id }})"
                                    {{ in_array($template->id, $selectedEnvironments ?? []) ? 'checked' : '' }}
                                    class="form-checkbox h-5 w-5 text-indigo-600"
                                >
                                <span class="ml-2 text-sm font-medium text-gray-900 dark:text-gray-100">{{ $template->name }}</span>
                            </label>
                        @endforeach
                    </div>
                    @error('selectedEnvironments')
                        <p class="mt-2 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                    @enderror
                    @if(empty($selectedEnvironments) || count($selectedEnvironments) === 0)
                        <p class="mt-2 text-sm text-gray-500 dark:text-gray-400">Selecione pelo menos um ambiente para continuar.</p>
                    @endif
                </div>
            </div>
        @endif

        <!-- Step 2: Avaliar Itens (Repeaters com Sub-repeaters) -->
        @if($currentStep === 2)
            <div class="space-y-6">
                <h3 class="text-lg font-medium text-gray-900 dark:text-gray-100 mb-4">Avaliar Itens dos Ambientes</h3>
                
                @foreach($environments as $envIndex => $environment)
                    <div class="border border-gray-300 dark:border-gray-600 rounded-lg p-4 mb-4">
                        <h4 class="text-md font-semibold text-gray-900 dark:text-gray-100 mb-4">{{ $environment['name'] }}</h4>
                        
                        @if(isset($environmentItems[$envIndex]))
                            @foreach($environmentItems[$envIndex] as $itemIndex => $item)
                                @php
                                    $itemKey = "{$envIndex}_{$itemIndex}";
                                @endphp
                                
                                <div class="mb-6 p-4 bg-gray-50 dark:bg-gray-700 rounded-lg">
                                    <div class="mb-4">
                                        <x-label :for="'title_' . $itemKey" value="Título do Item *" />
                                        <x-input 
                                            :id="'title_' . $itemKey" 
                                            type="text" 
                                            class="mt-1 block w-full" 
                                            wire:model="environmentItems.{{ $envIndex }}.{{ $itemIndex }}.title" 
                                            placeholder="Ex: Paredes, Portas, Janelas..."
                                        />
                                    </div>

                                    <!-- Sub-repeaters -->
                                    <div class="space-y-3 mb-4">
                                        <div class="flex justify-between items-center">
                                            <x-label value="Sub-itens (Avaliações Detalhadas)" />
                                            <button 
                                                type="button" 
                                                wire:click="addSubItem({{ $envIndex }}, {{ $itemIndex }})"
                                                class="text-sm text-indigo-600 dark:text-indigo-400 hover:text-indigo-800 dark:hover:text-indigo-300"
                                            >
                                                + Adicionar Sub-item
                                            </button>
                                        </div>

                                        @if(isset($subItems[$itemKey]) && count($subItems[$itemKey]) > 0)
                                            @foreach($subItems[$itemKey] as $subIndex => $subItem)
                                                <div class="p-3 border border-gray-200 dark:border-gray-600 rounded-lg bg-white dark:bg-gray-800">
                                                    <div class="flex justify-between items-start mb-2">
                                                        <span class="text-sm font-medium text-gray-700 dark:text-gray-300">Sub-item {{ $subIndex + 1 }}</span>
                                                        <button 
                                                            type="button" 
                                                            wire:click="removeSubItem({{ $envIndex }}, {{ $itemIndex }}, {{ $subIndex }})"
                                                            class="text-red-600 dark:text-red-400 hover:text-red-900 dark:hover:text-red-300"
                                                        >
                                                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                                                            </svg>
                                                        </button>
                                                    </div>

                                                    <div class="space-y-2">
                                                        <x-input 
                                                            type="text" 
                                                            class="block w-full text-sm" 
                                                            wire:model="subItems.{{ $itemKey }}.{{ $subIndex }}.title" 
                                                            placeholder="Título do sub-item *"
                                                        />
                                                        <x-input-error :for="'subItems.' . $itemKey . '.' . $subIndex . '.title'" class="mt-1" />

                                                        <x-textarea 
                                                            class="block w-full text-sm" 
                                                            wire:model="subItems.{{ $itemKey }}.{{ $subIndex }}.description" 
                                                            placeholder="Descrição"
                                                            rows="2"
                                                        />

                                                        <x-textarea 
                                                            class="block w-full text-sm" 
                                                            wire:model="subItems.{{ $itemKey }}.{{ $subIndex }}.observations" 
                                                            placeholder="Observações"
                                                            rows="2"
                                                        />

                                                        <div>
                                                            <x-label value="Qualidade *" class="text-sm" />
                                                            <div class="mt-1 flex flex-wrap gap-2">
                                                                <label class="inline-flex items-center">
                                                                    <input 
                                                                        type="radio" 
                                                                        wire:model="subItems.{{ $itemKey }}.{{ $subIndex }}.quality_rating" 
                                                                        value="poor"
                                                                        class="form-radio h-4 w-4 text-red-600"
                                                                    >
                                                                    <span class="ml-1 text-sm text-gray-700 dark:text-gray-300">Ruim</span>
                                                                </label>
                                                                <label class="inline-flex items-center">
                                                                    <input 
                                                                        type="radio" 
                                                                        wire:model="subItems.{{ $itemKey }}.{{ $subIndex }}.quality_rating" 
                                                                        value="good"
                                                                        class="form-radio h-4 w-4 text-blue-600"
                                                                    >
                                                                    <span class="ml-1 text-sm text-gray-700 dark:text-gray-300">Bom</span>
                                                                </label>
                                                                <label class="inline-flex items-center">
                                                                    <input 
                                                                        type="radio" 
                                                                        wire:model="subItems.{{ $itemKey }}.{{ $subIndex }}.quality_rating" 
                                                                        value="very_good"
                                                                        class="form-radio h-4 w-4 text-green-600"
                                                                    >
                                                                    <span class="ml-1 text-sm text-gray-700 dark:text-gray-300">Muito Bom</span>
                                                                </label>
                                                                <label class="inline-flex items-center">
                                                                    <input 
                                                                        type="radio" 
                                                                        wire:model="subItems.{{ $itemKey }}.{{ $subIndex }}.quality_rating" 
                                                                        value="excellent"
                                                                        class="form-radio h-4 w-4 text-indigo-600"
                                                                    >
                                                                    <span class="ml-1 text-sm text-gray-700 dark:text-gray-300">Excelente</span>
                                                                </label>
                                                            </div>
                                                            <x-input-error :for="'subItems.' . $itemKey . '.' . $subIndex . '.quality_rating'" class="mt-1" />
                                                        </div>
                                                    </div>
                                                </div>
                                            @endforeach
                                        @endif
                                    </div>

                                    <!-- Upload de Imagens -->
                                    <div>
                                        <x-label :for="'photos_' . $itemKey" value="Fotos" />
                                        <input 
                                            type="file" 
                                            :id="'photos_' . $itemKey"
                                            wire:model="tempPhotos.{{ $itemKey }}"
                                            multiple
                                            accept="image/*"
                                            capture="environment"
                                            class="mt-1 block w-full text-sm text-gray-500 dark:text-gray-400 file:mr-4 file:py-2 file:px-4 file:rounded-md file:border-0 file:text-sm file:font-semibold file:bg-indigo-50 file:text-indigo-700 hover:file:bg-indigo-100"
                                        />
                                        <p class="mt-1 text-xs text-gray-500 dark:text-gray-400">
                                            <svg class="inline w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 9a2 2 0 012-2h.93a2 2 0 001.664-.89l.812-1.22A2 2 0 0110.07 4h3.86a2 2 0 011.664.89l.812 1.22A2 2 0 0018.07 7H19a2 2 0 012 2v9a2 2 0 01-2 2H5a2 2 0 01-2-2V9z" />
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 13a3 3 0 11-6 0 3 3 0 016 0z" />
                                            </svg>
                                            Em dispositivos móveis, a câmera será aberta automaticamente
                                        </p>
                                        <x-input-error :for="'tempPhotos.' . $itemKey" class="mt-2" />
                                        
                                        <!-- Preview de fotos temporárias -->
                                        @if(isset($tempPhotos[$itemKey]) && is_array($tempPhotos[$itemKey]) && count($tempPhotos[$itemKey]) > 0)
                                            <div class="mt-2 grid grid-cols-4 gap-2">
                                                @foreach($tempPhotos[$itemKey] as $photoIndex => $photo)
                                                    @if($photo)
                                                        <div class="relative">
                                                            <img src="{{ $photo->temporaryUrl() }}" alt="Preview" class="w-full h-24 object-cover rounded">
                                                            <button 
                                                                type="button"
                                                                wire:click="removePhoto('{{ $itemKey }}', {{ $photoIndex }}, true)"
                                                                class="absolute top-0 right-0 bg-red-500 text-white rounded-full p-1"
                                                            >
                                                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                                                                </svg>
                                                            </button>
                                                        </div>
                                                    @endif
                                                @endforeach
                                            </div>
                                        @endif

                                        <!-- Preview de fotos salvas -->
                                        @if(isset($itemPhotos[$itemKey]) && count($itemPhotos[$itemKey]) > 0)
                                            <div class="mt-2 grid grid-cols-4 gap-2">
                                                @foreach($itemPhotos[$itemKey] as $photoIndex => $photo)
                                                    <div class="relative">
                                                        <img src="{{ $photo['url'] }}" alt="Foto" class="w-full h-24 object-cover rounded">
                                                        <button 
                                                            type="button"
                                                            wire:click="removePhoto('{{ $itemKey }}', {{ $photoIndex }}, false)"
                                                            class="absolute top-0 right-0 bg-red-500 text-white rounded-full p-1"
                                                        >
                                                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                                                            </svg>
                                                        </button>
                                                    </div>
                                                @endforeach
                                            </div>
                                        @endif
                                    </div>
                                </div>
                            @endforeach
                        @endif
                    </div>
                @endforeach
            </div>
        @endif

        <!-- Step 3: Localização e QR Code -->
        @if($currentStep === 3)
            <div class="space-y-6">
                <h3 class="text-lg font-medium text-gray-900 dark:text-gray-100 mb-4">Localização e QR Code</h3>
                
                <!-- Endereço -->
                <div>
                    <x-label for="address" value="Endereço *" />
                    <x-textarea id="address" class="mt-1 block w-full" wire:model="address" rows="2" />
                    <x-input-error for="address" class="mt-2" />
                </div>

                <!-- Google Maps -->
                <div>
                    <x-label value="Localização no Mapa" />
                    <div id="map" class="mt-2 w-full h-96 rounded-lg border border-gray-300 dark:border-gray-600"></div>
                    <input type="hidden" id="latitude" wire:model="latitude">
                    <input type="hidden" id="longitude" wire:model="longitude">
                    <p class="mt-2 text-sm text-gray-500 dark:text-gray-400">Clique no mapa para definir a localização</p>
                </div>

                <!-- QR Code -->
                @if($inspection && $inspection->qr_code_path)
                    <div>
                        <x-label value="QR Code para Visualização Pública" />
                        <div class="mt-2 p-4 bg-white dark:bg-gray-800 border border-gray-300 dark:border-gray-600 rounded-lg inline-block">
                            <img src="{{ asset('storage/' . $inspection->qr_code_path) }}" alt="QR Code" class="w-48 h-48">
                        </div>
                        <p class="mt-2 text-sm text-gray-500 dark:text-gray-400">Compartilhe este QR code para acesso público à vistoria</p>
                    </div>
                @endif

                <!-- Notas -->
                <div>
                    <x-label for="notes" value="Notas Adicionais" />
                    <x-textarea id="notes" class="mt-1 block w-full" wire:model="notes" rows="3" />
                </div>
            </div>
        @endif

        <!-- Navegação -->
        <div class="mt-6 flex justify-between">
            <div class="flex gap-2">
                @if($currentStep > 1)
                    <button 
                        type="button" 
                        wire:click="previousStep"
                        class="px-4 py-2 bg-gray-300 dark:bg-gray-600 text-gray-700 dark:text-gray-200 rounded-md hover:bg-gray-400 dark:hover:bg-gray-500"
                    >
                        Anterior
                    </button>
                @endif
                
                @if($currentStep > 1)
                    <button 
                        type="button" 
                        wire:click="saveDraft"
                        class="px-4 py-2 bg-yellow-500 text-white rounded-md hover:bg-yellow-600"
                    >
                        Salvar Rascunho
                    </button>
                @endif
            </div>

            <div class="flex gap-2">
                @if($currentStep < 3)
                    <button 
                        type="button" 
                        wire:click="nextStep"
                        class="px-4 py-2 bg-indigo-600 text-white rounded-md hover:bg-indigo-700"
                    >
                        Próximo
                    </button>
                @else
                    <button 
                        type="submit" 
                        class="px-4 py-2 bg-green-600 text-white rounded-md hover:bg-green-700"
                    >
                        Salvar Vistoria
                    </button>
                @endif
            </div>
        </div>
    </form>

    @push('scripts')
    <script>
        let map;
        let marker;
        let geocoder;
        let mapInitialized = false;
        let googleMapsLoaded = false;

        // Carregar Google Maps apenas quando necessário
        function loadGoogleMaps() {
            if (googleMapsLoaded) {
                initMap();
                return;
            }

            const script = document.createElement('script');
            script.src = 'https://maps.googleapis.com/maps/api/js?key={{ config('services.google.maps_key') }}&libraries=places&callback=initMap';
            script.async = true;
            script.defer = true;
            script.onload = function() {
                googleMapsLoaded = true;
                // initMap será chamado pelo callback
            };
            document.head.appendChild(script);
        }

        function initMap() {
            // Verificar se o elemento existe
            const mapElement = document.getElementById('map');
            if (!mapElement) {
                console.warn('Map element not found, retrying...');
                setTimeout(initMap, 100);
                return;
            }

            if (mapInitialized) {
                return; // Já foi inicializado
            }

            geocoder = new google.maps.Geocoder();
            
            const defaultLocation = { lat: -23.5505, lng: -46.6333 }; // São Paulo
            
            // Verificar se já tem coordenadas
            const latitude = @js($latitude);
            const longitude = @js($longitude);
            
            if (latitude && longitude) {
                const location = { lat: parseFloat(latitude), lng: parseFloat(longitude) };
                map = new google.maps.Map(mapElement, {
                    zoom: 15,
                    center: location,
                });
                
                marker = new google.maps.Marker({
                    position: location,
                    map: map,
                    draggable: true,
                });
            } else {
                map = new google.maps.Map(mapElement, {
                    zoom: 15,
                    center: defaultLocation,
                });
            }

            // Clique no mapa para definir localização
            map.addListener('click', (e) => {
                const location = { lat: e.latLng.lat(), lng: e.latLng.lng() };
                
                if (marker) {
                    marker.setPosition(location);
                } else {
                    marker = new google.maps.Marker({
                        position: location,
                        map: map,
                        draggable: true,
                    });
                }

                // Atualizar coordenadas no Livewire
                @this.set('latitude', location.lat);
                @this.set('longitude', location.lng);

                // Geocodificar reverso para obter endereço
                geocoder.geocode({ location: location }, (results, status) => {
                    if (status === 'OK' && results[0]) {
                        @this.set('address', results[0].formatted_address);
                    }
                });
            });

            // Autocomplete para endereço
            const addressInput = document.getElementById('address');
            if (addressInput) {
                const autocomplete = new google.maps.places.Autocomplete(addressInput);
                autocomplete.addListener('place_changed', () => {
                    const place = autocomplete.getPlace();
                    if (place.geometry) {
                        const location = {
                            lat: place.geometry.location.lat(),
                            lng: place.geometry.location.lng(),
                        };
                        
                        map.setCenter(location);
                        map.setZoom(15);
                        
                        if (marker) {
                            marker.setPosition(location);
                        } else {
                            marker = new google.maps.Marker({
                                position: location,
                                map: map,
                                draggable: true,
                            });
                        }

                        @this.set('latitude', location.lat);
                        @this.set('longitude', location.lng);
                    }
                });
            }

            mapInitialized = true;
        }

        // Escutar mudanças do Livewire para o Step 3
        document.addEventListener('livewire:init', () => {
            Livewire.on('stepChanged', (step) => {
                if (step === 3) {
                    // Aguardar o DOM atualizar
                    setTimeout(() => {
                        if (!mapInitialized) {
                            loadGoogleMaps();
                        }
                    }, 100);
                }
            });
        });

        // Verificar se já estamos no Step 3 ao carregar
        @if($currentStep === 3)
            document.addEventListener('DOMContentLoaded', () => {
                setTimeout(() => {
                    loadGoogleMaps();
                }, 300);
            });
        @endif
    </script>
    @endpush
</div>
