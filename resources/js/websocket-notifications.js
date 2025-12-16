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
        // Aguardar o DOM estar pronto e dar tempo para o echo.js inicializar
        const startSetup = () => {
            // Aguardar um pouco para garantir que o echo.js teve tempo de inicializar
            setTimeout(() => {
                this.setupEcho();
            }, 300);
        };
        
        if (document.readyState === 'loading') {
            document.addEventListener('DOMContentLoaded', startSetup);
        } else {
            startSetup();
        }
    }

    setupEcho() {
        // Não tentar conectar em páginas de autenticação
        const isAuthPage = window.location.pathname.includes('/login') || 
                          window.location.pathname.includes('/register') || 
                          window.location.pathname.includes('/password/reset') ||
                          window.location.pathname.includes('/forgot-password') ||
                          document.querySelector('body[data-auth-page]');
        
        if (isAuthPage) {
            return;
        }
        
        // Verificar se o Echo foi marcado como indisponível
        if (window.EchoUnavailable) {
            // Echo não está disponível (provavelmente falta REVERB_APP_KEY)
            return;
        }
        
        // Aguardar window.Laravel estar disponível (necessário para o echo.js inicializar)
        if (!window.Laravel) {
            // Tentar novamente após um delay
            if (!this.laravelRetryCount) this.laravelRetryCount = 0;
            if (this.laravelRetryCount < 20) {
                this.laravelRetryCount++;
                setTimeout(() => this.setupEcho(), 200);
            }
            return;
        }
        
        // Resetar contador do Laravel quando disponível
        this.laravelRetryCount = 0;
        
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
            // Tentativas silenciosas
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
            connection.bind('error', () => {
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

            // Canal de produtos (criação/atualização em tempo real)
            const productsChannel = this.echo.channel('products');
            
            // Verificar estado da conexão antes de inscrever
            if (this.echo.connector && this.echo.connector.pusher) {
                const connection = this.echo.connector.pusher.connection;
                
                if (connection.state !== 'connected' && connection.state !== 'connecting') {
                    connection.connect();
                }
            }
            
            productsChannel
                .listen('.product.changed', (data) => {
                    this.handleProductChanged(data);
                })
                .error(() => {
                    // Erro silencioso
                });
            
            productsChannel.subscribed(() => {
                // Canal inscrito com sucesso
            });
            
            productsChannel.error(() => {
                // Erro silencioso
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

            // Canal financeiro
            this.echo.channel('financial')
                .listen('.account-payable.changed', (data) => {
                    this.handleAccountPayableChanged(data);
                })
                .listen('.account-receivable.changed', (data) => {
                    this.handleAccountReceivableChanged(data);
                })
                .listen('.invoice.changed', (data) => {
                    this.handleInvoiceChanged(data);
                })
                .listen('.receipt.changed', (data) => {
                    this.handleReceiptChanged(data);
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

    handleProductChanged(data) {
        // Atualizar listas de produtos em tempo real (sem duplicar notificações)
        if (window.Livewire) {
            window.Livewire.dispatch('refresh-products');
        }
    }

    handleAccountPayableChanged(data) {
        const message = `💰 ${data.message}`;
        if (window.showNotification) {
            window.showNotification(message, 'success', 4000);
        }
        
        // Atualizar tabela suavemente
        this.updateAccountPayableTable(data);
        
        // Disparar evento Livewire se necessário
        if (window.Livewire) {
            window.Livewire.dispatch('account-payable-changed', data);
        }
    }

    handleAccountReceivableChanged(data) {
        const message = `💵 ${data.message}`;
        if (window.showNotification) {
            window.showNotification(message, 'success', 4000);
        }
        
        // Atualizar tabela suavemente
        this.updateAccountReceivableTable(data);
        
        // Disparar evento Livewire se necessário
        if (window.Livewire) {
            window.Livewire.dispatch('account-receivable-changed', data);
        }
    }

    handleInvoiceChanged(data) {
        const message = `📄 ${data.message}`;
        if (window.showNotification) {
            window.showNotification(message, 'success', 4000);
        }
        
        // Atualizar tabela suavemente
        this.updateInvoiceTable(data);
        
        // Disparar evento Livewire se necessário
        if (window.Livewire) {
            window.Livewire.dispatch('invoice-changed', data);
        }
    }

    handleReceiptChanged(data) {
        const message = `🧾 ${data.message}`;
        if (window.showNotification) {
            window.showNotification(message, 'success', 4000);
        }
        
        // Atualizar tabela suavemente
        this.updateReceiptTable(data);
        
        // Disparar evento Livewire se necessário
        if (window.Livewire) {
            window.Livewire.dispatch('receipt-changed', data);
        }
    }

    updateAccountPayableTable(data) {
        const table = document.getElementById('account-payables-table');
        if (!table) return;

        const row = table.querySelector(`tr[data-id="${data.accountPayableId}"]`);
        
        if (data.action === 'created') {
            // Recarregar página para mostrar novo item (ou fazer fetch)
            setTimeout(() => {
                window.location.reload();
            }, 500);
        } else if (data.action === 'updated' && row) {
            // Atualizar linha existente com animação
            row.classList.add('bg-indigo-50', 'dark:bg-indigo-900/20');
            setTimeout(() => {
                row.classList.remove('bg-indigo-50', 'dark:bg-indigo-900/20');
                // Recarregar para pegar dados atualizados
                window.location.reload();
            }, 1000);
        }
    }

    updateAccountReceivableTable(data) {
        const table = document.getElementById('account-receivables-table');
        if (!table) return;

        const row = table.querySelector(`tr[data-id="${data.accountReceivableId}"]`);
        
        if (data.action === 'created') {
            setTimeout(() => {
                window.location.reload();
            }, 500);
        } else if (data.action === 'updated' && row) {
            row.classList.add('bg-indigo-50', 'dark:bg-indigo-900/20');
            setTimeout(() => {
                row.classList.remove('bg-indigo-50', 'dark:bg-indigo-900/20');
                window.location.reload();
            }, 1000);
        }
    }

    updateInvoiceTable(data) {
        const table = document.getElementById('invoices-table');
        if (!table) return;

        const row = table.querySelector(`tr[data-id="${data.invoiceId}"]`);
        
        if (data.action === 'created') {
            setTimeout(() => {
                window.location.reload();
            }, 500);
        } else if (data.action === 'updated' && row) {
            row.classList.add('bg-indigo-50', 'dark:bg-indigo-900/20');
            setTimeout(() => {
                row.classList.remove('bg-indigo-50', 'dark:bg-indigo-900/20');
                window.location.reload();
            }, 1000);
        }
    }

    updateReceiptTable(data) {
        const table = document.getElementById('receipts-table');
        if (!table) return;

        const row = table.querySelector(`tr[data-id="${data.receiptId}"]`);
        
        if (data.action === 'created') {
            setTimeout(() => {
                window.location.reload();
            }, 500);
        } else if (data.action === 'updated' && row) {
            row.classList.add('bg-indigo-50', 'dark:bg-indigo-900/20');
            setTimeout(() => {
                row.classList.remove('bg-indigo-50', 'dark:bg-indigo-900/20');
                window.location.reload();
            }, 1000);
        }
    }
}

// Inicializar gerenciador de notificações WebSocket
if (!window.webSocketNotificationManager) {
    window.webSocketNotificationManager = new WebSocketNotificationManager();
}



