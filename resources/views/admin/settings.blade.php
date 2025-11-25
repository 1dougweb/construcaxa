<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('Configurações do Sistema') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-xl sm:rounded-lg">
                <div class="p-6">
                    <form method="POST" action="{{ route('admin.settings.update') }}" 
                          @submit="localStorage.setItem('notificationSoundFile', soundFile); localStorage.setItem('notificationSoundEnabled', soundEnabled);">
                        @csrf
                        
                        <div class="mb-6">
                            <h3 class="text-lg font-medium text-gray-900 dark:text-gray-100 mb-4">Google Maps API</h3>
                            
                            <div class="mb-4">
                                <label for="google_maps_api_key" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                                    Chave da API do Google Maps
                                </label>
                                <input 
                                    type="text" 
                                    name="google_maps_api_key" 
                                    id="google_maps_api_key"
                                    value="{{ old('google_maps_api_key', $settings['google_maps_api_key']) }}"
                                    class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-md shadow-sm bg-white dark:bg-gray-700 text-gray-900 dark:text-gray-100 focus:outline-none focus:ring-indigo-500 focus:border-indigo-500"
                                    placeholder="AIzaSyBNLrJhOMz6idD05pzfn5lhA-TAw-mAZCU"
                                >
                                @error('google_maps_api_key')
                                    <p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                                @enderror
                                <p class="mt-1 text-sm text-gray-500 dark:text-gray-400">
                                    Chave necessária para exibir mapas no dashboard. 
                                    <a href="https://developers.google.com/maps/documentation/javascript/get-api-key" 
                                       target="_blank" 
                                       class="text-indigo-600 dark:text-indigo-400 hover:text-indigo-800 dark:hover:text-indigo-300">
                                        Obter chave da API
                                    </a>
                                </p>
                            </div>
                        </div>

                        <div class="mb-6 border-t border-gray-200 dark:border-gray-700 pt-6">
                            <h3 class="text-lg font-medium text-gray-900 dark:text-gray-100 mb-4">Configurações de Notificações</h3>
                            
                            <div class="mb-4" x-data="notificationSettings()" x-init="init()">
                                <div class="mb-4">
                                    <label class="flex items-center">
                                        <input 
                                            type="checkbox" 
                                            x-model="soundEnabled"
                                            @change="toggleSound()"
                                            class="rounded border-gray-300 dark:border-gray-600 text-indigo-600 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 dark:bg-gray-700 dark:checked:bg-indigo-600"
                                        >
                                        <span class="ml-2 text-sm text-gray-700 dark:text-gray-300">
                                            Ativar som de notificação
                                        </span>
                                    </label>
                                    <p class="mt-1 ml-6 text-xs text-gray-500 dark:text-gray-400">
                                        Reproduzir um som quando uma nova notificação for recebida
                                    </p>
                                </div>

                                <div class="mb-4">
                                    <label for="notification_sound" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                                        Som da Notificação
                                    </label>
                                    <select 
                                        id="notification_sound"
                                        x-model="soundFile"
                                        x-bind:value="soundFile"
                                        @change="setSoundFile($event.target.value)"
                                        class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-md shadow-sm bg-white dark:bg-gray-700 text-gray-900 dark:text-gray-100 focus:outline-none focus:ring-indigo-500 focus:border-indigo-500"
                                    >
                                        <option value="default">Padrão (beep gerado)</option>
                                        <template x-for="sound in availableSounds" :key="sound">
                                            <option :value="sound" :selected="soundFile === sound" x-text="sound"></option>
                                        </template>
                                    </select>
                                    <p class="mt-1 text-sm text-gray-500 dark:text-gray-400">
                                        Escolha o som que será reproduzido quando uma notificação for recebida. 
                                        Coloque arquivos MP3 na pasta <code class="text-xs bg-gray-100 dark:bg-gray-700 px-1 py-0.5 rounded">/public/sounds/</code>
                                    </p>
                                    <div class="flex gap-2 mt-2">
                                        <button 
                                            type="button"
                                            @click="testSound()"
                                            class="px-4 py-2 bg-indigo-600 text-white text-sm rounded-md hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2"
                                        >
                                            <i class="bi bi-play-circle mr-1"></i> Testar Som
                                        </button>
                                        <button 
                                            type="button"
                                            @click="sendTestNotification()"
                                            :disabled="sendingTest"
                                            class="px-4 py-2 bg-green-600 text-white text-sm rounded-md hover:bg-green-700 focus:outline-none focus:ring-2 focus:ring-green-500 focus:ring-offset-2 disabled:opacity-50 disabled:cursor-not-allowed"
                                        >
                                            <i :class="sendingTest ? 'bi bi-arrow-repeat animate-spin mr-1' : 'bi bi-bell-fill mr-1'"></i>
                                            <span x-text="sendingTest ? 'Enviando...' : 'Enviar Notificação de Teste'"></span>
                                        </button>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="flex justify-end">
                            <x-button-loading variant="primary" type="submit">
                                Salvar Configurações
                            </x-button-loading>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <script>
        function notificationSettings() {
            return {
                soundEnabled: localStorage.getItem('notificationSoundEnabled') !== 'false',
                soundFile: localStorage.getItem('notificationSoundFile') || 'default',
                availableSounds: ['default'],
                sendingTest: false,
                audioContext: null,
                audioElement: null,
                
                async init() {
                    // Carregar o valor salvo do localStorage primeiro
                    const savedSound = localStorage.getItem('notificationSoundFile');
                    if (savedSound) {
                        this.soundFile = savedSound;
                    }
                    
                    await this.loadAvailableSounds();
                    
                    // Garantir que o valor do select está sincronizado após carregar os sons
                    this.$nextTick(() => {
                        const currentSound = localStorage.getItem('notificationSoundFile') || 'default';
                        if (this.soundFile !== currentSound) {
                            this.soundFile = currentSound;
                        }
                        // Forçar atualização do select
                        const selectElement = document.getElementById('notification_sound');
                        if (selectElement && selectElement.value !== this.soundFile) {
                            selectElement.value = this.soundFile;
                        }
                        // Sincronizar com notification-system.js se existir
                        if (window.notificationSystem) {
                            window.notificationSystem.setSoundFile(currentSound);
                        }
                    });
                },
                
                async loadAvailableSounds() {
                    try {
                        const response = await fetch('{{ route('notifications.sounds') }}');
                        const data = await response.json();
                        this.availableSounds = ['default', ...data.sounds.filter(s => s !== 'default')];
                    } catch (error) {
                        console.error('Erro ao carregar sons disponíveis:', error);
                        this.availableSounds = ['default'];
                    }
                },
                
                toggleSound() {
                    this.soundEnabled = !this.soundEnabled;
                    localStorage.setItem('notificationSoundEnabled', this.soundEnabled);
                    if (this.soundEnabled) {
                        this.testSound();
                    }
                },
                
                setSoundFile(filename) {
                    console.log('Definindo som:', filename);
                    this.soundFile = filename;
                    localStorage.setItem('notificationSoundFile', filename);
                    // Forçar atualização do select
                    const selectElement = document.getElementById('notification_sound');
                    if (selectElement) {
                        selectElement.value = filename;
                    }
                    // Sincronizar com notification-system.js se existir
                    if (window.notificationSystem) {
                        window.notificationSystem.setSoundFile(filename);
                    }
                    // Sincronizar com o dropdown de notificações no app-layout se existir
                    const notificationDropdown = document.querySelector('[x-data*="notificationDropdown"]');
                    if (notificationDropdown && window.Alpine) {
                        const alpineData = window.Alpine.$data(notificationDropdown);
                        if (alpineData && alpineData.setSoundFile) {
                            alpineData.setSoundFile(filename);
                        }
                    }
                    if (this.soundEnabled) {
                        this.testSound();
                    }
                },
                
                testSound() {
                    if (!this.soundEnabled || !this.soundFile || this.soundFile === 'default') return;
                    this.playSoundFile(this.soundFile);
                },
                
                playDefaultSound() {
                    try {
                        if (!this.audioContext) {
                            this.audioContext = new (window.AudioContext || window.webkitAudioContext)();
                        }
                        
                        const oscillator = this.audioContext.createOscillator();
                        const gainNode = this.audioContext.createGain();
                        
                        oscillator.connect(gainNode);
                        gainNode.connect(this.audioContext.destination);
                        
                        oscillator.frequency.setValueAtTime(800, this.audioContext.currentTime);
                        oscillator.frequency.setValueAtTime(600, this.audioContext.currentTime + 0.1);
                        oscillator.type = 'sine';
                        
                        gainNode.gain.setValueAtTime(0, this.audioContext.currentTime);
                        gainNode.gain.linearRampToValueAtTime(0.3, this.audioContext.currentTime + 0.01);
                        gainNode.gain.exponentialRampToValueAtTime(0.01, this.audioContext.currentTime + 0.2);
                        
                        oscillator.start(this.audioContext.currentTime);
                        oscillator.stop(this.audioContext.currentTime + 0.2);
                    } catch (error) {
                        console.warn('Erro ao tocar som padrão:', error);
                    }
                },
                
                playSoundFile(filename) {
                    try {
                        if (this.audioElement) {
                            this.audioElement.pause();
                            this.audioElement.currentTime = 0;
                        }
                        this.audioElement = new Audio(`/sounds/${filename}`);
                        this.audioElement.volume = 0.5;
                        this.audioElement.play().catch(error => {
                            console.warn('Erro ao tocar arquivo de som:', error);
                            this.playDefaultSound();
                        });
                    } catch (error) {
                        console.warn('Erro ao criar áudio:', error);
                        this.playDefaultSound();
                    }
                },
                
                async sendTestNotification() {
                    if (this.sendingTest) return;
                    
                    this.sendingTest = true;
                    try {
                        const csrfToken = document.querySelector('meta[name="csrf-token"]')?.content;
                        if (!csrfToken) {
                            console.error('CSRF token não encontrado');
                            alert('Erro: Token CSRF não encontrado');
                            this.sendingTest = false;
                            return;
                        }

                        const response = await fetch('{{ route('notifications.test') }}', {
                            method: 'POST',
                            headers: {
                                'X-CSRF-TOKEN': csrfToken,
                                'Content-Type': 'application/json',
                                'Accept': 'application/json',
                            },
                        });

                        if (!response.ok) {
                            const errorData = await response.json().catch(() => ({ message: 'Erro desconhecido' }));
                            console.error('Erro na resposta:', response.status, errorData);
                            alert('Erro ao enviar notificação: ' + (errorData.message || response.status));
                            this.sendingTest = false;
                            return;
                        }

                        const data = await response.json();
                        if (data.success) {
                            // A notificação será recebida via WebSocket e tratada automaticamente
                            if (window.showNotification) {
                                window.showNotification(data.message || 'Notificação de teste enviada!', 'success');
                            }
                        } else {
                            if (window.showNotification) {
                                window.showNotification(data.message || 'Erro ao enviar notificação de teste.', 'error');
                            }
                        }
                    } catch (error) {
                        console.error('Erro ao enviar notificação de teste:', error);
                        if (window.showNotification) {
                            window.showNotification('Erro de rede ao enviar notificação de teste.', 'error');
                        }
                    } finally {
                        this.sendingTest = false;
                    }
                },
            };
        }
    </script>
</x-app-layout>





