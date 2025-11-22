<div>
    <form wire:submit.prevent="save" class="space-y-6">
        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
            <!-- Número da Requisição -->
            <div>
                <x-label for="number" value="{{ __('Número da Requisição') }}" />
                <x-input id="number" type="text" class="block mt-1 w-full bg-gray-50" wire:model="number" readonly />
                <x-input-error for="number" class="mt-2" />
            </div>

            <!-- Tipo -->
            <div>
                <x-label for="type" value="{{ __('Tipo') }}" />
                <select wire:model="type" id="type" class="border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm block mt-1 w-full">
                    <option value="loan">Empréstimo</option>
                    <option value="return">Devolução</option>
                </select>
                <x-input-error for="type" class="mt-2" />
            </div>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
            <!-- Funcionário -->
            <div>
                <x-label for="employee_id" value="{{ __('Funcionário') }}" />
                <select wire:model="employee_id" id="employee_id" class="border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm block mt-1 w-full">
                    <option value="">Selecione um funcionário</option>
                    @foreach($employees as $employee)
                        <option value="{{ $employee->id }}">{{ $employee->name }} - {{ $employee->department }}</option>
                    @endforeach
                </select>
                <x-input-error for="employee_id" class="mt-2" />
            </div>

            <!-- Data Prevista de Devolução -->
            @if($type === 'loan')
            <div>
                <x-label for="expected_return_date" value="{{ __('Data Prevista de Devolução') }}" />
                <x-input id="expected_return_date" type="date" class="block mt-1 w-full" wire:model="expected_return_date" />
                <x-input-error for="expected_return_date" class="mt-2" />
            </div>
            @endif
        </div>

        <!-- Busca de OS -->
        <div>
            <x-label for="osSearch" value="{{ __('Ordem de Serviço') }}" />
            <div class="mt-1 relative">
                @if($selectedServiceOrder ?? null)
                    <div class="flex items-center justify-between p-3 bg-gray-50 border border-gray-300 rounded-md">
                        <div>
                            <div class="font-medium text-gray-900">{{ $selectedServiceOrder->number }}</div>
                            <div class="text-sm text-gray-500">{{ $selectedServiceOrder->client_name }}</div>
                        </div>
                        <button type="button" wire:click="clearServiceOrder" class="text-red-600 hover:text-red-900">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                            </svg>
                        </button>
                    </div>
                @else
                    <x-input 
                        id="osSearch" 
                        type="text" 
                        class="block w-full" 
                        wire:model.live.debounce.300ms="osSearch" 
                        placeholder="Digite para buscar OS por número ou cliente..." 
                    />
                    <input type="hidden" wire:model="service_order_id" />
                    
                    @if($osSearchResults && count($osSearchResults) > 0)
                        <div class="absolute z-10 mt-1 w-full bg-white shadow-lg max-h-60 rounded-md py-1 text-base ring-1 ring-black ring-opacity-5 overflow-auto focus:outline-none">
                            @foreach($osSearchResults as $os)
                                <button 
                                    type="button"
                                    wire:click="selectServiceOrder({{ $os->id }})"
                                    class="w-full text-left px-4 py-2 hover:bg-indigo-50 hover:text-indigo-900 cursor-pointer"
                                >
                                    <div class="font-medium">{{ $os->number }}</div>
                                    <div class="text-sm text-gray-500">{{ $os->client_name }}</div>
                                </button>
                            @endforeach
                        </div>
                    @endif
                @endif
            </div>
            <x-input-error for="service_order_id" class="mt-2" />
        </div>

        <!-- Finalidade -->
        @if($type === 'loan')
        <div>
            <x-label for="purpose" value="{{ __('Finalidade') }}" />
            <x-textarea id="purpose" class="block mt-1 w-full" wire:model="purpose" rows="3" placeholder="Descreva a finalidade do empréstimo..." />
            <x-input-error for="purpose" class="mt-2" />
        </div>
        @endif

        <!-- Busca de Equipamentos -->
        <div>
            <x-label for="search" value="{{ $type === 'loan' ? __('Buscar Equipamentos Disponíveis') : __('Buscar Equipamentos Emprestados') }}" />
            <div class="mt-1 relative">
                <x-input 
                    id="search" 
                    type="text" 
                    class="block w-full" 
                    wire:model.live.debounce.300ms="search" 
                    placeholder="Digite o nome ou número de série..." 
                />
                
                @if($searchResults && count($searchResults) > 0)
                    <div class="absolute z-10 mt-1 w-full bg-white shadow-lg max-h-60 rounded-md py-1 text-base ring-1 ring-black ring-opacity-5 overflow-auto focus:outline-none">
                        @foreach($searchResults as $equipment)
                            <button 
                                type="button"
                                wire:click="selectEquipment({{ $equipment->id }})"
                                class="w-full text-left px-4 py-2 hover:bg-indigo-50 hover:text-indigo-900 cursor-pointer flex items-center"
                            >
                                @if($equipment->photos && count($equipment->photos) > 0)
                                    <img src="{{ asset('storage/' . $equipment->photos[0]) }}" alt="{{ $equipment->name }}" class="h-8 w-8 rounded object-cover mr-3">
                                @else
                                    <div class="h-8 w-8 rounded bg-gray-200 mr-3 flex items-center justify-center">
                                        <svg class="w-5 h-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z" />
                                        </svg>
                                    </div>
                                @endif
                                <div>
                                    <div class="font-medium">{{ $equipment->name }}</div>
                                    <div class="text-sm text-gray-500">{{ $equipment->serial_number }}</div>
                                </div>
                            </button>
                        @endforeach
                    </div>
                @endif
            </div>
        </div>

        <!-- Equipamentos Selecionados -->
        @if(count($selectedEquipment) > 0)
            <div>
                <h3 class="text-lg font-medium text-gray-900 mb-3">{{ __('Equipamentos Selecionados') }}</h3>
                <div class="space-y-3">
                    @foreach($selectedEquipment as $index => $equipment)
                        <div class="flex items-center justify-between p-4 bg-gray-50 rounded-lg">
                            <div class="flex items-center flex-1">
                                @if(isset($equipment['photo']) && $equipment['photo'])
                                    <img src="{{ asset('storage/' . $equipment['photo']) }}" alt="{{ $equipment['name'] }}" class="h-12 w-12 rounded object-cover mr-4">
                                @else
                                    <div class="h-12 w-12 rounded bg-gray-200 mr-4 flex items-center justify-center">
                                        <svg class="w-6 h-6 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z" />
                                        </svg>
                                    </div>
                                @endif
                                <div class="flex-1">
                                    <h4 class="font-medium text-gray-900">{{ $equipment['name'] }}</h4>
                                    <p class="text-sm text-gray-500">Série: {{ $equipment['serial_number'] }}</p>
                                </div>
                            </div>
                            
                            <div class="flex items-center space-x-4">
                                <div>
                                    <x-label for="quantity_{{ $index }}" value="{{ __('Qtd') }}" class="text-xs" />
                                    <x-input id="quantity_{{ $index }}" type="number" class="w-16" wire:change="updateQuantity({{ $index }}, $event.target.value)" value="{{ $equipment['quantity'] }}" min="1" />
                                </div>
                                @if($type === 'return')
                                <div class="flex-1 max-w-xs">
                                    <x-label for="condition_{{ $index }}" value="{{ __('Condição') }}" class="text-xs" />
                                    <x-input id="condition_{{ $index }}" type="text" class="block w-full" wire:change="updateConditionNotes({{ $index }}, $event.target.value)" value="{{ $equipment['condition_notes'] ?? '' }}" placeholder="Estado do equipamento..." />
                                </div>
                                @endif
                                <button type="button" wire:click="removeEquipment({{ $index }})" class="text-red-600 hover:text-red-900 p-2">
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                                    </svg>
                                </button>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>
        @endif

        <x-input-error for="selectedEquipment" class="mt-2" />

        <!-- Observações -->
        <div>
            <x-label for="notes" value="{{ __('Observações') }}" />
            <x-textarea id="notes" class="block mt-1 w-full" wire:model="notes" rows="3" placeholder="Observações adicionais..." />
            <x-input-error for="notes" class="mt-2" />
        </div>

        <!-- Botões -->
        <div class="flex justify-end space-x-4 pt-4">
            <a href="{{ route('equipment-requests.index') }}" class="inline-flex items-center px-4 py-2 bg-gray-800 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-gray-700 focus:bg-gray-700 active:bg-gray-900 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 transition ease-in-out duration-150">
                {{ __('Cancelar') }}
            </a>
            <x-button-loading>
                {{ $equipmentRequest ? __('Salvar') : __('Salvar') }}
            </x-button-loading>
        </div>
    </form>
</div>