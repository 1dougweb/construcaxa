<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Configuração de Licença') }}
        </h2>
    </x-slot>

    <div class="py-6">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-xl sm:rounded-lg">
                <div class="p-6">
                    <!-- Status da Licença -->
                    <div id="license-status-container" class="mb-6">
                        @if ($isValid && $license)
                            <div class="bg-green-50 border border-green-200 rounded-lg p-4">
                                <div class="flex items-center">
                                    <svg class="w-6 h-6 text-green-600 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                    </svg>
                                    <div>
                                        <h3 class="text-lg font-semibold text-green-800">Licença Válida</h3>
                                        <p class="text-sm text-green-700">Sua licença está ativa e funcionando corretamente.</p>
                                        @if ($license->expires_at)
                                            <p class="text-sm text-green-600 mt-1">
                                                Expira em: <span id="expires-at">{{ $license->expires_at->format('d/m/Y H:i') }}</span>
                                            </p>
                                        @endif
                                        @if ($license->last_validated_at)
                                            <p class="text-sm text-green-600">
                                                Última validação: <span id="last-validated">{{ $license->last_validated_at->format('d/m/Y H:i') }}</span>
                                            </p>
                                        @endif
                                    </div>
                                </div>
                            </div>
                        @elseif ($license && !$isValid)
                            <div class="bg-yellow-50 border border-yellow-200 rounded-lg p-4">
                                <div class="flex items-center">
                                    <svg class="w-6 h-6 text-yellow-600 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"></path>
                                    </svg>
                                    <div>
                                        <h3 class="text-lg font-semibold text-yellow-800">Licença Inválida</h3>
                                        <p class="text-sm text-yellow-700" id="error-message">
                                            {{ $license->validation_error ?? 'A licença não pôde ser validada. Verifique as configurações abaixo.' }}
                                        </p>
                                    </div>
                                </div>
                            </div>
                        @else
                            <div class="bg-red-50 border border-red-200 rounded-lg p-4">
                                <div class="flex items-center">
                                    <svg class="w-6 h-6 text-red-600 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"></path>
                                    </svg>
                                    <div>
                                        <h3 class="text-lg font-semibold text-red-800">Licença Não Configurada</h3>
                                        <p class="text-sm text-red-700">Configure uma licença válida para continuar usando o sistema.</p>
                                    </div>
                                </div>
                            </div>
                        @endif
                    </div>

                    <form method="POST" action="{{ route('license.store') }}" id="license-form">
                        @csrf
                        
                        <div class="mb-6">
                            <h3 class="text-lg font-medium text-gray-900 mb-4">Informações da Licença</h3>
                            
                            <div class="mb-4">
                                <label for="license_token" class="block text-sm font-medium text-gray-700 mb-2">
                                    Código da Licença <span class="text-red-500">*</span>
                                </label>
                                <input 
                                    type="text" 
                                    name="license_token" 
                                    id="license_token"
                                    value="{{ old('license_token', $license->license_token ?? '') }}"
                                    class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 @error('license_token') border-red-500 @enderror"
                                    placeholder="Cole aqui o código da sua licença"
                                    required
                                >
                                @error('license_token')
                                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                                <p class="mt-1 text-sm text-gray-500">
                                    O código da licença fornecido pelo provedor de licenças.
                                </p>
                            </div>
                        </div>

                        <div class="flex justify-between items-center">
                            <button 
                                type="button"
                                id="validate-btn"
                                class="text-indigo-600 hover:text-indigo-800 font-medium"
                            >
                                Validar Licença
                            </button>
                            
                            <div class="flex gap-3">
                                <button 
                                    type="submit"
                                    class="bg-indigo-600 hover:bg-indigo-700 text-white font-bold py-2 px-4 rounded focus:outline-none focus:shadow-outline"
                                >
                                    Salvar e Validar
                                </button>
                            </div>
                        </div>
                    </form>

                    @if ($license)
                        <div class="mt-8 pt-6 border-t border-gray-200">
                            <h3 class="text-lg font-medium text-gray-900 mb-4">Informações Técnicas</h3>
                            <div class="bg-gray-50 rounded-lg p-4 space-y-2 text-sm">
                                <div class="flex justify-between">
                                    <span class="text-gray-600">Device ID:</span>
                                    <span class="font-mono text-gray-900" id="device-id">{{ $license->device_id ?? 'Não configurado' }}</span>
                                </div>
                                <div class="flex justify-between">
                                    <span class="text-gray-600">Domínio:</span>
                                    <span class="font-mono text-gray-900" id="domain">{{ $license->domain ?? 'Não configurado' }}</span>
                                </div>
                                <div class="flex justify-between">
                                    <span class="text-gray-600">Status:</span>
                                    <span class="font-semibold" id="status-text">
                                        <span class="{{ $license->is_valid ? 'text-green-600' : 'text-red-600' }}">
                                            {{ $license->is_valid ? 'Válida' : 'Inválida' }}
                                        </span>
                                    </span>
                                </div>
                            </div>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>

    @push('scripts')
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Garantir que notificationManager está disponível
            const showNotification = (message, type = 'success') => {
                if (window.notificationManager) {
                    window.notificationManager.show(message, type);
                } else {
                    console.warn('NotificationManager não disponível');
                }
            };

            const validateBtn = document.getElementById('validate-btn');
            const statusContainer = document.getElementById('license-status-container');
            
            // Validação em tempo real a cada 5 minutos
            let validationInterval = setInterval(validateLicense, 5 * 60 * 1000);
            
            // Validar ao clicar no botão
            if (validateBtn) {
                validateBtn.addEventListener('click', function() {
                    validateLicense(true);
                });
            }

            function validateLicense(showNotificationFlag = false) {
                fetch('{{ route("license.status") }}?validate=1', {
                    method: 'GET',
                    headers: {
                        'X-Requested-With': 'XMLHttpRequest',
                        'Accept': 'application/json',
                    },
                    credentials: 'same-origin'
                })
                .then(response => response.json())
                .then(data => {
                    updateStatusUI(data);
                    
                    if (showNotificationFlag) {
                        if (data.valid) {
                            showNotification('Licença validada com sucesso!', 'success');
                        } else {
                            showNotification(data.message || 'Erro ao validar licença', 'error');
                        }
                    } else {
                        // Notificação silenciosa apenas se mudou de válida para inválida
                        if (!data.valid && data.configured) {
                            showNotification('Atenção: Sua licença não está mais válida!', 'warning');
                        }
                    }
                })
                .catch(error => {
                    console.error('Erro ao validar licença:', error);
                    if (showNotificationFlag) {
                        showNotification('Erro ao conectar com o servidor de licenças', 'error');
                    }
                });
            }

            function updateStatusUI(data) {
                if (!statusContainer) return;

                let statusHTML = '';
                
                if (data.valid && data.configured) {
                    statusHTML = `
                        <div class="bg-green-50 border border-green-200 rounded-lg p-4">
                            <div class="flex items-center">
                                <svg class="w-6 h-6 text-green-600 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                </svg>
                                <div>
                                    <h3 class="text-lg font-semibold text-green-800">Licença Válida</h3>
                                    <p class="text-sm text-green-700">Sua licença está ativa e funcionando corretamente.</p>
                                    ${data.expires_at ? `<p class="text-sm text-green-600 mt-1">Expira em: ${new Date(data.expires_at).toLocaleString('pt-BR')}</p>` : ''}
                                    ${data.last_validated_at ? `<p class="text-sm text-green-600">Última validação: ${new Date(data.last_validated_at).toLocaleString('pt-BR')}</p>` : ''}
                                </div>
                            </div>
                        </div>
                    `;
                } else if (data.configured) {
                    statusHTML = `
                        <div class="bg-yellow-50 border border-yellow-200 rounded-lg p-4">
                            <div class="flex items-center">
                                <svg class="w-6 h-6 text-yellow-600 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"></path>
                                </svg>
                                <div>
                                    <h3 class="text-lg font-semibold text-yellow-800">Licença Inválida</h3>
                                    <p class="text-sm text-yellow-700" id="error-message">${data.message || 'A licença não pôde ser validada.'}</p>
                                </div>
                            </div>
                        </div>
                    `;
                } else {
                    statusHTML = `
                        <div class="bg-red-50 border border-red-200 rounded-lg p-4">
                            <div class="flex items-center">
                                <svg class="w-6 h-6 text-red-600 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"></path>
                                </svg>
                                <div>
                                    <h3 class="text-lg font-semibold text-red-800">Licença Não Configurada</h3>
                                    <p class="text-sm text-red-700">Configure uma licença válida para continuar usando o sistema.</p>
                                </div>
                            </div>
                        </div>
                    `;
                }

                statusContainer.innerHTML = statusHTML;

                // Atualizar status text se existir
                const statusText = document.getElementById('status-text');
                if (statusText) {
                    statusText.innerHTML = `<span class="${data.valid ? 'text-green-600' : 'text-red-600'}">${data.valid ? 'Válida' : 'Inválida'}</span>`;
                }
            }

            // Limpar intervalo quando a página for fechada
            window.addEventListener('beforeunload', function() {
                clearInterval(validationInterval);
            });
        });
    </script>
    @endpush
</x-app-layout>
