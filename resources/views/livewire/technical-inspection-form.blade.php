<div>
    <div class="max-w-7xl mx-auto py-6 sm:px-6 lg:px-8">
        <!-- Indicador de Progresso -->
        <div class="mb-6">
            <div class="flex items-center justify-between mb-2">
                <h2 class="text-2xl font-bold text-gray-900 dark:text-gray-100">
                    {{ $technicalInspection ? 'Editar Vistoria Técnica' : 'Nova Vistoria Técnica' }}
                </h2>
                <span class="text-sm text-gray-500 dark:text-gray-400">
                    Etapa {{ $currentStep }} de {{ $totalSteps }}
                </span>
            </div>
            <div class="w-full bg-gray-200 dark:bg-gray-700 rounded-full h-2">
                <div class="bg-indigo-600 h-2 rounded-full transition-all duration-300" 
                     style="width: {{ ($currentStep / $totalSteps) * 100 }}%"></div>
            </div>
        </div>

        <!-- Etapa 1: Seleção de Ambientes -->
        @if($currentStep === 1)
            <div class="bg-white dark:bg-gray-800 shadow-xl rounded-lg p-6">
                <h3 class="text-lg font-semibold text-gray-900 dark:text-gray-100 mb-4">
                    Selecione os Ambientes
                </h3>

                <!-- Ambientes Pré-definidos -->
                <div class="mb-6">
                    <x-label value="Ambientes Disponíveis" />
                    <div class="mt-2 grid grid-cols-2 md:grid-cols-3 lg:grid-cols-5 gap-3">
                        @php
                            $environmentIcons = [
                                'Sala' => 'fi fi-rr-home',
                                'Cozinha' => 'fi fi-rr-restaurant',
                                'Quarto' => 'fi fi-rr-bed',
                                'Banheiro' => 'fi fi-rr-bath',
                                'Área Externa' => 'fi fi-rr-kaaba',
                                'Fachada' => 'fi fi-rr-building',
                                'Infraestrutura' => 'fi fi-rr-settings',
                                'Instalações' => 'fi fi-rr-plug',
                                'Garagem' => 'fi fi-rr-car',
                                'Varanda' => 'fi fi-rr-fence',
                            ];
                        @endphp
                        @foreach($availableEnvironments as $env)
                            <label
                                class="p-4 border-2 rounded-lg text-sm font-medium transition-all duration-200 flex flex-col items-center justify-center gap-2 cursor-pointer
                                    {{ in_array($env, $selectedEnvironments) 
                                        ? 'border-indigo-500 bg-indigo-50 dark:bg-indigo-900/30 text-indigo-700 dark:text-indigo-300 shadow-md' 
                                        : 'border-gray-300 dark:border-gray-600 hover:border-indigo-300 dark:hover:border-indigo-600 hover:shadow-md text-gray-700 dark:text-gray-300' }}"
                            >
                                <input
                                    type="checkbox"
                                    wire:model.live="selectedEnvironments"
                                    value="{{ $env }}"
                                    class="hidden"
                                />
                                <i class="{{ $environmentIcons[$env] ?? 'fi fi-rr-home' }} text-2xl"></i>
                                <span>{{ $env }}</span>
                            </label>
                        @endforeach
                    </div>
                </div>

                <!-- Ambientes Selecionados -->
                @if(count($selectedEnvironments) > 0)
                    <div class="mb-6">
                        <x-label value="Ambientes Selecionados" />
                        <div class="mt-2 flex flex-wrap gap-2">
                            @foreach($selectedEnvironments as $index => $env)
                                <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium bg-indigo-100 dark:bg-indigo-900/50 text-indigo-800 dark:text-indigo-300">
                                    {{ $env }}
                                    <button
                                        type="button"
                                        wire:click="removeSelectedEnvironment({{ $index }})"
                                        class="ml-2 text-indigo-600 dark:text-indigo-400 hover:text-indigo-800 dark:hover:text-indigo-200"
                                    >
                                        ×
                                    </button>
                                </span>
                            @endforeach
                        </div>
                    </div>
                @endif

                <!-- Ambiente Customizado -->
                <div class="mb-6">
                    <x-label for="customEnvironmentName" value="Adicionar Ambiente Customizado" />
                    <div class="mt-1 flex gap-2">
                        <x-input
                            id="customEnvironmentName"
                            type="text"
                            class="flex-1"
                            wire:model="customEnvironmentName"
                            placeholder="Digite o nome do ambiente"
                            wire:keydown.enter.prevent="addCustomEnvironment"
                        />
                        <x-button type="button" wire:click="addCustomEnvironment">
                            Adicionar
                        </x-button>
                    </div>
                </div>

                <x-input-error for="selectedEnvironments" class="mt-2" />
            </div>
        @endif

        <!-- Etapa 2: Informações Gerais -->
        @if($currentStep === 2)
            <div class="bg-white dark:bg-gray-800 shadow-xl rounded-lg p-6">
                <h3 class="text-lg font-semibold text-gray-900 dark:text-gray-100 mb-4">
                    Informações Gerais da Vistoria
                </h3>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <!-- Número -->
                    <div>
                        <x-label for="number" value="Número da Vistoria" />
                        <x-input
                            id="number"
                            type="text"
                            class="mt-1 block w-full bg-gray-50 dark:bg-gray-700"
                            wire:model="number"
                            readonly
                        />
                    </div>

                    <!-- Data da Vistoria -->
                    <div>
                        <x-label for="inspection_date" value="Data da Vistoria *" />
                        <x-input
                            id="inspection_date"
                            type="date"
                            class="mt-1 block w-full"
                            wire:model="inspection_date"
                        />
                        <x-input-error for="inspection_date" class="mt-2" />
                    </div>

                    <!-- Endereço -->
                    <div class="md:col-span-2">
                        <x-label for="address" value="Endereço da Obra / Unidade *" />
                        <x-textarea
                            id="address"
                            class="mt-1 block w-full"
                            wire:model="address"
                            rows="2"
                        />
                        <x-input-error for="address" class="mt-2" />
                    </div>

                    <!-- Metragem -->
                    <div>
                        <x-label for="unit_area" value="Metragem da Unidade (m²)" />
                        <x-input
                            id="unit_area"
                            type="number"
                            step="0.01"
                            class="mt-1 block w-full"
                            wire:model="unit_area"
                        />
                        <x-input-error for="unit_area" class="mt-2" />
                    </div>

                    <!-- Situação da Mobília -->
                    <div>
                        <x-label for="furniture_status" value="Situação da Mobília" />
                        <x-input
                            id="furniture_status"
                            type="text"
                            class="mt-1 block w-full"
                            wire:model="furniture_status"
                            placeholder="Ex: Mobiliada, Vazia, Parcialmente mobiliada"
                        />
                    </div>

                    <!-- Responsável Técnico -->
                    <div>
                        <x-label for="responsible_name" value="Responsável Técnico *" />
                        <x-input
                            id="responsible_name"
                            type="text"
                            class="mt-1 block w-full"
                            wire:model="responsible_name"
                        />
                        <x-input-error for="responsible_name" class="mt-2" />
                    </div>

                    <!-- Intervenientes -->
                    <div>
                        <x-label for="involved_parties" value="Intervenientes ou Partes Envolvidas" />
                        <x-textarea
                            id="involved_parties"
                            class="mt-1 block w-full"
                            wire:model="involved_parties"
                            rows="2"
                            placeholder="Liste as partes envolvidas na vistoria"
                        />
                    </div>

                    <!-- Cliente -->
                    <div>
                        <x-label for="client_id" value="Cliente (Opcional)" />
                        <x-select id="client_id" class="mt-1 block w-full" wire:model="client_id">
                            <option value="">Selecione um cliente</option>
                            @foreach($clients as $client)
                                <option value="{{ $client->id }}">{{ $client->name }}</option>
                            @endforeach
                        </x-select>
                        <x-input-error for="client_id" class="mt-2" />
                    </div>

                    <!-- Projeto -->
                    <div>
                        <x-label for="project_id" value="Projeto / Obra (Opcional)" />
                        <x-select id="project_id" class="mt-1 block w-full" wire:model="project_id">
                            <option value="">Selecione um projeto</option>
                            @foreach($projects as $project)
                                <option value="{{ $project->id }}">{{ $project->name }}</option>
                            @endforeach
                        </x-select>
                        <x-input-error for="project_id" class="mt-2" />
                    </div>

                    <!-- Mapa do Google Maps -->
                    <div class="md:col-span-2">
                        <x-label value="Localização no Mapa (Opcional)" />
                        <div class="mt-2">
                            <!-- Busca de Endereço -->
                            <div class="mb-3">
                                <x-input
                                    id="address_search"
                                    type="text"
                                    class="block w-full"
                                    placeholder="Digite o endereço para buscar no mapa..."
                                    onkeydown="if(event.key === 'Enter') { event.preventDefault(); window.searchAddressOnMap && window.searchAddressOnMap(); }"
                                />
                                <button
                                    type="button"
                                    onclick="window.searchAddressOnMap && window.searchAddressOnMap();"
                                    class="mt-2 px-4 py-2 bg-indigo-600 text-white rounded-md hover:bg-indigo-700 text-sm"
                                >
                                    Buscar no Mapa
                                </button>
                            </div>

                            <!-- Mapa -->
                            <div 
                                id="map-container" 
                                class="w-full h-96 border border-gray-300 dark:border-gray-600 rounded-lg overflow-hidden"
                                wire:ignore
                            ></div>

                            <!-- Coordenadas -->
                            <div class="mt-3 grid grid-cols-2 gap-4">
                                <div>
                                    <x-label for="coordinates.lat" value="Latitude" />
                                    <x-input
                                        id="coordinates.lat"
                                        type="text"
                                        class="mt-1 block w-full"
                                        wire:model="coordinates.lat"
                                        placeholder="Ex: -23.5505"
                                        readonly
                                    />
                                </div>
                                <div>
                                    <x-label for="coordinates.lng" value="Longitude" />
                                    <x-input
                                        id="coordinates.lng"
                                        type="text"
                                        class="mt-1 block w-full"
                                        wire:model="coordinates.lng"
                                        placeholder="Ex: -46.6333"
                                        readonly
                                    />
                                </div>
                            </div>

                            <!-- Botão para capturar screenshot do mapa -->
                            <button
                                type="button"
                                wire:click="captureMapScreenshot"
                                class="mt-3 px-4 py-2 bg-green-600 text-white rounded-md hover:bg-green-700 text-sm"
                            >
                                Capturar Mapa para PDF
                            </button>

                            <!-- Preview do mapa capturado -->
                            @if($map_image)
                                <div class="mt-3">
                                    <p class="text-sm text-gray-600 dark:text-gray-400 mb-2">Mapa capturado:</p>
                                    <img src="{{ asset('storage/' . $map_image) }}" alt="Mapa" class="max-w-full h-auto rounded-lg border border-gray-300 dark:border-gray-600">
                                </div>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
        @endif

        <!-- Etapa 3: Repeater de Ambientes -->
        @if($currentStep === 3)
            <div class="space-y-6">
                @foreach($environments as $envIndex => $environment)
                    <div class="bg-white dark:bg-gray-800 shadow-xl rounded-lg p-6" wire:key="env-{{ $envIndex }}">
                        <div class="flex items-center justify-between mb-4">
                            <h3 class="text-lg font-semibold text-gray-900 dark:text-gray-100">
                                Ambiente: {{ $environment['name'] ?? 'Sem nome' }}
                            </h3>
                            @if(count($environments) > 1)
                                <button
                                    type="button"
                                    wire:click="removeEnvironment({{ $envIndex }})"
                                    class="text-red-600 dark:text-red-400 hover:text-red-800 dark:hover:text-red-300"
                                >
                                    Remover
                                </button>
                            @endif
                        </div>

                        <div class="space-y-4">
                            <!-- Nome do Ambiente -->
                            <div>
                                <x-label :for="'environments.' . $envIndex . '.name'" value="Nome do Ambiente *" />
                                <x-input
                                    :id="'environments.' . $envIndex . '.name'"
                                    type="text"
                                    class="mt-1 block w-full"
                                    wire:model="environments.{{ $envIndex }}.name"
                                />
                                <x-input-error :for="'environments.' . $envIndex . '.name'" class="mt-2" />
                            </div>

                            <!-- Observações Técnicas -->
                            <div>
                                <x-label :for="'environments.' . $envIndex . '.technical_notes'" value="Observações Técnicas" />
                                <x-textarea
                                    :id="'environments.' . $envIndex . '.technical_notes'"
                                    class="mt-1 block w-full"
                                    wire:model="environments.{{ $envIndex }}.technical_notes"
                                    rows="3"
                                />
                            </div>

                            <!-- Fotos do Ambiente -->
                            <div>
                                <x-label value="Fotos do Ambiente" />
                                @include('livewire.partials.media-capture', [
                                    'context' => ['type' => 'environment', 'index' => $envIndex],
                                    'photos' => $environment['photos'] ?? []
                                ])
                            </div>

                            <!-- Medidas -->
                            <div>
                                <x-label :for="'environments.' . $envIndex . '.measurements'" value="Medidas Relevantes" />
                                <x-input
                                    :id="'environments.' . $envIndex . '.measurements'"
                                    type="text"
                                    class="mt-1 block w-full"
                                    wire:model="environments.{{ $envIndex }}.measurements"
                                    placeholder="Ex: 3.5m x 4.2m"
                                />
                            </div>

                            <!-- Link Google Drive -->
                            <div>
                                <x-label :for="'environments.' . $envIndex . '.google_drive_link'" value="Link do Google Drive" />
                                <x-input
                                    :id="'environments.' . $envIndex . '.google_drive_link'"
                                    type="url"
                                    class="mt-1 block w-full"
                                    wire:model="environments.{{ $envIndex }}.google_drive_link"
                                    placeholder="https://drive.google.com/..."
                                    wire:blur="generateQRCode({{ $envIndex }})"
                                />
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
        @endif

        <!-- Etapa 4: Repeater Interno de Elementos -->
        @if($currentStep === 4)
            <div class="space-y-6">
                @foreach($environments as $envIndex => $environment)
                    <div class="bg-white dark:bg-gray-800 shadow-xl rounded-lg p-6" wire:key="env-elements-{{ $envIndex }}">
                        <h3 class="text-lg font-semibold text-gray-900 dark:text-gray-100 mb-4">
                            Elementos - {{ $environment['name'] }}
                        </h3>

                        @foreach($environment['elements'] ?? [] as $elemIndex => $element)
                            <div class="border border-gray-200 dark:border-gray-700 rounded-lg p-4 mb-4" wire:key="elem-{{ $envIndex }}-{{ $elemIndex }}">
                                <div class="flex items-center justify-between mb-4">
                                    <h4 class="font-medium text-gray-900 dark:text-gray-100">
                                        Elemento {{ $elemIndex + 1 }}
                                    </h4>
                                    <button
                                        type="button"
                                        wire:click="removeElement({{ $envIndex }}, {{ $elemIndex }})"
                                        class="text-red-600 dark:text-red-400 hover:text-red-800 dark:hover:text-red-300 text-sm"
                                    >
                                        Remover
                                    </button>
                                </div>

                                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                    <!-- Nome do Elemento -->
                                    <div class="md:col-span-2">
                                        <x-label :for="'elements.' . $envIndex . '.' . $elemIndex . '.name'" value="Nome do Elemento *" />
                                        <x-input
                                            :id="'elements.' . $envIndex . '.' . $elemIndex . '.name'"
                                            type="text"
                                            class="mt-1 block w-full"
                                            wire:model="environments.{{ $envIndex }}.elements.{{ $elemIndex }}.name"
                                            placeholder="Ex: Piso, Parede Leste, Tomada 220v"
                                        />
                                        <x-input-error :for="'environments.' . $envIndex . '.elements.' . $elemIndex . '.name'" class="mt-2" />
                                    </div>

                                    <!-- Observações Técnicas -->
                                    <div class="md:col-span-2">
                                        <x-label :for="'elements.' . $envIndex . '.' . $elemIndex . '.technical_notes'" value="Observações Técnicas" />
                                        <x-textarea
                                            :id="'elements.' . $envIndex . '.' . $elemIndex . '.technical_notes'"
                                            class="mt-1 block w-full"
                                            wire:model="environments.{{ $envIndex }}.elements.{{ $elemIndex }}.technical_notes"
                                            rows="2"
                                        />
                                    </div>

                                    <!-- Condição -->
                                    <div>
                                        <x-label :for="'elements.' . $envIndex . '.' . $elemIndex . '.condition_status'" value="Estado de Conservação *" />
                                        <div class="mt-2 space-y-2">
                                            @foreach(['poor' => 'Ruim', 'fair' => 'Razoável', 'good' => 'Em bom estado', 'very_good' => 'Em ótimo estado', 'excellent' => 'Excelente'] as $value => $label)
                                                <label class="flex items-center">
                                                    <input
                                                        type="radio"
                                                        name="condition_{{ $envIndex }}_{{ $elemIndex }}"
                                                        value="{{ $value }}"
                                                        wire:model="environments.{{ $envIndex }}.elements.{{ $elemIndex }}.condition_status"
                                                        class="mr-2"
                                                    />
                                                    <span class="text-sm text-gray-700 dark:text-gray-300">{{ $label }}</span>
                                                </label>
                                            @endforeach
                                        </div>
                                        <x-input-error :for="'environments.' . $envIndex . '.elements.' . $elemIndex . '.condition_status'" class="mt-2" />
                                    </div>

                                    <!-- Medidas -->
                                    <div>
                                        <x-label :for="'elements.' . $envIndex . '.' . $elemIndex . '.measurements'" value="Medidas" />
                                        <x-input
                                            :id="'elements.' . $envIndex . '.' . $elemIndex . '.measurements'"
                                            type="text"
                                            class="mt-1 block w-full"
                                            wire:model="environments.{{ $envIndex }}.elements.{{ $elemIndex }}.measurements"
                                        />
                                    </div>

                                    <!-- Fotos do Elemento -->
                                    <div class="md:col-span-2">
                                        <x-label value="Fotos do Elemento" />
                                        @include('livewire.partials.media-capture', [
                                            'context' => ['type' => 'element', 'envIndex' => $envIndex, 'elemIndex' => $elemIndex],
                                            'photos' => $element['photos'] ?? []
                                        ])
                                    </div>

                                    <!-- Defeitos Identificados -->
                                    <div>
                                        <x-label :for="'elements.' . $envIndex . '.' . $elemIndex . '.defects_identified'" value="Defeitos Identificados" />
                                        <x-textarea
                                            :id="'elements.' . $envIndex . '.' . $elemIndex . '.defects_identified'"
                                            class="mt-1 block w-full"
                                            wire:model="environments.{{ $envIndex }}.elements.{{ $elemIndex }}.defects_identified"
                                            rows="2"
                                        />
                                    </div>

                                    <!-- Causas Prováveis -->
                                    <div>
                                        <x-label :for="'elements.' . $envIndex . '.' . $elemIndex . '.probable_causes'" value="Causas Prováveis" />
                                        <x-textarea
                                            :id="'elements.' . $envIndex . '.' . $elemIndex . '.probable_causes'"
                                            class="mt-1 block w-full"
                                            wire:model="environments.{{ $envIndex }}.elements.{{ $elemIndex }}.probable_causes"
                                            rows="2"
                                        />
                                    </div>
                                </div>
                            </div>
                        @endforeach

                        <button
                            type="button"
                            wire:click="addElement({{ $envIndex }})"
                            class="mt-4 w-full px-4 py-2 border-2 border-dashed border-gray-300 dark:border-gray-600 rounded-lg text-gray-600 dark:text-gray-400 hover:border-indigo-500 dark:hover:border-indigo-400 hover:text-indigo-600 dark:hover:text-indigo-300 transition-colors"
                        >
                            + Adicionar Elemento
                        </button>
                    </div>
                @endforeach
            </div>
        @endif

        <!-- Etapa 5: Revisão Final -->
        @if($currentStep === 5)
            <div class="bg-white dark:bg-gray-800 shadow-xl rounded-lg p-8">
                <div class="mb-6 text-center">
                    <h3 class="text-2xl font-bold text-gray-900 dark:text-gray-100 mb-2">
                        Revisão Final da Vistoria
                    </h3>
                    <p class="text-gray-600 dark:text-gray-400">
                        Revise todas as informações antes de finalizar
                    </p>
                </div>

                <!-- Resumo das Informações Gerais -->
                <div class="bg-gradient-to-r from-indigo-50 to-blue-50 dark:from-indigo-900/20 dark:to-blue-900/20 rounded-lg p-6 mb-6">
                    <h4 class="text-lg font-semibold text-gray-900 dark:text-gray-100 mb-4 flex items-center">
                        <i class="fi fi-rr-document mr-2"></i>
                        Informações Gerais
                    </h4>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div class="bg-white dark:bg-gray-800 rounded-lg p-4 shadow-sm">
                            <p class="text-sm text-gray-500 dark:text-gray-400 mb-1">Número da Vistoria</p>
                            <p class="text-lg font-semibold text-gray-900 dark:text-gray-100">{{ $number }}</p>
                        </div>
                        <div class="bg-white dark:bg-gray-800 rounded-lg p-4 shadow-sm">
                            <p class="text-sm text-gray-500 dark:text-gray-400 mb-1">Data da Vistoria</p>
                            <p class="text-lg font-semibold text-gray-900 dark:text-gray-100">
                                {{ $inspection_date ? \Carbon\Carbon::parse($inspection_date)->format('d/m/Y') : 'Não informada' }}
                            </p>
                        </div>
                        <div class="bg-white dark:bg-gray-800 rounded-lg p-4 shadow-sm md:col-span-2">
                            <p class="text-sm text-gray-500 dark:text-gray-400 mb-1">Endereço</p>
                            <p class="text-lg font-semibold text-gray-900 dark:text-gray-100">{{ $address }}</p>
                        </div>
                        <div class="bg-white dark:bg-gray-800 rounded-lg p-4 shadow-sm">
                            <p class="text-sm text-gray-500 dark:text-gray-400 mb-1">Responsável Técnico</p>
                            <p class="text-lg font-semibold text-gray-900 dark:text-gray-100">{{ $responsible_name }}</p>
                        </div>
                        @if($unit_area)
                        <div class="bg-white dark:bg-gray-800 rounded-lg p-4 shadow-sm">
                            <p class="text-sm text-gray-500 dark:text-gray-400 mb-1">Metragem</p>
                            <p class="text-lg font-semibold text-gray-900 dark:text-gray-100">{{ number_format($unit_area, 2, ',', '.') }} m²</p>
                        </div>
                        @endif
                        <div class="bg-white dark:bg-gray-800 rounded-lg p-4 shadow-sm">
                            <p class="text-sm text-gray-500 dark:text-gray-400 mb-1">Total de Ambientes</p>
                            <p class="text-lg font-semibold text-indigo-600 dark:text-indigo-400">{{ count($environments) }}</p>
                        </div>
                        @php
                            $totalElements = 0;
                            $totalPhotos = 0;
                            foreach($environments as $env) {
                                $totalElements += count($env['elements'] ?? []);
                                $totalPhotos += count($env['photos'] ?? []);
                                foreach($env['elements'] ?? [] as $elem) {
                                    $totalPhotos += count($elem['photos'] ?? []);
                                }
                            }
                        @endphp
                        <div class="bg-white dark:bg-gray-800 rounded-lg p-4 shadow-sm">
                            <p class="text-sm text-gray-500 dark:text-gray-400 mb-1">Total de Elementos</p>
                            <p class="text-lg font-semibold text-indigo-600 dark:text-indigo-400">{{ $totalElements }}</p>
                        </div>
                        <div class="bg-white dark:bg-gray-800 rounded-lg p-4 shadow-sm">
                            <p class="text-sm text-gray-500 dark:text-gray-400 mb-1">Total de Fotos</p>
                            <p class="text-lg font-semibold text-indigo-600 dark:text-indigo-400">{{ $totalPhotos }}</p>
                        </div>
                    </div>
                </div>

                <!-- Lista Detalhada de Ambientes -->
                <div class="space-y-4">
                    <h4 class="text-lg font-semibold text-gray-900 dark:text-gray-100 mb-4 flex items-center">
                        <i class="fi fi-rr-home mr-2"></i>
                        Ambientes e Elementos
                    </h4>
                    @foreach($environments as $envIndex => $environment)
                        <div class="border-2 border-gray-200 dark:border-gray-700 rounded-lg p-6 hover:border-indigo-300 dark:hover:border-indigo-600 transition-colors">
                            <div class="flex items-center justify-between mb-4">
                                <h5 class="text-xl font-bold text-gray-900 dark:text-gray-100 flex items-center">
                                    <i class="fi fi-rr-room mr-2 text-indigo-600 dark:text-indigo-400"></i>
                                    {{ $environment['name'] }}
                                </h5>
                                <span class="px-3 py-1 bg-indigo-100 dark:bg-indigo-900/50 text-indigo-700 dark:text-indigo-300 rounded-full text-sm font-medium">
                                    {{ count($environment['elements'] ?? []) }} {{ count($environment['elements'] ?? []) === 1 ? 'elemento' : 'elementos' }}
                                </span>
                            </div>
                            
                            @if(!empty($environment['technical_notes']))
                            <div class="mb-4 p-3 bg-gray-50 dark:bg-gray-700/50 rounded-lg">
                                <p class="text-sm text-gray-500 dark:text-gray-400 mb-1">Observações Técnicas:</p>
                                <p class="text-gray-700 dark:text-gray-300">{{ $environment['technical_notes'] }}</p>
                            </div>
                            @endif

                            @if(!empty($environment['photos']) && count($environment['photos']) > 0)
                            <div class="mb-4">
                                <p class="text-sm text-gray-500 dark:text-gray-400 mb-2">
                                    <i class="fi fi-rr-camera mr-1"></i>
                                    {{ count($environment['photos']) }} {{ count($environment['photos']) === 1 ? 'foto' : 'fotos' }} do ambiente
                                </p>
                            </div>
                            @endif

                            <!-- Elementos do Ambiente -->
                            @if(!empty($environment['elements']) && count($environment['elements']) > 0)
                            <div class="mt-4 space-y-3">
                                <p class="text-sm font-semibold text-gray-700 dark:text-gray-300 mb-2">Elementos:</p>
                                @foreach($environment['elements'] as $elemIndex => $element)
                                    <div class="bg-gray-50 dark:bg-gray-700/30 rounded-lg p-4 
                                        @if($element['condition_status'] === 'excellent') border-green-500
                                        @elseif($element['condition_status'] === 'very_good') border-blue-500
                                        @elseif($element['condition_status'] === 'good') border-indigo-500
                                        @elseif($element['condition_status'] === 'fair') border-yellow-500
                                        @else border-red-500
                                        @endif">
                                        <div class="flex items-center justify-between mb-2">
                                            <h6 class="font-semibold text-gray-900 dark:text-gray-100">{{ $element['name'] }}</h6>
                                            <span class="px-2 py-1 rounded text-xs font-medium
                                                @if($element['condition_status'] === 'excellent') bg-green-100 text-green-800 dark:bg-green-900/50 dark:text-green-300
                                                @elseif($element['condition_status'] === 'very_good') bg-blue-100 text-blue-800 dark:bg-blue-900/50 dark:text-blue-300
                                                @elseif($element['condition_status'] === 'good') bg-indigo-100 text-indigo-800 dark:bg-indigo-900/50 dark:text-indigo-300
                                                @elseif($element['condition_status'] === 'fair') bg-yellow-100 text-yellow-800 dark:bg-yellow-900/50 dark:text-yellow-300
                                                @else bg-red-100 text-red-800 dark:bg-red-900/50 dark:text-red-300
                                                @endif">
                                                @if($element['condition_status'] === 'excellent') Excelente
                                                @elseif($element['condition_status'] === 'very_good') Muito Bom
                                                @elseif($element['condition_status'] === 'good') Bom
                                                @elseif($element['condition_status'] === 'fair') Razoável
                                                @else Ruim
                                                @endif
                                            </span>
                                        </div>
                                        @if(!empty($element['technical_notes']))
                                        <p class="text-sm text-gray-600 dark:text-gray-400 mb-2">{{ $element['technical_notes'] }}</p>
                                        @endif
                                        @if(!empty($element['photos']) && count($element['photos']) > 0)
                                        <p class="text-xs text-gray-500 dark:text-gray-400">
                                            <i class="fi fi-rr-camera mr-1"></i>
                                            {{ count($element['photos']) }} {{ count($element['photos']) === 1 ? 'foto' : 'fotos' }}
                                        </p>
                                        @endif
                                    </div>
                                @endforeach
                            </div>
                            @endif
                        </div>
                    @endforeach
                </div>
            </div>
        @endif

        <!-- Navegação -->
        <div class="mt-6 flex justify-between">
            <div>
                @if($currentStep > 1)
                    <x-button type="button" wire:click="goBack" class="bg-gray-600 hover:bg-gray-700">
                        ← Voltar
                    </x-button>
                @endif
            </div>
            <div class="flex gap-3">
                @if($currentStep < $totalSteps)
                    <x-button type="button" wire:click="nextStep" class="bg-indigo-600 hover:bg-indigo-700">
                        Próximo →
                    </x-button>
                @else
                    <x-button type="button" wire:click="saveDraft" class="bg-gray-600 hover:bg-gray-700">
                        Salvar Rascunho
                    </x-button>
                    <x-button type="button" wire:click="save" class="bg-green-600 hover:bg-green-700">
                        Salvar e Finalizar
                    </x-button>
                @endif
            </div>
        </div>
    </div>

    @push('scripts')
    @php
        try {
            $mapsService = app(\App\Services\GoogleMapsService::class);
            $mapsApiKey = $mapsService->getApiKey();
        } catch (\Exception $e) {
            $mapsApiKey = null;
        }
    @endphp
    
    <script>
        function mediaCapture() {
            return {
                previews: [],
                isMobile: /Android|webOS|iPhone|iPad|iPod|BlackBerry|IEMobile|Opera Mini/i.test(navigator.userAgent),
                showMediaOptions: false,
                context: null,
                
                openMediaSelector(context) {
                    this.context = context;
                    if (this.isMobile) {
                        this.showMediaOptions = true;
                    } else {
                        this.$refs.galleryInput.click();
                    }
                },
                
                openCamera() {
                    this.$refs.cameraInput.click();
                },
                
                openGallery() {
                    this.$refs.galleryInput.click();
                },
                
                handleFiles(event) {
                    const files = Array.from(event.target.files);
                    files.forEach(file => {
                        if (this.validateFile(file)) {
                            const reader = new FileReader();
                            reader.onload = (e) => {
                                this.previews.push({
                                    file: file,
                                    url: e.target.result,
                                    name: file.name,
                                    size: file.size
                                });
                            };
                            reader.readAsDataURL(file);
                        }
                    });
                    event.target.value = '';
                },
                
                validateFile(file) {
                    const maxSize = 5 * 1024 * 1024;
                    const allowedTypes = ['image/jpeg', 'image/png', 'image/webp', 'image/jpg'];
                    
                    if (!allowedTypes.includes(file.type)) {
                        alert('Tipo de arquivo não permitido. Use JPG, PNG ou WEBP.');
                        return false;
                    }
                    
                    if (file.size > maxSize) {
                        alert('Arquivo muito grande. Máximo 5MB.');
                        return false;
                    }
                    
                    return true;
                },
                
                removePreview(index) {
                    this.previews.splice(index, 1);
                },
                
                async uploadAll() {
                    if (this.previews.length === 0) return;
                    
                    const files = this.previews.map(p => p.file);
                    @this.call('uploadMultiplePhotos', files, this.context);
                    
                    this.previews = [];
                    this.showMediaOptions = false;
                }
            }
        }

        // Variáveis globais do Google Maps
        let map = null;
        let marker = null;
        let geocoder = null;
        let mapsScriptLoaded = false;
        let mapsInitialized = false;

        @if(!empty($mapsApiKey))
        const GOOGLE_MAPS_API_KEY = {!! json_encode($mapsApiKey) !!};
        @else
        const GOOGLE_MAPS_API_KEY = '';
        @endif

        // Função para carregar o script do Google Maps
        function loadGoogleMapsScript() {
            if (mapsScriptLoaded) {
                return Promise.resolve();
            }

            return new Promise((resolve, reject) => {
                // Verificar se já está carregado
                if (typeof google !== 'undefined' && google.maps) {
                    mapsScriptLoaded = true;
                    resolve();
                    return;
                }

                // Verificar se o script já está sendo carregado
                const existingScript = document.querySelector('script[src*="maps.googleapis.com"]');
                if (existingScript) {
                    existingScript.addEventListener('load', () => {
                        mapsScriptLoaded = true;
                        resolve();
                    });
                    return;
                }

                // Criar callback global
                window.initGoogleMapsCallback = function() {
                    mapsScriptLoaded = true;
                    resolve();
                };

                // Carregar script com loading=async (sem biblioteca places)
                const script = document.createElement('script');
                script.src = 'https://maps.googleapis.com/maps/api/js?key=' + GOOGLE_MAPS_API_KEY + '&language=pt-BR&region=BR&loading=async&callback=initGoogleMapsCallback';
                script.async = true;
                script.defer = true;
                script.onerror = reject;
                document.head.appendChild(script);
            });
        }

        // Função para carregar html2canvas
        function loadHtml2Canvas() {
            if (typeof html2canvas !== 'undefined') {
                return Promise.resolve();
            }

            return new Promise((resolve, reject) => {
                const existingScript = document.querySelector('script[src*="html2canvas"]');
                if (existingScript) {
                    existingScript.addEventListener('load', resolve);
                    return;
                }

                const script = document.createElement('script');
                script.src = 'https://cdnjs.cloudflare.com/ajax/libs/html2canvas/1.4.1/html2canvas.min.js';
                script.defer = true;
                script.onload = resolve;
                script.onerror = reject;
                document.head.appendChild(script);
            });
        }

        // Função para inicializar o mapa
        function initMap() {
            const mapContainer = document.getElementById('map-container');
            
            if (!mapContainer) {
                console.warn('Container do mapa não encontrado');
                return;
            }

            // Verificar se o container está visível
            const isVisible = mapContainer.offsetParent !== null || 
                            mapContainer.style.display !== 'none' ||
                            mapContainer.offsetWidth > 0;
            
            if (!isVisible) {
                console.warn('Container do mapa não está visível');
                return;
            }

            // Se o mapa já foi inicializado, não inicializar novamente
            if (map && mapsInitialized) {
                return;
            }

            if (typeof google === 'undefined' || !google.maps) {
                console.error('Google Maps API não carregada');
                return;
            }

            @php
                $lat = isset($coordinates['lat']) && $coordinates['lat'] !== null ? floatval($coordinates['lat']) : -23.5505;
                $lng = isset($coordinates['lng']) && $coordinates['lng'] !== null ? floatval($coordinates['lng']) : -46.6333;
            @endphp
            const initialLat = {{ $lat }};
            const initialLng = {{ $lng }};

            try {
                // Criar mapa
                map = new google.maps.Map(mapContainer, {
                    center: { lat: initialLat, lng: initialLng },
                    zoom: 15,
                    mapTypeId: 'roadmap',
                });

                geocoder = new google.maps.Geocoder();

                // Criar marcador arrastável
                marker = new google.maps.Marker({
                    position: { lat: initialLat, lng: initialLng },
                    map: map,
                    draggable: true,
                });

                // Atualizar coordenadas quando arrastar
                marker.addListener('dragend', function() {
                    const pos = marker.getPosition();
                    @this.updateCoordinates(pos.lat(), pos.lng());
                });

                // Configurar busca de endereço usando apenas geocoding manual (sem autocomplete)
                const addressInput = document.getElementById('address_search');
                if (addressInput) {
                    // Listener para Enter
                    addressInput.addEventListener('keydown', function(e) {
                        if (e.key === 'Enter') {
                            e.preventDefault();
                            const address = addressInput.value;
                            if (address.trim()) {
                                searchAddressOnMap();
                            }
                        }
                    });
                }

                mapsInitialized = true;
                console.log('Mapa inicializado com sucesso');
            } catch (error) {
                console.error('Erro ao inicializar mapa:', error);
            }
        }

        // Função para inicializar o mapa quando entrar na etapa 2
        async function initializeMapForStep2() {
            if (!GOOGLE_MAPS_API_KEY || GOOGLE_MAPS_API_KEY === '') {
                console.warn('Google Maps API key não configurada');
                return;
            }

            const mapContainer = document.getElementById('map-container');
            if (!mapContainer) {
                // Aguardar um pouco e tentar novamente
                setTimeout(initializeMapForStep2, 500);
                return;
            }

            try {
                // Carregar scripts necessários
                await loadGoogleMapsScript();
                await loadHtml2Canvas();

                // Aguardar um pouco para garantir que o DOM está pronto
                setTimeout(() => {
                    initMap();
                }, 300);
            } catch (error) {
                console.error('Erro ao carregar Google Maps:', error);
            }
        }

        // Função para buscar endereço no mapa
        async function searchAddressOnMap() {
            const addressInput = document.getElementById('address_search');
            if (!addressInput || !addressInput.value) {
                return;
            }

            const address = addressInput.value.trim();
            if (!address) {
                return;
            }

            // Verificar se o mapa está inicializado
            if (!map || !geocoder) {
                alert('Mapa não inicializado. Aguarde o carregamento.');
                return;
            }

            // Mostrar loading
            const button = document.querySelector('button[x-on\\:click*="searchAddressOnMap"]') || 
                          document.querySelector('button[onclick*="searchAddressOnMap"]');
            let originalButtonText = null;
            if (button) {
                originalButtonText = button.textContent;
                button.disabled = true;
                button.textContent = 'Buscando...';
            }

            try {
                // Usar geocoding do Google Maps diretamente
                geocoder.geocode({ address: address }, function(results, status) {
                    if (button) {
                        button.disabled = false;
                        button.textContent = originalButtonText || 'Buscar no Mapa';
                    }

                    if (status === 'OK' && results && results[0]) {
                        const location = results[0].geometry.location;
                        const lat = location.lat();
                        const lng = location.lng();

                        // Atualizar mapa
                        if (results[0].geometry.viewport) {
                            map.fitBounds(results[0].geometry.viewport);
                        } else {
                            map.setCenter(location);
                            map.setZoom(17);
                        }

                        // Atualizar marcador
                        if (marker) {
                            marker.setPosition(location);
                        } else {
                            marker = new google.maps.Marker({
                                position: location,
                                map: map,
                                draggable: true,
                            });
                            
                            marker.addListener('dragend', function() {
                                const pos = marker.getPosition();
                                @this.updateCoordinates(pos.lat(), pos.lng());
                            });
                        }

                        // Atualizar coordenadas no Livewire
                        @this.updateCoordinates(lat, lng);
                        
                        // Atualizar endereço no Livewire
                        @this.set('address', results[0].formatted_address || address);
                    } else {
                        alert('Endereço não encontrado. Tente novamente com um endereço mais específico.');
                    }
                });
            } catch (error) {
                if (button) {
                    button.disabled = false;
                    button.textContent = originalButtonText || 'Buscar no Mapa';
                }
                console.error('Erro ao buscar endereço:', error);
                alert('Erro ao buscar endereço. Tente novamente.');
            }
        }

        // Tornar a função global para ser acessível pelo Alpine.js
        window.searchAddressOnMap = searchAddressOnMap;

        // Função para capturar screenshot do mapa
        function captureMapScreenshot() {
            if (!map) {
                alert('Mapa não inicializado. Aguarde o carregamento.');
                return;
            }

            const mapContainer = document.getElementById('map-container');
            if (!mapContainer) return;

            if (typeof html2canvas === 'undefined') {
                alert('Biblioteca html2canvas não carregada. Recarregue a página.');
                return;
            }

            html2canvas(mapContainer, {
                useCORS: true,
                logging: false,
                backgroundColor: '#ffffff',
                scale: 2,
                width: mapContainer.offsetWidth,
                height: mapContainer.offsetHeight
            }).then(canvas => {
                const imageData = canvas.toDataURL('image/png');
                @this.saveMapScreenshot(imageData);
            }).catch(error => {
                console.error('Erro ao capturar mapa:', error);
                alert('Erro ao capturar o mapa. Tente novamente.');
            });
        }

        // Função para verificar e inicializar mapa quando necessário
        function checkAndInitializeMap() {
            const mapContainer = document.getElementById('map-container');
            if (mapContainer && !mapsInitialized) {
                // Verificar se o container está visível
                const isVisible = mapContainer.offsetParent !== null;
                if (isVisible) {
                    initializeMapForStep2();
                }
            }
        }

        // Inicializar listeners do Livewire
        function setupLivewireListeners() {
            // Armazenar referência do componente para usar no fallback
            let componentId = null;
            try {
                componentId = @this.__instance.id;
            } catch (e) {
                // Se não conseguir acessar, tentar encontrar pelo DOM
                const livewireElement = document.querySelector('[wire\\:id]');
                if (livewireElement) {
                    componentId = livewireElement.getAttribute('wire:id');
                }
            }
            
            try {
                // Listener para mudanças de etapa
                @this.on('step-changed', (step) => {
                    const stepNumber = Array.isArray(step) ? step[0] : step;
                    if (stepNumber === 2) {
                        mapsInitialized = false;
                        setTimeout(() => {
                            checkAndInitializeMap();
                        }, 500);
                    } else {
                        if (map) {
                            mapsInitialized = false;
                        }
                    }
                });

                // Listener para eventos do mapa
                @this.on('map-center', (data) => {
                    if (map && data && data.lat && data.lng) {
                        const center = { lat: parseFloat(data.lat), lng: parseFloat(data.lng) };
                        map.setCenter(center);
                        if (marker) {
                            marker.setPosition(center);
                        } else if (map) {
                            marker = new google.maps.Marker({
                                position: center,
                                map: map,
                                draggable: true,
                            });
                            
                            marker.addListener('dragend', function() {
                                const pos = marker.getPosition();
                                @this.updateCoordinates(pos.lat(), pos.lng());
                            });
                        }
                    }
                });

                @this.on('capture-map-screenshot', () => {
                    if (map) {
                        captureMapScreenshot();
                    }
                });
            } catch (error) {
                // Se @this.on() falhar, usar Livewire.on() como fallback
                console.warn('Usando Livewire.on() como fallback:', error);
                if (typeof Livewire !== 'undefined') {
                    Livewire.on('step-changed', (step) => {
                        const stepNumber = Array.isArray(step) ? step[0] : step;
                        if (stepNumber === 2) {
                            mapsInitialized = false;
                            setTimeout(() => {
                                checkAndInitializeMap();
                            }, 500);
                        } else {
                            if (map) {
                                mapsInitialized = false;
                            }
                        }
                    });

                    Livewire.on('map-center', (data) => {
                        if (map && data && data.lat && data.lng) {
                            const center = { lat: parseFloat(data.lat), lng: parseFloat(data.lng) };
                            map.setCenter(center);
                            if (marker) {
                                marker.setPosition(center);
                            } else if (map) {
                                marker = new google.maps.Marker({
                                    position: center,
                                    map: map,
                                    draggable: true,
                                });
                                
                                marker.addListener('dragend', function() {
                                    const pos = marker.getPosition();
                                    // Usar Livewire.find() para acessar o componente
                                    if (componentId && window.Livewire) {
                                        const component = window.Livewire.find(componentId);
                                        if (component) {
                                            component.call('updateCoordinates', pos.lat(), pos.lng());
                                        }
                                    }
                                });
                            }
                        }
                    });

                    Livewire.on('capture-map-screenshot', () => {
                        if (map) {
                            captureMapScreenshot();
                        }
                    });
                }
            }
        }

        // Configurar quando o Livewire estiver pronto
        document.addEventListener('livewire:init', () => {
            setupLivewireListeners();
            
            // Verificar etapa inicial
            const currentStep = @json($currentStep);
            if (currentStep === 2) {
                setTimeout(() => {
                    checkAndInitializeMap();
                }, 500);
            }
        });

        // Observar atualizações do Livewire
        document.addEventListener('livewire:init', () => {
            Livewire.hook('morph.updated', () => {
                // Verificar se precisa inicializar o mapa após atualização do DOM
                setTimeout(() => {
                    // Verificar se o container do mapa existe e está visível
                    const mapContainer = document.getElementById('map-container');
                    if (mapContainer && mapContainer.offsetParent !== null && !mapsInitialized) {
                        checkAndInitializeMap();
                    }
                }, 300);
            });
        });

        // Inicializar mapa se já estiver na etapa 2 ao carregar a página
        document.addEventListener('DOMContentLoaded', function() {
            const currentStep = @json($currentStep);
            if (currentStep === 2) {
                setTimeout(() => {
                    checkAndInitializeMap();
                }, 500);
            }
        });

    </script>
    @endpush
</div>
