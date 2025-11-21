// Registrar Service Worker
if ('serviceWorker' in navigator) {
  window.addEventListener('load', () => {
    navigator.serviceWorker.register('/sw.js')
      .then((registration) => {
        console.log('Service Worker registrado com sucesso:', registration.scope);

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
      .catch((error) => {
        console.log('Erro ao registrar Service Worker:', error);
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
      const { outcome } = await deferredPrompt.userChoice;
      console.log(`Resultado do prompt: ${outcome}`);
      
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
  console.log('PWA instalado com sucesso');
  deferredPrompt = null;
  
  if (installButton) {
    installButton.style.display = 'none';
  }
});


