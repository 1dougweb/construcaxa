<x-app-layout>
    <script>
        // Função global para abrir o offcanvas de contrato (novo/editar)
        window.loadContractForm = async function(contractId) {
            const form = document.getElementById('contractForm');
            const offcanvasTitle = document.querySelector('#contract-offcanvas h2');
            const methodInput = document.getElementById('contract_method');
            
            if (!form || !offcanvasTitle || !methodInput) {
                console.error('Elementos do formulário de contrato não encontrados');
                return;
            }
            
            // Limpar formulário
            form.reset();
            if (window.clearContractErrors) window.clearContractErrors();
            const fileInfo = document.getElementById('contract_file_info');
            if (fileInfo) fileInfo.classList.add('hidden');
            
            // Carregar projetos e orçamentos
            if (window.loadProjects) await window.loadProjects();
            if (window.loadBudgets) await window.loadBudgets();
            
            if (contractId) {
                // Modo edição
                offcanvasTitle.textContent = 'Editar Contrato';
                methodInput.value = 'PUT';
                form.action = `/contracts/${contractId}`;
                
                try {
                    const response = await fetch(`/contracts/${contractId}/edit`, {
                        headers: {
                            'X-Requested-With': 'XMLHttpRequest',
                            'Accept': 'application/json'
                        }
                    });
                    
                    if (response.ok) {
                        const data = await response.json();
                        const contract = data.contract;
                        
                        if (contract.client_id) document.getElementById('contract_client_id').value = contract.client_id;
                        if (contract.project_id) document.getElementById('contract_project_id').value = contract.project_id;
                        if (contract.budget_id) document.getElementById('contract_budget_id').value = contract.budget_id;
                        if (contract.title) document.getElementById('contract_title').value = contract.title;
                        if (contract.description) document.getElementById('contract_description').value = contract.description;
                        if (contract.start_date) document.getElementById('contract_start_date').value = contract.start_date;
                        if (contract.end_date) document.getElementById('contract_end_date').value = contract.end_date;
                        if (contract.value) document.getElementById('contract_value').value = contract.value;
                        if (contract.status) document.getElementById('contract_status').value = contract.status;
                        if (contract.signed_at) document.getElementById('contract_signed_at').value = contract.signed_at;
                        if (contract.notes) document.getElementById('contract_notes').value = contract.notes;
                        
                        if (contract.file_path) {
                            const info = document.getElementById('contract_file_info');
                            const link = document.getElementById('contract_file_link');
                            if (info) info.classList.remove('hidden');
                            if (link) link.href = `/contracts/${contractId}/download`;
                        }
                    } else {
                        window.location.href = `/contracts/${contractId}/edit`;
                        return;
                    }
                } catch (error) {
                    console.error('Erro ao carregar contrato:', error);
                    window.location.href = `/contracts/${contractId}/edit`;
                    return;
                }
            } else {
                // Modo criação
                offcanvasTitle.textContent = 'Novo Contrato';
                methodInput.value = 'POST';
                form.action = '{{ route("contracts.store") }}';
            }
            
            if (window.openOffcanvas) {
                window.openOffcanvas('contract-offcanvas');
            } else if (typeof openOffcanvas === 'function') {
                openOffcanvas('contract-offcanvas');
            }
        };
    </script>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
                {{ __('Contratos') }}
            </h2>
            @can('create contracts')
            <button onclick="loadContractForm(null)" class="inline-flex items-center px-4 py-2 bg-indigo-600 dark:bg-indigo-700 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-indigo-700 dark:hover:bg-indigo-600 transition-colors">
                <i class="bi bi-plus-circle mr-2"></i>
                Novo Contrato
            </button>
            @endcan
        </div>
    </x-slot>

    <div class="py-6">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-xl sm:rounded-lg">
                <div class="p-6">
                    <!-- Filtros -->
                    <form method="GET" action="{{ route('contracts.index') }}" class="mb-6">
                        <div class="grid grid-cols-1 md:grid-cols-4 gap-4">
                            <div>
                                <input type="text" name="search" value="{{ request('search') }}"
                                       placeholder="Buscar por número, título ou cliente..."
                                       class="w-full border-gray-300 dark:border-gray-600 rounded-md bg-white dark:bg-gray-700 text-gray-900 dark:text-gray-100 focus:ring-indigo-500 focus:border-indigo-500">
                            </div>
                            <div>
                                <select name="client_id" class="w-full border-gray-300 dark:border-gray-600 rounded-md bg-white dark:bg-gray-700 text-gray-900 dark:text-gray-100 focus:ring-indigo-500 focus:border-indigo-500">
                                    <option value="">Todos os clientes</option>
                                    @foreach($clients as $client)
                                        <option value="{{ $client->id }}" {{ request('client_id') == $client->id ? 'selected' : '' }}>
                                            {{ $client->name }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                            <div>
                                <select name="status" class="w-full border-gray-300 dark:border-gray-600 rounded-md bg-white dark:bg-gray-700 text-gray-900 dark:text-gray-100 focus:ring-indigo-500 focus:border-indigo-500">
                                    <option value="">Todos os status</option>
                                    <option value="draft" {{ request('status') === 'draft' ? 'selected' : '' }}>Rascunho</option>
                                    <option value="active" {{ request('status') === 'active' ? 'selected' : '' }}>Ativo</option>
                                    <option value="expired" {{ request('status') === 'expired' ? 'selected' : '' }}>Expirado</option>
                                    <option value="cancelled" {{ request('status') === 'cancelled' ? 'selected' : '' }}>Cancelado</option>
                                </select>
                            </div>
                            <div>
                                <x-button-loading variant="primary" type="submit" class="w-full">
                                    Filtrar
                                </x-button-loading>
                            </div>
                        </div>
                    </form>

                    <!-- Tabela de Contratos -->
                    <div class="overflow-x-auto">
                        <table class="min-w-full divide-y divide-gray-200 dark:divide-gray-700">
                            <thead class="bg-gray-50 dark:bg-gray-700">
                                <tr>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">Número</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">Título</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">Cliente</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">Valor</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">Status</th>
                                    <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">Ações</th>
                                </tr>
                            </thead>
                            <tbody class="bg-white dark:bg-gray-800 divide-y divide-gray-200 dark:divide-gray-700">
                                @forelse($contracts as $contract)
                                    <tr class="hover:bg-gray-50 dark:hover:bg-gray-700 transition-colors">
                                        <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900 dark:text-gray-100">{{ $contract->contract_number }}</td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900 dark:text-gray-100">{{ $contract->title }}</td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500 dark:text-gray-400">{{ $contract->client->name }}</td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500 dark:text-gray-400">{{ $contract->formatted_value ?: '-' }}</td>
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            <span class="px-2 py-1 text-xs rounded-full {{ $contract->status_color }}">
                                                {{ $contract->status_label }}
                                            </span>
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                                            <div class="flex items-center justify-end space-x-3">
                                                <a
                                                    href="{{ route('contracts.show', $contract) }}"
                                                    class="text-indigo-600 dark:text-indigo-400 hover:text-indigo-900 dark:hover:text-indigo-300 transition-colors"
                                                    title="Ver detalhes"
                                                >
                                                    <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                                                    </svg>
                                                </a>
                                                @can('edit contracts')
                                                <button
                                                    type="button"
                                                    onclick="loadContractForm({{ $contract->id }})"
                                                    class="text-yellow-600 dark:text-yellow-400 hover:text-yellow-900 dark:hover:text-yellow-300 transition-colors"
                                                    title="Editar contrato"
                                                >
                                                    <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                                                    </svg>
                                                </button>
                                                @endcan
                                                @if($contract->file_path)
                                                    <a
                                                        href="{{ route('contracts.download', $contract) }}"
                                                        class="inline-flex items-center px-3 py-1.5 rounded-md bg-green-600 hover:bg-green-700 text-white text-xs font-medium transition-colors"
                                                        title="Baixar contrato"
                                                    >
                                                        <i class="bi bi-download mr-1"></i>
                                                        Download
                                                    </a>
                                                @endif
                                            </div>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="6" class="px-6 py-4 text-center text-gray-500 dark:text-gray-400">
                                            Nenhum contrato encontrado.
                                        </td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>

                    <!-- Paginação -->
                    <div class="mt-6">
                        {{ $contracts->links() }}
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Offcanvas para Novo/Editar Contrato -->
    <x-offcanvas id="contract-offcanvas" title="Novo Contrato" width="w-full md:w-[700px]">
        <form method="POST" action="{{ route('contracts.store') }}" id="contractForm" enctype="multipart/form-data">
            @csrf
            <input type="hidden" name="_method" id="contract_method" value="POST">

            <div class="mb-4">
                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Cliente *</label>
                <select name="client_id" id="contract_client_id" required class="w-full border-gray-300 dark:border-gray-600 rounded-md bg-white dark:bg-gray-700 text-gray-900 dark:text-gray-100 focus:ring-indigo-500 focus:border-indigo-500">
                    <option value="">Selecione um cliente</option>
                    @foreach($clients as $client)
                        <option value="{{ $client->id }}">{{ $client->name }}</option>
                    @endforeach
                </select>
                <div id="client_id_error" class="hidden">
                    <p class="text-red-500 dark:text-red-400 text-xs mt-1"></p>
                </div>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-4">
                <div>
                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Projeto (opcional)</label>
                    <select name="project_id" id="contract_project_id" class="w-full border-gray-300 dark:border-gray-600 rounded-md bg-white dark:bg-gray-700 text-gray-900 dark:text-gray-100 focus:ring-indigo-500 focus:border-indigo-500">
                        <option value="">Nenhum</option>
                    </select>
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Orçamento (opcional)</label>
                    <select name="budget_id" id="contract_budget_id" class="w-full border-gray-300 dark:border-gray-600 rounded-md bg-white dark:bg-gray-700 text-gray-900 dark:text-gray-100 focus:ring-indigo-500 focus:border-indigo-500">
                        <option value="">Nenhum</option>
                    </select>
                </div>
            </div>

            <div class="mb-4">
                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Título *</label>
                <input type="text" name="title" id="contract_title" required class="w-full border-gray-300 dark:border-gray-600 rounded-md bg-white dark:bg-gray-700 text-gray-900 dark:text-gray-100 focus:ring-indigo-500 focus:border-indigo-500">
                <div id="title_error" class="hidden">
                    <p class="text-red-500 dark:text-red-400 text-xs mt-1"></p>
                </div>
            </div>

            <div class="mb-4">
                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Descrição</label>
                <textarea name="description" id="contract_description" rows="3" class="w-full border-gray-300 dark:border-gray-600 rounded-md bg-white dark:bg-gray-700 text-gray-900 dark:text-gray-100 focus:ring-indigo-500 focus:border-indigo-500"></textarea>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-3 gap-4 mb-4">
                <div>
                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Data de Início</label>
                    <input type="date" name="start_date" id="contract_start_date" class="w-full border-gray-300 dark:border-gray-600 rounded-md bg-white dark:bg-gray-700 text-gray-900 dark:text-gray-100 focus:ring-indigo-500 focus:border-indigo-500">
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Data de Término</label>
                    <input type="date" name="end_date" id="contract_end_date" class="w-full border-gray-300 dark:border-gray-600 rounded-md bg-white dark:bg-gray-700 text-gray-900 dark:text-gray-100 focus:ring-indigo-500 focus:border-indigo-500">
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Valor</label>
                    <input type="number" name="value" id="contract_value" step="0.01" class="w-full border-gray-300 dark:border-gray-600 rounded-md bg-white dark:bg-gray-700 text-gray-900 dark:text-gray-100 focus:ring-indigo-500 focus:border-indigo-500" placeholder="0.00">
                </div>
            </div>

            <div class="mb-4">
                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Status *</label>
                <select name="status" id="contract_status" required class="w-full border-gray-300 dark:border-gray-600 rounded-md bg-white dark:bg-gray-700 text-gray-900 dark:text-gray-100 focus:ring-indigo-500 focus:border-indigo-500">
                    <option value="draft">Rascunho</option>
                    <option value="active">Ativo</option>
                    <option value="expired">Expirado</option>
                    <option value="cancelled">Cancelado</option>
                </select>
                <div id="status_error" class="hidden">
                    <p class="text-red-500 dark:text-red-400 text-xs mt-1"></p>
                </div>
            </div>

            <div class="mb-4">
                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Arquivo PDF (máx. 10MB)</label>
                <div id="contract_file_info" class="hidden mb-2">
                    <p class="text-sm text-gray-600 dark:text-gray-400">Arquivo atual: <a href="#" id="contract_file_link" class="text-indigo-600 dark:text-indigo-400 hover:text-indigo-900 dark:hover:text-indigo-300 transition-colors">Baixar</a></p>
                    <p class="text-sm text-gray-500 dark:text-gray-400">Envie um novo arquivo para substituir o atual.</p>
                </div>
                <input type="file" name="file" id="contract_file" accept=".pdf" class="w-full border-gray-300 dark:border-gray-600 rounded-md bg-white dark:bg-gray-700 text-gray-900 dark:text-gray-100 file:mr-4 file:py-2 file:px-4 file:rounded-md file:border-0 file:text-sm file:font-semibold file:bg-indigo-50 dark:file:bg-indigo-900/30 file:text-indigo-700 dark:file:text-indigo-300 hover:file:bg-indigo-100 dark:hover:file:bg-indigo-900/50">
                <div id="file_error" class="hidden">
                    <p class="text-red-500 dark:text-red-400 text-xs mt-1"></p>
                </div>
            </div>

            <div class="mb-4">
                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Data de Assinatura</label>
                <input type="datetime-local" name="signed_at" id="contract_signed_at" class="w-full border-gray-300 dark:border-gray-600 rounded-md bg-white dark:bg-gray-700 text-gray-900 dark:text-gray-100 focus:ring-indigo-500 focus:border-indigo-500">
            </div>

            <div class="mb-6">
                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Observações</label>
                <textarea name="notes" id="contract_notes" rows="3" class="w-full border-gray-300 dark:border-gray-600 rounded-md bg-white dark:bg-gray-700 text-gray-900 dark:text-gray-100 focus:ring-indigo-500 focus:border-indigo-500"></textarea>
            </div>

            <div class="flex justify-end space-x-3">
                <button type="button" onclick="closeOffcanvas('contract-offcanvas')" class="px-4 py-2 bg-gray-300 dark:bg-gray-600 text-gray-700 dark:text-gray-200 rounded-md hover:bg-gray-400 dark:hover:bg-gray-500 transition-colors">
                    Cancelar
                </button>
                <x-button-loading variant="primary" type="submit">
                    Salvar Contrato
                </x-button-loading>
            </div>
        </form>
    </x-offcanvas>
</x-app-layout>

@push('scripts')
<script>
    async function loadProjects() {
        try {
            const response = await fetch('/contracts/create', {
                headers: {
                    'X-Requested-With': 'XMLHttpRequest',
                    'Accept': 'application/json'
                }
            });
            if (response.ok) {
                const data = await response.json();
                const select = document.getElementById('contract_project_id');
                select.innerHTML = '<option value="">Nenhum</option>';
                data.projects.forEach(project => {
                    const option = document.createElement('option');
                    option.value = project.id;
                    option.textContent = project.name;
                    select.appendChild(option);
                });
            }
        } catch (error) {
            console.error('Erro ao carregar projetos:', error);
        }
    }
    
    async function loadBudgets() {
        try {
            const response = await fetch('/contracts/create', {
                headers: {
                    'X-Requested-With': 'XMLHttpRequest',
                    'Accept': 'application/json'
                }
            });
            if (response.ok) {
                const data = await response.json();
                const select = document.getElementById('contract_budget_id');
                select.innerHTML = '<option value="">Nenhum</option>';
                data.budgets.forEach(budget => {
                    const option = document.createElement('option');
                    option.value = budget.id;
                    option.textContent = `${budget.id} - ${budget.client_name}`;
                    select.appendChild(option);
                });
            }
        } catch (error) {
            console.error('Erro ao carregar orçamentos:', error);
        }
    }
    
    function clearContractErrors() {
        document.querySelectorAll('[id$="_error"]').forEach(el => {
            el.classList.add('hidden');
            const p = el.querySelector('p');
            if (p) p.textContent = '';
        });
    }
    
    // Interceptar submissão do formulário
    document.getElementById('contractForm').addEventListener('submit', async function(e) {
        e.preventDefault();
        
        const form = e.target;
        const formData = new FormData(form);
        const method = document.getElementById('contract_method').value;
        const submitButton = form.querySelector('button[type="submit"]');
        const originalText = submitButton.innerHTML;
        
        submitButton.disabled = true;
        submitButton.innerHTML = '<i class="bi bi-hourglass-split mr-2"></i>Salvando...';
        
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
                clearContractErrors();
                
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
                alert(data.message || 'Erro ao salvar contrato');
                submitButton.disabled = false;
                submitButton.innerHTML = originalText;
            }
        } catch (error) {
            console.error('Erro:', error);
            alert('Erro ao salvar contrato');
            submitButton.disabled = false;
            submitButton.innerHTML = originalText;
        }
    });
</script>
@endpush



