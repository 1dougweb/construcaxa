<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
                {{ __('Tipos de Mão de Obra') }}
            </h2>
            <button onclick="loadLaborTypeForm(null)" class="inline-flex items-center px-4 py-2 bg-purple-600 dark:bg-purple-700 text-white rounded-md hover:bg-purple-700 dark:hover:bg-purple-600 transition-colors">
                <i class="bi bi-plus-circle mr-2"></i>
                Novo Tipo
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
                               placeholder="Nome do tipo..." 
                               class="w-full border-gray-300 dark:border-gray-600 rounded-md bg-white dark:bg-gray-700 text-gray-900 dark:text-gray-100 focus:ring-indigo-500 focus:border-indigo-500">
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Nível</label>
                        <select name="skill_level" class="w-full border-gray-300 dark:border-gray-600 rounded-md bg-white dark:bg-gray-700 text-gray-900 dark:text-gray-100 focus:ring-indigo-500 focus:border-indigo-500">
                            <option value="">Todos os níveis</option>
                            @foreach($skillLevels as $value => $label)
                                <option value="{{ $value }}" {{ request('skill_level') == $value ? 'selected' : '' }}>
                                    {{ $label }}
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

            <!-- Labor Types Grid -->
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                @forelse($laborTypes as $laborType)
                    <div class="bg-white dark:bg-gray-800 rounded-lg shadow-md border border-gray-200 dark:border-gray-700 hover:shadow-lg transition-shadow duration-200">
                        <div class="p-6">
                            <!-- Header -->
                            <div class="flex items-start justify-between mb-4">
                                <div class="flex-1">
                                    <h3 class="font-semibold text-lg text-gray-900 dark:text-gray-100 mb-1">{{ $laborType->name }}</h3>
                                    <span class="px-2 py-1 text-xs rounded-full {{ $laborType->skill_level_color }}">
                                        {{ $laborType->skill_level_label }}
                                    </span>
                                </div>
                                <div class="flex items-center space-x-2">
                                    <span class="px-2 py-1 text-xs rounded-full {{ $laborType->is_active ? 'bg-green-100 dark:bg-green-900/30 text-green-800 dark:text-green-300' : 'bg-red-100 dark:bg-red-900/30 text-red-800 dark:text-red-300' }}">
                                        {{ $laborType->is_active ? 'Ativo' : 'Inativo' }}
                                    </span>
                                </div>
                            </div>

                            <!-- Description -->
                            @if($laborType->description)
                                <p class="text-sm text-gray-600 dark:text-gray-400 mb-4">{{ Str::limit($laborType->description, 100) }}</p>
                            @endif

                            <!-- Pricing Info -->
                            <div class="bg-gray-50 dark:bg-gray-700 rounded-lg p-3 mb-4 space-y-2">
                                <div class="flex justify-between">
                                    <span class="text-sm text-gray-600 dark:text-gray-400">Hora Normal:</span>
                                    <span class="font-medium text-purple-600 dark:text-purple-400">{{ $laborType->formatted_hourly_rate }}</span>
                                </div>
                                <div class="flex justify-between">
                                    <span class="text-sm text-gray-600 dark:text-gray-400">Hora Extra:</span>
                                    <span class="font-medium text-orange-600 dark:text-orange-400">{{ $laborType->formatted_overtime_rate }}</span>
                                </div>
                            </div>

                            <!-- Actions -->
                            <div class="flex space-x-2">
                                <button onclick="loadLaborTypeForm({{ $laborType->id }})" 
                                   class="flex-1 text-center px-3 py-2 text-sm bg-purple-600 dark:bg-purple-700 text-white rounded-md hover:bg-purple-700 dark:hover:bg-purple-600 transition-colors">
                                    Editar
                                </button>
                                <a href="{{ route('labor-types.show', $laborType) }}" 
                                   class="px-3 py-2 text-sm bg-gray-600 dark:bg-gray-700 text-white rounded-md hover:bg-gray-700 dark:hover:bg-gray-600 transition-colors">
                                    Ver
                                </a>
                            </div>
                        </div>
                    </div>
                @empty
                    <div class="col-span-full">
                        <div class="text-center py-12">
                            <div class="text-gray-400 dark:text-gray-500 text-6xl mb-4">
                                <i class="bi bi-people" style="font-size: 4rem;"></i>
                            </div>
                            <h3 class="text-lg font-medium text-gray-900 dark:text-gray-100 mb-2">Nenhum tipo de mão de obra encontrado</h3>
                            <p class="text-gray-600 dark:text-gray-400 mb-4">Comece criando seu primeiro tipo de mão de obra.</p>
                            <button onclick="loadLaborTypeForm(null)" 
                               class="inline-flex items-center px-4 py-2 bg-purple-600 dark:bg-purple-700 text-white rounded-md hover:bg-purple-700 dark:hover:bg-purple-600 transition-colors">
                                <i class="bi bi-plus-circle mr-2"></i>
                                Criar Tipo
                            </button>
                        </div>
                    </div>
                @endforelse
            </div>

        <!-- Pagination -->
        @if($laborTypes->hasPages())
            <div class="mt-6">{{ $laborTypes->links() }}</div>
        @endif

            <!-- Quick Links -->
            <div class="mt-8 border-t border-gray-200 dark:border-gray-700 pt-6">
                <div class="flex space-x-4">
                    <a href="{{ route('services.index') }}" 
                       class="inline-flex items-center px-4 py-2 bg-indigo-600 dark:bg-indigo-700 text-white rounded-md hover:bg-indigo-700 dark:hover:bg-indigo-600 transition-colors">
                        <i class="bi bi-tools mr-2"></i>
                        Gerenciar Serviços
                    </a>
                    <a href="{{ route('service-categories.index') }}" 
                       class="inline-flex items-center px-4 py-2 bg-gray-600 dark:bg-gray-700 text-white rounded-md hover:bg-gray-700 dark:hover:bg-gray-600 transition-colors">
                        <i class="bi bi-folder mr-2"></i>
                        Categorias de Serviços
                    </a>
                </div>
            </div>
        </div>
    </div>

    <!-- Offcanvas para Novo/Editar Tipo de Mão de Obra -->
    <x-offcanvas id="labor-type-offcanvas" title="Novo Tipo de Mão de Obra" width="w-full md:w-[700px]">
        <form method="POST" action="{{ route('labor-types.store') }}" id="laborTypeForm">
            @csrf
            <input type="hidden" name="_method" id="labor_type_method" value="POST">

            <div class="mb-4">
                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Nome *</label>
                <input type="text" name="name" id="labor_type_name" required 
                       class="w-full border-gray-300 dark:border-gray-600 rounded-md bg-white dark:bg-gray-700 text-gray-900 dark:text-gray-100 focus:ring-indigo-500 focus:border-indigo-500">
                <div id="name_error" class="hidden">
                    <p class="text-red-500 dark:text-red-400 text-xs mt-1"></p>
                </div>
            </div>

            <div class="mb-4">
                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Nível de Habilidade *</label>
                <select name="skill_level" id="labor_type_skill_level" required class="w-full border-gray-300 dark:border-gray-600 rounded-md bg-white dark:bg-gray-700 text-gray-900 dark:text-gray-100 focus:ring-indigo-500 focus:border-indigo-500">
                    @foreach($skillLevels as $value => $label)
                        <option value="{{ $value }}">{{ $label }}</option>
                    @endforeach
                </select>
                <div id="skill_level_error" class="hidden">
                    <p class="text-red-500 dark:text-red-400 text-xs mt-1"></p>
                </div>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-4">
                <div>
                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Valor Hora Normal (R$) *</label>
                    <input type="number" name="hourly_rate" id="labor_type_hourly_rate" step="0.01" min="0" required 
                           class="w-full border-gray-300 dark:border-gray-600 rounded-md bg-white dark:bg-gray-700 text-gray-900 dark:text-gray-100 focus:ring-indigo-500 focus:border-indigo-500">
                    <div id="hourly_rate_error" class="hidden">
                        <p class="text-red-500 dark:text-red-400 text-xs mt-1"></p>
                    </div>
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Valor Hora Extra (R$) *</label>
                    <input type="number" name="overtime_rate" id="labor_type_overtime_rate" step="0.01" min="0" required 
                           class="w-full border-gray-300 dark:border-gray-600 rounded-md bg-white dark:bg-gray-700 text-gray-900 dark:text-gray-100 focus:ring-indigo-500 focus:border-indigo-500">
                    <div id="overtime_rate_error" class="hidden">
                        <p class="text-red-500 dark:text-red-400 text-xs mt-1"></p>
                    </div>
                </div>
            </div>

            <div class="mb-4">
                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Descrição</label>
                <textarea name="description" id="labor_type_description" rows="3" 
                          class="w-full border-gray-300 dark:border-gray-600 rounded-md bg-white dark:bg-gray-700 text-gray-900 dark:text-gray-100 focus:ring-indigo-500 focus:border-indigo-500"></textarea>
            </div>

            <div class="mb-6 flex items-center">
                <input type="checkbox" name="is_active" id="labor_type_is_active" value="1" checked 
                       class="rounded border-gray-300 dark:border-gray-600 text-indigo-600 focus:ring-indigo-500">
                <label for="labor_type_is_active" class="ml-2 text-sm text-gray-700 dark:text-gray-300">Tipo ativo</label>
            </div>

            <div class="flex justify-end gap-3 pt-4 border-t border-gray-200 dark:border-gray-700">
                <button type="button" onclick="closeOffcanvas('labor-type-offcanvas')" 
                       class="px-4 py-2 border border-gray-300 dark:border-gray-600 rounded-md text-gray-700 dark:text-gray-200 hover:bg-gray-50 dark:hover:bg-gray-700 transition-colors">
                    Cancelar
                </button>
                <button type="submit" 
                       class="px-4 py-2 bg-purple-600 dark:bg-purple-700 text-white rounded-md hover:bg-purple-700 dark:hover:bg-purple-600 transition-colors">
                    Salvar Tipo
                </button>
            </div>
        </form>
    </x-offcanvas>
</x-app-layout>

@push('scripts')
<script>
    async function loadLaborTypeForm(laborTypeId) {
        const form = document.getElementById('laborTypeForm');
        const offcanvasTitle = document.querySelector('#labor-type-offcanvas h2');
        const methodInput = document.getElementById('labor_type_method');
        
        form.reset();
        clearLaborTypeErrors();
        
        if (laborTypeId) {
            offcanvasTitle.textContent = 'Editar Tipo de Mão de Obra';
            methodInput.value = 'PUT';
            form.action = `/labor-types/${laborTypeId}`;
            
            try {
                const response = await fetch(`/labor-types/${laborTypeId}/edit`, {
                    headers: {
                        'X-Requested-With': 'XMLHttpRequest',
                        'Accept': 'application/json'
                    }
                });
                
                if (response.ok) {
                    const data = await response.json();
                    const laborType = data.laborType;
                    
                    if (laborType.name) document.getElementById('labor_type_name').value = laborType.name;
                    if (laborType.skill_level) document.getElementById('labor_type_skill_level').value = laborType.skill_level;
                    if (laborType.hourly_rate) document.getElementById('labor_type_hourly_rate').value = laborType.hourly_rate;
                    if (laborType.overtime_rate) document.getElementById('labor_type_overtime_rate').value = laborType.overtime_rate;
                    if (laborType.description) document.getElementById('labor_type_description').value = laborType.description;
                    document.getElementById('labor_type_is_active').checked = laborType.is_active;
                } else {
                    window.location.href = `/labor-types/${laborTypeId}/edit`;
                    return;
                }
            } catch (error) {
                console.error('Erro ao carregar tipo de mão de obra:', error);
                window.location.href = `/labor-types/${laborTypeId}/edit`;
                return;
            }
        } else {
            offcanvasTitle.textContent = 'Novo Tipo de Mão de Obra';
            methodInput.value = 'POST';
            form.action = '{{ route("labor-types.store") }}';
        }
        
        openOffcanvas('labor-type-offcanvas');
    }
    
    function clearLaborTypeErrors() {
        document.querySelectorAll('[id$="_error"]').forEach(el => {
            el.classList.add('hidden');
            const p = el.querySelector('p');
            if (p) p.textContent = '';
        });
    }
    
    document.getElementById('laborTypeForm').addEventListener('submit', async function(e) {
        e.preventDefault();
        
        const form = e.target;
        const formData = new FormData(form);
        const method = document.getElementById('labor_type_method').value;
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
                clearLaborTypeErrors();
                
                Object.keys(data.errors || {}).forEach(field => {
                    const errorDiv = document.getElementById(`${field}_error`);
                    if (errorDiv) {
                        errorDiv.classList.remove('hidden');
                        errorDiv.querySelector('p').textContent = data.errors[field][0];
                    }
                });
                
                submitButton.disabled = false;
                submitButton.innerHTML = originalText;
            } else {
                alert(data.message || 'Erro ao salvar tipo de mão de obra');
                submitButton.disabled = false;
                submitButton.innerHTML = originalText;
            }
        } catch (error) {
            console.error('Erro:', error);
            alert('Erro ao salvar tipo de mão de obra');
            submitButton.disabled = false;
            submitButton.innerHTML = originalText;
        }
    });
</script>
@endpush
