// Importar Flaticon UIcons - todos os estilos (fi-rr, fi-rs, fi-br, fi-bs, fi-sr, fi-ss, etc.)
import '@flaticon/flaticon-uicons/css/all/all.css';

import './bootstrap';
import './notifications';
import './theme';
import './echo';
import './websocket-notifications';
import './notification-system';
import './masks';

// Inicializar Alpine.js se ainda não estiver inicializado (para páginas sem Livewire)
// O Livewire 3 já inicializa o Alpine.js automaticamente, então só precisamos inicializar manualmente
// em páginas que não usam Livewire (como páginas de autenticação)

import Alpine from 'alpinejs';
import persist from '@alpinejs/persist';

// Aguardar um pouco para verificar se o Livewire já carregou o Alpine
setTimeout(() => {
    // Verificar se o Alpine já está disponível (carregado pelo Livewire)
    if (typeof window.Alpine === 'undefined' || !window.Alpine) {
        // Se não estiver disponível, inicializar manualmente
        Alpine.plugin(persist);
        window.Alpine = Alpine;
        Alpine.start();
    }
}, 50); 