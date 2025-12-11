// Suprimir avisos do PWA no console
const originalWarn = console.warn;
console.warn = function(...args) {
  const message = args[0]?.toString() || '';
  // Filtrar avisos relacionados ao beforeinstallprompt
  if (message.includes('Banner not shown') || 
      message.includes('beforeinstallprompt') ||
      message.includes('preventDefault() called')) {
    return; // Não exibir este aviso
  }
  originalWarn.apply(console, args);
};

// Registrar Service Worker
if ('serviceWorker' in navigator) {
  window.addEventListener('load', () => {
    navigator.serviceWorker.register('/sw.js')
      .then((registration) => {
        // Service Worker registrado silenciosamente

        // Verificar atualizações periodicamente
        setInterval(() => {
          registration.update();
        }, 60000); // Verificar a cada minuto

        // Escutar atualizações do service worker
        registration.addEventListener('updatefound', () => {
          const newWorker = registration.installing;
          
          newWorker.addEventListener('statechange', () => {
            if (newWorker.state === 'installed' && navigator.serviceWorker.controller) {
              // Nova versão disponível
              if (confirm('Nova versão disponível! Deseja atualizar?')) {
                newWorker.postMessage({ action: 'skipWaiting' });
                window.location.reload();
              }
            }
          });
        });
      })
      .catch(() => {
        // Erro ao registrar Service Worker (silencioso)
      });

    // Escutar mensagens do service worker
    navigator.serviceWorker.addEventListener('message', (event) => {
      if (event.data && event.data.action === 'skipWaiting') {
        window.location.reload();
      }
    });
  });
}

// Gerenciar prompt de instalação
let deferredPrompt;
const installButton = document.getElementById('install-pwa-button');

window.addEventListener('beforeinstallprompt', (e) => {
  // Prevenir o prompt automático
  e.preventDefault();
  deferredPrompt = e;
  
  // Mostrar botão de instalação se existir
  if (installButton) {
    installButton.style.display = 'block';
    installButton.addEventListener('click', async () => {
      // Mostrar o prompt
      deferredPrompt.prompt();
      
      // Aguardar resposta do usuário
      await deferredPrompt.userChoice;
      
      // Limpar a referência
      deferredPrompt = null;
      
      // Esconder o botão
      if (installButton) {
        installButton.style.display = 'none';
      }
    });
  }
});

// Quando o app for instalado
window.addEventListener('appinstalled', () => {
  deferredPrompt = null;
  
  if (installButton) {
    installButton.style.display = 'none';
  }
});


