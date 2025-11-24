import Echo from 'laravel-echo';
import Pusher from 'pusher-js';

window.Pusher = Pusher;

// Só inicializar o Echo se as variáveis de ambiente estiverem configuradas
const reverbKey = import.meta.env.VITE_REVERB_APP_KEY || import.meta.env.REVERB_APP_KEY;

// Detectar host e porta baseado no APP_URL ou variáveis de ambiente
let reverbHost, reverbPort, reverbScheme;

// Tentar pegar do window.Laravel.appUrl (definido no layout) ou variáveis de ambiente
const appUrl = window.Laravel?.appUrl || import.meta.env.VITE_APP_URL || import.meta.env.APP_URL;

if (appUrl && !appUrl.includes('127.0.0.1') && !appUrl.includes('localhost')) {
    // Produção: usar APP_URL
    try {
        const url = new URL(appUrl);
        reverbHost = url.hostname;
        reverbScheme = url.protocol.replace(':', '');
        // Em produção, o Reverb geralmente roda na mesma porta ou porta específica
        reverbPort = import.meta.env.VITE_REVERB_PORT ?? import.meta.env.REVERB_PORT ?? (reverbScheme === 'https' ? 443 : 80);
    } catch (e) {
        reverbHost = import.meta.env.VITE_REVERB_HOST || import.meta.env.REVERB_HOST || '127.0.0.1';
        reverbPort = import.meta.env.VITE_REVERB_PORT ?? import.meta.env.REVERB_PORT ?? 8080;
        reverbScheme = import.meta.env.VITE_REVERB_SCHEME ?? import.meta.env.REVERB_SCHEME ?? 'http';
    }
} else {
    // Desenvolvimento: usar valores das variáveis de ambiente ou padrões
    reverbHost = import.meta.env.VITE_REVERB_HOST || import.meta.env.REVERB_HOST || '127.0.0.1';
    reverbPort = import.meta.env.VITE_REVERB_PORT ?? import.meta.env.REVERB_PORT ?? 8080;
    reverbScheme = import.meta.env.VITE_REVERB_SCHEME ?? import.meta.env.REVERB_SCHEME ?? 'http';
    
    // Sempre usar 127.0.0.1 em vez de localhost para compatibilidade com Windows
    if (reverbHost === 'localhost') {
        reverbHost = '127.0.0.1';
    }
}

if (reverbKey) {
    try {
        // Configurar Echo com Reverb
        // Laravel Echo 1.19+ suporta 'reverb' como broadcaster diretamente
        window.Echo = new Echo({
            broadcaster: 'reverb',
            key: reverbKey,
            wsHost: reverbHost,
            wsPort: reverbPort,
            wssPort: reverbPort,
            forceTLS: reverbScheme === 'https',
            enabledTransports: ['ws', 'wss'],
            disableStats: true,
            authEndpoint: '/broadcasting/auth',
            auth: {
                headers: {
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')?.getAttribute('content'),
                },
            },
        });
        
        // Desabilitar logs do pusher-js
        if (window.Pusher) {
            if (window.Pusher.log) {
                window.Pusher.log = function() {};
            }
            if (window.Pusher.logToConsole !== undefined) {
                window.Pusher.logToConsole = false;
            }
        }
        
        // Suprimir apenas erros de conexão WebSocket do pusher-js
        const originalError = console.error;
        const originalWarn = console.warn;
        
        console.error = function(...args) {
            const message = args[0]?.toString() || '';
            // Filtrar apenas erros específicos de WebSocket do pusher-js
            if (message.includes('WebSocket connection to') && 
                message.includes('failed') && 
                (message.includes('/app/') || message.includes('pusher-js'))) {
                return; // Não exibir este erro
            }
            originalError.apply(console, args);
        };
        
        console.warn = function(...args) {
            const message = args[0]?.toString() || '';
            // Filtrar apenas warnings específicos de WebSocket do pusher-js
            if (message.includes('WebSocket') && message.includes('pusher-js')) {
                return; // Não exibir este warning
            }
            originalWarn.apply(console, args);
        };
        
        // Aguardar um pouco e verificar se conectou automaticamente
        setTimeout(() => {
            if (window.Echo && window.Echo.connector && window.Echo.connector.pusher) {
                const connection = window.Echo.connector.pusher.connection;
                
                if (connection.state === 'disconnected' || connection.state === 'unavailable') {
                    try {
                        connection.connect();
                    } catch (error) {
                        // Silenciosamente tenta reconectar
                    }
                }
            }
        }, 100);
    } catch (error) {
        // Erro silencioso
    }
}

