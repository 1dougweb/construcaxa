<x-app-layout>
    <div class="py-6">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="flex justify-between items-center mb-6">
                <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
                        {{ __('Notas Fiscais') }}
                    </h2>
                    @can('manage finances')
                    <button 
                        onclick="openOffcanvas('invoice-offcanvas'); resetInvoiceForm();" 
                        class="inline-flex items-center px-4 py-2 bg-indigo-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-indigo-700"
                    >
                        <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
                        </svg>
                        {{ __('Nova Nota Fiscal') }}
                    </button>
                    @endcan
                </div>
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-xl sm:rounded-lg border border-gray-200 dark:border-gray-700">
                <div class="p-6 lg:p-8 bg-white dark:bg-gray-800">
                    @if($invoices->isEmpty())
                        <div class="text-center py-8">
                            <h3 class="mt-2 text-sm font-medium text-gray-900 dark:text-gray-100">Nenhuma nota fiscal encontrada</h3>
                        </div>
                    @else
                        <div class="overflow-x-auto">
                            <table class="min-w-full divide-y divide-gray-200 dark:divide-gray-700" id="invoices-table">
                                <thead>
                                    <tr>
                                        <th class="px-6 py-3 bg-gray-50 dark:bg-gray-700/50 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase">Número</th>
                                        <th class="px-6 py-3 bg-gray-50 dark:bg-gray-700/50 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase">Cliente</th>
                                        <th class="px-6 py-3 bg-gray-50 dark:bg-gray-700/50 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase">Obra</th>
                                        <th class="px-6 py-3 bg-gray-50 dark:bg-gray-700/50 text-right text-xs font-medium text-gray-500 dark:text-gray-300 uppercase">Valor</th>
                                        <th class="px-6 py-3 bg-gray-50 dark:bg-gray-700/50 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase">Data</th>
                                        <th class="px-6 py-3 bg-gray-50 dark:bg-gray-700/50 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase">Status</th>
                                        <th class="px-6 py-3 bg-gray-50 dark:bg-gray-700/50 text-right text-xs font-medium text-gray-500 dark:text-gray-300 uppercase">Ações</th>
                                    </tr>
                                </thead>
                                <tbody class="bg-white dark:bg-gray-800 divide-y divide-gray-200 dark:divide-gray-700">
                                    @foreach($invoices as $invoice)
                                        <tr data-id="{{ $invoice->id }}" class="hover:bg-gray-50 dark:hover:bg-gray-700/50 transition-colors">
                                            <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900 dark:text-gray-100">
                                                <a href="{{ route('financial.invoices.show', $invoice) }}" class="hover:text-indigo-600 dark:hover:text-indigo-400">{{ $invoice->number }}</a>
                                            </td>
                                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500 dark:text-gray-400">{{ optional($invoice->client)->name ?? '-' }}</td>
                                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500 dark:text-gray-400">{{ optional($invoice->project)->name ?? '-' }}</td>
                                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500 dark:text-gray-400 text-right">R$ {{ number_format($invoice->total_amount, 2, ',', '.') }}</td>
                                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500 dark:text-gray-400">{{ $invoice->issue_date->format('d/m/Y') }}</td>
                                            <td class="px-6 py-4 whitespace-nowrap">
                                                <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full {{ $invoice->status_color }}">{{ $invoice->status_label }}</span>
                                            </td>
                                            <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                                                <div class="flex items-center justify-end space-x-4">
                                                    <a href="{{ route('financial.invoices.show', $invoice) }}" class="text-gray-600 dark:text-gray-400 hover:text-gray-900 dark:hover:text-gray-100">Ver</a>
                                                    @can('manage finances')
                                                    <button 
                                                        onclick="openOffcanvas('invoice-offcanvas'); loadInvoiceForEdit({{ $invoice->id }});" 
                                                        class="text-indigo-600 dark:text-indigo-400 hover:text-indigo-900 dark:hover:text-indigo-300"
                                                        title="Editar"
                                                    >
                                                        Editar
                                                    </button>
                                                    <a href="{{ route('financial.invoices.pdf', $invoice) }}" target="_blank" class="text-green-600 dark:text-green-400 hover:text-green-900 dark:hover:text-green-300">PDF</a>
                                                    @endcan
                                                </div>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                        <div class="mt-4 px-6">{{ $invoices->links() }}</div>
                    @endif
                </div>
            </div>
        </div>
        </div>
    </div>

    <!-- Offcanvas para Nova/Editar Nota Fiscal -->
    <x-offcanvas id="invoice-offcanvas" title="Nova Nota Fiscal" width="w-full md:w-[700px]">
        <form method="POST" action="{{ route('financial.invoices.store') }}" enctype="multipart/form-data" id="invoiceForm">
            @csrf
            <input type="hidden" name="_method" id="invoice_method" value="POST">
            <input type="hidden" name="invoice_id" id="invoice_id" value="">

            <div class="space-y-6">
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div>
                        <label for="client_id" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Cliente *</label>
                        <select name="client_id" id="client_id" required class="mt-1 block w-full border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-gray-300 focus:border-indigo-500 dark:focus:border-indigo-400 focus:ring-indigo-500 dark:focus:ring-indigo-400 rounded-md shadow-sm">
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
                            <option value="">Selecione</option>
                            @foreach(\App\Models\Project::orderBy('name')->get() as $project)
                                <option value="{{ $project->id }}">{{ $project->name }}</option>
                            @endforeach
                        </select>
                        @error('project_id')
                            <p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                        @enderror
                    </div>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div>
                        <label for="issue_date" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Data de Emissão *</label>
                        <input type="date" name="issue_date" id="issue_date" required value="{{ date('Y-m-d') }}" class="mt-1 block w-full border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-gray-300 focus:border-indigo-500 dark:focus:border-indigo-400 focus:ring-indigo-500 dark:focus:ring-indigo-400 rounded-md shadow-sm">
                        @error('issue_date')
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

                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div>
                        <label for="subtotal" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Subtotal *</label>
                        <input type="number" name="subtotal" id="subtotal" step="0.01" required class="mt-1 block w-full border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-gray-300 focus:border-indigo-500 dark:focus:border-indigo-400 focus:ring-indigo-500 dark:focus:ring-indigo-400 rounded-md shadow-sm">
                        @error('subtotal')
                            <p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label for="tax_amount" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Impostos</label>
                        <input type="number" name="tax_amount" id="tax_amount" step="0.01" value="0" class="mt-1 block w-full border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-gray-300 focus:border-indigo-500 dark:focus:border-indigo-400 focus:ring-indigo-500 dark:focus:ring-indigo-400 rounded-md shadow-sm">
                        @error('tax_amount')
                            <p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                        @enderror
                    </div>
                </div>

                <div>
                    <label for="status" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Status *</label>
                    <select name="status" id="status" required class="mt-1 block w-full border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-gray-300 focus:border-indigo-500 dark:focus:border-indigo-400 focus:ring-indigo-500 dark:focus:ring-indigo-400 rounded-md shadow-sm">
                        @foreach(\App\Models\Invoice::getStatusOptions() as $value => $label)
                            <option value="{{ $value }}">{{ $label }}</option>
                        @endforeach
                    </select>
                    @error('status')
                        <p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                    @enderror
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div>
                        <x-document-upload 
                            name="xml_file"
                            label="Arquivo XML"
                            accept=".xml"
                            :existingDocumentPath="null"
                        />
                    </div>

                    <div>
                        <x-document-upload 
                            name="pdf_file"
                            label="Arquivo PDF"
                            accept=".pdf"
                            :existingDocumentPath="null"
                        />
                    </div>
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
                    onclick="closeOffcanvas('invoice-offcanvas')"
                    class="inline-flex items-center px-4 py-2 bg-gray-600 dark:bg-gray-700 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-gray-700 dark:hover:bg-gray-600 focus:bg-gray-700 active:bg-gray-900 focus:outline-none focus:ring-2 focus:ring-gray-500 focus:ring-offset-2 transition ease-in-out duration-150"
                >
                    Cancelar
                </button>
                <button 
                    type="submit" 
                    id="invoiceSubmitBtn"
                    class="inline-flex items-center px-4 py-2 bg-indigo-600 dark:bg-indigo-700 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-indigo-700 dark:hover:bg-indigo-600 focus:bg-indigo-700 active:bg-indigo-900 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 transition ease-in-out duration-150 disabled:opacity-50"
                >
                    <span id="invoiceSubmitText">Salvar</span>
                    <span id="invoiceSubmitSpinner" class="hidden ml-2">
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
        function resetInvoiceForm() {
            document.getElementById('invoiceForm').reset();
            document.getElementById('invoice_method').value = 'POST';
            document.getElementById('invoice_id').value = '';
            document.getElementById('invoiceForm').action = '{{ route('financial.invoices.store') }}';
            document.getElementById('issue_date').value = '{{ date('Y-m-d') }}';
            const titleElement = document.querySelector('#invoice-offcanvas h2');
            if (titleElement) titleElement.textContent = 'Nova Nota Fiscal';
        }

        async function loadInvoiceForEdit(id) {
            try {
                const response = await fetch(`{{ url('financial/invoices') }}/${id}/edit-data`);
                if (!response.ok) throw new Error('Erro ao carregar dados');
                const invoice = await response.json();
                
                document.getElementById('invoice_id').value = invoice.id;
                document.getElementById('invoice_method').value = 'PUT';
                document.getElementById('invoiceForm').action = `{{ url('financial/invoices') }}/${invoice.id}`;
                const titleElement = document.querySelector('#invoice-offcanvas h2');
                if (titleElement) titleElement.textContent = 'Editar Nota Fiscal';
                
                document.getElementById('client_id').value = invoice.client_id || '';
                document.getElementById('project_id').value = invoice.project_id || '';
                document.getElementById('issue_date').value = invoice.issue_date || '';
                document.getElementById('due_date').value = invoice.due_date || '';
                document.getElementById('subtotal').value = invoice.subtotal || '';
                document.getElementById('tax_amount').value = invoice.tax_amount || '0';
                document.getElementById('status').value = invoice.status || 'draft';
                document.getElementById('notes').value = invoice.notes || '';
                
                // Atualizar componentes de documentos se existirem
                if (invoice.xml_file) {
                    const existingDocDiv = document.getElementById('existing-document-xml_file');
                    const docNameEl = document.getElementById('document-name-xml_file');
                    const docLinkEl = document.getElementById('document-link-xml_file');
                    if (existingDocDiv && docNameEl && docLinkEl) {
                        existingDocDiv.style.display = 'flex';
                        docNameEl.textContent = invoice.xml_file.split('/').pop();
                        docLinkEl.href = '/' + invoice.xml_file.replace(/^\/+/, '');
                    }
                }
                if (invoice.pdf_file) {
                    const existingDocDiv = document.getElementById('existing-document-pdf_file');
                    const docNameEl = document.getElementById('document-name-pdf_file');
                    const docLinkEl = document.getElementById('document-link-pdf_file');
                    if (existingDocDiv && docNameEl && docLinkEl) {
                        existingDocDiv.style.display = 'flex';
                        docNameEl.textContent = invoice.pdf_file.split('/').pop();
                        docLinkEl.href = '/' + invoice.pdf_file.replace(/^\/+/, '');
                    }
                }
            } catch (error) {
                console.error('Erro ao carregar nota fiscal:', error);
                alert('Erro ao carregar dados da nota fiscal');
            }
        }

        document.getElementById('invoiceForm')?.addEventListener('submit', function(e) {
            const submitBtn = document.getElementById('invoiceSubmitBtn');
            const submitText = document.getElementById('invoiceSubmitText');
            const submitSpinner = document.getElementById('invoiceSubmitSpinner');
            
            if (submitBtn && submitText && submitSpinner) {
                submitBtn.disabled = true;
                submitText.textContent = 'Salvando...';
                submitSpinner.classList.remove('hidden');
            }
            
            const method = document.getElementById('invoice_method').value;
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
                    alert('Erro ao salvar nota fiscal');
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
                console.log('[Invoices] WebSocket listeners ativos');
            }
        });
    </script>
</x-app-layout>

