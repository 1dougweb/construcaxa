<x-app-layout>

    <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
        @if (session('error'))
            <div class="mb-4 bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded">
                {{ session('error') }}
            </div>
        @endif
        <form action="{{ route('budgets.update', $budget) }}" method="POST" id="budgetForm">
            @csrf
            @method('PUT')

            <div class="bg-white shadow rounded-md p-6 space-y-6">
                <!-- Informações básicas -->
                <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
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

                    <div class="md:col-span-2">
                        <label class="block text-sm font-medium text-gray-700 mb-1">Endereço da Obra (para o mapa)</label>
                        <input
                            type="text"
                            name="address"
                            value="{{ old('address', $budget->address) }}"
                            placeholder="Ex: Rua Exemplo, 123 - Bairro, Cidade - UF"
                            class="w-full border-gray-300 rounded-md"
                        >
                        <p class="text-xs text-gray-500 mt-1">
                            Este endereço será utilizado para localizar a obra no mapa quando o orçamento for aprovado e virar obra.
                        </p>
                        @error('address')<p class="text-red-500 text-xs mt-1">{{ $message }}</p>@enderror
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
                <div class="flex justify-between items-center gap-3 pt-4 border-t">
                    <div class="flex items-center gap-3 text-sm text-gray-500">
                        <span class="inline-flex items-center gap-1">
                            <span class="w-2 h-2 rounded-full bg-green-500"></span>
                            Versão atual: <strong class="ml-1">v{{ $budget->version }}</strong>
                        </span>
                        <span class="hidden sm:inline">|</span>
                        <span class="text-xs sm:text-sm">
                            Ao alterar a versão e salvar, o orçamento será reenviado automaticamente para o cliente.
                        </span>
                    </div>
                    <div class="flex items-center gap-3">
                    <a href="{{ route('budgets.index') }}" class="px-4 py-2 border border-gray-300 rounded-md text-gray-700 hover:bg-gray-50">
                        Cancelar
                    </a>
                    <button type="submit" class="px-4 py-2 bg-indigo-600 text-white rounded-md hover:bg-indigo-700">
                            Atualizar
                    </button>
                    </div>
                </div>
            </div>
        </form>
        
        <!-- Formulário de reenvio separado (fora do formulário principal) -->
        <div class="mt-4 bg-white shadow rounded-md p-4">
            <form action="{{ route('budgets.resend', $budget) }}" method="POST" id="resendForm" onsubmit="return confirm('Reenviar este orçamento para o cliente agora?');">
                @csrf
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-sm text-gray-700">Reenviar orçamento por e-mail</p>
                        <p class="text-xs text-gray-500 mt-1">Envia o orçamento atual por e-mail para o cliente</p>
                    </div>
                    <button type="submit" class="px-4 py-2 border border-gray-300 rounded-md text-gray-700 hover:bg-gray-50 text-sm">
                        <i class="bi bi-envelope mr-1"></i> Reenviar orçamento
                    </button>
                </div>
            </form>
        </div>
    </div>

    @php
        $products = \App\Models\Product::orderBy('name')->get();
        
        // Map products for JavaScript (mesmo padrão da tela de criação)
        $productsData = $products->map(function($p) {
            $photos = $p->photos ?? [];
            $firstPhoto = null;
            if (is_array($photos) && count($photos) > 0) {
                $firstPhoto = $photos[0];
            }
            return [
                'id' => $p->id,
                'name' => $p->name,
                'sku' => $p->sku ?? '',
                'price' => $p->price ?? 0,
                'sale_price' => $p->sale_price ?? null,
                'photo' => $firstPhoto,
            ];
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
        console.log('[Budgets/Edit] Script de edição de orçamento carregado');

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
            console.log('[Budgets/Edit] addItem', { itemType, itemTypeValue, index, hasItem: !!item });
            
            if (itemTypeValue === 'product') {
                itemHtml = createProductItem(index, item);
            } else if (itemTypeValue === 'service') {
                itemHtml = createServiceItem(index, item);
            } else if (itemTypeValue === 'labor') {
                itemHtml = createLaborItem(index, item);
            }
            
            container.insertAdjacentHTML('beforeend', itemHtml);
            console.log('[Budgets/Edit] Item HTML inserido no container', { index, itemTypeValue });
            
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
                <div class="border border-gray-200 dark:border-gray-700 rounded-md p-4 bg-blue-50 dark:bg-blue-900/20" data-item-index="${index}" data-item-type="product">
                    <div class="flex justify-between items-center mb-2">
                        <span class="text-sm font-medium text-blue-800 dark:text-blue-300"><i class="bi bi-box mr-1"></i> Produto</span>
                        <button type="button" onclick="removeItem(this)" class="text-red-600 dark:text-red-400 hover:text-red-800 dark:hover:text-red-300 text-sm">
                            <i class="bi bi-trash"></i> Remover
                        </button>
                    </div>
                    <input type="hidden" name="items[${index}][item_type]" value="product" data-item-type-field="product">
                    <div class="grid grid-cols-6 gap-3">
                        <div class="col-span-2">
                            <label class="block text-xs text-gray-600 dark:text-gray-400 mb-1">Produto</label>
                            <div class="relative">
                                <input type="text" 
                                       class="product-search w-full border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-gray-300 rounded-md text-sm" 
                                       placeholder="Buscar produto..."
                                       data-index="${index}">
                                <input type="hidden" name="items[${index}][product_id]" value="${item ? (item.product_id || '') : ''}">
                                <div class="product-results absolute z-10 w-full bg-white dark:bg-gray-800 border border-gray-300 dark:border-gray-700 rounded-md shadow-lg hidden max-h-64 overflow-y-auto mt-1"></div>
                            </div>
                        </div>
                        <div class="col-span-2">
                            <label class="block text-xs text-gray-600 dark:text-gray-400 mb-1">Descrição *</label>
                            <input type="text" name="items[${index}][description]" value="${item ? (item.description || '') : ''}" required class="w-full border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-gray-300 rounded-md text-sm">
                        </div>
                        <div>
                            <label class="block text-xs text-gray-600 dark:text-gray-400 mb-1">Quantidade *</label>
                            <input type="number" name="items[${index}][quantity]" value="${item ? (item.quantity || '') : ''}" step="0.001" min="0" required onchange="calculateTotals()" class="w-full border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-gray-300 rounded-md text-sm">
                        </div>
                        <div>
                            <label class="block text-xs text-gray-600 dark:text-gray-400 mb-1">Preço Unit. *</label>
                            <input type="number" name="items[${index}][unit_price]" value="${item ? (item.unit_price || '') : ''}" step="0.01" min="0" required onchange="calculateTotals()" class="w-full border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-gray-300 rounded-md text-sm">
                        </div>
                    </div>
                </div>
            `;
        }

        function createServiceItem(index, item = null) {
            return `
                <div class="border border-gray-200 dark:border-gray-700 rounded-md p-4 bg-green-50 dark:bg-green-900/20" data-item-index="${index}" data-item-type="service">
                    <div class="flex justify-between items-center mb-2">
                        <span class="text-sm font-medium text-green-800 dark:text-green-300"><i class="bi bi-tools mr-1"></i> Serviço</span>
                        <button type="button" onclick="removeItem(this)" class="text-red-600 dark:text-red-400 hover:text-red-800 dark:hover:text-red-300 text-sm">
                            <i class="bi bi-trash"></i> Remover
                        </button>
                    </div>
                    <input type="hidden" name="items[${index}][item_type]" value="service" data-item-type-field="service">
                    <div class="grid grid-cols-6 gap-3">
                        <div class="col-span-2">
                            <label class="block text-xs text-gray-600 dark:text-gray-400 mb-1">Serviço</label>
                            <div class="relative">
                                <input type="text" 
                                       class="service-search w-full border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-gray-300 rounded-md text-sm" 
                                       placeholder="Buscar serviço..."
                                       data-index="${index}">
                                <input type="hidden" name="items[${index}][service_id]" value="${item ? (item.service_id || '') : ''}">
                                <div class="service-results absolute z-10 w-full bg-white dark:bg-gray-800 border border-gray-300 dark:border-gray-700 rounded-md shadow-lg hidden max-h-48 overflow-y-auto mt-1"></div>
                            </div>
                        </div>
                        <div class="col-span-2">
                            <label class="block text-xs text-gray-600 dark:text-gray-400 mb-1">Descrição *</label>
                            <input type="text" name="items[${index}][description]" value="${item ? (item.description || '') : ''}" required class="w-full border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-gray-300 rounded-md text-sm">
                        </div>
                        <div>
                            <label class="block text-xs text-gray-600 dark:text-gray-400 mb-1">Quantidade *</label>
                            <input type="number" name="items[${index}][quantity]" value="${item ? (item.quantity || '') : ''}" step="0.001" min="0" required onchange="calculateTotals()" class="w-full border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-gray-300 rounded-md text-sm">
                        </div>
                        <div>
                            <label class="block text-xs text-gray-600 dark:text-gray-400 mb-1">Preço Unit. *</label>
                            <input type="number" name="items[${index}][unit_price]" value="${item ? (item.unit_price || '') : ''}" step="0.01" min="0" required onchange="calculateTotals()" class="w-full border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-gray-300 rounded-md text-sm">
                        </div>
                    </div>
                </div>
            `;
        }

        function createLaborItem(index, item = null) {
            return `
                <div class="border border-gray-200 dark:border-gray-700 rounded-md p-4 bg-purple-50 dark:bg-purple-900/20" data-item-index="${index}" data-item-type="labor">
                    <div class="flex justify-between items-center mb-2">
                        <span class="text-sm font-medium text-purple-800 dark:text-purple-300"><i class="bi bi-people mr-1"></i> Mão de Obra</span>
                        <button type="button" onclick="removeItem(this)" class="text-red-600 dark:text-red-400 hover:text-red-800 dark:hover:text-red-300 text-sm">
                            <i class="bi bi-trash"></i> Remover
                        </button>
                    </div>
                    <input type="hidden" name="items[${index}][item_type]" value="labor" data-item-type-field="labor">
                    <input type="hidden" name="items[${index}][unit_price]" value="${item ? (item.unit_price || '') : ''}" class="labor-unit-price" data-index="${index}">
                    <div class="grid grid-cols-1 md:grid-cols-6 gap-4">
                        <div class="md:col-span-2">
                            <label class="block text-xs text-gray-600 dark:text-gray-400 mb-1">Tipo de Mão de Obra</label>
                            <div class="relative">
                                <input type="text" 
                                       class="labor-search w-full border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-gray-300 rounded-md text-sm" 
                                       placeholder="Buscar tipo..."
                                       data-index="${index}">
                                <input type="hidden" name="items[${index}][labor_type_id]" value="${item ? (item.labor_type_id || '') : ''}" class="labor-type-id-input" data-index="${index}">
                                <div class="labor-results absolute z-10 w-full bg-white dark:bg-gray-800 border border-gray-300 dark:border-gray-700 rounded-md shadow-lg hidden max-h-48 overflow-y-auto"></div>
                            </div>
                        </div>
                        <div class="md:col-span-2">
                            <label class="block text-xs text-gray-600 dark:text-gray-400 mb-1">Descrição *</label>
                            <input type="text" name="items[${index}][description]" value="${item ? (item.description || '') : ''}" required class="w-full border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-gray-300 rounded-md text-sm">
                        </div>
                        <div>
                            <label class="block text-xs text-gray-600 dark:text-gray-400 mb-1">Horas *</label>
                            <input type="number" name="items[${index}][hours]" value="${item ? (item.hours || '') : ''}" step="0.25" min="0" required onchange="updateLaborUnitPrice(${index}); calculateTotals();" class="labor-hours-input w-full border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-gray-300 rounded-md text-sm" data-index="${index}">
                        </div>
                        <div>
                            <label class="block text-xs text-gray-600 dark:text-gray-400 mb-1">Horas Extra</label>
                            <input type="number" name="items[${index}][overtime_hours]" value="${item ? (item.overtime_hours || 0) : 0}" step="0.25" min="0" onchange="calculateTotals()" class="w-full border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-gray-300 rounded-md text-sm">
                        </div>
                    </div>
                </div>
            `;
        }

        function updateLaborUnitPrice(index) {
            const item = document.querySelector(`[data-item-index="${index}"]`);
            if (!item) return;
            
            const laborTypeIdInput = item.querySelector(`.labor-type-id-input[data-index="${index}"]`);
            const unitPriceInput = item.querySelector(`.labor-unit-price[data-index="${index}"]`);
            
            if (laborTypeIdInput && unitPriceInput && laborTypeIdInput.value) {
                const laborType = laborTypes.find(lt => lt.id == laborTypeIdInput.value);
                if (laborType && (!unitPriceInput.value || unitPriceInput.value === '0')) {
                    unitPriceInput.value = parseFloat(laborType.hourly_rate || 0).toFixed(2);
                    console.log('[Budgets/Edit] Unit price atualizado para labor', { index, laborTypeId: laborTypeIdInput.value, unitPrice: unitPriceInput.value });
                }
            }
        }

        function removeItem(button) {
            console.log('[Budgets/Edit] removeItem chamado');
            button.closest('[data-item-index]').remove();
            calculateTotals();
        }

        function calculateTotals() {
            console.log('[Budgets/Edit] calculateTotals chamado');
            const form = document.getElementById('budgetForm');
            const items = form.querySelectorAll('[data-item-index]');
            let subtotal = 0;

            items.forEach(item => {
                const itemType = item.querySelector('[name*="[item_type]"]')?.value || 'product';
                
                if (itemType === 'labor') {
                    const hours = parseFloat(item.querySelector('[name*="[hours]"]')?.value) || 0;
                    const overtimeHours = parseFloat(item.querySelector('[name*="[overtime_hours]"]')?.value) || 0;
                    const unitPriceInput = item.querySelector('.labor-unit-price');
                    let unitPrice = 0;
                    
                    if (unitPriceInput) {
                        unitPrice = parseFloat(unitPriceInput.value) || 0;
                    }
                    
                    if (!unitPrice || unitPrice === 0) {
                        const laborTypeId = item.querySelector('input[name*="[labor_type_id]"]')?.value;
                        const laborType = laborTypes.find(lt => lt.id == laborTypeId);
                        if (laborType) {
                            unitPrice = parseFloat(laborType.hourly_rate) || 0;
                            if (unitPriceInput) {
                                unitPriceInput.value = unitPrice.toFixed(2);
                            }
                        }
                    }
                    
                    const laborTypeId = item.querySelector('input[name*="[labor_type_id]"]')?.value;
                    const laborType = laborTypes.find(lt => lt.id == laborTypeId);
                    const overtimeRate = laborType ? parseFloat(laborType.overtime_rate) || (unitPrice * 1.5) : unitPrice * 1.5;
                    
                    subtotal += (hours * unitPrice) + (overtimeHours * overtimeRate);
                } else if (itemType === 'service') {
                    const quantity = parseFloat(item.querySelector('[name*="[quantity]"]')?.value) || 0;
                    const unitPrice = parseFloat(item.querySelector('[name*="[unit_price]"]')?.value) || 0;
                    const serviceId = item.querySelector('input[name*="[service_id]"]')?.value;
                    
                    const service = services.find(s => s.id == serviceId);
                    if (service) {
                        if (service.unit_type === 'fixed') {
                            subtotal += unitPrice;
                        } else {
                            subtotal += quantity * unitPrice;
                        }
                    } else {
                        subtotal += quantity * unitPrice;
                    }
                } else {
                    const quantity = parseFloat(item.querySelector('[name*="[quantity]"]')?.value) || 0;
                    const unitPrice = parseFloat(item.querySelector('[name*="[unit_price]"]')?.value) || 0;
                    subtotal += quantity * unitPrice;
                }
            });

            const discount = parseFloat(form.querySelector('[name="discount"]').value) || 0;
            const total = subtotal - discount;
            console.log('[Budgets/Edit] Totais calculados', { subtotal, discount, total });

            document.getElementById('subtotal').textContent = 'R$ ' + subtotal.toFixed(2).replace('.', ',');
            document.getElementById('discount-display').textContent = 'R$ ' + discount.toFixed(2).replace('.', ',');
            document.getElementById('total').textContent = 'R$ ' + total.toFixed(2).replace('.', ',');
        }

        function setupProductSearch(container) {
            const searchInput = container.querySelector('.product-search');
            const hiddenInput = container.querySelector('input[name*="[product_id]"]');
            const resultsDiv = container.querySelector('.product-results');
            
            const itemTypeInput = container.querySelector('[data-item-type-field="product"]') || container.querySelector('[name*="[item_type]"]');
            if (itemTypeInput && itemTypeInput.value !== 'product') {
                itemTypeInput.value = 'product';
            }
            
            let searchTimeout;
            
            searchInput.addEventListener('input', function() {
                const query = this.value.trim();
                
                clearTimeout(searchTimeout);
                
                if (query.length < 2) {
                    resultsDiv.classList.add('hidden');
                    return;
                }
                
                searchTimeout = setTimeout(() => {
                    const filteredProducts = products.filter(product => 
                        product.name.toLowerCase().includes(query.toLowerCase())
                    );
                    
                    if (filteredProducts.length > 0) {
                        let resultsHtml = '';
                        filteredProducts.slice(0, 10).forEach(product => {
                            const photoUrl = product.photo ? `/${product.photo}` : null;
                            resultsHtml += `
                                <div class="product-option p-2 hover:bg-gray-100 dark:hover:bg-gray-700 cursor-pointer border-b border-gray-200 dark:border-gray-700 last:border-b-0 flex items-center gap-3" 
                                     data-id="${product.id}" 
                                     data-name="${product.name}"
                                     data-price="${product.price || 0}"
                                     data-sale-price="${product.sale_price || ''}">
                                    ${photoUrl ? `
                                        <img src="${photoUrl}" alt="${product.name}" class="w-12 h-12 object-cover rounded border border-gray-200 dark:border-gray-700 flex-shrink-0">
                                    ` : `
                                        <div class="w-12 h-12 bg-gray-200 dark:bg-gray-700 rounded border border-gray-300 dark:border-gray-600 flex items-center justify-center flex-shrink-0">
                                            <svg class="w-6 h-6 text-gray-400 dark:text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                                            </svg>
                                        </div>
                                    `}
                                    <div class="flex-1 min-w-0">
                                        <div class="font-medium text-sm truncate text-gray-900 dark:text-gray-100">${product.name}</div>
                                        ${product.sku ? `<div class="text-xs text-gray-500 dark:text-gray-400">SKU: ${product.sku}</div>` : ''}
                                        <div class="text-xs font-semibold text-indigo-600 dark:text-indigo-400">R$ ${parseFloat(product.price || 0).toFixed(2).replace('.', ',')}</div>
                                    </div>
                                </div>
                            `;
                        });
                        resultsDiv.innerHTML = resultsHtml;
                        resultsDiv.classList.remove('hidden');
                        
                        resultsDiv.querySelectorAll('.product-option').forEach(option => {
                            option.addEventListener('click', function() {
                                const productId = this.dataset.id;
                                const productName = this.dataset.name;
                                const productPrice = parseFloat(this.dataset.price) || 0;
                                const productSalePrice = this.dataset.salePrice ? parseFloat(this.dataset.salePrice) : null;
                                
                                // Usar sale_price se disponível, senão usar price
                                const priceToUse = productSalePrice !== null && productSalePrice > 0 ? productSalePrice : productPrice;
                                
                                const itemTypeInput = container.querySelector('[data-item-type-field="product"]') || container.querySelector('[name*="[item_type]"]');
                                if (itemTypeInput) {
                                    itemTypeInput.value = 'product';
                                }
                                
                                searchInput.value = productName;
                                if (hiddenInput) {
                                hiddenInput.value = productId;
                                }
                                resultsDiv.classList.add('hidden');
                                
                                const descriptionInput = container.querySelector('input[name*="[description]"]');
                                const quantityInput = container.querySelector('input[name*="[quantity]"]');
                                const priceInput = container.querySelector('input[name*="[unit_price]"]');
                                
                                if (!descriptionInput.value) {
                                    descriptionInput.value = productName;
                                }
                                if (!quantityInput.value || quantityInput.value === '0') {
                                    quantityInput.value = '1';
                                }
                                // Preencher com sale_price se disponível, permitindo edição manual
                                if (!priceInput.value || priceInput.value === '0') {
                                    priceInput.value = priceToUse.toFixed(2);
                                }
                                
                                calculateTotals();
                            });
                        });
                    } else {
                        resultsDiv.innerHTML = '<div class="p-2 text-gray-500 dark:text-gray-400 text-sm">Nenhum produto encontrado</div>';
                        resultsDiv.classList.remove('hidden');
                    }
                }, 300);
            });
            
            document.addEventListener('click', function(e) {
                if (!container.contains(e.target)) {
                    resultsDiv.classList.add('hidden');
                }
            });
            
            const initialProductId = hiddenInput.value;
            if (initialProductId) {
                const product = products.find(p => p.id == initialProductId);
                if (product) {
                    searchInput.value = product.name;
                }
            }
        }

        function setupServiceSearch(container) {
            const searchInput = container.querySelector('.service-search');
            const hiddenInput = container.querySelector('input[name*="[service_id]"]');
            const resultsDiv = container.querySelector('.service-results');
            
            const itemTypeInput = container.querySelector('[data-item-type-field="service"]') || container.querySelector('[name*="[item_type]"]');
            if (itemTypeInput && itemTypeInput.value !== 'service') {
                itemTypeInput.value = 'service';
            }
            
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
                                <div class="service-option p-2 hover:bg-gray-100 dark:hover:bg-gray-700 cursor-pointer border-b border-gray-200 dark:border-gray-700 last:border-b-0" 
                                     data-id="${service.id}" 
                                     data-name="${service.name}" 
                                     data-price="${service.default_price}"
                                     data-unit-type="${service.unit_type}">
                                    <div class="font-medium text-sm text-gray-900 dark:text-gray-100">${service.name}</div>
                                    <div class="text-xs text-gray-500 dark:text-gray-400">${priceDisplay}</div>
                                    ${service.category ? `<div class="text-xs text-gray-400 dark:text-gray-500">${service.category}</div>` : ''}
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
                        resultsDiv.innerHTML = '<div class="p-2 text-gray-500 dark:text-gray-400 text-sm">Nenhum serviço encontrado</div>';
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

        function setupLaborSearch(container) {
            const searchInput = container.querySelector('.labor-search');
            const index = searchInput ? searchInput.dataset.index : null;
            const hiddenInput = container.querySelector(`.labor-type-id-input[data-index="${index}"]`) || container.querySelector('input[name*="[labor_type_id]"]');
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
                                <div class="labor-option p-2 hover:bg-gray-100 dark:hover:bg-gray-700 cursor-pointer border-b border-gray-200 dark:border-gray-700 last:border-b-0" 
                                     data-id="${laborType.id}" 
                                     data-name="${laborType.name}" 
                                     data-rate="${laborType.hourly_rate}"
                                     data-overtime-rate="${laborType.overtime_rate}">
                                    <div class="font-medium text-sm text-gray-900 dark:text-gray-100">${laborType.name}</div>
                                    <div class="text-xs text-gray-500 dark:text-gray-400">
                                        Normal: R$ ${parseFloat(laborType.hourly_rate).toFixed(2)}/h | 
                                        Extra: R$ ${parseFloat(laborType.overtime_rate).toFixed(2)}/h
                                    </div>
                                    <div class="text-xs text-gray-400 dark:text-gray-500">${laborType.skill_level_label}</div>
                                </div>
                            `;
                        });
                        resultsDiv.innerHTML = resultsHtml;
                        resultsDiv.classList.remove('hidden');
                        
                        resultsDiv.querySelectorAll('.labor-option').forEach(option => {
                            option.addEventListener('click', function() {
                                const laborId = this.dataset.id;
                                const laborName = this.dataset.name;
                                const hourlyRate = parseFloat(this.dataset.rate) || 0;
                                
                                searchInput.value = laborName;
                                if (hiddenInput) {
                                hiddenInput.value = laborId;
                                }
                                resultsDiv.classList.add('hidden');
                                
                                const unitPriceInput = container.querySelector(`.labor-unit-price[data-index="${index}"]`) || container.querySelector('.labor-unit-price');
                                if (unitPriceInput && hourlyRate > 0) {
                                    unitPriceInput.value = hourlyRate.toFixed(2);
                                    console.log('[Budgets/Edit] Labor unit_price definido', { index, laborId, hourlyRate, unitPrice: unitPriceInput.value });
                                }
                                
                                const descriptionInput = container.querySelector('input[name*="[description]"]');
                                if (!descriptionInput || !descriptionInput.value) {
                                    if (descriptionInput) {
                                    descriptionInput.value = laborName;
                                    }
                                }
                                
                                const hoursInput = container.querySelector(`.labor-hours-input[data-index="${index}"]`) || container.querySelector('[name*="[hours]"]');
                                if (hoursInput && (!hoursInput.value || hoursInput.value === '0')) {
                                    hoursInput.value = '1';
                                }
                                
                                updateLaborUnitPrice(index);
                                calculateTotals();
                            });
                        });
                    } else {
                        resultsDiv.innerHTML = '<div class="p-2 text-gray-500 dark:text-gray-400 text-sm">Nenhum tipo encontrado</div>';
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

        document.addEventListener('DOMContentLoaded', function() {
            console.log('[Budgets/Edit] DOMContentLoaded (itens) - inicializando itens existentes', { existingCount: existingItems.length });
            if (existingItems && existingItems.length > 0) {
                existingItems.forEach(item => addItem(item.item_type || 'product', item));
            } else {
                console.log('[Budgets/Edit] Nenhum item existente, adicionando item de produto padrão');
                addItem('product');
            }

            const discountInput = document.querySelector('[name="discount"]');
            if (discountInput) {
                discountInput.addEventListener('input', calculateTotals);
                console.log('[Budgets/Edit] Listener para discount.input registrado');
            } else {
                console.warn('[Budgets/Edit] Campo de desconto não encontrado para registrar listener');
            }

            calculateTotals();

            const form = document.getElementById('budgetForm');
            if (form) {
                form.addEventListener('submit', function(e) {
                    console.log('[Budgets/Edit] Formulário de orçamento sendo enviado');

                    const items = form.querySelectorAll('[data-item-index]');
                    let hasValidItem = false;
                    
                    items.forEach(item => {
                        const description = item.querySelector('[name*="[description]"]')?.value?.trim();
                        const itemType = item.querySelector('[name*="[item_type]"]')?.value;
                        
                        if (itemType === 'labor') {
                            const hours = parseFloat(item.querySelector('[name*="[hours]"]')?.value) || 0;
                            const unitPrice = parseFloat(item.querySelector('[name*="[unit_price]"]')?.value) || 0;
                            if (description && hours > 0 && unitPrice > 0) {
                                hasValidItem = true;
                            }
                        } else {
                            const quantity = parseFloat(item.querySelector('[name*="[quantity]"]')?.value) || 0;
                            const unitPrice = parseFloat(item.querySelector('[name*="[unit_price]"]')?.value) || 0;
                            if (description && quantity > 0 && unitPrice > 0) {
                                hasValidItem = true;
                            }
                        }
                    });
                    
                    if (!hasValidItem) {
                        e.preventDefault();
                        alert('Por favor, mantenha pelo menos um item válido no orçamento com descrição, quantidade/horas e preço preenchidos.');
                        return false;
                    }

                    items.forEach((item, index) => {
                        const itemTypeFromData = item.getAttribute('data-item-type');
                        const itemTypeInput = item.querySelector('[data-item-type-field]') || item.querySelector('[name*="[item_type]"]');
                        
                        let itemType = itemTypeFromData || (itemTypeInput ? itemTypeInput.value : null);
                        
                        if (!itemType || !['product', 'service', 'labor'].includes(itemType)) {
                            if (item.querySelector('[name*="[product_id]"]')) {
                                itemType = 'product';
                            } else if (item.querySelector('[name*="[service_id]"]')) {
                                itemType = 'service';
                            } else if (item.querySelector('[name*="[labor_type_id]"]') || item.querySelector('[name*="[hours]"]')) {
                                itemType = 'labor';
                            } else {
                                itemType = 'product';
                            }
                        }
                        
                        if (itemTypeInput) {
                            itemTypeInput.value = itemType;
                        } else {
                            const hiddenInput = document.createElement('input');
                            hiddenInput.type = 'hidden';
                            hiddenInput.name = `items[${index}][item_type]`;
                            hiddenInput.value = itemType;
                            hiddenInput.setAttribute('data-item-type-field', itemType);
                            item.insertBefore(hiddenInput, item.firstChild);
                        }
                        
                        const allItemTypeInputs = item.querySelectorAll('[name*="[item_type]"]');
                        if (allItemTypeInputs.length > 1) {
                            allItemTypeInputs.forEach((input, idx) => {
                                if (idx > 0) {
                                    input.remove();
                                } else {
                                    input.value = itemType;
                                }
                            });
                        }
                        
                        if (itemType === 'product') {
                            const productId = item.querySelector('input[name*="[product_id]"]')?.value;
                            if (!productId) {
                                item.querySelector('input[name*="[product_id]"]').value = '';
                            }
                        } else if (itemType === 'service') {
                            const serviceId = item.querySelector('input[name*="[service_id]"]')?.value;
                            if (!serviceId) {
                                item.querySelector('input[name*="[service_id]"]').value = '';
                            }
                        } else if (itemType === 'labor') {
                            const laborTypeIdInput = item.querySelector(`.labor-type-id-input[data-index="${index}"]`) || item.querySelector('input[name*="[labor_type_id]"]');
                            const laborTypeId = laborTypeIdInput?.value;
                            
                            if (!laborTypeId) {
                                if (laborTypeIdInput) {
                                    laborTypeIdInput.value = '';
                                }
                            }
                            
                            const unitPriceInput = item.querySelector(`.labor-unit-price[data-index="${index}"]`) || item.querySelector('.labor-unit-price');
                            const hours = parseFloat(item.querySelector(`.labor-hours-input[data-index="${index}"]`)?.value || item.querySelector('[name*="[hours]"]')?.value) || 0;
                            
                            if (hours > 0 && unitPriceInput) {
                                if (!unitPriceInput.value || unitPriceInput.value === '0' || parseFloat(unitPriceInput.value) === 0) {
                                    if (laborTypeId) {
                                        const laborType = laborTypes.find(lt => lt.id == laborTypeId);
                                        if (laborType && laborType.hourly_rate) {
                                            unitPriceInput.value = parseFloat(laborType.hourly_rate).toFixed(2);
                                            console.log('[Budgets/Edit] Unit price preenchido antes do submit', { index, laborTypeId, unitPrice: unitPriceInput.value });
                                        } else {
                                            unitPriceInput.value = '0.01';
                                        }
                                    } else {
                                        unitPriceInput.value = '0.01';
                                    }
                                }
                            }
                        }
                        
                        const quantityInput = item.querySelector('[name*="[quantity]"]');
                        if (quantityInput && !quantityInput.value) {
                            quantityInput.value = '0';
                        }
                        
                        const hoursInput = item.querySelector('[name*="[hours]"]');
                        if (hoursInput && !hoursInput.value) {
                            hoursInput.value = '0';
                        }
                        
                        const overtimeHoursInput = item.querySelector('[name*="[overtime_hours]"]');
                        if (overtimeHoursInput && !overtimeHoursInput.value) {
                            overtimeHoursInput.value = '0';
                        }
                    });

                    const finalCheck = form.querySelectorAll('[data-item-index]');
                    finalCheck.forEach(item => {
                        const itemTypeFromData = item.getAttribute('data-item-type');
                        const itemTypeInput = item.querySelector('[data-item-type-field]') || item.querySelector('[name*="[item_type]"]');
                        
                        if (itemTypeInput && itemTypeFromData && itemTypeInput.value !== itemTypeFromData) {
                            console.warn('[Budgets/Edit] Corrigindo item_type antes do submit', { 
                                index: item.getAttribute('data-item-index'),
                                oldValue: itemTypeInput.value,
                                newValue: itemTypeFromData
                            });
                            itemTypeInput.value = itemTypeFromData;
                        }
                    });

                    const formData = new FormData(form);
                    const formDataObj = {};
                    for (let [key, value] of formData.entries()) {
                        formDataObj[key] = value;
                    }
                    console.log('[Budgets/Edit] Dados do formulário preparados, enviando...', formDataObj);
                });
            }
        });
    </script>
    @endpush
</x-app-layout>