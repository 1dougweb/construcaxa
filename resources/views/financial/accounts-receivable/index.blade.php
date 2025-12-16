<x-app-layout>
    <div class="py-6">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
        <div class="flex justify-between items-center mb-6">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                {{ __('Contas a Receber') }}
            </h2>
            @can('manage finances')
            <button 
                onclick="openOffcanvas('account-receivable-offcanvas'); resetAccountReceivableForm();" 
                class="inline-flex items-center px-4 py-2 bg-indigo-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-indigo-700 focus:bg-indigo-700 active:bg-indigo-900 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 transition ease-in-out duration-150"
            >
                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
                </svg>
                {{ __('Nova Conta a Receber') }}
            </button>
            @endcan
        </div>
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-xl sm:rounded-lg border border-gray-200 dark:border-gray-700">
                <div class="p-6 lg:p-8 bg-white dark:bg-gray-800 border-b border-gray-200 dark:border-gray-700">
                    @if($accountReceivables->isEmpty())
                        <div class="text-center py-8">
                            <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1"></path>
                            </svg>
                            <h3 class="mt-2 text-sm font-medium text-gray-900 dark:text-gray-100">Nenhuma conta a receber encontrada</h3>
                            <p class="mt-1 text-sm text-gray-500 dark:text-gray-400">Comece criando uma nova conta a receber.</p>
                        </div>
                    @else
                        <div class="overflow-x-auto">
                            <table class="min-w-full divide-y divide-gray-200 dark:divide-gray-700" id="account-receivables-table">
                                <thead>
                                    <tr>
                                        <th class="px-6 py-3 bg-gray-50 dark:bg-gray-700/50 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">Número</th>
                                        <th class="px-6 py-3 bg-gray-50 dark:bg-gray-700/50 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">Descrição</th>
                                        <th class="px-6 py-3 bg-gray-50 dark:bg-gray-700/50 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">Cliente</th>
                                        <th class="px-6 py-3 bg-gray-50 dark:bg-gray-700/50 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">Obra</th>
                                        <th class="px-6 py-3 bg-gray-50 dark:bg-gray-700/50 text-right text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">Valor</th>
                                        <th class="px-6 py-3 bg-gray-50 dark:bg-gray-700/50 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">Vencimento</th>
                                        <th class="px-6 py-3 bg-gray-50 dark:bg-gray-700/50 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">Status</th>
                                        <th class="px-6 py-3 bg-gray-50 dark:bg-gray-700/50 text-right text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">Ações</th>
                                    </tr>
                                </thead>
                                <tbody class="bg-white dark:bg-gray-800 divide-y divide-gray-200 dark:divide-gray-700">
                                    @foreach($accountReceivables as $accountReceivable)
                                        <tr data-id="{{ $accountReceivable->id }}" class="hover:bg-gray-50 dark:hover:bg-gray-700/50 transition-colors">
                                            <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900 dark:text-gray-100">
                                                <a href="{{ route('financial.accounts-receivable.show', $accountReceivable) }}" class="hover:text-indigo-600 dark:hover:text-indigo-400">
                                                    {{ $accountReceivable->number }}
                                                </a>
                                            </td>
                                            <td class="px-6 py-4 text-sm text-gray-500 dark:text-gray-400">
                                                {{ $accountReceivable->description }}
                                            </td>
                                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500 dark:text-gray-400">
                                                {{ optional($accountReceivable->client)->name ?? '-' }}
                                            </td>
                                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500 dark:text-gray-400">
                                                {{ optional($accountReceivable->project)->name ?? '-' }}
                                            </td>
                                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500 dark:text-gray-400 text-right">
                                                R$ {{ number_format($accountReceivable->amount, 2, ',', '.') }}
                                            </td>
                                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500 dark:text-gray-400">
                                                {{ $accountReceivable->due_date->format('d/m/Y') }}
                                            </td>
                                            <td class="px-6 py-4 whitespace-nowrap">
                                                <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full {{ $accountReceivable->status_color }}">
                                                    {{ $accountReceivable->status_label }}
                                                </span>
                                            </td>
                                            <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                                                <div class="flex items-center justify-end space-x-4">
                                                    <a href="{{ route('financial.accounts-receivable.show', $accountReceivable) }}" class="text-gray-600 dark:text-gray-400 hover:text-gray-900 dark:hover:text-gray-100" title="Visualizar">
                                                        <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                                                        </svg>
                                                    </a>
                                                    @can('manage finances')
                                                    <button 
                                                        onclick="openOffcanvas('account-receivable-offcanvas'); loadAccountReceivableForEdit({{ $accountReceivable->id }});" 
                                                        class="text-indigo-600 dark:text-indigo-400 hover:text-indigo-900 dark:hover:text-indigo-300"
                                                        title="Editar"
                                                    >
                                                        <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                                                        </svg>
                                                    </button>
                                                    @endcan
                                                </div>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                        <div class="mt-4 px-6">
                            {{ $accountReceivables->links() }}
                        </div>
                    @endif
                </div>
            </div>
        </div>
        </div>
    </div>

    <!-- Offcanvas para Nova/Editar Conta a Receber -->
    <x-offcanvas id="account-receivable-offcanvas" title="Nova Conta a Receber" width="w-full md:w-[700px]">
        <form method="POST" action="{{ route('financial.accounts-receivable.store') }}" enctype="multipart/form-data" id="accountReceivableForm">
            @csrf
            <input type="hidden" name="_method" id="account_receivable_method" value="POST">
            <input type="hidden" name="account_receivable_id" id="account_receivable_id" value="">

            <div class="space-y-6">
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div>
                        <label for="client_id" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Cliente</label>
                        <select name="client_id" id="client_id" class="mt-1 block w-full border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-gray-300 focus:border-indigo-500 dark:focus:border-indigo-400 focus:ring-indigo-500 dark:focus:ring-indigo-400 rounded-md shadow-sm">
                            <option value="">Selecione um cliente</option>
                            @foreach(\App\Models\Client::active()->orderBy('name')->get() as $client)
                                <option value="{{ $client->id }}">{{ $client->name ?? $client->trading_name }}</option>
                            @endforeach
                        </select>
                        @error('client_id')
                            <p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label for="project_id" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Obra</label>
                        <select name="project_id" id="project_id" class="mt-1 block w-full border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-gray-300 focus:border-indigo-500 dark:focus:border-indigo-400 focus:ring-indigo-500 dark:focus:ring-indigo-400 rounded-md shadow-sm">
                            <option value="">Selecione uma obra</option>
                            @foreach(\App\Models\Project::orderBy('name')->get() as $project)
                                <option value="{{ $project->id }}">{{ $project->name }} ({{ $project->code }})</option>
                            @endforeach
                        </select>
                        @error('project_id')
                            <p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                        @enderror
                    </div>
                </div>

                <div>
                    <label for="description" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Descrição *</label>
                    <input type="text" name="description" id="description" required class="mt-1 block w-full border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-gray-300 focus:border-indigo-500 dark:focus:border-indigo-400 focus:ring-indigo-500 dark:focus:ring-indigo-400 rounded-md shadow-sm">
                    @error('description')
                        <p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                    @enderror
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div>
                        <label for="amount" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Valor *</label>
                        <input type="number" name="amount" id="amount" step="0.01" min="0" required class="mt-1 block w-full border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-gray-300 focus:border-indigo-500 dark:focus:border-indigo-400 focus:ring-indigo-500 dark:focus:ring-indigo-400 rounded-md shadow-sm">
                        @error('amount')
                            <p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label for="due_date" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Data de Vencimento *</label>
                        <input type="date" name="due_date" id="due_date" required class="mt-1 block w-full border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-gray-300 focus:border-indigo-500 dark:focus:border-indigo-400 focus:ring-indigo-500 dark:focus:ring-indigo-400 rounded-md shadow-sm">
                        @error('due_date')
                            <p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                        @enderror
                    </div>
                </div>

                <div>
                    <label for="status" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Status *</label>
                    <select name="status" id="status" required class="mt-1 block w-full border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-gray-300 focus:border-indigo-500 dark:focus:border-indigo-400 focus:ring-indigo-500 dark:focus:ring-indigo-400 rounded-md shadow-sm">
                        @foreach(\App\Models\AccountReceivable::getStatusOptions() as $value => $label)
                            <option value="{{ $value }}">{{ $label }}</option>
                        @endforeach
                    </select>
                    @error('status')
                        <p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <x-document-upload 
                        name="document_file"
                        label="Documento"
                        :existingDocumentPath="null"
                    />
                </div>

                <div>
                    <label for="notes" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Observações</label>
                    <textarea name="notes" id="notes" rows="3" class="mt-1 block w-full border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-gray-300 focus:border-indigo-500 dark:focus:border-indigo-400 focus:ring-indigo-500 dark:focus:ring-indigo-400 rounded-md shadow-sm"></textarea>
                    @error('notes')
                        <p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                    @enderror
                </div>
            </div>

            <div class="flex items-center justify-end gap-4 mt-6 pt-4 border-t border-gray-200 dark:border-gray-700">
                <button 
                    type="button"
                    onclick="closeOffcanvas('account-receivable-offcanvas')"
                    class="inline-flex items-center px-4 py-2 bg-gray-600 dark:bg-gray-700 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-gray-700 dark:hover:bg-gray-600 focus:bg-gray-700 active:bg-gray-900 focus:outline-none focus:ring-2 focus:ring-gray-500 focus:ring-offset-2 transition ease-in-out duration-150"
                >
                    Cancelar
                </button>
                <button 
                    type="submit" 
                    id="accountReceivableSubmitBtn"
                    class="inline-flex items-center px-4 py-2 bg-indigo-600 dark:bg-indigo-700 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-indigo-700 dark:hover:bg-indigo-600 focus:bg-indigo-700 active:bg-indigo-900 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 transition ease-in-out duration-150 disabled:opacity-50"
                >
                    <span id="accountReceivableSubmitText">Salvar</span>
                    <span id="accountReceivableSubmitSpinner" class="hidden ml-2">
                        <svg class="animate-spin h-4 w-4" fill="none" viewBox="0 0 24 24">
                            <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                            <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                        </svg>
                    </span>
                </button>
            </div>
        </form>
    </x-offcanvas>

    <script>
        function resetAccountReceivableForm() {
            document.getElementById('accountReceivableForm').reset();
            document.getElementById('account_receivable_method').value = 'POST';
            document.getElementById('account_receivable_id').value = '';
            document.getElementById('accountReceivableForm').action = '{{ route('financial.accounts-receivable.store') }}';
            const titleElement = document.querySelector('#account-receivable-offcanvas h2');
            if (titleElement) titleElement.textContent = 'Nova Conta a Receber';
        }

        async function loadAccountReceivableForEdit(id) {
            try {
                const response = await fetch(`{{ url('financial/accounts-receivable') }}/${id}/edit-data`);
                if (!response.ok) throw new Error('Erro ao carregar dados');
                const accountReceivable = await response.json();
                
                document.getElementById('account_receivable_id').value = accountReceivable.id;
                document.getElementById('account_receivable_method').value = 'PUT';
                document.getElementById('accountReceivableForm').action = `{{ url('financial/accounts-receivable') }}/${accountReceivable.id}`;
                const titleElement = document.querySelector('#account-receivable-offcanvas h2');
                if (titleElement) titleElement.textContent = 'Editar Conta a Receber';
                
                document.getElementById('client_id').value = accountReceivable.client_id || '';
                document.getElementById('project_id').value = accountReceivable.project_id || '';
                document.getElementById('description').value = accountReceivable.description || '';
                document.getElementById('amount').value = accountReceivable.amount || '';
                document.getElementById('due_date').value = accountReceivable.due_date || '';
                document.getElementById('status').value = accountReceivable.status || 'pending';
                document.getElementById('notes').value = accountReceivable.notes || '';
                
                // Atualizar componente de documento se existir
                if (accountReceivable.document_file) {
                    const existingDocDiv = document.getElementById('existing-document-document_file');
                    const docNameEl = document.getElementById('document-name-document_file');
                    const docLinkEl = document.getElementById('document-link-document_file');
                    if (existingDocDiv && docNameEl && docLinkEl) {
                        existingDocDiv.style.display = 'flex';
                        docNameEl.textContent = accountReceivable.document_file.split('/').pop();
                        docLinkEl.href = '/' + accountReceivable.document_file.replace(/^\/+/, '');
                    }
                }
            } catch (error) {
                console.error('Erro ao carregar conta a receber:', error);
                alert('Erro ao carregar dados da conta a receber');
            }
        }

        document.getElementById('accountReceivableForm')?.addEventListener('submit', function(e) {
            const submitBtn = document.getElementById('accountReceivableSubmitBtn');
            const submitText = document.getElementById('accountReceivableSubmitText');
            const submitSpinner = document.getElementById('accountReceivableSubmitSpinner');
            
            if (submitBtn && submitText && submitSpinner) {
                submitBtn.disabled = true;
                submitText.textContent = 'Salvando...';
                submitSpinner.classList.remove('hidden');
            }
            
            const method = document.getElementById('account_receivable_method').value;
            if (method === 'PUT') {
                e.preventDefault();
                const formData = new FormData(this);
                formData.append('_method', 'PUT');
                
                fetch(this.action, {
                    method: 'POST',
                    body: formData,
                    headers: { 'X-Requested-With': 'XMLHttpRequest' }
                })
                .then(response => {
                    if (response.redirected) {
                        window.location.href = response.url;
                    }
                })
                .catch(error => {
                    console.error('Erro:', error);
                    alert('Erro ao salvar conta a receber');
                    if (submitBtn && submitText && submitSpinner) {
                        submitBtn.disabled = false;
                        submitText.textContent = 'Salvar';
                        submitSpinner.classList.add('hidden');
                    }
                });
            }
        });
    </script>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            if (window.webSocketNotificationManager && window.webSocketNotificationManager.echo) {
                console.log('[Accounts Receivable] WebSocket listeners ativos');
            }
        });
    </script>
</x-app-layout>

