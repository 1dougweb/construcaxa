const CACHE_NAME = 'stock-master-v3';
const RUNTIME_CACHE = 'stock-master-runtime-v3';

// Assets estáticos para cachear na instalação
const STATIC_ASSETS = [
  '/',
  '/assets/images/logo.svg',
  '/manifest.json'
];

// Instalação do Service Worker
self.addEventListener('install', (event) => {
  event.waitUntil(
    caches.open(CACHE_NAME)
      .then((cache) => {
        // Adicionar assets um por um para evitar falha se algum não existir
        return Promise.allSettled(
          STATIC_ASSETS.map((url) => {
            return fetch(url)
              .then((response) => {
                if (response.ok) {
                  return cache.put(url, response).catch(() => {
                    // Ignorar erros de cache silenciosamente
                  });
                }
              })
              .catch(() => {
                // Ignorar erros silenciosamente
              });
          })
        );
      })
      .then(() => self.skipWaiting())
  );
});

// Ativação do Service Worker
self.addEventListener('activate', (event) => {
  event.waitUntil(
    caches.keys().then((cacheNames) => {
      return Promise.all(
        cacheNames
          .filter((cacheName) => {
            return cacheName !== CACHE_NAME && cacheName !== RUNTIME_CACHE;
          })
          .map((cacheName) => {
            return caches.delete(cacheName);
          })
      );
    })
    .then(() => self.clients.claim())
  );
});

// Função auxiliar para verificar se a URL pode ser cacheada
function canCacheRequest(request) {
  const url = new URL(request.url);
  
  // Ignorar requisições que não sejam HTTP/HTTPS
  if (url.protocol !== 'http:' && url.protocol !== 'https:') {
    return false;
  }
  
  // Ignorar requisições de extensões do navegador
  if (url.protocol === 'chrome-extension:' || 
      url.protocol === 'moz-extension:' ||
      url.protocol === 'safari-extension:') {
    return false;
  }
  
  // Ignorar requisições de blob/data
  if (url.protocol === 'blob:' || url.protocol === 'data:') {
    return false;
  }
  
  return true;
}

// Estratégia de cache: Network First com fallback para cache
self.addEventListener('fetch', (event) => {
  // Ignorar requisições não-GET
  if (event.request.method !== 'GET') {
    return;
  }

  // Verificar se a requisição pode ser cacheada
  if (!canCacheRequest(event.request)) {
    return;
  }

  // Ignorar requisições de API, Livewire, WebSocket e Broadcasting
  if (event.request.url.includes('/livewire/') || 
      event.request.url.includes('/api/') ||
      event.request.url.includes('/_dusk/') ||
      event.request.url.includes('/broadcasting/') ||
      event.request.url.includes('ws://') ||
      event.request.url.includes('wss://') ||
      event.request.url.includes(':8080')) {
    return;
  }

  event.respondWith(
    fetch(event.request)
      .then((response) => {
        // Clonar a resposta antes de cachear
        const responseToCache = response.clone();

        // Cachear apenas respostas válidas e se a requisição pode ser cacheada
        if (response.status === 200 && canCacheRequest(event.request)) {
          caches.open(RUNTIME_CACHE).then((cache) => {
            cache.put(event.request, responseToCache).catch(() => {
              // Ignorar erros de cache silenciosamente
            });
          });
        }

        return response;
      })
      .catch(() => {
        // Fallback para cache se a rede falhar
        return caches.match(event.request).then((cachedResponse) => {
          if (cachedResponse) {
            return cachedResponse;
          }

          // Se for uma navegação e não houver cache, retornar página inicial
          if (event.request.mode === 'navigate') {
            return caches.match('/');
          }

          // Retornar resposta vazia para outros recursos
          return new Response('Offline', {
            status: 503,
            statusText: 'Service Unavailable',
            headers: new Headers({
              'Content-Type': 'text/plain'
            })
          });
        });
      })
  );
});

