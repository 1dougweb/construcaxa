<x-app-layout>
    <div class="py-6">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="flex justify-between items-center mb-6">
                <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
                    {{ __('Recibos') }}
                </h2>
                @can('manage finances')
                <button 
                    onclick="openOffcanvas('receipt-offcanvas'); resetReceiptForm();" 
                    class="inline-flex items-center px-4 py-2 bg-indigo-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-indigo-700"
                >
                    <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
                    </svg>
                    {{ __('Novo Recibo') }}
                </button>
                @endcan
            </div>
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-xl sm:rounded-lg border border-gray-200 dark:border-gray-700">
                <div class="p-6 bg-white dark:bg-gray-800">
                    @if($receipts->isEmpty())
                        <div class="text-center py-8"><h3 class="mt-2 text-sm font-medium text-gray-900 dark:text-gray-100">Nenhum recibo encontrado</h3></div>
                    @else
                        <div class="overflow-x-auto">
                            <table class="min-w-full divide-y divide-gray-200 dark:divide-gray-700" id="receipts-table">
                                <thead>
                                    <tr>
                                        <th class="px-6 py-3 bg-gray-50 dark:bg-gray-700/50 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase">Número</th>
                                        <th class="px-6 py-3 bg-gray-50 dark:bg-gray-700/50 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase">Cliente</th>
                                        <th class="px-6 py-3 bg-gray-50 dark:bg-gray-700/50 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase">Obra</th>
                                        <th class="px-6 py-3 bg-gray-50 dark:bg-gray-700/50 text-right text-xs font-medium text-gray-500 dark:text-gray-300 uppercase">Valor</th>
                                        <th class="px-6 py-3 bg-gray-50 dark:bg-gray-700/50 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase">Pagamento</th>
                                        <th class="px-6 py-3 bg-gray-50 dark:bg-gray-700/50 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase">Data</th>
                                        <th class="px-6 py-3 bg-gray-50 dark:bg-gray-700/50 text-right text-xs font-medium text-gray-500 dark:text-gray-300 uppercase">Ações</th>
                                    </tr>
                                </thead>
                                <tbody class="bg-white dark:bg-gray-800 divide-y divide-gray-200 dark:divide-gray-700">
                                    @foreach($receipts as $receipt)
                                        <tr data-id="{{ $receipt->id }}" class="hover:bg-gray-50 dark:hover:bg-gray-700/50 transition-colors">
                                            <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900 dark:text-gray-100">
                                                <a href="{{ route('financial.receipts.show', $receipt) }}" class="hover:text-indigo-600 dark:hover:text-indigo-400">{{ $receipt->number }}</a>
                                            </td>
                                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500 dark:text-gray-400">{{ optional($receipt->client)->name ?? '-' }}</td>
                                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500 dark:text-gray-400">{{ optional($receipt->project)->name ?? '-' }}</td>
                                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500 dark:text-gray-400 text-right">R$ {{ number_format($receipt->amount, 2, ',', '.') }}</td>
                                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500 dark:text-gray-400">{{ $receipt->payment_method_label }}</td>
                                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500 dark:text-gray-400">{{ $receipt->issue_date->format('d/m/Y') }}</td>
                                            <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                                                <div class="flex items-center justify-end space-x-4">
                                                    <a href="{{ route('financial.receipts.show', $receipt) }}" class="text-gray-600 dark:text-gray-400 hover:text-gray-900 dark:hover:text-gray-100">Ver</a>
                                                    @can('manage finances')
                                                    <button 
                                                        onclick="openOffcanvas('receipt-offcanvas'); loadReceiptForEdit({{ $receipt->id }});" 
                                                        class="text-indigo-600 dark:text-indigo-400 hover:text-indigo-900 dark:hover:text-indigo-300"
                                                        title="Editar"
                                                    >
                                                        Editar
                                                    </button>
                                                    <a href="{{ route('financial.receipts.pdf', $receipt) }}" target="_blank" class="text-green-600 dark:text-green-400 hover:text-green-900 dark:hover:text-green-300">PDF</a>
                                                    @endcan
                                                </div>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                        <div class="mt-4 px-6">{{ $receipts->links() }}</div>
                    @endif
                </div>
            </div>
        </div>
    </div>

    <!-- Offcanvas para Novo/Editar Recibo -->
    <x-offcanvas id="receipt-offcanvas" title="Novo Recibo" width="w-full md:w-[700px]">
        <form method="POST" action="{{ route('financial.receipts.store') }}" enctype="multipart/form-data" id="receiptForm">
            @csrf
            <input type="hidden" name="_method" id="receipt_method" value="POST">
            <input type="hidden" name="receipt_id" id="receipt_id" value="">

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

                <div>
                    <label for="invoice_id" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Nota Fiscal</label>
                    <select name="invoice_id" id="invoice_id" class="mt-1 block w-full border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-gray-300 focus:border-indigo-500 dark:focus:border-indigo-400 focus:ring-indigo-500 dark:focus:ring-indigo-400 rounded-md shadow-sm">
                        <option value="">Selecione</option>
                        @foreach(\App\Models\Invoice::where('status', '!=', 'cancelled')->with('client')->orderBy('created_at', 'desc')->get() as $invoice)
                            <option value="{{ $invoice->id }}">{{ $invoice->number }} - R$ {{ number_format($invoice->total_amount, 2, ',', '.') }}</option>
                        @endforeach
                    </select>
                    @error('invoice_id')
                        <p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                    @enderror
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div>
                        <label for="issue_date" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Data *</label>
                        <input type="date" name="issue_date" id="issue_date" required value="{{ date('Y-m-d') }}" class="mt-1 block w-full border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-gray-300 focus:border-indigo-500 dark:focus:border-indigo-400 focus:ring-indigo-500 dark:focus:ring-indigo-400 rounded-md shadow-sm">
                        @error('issue_date')
                            <p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label for="amount" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Valor *</label>
                        <input type="number" name="amount" id="amount" step="0.01" required class="mt-1 block w-full border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-gray-300 focus:border-indigo-500 dark:focus:border-indigo-400 focus:ring-indigo-500 dark:focus:ring-indigo-400 rounded-md shadow-sm">
                        @error('amount')
                            <p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                        @enderror
                    </div>
                </div>

                <div>
                    <label for="payment_method" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Forma de Pagamento *</label>
                    <select name="payment_method" id="payment_method" required class="mt-1 block w-full border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-gray-300 focus:border-indigo-500 dark:focus:border-indigo-400 focus:ring-indigo-500 dark:focus:ring-indigo-400 rounded-md shadow-sm">
                        @foreach(\App\Models\Receipt::getPaymentMethodOptions() as $value => $label)
                            <option value="{{ $value }}">{{ $label }}</option>
                        @endforeach
                    </select>
                    @error('payment_method')
                        <p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label for="description" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Descrição</label>
                    <input type="text" name="description" id="description" class="mt-1 block w-full border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-gray-300 focus:border-indigo-500 dark:focus:border-indigo-400 focus:ring-indigo-500 dark:focus:ring-indigo-400 rounded-md shadow-sm">
                    @error('description')
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
                    onclick="closeOffcanvas('receipt-offcanvas')"
                    class="inline-flex items-center px-4 py-2 bg-gray-600 dark:bg-gray-700 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-gray-700 dark:hover:bg-gray-600 focus:bg-gray-700 active:bg-gray-900 focus:outline-none focus:ring-2 focus:ring-gray-500 focus:ring-offset-2 transition ease-in-out duration-150"
                >
                    Cancelar
                </button>
                <button 
                    type="submit" 
                    id="receiptSubmitBtn"
                    class="inline-flex items-center px-4 py-2 bg-indigo-600 dark:bg-indigo-700 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-indigo-700 dark:hover:bg-indigo-600 focus:bg-indigo-700 active:bg-indigo-900 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 transition ease-in-out duration-150 disabled:opacity-50"
                >
                    <span id="receiptSubmitText">Salvar</span>
                    <span id="receiptSubmitSpinner" class="hidden ml-2">
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
        function resetReceiptForm() {
            document.getElementById('receiptForm').reset();
            document.getElementById('receipt_method').value = 'POST';
            document.getElementById('receipt_id').value = '';
            document.getElementById('receiptForm').action = '{{ route('financial.receipts.store') }}';
            document.getElementById('issue_date').value = '{{ date('Y-m-d') }}';
            const titleElement = document.querySelector('#receipt-offcanvas h2');
            if (titleElement) titleElement.textContent = 'Novo Recibo';
        }

        async function loadReceiptForEdit(id) {
            try {
                const response = await fetch(`{{ url('financial/receipts') }}/${id}/edit-data`);
                if (!response.ok) throw new Error('Erro ao carregar dados');
                const receipt = await response.json();
                
                document.getElementById('receipt_id').value = receipt.id;
                document.getElementById('receipt_method').value = 'PUT';
                document.getElementById('receiptForm').action = `{{ url('financial/receipts') }}/${receipt.id}`;
                const titleElement = document.querySelector('#receipt-offcanvas h2');
                if (titleElement) titleElement.textContent = 'Editar Recibo';
                
                document.getElementById('client_id').value = receipt.client_id || '';
                document.getElementById('project_id').value = receipt.project_id || '';
                document.getElementById('invoice_id').value = receipt.invoice_id || '';
                document.getElementById('issue_date').value = receipt.issue_date || '';
                document.getElementById('amount').value = receipt.amount || '';
                document.getElementById('payment_method').value = receipt.payment_method || 'cash';
                document.getElementById('description').value = receipt.description || '';
                document.getElementById('notes').value = receipt.notes || '';
                
                // Atualizar componente de documento se existir
                if (receipt.document_file) {
                    const existingDocDiv = document.getElementById('existing-document-document_file');
                    const docNameEl = document.getElementById('document-name-document_file');
                    const docLinkEl = document.getElementById('document-link-document_file');
                    if (existingDocDiv && docNameEl && docLinkEl) {
                        existingDocDiv.style.display = 'flex';
                        docNameEl.textContent = receipt.document_file.split('/').pop();
                        docLinkEl.href = '/' + receipt.document_file.replace(/^\/+/, '');
                    }
                }
            } catch (error) {
                console.error('Erro ao carregar recibo:', error);
                alert('Erro ao carregar dados do recibo');
            }
        }

        document.getElementById('receiptForm')?.addEventListener('submit', function(e) {
            const submitBtn = document.getElementById('receiptSubmitBtn');
            const submitText = document.getElementById('receiptSubmitText');
            const submitSpinner = document.getElementById('receiptSubmitSpinner');
            
            if (submitBtn && submitText && submitSpinner) {
                submitBtn.disabled = true;
                submitText.textContent = 'Salvando...';
                submitSpinner.classList.remove('hidden');
            }
            
            const method = document.getElementById('receipt_method').value;
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
                    alert('Erro ao salvar recibo');
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
                console.log('[Receipts] WebSocket listeners ativos');
            }
        });
    </script>
</x-app-layout>

