/**
 * Gerenciador de notificações para exibir mensagens de feedback ao usuário
 */
class NotificationManager {
    constructor() {
        this.container = null;
        this.init();
    }

    init() {
        // Aguardar o DOM estar pronto
        if (document.readyState === 'loading') {
            document.addEventListener('DOMContentLoaded', () => {
                this.container = this.createContainer();
                this.setupLivewireListeners();
                this.checkFlashMessages();
                this.setupThemeObserver();
            });
        } else {
            // DOM já está pronto
            this.container = this.createContainer();
            this.setupLivewireListeners();
            this.checkFlashMessages();
            this.setupThemeObserver();
        }
    }

    createContainer() {
        // Verificar se o container já existe no DOM
        let container = document.getElementById('notifications-container');
        if (!container) {
            container = document.createElement('div');
            container.id = 'notifications-container';
            container.className = 'fixed top-0 right-0 m-6 space-y-3';
            container.style.zIndex = '9999';
            container.style.pointerEvents = 'none';
            document.body.appendChild(container);
        }
        return container;
    }

    checkFlashMessages() {
        // Verificar se há mensagens flash para exibir
        const successEl = document.querySelector('meta[name="notification-success"]');
        const errorEl = document.querySelector('meta[name="notification-error"]');
        const infoEl = document.querySelector('meta[name="notification-info"]');
        
        if (successEl && successEl.content) {
            setTimeout(() => {
                this.show(successEl.content, 'success');
            }, 100);
        }
        
        if (errorEl && errorEl.content) {
            setTimeout(() => {
                this.show(errorEl.content, 'error');
            }, 100);
        }
        
        if (infoEl && infoEl.content) {
            setTimeout(() => {
                this.show(infoEl.content, 'info');
            }, 100);
        }
    }

    setupLivewireListeners() {
        document.addEventListener('DOMContentLoaded', () => {
            if (window.Livewire) {
                // Livewire 3.x events
                document.addEventListener('livewire:initialized', () => {
                    this.listenToLivewireEvents();
                });
            }
        });
    }

    listenToLivewireEvents() {
        if (window.Livewire) {
            Livewire.on('notification', (data) => {
                this.show(data.message, data.type || 'info');
            });
        }
    }

    /**
     * Exibe uma notificação
     * @param {string} message - A mensagem a ser exibida
     * @param {string} type - O tipo de notificação (success, error, warning, info)
     * @param {number} duration - Duração em milissegundos
     */
    show(message, type = 'success', duration = 6000) {
        // Garantir que o container existe
        if (!this.container) {
            this.container = this.createContainer();
        }

        const notification = this.createNotification(message, type);
        this.container.appendChild(notification);

        // Animação de entrada
        setTimeout(() => {
            notification.style.opacity = '1';
            notification.style.transform = 'translateX(0)';
        }, 10);

        // Remover após a duração especificada
        setTimeout(() => {
            notification.style.opacity = '0';
            notification.style.transform = 'translateX(100%)';
            setTimeout(() => {
                if (notification.parentNode === this.container) {
                    this.container.removeChild(notification);
                }
            }, 500);
        }, duration);
    }

    createNotification(message, type) {
        const notification = document.createElement('div');
        const bgColor = this.getBackgroundColor(type);
        const icon = this.getIcon(type);
        
        notification.className = `${bgColor} text-white px-6 py-4 rounded-lg shadow-lg flex items-center min-w-[300px] max-w-md border border-white/20`;
        notification.style.opacity = '0';
        notification.style.transform = 'translateX(100%)';
        notification.style.transition = 'opacity 0.3s ease-in-out, transform 0.3s ease-in-out';
        notification.style.pointerEvents = 'auto';
        notification.style.cursor = 'pointer';
        notification.setAttribute('data-notification', 'true');
        
        notification.innerHTML = `
            <div class="flex-shrink-0 mr-3">
                ${icon}
            </div>
            <div class="flex-1">
                ${message}
            </div>
            <button class="ml-3 text-white/80 hover:text-white transition-colors" aria-label="Fechar">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                </svg>
            </button>
        `;
        
        // Permitir fechar ao clicar no botão ou na notificação
        const closeBtn = notification.querySelector('button');
        const closeNotification = () => {
            notification.style.opacity = '0';
            notification.style.transform = 'translateX(100%)';
            setTimeout(() => {
                if (notification.parentNode === this.container) {
                    this.container.removeChild(notification);
                }
            }, 300);
        };
        
        closeBtn.addEventListener('click', (e) => {
            e.stopPropagation();
            closeNotification();
        });
        
        notification.addEventListener('click', closeNotification);
        
        return notification;
    }

    getIcon(type) {
        const icons = {
            success: `<svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
            </svg>`,
            error: `<svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
            </svg>`,
            warning: `<svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"></path>
            </svg>`,
            info: `<svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
            </svg>`
        };
        return icons[type] || icons.success;
    }

    getBackgroundColor(type) {
        // Cores que funcionam bem em ambos os temas com dark mode
        switch (type) {
            case 'success': return 'bg-green-500 dark:bg-green-600';
            case 'error': return 'bg-red-500 dark:bg-red-600';
            case 'warning': return 'bg-yellow-500 dark:bg-yellow-600';
            case 'info': return 'bg-blue-500 dark:bg-blue-600';
            default: return 'bg-green-500 dark:bg-green-600';
        }
    }

    // Detectar mudanças no tema (para futuras melhorias)
    setupThemeObserver() {
        // Observer pode ser usado para atualizar notificações existentes quando o tema mudar
        // Por enquanto, apenas as novas notificações usarão o tema correto
    }
}

// Inicializar gerenciador de notificações apenas se ainda não foi inicializado
if (!window.notificationManager) {
    window.notificationManager = new NotificationManager();
}

// Função global para exibir notificações de qualquer lugar
window.showNotification = function(message, type = 'success', duration = 6000) {
    if (window.notificationManager) {
        window.notificationManager.show(message, type, duration);
    } else {
        console.error('NotificationManager não foi inicializado');
    }
};

// Função de teste para verificar se as notificações estão funcionando
window.testNotification = function(type = 'success') {
    if (window.notificationManager) {
        window.notificationManager.show('Notificação de teste!', type);
    } else {
        console.error('NotificationManager não foi inicializado');
    }
};
