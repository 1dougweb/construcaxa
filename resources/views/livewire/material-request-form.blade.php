<div>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('Nova Requisição de Material') }}
        </h2>
    </x-slot>
                    <form wire:submit.prevent="save">
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <!-- OS (Ordem de Serviço) -->
                            <div class="col-span-2">
                                <x-label for="osSearch" value="{{ __('Ordem de Serviço (OS)') }}" />
                                <div class="mt-1 relative">
                                    @if($selectedServiceOrder)
                                        <div class="flex items-center justify-between p-3 bg-gray-50 dark:bg-gray-700 border border-gray-300 dark:border-gray-600 rounded-md">
                                            <div>
                                                <div class="font-medium text-gray-900 dark:text-gray-100">{{ $selectedServiceOrder->number }}</div>
                                                <div class="text-sm text-gray-500 dark:text-gray-400">{{ $selectedServiceOrder->client_name }}</div>
                                            </div>
                                            <button type="button" wire:click="clearServiceOrder" class="text-red-600 dark:text-red-400 hover:text-red-900 dark:hover:text-red-300">
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
                                            <div class="absolute z-10 mt-1 w-full bg-white dark:bg-gray-800 shadow-lg max-h-60 rounded-md py-1 text-base ring-1 ring-black dark:ring-gray-700 ring-opacity-5 overflow-auto focus:outline-none">
                                                @foreach($osSearchResults as $os)
                                                    <button 
                                                        type="button"
                                                        wire:click="selectServiceOrder({{ $os->id }})"
                                                        class="w-full text-left px-4 py-2 hover:bg-indigo-50 dark:hover:bg-indigo-900/30 hover:text-indigo-900 dark:hover:text-indigo-300 cursor-pointer"
                                                    >
                                                        <div class="font-medium text-gray-900 dark:text-gray-100">{{ $os->number }}</div>
                                                        <div class="text-sm text-gray-500 dark:text-gray-400">{{ $os->client_name }}</div>
                                                    </button>
                                                @endforeach
                                            </div>
                                        @endif
                                    @endif
                                </div>
                                <x-input-error for="service_order_id" class="mt-2" />
                            </div>
                            
                            <!-- Número -->
                            <div class="col-span-2">
                                <x-label for="number" value="{{ __('Número da Requisição') }}" />
                                <div class="mt-1 flex max-w-lg rounded-md">
                                    <span class="inline-flex items-center px-4 rounded-l-md border border-r-0 border-gray-300 dark:border-gray-600 bg-gray-50 dark:bg-gray-700 text-gray-500 dark:text-gray-400">REQ</span>
                                    <x-input id="number" type="text" class="rounded-none rounded-r-md bg-gray-50 dark:bg-gray-700" wire:model="number" readonly />
                                </div>
                                <x-input-error for="number" class="mt-2" />
                            </div>

                            <!-- Funcionário Requisitante -->
                            <div class="col-span-2">
                                <x-label for="employee_id" value="{{ __('Funcionário Requisitante') }}" />
                                <x-select id="employee_id" class="mt-1 block w-full" wire:model="employee_id">
                                    <option value="">Selecione um funcionário</option>
                                    @foreach($employees as $employee)
                                        <option value="{{ $employee->id }}">{{ $employee->user->name }} - {{ $employee->position }}</option>
                                    @endforeach
                                </x-select>
                                <x-input-error for="employee_id" class="mt-2" />
                            </div>

                            <!-- Obra (em andamento) -->
                            <div class="col-span-2">
                                <x-label for="projectSearch" value="{{ __('Obra (em andamento)') }}" />
                                <div class="mt-1 relative">
                                    @if($selectedProject)
                                        <div class="flex items-center justify-between p-3 bg-gray-50 dark:bg-gray-700 border border-gray-300 dark:border-gray-600 rounded-md">
                                            <div>
                                                <div class="font-medium text-gray-900 dark:text-gray-100">{{ $selectedProject->name }}</div>
                                                <div class="text-sm text-gray-500 dark:text-gray-400">{{ $selectedProject->code }}</div>
                                            </div>
                                            <button type="button" wire:click="clearProject" class="text-red-600 dark:text-red-400 hover:text-red-900 dark:hover:text-red-300">
                                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                                                </svg>
                                            </button>
                                        </div>
                                    @else
                                        <x-input 
                                            id="projectSearch" 
                                            type="text" 
                                            class="block w-full" 
                                            wire:model.live.debounce.300ms="projectSearch" 
                                            placeholder="Digite para buscar obra por nome ou código..." 
                                        />
                                        <input type="hidden" wire:model="project_id" />
                                        
                                        @if($projectSearchResults && count($projectSearchResults) > 0)
                                            <div class="absolute z-10 mt-1 w-full bg-white dark:bg-gray-800 shadow-lg max-h-60 rounded-md py-1 text-base ring-1 ring-black dark:ring-gray-700 ring-opacity-5 overflow-auto focus:outline-none">
                                                @foreach($projectSearchResults as $project)
                                                    <button 
                                                        type="button"
                                                        wire:click="selectProject({{ $project->id }})"
                                                        class="w-full text-left px-4 py-2 hover:bg-indigo-50 dark:hover:bg-indigo-900/30 hover:text-indigo-900 dark:hover:text-indigo-300 cursor-pointer"
                                                    >
                                                        <div class="font-medium text-gray-900 dark:text-gray-100">{{ $project->name }}</div>
                                                        <div class="text-sm text-gray-500 dark:text-gray-400">{{ $project->code }}</div>
                                                    </button>
                                                @endforeach
                                            </div>
                                        @endif
                                    @endif
                                </div>
                                <x-input-error for="project_id" class="mt-2" />
                            </div>

                            <!-- Status -->
                            <div class="col-span-2">
                                <x-label value="{{ __('Ações de Estoque') }}" />
                                <div class="mt-2 space-y-2">
                                    <label class="inline-flex items-center">
                                        <input type="checkbox" class="form-checkbox rounded border-gray-300 dark:border-gray-600 dark:bg-gray-700 text-indigo-600 shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50"
                                            wire:model="take_from_stock" />
                                        <span class="ml-2 text-gray-900 dark:text-gray-100">Retirar itens do estoque</span>
                                    </label>
                                    
                                    <label class="inline-flex items-center ml-6">
                                        <input type="checkbox" class="form-checkbox rounded border-gray-300 dark:border-gray-600 dark:bg-gray-700 text-indigo-600 shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50"
                                            wire:model="return_to_stock" />
                                        <span class="ml-2 text-gray-900 dark:text-gray-100">Devolver itens ao estoque</span>
                                    </label>
                                </div>
                                <p class="mt-1 text-sm text-gray-500 dark:text-gray-400">Selecione uma ação caso deseje atualizar o estoque agora.</p>
                                <x-input-error for="take_from_stock" class="mt-2" />
                                <x-input-error for="return_to_stock" class="mt-2" />
                            </div>

                            <!-- Observações -->
                            <div class="col-span-2">
                                <x-label for="notes" value="{{ __('Observações') }}" />
                                <x-textarea id="notes" class="mt-1 block w-full" wire:model="notes" />
                                <x-input-error for="notes" class="mt-2" />
                            </div>
                        </div>

                        <!-- Produtos -->
                        <div class="mt-6">
                            <h3 class="text-lg font-medium text-gray-900 dark:text-gray-100 mb-4">{{ __('Produtos') }}</h3>
                            
                            <!-- Busca de Produtos -->
                            <div class="mb-6">
                                <x-input type="text" 
                                    class="w-full" 
                                    wire:model.live="search" 
                                    placeholder="Digite para buscar produtos..." />
                            </div>

                            <!-- Resultados da Busca -->
                            @if($searchResults && count($searchResults) > 0)
                                <div class="mb-6 bg-white dark:bg-gray-800 shadow overflow-hidden sm:rounded-lg">
                                    <ul class="divide-y divide-gray-200 dark:divide-gray-700">
                                        @foreach($searchResults as $product)
                                            <li class="p-4 hover:bg-gray-50 dark:hover:bg-gray-700 flex justify-between items-center">
                                                <div>
                                                    <div class="font-medium text-gray-900 dark:text-gray-100">{{ $product->name }}</div>
                                                    <div class="text-sm text-gray-500 dark:text-gray-400">SKU: {{ $product->sku }}</div>
                                                </div>
                                                <button type="button" 
                                                    wire:click="selectProduct({{ $product->id }})"
                                                    class="bg-green-600 dark:bg-green-700 text-white p-2 rounded-full hover:bg-green-700 dark:hover:bg-green-600">
                                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
                                                    </svg>
                                                </button>
                                            </li>
                                        @endforeach
                                    </ul>
                                </div>
                            @endif

                            <!-- Produtos Selecionados -->
                            <div class="bg-white dark:bg-gray-800 shadow overflow-hidden sm:rounded-lg">
                                <ul class="divide-y divide-gray-200 dark:divide-gray-700">
                                    @forelse($selectedProducts as $index => $product)
                                        <li class="p-4 flex items-center justify-between gap-4">
                                            <div class="flex-1">
                                                <div class="font-medium text-gray-900 dark:text-gray-100">{{ $product['name'] }}</div>
                                                <div class="text-sm text-gray-500 dark:text-gray-400">SKU: {{ $product['sku'] }}</div>
                                            </div>
                                            <div class="w-32">
                                                <x-input type="number" 
                                                    class="block w-full" 
                                                    wire:model="selectedProducts.{{ $index }}.quantity"
                                                    min="1" 
                                                    placeholder="Qtd" />
                                            </div>
                                            <button type="button" 
                                                wire:click="removeProduct({{ $index }})" 
                                                class="text-red-600 dark:text-red-400 hover:text-red-900 dark:hover:text-red-300 p-2">
                                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                                                </svg>
                                            </button>
                                        </li>
                                    @empty
                                        <li class="p-4 text-center text-gray-500 dark:text-gray-400">
                                            Nenhum produto selecionado
                                        </li>
                                    @endforelse
                                </ul>
                            </div>
                        </div>

                        <div class="mt-6 flex justify-end gap-4">
                            <a href="{{ route('material-requests.index') }}" class="inline-flex items-center px-4 py-2 bg-gray-800 dark:bg-gray-700 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-gray-700 dark:hover:bg-gray-600 focus:bg-gray-700 active:bg-gray-900 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 transition ease-in-out duration-150">
                                {{ __('Cancelar') }}
                            </a>
                            <x-button-loading>
                                {{ __('Salvar') }}
                            </x-button-loading>
                        </div>
                    </form>
</div>
