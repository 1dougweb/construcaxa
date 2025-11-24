/**
 * Gerenciador de notificações WebSocket em tempo real
 */
class WebSocketNotificationManager {
    constructor() {
        this.echo = null;
        this.connected = false;
        this.init();
    }

    init() {
        // Aguardar o DOM estar pronto e o Echo estar disponível
        if (document.readyState === 'loading') {
            document.addEventListener('DOMContentLoaded', () => {
                this.setupEcho();
            });
        } else {
            this.setupEcho();
        }
    }

    setupEcho() {
        // Aguardar o Echo estar disponível
        if (window.Echo) {
            this.echo = window.Echo;
            
            // Configurar listeners primeiro
            this.setupConnectionListeners();
            
            // Tentar conectar manualmente se necessário
            setTimeout(() => {
                if (this.echo.connector && this.echo.connector.pusher) {
                    const connection = this.echo.connector.pusher.connection;
                    
                    if (connection.state === 'disconnected' || connection.state === 'unavailable') {
                        try {
                            // Forçar reconexão
                            if (connection.state === 'disconnected') {
                                connection.connect();
                            }
                        } catch (error) {
                            // Silenciosamente tenta reconectar
                        }
                    }
                }
            }, 200);
            
            // Aguardar um pouco antes de se inscrever nos canais para garantir que a conexão está pronta
            setTimeout(() => {
                this.subscribeToChannels();
            }, 1000);
        } else {
            // Tentar novamente após um delay (máximo 5 segundos)
            if (!this.retryCount) this.retryCount = 0;
            if (this.retryCount < 50) {
                this.retryCount++;
                setTimeout(() => this.setupEcho(), 100);
            }
        }
    }

    setupConnectionListeners() {
        if (!this.echo || !this.echo.connector || !this.echo.connector.pusher) {
            return;
        }

        try {
            const connection = this.echo.connector.pusher.connection;
            
            // Listener para conexão estabelecida
            connection.bind('connected', () => {
                this.connected = true;
            });

            // Listener para desconexão
            connection.bind('disconnected', () => {
                this.connected = false;
            });

            // Listener para erro de conexão
            connection.bind('error', (error) => {
                this.connected = false;
            });
            
            // Listener para estado da conexão
            connection.bind('state_change', (states) => {
                if (states.current === 'connected') {
                    this.connected = true;
                } else if (states.current === 'disconnected' || states.current === 'failed') {
                    this.connected = false;
                }
            });
            
        } catch (error) {
            // Erro silencioso
        }
    }

    subscribeToChannels() {
        if (!this.echo) {
            return;
        }

        try {
            // Canal de alertas de estoque
            this.echo.channel('stock-alerts')
                .listen('.stock.low', (data) => {
                    this.handleStockLowAlert(data);
                });

            // Canal de requisições de material
            this.echo.channel('material-requests')
                .listen('.material-request.created', (data) => {
                    this.handleNewMaterialRequest(data);
                })
                .listen('.request.status-changed', (data) => {
                    this.handleRequestStatusChanged(data);
                });

            // Canal de requisições de equipamento
            this.echo.channel('equipment-requests')
                .listen('.equipment-request.created', (data) => {
                    this.handleNewEquipmentRequest(data);
                })
                .listen('.request.status-changed', (data) => {
                    this.handleRequestStatusChanged(data);
                });

            // Canal de movimentações de estoque
            this.echo.channel('stock-movements')
                .listen('.stock-movement.created', (data) => {
                    this.handleStockMovement(data);
                });

            // Canal privado para notificações do usuário (se autenticado)
            if (window.Laravel && window.Laravel.user) {
                this.echo.private(`user.${window.Laravel.user.id}`)
                    .listen('.notification', (data) => {
                        this.handleUserNotification(data);
                    });
            }
        } catch (error) {
            // Erro silencioso
        }
    }

    handleStockLowAlert(data) {
        const message = `⚠️ ${data.message}`;
        if (window.showNotification) {
            window.showNotification(message, 'warning', 8000);
        }
        
        // Disparar evento para atualizar dashboard se necessário
        if (window.Livewire) {
            window.Livewire.dispatch('stock-low-alert', data);
        }
    }

    handleNewMaterialRequest(data) {
        const message = `📋 ${data.message}`;
        if (window.showNotification) {
            window.showNotification(message, 'info', 6000);
        }
        
        // Disparar evento para atualizar dashboard
        if (window.Livewire) {
            window.Livewire.dispatch('new-material-request', data);
        }
    }

    handleNewEquipmentRequest(data) {
        const message = `🔧 ${data.message}`;
        if (window.showNotification) {
            window.showNotification(message, 'info', 6000);
        }
        
        // Disparar evento para atualizar dashboard
        if (window.Livewire) {
            window.Livewire.dispatch('new-equipment-request', data);
        }
    }

    handleStockMovement(data) {
        const message = `📦 ${data.message}`;
        if (window.showNotification) {
            window.showNotification(message, 'info', 5000);
        }
        
        // Disparar evento para atualizar dashboard
        if (window.Livewire) {
            window.Livewire.dispatch('stock-movement-created', data);
        }
    }

    handleRequestStatusChanged(data) {
        const message = `🔄 ${data.message}`;
        const type = data.new_status === 'completed' || data.new_status === 'approved' ? 'success' : 'info';
        if (window.showNotification) {
            window.showNotification(message, type, 6000);
        }
        
        // Disparar evento para atualizar dashboard
        if (window.Livewire) {
            window.Livewire.dispatch('request-status-changed', data);
        }
    }

    handleUserNotification(data) {
        if (window.showNotification) {
            window.showNotification(data.message, data.type || 'info', data.duration || 6000);
        }
    }
}

// Inicializar gerenciador de notificações WebSocket
if (!window.webSocketNotificationManager) {
    window.webSocketNotificationManager = new WebSocketNotificationManager();
}



