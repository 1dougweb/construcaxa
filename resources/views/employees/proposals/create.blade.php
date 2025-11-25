<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('Nova Proposta para ') }} {{ $employee->user->name }}
        </h2>
    </x-slot>

    <div class="py-6">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-xl sm:rounded-lg">
                <div class="p-6 lg:p-8">
                    <form action="{{ route('employees.proposals.store', $employee) }}" method="POST" id="proposal-form">
                        @csrf

                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">
                            <!-- Obra -->
                            <div>
                                <x-label for="project_id" value="{{ __('Obra (Opcional)') }}" />
                                <select id="project_id" name="project_id" class="mt-1 block w-full border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-gray-100 rounded-md shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                                    <option value="">Selecione uma obra</option>
                                    @foreach($projects as $project)
                                        <option value="{{ $project->id }}" {{ old('project_id') == $project->id ? 'selected' : '' }}>
                                            {{ $project->name }}
                                        </option>
                                    @endforeach
                                </select>
                                <x-input-error for="project_id" class="mt-2" />
                            </div>

                            <!-- Valor da Hora -->
                            <div>
                                <x-label for="hourly_rate" value="{{ __('Valor da Hora (R$)') }}" />
                                <x-input id="hourly_rate" type="number" step="0.01" class="mt-1 block w-full" name="hourly_rate" :value="old('hourly_rate')" required />
                                <x-input-error for="hourly_rate" class="mt-2" />
                            </div>

                            <!-- Tipo de Contrato -->
                            <div>
                                <x-label for="contract_type" value="{{ __('Tipo de Contrato') }}" />
                                <select id="contract_type" name="contract_type" class="mt-1 block w-full border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-gray-100 rounded-md shadow-sm focus:border-indigo-500 focus:ring-indigo-500" required>
                                    <option value="fixed_days" {{ old('contract_type') == 'fixed_days' ? 'selected' : '' }}>Dias Determinados</option>
                                    <option value="indefinite" {{ old('contract_type') == 'indefinite' ? 'selected' : '' }}>Indeterminado</option>
                                </select>
                                <x-input-error for="contract_type" class="mt-2" />
                            </div>
                        </div>

                        <!-- Campos condicionais para dias determinados -->
                        <div id="fixed-days-fields" class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-6" style="display: none;">
                            <div>
                                <x-label for="days" value="{{ __('Número de Dias') }}" />
                                <x-input id="days" type="number" class="mt-1 block w-full" name="days" :value="old('days')" />
                                <x-input-error for="days" class="mt-2" />
                            </div>
                            <div>
                                <x-label for="start_date" value="{{ __('Data de Início') }}" />
                                <x-input id="start_date" type="date" class="mt-1 block w-full" name="start_date" :value="old('start_date')" />
                                <x-input-error for="start_date" class="mt-2" />
                            </div>
                            <div>
                                <x-label for="end_date" value="{{ __('Data de Término') }}" />
                                <x-input id="end_date" type="date" class="mt-1 block w-full" name="end_date" :value="old('end_date')" />
                                <x-input-error for="end_date" class="mt-2" />
                            </div>
                        </div>

                        <!-- Itens da Proposta -->
                        <div class="mb-6">
                            <div class="flex justify-between items-center mb-4">
                                <h3 class="text-lg font-medium text-gray-900 dark:text-gray-100">Itens da Proposta</h3>
                                <button type="button" onclick="addItem()" class="inline-flex items-center px-3 py-2 bg-green-600 dark:bg-green-700 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-green-700 dark:hover:bg-green-600">
                                    <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
                                    </svg>
                                    Adicionar Item
                                </button>
                            </div>

                            <div id="items-container">
                                <!-- Itens serão adicionados aqui via JavaScript -->
                            </div>
                            <x-input-error for="items" class="mt-2" />
                        </div>

                        <!-- Observações -->
                        <div class="mb-6">
                            <x-label for="observations" value="{{ __('Observações') }}" />
                            <textarea id="observations" name="observations" class="mt-1 block w-full border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-gray-100 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm" rows="4">{{ old('observations') }}</textarea>
                            <x-input-error for="observations" class="mt-2" />
                        </div>

                        <div class="flex justify-end gap-4">
                            <a href="{{ route('employees.proposals.index', $employee) }}" class="inline-flex items-center px-4 py-2 bg-gray-300 dark:bg-gray-600 border border-transparent rounded-md font-semibold text-xs text-gray-700 dark:text-gray-200 uppercase tracking-widest hover:bg-gray-400 dark:hover:bg-gray-500">
                                {{ __('Cancelar') }}
                            </a>
                            <x-button-loading variant="primary" type="submit">
                                {{ __('Criar Proposta e Enviar Email') }}
                            </x-button-loading>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <script>
        let itemIndex = 0;
        const laborTypes = @json($laborTypes);
        const services = @json($services);

        function addItem(itemType = null, laborTypeId = null, serviceId = null, quantity = '', unitPrice = '') {
            const container = document.getElementById('items-container');
            const itemDiv = document.createElement('div');
            itemDiv.className = 'item-row mb-4 p-4 border border-gray-300 dark:border-gray-600 rounded-lg';
            itemDiv.dataset.index = itemIndex;

            itemDiv.innerHTML = `
                <div class="grid grid-cols-1 md:grid-cols-5 gap-4">
                    <div>
                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Tipo</label>
                        <select name="items[${itemIndex}][item_type]" class="item-type-select block w-full border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-gray-100 rounded-md shadow-sm" required>
                            <option value="labor" ${itemType === 'labor' ? 'selected' : ''}>Mão de Obra</option>
                            <option value="service" ${itemType === 'service' ? 'selected' : ''}>Serviço</option>
                        </select>
                    </div>
                    <div class="labor-type-field">
                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Tipo de Mão de Obra</label>
                        <select name="items[${itemIndex}][labor_type_id]" class="labor-type-select block w-full border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-gray-100 rounded-md shadow-sm">
                            <option value="">Selecione</option>
                            ${laborTypes.map(lt => `<option value="${lt.id}" ${laborTypeId == lt.id ? 'selected' : ''}>${lt.name}</option>`).join('')}
                        </select>
                    </div>
                    <div class="service-field" style="display: none;">
                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Serviço</label>
                        <select name="items[${itemIndex}][service_id]" class="service-select block w-full border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-gray-100 rounded-md shadow-sm">
                            <option value="">Selecione</option>
                            ${services.map(s => `<option value="${s.id}" ${serviceId == s.id ? 'selected' : ''}>${s.name}</option>`).join('')}
                        </select>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Quantidade</label>
                        <input type="number" step="0.01" name="items[${itemIndex}][quantity]" value="${quantity}" class="block w-full border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-gray-100 rounded-md shadow-sm" required>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Preço Unitário (R$)</label>
                        <input type="number" step="0.01" name="items[${itemIndex}][unit_price]" value="${unitPrice}" class="block w-full border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-gray-100 rounded-md shadow-sm" required>
                    </div>
                </div>
                <div class="mt-2 flex justify-end">
                    <button type="button" onclick="removeItem(this)" class="text-red-600 dark:text-red-400 hover:text-red-900 dark:hover:text-red-300 text-sm">
                        Remover
                    </button>
                </div>
            `;

            container.appendChild(itemDiv);
            
            // Adicionar event listener para mudança de tipo
            const typeSelect = itemDiv.querySelector('.item-type-select');
            typeSelect.addEventListener('change', function() {
                toggleItemFields(itemDiv, this.value);
            });
            
            // Inicializar campos baseado no tipo selecionado
            toggleItemFields(itemDiv, typeSelect.value);
            
            itemIndex++;
        }

        function toggleItemFields(itemDiv, itemType) {
            const laborField = itemDiv.querySelector('.labor-type-field');
            const serviceField = itemDiv.querySelector('.service-field');
            const laborSelect = itemDiv.querySelector('.labor-type-select');
            const serviceSelect = itemDiv.querySelector('.service-select');

            if (itemType === 'labor') {
                laborField.style.display = 'block';
                serviceField.style.display = 'none';
                laborSelect.required = true;
                serviceSelect.required = false;
                serviceSelect.value = '';
            } else {
                laborField.style.display = 'none';
                serviceField.style.display = 'block';
                laborSelect.required = false;
                serviceSelect.required = true;
                laborSelect.value = '';
            }
        }

        function removeItem(button) {
            button.closest('.item-row').remove();
        }

        // Mostrar/ocultar campos de dias determinados
        document.getElementById('contract_type').addEventListener('change', function() {
            const fixedDaysFields = document.getElementById('fixed-days-fields');
            if (this.value === 'fixed_days') {
                fixedDaysFields.style.display = 'grid';
                document.getElementById('days').required = true;
                document.getElementById('start_date').required = true;
                document.getElementById('end_date').required = true;
            } else {
                fixedDaysFields.style.display = 'none';
                document.getElementById('days').required = false;
                document.getElementById('start_date').required = false;
                document.getElementById('end_date').required = false;
            }
        });

        // Inicializar campos de dias determinados
        document.getElementById('contract_type').dispatchEvent(new Event('change'));

        // Adicionar primeiro item ao carregar
        @if(old('items'))
            @foreach(old('items') as $oldItem)
                addItem('{{ $oldItem['item_type'] ?? '' }}', '{{ $oldItem['labor_type_id'] ?? '' }}', '{{ $oldItem['service_id'] ?? '' }}', '{{ $oldItem['quantity'] ?? '' }}', '{{ $oldItem['unit_price'] ?? '' }}');
            @endforeach
        @else
            addItem();
        @endif
    </script>
</x-app-layout>

