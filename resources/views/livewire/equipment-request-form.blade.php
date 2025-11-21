<div>
    <form wire:submit.prevent="save">
        <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">
            <!-- Número da Requisição -->
            <div>
                <label for="number" class="block text-sm font-medium text-gray-700">Número da Requisição *</label>
                <input type="text" wire:model="number" id="number" 
                       class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                @error('number') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
            </div>

            <!-- Tipo -->
            <div>
                <label for="type" class="block text-sm font-medium text-gray-700">Tipo *</label>
                <select wire:model="type" id="type" 
                        class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                    <option value="loan">Empréstimo</option>
                    <option value="return">Devolução</option>
                </select>
                @error('type') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
            </div>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">
            <!-- Funcionário -->
            <div>
                <label for="employee_id" class="block text-sm font-medium text-gray-700">Funcionário *</label>
                <select wire:model="employee_id" id="employee_id" 
                        class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                    <option value="">Selecione um funcionário</option>
                    @foreach($employees as $employee)
                        <option value="{{ $employee->id }}">{{ $employee->name }} - {{ $employee->department }}</option>
                    @endforeach
                </select>
                @error('employee_id') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
            </div>

            <!-- Data Prevista de Devolução -->
            @if($type === 'loan')
            <div>
                <label for="expected_return_date" class="block text-sm font-medium text-gray-700">Data Prevista de Devolução</label>
                <input type="date" wire:model="expected_return_date" id="expected_return_date" 
                       class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                @error('expected_return_date') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
            </div>
            @endif
        </div>

        <!-- Busca de OS -->
        <div class="mb-6">
            <label for="osSearch" class="block text-sm font-medium text-gray-700">Ordem de Serviço</label>
            <div class="relative">
                <input type="text" wire:model.debounce.300ms="osSearch" id="osSearch" 
                       placeholder="Digite para buscar uma OS..." 
                       class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500"
                       wire:keydown.escape="$set('osSearch', '')">
                
                @if(strlen($osSearch) >= 2)
                    @if(count($osSearchResults) > 0)
                        <div class="absolute z-10 mt-1 w-full bg-white shadow-lg max-h-60 rounded-md py-1 text-base ring-1 ring-black ring-opacity-5 overflow-auto focus:outline-none">
                            @foreach($osSearchResults as $os)
                                <div wire:click="selectServiceOrder({{ $os->id }})" 
                                     class="cursor-pointer select-none relative py-2 pl-3 pr-9 hover:bg-indigo-600 hover:text-white">
                                    <div class="flex items-center">
                                        <span class="font-medium">#{{ $os->number }}</span>
                                        <span class="ml-2 text-sm text-gray-500">{{ $os->client_name }}</span>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    @else
                        <div class="absolute z-10 mt-1 w-full bg-white shadow-lg rounded-md py-2 px-3 text-sm text-gray-500">
                            Nenhuma OS encontrada.
                        </div>
                    @endif
                @endif
            </div>
            @if($service_order_id)
                @php $selectedOS = $serviceOrders->find($service_order_id) @endphp
                @if($selectedOS)
                    <div class="mt-2 p-2 bg-indigo-50 rounded-md">
                        <span class="text-sm text-indigo-700">OS Selecionada: #{{ $selectedOS->number }} - {{ $selectedOS->client_name }}</span>
                        <button type="button" wire:click="$set('service_order_id', null)" class="ml-2 text-indigo-600 hover:text-indigo-800">
                            <i class="bi bi-x"></i>
                        </button>
                    </div>
                @endif
            @endif
        </div>

        <!-- Finalidade -->
        @if($type === 'loan')
        <div class="mb-6">
            <label for="purpose" class="block text-sm font-medium text-gray-700">Finalidade</label>
            <textarea wire:model="purpose" id="purpose" rows="3" 
                      class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500"
                      placeholder="Descreva a finalidade do empréstimo..."></textarea>
            @error('purpose') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
        </div>
        @endif

        <!-- Busca de Equipamentos -->
        <div class="mb-6">
            <label for="search" class="block text-sm font-medium text-gray-700">
                Buscar {{ $type === 'loan' ? 'Equipamentos Disponíveis' : 'Equipamentos Emprestados' }} *
            </label>
            <div class="relative">
                <input type="text" wire:model.debounce.300ms="search" id="search" 
                       placeholder="Digite o nome ou número de série..." 
                       class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500"
                       wire:keydown.escape="$set('search', '')">
                
                @if(strlen($search) >= 2)
                    @if(count($searchResults) > 0)
                        <div class="absolute z-10 mt-1 w-full bg-white shadow-lg max-h-60 rounded-md py-1 text-base ring-1 ring-black ring-opacity-5 overflow-auto focus:outline-none">
                            @foreach($searchResults as $equipment)
                                <div wire:click="selectEquipment({{ $equipment->id }})" 
                                     class="cursor-pointer select-none relative py-2 pl-3 pr-9 hover:bg-indigo-600 hover:text-white">
                                    <div class="flex items-center">
                                        @if($equipment->photos && count($equipment->photos) > 0)
                                            <img src="{{ asset('storage/' . $equipment->photos[0]) }}" 
                                                 alt="{{ $equipment->name }}" 
                                                 class="h-8 w-8 rounded object-cover mr-3">
                                        @else
                                            <div class="h-8 w-8 rounded bg-gray-200 mr-3 flex items-center justify-center">
                                                <i class="bi bi-image text-gray-400"></i>
                                            </div>
                                        @endif
                                        <div>
                                            <span class="font-medium">{{ $equipment->name }}</span>
                                            <span class="ml-2 text-sm text-gray-500">{{ $equipment->serial_number }}</span>
                                            @if($equipment->category)
                                                <span class="ml-2 text-xs text-gray-400">{{ $equipment->category->name }}</span>
                                            @endif
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    @else
                        <div class="absolute z-10 mt-1 w-full bg-white shadow-lg rounded-md py-2 px-3 text-sm text-gray-500">
                            Nenhum equipamento encontrado.
                        </div>
                    @endif
                @endif
            </div>
        </div>

        <!-- Equipamentos Selecionados -->
        @if(count($selectedEquipment) > 0)
            <div class="mb-6">
                <h3 class="text-lg font-medium text-gray-900 mb-3">Equipamentos Selecionados</h3>
                <div class="space-y-3">
                    @foreach($selectedEquipment as $index => $equipment)
                        <div class="flex items-center justify-between p-4 bg-gray-50 rounded-lg">
                            <div class="flex items-center flex-1">
                                @if(isset($equipment['photo']) && $equipment['photo'])
                                    <img src="{{ asset('storage/' . $equipment['photo']) }}" alt="{{ $equipment['name'] }}" 
                                         class="h-12 w-12 rounded object-cover mr-4">
                                @else
                                    <div class="h-12 w-12 rounded bg-gray-200 mr-4 flex items-center justify-center">
                                        <i class="bi bi-image text-gray-400"></i>
                                    </div>
                                @endif
                                <div class="flex-1">
                                    <h4 class="font-medium text-gray-900">{{ $equipment['name'] }}</h4>
                                    <p class="text-sm text-gray-500">Série: {{ $equipment['serial_number'] }}</p>
                                </div>
                            </div>
                            
                            <div class="flex items-center space-x-4">
                                <!-- Quantidade -->
                                <div>
                                    <label class="block text-xs font-medium text-gray-700">Qtd</label>
                                    <input type="number" wire:change="updateQuantity({{ $index }}, $event.target.value)" 
                                           value="{{ $equipment['quantity'] }}" min="1" 
                                           class="w-16 rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
</div>

                                <!-- Observações sobre condição -->
                                @if($type === 'return')
                                <div class="flex-1 max-w-xs">
                                    <label class="block text-xs font-medium text-gray-700">Condição</label>
                                    <input type="text" wire:change="updateConditionNotes({{ $index }}, $event.target.value)" 
                                           value="{{ $equipment['condition_notes'] }}" 
                                           placeholder="Estado do equipamento..."
                                           class="w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                                </div>
                                @endif
                                
                                <!-- Remover -->
                                <button type="button" wire:click="removeEquipment({{ $index }})" 
                                        class="text-red-600 hover:text-red-800">
                                    <i class="bi bi-trash"></i>
                                </button>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>
        @endif

        @error('selectedEquipment') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror

        <!-- Observações -->
        <div class="mb-6">
            <label for="notes" class="block text-sm font-medium text-gray-700">Observações</label>
            <textarea wire:model="notes" id="notes" rows="3" 
                      class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500"
                      placeholder="Observações adicionais..."></textarea>
            @error('notes') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
        </div>

        <!-- Botões -->
        <div class="flex justify-end space-x-3">
            <a href="{{ route('equipment-requests.index') }}" 
               class="bg-gray-300 hover:bg-gray-400 text-gray-700 font-bold py-2 px-4 rounded">
                Cancelar
            </a>
            <button type="submit" 
                    class="bg-indigo-600 hover:bg-indigo-700 text-white font-bold py-2 px-4 rounded">
                {{ $equipmentRequest ? 'Atualizar' : 'Criar' }} Requisição
            </button>
        </div>
    </form>
</div>