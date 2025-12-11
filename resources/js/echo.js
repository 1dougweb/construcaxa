import Echo from 'laravel-echo';
import Pusher from 'pusher-js';

window.Pusher = Pusher;

// Só inicializar o Echo se as variáveis de ambiente estiverem configuradas
// Prioridade: window.Laravel.reverb (do backend) > VITE_REVERB_APP_KEY > REVERB_APP_KEY
const getReverbKey = () => {
    // Primeiro tentar do window.Laravel (sempre disponível em produção)
    if (window.Laravel?.reverb?.key) {
        return window.Laravel.reverb.key;
    }
    // Depois tentar variáveis do Vite (só disponíveis em desenvolvimento ou se compiladas)
    return import.meta.env.VITE_REVERB_APP_KEY || import.meta.env.REVERB_APP_KEY;
};
const reverbKey = getReverbKey();

// Detectar host e porta baseado no APP_URL ou variáveis de ambiente
let reverbHost, reverbPort, reverbScheme;

// Tentar pegar do window.Laravel (definido no layout) ou variáveis de ambiente
const getAppUrl = () => {
    return window.Laravel?.appUrl || 
           window.Laravel?.reverb?.appUrl ||
           import.meta.env.VITE_APP_URL || 
           import.meta.env.APP_URL;
};
const appUrl = getAppUrl();

// Função para obter configurações do Reverb (prioridade: window.Laravel > variáveis de ambiente)
const getReverbConfig = () => {
    // Priorizar configurações do window.Laravel (sempre disponível em produção)
    const config = window.Laravel?.reverb || {};
    return {
        host: config.host || import.meta.env.VITE_REVERB_HOST || import.meta.env.REVERB_HOST || '127.0.0.1',
        port: config.port || import.meta.env.VITE_REVERB_PORT || import.meta.env.REVERB_PORT || 8080,
        scheme: config.scheme || import.meta.env.VITE_REVERB_SCHEME || import.meta.env.REVERB_SCHEME || 'http',
    };
};

const reverbConfig = getReverbConfig();

if (appUrl && !appUrl.includes('127.0.0.1') && !appUrl.includes('localhost')) {
    // Produção: usar APP_URL
    try {
        const url = new URL(appUrl);
        reverbHost = reverbConfig.host || url.hostname;
        reverbScheme = reverbConfig.scheme || url.protocol.replace(':', '');
        // Em produção, o Reverb geralmente roda na mesma porta ou porta específica
        reverbPort = reverbConfig.port ?? (reverbScheme === 'https' ? 443 : 80);
    } catch (e) {
        reverbHost = reverbConfig.host || '127.0.0.1';
        reverbPort = reverbConfig.port || 8080;
        reverbScheme = reverbConfig.scheme || 'http';
    }
} else {
    // Desenvolvimento: usar valores das variáveis de ambiente ou padrões
    reverbHost = reverbConfig.host || '127.0.0.1';
    reverbPort = reverbConfig.port || 8080;
    reverbScheme = reverbConfig.scheme || 'http';
    
    // Sempre usar 127.0.0.1 em vez de localhost para compatibilidade com Windows
    if (reverbHost === 'localhost') {
        reverbHost = '127.0.0.1';
    }
}

// Função para verificar se estamos em uma página de autenticação
const isAuthPage = () => {
    // Verificar pelo atributo data-auth-page no body (mais confiável)
    if (document.body && document.body.hasAttribute('data-auth-page')) {
        return true;
    }
    
    // Verificar pelo pathname como fallback
    const pathname = window.location.pathname;
    const authPaths = ['/login', '/register', '/password/reset', '/forgot-password', '/email/verify'];
    return authPaths.some(path => pathname.includes(path));
};

// Flag para indicar se o Echo foi inicializado ou se está indisponível
window.EchoUnavailable = false;

// Função para inicializar o Echo
const initializeEcho = () => {
    // Não inicializar se não houver reverbKey
    if (!reverbKey) {
        // Definir flag para indicar que o Echo não está disponível
        window.EchoUnavailable = true;
        return;
    }
    
    // Não inicializar em páginas de autenticação
    if (isAuthPage()) {
        return;
    }
    
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
        
        // Monitorar conexão silenciosamente
        setTimeout(() => {
            if (window.Echo && window.Echo.connector && window.Echo.connector.pusher) {
                const connection = window.Echo.connector.pusher.connection;
                if (connection.state !== 'connected') {
                    // Servidor Reverb não está disponível - silencioso
                }
            }
        }, 3000);
    } catch (error) {
        // Erro silencioso
    }
};

// Função para tentar inicializar com retry
const tryInitializeEcho = (retries = 10) => {
    // Verificar se window.Laravel está disponível
    if (!window.Laravel && retries > 0) {
        setTimeout(() => tryInitializeEcho(retries - 1), 200);
        return;
    }
    
    // Inicializar normalmente
    initializeEcho();
};

// Inicializar quando o DOM estiver pronto
if (document.readyState === 'loading') {
    document.addEventListener('DOMContentLoaded', () => {
        // Aguardar um pouco mais para garantir que window.Laravel está definido
        setTimeout(() => tryInitializeEcho(), 100);
    });
} else {
    // DOM já está pronto, mas dar um pequeno delay para garantir que tudo está carregado
    setTimeout(() => tryInitializeEcho(), 200);
}

