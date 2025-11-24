<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('Configurações de Email') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-xl sm:rounded-lg">
                <div class="p-6">
                    <!-- Success/Error Messages -->
                    @if(session('success'))
                        <div class="mb-4 p-4 bg-green-100 dark:bg-green-800 border border-green-400 dark:border-green-600 text-green-700 dark:text-green-200 rounded">
                            {{ session('success') }}
                        </div>
                    @endif

                    @if(session('test_success'))
                        <div class="mb-4 p-4 bg-green-100 dark:bg-green-800 border border-green-400 dark:border-green-600 text-green-700 dark:text-green-200 rounded">
                            {{ session('test_success') }}
                        </div>
                    @endif

                    @if(session('test_error'))
                        <div class="mb-4 p-4 bg-red-100 dark:bg-red-800 border border-red-400 dark:border-red-600 text-red-700 dark:text-red-200 rounded">
                            {{ session('test_error') }}
                        </div>
                    @endif

                    <form method="POST" action="{{ route('admin.email.update') }}">
                        @csrf
                        
                        <div class="mb-6">
                            <h3 class="text-lg font-medium text-gray-900 dark:text-gray-100 mb-4">Configurações do Servidor de Email</h3>
                            
                            <!-- Mailer Type -->
                            <div class="mb-4">
                                <label for="mail_mailer" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                                    Tipo de Mailer <span class="text-red-500">*</span>
                                </label>
                                <select 
                                    name="mail_mailer" 
                                    id="mail_mailer"
                                    class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-md shadow-sm bg-white dark:bg-gray-700 text-gray-900 dark:text-gray-100 focus:outline-none focus:ring-indigo-500 focus:border-indigo-500"
                                    required
                                >
                                    <option value="smtp" {{ old('mail_mailer', $settings['mail_mailer']) === 'smtp' ? 'selected' : '' }}>SMTP</option>
                                    <option value="sendmail" {{ old('mail_mailer', $settings['mail_mailer']) === 'sendmail' ? 'selected' : '' }}>Sendmail</option>
                                    <option value="log" {{ old('mail_mailer', $settings['mail_mailer']) === 'log' ? 'selected' : '' }}>Log (Apenas para testes)</option>
                                </select>
                                @error('mail_mailer')
                                    <p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                                @enderror
                            </div>

                            <!-- SMTP Settings (shown when mailer is SMTP) -->
                            <div id="smtp-settings" style="display: {{ old('mail_mailer', $settings['mail_mailer']) === 'smtp' ? 'block' : 'none' }};">
                                <!-- SMTP Host -->
                                <div class="mb-4">
                                    <label for="mail_host" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                                        Host SMTP
                                    </label>
                                    <input 
                                        type="text" 
                                        name="mail_host" 
                                        id="mail_host"
                                        value="{{ old('mail_host', $settings['mail_host']) }}"
                                        class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-md shadow-sm bg-white dark:bg-gray-700 text-gray-900 dark:text-gray-100 focus:outline-none focus:ring-indigo-500 focus:border-indigo-500"
                                        placeholder="smtp.gmail.com"
                                    >
                                    @error('mail_host')
                                        <p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                                    @enderror
                                </div>

                                <!-- SMTP Port -->
                                <div class="mb-4">
                                    <label for="mail_port" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                                        Porta SMTP
                                    </label>
                                    <input 
                                        type="number" 
                                        name="mail_port" 
                                        id="mail_port"
                                        value="{{ old('mail_port', $settings['mail_port']) }}"
                                        class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-md shadow-sm bg-white dark:bg-gray-700 text-gray-900 dark:text-gray-100 focus:outline-none focus:ring-indigo-500 focus:border-indigo-500"
                                        placeholder="587"
                                        min="1"
                                        max="65535"
                                    >
                                    @error('mail_port')
                                        <p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                                    @enderror
                                </div>

                                <!-- SMTP Username -->
                                <div class="mb-4">
                                    <label for="mail_username" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                                        Usuário SMTP
                                    </label>
                                    <input 
                                        type="text" 
                                        name="mail_username" 
                                        id="mail_username"
                                        value="{{ old('mail_username', $settings['mail_username']) }}"
                                        class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-md shadow-sm bg-white dark:bg-gray-700 text-gray-900 dark:text-gray-100 focus:outline-none focus:ring-indigo-500 focus:border-indigo-500"
                                        placeholder="seu-email@gmail.com"
                                    >
                                    @error('mail_username')
                                        <p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                                    @enderror
                                </div>

                                <!-- SMTP Password -->
                                <div class="mb-4">
                                    <label for="mail_password" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                                        Senha SMTP
                                    </label>
                                    <input 
                                        type="password" 
                                        name="mail_password" 
                                        id="mail_password"
                                        class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-md shadow-sm bg-white dark:bg-gray-700 text-gray-900 dark:text-gray-100 focus:outline-none focus:ring-indigo-500 focus:border-indigo-500"
                                        placeholder="Deixe em branco para manter a senha atual"
                                    >
                                    @error('mail_password')
                                        <p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                                    @enderror
                                    <p class="mt-1 text-sm text-gray-500 dark:text-gray-400">
                                        Deixe em branco se não deseja alterar a senha atual.
                                    </p>
                                </div>

                                <!-- SMTP Encryption -->
                                <div class="mb-4">
                                    <label for="mail_encryption" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                                        Criptografia
                                    </label>
                                    <select 
                                        name="mail_encryption" 
                                        id="mail_encryption"
                                        class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-md shadow-sm bg-white dark:bg-gray-700 text-gray-900 dark:text-gray-100 focus:outline-none focus:ring-indigo-500 focus:border-indigo-500"
                                    >
                                        <option value="tls" {{ old('mail_encryption', $settings['mail_encryption']) === 'tls' ? 'selected' : '' }}>TLS</option>
                                        <option value="ssl" {{ old('mail_encryption', $settings['mail_encryption']) === 'ssl' ? 'selected' : '' }}>SSL</option>
                                    </select>
                                    @error('mail_encryption')
                                        <p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                                    @enderror
                                </div>
                            </div>
                        </div>

                        <div class="mb-6">
                            <h3 class="text-lg font-medium text-gray-900 dark:text-gray-100 mb-4">Configurações do Remetente</h3>
                            
                            <!-- From Address -->
                            <div class="mb-4">
                                <label for="mail_from_address" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                                    Email do Remetente <span class="text-red-500">*</span>
                                </label>
                                <input 
                                    type="email" 
                                    name="mail_from_address" 
                                    id="mail_from_address"
                                    value="{{ old('mail_from_address', $settings['mail_from_address']) }}"
                                    class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-md shadow-sm bg-white dark:bg-gray-700 text-gray-900 dark:text-gray-100 focus:outline-none focus:ring-indigo-500 focus:border-indigo-500"
                                    placeholder="noreply@exemplo.com"
                                    required
                                >
                                @error('mail_from_address')
                                    <p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                                @enderror
                            </div>

                            <!-- From Name -->
                            <div class="mb-4">
                                <label for="mail_from_name" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                                    Nome do Remetente <span class="text-red-500">*</span>
                                </label>
                                <input 
                                    type="text" 
                                    name="mail_from_name" 
                                    id="mail_from_name"
                                    value="{{ old('mail_from_name', $settings['mail_from_name']) }}"
                                    class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-md shadow-sm bg-white dark:bg-gray-700 text-gray-900 dark:text-gray-100 focus:outline-none focus:ring-indigo-500 focus:border-indigo-500"
                                    placeholder="Nome da Empresa"
                                    required
                                >
                                @error('mail_from_name')
                                    <p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                                @enderror
                            </div>
                        </div>

                        <div class="flex justify-end space-x-3">
                            <x-button-loading variant="primary" type="submit">
                                Salvar Configurações
                            </x-button-loading>
                        </div>
                    </form>

                    <!-- Test Email Section -->
                    <div class="mt-8 pt-8 border-t border-gray-200 dark:border-gray-700">
                        <h3 class="text-lg font-medium text-gray-900 dark:text-gray-100 mb-4">Testar Configuração de Email</h3>
                        <p class="text-sm text-gray-600 dark:text-gray-400 mb-4">
                            Envie um email de teste para verificar se as configurações estão funcionando corretamente.
                        </p>
                        
                        <form method="POST" action="{{ route('admin.email.test') }}">
                            @csrf
                            <div class="flex gap-3">
                                <div class="flex-1">
                                    <input 
                                        type="email" 
                                        name="test_email" 
                                        id="test_email"
                                        value="{{ old('test_email') }}"
                                        class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-md shadow-sm bg-white dark:bg-gray-700 text-gray-900 dark:text-gray-100 focus:outline-none focus:ring-indigo-500 focus:border-indigo-500"
                                        placeholder="email@exemplo.com"
                                        required
                                    >
                                    @error('test_email')
                                        <p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                                    @enderror
                                </div>
                                <x-button-loading variant="secondary" type="submit">
                                    Enviar Email de Teste
                                </x-button-loading>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script>
        // Show/hide SMTP settings based on mailer selection
        document.getElementById('mail_mailer').addEventListener('change', function() {
            const smtpSettings = document.getElementById('smtp-settings');
            if (this.value === 'smtp') {
                smtpSettings.style.display = 'block';
            } else {
                smtpSettings.style.display = 'none';
            }
        });
    </script>
</x-app-layout>

