<x-app-layout>
    <script>
        // Define functions immediately so they're available for onclick handlers
        window.loadServiceForm = async function(serviceId) {
            const form = document.getElementById('serviceForm');
            const offcanvasTitle = document.querySelector('#service-offcanvas h2');
            const methodInput = document.getElementById('service_method');
            
            if (!form || !offcanvasTitle || !methodInput) return;
            
            form.reset();
            if (window.clearServiceErrors) window.clearServiceErrors();
            
            if (serviceId) {
                offcanvasTitle.textContent = 'Editar Serviço';
                methodInput.value = 'PUT';
                form.action = `/services/${serviceId}`;
                
                try {
                    const response = await fetch(`/services/${serviceId}/edit`, {
                        headers: {
                            'X-Requested-With': 'XMLHttpRequest',
                            'Accept': 'application/json'
                        }
                    });
                    
                    if (response.ok) {
                        const data = await response.json();
                        const service = data.service;
                        
                        if (service.name) document.getElementById('service_name').value = service.name;
                        if (service.category_id) document.getElementById('service_category_id').value = service.category_id;
                        if (service.unit_type) {
                            document.getElementById('service_unit_type').value = service.unit_type;
                            if (window.updateServicePriceLabel) window.updateServicePriceLabel();
                        }
                        if (service.default_price) document.getElementById('service_default_price').value = service.default_price;
                        if (service.minimum_price) document.getElementById('service_minimum_price').value = service.minimum_price;
                        if (service.maximum_price) document.getElementById('service_maximum_price').value = service.maximum_price;
                        if (service.description) document.getElementById('service_description').value = service.description;
                        document.getElementById('service_is_active').checked = service.is_active;
                    } else {
                        window.location.href = `/services/${serviceId}/edit`;
                        return;
                    }
                } catch (error) {
                    console.error('Erro ao carregar serviço:', error);
                    window.location.href = `/services/${serviceId}/edit`;
                    return;
                }
            } else {
                offcanvasTitle.textContent = 'Novo Serviço';
                methodInput.value = 'POST';
                form.action = '{{ route("services.store") }}';
                if (window.updateServicePriceLabel) window.updateServicePriceLabel();
            }
            
            if (window.openOffcanvas) {
                window.openOffcanvas('service-offcanvas');
            }
        };
        
        window.updateServicePriceLabel = function() {
            const unitTypeSelect = document.getElementById('service_unit_type');
            const label = document.getElementById('service_price_label');
            
            if (!unitTypeSelect || !label) return;
            
            const unitType = unitTypeSelect.value;
            switch(unitType) {
                case 'hour':
                    label.textContent = 'Preço por Hora (R$) *';
                    break;
                case 'fixed':
                    label.textContent = 'Preço Fixo (R$) *';
                    break;
                case 'per_unit':
                    label.textContent = 'Preço por Unidade (R$) *';
                    break;
                default:
                    label.textContent = 'Preço Padrão (R$) *';
            }
        };
        
        window.loadServiceView = async function(serviceId) {
            const contentDiv = document.getElementById('service-view-content');
            const offcanvasTitle = document.querySelector('#service-view-offcanvas h2');
            
            if (!contentDiv || !offcanvasTitle) return;
            
            // Show loading
            contentDiv.innerHTML = '<div class="flex justify-center items-center py-12"><div class="animate-spin rounded-full h-8 w-8 border-b-2 border-indigo-600"></div></div>';
            
            if (window.openOffcanvas) window.openOffcanvas('service-view-offcanvas');
            
            try {
                const response = await fetch(`/services/${serviceId}`, {
                    headers: {
                        'X-Requested-With': 'XMLHttpRequest',
                        'Accept': 'application/json'
                    }
                });
                
                if (response.ok) {
                    const data = await response.json();
                    const service = data.service;
                    
                    // Build HTML content
                    let html = `
                        <div class="space-y-6">
                            <!-- Header -->
                            <div class="flex items-start justify-between pb-4 border-b border-gray-200 dark:border-gray-700">
                                <div class="flex-1">
                                    <h3 class="text-xl font-semibold text-gray-900 dark:text-gray-100 mb-2">${service.name}</h3>
                                    <div class="flex items-center space-x-2">
                                        <span class="inline-block w-3 h-3 rounded-full" style="background-color: ${service.category.color}"></span>
                                        <span class="text-sm text-gray-600 dark:text-gray-400">${service.category.name}</span>
                                    </div>
                                </div>
                                <span class="px-3 py-1 text-sm rounded-full ${service.is_active ? 'bg-green-100 dark:bg-green-900/30 text-green-800 dark:text-green-300' : 'bg-red-100 dark:bg-red-900/30 text-red-800 dark:text-red-300'}">
                                    ${service.is_active ? 'Ativo' : 'Inativo'}
                                </span>
                            </div>
                            
                            <!-- Description -->
                            ${service.description ? `
                            <div>
                                <h4 class="text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Descrição</h4>
                                <p class="text-sm text-gray-600 dark:text-gray-400 whitespace-pre-wrap">${service.description}</p>
                            </div>
                            ` : ''}
                            
                            <!-- Pricing Info -->
                            <div class="bg-gray-50 dark:bg-gray-700 rounded-lg p-4 space-y-3">
                                <h4 class="text-sm font-medium text-gray-700 dark:text-gray-300 mb-3">Informações de Preço</h4>
                                <div class="space-y-2">
                                    <div class="flex justify-between items-center">
                                        <span class="text-sm text-gray-600 dark:text-gray-400">Tipo de Cobrança:</span>
                                        <span class="font-medium text-indigo-600 dark:text-indigo-400">${service.unit_type_label}</span>
                                    </div>
                                    <div class="flex justify-between items-center">
                                        <span class="text-sm text-gray-600 dark:text-gray-400">Preço Padrão:</span>
                                        <span class="text-lg font-bold text-gray-900 dark:text-gray-100">${service.formatted_price}</span>
                                    </div>
                                    ${service.minimum_price ? `
                                    <div class="flex justify-between items-center">
                                        <span class="text-sm text-gray-600 dark:text-gray-400">Preço Mínimo:</span>
                                        <span class="font-medium text-gray-900 dark:text-gray-100">R$ ${parseFloat(service.minimum_price).toLocaleString('pt-BR', {minimumFractionDigits: 2, maximumFractionDigits: 2})}</span>
                                    </div>
                                    ` : ''}
                                    ${service.maximum_price ? `
                                    <div class="flex justify-between items-center">
                                        <span class="text-sm text-gray-600 dark:text-gray-400">Preço Máximo:</span>
                                        <span class="font-medium text-gray-900 dark:text-gray-100">R$ ${parseFloat(service.maximum_price).toLocaleString('pt-BR', {minimumFractionDigits: 2, maximumFractionDigits: 2})}</span>
                                    </div>
                                    ` : ''}
                                </div>
                            </div>
                            
                            <!-- Additional Info -->
                            <div class="grid grid-cols-2 gap-4 pt-4 border-t border-gray-200 dark:border-gray-700">
                                <div>
                                    <h4 class="text-xs font-medium text-gray-500 dark:text-gray-400 uppercase mb-1">Usado em Orçamentos</h4>
                                    <p class="text-sm font-medium text-gray-900 dark:text-gray-100">${service.budget_items_count || 0}</p>
                                </div>
                                <div>
                                    <h4 class="text-xs font-medium text-gray-500 dark:text-gray-400 uppercase mb-1">Criado em</h4>
                                    <p class="text-sm text-gray-600 dark:text-gray-400">${service.created_at}</p>
                                </div>
                            </div>
                            
                            <!-- Actions -->
                            <div class="flex justify-end gap-3 pt-4 border-t border-gray-200 dark:border-gray-700">
                                <button onclick="loadServiceForm(${service.id})" 
                                   class="px-4 py-2 bg-indigo-600 dark:bg-indigo-700 text-white rounded-md hover:bg-indigo-700 dark:hover:bg-indigo-600 transition-colors">
                                    Editar
                                </button>
                                <button onclick="closeOffcanvas('service-view-offcanvas')" 
                                   class="px-4 py-2 border border-gray-300 dark:border-gray-600 rounded-md text-gray-700 dark:text-gray-200 hover:bg-gray-50 dark:hover:bg-gray-700 transition-colors">
                                    Fechar
                                </button>
                            </div>
                        </div>
                    `;
                    
                    contentDiv.innerHTML = html;
                } else {
                    contentDiv.innerHTML = '<div class="text-center py-12"><p class="text-red-500 dark:text-red-400">Erro ao carregar serviço</p></div>';
                }
            } catch (error) {
                console.error('Erro ao carregar serviço:', error);
                contentDiv.innerHTML = '<div class="text-center py-12"><p class="text-red-500 dark:text-red-400">Erro ao carregar serviço</p></div>';
            }
        };
    </script>
    
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
                {{ __('Serviços') }}
            </h2>
            <button onclick="loadServiceForm(null)" class="inline-flex items-center px-4 py-2 bg-indigo-600 dark:bg-indigo-700 text-white rounded-md hover:bg-indigo-700 dark:hover:bg-indigo-600 transition-colors">
                <i class="bi bi-plus-circle mr-2"></i>
                Novo Serviço
            </button>
        </div>
    </x-slot>

    <div class="py-6">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <!-- Filters -->
            <div class="bg-white dark:bg-gray-800 rounded-lg shadow p-4 mb-6">
                <form method="GET" class="grid grid-cols-1 md:grid-cols-4 gap-4">
                    <div>
                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Buscar</label>
                        <input type="text" name="search" value="{{ request('search') }}" 
                               placeholder="Nome do serviço..." 
                               class="w-full border-gray-300 dark:border-gray-600 rounded-md bg-white dark:bg-gray-700 text-gray-900 dark:text-gray-100 focus:ring-indigo-500 focus:border-indigo-500">
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Categoria</label>
                        <select name="category_id" class="w-full border-gray-300 dark:border-gray-600 rounded-md bg-white dark:bg-gray-700 text-gray-900 dark:text-gray-100 focus:ring-indigo-500 focus:border-indigo-500">
                            <option value="">Todas as categorias</option>
                            @foreach($categories as $category)
                                <option value="{{ $category->id }}" {{ request('category_id') == $category->id ? 'selected' : '' }}>
                                    {{ $category->name }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Status</label>
                        <select name="status" class="w-full border-gray-300 dark:border-gray-600 rounded-md bg-white dark:bg-gray-700 text-gray-900 dark:text-gray-100 focus:ring-indigo-500 focus:border-indigo-500">
                            <option value="">Todos</option>
                            <option value="active" {{ request('status') === 'active' ? 'selected' : '' }}>Ativo</option>
                            <option value="inactive" {{ request('status') === 'inactive' ? 'selected' : '' }}>Inativo</option>
                        </select>
                    </div>
                    <div class="flex items-end">
                        <button type="submit" class="w-full px-4 py-2 bg-gray-600 dark:bg-gray-700 text-white rounded-md hover:bg-gray-700 dark:hover:bg-gray-600 transition-colors">
                            Filtrar
                        </button>
                    </div>
                </form>
            </div>

            <!-- Services Grid -->
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                @forelse($services as $service)
                    <div class="bg-white dark:bg-gray-800 rounded-lg shadow-md border border-gray-200 dark:border-gray-700 hover:shadow-lg transition-shadow duration-200">
                        <div class="p-6">
                            <!-- Header -->
                            <div class="flex items-start justify-between mb-4">
                                <div class="flex-1">
                                    <h3 class="font-semibold text-lg text-gray-900 dark:text-gray-100 mb-1">{{ $service->name }}</h3>
                                    <div class="flex items-center space-x-2">
                                        <span class="inline-block w-3 h-3 rounded-full" style="background-color: {{ $service->category->color }}"></span>
                                        <span class="text-sm text-gray-600 dark:text-gray-400">{{ $service->category->name }}</span>
                                    </div>
                                </div>
                                <div class="flex items-center space-x-2">
                                    <span class="px-2 py-1 text-xs rounded-full {{ $service->is_active ? 'bg-green-100 dark:bg-green-900/30 text-green-800 dark:text-green-300' : 'bg-red-100 dark:bg-red-900/30 text-red-800 dark:text-red-300' }}">
                                        {{ $service->is_active ? 'Ativo' : 'Inativo' }}
                                    </span>
                                </div>
                            </div>

                            <!-- Description -->
                            @if($service->description)
                                <p class="text-sm text-gray-600 dark:text-gray-400 mb-4">{{ Str::limit($service->description, 100) }}</p>
                            @endif

                            <!-- Pricing Info -->
                            <div class="bg-gray-50 dark:bg-gray-700 rounded-lg p-3 mb-4">
                                <div class="text-sm text-gray-600 dark:text-gray-400 mb-1">Tipo de Cobrança</div>
                                <div class="font-medium text-indigo-600 dark:text-indigo-400">{{ $service->unit_type_label }}</div>
                                <div class="text-lg font-bold text-gray-900 dark:text-gray-100 mt-1">{{ $service->formatted_price }}</div>
                                @if($service->minimum_price || $service->maximum_price)
                                    <div class="text-xs text-gray-500 dark:text-gray-400 mt-1">
                                        @if($service->minimum_price)
                                            Mín: R$ {{ number_format($service->minimum_price, 2, ',', '.') }}
                                        @endif
                                        @if($service->minimum_price && $service->maximum_price) | @endif
                                        @if($service->maximum_price)
                                            Máx: R$ {{ number_format($service->maximum_price, 2, ',', '.') }}
                                        @endif
                                    </div>
                                @endif
                            </div>

                            <!-- Actions -->
                            <div class="flex space-x-2">
                                <button onclick="loadServiceForm({{ $service->id }})" 
                                   class="flex-1 text-center px-3 py-2 text-sm bg-indigo-600 dark:bg-indigo-700 text-white rounded-md hover:bg-indigo-700 dark:hover:bg-indigo-600 transition-colors">
                                    Editar
                                </button>
                                <button onclick="loadServiceView({{ $service->id }})" 
                                   class="px-3 py-2 text-sm bg-gray-600 dark:bg-gray-700 text-white rounded-md hover:bg-gray-700 dark:hover:bg-gray-600 transition-colors">
                                    Ver
                                </button>
                            </div>
                        </div>
                    </div>
                @empty
                    <div class="col-span-full">
                        <div class="text-center py-12">
                            <div class="text-gray-400 dark:text-gray-500 text-6xl mb-4">
                                <i class="bi bi-tools" style="font-size: 4rem;"></i>
                            </div>
                            <h3 class="text-lg font-medium text-gray-900 dark:text-gray-100 mb-2">Nenhum serviço encontrado</h3>
                            <p class="text-gray-600 dark:text-gray-400 mb-4">Comece criando seu primeiro serviço.</p>
                            <button onclick="loadServiceForm(null)" 
                               class="inline-flex items-center px-4 py-2 bg-indigo-600 dark:bg-indigo-700 text-white rounded-md hover:bg-indigo-700 dark:hover:bg-indigo-600 transition-colors">
                                <i class="bi bi-plus-circle mr-2"></i>
                                Criar Serviço
                            </button>
                        </div>
                    </div>
                @endforelse
            </div>

        <!-- Pagination -->
        @if($services->hasPages())
            <div class="mt-6">{{ $services->links() }}</div>
        @endif

            <!-- Quick Links -->
            <div class="mt-8 border-t border-gray-200 dark:border-gray-700 pt-6">
                <div class="flex space-x-4">
                    <a href="{{ route('service-categories.index') }}" 
                       class="inline-flex items-center px-4 py-2 bg-gray-600 dark:bg-gray-700 text-white rounded-md hover:bg-gray-700 dark:hover:bg-gray-600 transition-colors">
                        <i class="bi bi-folder mr-2"></i>
                        Gerenciar Categorias
                    </a>
                    <a href="{{ route('labor-types.index') }}" 
                       class="inline-flex items-center px-4 py-2 bg-purple-600 dark:bg-purple-700 text-white rounded-md hover:bg-purple-700 dark:hover:bg-purple-600 transition-colors">
                        <i class="bi bi-people mr-2"></i>
                        Tipos de Mão de Obra
                    </a>
                </div>
            </div>
        </div>
    </div>

    <!-- Offcanvas para Novo/Editar Serviço -->
    <x-offcanvas id="service-offcanvas" title="Novo Serviço" width="w-full md:w-[700px]">
        <form method="POST" action="{{ route('services.store') }}" id="serviceForm">
            @csrf
            <input type="hidden" name="_method" id="service_method" value="POST">

            <div class="mb-4">
                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Nome do Serviço *</label>
                <input type="text" name="name" id="service_name" required 
                       class="w-full border-gray-300 dark:border-gray-600 rounded-md bg-white dark:bg-gray-700 text-gray-900 dark:text-gray-100 focus:ring-indigo-500 focus:border-indigo-500" 
                       placeholder="Ex: Instalação Elétrica">
                <div id="name_error" class="hidden">
                    <p class="text-red-500 dark:text-red-400 text-xs mt-1"></p>
                </div>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-4">
                <div>
                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Categoria *</label>
                    <select name="category_id" id="service_category_id" required class="w-full border-gray-300 dark:border-gray-600 rounded-md bg-white dark:bg-gray-700 text-gray-900 dark:text-gray-100 focus:ring-indigo-500 focus:border-indigo-500">
                        <option value="">Selecione uma categoria</option>
                        @foreach($categories as $category)
                            <option value="{{ $category->id }}">{{ $category->name }}</option>
                        @endforeach
                    </select>
                    <div id="category_id_error" class="hidden">
                        <p class="text-red-500 dark:text-red-400 text-xs mt-1"></p>
                    </div>
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Tipo de Cobrança *</label>
                    <select name="unit_type" id="service_unit_type" required class="w-full border-gray-300 dark:border-gray-600 rounded-md bg-white dark:bg-gray-700 text-gray-900 dark:text-gray-100 focus:ring-indigo-500 focus:border-indigo-500" onchange="updateServicePriceLabel()">
                        <option value="hour">Por Hora</option>
                        <option value="fixed">Preço Fixo</option>
                        <option value="per_unit">Por Unidade</option>
                    </select>
                    <div id="unit_type_error" class="hidden">
                        <p class="text-red-500 dark:text-red-400 text-xs mt-1"></p>
                    </div>
                </div>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-4">
                <div>
                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">
                        <span id="service_price_label">Preço Padrão (R$) *</span>
                    </label>
                    <input type="number" name="default_price" id="service_default_price" step="0.01" min="0" required 
                           class="w-full border-gray-300 dark:border-gray-600 rounded-md bg-white dark:bg-gray-700 text-gray-900 dark:text-gray-100 focus:ring-indigo-500 focus:border-indigo-500">
                    <div id="default_price_error" class="hidden">
                        <p class="text-red-500 dark:text-red-400 text-xs mt-1"></p>
                    </div>
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Preço Mínimo (R$)</label>
                    <input type="number" name="minimum_price" id="service_minimum_price" step="0.01" min="0" 
                           class="w-full border-gray-300 dark:border-gray-600 rounded-md bg-white dark:bg-gray-700 text-gray-900 dark:text-gray-100 focus:ring-indigo-500 focus:border-indigo-500">
                </div>
            </div>

            <div class="mb-4">
                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Preço Máximo (R$)</label>
                <input type="number" name="maximum_price" id="service_maximum_price" step="0.01" min="0" 
                       class="w-full border-gray-300 dark:border-gray-600 rounded-md bg-white dark:bg-gray-700 text-gray-900 dark:text-gray-100 focus:ring-indigo-500 focus:border-indigo-500">
            </div>

            <div class="mb-4">
                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Descrição</label>
                <textarea name="description" id="service_description" rows="3" 
                          class="w-full border-gray-300 dark:border-gray-600 rounded-md bg-white dark:bg-gray-700 text-gray-900 dark:text-gray-100 focus:ring-indigo-500 focus:border-indigo-500" 
                          placeholder="Descreva o serviço, incluindo o que está incluído..."></textarea>
            </div>

            <div class="mb-6 flex items-center">
                <input type="checkbox" name="is_active" id="service_is_active" value="1" checked 
                       class="rounded border-gray-300 dark:border-gray-600 text-indigo-600 focus:ring-indigo-500">
                <label for="service_is_active" class="ml-2 text-sm text-gray-700 dark:text-gray-300">Serviço ativo</label>
            </div>

            <div class="flex justify-end gap-3 pt-4 border-t border-gray-200 dark:border-gray-700">
                <button type="button" onclick="closeOffcanvas('service-offcanvas')" 
                       class="px-4 py-2 border border-gray-300 dark:border-gray-600 rounded-md text-gray-700 dark:text-gray-200 hover:bg-gray-50 dark:hover:bg-gray-700 transition-colors">
                    Cancelar
                </button>
                <button type="submit" 
                       class="px-4 py-2 bg-indigo-600 dark:bg-indigo-700 text-white rounded-md hover:bg-indigo-700 dark:hover:bg-indigo-600 transition-colors">
                    Salvar Serviço
                </button>
            </div>
        </form>
    </x-offcanvas>

    <!-- Offcanvas para Visualizar Serviço -->
    <x-offcanvas id="service-view-offcanvas" title="Detalhes do Serviço" width="w-full md:w-[700px]">
        <div id="service-view-content">
            <div class="flex justify-center items-center py-12">
                <div class="animate-spin rounded-full h-8 w-8 border-b-2 border-indigo-600"></div>
            </div>
        </div>
    </x-offcanvas>
</x-app-layout>

@push('scripts')
<script>
    window.clearServiceErrors = function() {
        document.querySelectorAll('[id$="_error"]').forEach(el => {
            el.classList.add('hidden');
            const p = el.querySelector('p');
            if (p) p.textContent = '';
        });
    };
    
    // Update the functions with full implementation including error clearing
    window.loadServiceForm = async function(serviceId) {
        const form = document.getElementById('serviceForm');
        const offcanvasTitle = document.querySelector('#service-offcanvas h2');
        const methodInput = document.getElementById('service_method');
        
        if (!form || !offcanvasTitle || !methodInput) return;
        
        form.reset();
        window.clearServiceErrors();
        
        if (serviceId) {
            offcanvasTitle.textContent = 'Editar Serviço';
            methodInput.value = 'PUT';
            form.action = `/services/${serviceId}`;
            
            try {
                const response = await fetch(`/services/${serviceId}/edit`, {
                    headers: {
                        'X-Requested-With': 'XMLHttpRequest',
                        'Accept': 'application/json'
                    }
                });
                
                if (response.ok) {
                    const data = await response.json();
                    const service = data.service;
                    
                    if (service.name) document.getElementById('service_name').value = service.name;
                    if (service.category_id) document.getElementById('service_category_id').value = service.category_id;
                    if (service.unit_type) {
                        document.getElementById('service_unit_type').value = service.unit_type;
                        window.updateServicePriceLabel();
                    }
                    if (service.default_price) document.getElementById('service_default_price').value = service.default_price;
                    if (service.minimum_price) document.getElementById('service_minimum_price').value = service.minimum_price;
                    if (service.maximum_price) document.getElementById('service_maximum_price').value = service.maximum_price;
                    if (service.description) document.getElementById('service_description').value = service.description;
                    document.getElementById('service_is_active').checked = service.is_active;
                } else {
                    window.location.href = `/services/${serviceId}/edit`;
                    return;
                }
            } catch (error) {
                console.error('Erro ao carregar serviço:', error);
                window.location.href = `/services/${serviceId}/edit`;
                return;
            }
        } else {
            offcanvasTitle.textContent = 'Novo Serviço';
            methodInput.value = 'POST';
            form.action = '{{ route("services.store") }}';
            window.updateServicePriceLabel();
        }
        
        if (window.openOffcanvas) {
            window.openOffcanvas('service-offcanvas');
        }
    };
    
    // Form submit handler
    document.addEventListener('DOMContentLoaded', function() {
        const form = document.getElementById('serviceForm');
        if (!form) return;
        
        form.addEventListener('submit', async function(e) {
            e.preventDefault();
            
            const formData = new FormData(form);
            const method = document.getElementById('service_method').value;
            const submitButton = form.querySelector('button[type="submit"]');
            const originalText = submitButton.innerHTML;
            
            submitButton.disabled = true;
            submitButton.innerHTML = 'Salvando...';
            
            let url = form.action;
            if (method === 'PUT') {
                formData.append('_method', 'PUT');
            }
            
            try {
                const response = await fetch(url, {
                    method: 'POST',
                    body: formData,
                    headers: {
                        'X-Requested-With': 'XMLHttpRequest',
                        'Accept': 'application/json',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
                    }
                });
                
                const data = await response.json();
                
                if (response.ok && data.success) {
                    if (data.redirect) {
                        window.location.href = data.redirect;
                    } else {
                        window.location.reload();
                    }
                } else if (response.status === 422) {
                    window.clearServiceErrors();
                    
                    Object.keys(data.errors || {}).forEach(field => {
                        const errorDiv = document.getElementById(`${field}_error`);
                        if (errorDiv) {
                            errorDiv.classList.remove('hidden');
                            const p = errorDiv.querySelector('p');
                            if (p) p.textContent = data.errors[field][0];
                        }
                    });
                    
                    submitButton.disabled = false;
                    submitButton.innerHTML = originalText;
                } else {
                    alert(data.message || 'Erro ao salvar serviço');
                    submitButton.disabled = false;
                    submitButton.innerHTML = originalText;
                }
            } catch (error) {
                console.error('Erro:', error);
                alert('Erro ao salvar serviço');
                submitButton.disabled = false;
                submitButton.innerHTML = originalText;
            }
        });
    });
</script>
@endpush
