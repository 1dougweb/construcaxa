<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Editar Orçamento') }}
        </h2>
    </x-slot>

    <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
        <form action="{{ route('budgets.update', $budget) }}" method="POST" id="budgetForm">
            @csrf
            @method('PUT')

            <div class="bg-white shadow rounded-md p-6 space-y-6">
                <!-- Informações básicas -->
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Cliente *</label>
                        <select name="client_id" id="client_id" required class="w-full border-gray-300 rounded-md">
                            <option value="">Selecione um cliente</option>
                            @foreach($clients as $client)
                                <option value="{{ $client->id }}" {{ old('client_id', $budget->client_id) == $client->id ? 'selected' : '' }}>
                                    {{ $client->name }} ({{ $client->email }})
                                </option>
                            @endforeach
                        </select>
                        @error('client_id')<p class="text-red-500 text-xs mt-1">{{ $message }}</p>@enderror
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Vistoria Técnica</label>
                        <select name="inspection_id" id="inspection_id" class="w-full border-gray-300 rounded-md">
                            <option value="">Nenhuma vistoria selecionada</option>
                            @if($budget->inspection_id)
                                <option value="{{ $budget->inspection_id }}" selected>
                                    Vistoria #{{ $budget->inspection_id }}
                                </option>
                            @endif
                        </select>
                        <p class="text-xs text-gray-500 mt-1">Nota: Este campo está desabilitado. Use Vistorias Técnicas para criar novas vistorias.</p>
                        @error('inspection_id')<p class="text-red-500 text-xs mt-1">{{ $message }}</p>@enderror
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Versão *</label>
                        <input type="number" name="version" value="{{ old('version', $budget->version) }}" min="1" required class="w-full border-gray-300 rounded-md">
                        @error('version')<p class="text-red-500 text-xs mt-1">{{ $message }}</p>@enderror
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Status *</label>
                        <select name="status" required class="w-full border-gray-300 rounded-md">
                            @foreach(\App\Models\ProjectBudget::getStatusOptions() as $value => $label)
                                <option value="{{ $value }}" {{ old('status', $budget->status) == $value ? 'selected' : '' }}>
                                    {{ $label }}
                                </option>
                            @endforeach
                        </select>
                        @error('status')<p class="text-red-500 text-xs mt-1">{{ $message }}</p>@enderror
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Desconto (R$)</label>
                        <input type="number" name="discount" value="{{ old('discount', $budget->discount) }}" step="0.01" min="0" class="w-full border-gray-300 rounded-md">
                        @error('discount')<p class="text-red-500 text-xs mt-1">{{ $message }}</p>@enderror
                    </div>
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Observações</label>
                    <textarea name="notes" rows="3" class="w-full border-gray-300 rounded-md">{{ old('notes', $budget->notes) }}</textarea>
                    @error('notes')<p class="text-red-500 text-xs mt-1">{{ $message }}</p>@enderror
                </div>

                <!-- Itens do orçamento -->
                <div>
                    <div class="flex items-center justify-between mb-4">
                        <h3 class="text-lg font-medium text-gray-900">Itens do Orçamento</h3>
                        <div class="flex space-x-2">
                            <button type="button" onclick="addItem('product')" class="px-3 py-1 bg-blue-600 text-white rounded-md text-sm hover:bg-blue-700">
                                <i class="bi bi-box mr-1"></i> Adicionar Produto
                            </button>
                            <button type="button" onclick="addItem('service')" class="px-3 py-1 bg-green-600 text-white rounded-md text-sm hover:bg-green-700">
                                <i class="bi bi-tools mr-1"></i> Adicionar Serviço
                            </button>
                            <button type="button" onclick="addItem('labor')" class="px-3 py-1 bg-purple-600 text-white rounded-md text-sm hover:bg-purple-700">
                                <i class="bi bi-people mr-1"></i> Adicionar Mão de Obra
                            </button>
                        </div>
                    </div>

                    <div id="itemsContainer" class="space-y-4">
                        <!-- Itens serão adicionados aqui via JavaScript -->
                    </div>
                    @error('items')<p class="text-red-500 text-xs mt-1">{{ $message }}</p>@enderror
                </div>

                <!-- Totais -->
                <div class="border-t pt-4">
                    <div class="flex justify-end">
                        <div class="w-64 space-y-2">
                            <div class="flex justify-between">
                                <span class="text-gray-600">Subtotal:</span>
                                <span id="subtotal" class="font-medium">R$ {{ number_format($budget->subtotal, 2, ',', '.') }}</span>
                            </div>
                            <div class="flex justify-between">
                                <span class="text-gray-600">Desconto:</span>
                                <span id="discount-display" class="font-medium">R$ {{ number_format($budget->discount ?? 0, 2, ',', '.') }}</span>
                            </div>
                            <div class="flex justify-between border-t pt-2">
                                <span class="font-semibold text-gray-900">Total:</span>
                                <span id="total" class="font-semibold text-lg text-indigo-600">R$ {{ number_format($budget->total, 2, ',', '.') }}</span>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Botões -->
                <div class="flex justify-end gap-3 pt-4 border-t">
                    <a href="{{ route('budgets.index') }}" class="px-4 py-2 border border-gray-300 rounded-md text-gray-700 hover:bg-gray-50">
                        Cancelar
                    </a>
                    <button type="submit" class="px-4 py-2 bg-indigo-600 text-white rounded-md hover:bg-indigo-700">
                        Atualizar Orçamento
                    </button>
                </div>
            </div>
        </form>
    </div>

    @php
        $products = \App\Models\Product::orderBy('name')->get();
        
        // Map products for JavaScript
        $productsData = $products->map(function($p) {
            return ['id' => $p->id, 'name' => $p->name];
        })->values();
        
        // Map services for JavaScript (using data from controller)
        $servicesData = $services->map(function($s) {
            return [
                'id' => $s->id,
                'name' => $s->name,
                'description' => $s->description,
                'category' => $s->category ? $s->category->name : '',
                'unit_type' => $s->unit_type ?? 'hour',
                'unit_type_label' => $s->unit_type_label ?? 'Por Hora',
                'default_price' => $s->default_price ?? 0,
                'minimum_price' => $s->minimum_price ?? null,
                'maximum_price' => $s->maximum_price ?? null
            ];
        })->values();
        
        // Map labor types for JavaScript (using data from controller)
        $laborTypesData = $laborTypes->map(function($l) {
            return [
                'id' => $l->id,
                'name' => $l->name,
                'description' => $l->description,
                'skill_level' => $l->skill_level ?? 'junior',
                'skill_level_label' => $l->skill_level_label ?? 'Júnior',
                'hourly_rate' => $l->hourly_rate ?? 0,
                'overtime_rate' => $l->overtime_rate ?? 0
            ];
        })->values();
        
        // Map existing items for JavaScript
        $existingItemsData = $budget->items->map(function($item) {
            return [
                'item_type' => $item->item_type ?? 'product',
                'product_id' => $item->product_id,
                'service_id' => $item->service_id,
                'labor_type_id' => $item->labor_type_id,
                'description' => $item->description,
                'quantity' => $item->quantity,
                'hours' => $item->hours ?? 0,
                'overtime_hours' => $item->overtime_hours ?? 0,
                'unit_price' => $item->unit_price,
            ];
        })->values();
    @endphp

    @push('scripts')
    <script>
        let itemIndex = 0;
        const products = @json($productsData);
        const services = @json($servicesData);
        const laborTypes = @json($laborTypesData);
        const existingItems = @json($existingItemsData);

        function addItem(itemType = 'product', item = null) {
            const container = document.getElementById('itemsContainer');
            const index = itemIndex++;
            
            let itemHtml = '';
            const itemTypeValue = item ? item.item_type || itemType : itemType;
            
            if (itemTypeValue === 'product') {
                itemHtml = createProductItem(index, item);
            } else if (itemTypeValue === 'service') {
                itemHtml = createServiceItem(index, item);
            } else if (itemTypeValue === 'labor') {
                itemHtml = createLaborItem(index, item);
            }
            
            container.insertAdjacentHTML('beforeend', itemHtml);
            
            // Setup search for the new item
            const newItem = container.lastElementChild;
            if (itemTypeValue === 'product') {
                setupProductSearch(newItem);
            } else if (itemTypeValue === 'service') {
                setupServiceSearch(newItem);
            } else if (itemTypeValue === 'labor') {
                setupLaborSearch(newItem);
            }
        }

        function createProductItem(index, item = null) {
            return `
                <div class="border rounded-md p-4 bg-blue-50" data-item-index="${index}">
                    <div class="flex justify-between items-center mb-2">
                        <span class="text-sm font-medium text-blue-800"><i class="bi bi-box mr-1"></i> Produto</span>
                        <button type="button" onclick="removeItem(this)" class="text-red-600 hover:text-red-800 text-sm">
                            <i class="bi bi-trash"></i> Remover
                        </button>
                    </div>
                    <input type="hidden" name="items[${index}][item_type]" value="product">
                    <div class="grid grid-cols-1 md:grid-cols-5 gap-4">
                        <div class="md:col-span-2">
                            <label class="block text-xs text-gray-600 mb-1">Produto</label>
                            <div class="relative">
                                <input type="text" 
                                       class="product-search w-full border-gray-300 rounded-md text-sm" 
                                       placeholder="Buscar produto..."
                                       data-index="${index}">
                                <input type="hidden" name="items[${index}][product_id]" value="${item ? (item.product_id || '') : ''}">
                                <div class="product-results absolute z-10 w-full bg-white border border-gray-300 rounded-md shadow-lg hidden max-h-48 overflow-y-auto"></div>
                            </div>
                        </div>
                        <div class="md:col-span-2">
                            <label class="block text-xs text-gray-600 mb-1">Descrição *</label>
                            <input type="text" name="items[${index}][description]" value="${item ? (item.description || '') : ''}" required class="w-full border-gray-300 rounded-md text-sm">
                        </div>
                        <div>
                            <label class="block text-xs text-gray-600 mb-1">Quantidade *</label>
                            <input type="number" name="items[${index}][quantity]" value="${item ? (item.quantity || '') : ''}" step="0.001" min="0" required onchange="calculateTotals()" class="w-full border-gray-300 rounded-md text-sm">
                        </div>
                        <div>
                            <label class="block text-xs text-gray-600 mb-1">Preço Unit. *</label>
                            <input type="number" name="items[${index}][unit_price]" value="${item ? (item.unit_price || '') : ''}" step="0.01" min="0" required onchange="calculateTotals()" class="w-full border-gray-300 rounded-md text-sm">
                        </div>
                    </div>
                </div>
            `;
        }

        function createServiceItem(index, item = null) {
            return `
                <div class="border rounded-md p-4 bg-green-50" data-item-index="${index}">
                    <div class="flex justify-between items-center mb-2">
                        <span class="text-sm font-medium text-green-800"><i class="bi bi-tools mr-1"></i> Serviço</span>
                        <button type="button" onclick="removeItem(this)" class="text-red-600 hover:text-red-800 text-sm">
                            <i class="bi bi-trash"></i> Remover
                        </button>
                    </div>
                    <input type="hidden" name="items[${index}][item_type]" value="service">
                    <div class="grid grid-cols-1 md:grid-cols-5 gap-4">
                        <div class="md:col-span-2">
                            <label class="block text-xs text-gray-600 mb-1">Serviço</label>
                            <div class="relative">
                                <input type="text" 
                                       class="service-search w-full border-gray-300 rounded-md text-sm" 
                                       placeholder="Buscar serviço..."
                                       data-index="${index}">
                                <input type="hidden" name="items[${index}][service_id]" value="${item ? (item.service_id || '') : ''}">
                                <div class="service-results absolute z-10 w-full bg-white border border-gray-300 rounded-md shadow-lg hidden max-h-48 overflow-y-auto"></div>
                            </div>
                        </div>
                        <div class="md:col-span-2">
                            <label class="block text-xs text-gray-600 mb-1">Descrição *</label>
                            <input type="text" name="items[${index}][description]" value="${item ? (item.description || '') : ''}" required class="w-full border-gray-300 rounded-md text-sm">
                        </div>
                        <div>
                            <label class="block text-xs text-gray-600 mb-1">Quantidade *</label>
                            <input type="number" name="items[${index}][quantity]" value="${item ? (item.quantity || '') : ''}" step="0.001" min="0" required onchange="calculateTotals()" class="w-full border-gray-300 rounded-md text-sm">
                        </div>
                        <div>
                            <label class="block text-xs text-gray-600 mb-1">Preço Unit. *</label>
                            <input type="number" name="items[${index}][unit_price]" value="${item ? (item.unit_price || '') : ''}" step="0.01" min="0" required onchange="calculateTotals()" class="w-full border-gray-300 rounded-md text-sm">
                        </div>
                    </div>
                </div>
            `;
        }

        function createLaborItem(index, item = null) {
            return `
                <div class="border rounded-md p-4 bg-purple-50" data-item-index="${index}">
                    <div class="flex justify-between items-center mb-2">
                        <span class="text-sm font-medium text-purple-800"><i class="bi bi-people mr-1"></i> Mão de Obra</span>
                        <button type="button" onclick="removeItem(this)" class="text-red-600 hover:text-red-800 text-sm">
                            <i class="bi bi-trash"></i> Remover
                        </button>
                    </div>
                    <input type="hidden" name="items[${index}][item_type]" value="labor">
                    <input type="hidden" name="items[${index}][unit_price]" value="${item ? (item.unit_price || '') : ''}" class="labor-unit-price">
                    <div class="grid grid-cols-1 md:grid-cols-6 gap-4">
                        <div class="md:col-span-2">
                            <label class="block text-xs text-gray-600 mb-1">Tipo de Mão de Obra</label>
                            <div class="relative">
                                <input type="text" 
                                       class="labor-search w-full border-gray-300 rounded-md text-sm" 
                                       placeholder="Buscar tipo..."
                                       data-index="${index}">
                                <input type="hidden" name="items[${index}][labor_type_id]" value="${item ? (item.labor_type_id || '') : ''}">
                                <div class="labor-results absolute z-10 w-full bg-white border border-gray-300 rounded-md shadow-lg hidden max-h-48 overflow-y-auto"></div>
                            </div>
                        </div>
                        <div class="md:col-span-2">
                            <label class="block text-xs text-gray-600 mb-1">Descrição *</label>
                            <input type="text" name="items[${index}][description]" value="${item ? (item.description || '') : ''}" required class="w-full border-gray-300 rounded-md text-sm">
                        </div>
                        <div>
                            <label class="block text-xs text-gray-600 mb-1">Horas *</label>
                            <input type="number" name="items[${index}][hours]" value="${item ? (item.hours || '') : ''}" step="0.25" min="0" required onchange="calculateTotals()" class="w-full border-gray-300 rounded-md text-sm">
                        </div>
                        <div>
                            <label class="block text-xs text-gray-600 mb-1">Horas Extra</label>
                            <input type="number" name="items[${index}][overtime_hours]" value="${item ? (item.overtime_hours || 0) : 0}" step="0.25" min="0" onchange="calculateTotals()" class="w-full border-gray-300 rounded-md text-sm">
                        </div>
                    </div>
                </div>
            `;
        }

        function removeItem(button) {
            button.closest('[data-item-index]').remove();
            calculateTotals();
        }

        function calculateTotals() {
            const form = document.getElementById('budgetForm');
            const items = form.querySelectorAll('[data-item-index]');
            let subtotal = 0;

            items.forEach(item => {
                const itemType = item.querySelector('[name*="[item_type]"]')?.value || 'product';
                
                if (itemType === 'labor') {
                    const hours = parseFloat(item.querySelector('[name*="[hours]"]')?.value) || 0;
                    const overtimeHours = parseFloat(item.querySelector('[name*="[overtime_hours]"]')?.value) || 0;
                    const unitPrice = parseFloat(item.querySelector('[name*="[unit_price]"]')?.value) || 0;
                    const laborTypeId = item.querySelector('input[name*="[labor_type_id]"]')?.value;
                    
                    // Find labor type to get overtime rate
                    const laborType = laborTypes.find(lt => lt.id == laborTypeId);
                    const overtimeRate = laborType ? laborType.overtime_rate : unitPrice * 1.5;
                    
                    subtotal += (hours * unitPrice) + (overtimeHours * overtimeRate);
                } else if (itemType === 'service') {
                    const quantity = parseFloat(item.querySelector('[name*="[quantity]"]')?.value) || 0;
                    const unitPrice = parseFloat(item.querySelector('[name*="[unit_price]"]')?.value) || 0;
                    const serviceId = item.querySelector('input[name*="[service_id]"]')?.value;
                    
                    // Find service to get unit type
                    const service = services.find(s => s.id == serviceId);
                    if (service) {
                        // Calculate based on unit type
                        if (service.unit_type === 'fixed') {
                            // Fixed price regardless of quantity
                            subtotal += unitPrice;
                        } else {
                            // Per hour or per unit
                            subtotal += quantity * unitPrice;
                        }
                    } else {
                        // Fallback to simple calculation
                        subtotal += quantity * unitPrice;
                    }
                } else {
                    // Product
                    const quantity = parseFloat(item.querySelector('[name*="[quantity]"]')?.value) || 0;
                    const unitPrice = parseFloat(item.querySelector('[name*="[unit_price]"]')?.value) || 0;
                    subtotal += quantity * unitPrice;
                }
            });

            const discount = parseFloat(form.querySelector('[name="discount"]').value) || 0;
            const total = subtotal - discount;

            document.getElementById('subtotal').textContent = 'R$ ' + subtotal.toFixed(2).replace('.', ',');
            document.getElementById('discount-display').textContent = 'R$ ' + discount.toFixed(2).replace('.', ',');
            document.getElementById('total').textContent = 'R$ ' + total.toFixed(2).replace('.', ',');
        }

        // Product search functionality
        function setupProductSearch(container) {
            const searchInput = container.querySelector('.product-search');
            const hiddenInput = container.querySelector('input[type="hidden"]');
            const resultsDiv = container.querySelector('.product-results');
            
            let searchTimeout;
            
            searchInput.addEventListener('input', function() {
                const query = this.value.trim();
                
                clearTimeout(searchTimeout);
                
                if (query.length < 2) {
                    resultsDiv.classList.add('hidden');
                    return;
                }
                
                searchTimeout = setTimeout(() => {
                    // Filter products based on search query
                    const filteredProducts = products.filter(product => 
                        product.name.toLowerCase().includes(query.toLowerCase())
                    );
                    
                    if (filteredProducts.length > 0) {
                        let resultsHtml = '';
                        filteredProducts.slice(0, 10).forEach(product => {
                            resultsHtml += `
                                <div class="product-option p-2 hover:bg-gray-100 cursor-pointer border-b last:border-b-0" 
                                     data-id="${product.id}" data-name="${product.name}">
                                    <div class="font-medium text-sm">${product.name}</div>
                                </div>
                            `;
                        });
                        resultsDiv.innerHTML = resultsHtml;
                        resultsDiv.classList.remove('hidden');
                        
                        // Add click handlers for results
                        resultsDiv.querySelectorAll('.product-option').forEach(option => {
                            option.addEventListener('click', function() {
                                const productId = this.dataset.id;
                                const productName = this.dataset.name;
                                
                                searchInput.value = productName;
                                hiddenInput.value = productId;
                                resultsDiv.classList.add('hidden');
                                
                                // Auto-fill description if empty
                                const descriptionInput = container.querySelector('input[name*="[description]"]');
                                if (!descriptionInput.value) {
                                    descriptionInput.value = productName;
                                }
                            });
                        });
                    } else {
                        resultsDiv.innerHTML = '<div class="p-2 text-gray-500 text-sm">Nenhum produto encontrado</div>';
                        resultsDiv.classList.remove('hidden');
                    }
                }, 300);
            });
            
            // Hide results when clicking outside
            document.addEventListener('click', function(e) {
                if (!container.contains(e.target)) {
                    resultsDiv.classList.add('hidden');
                }
            });
            
            // Set initial value if product is selected
            const initialProductId = hiddenInput.value;
            if (initialProductId) {
                const product = products.find(p => p.id == initialProductId);
                if (product) {
                    searchInput.value = product.name;
                }
            }
        }

        // Service search functionality
        function setupServiceSearch(container) {
            const searchInput = container.querySelector('.service-search');
            const hiddenInput = container.querySelector('input[name*="[service_id]"]');
            const resultsDiv = container.querySelector('.service-results');
            
            let searchTimeout;
            
            searchInput.addEventListener('input', function() {
                const query = this.value.trim();
                
                clearTimeout(searchTimeout);
                
                if (query.length < 2) {
                    resultsDiv.classList.add('hidden');
                    return;
                }
                
                searchTimeout = setTimeout(() => {
                    const filteredServices = services.filter(service => 
                        service.name.toLowerCase().includes(query.toLowerCase())
                    );
                    
                    if (filteredServices.length > 0) {
                        let resultsHtml = '';
                        filteredServices.slice(0, 10).forEach(service => {
                            const priceDisplay = service.unit_type === 'fixed' 
                                ? `R$ ${parseFloat(service.default_price).toFixed(2)} (Preço Fixo)`
                                : `R$ ${parseFloat(service.default_price).toFixed(2)}/${service.unit_type_label}`;
                            resultsHtml += `
                                <div class="service-option p-2 hover:bg-gray-100 cursor-pointer border-b last:border-b-0" 
                                     data-id="${service.id}" 
                                     data-name="${service.name}" 
                                     data-price="${service.default_price}"
                                     data-unit-type="${service.unit_type}">
                                    <div class="font-medium text-sm">${service.name}</div>
                                    <div class="text-xs text-gray-500">${priceDisplay}</div>
                                    ${service.category ? `<div class="text-xs text-gray-400">${service.category}</div>` : ''}
                                </div>
                            `;
                        });
                        resultsDiv.innerHTML = resultsHtml;
                        resultsDiv.classList.remove('hidden');
                        
                        resultsDiv.querySelectorAll('.service-option').forEach(option => {
                            option.addEventListener('click', function() {
                                const serviceId = this.dataset.id;
                                const serviceName = this.dataset.name;
                                const servicePrice = this.dataset.price;
                                const unitType = this.dataset.unitType;
                                
                                searchInput.value = serviceName;
                                hiddenInput.value = serviceId;
                                resultsDiv.classList.add('hidden');
                                
                                const descriptionInput = container.querySelector('input[name*="[description]"]');
                                const priceInput = container.querySelector('input[name*="[unit_price]"]');
                                
                                if (!descriptionInput.value) {
                                    descriptionInput.value = serviceName;
                                }
                                if (!priceInput.value) {
                                    priceInput.value = servicePrice;
                                }
                                
                                calculateTotals();
                            });
                        });
                    } else {
                        resultsDiv.innerHTML = '<div class="p-2 text-gray-500 text-sm">Nenhum serviço encontrado</div>';
                        resultsDiv.classList.remove('hidden');
                    }
                }, 300);
            });
            
            document.addEventListener('click', function(e) {
                if (!container.contains(e.target)) {
                    resultsDiv.classList.add('hidden');
                }
            });
        }

        // Labor search functionality
        function setupLaborSearch(container) {
            const searchInput = container.querySelector('.labor-search');
            const hiddenInput = container.querySelector('input[name*="[labor_type_id]"]');
            const resultsDiv = container.querySelector('.labor-results');
            
            let searchTimeout;
            
            searchInput.addEventListener('input', function() {
                const query = this.value.trim();
                
                clearTimeout(searchTimeout);
                
                if (query.length < 2) {
                    resultsDiv.classList.add('hidden');
                    return;
                }
                
                searchTimeout = setTimeout(() => {
                    const filteredLaborTypes = laborTypes.filter(laborType => 
                        laborType.name.toLowerCase().includes(query.toLowerCase())
                    );
                    
                    if (filteredLaborTypes.length > 0) {
                        let resultsHtml = '';
                        filteredLaborTypes.slice(0, 10).forEach(laborType => {
                            resultsHtml += `
                                <div class="labor-option p-2 hover:bg-gray-100 cursor-pointer border-b last:border-b-0" 
                                     data-id="${laborType.id}" 
                                     data-name="${laborType.name}" 
                                     data-rate="${laborType.hourly_rate}"
                                     data-overtime-rate="${laborType.overtime_rate}">
                                    <div class="font-medium text-sm">${laborType.name}</div>
                                    <div class="text-xs text-gray-500">
                                        Normal: R$ ${parseFloat(laborType.hourly_rate).toFixed(2)}/h | 
                                        Extra: R$ ${parseFloat(laborType.overtime_rate).toFixed(2)}/h
                                    </div>
                                    <div class="text-xs text-gray-400">${laborType.skill_level_label}</div>
                                </div>
                            `;
                        });
                        resultsDiv.innerHTML = resultsHtml;
                        resultsDiv.classList.remove('hidden');
                        
                        resultsDiv.querySelectorAll('.labor-option').forEach(option => {
                            option.addEventListener('click', function() {
                                const laborId = this.dataset.id;
                                const laborName = this.dataset.name;
                                const hourlyRate = this.dataset.rate;
                                
                                searchInput.value = laborName;
                                hiddenInput.value = laborId;
                                resultsDiv.classList.add('hidden');
                                
                                // Set unit_price from hourly rate
                                const unitPriceInput = container.querySelector('.labor-unit-price');
                                if (unitPriceInput) {
                                    unitPriceInput.value = hourlyRate;
                                }
                                
                                const descriptionInput = container.querySelector('input[name*="[description]"]');
                                if (!descriptionInput.value) {
                                    descriptionInput.value = laborName;
                                }
                                
                                calculateTotals();
                            });
                        });
                    } else {
                        resultsDiv.innerHTML = '<div class="p-2 text-gray-500 text-sm">Nenhum tipo encontrado</div>';
                        resultsDiv.classList.remove('hidden');
                    }
                }, 300);
            });
            
            document.addEventListener('click', function(e) {
                if (!container.contains(e.target)) {
                    resultsDiv.classList.add('hidden');
                }
            });
        }

        // Adicionar itens existentes e calcular totais ao carregar
        document.addEventListener('DOMContentLoaded', function() {
            existingItems.forEach(item => addItem(null, item));
            if (existingItems.length === 0) {
                addItem('product');
            }
            const discountInput = document.querySelector('[name="discount"]');
            discountInput.addEventListener('input', calculateTotals);
            
            // Funcionalidade de busca de vistoria removida - sistema antigo foi descontinuado
        });
    </script>
    @endpush
</x-app-layout>