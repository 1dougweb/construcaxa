/**
 * Sistema de Notificações em Tempo Real
 * Gerencia WebSocket, atualiza contador e dropdown de notificações
 */
class NotificationSystem {
    constructor() {
        this.echo = null;
        this.unreadCount = 0;
        this.notifications = [];
        this.soundEnabled = this.getSoundPreference();
        this.soundFile = this.getSoundFile();
        this.audioContext = null;
        this.audioElement = null;
        this.init();
    }

    getSoundPreference() {
        const stored = localStorage.getItem('notificationSoundEnabled');
        return stored !== null ? stored === 'true' : true; // Padrão: habilitado
    }

    getSoundFile() {
        const soundFile = localStorage.getItem('notificationSoundFile');
        // Se não houver som selecionado, retornar null (não tocar nada)
        return soundFile || null;
    }

    setSoundEnabled(enabled) {
        this.soundEnabled = enabled;
        localStorage.setItem('notificationSoundEnabled', enabled);
    }

    setSoundFile(soundFile) {
        this.soundFile = soundFile;
        localStorage.setItem('notificationSoundFile', soundFile);
    }

    async ensureAudioContextReady() {
        // Garantir que o contexto de áudio esteja sempre pronto
        // Mas não tentar retomar se não houver interação do usuário
        if (!this.audioContext) {
            // Criar contexto apenas se já houver interação do usuário
            // Caso contrário, retornar false e não criar
            return false;
        }
        
        // Tentar retomar apenas se o contexto já existir e estiver suspenso
        if (this.audioContext.state === 'suspended') {
            try {
                await this.audioContext.resume();
            } catch (error) {
                // Se não conseguir retomar, não criar novo contexto automaticamente
                // Isso evita avisos do navegador sobre falta de interação do usuário
                return false;
            }
        }
        
        return this.audioContext.state === 'running';
    }

    async playNotificationSound() {
        // Verificar se o som está habilitado
        const soundEnabled = this.getSoundPreference();
        if (!soundEnabled) {
            return;
        }

        // Garantir que o contexto de áudio esteja pronto
        const contextReady = await this.ensureAudioContextReady();
        if (!contextReady) {
            // Se o contexto não estiver pronto (sem interação do usuário), não tocar som
            return;
        }

        // Usar soundFile da instância ou carregar do localStorage
        const soundFile = this.getSoundFile();
        
        // Se não houver som selecionado, não tocar nada
        if (!soundFile) {
            return;
        }
        
        // Tocar apenas o arquivo MP3 selecionado
        await this.playSoundFile(soundFile);
    }

    async playDefaultSound() {
        try {
            // Garantir que o contexto principal esteja pronto primeiro
            const contextReady = await this.ensureAudioContextReady();
            if (!contextReady) {
                // Se o contexto não estiver pronto, não tocar som
                return;
            }
            
            // Usar o contexto principal se disponível, caso contrário criar novo
            const ctx = this.audioContext || new (window.AudioContext || window.webkitAudioContext)();
            
            // Se suspenso, tentar retomar apenas uma vez (evitar múltiplas tentativas)
            if (ctx.state === 'suspended') {
                try {
                    await ctx.resume();
                } catch (e) {
                    // Se não conseguir retomar, não tocar som
                    return;
                }
            }
            
            // Se ainda não estiver rodando, não tocar som
            if (ctx.state !== 'running') {
                return;
            }
            
            // Criar oscilador
            const osc = ctx.createOscillator();
            const gain = ctx.createGain();
            
            osc.connect(gain);
            gain.connect(ctx.destination);
            
            // Configurar som mais alto e mais longo (3 bips)
            const now = ctx.currentTime;
            
            // Primeiro bip
            osc.frequency.setValueAtTime(800, now);
            osc.frequency.setValueAtTime(600, now + 0.1);
            osc.type = 'sine';
            
            gain.gain.setValueAtTime(0, now);
            gain.gain.linearRampToValueAtTime(1.0, now + 0.01);
            gain.gain.exponentialRampToValueAtTime(0.01, now + 0.2);
            
            // Segundo bip
            gain.gain.setValueAtTime(0, now + 0.25);
            gain.gain.linearRampToValueAtTime(1.0, now + 0.26);
            gain.gain.exponentialRampToValueAtTime(0.01, now + 0.45);
            
            // Terceiro bip
            gain.gain.setValueAtTime(0, now + 0.5);
            gain.gain.linearRampToValueAtTime(1.0, now + 0.51);
            gain.gain.exponentialRampToValueAtTime(0.01, now + 0.7);
            
            // Tocar
            osc.start(now);
            osc.stop(now + 0.7);
            
            // Manter referência do contexto para evitar garbage collection
            setTimeout(() => {
                try {
                    ctx.close();
                } catch (e) {
                    // Ignorar erro ao fechar
                }
            }, 2000);
        } catch (error) {
            console.error('Erro ao tocar som padrão:', error);
            // Tentar método alternativo ainda mais simples
            this.playSimpleBeep();
        }
    }
    
    playSimpleBeep() {
        try {
            // Só tocar se o contexto principal estiver disponível e rodando
            if (!this.audioContext || this.audioContext.state !== 'running') {
                return;
            }
            
            const ctx = this.audioContext;
            const osc = ctx.createOscillator();
            const gain = ctx.createGain();
            
            osc.connect(gain);
            gain.connect(ctx.destination);
            
            osc.frequency.value = 800;
            osc.type = 'sine';
            gain.gain.value = 1.0;
            
            osc.start();
            osc.stop(ctx.currentTime + 0.5);
        } catch (error) {
            // Erro silencioso
        }
    }


    async playSoundFile(soundFile) {
        try {
            const audioUrl = `/sounds/${soundFile}`;
            const audio = new Audio(audioUrl);
            audio.volume = 1.0;
            audio.preload = 'auto';
            
            let actuallyPlaying = false;
            let playTimeout;
            let pauseTimeout;
            let pauseCount = 0;
            
            audio.addEventListener('playing', () => {
                actuallyPlaying = true;
                pauseCount = 0;
                if (playTimeout) clearTimeout(playTimeout);
                if (pauseTimeout) clearTimeout(pauseTimeout);
            });
            audio.addEventListener('pause', () => {
                pauseCount++;
                
                // Se foi pausado e ainda não terminou, tentar retomar
                if (!audio.ended && pauseCount <= 3) {
                    pauseTimeout = setTimeout(async () => {
                        try {
                            await this.ensureAudioContextReady();
                            await audio.play();
                        } catch (error) {
                            // Silenciosamente ignora erro
                        }
                    }, 100);
                }
            });
            audio.addEventListener('error', (e) => {
                console.error('Erro no elemento de áudio:', e);
                if (playTimeout) clearTimeout(playTimeout);
                if (pauseTimeout) clearTimeout(pauseTimeout);
            });
            audio.addEventListener('ended', () => {
                actuallyPlaying = false;
                if (pauseTimeout) clearTimeout(pauseTimeout);
            });
            
            // Aguardar o áudio estar pronto
            return new Promise((resolve, reject) => {
                const handleCanPlay = () => {
                    audio.removeEventListener('canplay', handleCanPlay);
                    audio.removeEventListener('error', handleError);
                    
                    // Tentar tocar
                    const playPromise = audio.play();
                    if (playPromise !== undefined) {
                        playPromise
                            .then(() => {
                                
                                // Verificar se realmente está tocando após um breve delay
                                playTimeout = setTimeout(() => {
                                    if (audio.paused && actuallyPlaying) {
                                        audio.play().catch(() => {
                                            // Silenciosamente ignora erro
                                        });
                                    }
                                }, 300);
                                
                                resolve();
                            })
                            .catch((playError) => {
                                reject(playError);
                            });
                    } else {
                        resolve();
                    }
                };
                
                const handleError = (e) => {
                    audio.removeEventListener('canplay', handleCanPlay);
                    audio.removeEventListener('error', handleError);
                    reject(e);
                };
                
                if (audio.readyState >= 2) {
                    handleCanPlay();
                } else {
                    audio.addEventListener('canplay', handleCanPlay);
                    audio.addEventListener('error', handleError);
                    setTimeout(() => {
                        if (audio.readyState < 2) {
                            audio.removeEventListener('canplay', handleCanPlay);
                            audio.removeEventListener('error', handleError);
                            audio.play()
                                .then(resolve)
                                .catch((err) => {
                                    reject(err);
                                });
                        }
                    }, 2000);
                }
            });
        } catch (playError) {
            console.error('Erro ao tocar arquivo:', playError);
            throw playError;
        }
    }

    init() {
        // Aguardar DOM e Echo estarem prontos
        if (document.readyState === 'loading') {
            document.addEventListener('DOMContentLoaded', () => {
                this.initialize();
            });
        } else {
            this.initialize();
        }
    }
    
    async initialize() {
        // Carregar contador inicial apenas uma vez
        await this.loadUnreadCount();
        
        // Configurar WebSocket
        this.setupEcho();
        
        // Inicializar áudio
        this.initializeAudioContext();
        
        // NÃO fazer polling - WebSocket é suficiente!
        // O contador será atualizado apenas quando necessário via WebSocket
    }

    initializeAudioContext() {
        // Inicializar contexto de áudio apenas após interação do usuário
        // Isso resolve o problema de navegadores que bloqueiam áudio sem interação
        let audioInitialized = false;
        
        const initAudio = async () => {
            if (audioInitialized) return;
            try {
                await this.ensureAudioContextReady();
                audioInitialized = true;
            } catch (error) {
                // Erro silencioso - tentará novamente na próxima interação
            }
        };

        // Tentar inicializar em vários eventos de interação (apenas uma vez)
        // Isso garante que o contexto seja inicializado após o primeiro gesto do usuário
        const initOnInteraction = () => {
            if (!audioInitialized) {
                initAudio();
            }
        };
        
        ['click', 'touchstart', 'keydown', 'mousedown'].forEach(eventType => {
            document.addEventListener(eventType, initOnInteraction, { once: true });
        });
        
        // Não tentar inicializar automaticamente - apenas após interação do usuário
        // O setInterval foi removido para evitar avisos do navegador
    }

    setupEcho() {
        // Não tentar conectar em páginas de autenticação
        const isAuthPage = window.location.pathname.includes('/login') || 
                          window.location.pathname.includes('/register') || 
                          window.location.pathname.includes('/password/reset') ||
                          window.location.pathname.includes('/forgot-password') ||
                          document.querySelector('body[data-auth-page]');
        
        if (isAuthPage || !window.Laravel?.user) {
            return;
        }

        // Verificar se o Echo foi marcado como indisponível
        if (window.EchoUnavailable) {
            // Echo não está configurado (REVERB_APP_KEY não encontrado)
            // Parar tentativas
            return;
        }

        // Aguardar Echo estar disponível
        if (window.Echo) {
            this.echo = window.Echo;
            
            // Verificar se o Echo está conectado
            if (this.echo.connector && this.echo.connector.pusher) {
                const connection = this.echo.connector.pusher.connection;
                
                // Se não estiver conectado, tentar conectar
                if (connection.state === 'disconnected' || connection.state === 'unavailable') {
                    try {
                        connection.connect();
                    } catch (error) {
                        console.error('Erro ao conectar WebSocket:', error);
                    }
                }
            }
            
            this.subscribeToNotifications();
        } else {
            // Tentar novamente após delay (com limite menor)
            if (!this.retryCount) this.retryCount = 0;
            // Reduzir tentativas de 50 para 10 (1 segundo de tentativas)
            if (this.retryCount < 10) {
                this.retryCount++;
                setTimeout(() => this.setupEcho(), 100);
            } else {
                // Se chegou aqui, o Echo não está disponível
                // Verificar se foi marcado como indisponível para não mostrar erro repetido
                if (!window.EchoUnavailable && !window.EchoUnavailableWarningShown) {
                    console.warn('Echo não disponível após 10 tentativas. Verifique se o Reverb está configurado.');
                    window.EchoUnavailableWarningShown = true;
                }
            }
        }
    }

    subscribeToNotifications() {
        if (!this.echo || !window.Laravel?.user) {
            return;
        }

        try {
            const userId = window.Laravel.user.id;
            const channelName = `user.${userId}`;
            const channel = this.echo.private(channelName);
            
            // Inscrever no canal privado do usuário
            channel.listen('.notification.created', (data) => {
                this.handleNewNotification(data);
            });
            
            // Também escutar sem o ponto (caso o formato seja diferente)
            channel.listen('notification.created', (data) => {
                this.handleNewNotification(data);
            });
            
            channel.error((error) => {
                console.error('Erro no canal privado:', error);
            });
            
            // Expor método de teste manual
            window.testNotificationSoundManually = () => {
                this.handleNewNotification({
                    id: 999,
                    type: 'test',
                    title: 'Teste Manual',
                    message: 'Esta é uma notificação de teste',
                    data: {},
                    created_at: new Date().toISOString()
                });
            };
        } catch (error) {
            console.error('Erro ao inscrever em notificações:', error);
        }
    }

    async handleNewNotification(data) {
        // Validar dados
        if (!data || !data.id) {
            console.error('Dados de notificação inválidos:', data);
            return;
        }
        
        // Garantir que o contexto de áudio esteja pronto
        try {
            await this.ensureAudioContextReady();
        } catch (error) {
            console.error('Erro ao preparar contexto de áudio:', error);
        }
        
        // Aguardar um pouco para garantir que o contexto está estável
        await new Promise(resolve => setTimeout(resolve, 50));
        
        // Tocar som de notificação
        if (this.soundEnabled) {
            this.playNotificationSound().catch(error => {
                console.error('Erro ao tocar som de notificação:', error);
            });
        }

        // Atualizar contador
        const newCount = (this.unreadCount || 0) + 1;
        this.updateUnreadCount(newCount);

        // Adicionar notificação à lista
        this.addNotificationToList(data);

        // Atualizar dropdown se estiver aberto
        this.updateDropdownIfOpen(data)

        // Disparar evento customizado para outros componentes (Alpine.js)
        window.dispatchEvent(new CustomEvent('notification-received', { detail: data }));
    }

    updateDropdownIfOpen(notification) {
        // Verificar se há um componente Alpine.js de notificações ativo
        const notificationComponent = document.querySelector('[x-data*="notificationDropdown"]');
        if (notificationComponent && window.Alpine) {
            const alpineData = window.Alpine.$data(notificationComponent);
            if (alpineData && alpineData.open) {
                // Adicionar à lista do dropdown
                if (!alpineData.notifications.find(n => n.id === notification.id)) {
                    alpineData.notifications.unshift({
                        ...notification,
                        time_ago: this.getTimeAgo(notification.created_at),
                    });
                    // Limitar a 10 notificações
                    if (alpineData.notifications.length > 10) {
                        alpineData.notifications = alpineData.notifications.slice(0, 10);
                    }
                    // Atualizar contador
                    alpineData.unreadCount = (alpineData.unreadCount || 0) + 1;
                }
            }
        }
    }

    async loadUnreadCount() {
        if (!window.Laravel?.user) {
            return;
        }

        try {
            const response = await fetch('/api/notifications/unread');
            const data = await response.json();
            this.updateUnreadCount(data.count || 0);
        } catch (error) {
            console.error('Erro ao carregar contador de notificações:', error);
        }
    }

    updateUnreadCount(count) {
        this.unreadCount = count;
        
        // Atualizar badge no DOM (se existir)
        const badge = document.querySelector('[data-notification-badge]');
        if (badge) {
            if (count > 0) {
                badge.textContent = count > 99 ? '99+' : count;
                badge.style.display = 'flex';
            } else {
                badge.style.display = 'none';
            }
        }

        // Atualizar contador no Alpine.js (se estiver usando)
        if (window.Alpine && window.Alpine.store) {
            const store = window.Alpine.store('notifications');
            if (store) {
                store.unreadCount = count;
            }
        }
    }

    addNotificationToList(notification) {
        // Adicionar notificação no início da lista
        this.notifications.unshift({
            ...notification,
            time_ago: this.getTimeAgo(notification.created_at),
        });

        // Limitar a 10 notificações
        if (this.notifications.length > 10) {
            this.notifications = this.notifications.slice(0, 10);
        }
    }

    getTimeAgo(dateString) {
        const date = new Date(dateString);
        const now = new Date();
        const diffInSeconds = Math.floor((now - date) / 1000);

        if (diffInSeconds < 60) {
            return 'Agora';
        } else if (diffInSeconds < 3600) {
            const minutes = Math.floor(diffInSeconds / 60);
            return `${minutes} min atrás`;
        } else if (diffInSeconds < 86400) {
            const hours = Math.floor(diffInSeconds / 3600);
            return `${hours} h atrás`;
        } else {
            const days = Math.floor(diffInSeconds / 86400);
            return `${days} dia${days > 1 ? 's' : ''} atrás`;
        }
    }

    async markAsRead(notificationId) {
        try {
            const response = await fetch(`/notifications/${notificationId}/read`, {
                method: 'POST',
                headers: {
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')?.content,
                    'Content-Type': 'application/json',
                },
            });

            const data = await response.json();
            if (data.success) {
                this.updateUnreadCount(data.unread_count || 0);
            }
        } catch (error) {
            console.error('Erro ao marcar notificação como lida:', error);
        }
    }

    async markAllAsRead() {
        try {
            const response = await fetch('/notifications/read-all', {
                method: 'POST',
                headers: {
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')?.content,
                    'Content-Type': 'application/json',
                },
            });

            const data = await response.json();
            if (data.success) {
                this.updateUnreadCount(0);
            }
        } catch (error) {
            console.error('Erro ao marcar todas como lidas:', error);
        }
    }
}

// Inicializar sistema de notificações
if (!window.notificationSystem) {
    window.notificationSystem = new NotificationSystem();
}

// Expor método de teste globalmente
window.testNotificationSound = function() {
    if (window.notificationSystem) {
        window.notificationSystem.testSound();
    } else {
        console.error('Sistema de notificações não inicializado');
    }
};

// Exportar para uso global
export default NotificationSystem;


