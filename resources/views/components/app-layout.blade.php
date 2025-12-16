<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">

        <title>{{ config('app.name', 'Laravel') }}</title>

        <!-- Notificações de sessão flash -->
        @if(session('success'))
            <meta name="notification-success" content="{{ session('success') }}">
        @endif
        @if(session('error'))
            <meta name="notification-error" content="{{ session('error') }}">
        @endif
        @if(session('info'))
            <meta name="notification-info" content="{{ session('info') }}">
        @endif
        @if(session('test_success'))
            <meta name="notification-info" content="{{ session('test_success') }}">
        @endif
        @if(session('test_error'))
            <meta name="notification-error" content="{{ session('test_error') }}">
        @endif

        <!-- PWA Meta Tags -->
        <meta name="theme-color" content="#1E2780">
        <meta name="mobile-web-app-capable" content="yes">
        <meta name="apple-mobile-web-app-capable" content="yes">
        <meta name="apple-mobile-web-app-status-bar-style" content="black-translucent">
        <meta name="apple-mobile-web-app-title" content="Stock Master">
        <meta name="description" content="Sistema de gestão de estoque e projetos">
        
        <!-- PWA Manifest -->
        <link rel="manifest" href="{{ url('manifest.json') }}">
        
        <!-- Apple Touch Icons (opcional - só carrega se existir) -->
        @if(file_exists(public_path('icons/icon-192x192.png')))
        <link rel="apple-touch-icon" href="{{ asset('icons/icon-192x192.png') }}">
        <link rel="apple-touch-icon" sizes="152x152" href="{{ asset('icons/icon-152x152.png') }}">
        <link rel="apple-touch-icon" sizes="180x180" href="{{ asset('icons/icon-192x192.png') }}">
        @endif
        
        <!-- Favicon (opcional - só carrega se existir) -->
        @if(file_exists(public_path('icons/icon-192x192.png')))
        <link rel="icon" type="image/png" sizes="192x192" href="{{ asset('icons/icon-192x192.png') }}">
        <link rel="icon" type="image/png" sizes="512x512" href="{{ asset('icons/icon-512x512.png') }}">
        @endif

        <!-- Fonts -->
        <link rel="preconnect" href="https://fonts.bunny.net">
        <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />

        <!-- WebSocket Configuration (antes dos scripts para garantir disponibilidade) -->
        <script>
            window.Laravel = {
                @auth
                user: @json(auth()->user()),
                @endauth
                csrfToken: '{{ csrf_token() }}',
                appUrl: '{{ config('app.url') }}',
                reverb: {
                    @if(config('reverb.apps.apps.main.key'))
                    key: '{{ config('reverb.apps.apps.main.key') }}',
                    @endif
                    @if(config('reverb.servers.reverb.host'))
                    host: '{{ config('reverb.servers.reverb.host') }}',
                    @endif
                    @if(config('reverb.servers.reverb.port'))
                    port: {{ config('reverb.servers.reverb.port') }},
                    @endif
                    @if(config('reverb.servers.reverb.options.scheme'))
                    scheme: '{{ config('reverb.servers.reverb.options.scheme') }}',
                    @endif
                    appUrl: '{{ config('app.url') }}'
                }
            };
        </script>

        <!-- Scripts -->
        @vite(['resources/css/app.css', 'resources/js/app.js', 'resources/js/notifications.js'])

        <!-- Flaticon UIcons será carregado via Vite -->

        <!-- Styles -->
        @livewireStyles
        <style>
            /* Scrollbar customizada para o sidebar - invisível por padrão */
            .sidebar-scroll {
                /* Padding fixo - sempre 1rem em todos os lados, não muda quando scrollbar aparece */
                padding: 1rem !important;
                box-sizing: border-box;
                /* Overlay scroll - não afeta o tamanho do elemento */
                overflow-y: auto;
                scrollbar-width: thin;
                scrollbar-color: transparent transparent;
                /* Não reservar espaço para scrollbar - sempre overlay */
                scrollbar-gutter: auto;
            }
            
            /* Webkit (Chrome, Safari, Edge) - scrollbar invisível por padrão */
            .sidebar-scroll::-webkit-scrollbar {
                width: 8px;
                background: transparent;
                /* Scrollbar overlay - não afeta o layout */
            }
            
            .sidebar-scroll::-webkit-scrollbar-track {
                background: transparent;
            }
            
            .sidebar-scroll::-webkit-scrollbar-thumb {
                background: transparent;
                border-radius: 4px;
                transition: background 0.3s cubic-bezier(0.4, 0, 0.2, 1), opacity 0.3s cubic-bezier(0.4, 0, 0.2, 1);
            }
            
            /* Mostrar scrollbar quando a classe sidebar-scroll-visible estiver presente */
            .sidebar-scroll-visible::-webkit-scrollbar-thumb {
                background: rgba(203, 213, 225, 0.6);
            }
            
            .sidebar-scroll-visible::-webkit-scrollbar-thumb:hover {
                background: rgba(148, 163, 184, 0.9);
            }
            
            /* Para Firefox - mostrar quando visível */
            .sidebar-scroll-visible {
                scrollbar-color: rgba(203, 213, 225, 0.6) transparent;
            }
            
            /* Dark mode adjustments */
            .dark .sidebar-scroll-visible::-webkit-scrollbar-thumb {
                background: rgba(75, 85, 99, 0.6);
            }
            
            .dark .sidebar-scroll-visible::-webkit-scrollbar-thumb:hover {
                background: rgba(107, 114, 128, 0.9);
            }
            
            .dark .sidebar-scroll-visible {
                scrollbar-color: rgba(75, 85, 99, 0.6) transparent;
            }
            
            /* Garantir que o scrollbar seja sempre overlay (não afeta o layout) */
            /* Para navegadores que suportam overlay (Chrome, Edge) */
            @supports (overflow: overlay) {
                .sidebar-scroll {
                    overflow-y: overlay;
                }
            }
            
            /* Para navegadores que não suportam overlay */
            @supports not (overflow: overlay) {
                .sidebar-scroll {
                    /* Manter padding fixo mesmo quando scrollbar aparece */
                    padding: 1rem !important;
                    padding-right: 1rem !important;
                    /* Usar margin negativo para compensar scrollbar sem afetar padding */
                    margin-right: 0;
                }
            }
            
            /* Garantir que o padding seja sempre fixo, independente do scrollbar estar visível */
            .sidebar-scroll,
            .sidebar-scroll-visible {
                padding: 1rem !important;
                padding-right: 1rem !important;
                box-sizing: border-box;
            }
            
            /* Forçar padding fixo mesmo quando hover (scrollbar visível) */
            .sidebar-container:hover .sidebar-scroll,
            .sidebar-container:hover .sidebar-scroll-visible {
                padding: 1rem !important;
                padding-right: 1rem !important;
            }
            
            /* Scrollbar customizada para o layout principal (body/html) - igual ao sidebar */
            html,
            body {
                scrollbar-width: thin;
                scrollbar-color: transparent transparent;
                scrollbar-gutter: auto;
            }
            
            /* Webkit (Chrome, Safari, Edge) - scrollbar invisível por padrão */
            html::-webkit-scrollbar,
            body::-webkit-scrollbar {
                width: 8px;
                height: 8px;
                background: transparent;
            }
            
            html::-webkit-scrollbar-track,
            body::-webkit-scrollbar-track {
                background: transparent;
            }
            
            html::-webkit-scrollbar-thumb,
            body::-webkit-scrollbar-thumb {
                background: transparent;
                border-radius: 4px;
                transition: background 0.3s cubic-bezier(0.4, 0, 0.2, 1), opacity 0.3s cubic-bezier(0.4, 0, 0.2, 1);
            }
            
            /* Mostrar scrollbar quando a classe layout-scroll-visible estiver presente */
            html.layout-scroll-visible::-webkit-scrollbar-thumb,
            body.layout-scroll-visible::-webkit-scrollbar-thumb {
                background: rgba(203, 213, 225, 0.6);
            }
            
            html.layout-scroll-visible::-webkit-scrollbar-thumb:hover,
            body.layout-scroll-visible::-webkit-scrollbar-thumb:hover {
                background: rgba(148, 163, 184, 0.9);
            }
            
            /* Para Firefox - mostrar quando visível */
            html.layout-scroll-visible,
            body.layout-scroll-visible {
                scrollbar-color: rgba(203, 213, 225, 0.6) transparent;
            }
            
            /* Dark mode adjustments para scrollbar do layout */
            .dark html.layout-scroll-visible::-webkit-scrollbar-thumb,
            .dark body.layout-scroll-visible::-webkit-scrollbar-thumb {
                background: rgba(75, 85, 99, 0.6);
            }
            
            .dark html.layout-scroll-visible::-webkit-scrollbar-thumb:hover,
            .dark body.layout-scroll-visible::-webkit-scrollbar-thumb:hover {
                background: rgba(107, 114, 128, 0.9);
            }
            
            .dark html.layout-scroll-visible,
            .dark body.layout-scroll-visible {
                scrollbar-color: rgba(75, 85, 99, 0.6) transparent;
            }
            
            /* Garantir que o scrollbar seja overlay (não afeta o layout) */
            @supports (overflow: overlay) {
                html,
                body {
                    overflow: overlay;
                }
            }
            
            /* x-cloak para Alpine.js */
            [x-cloak] {
                display: none !important;
            }
            
            /* Transições suaves para dropdowns */
            .dropdown-chevron {
                transition: transform 0.3s cubic-bezier(0.4, 0, 0.2, 1);
            }
            
            /* Melhorias nas setas dos dropdowns do sidebar */
            .sidebar-dropdown-arrow {
                transition: transform 0.25s cubic-bezier(0.4, 0, 0.2, 1);
                opacity: 0.7;
                font-size: 0.75rem;
            }
            
            .sidebar-dropdown-arrow:hover {
                opacity: 1;
            }
            
            .sidebar-dropdown-button:hover .sidebar-dropdown-arrow {
                opacity: 1;
                transform: scale(1.1);
            }
            
            .sidebar-dropdown-button[aria-expanded="true"] .sidebar-dropdown-arrow {
                transform: rotate(180deg) scale(1.1);
                opacity: 1;
            }
            
            /* Melhor alinhamento vertical */
            .sidebar-dropdown-button {
                align-items: center;
            }
            
            .sidebar-dropdown-arrow {
                display: flex;
                align-items: center;
                justify-content: center;
                width: 1rem;
                height: 1rem;
                transition: transform 0.2s ease-in-out;
            }
            
            /* Prevenir flash branco - aplicar background escuro imediatamente */
            html.dark body {
                background-color: #111827;
            }
        </style>
        
        <!-- Script inline para prevenir flash branco no dark mode e sidebar -->
        <script>
            (function() {
                // Aplicar tema ANTES de qualquer renderização
                const stored = localStorage.getItem('darkMode');
                const prefersDark = window.matchMedia('(prefers-color-scheme: dark)').matches;
                const isDark = stored === 'true' || (!stored && prefersDark);
                
                if (isDark) {
                    document.documentElement.classList.add('dark');
                } else {
                    document.documentElement.classList.remove('dark');
                }
                
                // Aplicar estado do sidebar ANTES de qualquer renderização
                const sidebarCollapsed = localStorage.getItem('sidebarCollapsed') === 'true';
                if (sidebarCollapsed) {
                    // Adicionar estilos inline diretamente no head para aplicar antes do render
                    const style = document.createElement('style');
                    style.id = 'sidebar-collapsed-styles';
                    style.textContent = `
                        @media (min-width: 1024px) {
                            .sidebar-container {
                                transform: translateX(-100%) !important;
                            }
                            .topbar-container {
                                left: 0 !important;
                            }
                            .content-container {
                                margin-left: 0 !important;
                            }
                        }
                    `;
                    document.head.appendChild(style);
                }
            })();
        </script>
    </head>
    <body class="font-sans antialiased">
        <!-- Notification container for dynamic notifications -->
        <div id="notifications-container" class="fixed top-0 right-0 m-6 space-y-3" style="z-index: 9999; pointer-events: none;"></div>

        <div class="min-h-screen bg-gray-100 dark:bg-gray-900 transition-colors duration-200" 
             x-data="{ 
                sidebarOpen: false,
                sidebarCollapsed: (() => {
                    const stored = localStorage.getItem('sidebarCollapsed');
                    return stored === 'true';
                })(),
                userMenuOpen: false,
                openSearchModal: false,
                searchQuery: '',
                searchResults: [],
                searchLoading: false,
                searchHistory: [],
                selectedIndex: -1,
                darkMode: (() => {
                    const stored = localStorage.getItem('darkMode');
                    const prefersDark = window.matchMedia('(prefers-color-scheme: dark)').matches;
                    const isDark = stored === 'true' || (!stored && prefersDark);
                    if (isDark) {
                        document.documentElement.classList.add('dark');
                    } else {
                        document.documentElement.classList.remove('dark');
                    }
                    return isDark;
                })(),
                toggleDarkMode() {
                    this.darkMode = !this.darkMode;
                    this.updateDarkMode();
                },
                updateDarkMode() {
                    localStorage.setItem('darkMode', this.darkMode);
                    document.documentElement.classList.toggle('dark', this.darkMode);
                },
                toggleSidebar() {
                    this.sidebarCollapsed = !this.sidebarCollapsed;
                    localStorage.setItem('sidebarCollapsed', this.sidebarCollapsed);
                    
                    // Atualizar estilos dinamicamente
                    let styleEl = document.getElementById('sidebar-collapsed-styles');
                    if (this.sidebarCollapsed) {
                        document.body.setAttribute('data-sidebar-collapsed', 'true');
                        if (!styleEl) {
                            styleEl = document.createElement('style');
                            styleEl.id = 'sidebar-collapsed-styles';
                            document.head.appendChild(styleEl);
                        }
                        styleEl.textContent = `
                            @media (min-width: 1024px) {
                                .sidebar-container {
                                    transform: translateX(-100%) !important;
                                }
                                .topbar-container {
                                    left: 0 !important;
                                }
                                .content-container {
                                    margin-left: 0 !important;
                                }
                            }
                        `;
                    } else {
                        document.body.removeAttribute('data-sidebar-collapsed');
                        if (styleEl) {
                            styleEl.remove();
                        }
                    }
                },
                init() {
                    // Sincronizar estado inicial - não precisa fazer nada pois os estilos já foram aplicados
                },
                loadSearchHistory() {
                    try {
                        const history = localStorage.getItem('searchHistory');
                        this.searchHistory = history ? JSON.parse(history) : [];
                    } catch (e) {
                        this.searchHistory = [];
                    }
                },
                saveToHistory(query) {
                    if (!query || query.trim().length < 2) return;
                    try {
                        let history = JSON.parse(localStorage.getItem('searchHistory') || '[]');
                        history = history.filter(h => h.toLowerCase() !== query.toLowerCase());
                        history.unshift(query);
                        history = history.slice(0, 10); // Manter apenas 10 itens
                        localStorage.setItem('searchHistory', JSON.stringify(history));
                        this.searchHistory = history;
                    } catch (e) {
                        console.error('Erro ao salvar histórico:', e);
                    }
                },
                async performSearch(query) {
                    if (!query || query.trim().length < 2) {
                        this.searchResults = [];
                        return;
                    }
                    
                    this.searchLoading = true;
                    try {
                        const response = await fetch(`{{ route('search') }}?q=${encodeURIComponent(query)}`, {
                            headers: {
                                'X-Requested-With': 'XMLHttpRequest',
                                'Accept': 'application/json'
                            }
                        });
                        const data = await response.json();
                        this.searchResults = data.results || [];
                        this.saveToHistory(query);
                    } catch (error) {
                        console.error('Erro na pesquisa:', error);
                        this.searchResults = [];
                    } finally {
                        this.searchLoading = false;
                    }
                }
             }"
             x-init="
                (function() {
                    // Listener global para atalho de teclado CTRL+K / CMD+K
                    var handleSearchShortcut = function(e) {
                        if ((e.ctrlKey || e.metaKey) && e.key === 'k' && !e.shiftKey && !e.altKey) {
                            e.preventDefault();
                            e.stopPropagation();
                            openSearchModal = true;
                            $nextTick(function() {
                                var input = document.getElementById('search-input');
                                if (input) {
                                    input.focus();
                                }
                            });
                        }
                    };
                    
                    document.addEventListener('keydown', handleSearchShortcut);
                    
                    // Cleanup quando o componente for destruído
                    $el.addEventListener('alpine:destroyed', function() {
                        document.removeEventListener('keydown', handleSearchShortcut);
                    });
                    
                    // Watch para abrir/fechar modal de pesquisa
                    $watch('openSearchModal', function(value) {
                        if (value) {
                            document.body.style.overflow = 'hidden';
                            $nextTick(function() {
                                var input = document.getElementById('search-input');
                                if (input) {
                                    input.focus();
                                }
                                loadSearchHistory();
                            });
                        } else {
                            document.body.style.overflow = '';
                            searchQuery = '';
                            searchResults = [];
                            selectedIndex = -1;
                        }
                    });
                    
                    // Watch para pesquisa com debounce
                    var searchDebounceTimer;
                    $watch('searchQuery', function(value) {
                        clearTimeout(searchDebounceTimer);
                        searchDebounceTimer = setTimeout(function() {
                            performSearch(value);
                        }, 300);
                    });
                })();
             ">
            <!-- Mobile top bar -->
            <div class="fixed top-0 left-0 right-0 h-16 bg-white dark:bg-gray-800 border-b border-gray-200 dark:border-gray-700 px-4 flex items-center justify-between z-50 lg:hidden">
                <!-- Botão hamburger/X -->
                <button @click="sidebarOpen = !sidebarOpen" class="p-2 rounded-md text-gray-600 dark:text-gray-300 hover:bg-gray-100 dark:hover:bg-gray-700 focus:outline-none focus:ring-2 focus:ring-indigo-500 relative w-10 h-10 flex items-center justify-center" aria-label="Toggle menu" type="button">
                    <!-- Ícone Hamburger -->
                    <svg x-show="!sidebarOpen" 
                         x-transition:enter="transition ease-out duration-200"
                         x-transition:enter-start="opacity-0 rotate-90"
                         x-transition:enter-end="opacity-100 rotate-0"
                         x-transition:leave="transition ease-in duration-150"
                         x-transition:leave-start="opacity-100 rotate-0"
                         x-transition:leave-end="opacity-0 -rotate-90"
                         class="h-6 w-6 absolute" 
                         fill="none" 
                         viewBox="0 0 24 24" 
                         stroke="currentColor" 
                         stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M4 6h16M4 12h16M4 18h16"/>
                    </svg>
                    <!-- Ícone X -->
                    <svg x-show="sidebarOpen"
                         x-transition:enter="transition ease-out duration-200"
                         x-transition:enter-start="opacity-0 rotate-90"
                         x-transition:enter-end="opacity-100 rotate-0"
                         x-transition:leave="transition ease-in duration-150"
                         x-transition:leave-start="opacity-100 rotate-0"
                         x-transition:leave-end="opacity-0 -rotate-90"
                         class="h-6 w-6 absolute" 
                         fill="none" 
                         viewBox="0 0 24 24" 
                         stroke="currentColor" 
                         stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12"/>
                    </svg>
                </button>
                <!-- Logo -->
                <div class="flex items-center gap-2 flex-1 justify-center" x-cloak>
                    <!-- Logo modo claro -->
                    <div x-show="!darkMode">
                        <a href="{{ route('dashboard') }}" class="flex items-center">
                            <img src="{{ asset('assets/images/logo.svg') }}" alt="Logo" class="h-8 w-auto" />
                        </a>
                    </div>
                    <!-- Logo modo escuro -->
                    <div x-show="darkMode">
                        <a href="{{ route('dashboard') }}" class="flex items-center">
                            <img src="{{ asset('assets/images/logo-light.svg') }}" alt="Logo" class="h-8 w-auto" />
                        </a>
                    </div>
                </div>
                
                <!-- Search Button (Mobile) -->
                <button 
                    @click="openSearchModal = true"
                    class="p-2 rounded-md text-gray-600 dark:text-gray-300 hover:bg-gray-100 dark:hover:bg-gray-700 transition-colors"
                    aria-label="Pesquisar">
                    <i class="fi fi-rr-search text-lg"></i>
                </button>
                
                <!-- Notificações e Perfil -->
                <div class="flex items-center gap-2">
                    <!-- Notifications Dropdown -->
                    <div class="relative" 
                         x-data="notificationDropdown()"
                         @click.away="open = false">
                        <button 
                            @click="open = !open; if (open && notifications.length === 0) loadNotifications()"
                            class="relative p-2 rounded-md text-gray-600 dark:text-gray-300 hover:bg-gray-100 dark:hover:bg-gray-700 transition-colors"
                            aria-label="Notificações">
                            <i class="fi fi-rr-bell text-lg mt-2"></i>
                            <span x-show="unreadCount > 0" 
                                  x-text="unreadCount > 99 ? '99+' : unreadCount"
                                  class="absolute -top-1 -right-1 h-5 w-5 bg-red-500 text-white text-xs font-bold rounded-full flex items-center justify-center"
                                  style="min-width: 1.25rem;"></span>
                        </button>
                        
                        <!-- Dropdown de Notificações -->
                        <div 
                            x-show="open"
                            x-transition:enter="transition ease-out duration-100"
                            x-transition:enter-start="transform opacity-0 scale-95"
                            x-transition:enter-end="transform opacity-100 scale-100"
                            x-transition:leave="transition ease-in duration-75"
                            x-transition:leave-start="transform opacity-100 scale-100"
                            x-transition:leave-end="transform opacity-0 scale-95"
                            x-cloak
                            class="fixed right-4 top-16 w-[calc(100vw-2rem)] max-w-sm bg-white dark:bg-gray-800 rounded-md shadow-lg border border-gray-200 dark:border-gray-700 z-50 max-h-96 overflow-hidden flex flex-col">
                            <!-- Header -->
                            <div class="px-4 py-3 border-b border-gray-200 dark:border-gray-700 flex items-center justify-between">
                                <h3 class="text-sm font-semibold text-gray-900 dark:text-gray-100">Notificações</h3>
                                <a href="{{ route('notifications.index') }}" 
                                   class="text-xs text-indigo-600 dark:text-indigo-400 hover:text-indigo-800 dark:hover:text-indigo-300">
                                    Ver todas
                                </a>
                            </div>
                            
                            <!-- Lista de Notificações -->
                            <div class="overflow-y-auto flex-1" style="max-height: 20rem;">
                                <div x-show="loading" x-cloak class="px-4 py-8 text-center text-gray-500 dark:text-gray-400">
                                    <i class="fi fi-rr-spinner animate-spin text-2xl mb-2"></i>
                                    <p class="text-sm">Carregando...</p>
                                </div>
                                <div x-show="!loading && notifications.length === 0" x-cloak class="px-4 py-8 text-center text-gray-500 dark:text-gray-400">
                                    <i class="fi fi-rr-bell-slash text-3xl mb-2"></i>
                                    <p class="text-sm">Nenhuma notificação</p>
                                </div>
                                <div x-show="!loading && notifications.length > 0" x-cloak>
                                    <template x-for="notification in notifications" :key="notification.id">
                                        <div 
                                            @click="handleNotificationClick(notification)"
                                            :class="notification.read_at ? 'bg-white dark:bg-gray-800' : 'bg-indigo-50 dark:bg-indigo-900/20'"
                                            class="px-4 py-3 border-b border-gray-200 dark:border-gray-700 hover:bg-gray-50 dark:hover:bg-gray-700 cursor-pointer transition-colors">
                                            <div class="flex items-start gap-3">
                                                <div class="flex-shrink-0 mt-1">
                                                    <i :class="getNotificationIcon(notification.type) + ' ' + getNotificationColor(notification.type) + ' text-lg'"></i>
                                                </div>
                                                <div class="flex-1 min-w-0">
                                                    <div class="flex items-start justify-between gap-2">
                                                        <p class="text-sm font-medium text-gray-900 dark:text-gray-100 flex-1" 
                                                           x-text="notification.title"></p>
                                                        <span x-show="getNotificationBadge(notification.type)" 
                                                              :class="getNotificationBadge(notification.type)"
                                                              class="px-2 py-0.5 text-xs font-medium rounded-full whitespace-nowrap"
                                                              x-cloak></span>
                                                    </div>
                                                    <p class="text-xs text-gray-600 dark:text-gray-400 mt-1 line-clamp-2" 
                                                       x-text="notification.message"></p>
                                                    <p class="text-xs text-gray-500 dark:text-gray-500 mt-1" 
                                                       x-text="notification.time_ago"></p>
                                                </div>
                                                <div x-show="!notification.read_at" 
                                                     class="flex-shrink-0 mt-1">
                                                    <span class="h-2 w-2 bg-indigo-500 rounded-full block"></span>
                                                </div>
                                            </div>
                                        </div>
                                    </template>
                                </div>
                            </div>
                            
                            <!-- Footer -->
                            <div class="px-4 py-3 border-t border-gray-200 dark:border-gray-700 space-y-2">
                                <button 
                                    @click="markAllAsRead()"
                                    x-show="unreadCount > 0"
                                    class="w-full text-center text-xs text-indigo-600 dark:text-indigo-400 hover:text-indigo-800 dark:hover:text-indigo-300 hover:bg-indigo-50 dark:hover:bg-indigo-900/20 py-2 rounded-md transition-colors">
                                    <i class="fi fi-rr-check-circle mr-1"></i> Marcar todas como lidas
                                </button>
                            </div>
                        </div>
                    </div>
                    
                    <!-- User Menu Dropdown -->
                    <div class="relative" x-data="{ open: false }" @click.away="open = false">
                        <button 
                            @click="open = !open"
                            class="flex items-center gap-1 p-1.5 rounded-md hover:bg-gray-100 dark:hover:bg-gray-700 transition-colors">
                            <div class="flex-shrink-0">
                                @if(Auth::user()->profile_photo)
                                    <img src="{{ asset('storage/' . Auth::user()->profile_photo) }}" alt="{{ Auth::user()->name }}" class="h-8 w-8 rounded-full object-cover border-2 border-gray-200 dark:border-gray-600">
                                @else
                                    <div class="h-8 w-8 rounded-full bg-indigo-500 flex items-center justify-center text-white font-semibold text-sm border-2 border-gray-200 dark:border-gray-600">
                                        {{ strtoupper(substr(Auth::user()->name, 0, 1)) }}
                                    </div>
                                @endif
                            </div>
                            <i class="fi fi-rr-angle-small-down text-xs text-gray-600 dark:text-gray-400 hidden sm:block"></i>
                        </button>
                        
                        <!-- Dropdown Menu -->
                        <div 
                            x-show="open"
                            x-transition:enter="transition ease-out duration-100"
                            x-transition:enter-start="transform opacity-0 scale-95"
                            x-transition:enter-end="transform opacity-100 scale-100"
                            x-transition:leave="transition ease-in duration-75"
                            x-transition:leave-start="transform opacity-100 scale-100"
                            x-transition:leave-end="transform opacity-0 scale-95"
                            x-cloak
                            class="fixed right-4 top-16 w-56 bg-white dark:bg-gray-800 rounded-md shadow-lg border border-gray-200 dark:border-gray-700 py-1 z-50">
                            <div class="px-4 py-3 border-b border-gray-200 dark:border-gray-700">
                                <p class="text-sm font-medium text-gray-900 dark:text-gray-100">{{ Auth::user()->name }}</p>
                                <p class="text-xs text-gray-500 dark:text-gray-400 truncate">{{ Auth::user()->email }}</p>
                            </div>
                            <a 
                                href="{{ route('profile.edit') }}" 
                                class="block px-4 py-2 text-sm text-gray-700 dark:text-gray-300 hover:bg-gray-100 dark:hover:bg-gray-700 transition-colors">
                                <i class="fi fi-rr-user-pen mr-2"></i>
                                Perfil
                            </a>
                            <form method="POST" action="{{ route('logout') }}">
                                @csrf
                                <button 
                                    type="submit" 
                                    class="w-full text-left px-4 py-2 text-sm text-red-600 dark:text-red-400 hover:bg-red-50 dark:hover:bg-red-900/20 transition-colors">
                                    <i class="fi fi-rr-sign-out-alt mr-2"></i>
                                    {{ __('Sair') }}
                                </button>
                            </form>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Sidebar -->
            <aside class="sidebar-container fixed inset-y-0 left-0 bg-white dark:bg-gray-800 border-r border-gray-200 dark:border-gray-700 z-40 transform transition-all duration-200 ease-in-out flex flex-col" 
                   id="sidebar-container" 
                   :class="{ 
                       '-translate-x-full': !sidebarOpen, 
                       'translate-x-0': sidebarOpen,
                       'lg:translate-x-0': !sidebarCollapsed,
                       'lg:-translate-x-full': sidebarCollapsed
                   }" 
                   style="width: 16rem;"
                   @keydown.window.escape="sidebarOpen = false">
                <div class="h-16 px-6 flex items-center border-b border-gray-200 dark:border-gray-700 flex-shrink-0" 
                     x-cloak
                     :class="{ 'lg:hidden': sidebarCollapsed }">
                    <div class="flex-1 flex items-center justify-center lg:justify-start">
                        <!-- Logo modo claro -->
                        <div x-show="!darkMode">
                            <a href="{{ route('dashboard') }}" class="flex items-center gap-2">
                                <img src="{{ asset('assets/images/logo.svg') }}" alt="Logo" class="h-12 w-auto" />
                            </a>
                        </div>
                        <!-- Logo modo escuro -->
                        <div x-show="darkMode">
                            <a href="{{ route('dashboard') }}" class="flex items-center gap-2">
                                <img src="{{ asset('assets/images/logo-light.svg') }}" alt="Logo" class="h-12 w-auto" />
                            </a>
                        </div>
                    </div>
                </div>
                <nav class="flex-1 overflow-y-auto sidebar-scroll space-y-2" 
                     id="sidebar-nav" 
                     :class="{ 'lg:hidden': sidebarCollapsed }"
                     x-data="{ 
                    estoqueOpen: {{ request()->routeIs('products.*') || request()->routeIs('equipment.*') || request()->routeIs('material-requests.*') || request()->routeIs('equipment-requests.*') || request()->routeIs('suppliers.*') ? 'true' : 'false' }},
                    gestaoOpen: {{ request()->routeIs('employees.*') || request()->routeIs('attendance.manage') || request()->routeIs('budgets.*') || request()->routeIs('inspections.*') || (request()->routeIs('projects.*') && !request()->routeIs('client.projects.*')) || request()->routeIs('services.*') || request()->routeIs('labor-types.*') || request()->routeIs('service-categories.*') || request()->routeIs('map.*') || request()->routeIs('clients.*') || request()->routeIs('contracts.*') ? 'true' : 'false' }},
                    financeiroOpen: {{ request()->routeIs('financial.*') ? 'true' : 'false' }},
                    adminOpen: {{ request()->routeIs('admin.permissions.*') || request()->routeIs('admin.email.*') ? 'true' : 'false' }}
                }">
                    <!-- Dashboard sempre visível -->
                    <a href="{{ route('dashboard') }}" class="flex items-center px-3 py-2 rounded-md text-sm font-medium {{ request()->routeIs('dashboard') ? 'bg-indigo-50 dark:bg-indigo-900/50 text-indigo-700 dark:text-indigo-300' : 'text-gray-700 dark:text-gray-300 hover:bg-gray-50 dark:hover:bg-gray-700' }}">
                        <i class="fi fi-rr-dashboard mr-3 mt-1 text-base"></i>
                        {{ __('Dashboard') }}
                    </a>

                    <!-- Dropdown Estoque -->
                    @if(auth()->user()->can('view products') || auth()->user()->can('view service-orders') || auth()->user()->can('view suppliers') || auth()->user()->hasAnyRole(['admin', 'manager']))
                    <div>
                        <button 
                            @click="estoqueOpen = !estoqueOpen" 
                            class="sidebar-dropdown-button w-full flex items-center justify-between px-3 py-2 rounded-md text-sm font-medium text-gray-700 dark:text-gray-300 hover:bg-gray-50 dark:hover:bg-gray-700 transition-colors"
                            :aria-expanded="estoqueOpen"
                        >
                            <div class="flex items-center">
                                <i class="fi fi-rr-box mr-3 text-base mt-1"></i>
                                <span>{{ __('Estoque') }}</span>
                            </div>
                            <i class="fi fi-rr-angle-small-down sidebar-dropdown-arrow flex-shrink-0" :class="{ 'rotate-180': estoqueOpen }"></i>
                        </button>
                        <div x-show="estoqueOpen" 
                             x-transition:enter="transition ease-out duration-200"
                             x-transition:enter-start="opacity-0 transform -translate-y-2"
                             x-transition:enter-end="opacity-100 transform translate-y-0"
                             x-transition:leave="transition ease-in duration-150"
                             x-transition:leave-start="opacity-100 transform translate-y-0"
                             x-transition:leave-end="opacity-0 transform -translate-y-2"
                             class="ml-3 mt-0.5 mb-1 space-y-0.5 border-l-2 border-gray-200 dark:border-gray-700 pl-3">
                            @can('view products')
                            <a href="{{ route('products.index') }}" class="flex items-center px-3 py-2 rounded-md text-sm font-medium {{ request()->routeIs('products.*') ? 'bg-indigo-50 dark:bg-indigo-900/50 text-indigo-700 dark:text-indigo-300' : 'text-gray-600 dark:text-gray-400 hover:bg-gray-50 dark:hover:bg-gray-700' }}">
                                <i class="fi fi-rr-box mr-3 text-base mt-1"></i>
                                {{ __('Produtos') }}
                            </a>
                            @endcan
                            @can('view products')
                            <a href="{{ route('equipment.index') }}" class="flex items-center px-3 py-2 rounded-md text-sm font-medium {{ request()->routeIs('equipment.*') ? 'bg-indigo-50 dark:bg-indigo-900/50 text-indigo-700 dark:text-indigo-300' : 'text-gray-600 dark:text-gray-400 hover:bg-gray-50 dark:hover:bg-gray-700' }}">
                                <i class="fi fi-rr-tools mr-3 text-base mt-1"></i>
                                {{ __('Equipamentos') }}
                            </a>
                            @endcan
                            @can('view service-orders')
                            <a href="{{ route('material-requests.index') }}" class="flex items-center px-3 py-2 rounded-md text-sm font-medium {{ request()->routeIs('material-requests.*') ? 'bg-indigo-50 dark:bg-indigo-900/50 text-indigo-700 dark:text-indigo-300' : 'text-gray-600 dark:text-gray-400 hover:bg-gray-50 dark:hover:bg-gray-700' }}">
                                <i class="fi fi-rr-clipboard mr-3 text-base"></i>
                                {{ __('Requisições de Material') }}
                            </a>
                            @endcan
                            @can('view service-orders')
                            <a href="{{ route('equipment-requests.index') }}" class="flex items-center px-3 py-2 rounded-md text-sm font-medium {{ request()->routeIs('equipment-requests.*') ? 'bg-indigo-50 dark:bg-indigo-900/50 text-indigo-700 dark:text-indigo-300' : 'text-gray-600 dark:text-gray-400 hover:bg-gray-50 dark:hover:bg-gray-700' }}">
                                <i class="fi fi-rr-clipboard mr-3 text-base"></i>
                                {{ __('Requisições de Equipamento') }}
                            </a>
                            @endcan
                            @can('view suppliers')
                            <a href="{{ route('suppliers.index') }}" class="flex items-center px-3 py-2 rounded-md text-sm font-medium {{ request()->routeIs('suppliers.*') ? 'bg-indigo-50 dark:bg-indigo-900/50 text-indigo-700 dark:text-indigo-300' : 'text-gray-600 dark:text-gray-400 hover:bg-gray-50 dark:hover:bg-gray-700' }}">
                                <i class="fi fi-rr-truck-side mr-3 text-base mt-1"></i>
                                {{ __('Fornecedores') }}
                            </a>
                            @endcan
                        </div>
                    </div>
                    @endif

                    <!-- Dropdown Gestão -->
                    @if(auth()->user()->can('view employees') || auth()->user()->can('manage attendance') || auth()->user()->can('view budgets') || auth()->user()->can('view projects') || auth()->user()->can('manage services') || auth()->user()->can('view clients') || auth()->user()->can('view service-orders') || auth()->user()->hasAnyRole(['admin', 'manager']))
                    <div>
                        <button 
                            @click="gestaoOpen = !gestaoOpen" 
                            class="sidebar-dropdown-button w-full flex items-center justify-between px-3 py-2 rounded-md text-sm font-medium text-gray-700 dark:text-gray-300 hover:bg-gray-50 dark:hover:bg-gray-700 transition-colors"
                            :aria-expanded="gestaoOpen"
                        >
                            <div class="flex items-center">
                                <i class="fi fi-rr-settings mt-1 mr-3 text-base"></i>
                                <span>{{ __('Gestão') }}</span>
                            </div>
                            <i class="fi fi-rr-angle-small-down sidebar-dropdown-arrow flex-shrink-0" :class="{ 'rotate-180': gestaoOpen }"></i>
                        </button>
                        <div x-show="gestaoOpen" 
                             x-transition:enter="transition ease-out duration-200"
                             x-transition:enter-start="opacity-0 transform -translate-y-2"
                             x-transition:enter-end="opacity-100 transform translate-y-0"
                             x-transition:leave="transition ease-in duration-150"
                             x-transition:leave-start="opacity-100 transform translate-y-0"
                             x-transition:leave-end="opacity-0 transform -translate-y-2"
                             class="ml-3 mt-0.5 mb-1 space-y-0.5 border-l-2 border-gray-200 dark:border-gray-700 pl-3">
                            @php
                                $user = auth()->user();
                                // Mesma lógica das rotas: role_or_permission:manager|admin|view clients
                                $showClients = $user->hasAnyRole(['admin', 'manager']) || $user->can('view clients');
                                // Mesma lógica das rotas: role_or_permission:manager|admin|view contracts
                                $showContracts = $user->hasAnyRole(['admin', 'manager']) || $user->can('view contracts');
                            @endphp
                            @if($showClients)
                            <a href="{{ route('clients.index') }}" 
                               class="flex items-center px-3 py-2 rounded-md text-sm font-medium {{ request()->routeIs('clients.*') ? 'bg-indigo-50 dark:bg-indigo-900/50 text-indigo-700 dark:text-indigo-300' : 'text-gray-600 dark:text-gray-400 hover:bg-gray-50 dark:hover:bg-gray-700' }}">
                                <i class="fi fi-rr-user mr-3 text-base"></i>
                                {{ __('Clientes') }}
                            </a>
                            @endif
                            @if($showContracts)
                            <a href="{{ route('contracts.index') }}" 
                               class="flex items-center px-3 py-2 rounded-md text-sm font-medium {{ request()->routeIs('contracts.*') ? 'bg-indigo-50 dark:bg-indigo-900/50 text-indigo-700 dark:text-indigo-300' : 'text-gray-600 dark:text-gray-400 hover:bg-gray-50 dark:hover:bg-gray-700' }}">
                                <i class="fi fi-rr-legal mr-3 text-base"></i>
                                {{ __('Contratos') }}
                            </a>
                            @endif
                            @can('view employees')
                            <a href="{{ route('employees.index') }}" class="flex items-center px-3 py-2 rounded-md text-sm font-medium {{ request()->routeIs('employees.*') && !request()->routeIs('employees.proposals.*') ? 'bg-indigo-50 dark:bg-indigo-900/50 text-indigo-700 dark:text-indigo-300' : 'text-gray-600 dark:text-gray-400 hover:bg-gray-50 dark:hover:bg-gray-700' }}">
                                <i class="fi fi-rr-users mr-3 text-base"></i>
                                {{ __('Funcionários') }}
                            </a>
                            @endcan
                            <!-- @can('view employees')
                            <a href="{{ route('proposals.index') }}" class="flex items-center px-3 py-2 rounded-md text-sm font-medium {{ request()->routeIs('proposals.*') || request()->routeIs('employees.proposals.*') ? 'bg-indigo-50 dark:bg-indigo-900/50 text-indigo-700 dark:text-indigo-300' : 'text-gray-600 dark:text-gray-400 hover:bg-gray-50 dark:hover:bg-gray-700' }}">
                                <i class="fi fi-rr-receipt mr-3 text-base"></i>
                                {{ __('Propostas') }}
                            </a>
                            @endcan -->
                            @can('manage attendance')
                            <a href="{{ route('attendance.manage') }}" class="flex items-center px-3 py-2 rounded-md text-sm font-medium {{ request()->routeIs('attendance.manage') ? 'bg-indigo-50 dark:bg-indigo-900/50 text-indigo-700 dark:text-indigo-300' : 'text-gray-600 dark:text-gray-400 hover:bg-gray-50 dark:hover:bg-gray-700' }}">
                                <i class="fi fi-rr-calendar-check mr-3 text-base"></i>
                                {{ __('Gestão de Pontos') }}
                            </a>
                            @endcan
                            @can('view budgets')
                            <a href="{{ route('budgets.index') }}" class="flex items-center px-3 py-2 rounded-md text-sm font-medium {{ request()->routeIs('budgets.*') ? 'bg-indigo-50 dark:bg-indigo-900/50 text-indigo-700 dark:text-indigo-300' : 'text-gray-600 dark:text-gray-400 hover:bg-gray-50 dark:hover:bg-gray-700' }}">
                                <i class="fi fi-rr-receipt mr-3 text-base"></i>
                                {{ __('Orçamentos') }}
                            </a>
                            @endcan
                            <a href="{{ route('inspections.index') }}" class="flex items-center px-3 py-2 rounded-md text-sm font-medium {{ request()->routeIs('inspections.*') ? 'bg-indigo-50 dark:bg-indigo-900/50 text-indigo-700 dark:text-indigo-300' : 'text-gray-600 dark:text-gray-400 hover:bg-gray-50 dark:hover:bg-gray-700' }}">
                                <i class="fi fi-rr-clipboard-check mr-3 text-base"></i>
                                {{ __('Vistorias') }}
                            </a>
                            @can('view projects')
                            <a href="{{ route('projects.index') }}" class="flex items-center px-3 py-2 rounded-md text-sm font-medium {{ request()->routeIs('projects.*') && !request()->routeIs('client.projects.*') ? 'bg-indigo-50 dark:bg-indigo-900/50 text-indigo-700 dark:text-indigo-300' : 'text-gray-600 dark:text-gray-400 hover:bg-gray-50 dark:hover:bg-gray-700' }}">
                                <i class="fi fi-rr-building mr-3 text-base"></i>
                                {{ __('Obras') }}
                            </a>
                            @endcan
                            @can('view projects')
                            <a href="{{ route('map.index') }}" class="flex items-center px-3 py-2 rounded-md text-sm font-medium {{ request()->routeIs('map.*') ? 'bg-indigo-50 dark:bg-indigo-900/50 text-indigo-700 dark:text-indigo-300' : 'text-gray-600 dark:text-gray-400 hover:bg-gray-50 dark:hover:bg-gray-700' }}">
                                <i class="fi fi-rr-map mr-3 text-base"></i>
                                {{ __('Mapa') }}
                            </a>
                            @endcan
                            @can('manage services')
                            <a href="{{ route('services.index') }}" class="flex items-center px-3 py-2 rounded-md text-sm font-medium {{ request()->routeIs('services.*') ? 'bg-indigo-50 dark:bg-indigo-900/50 text-indigo-700 dark:text-indigo-300' : 'text-gray-600 dark:text-gray-400 hover:bg-gray-50 dark:hover:bg-gray-700' }}">
                                <i class="fi fi-rr-person-dolly-empty mr-3 text-base"></i>
                                {{ __('Serviços') }}
                            </a>
                            @endcan
                            @can('manage services')
                            <a href="{{ route('labor-types.index') }}" class="flex items-center px-3 py-2 rounded-md text-sm font-medium {{ request()->routeIs('labor-types.*') ? 'bg-indigo-50 dark:bg-indigo-900/50 text-indigo-700 dark:text-indigo-300' : 'text-gray-600 dark:text-gray-400 hover:bg-gray-50 dark:hover:bg-gray-700' }}">
                                <i class="fi fi-rr-users-alt mr-3 text-base"></i>
                                {{ __('Tipos de Mão de Obra') }}
                            </a>
                            @endcan
                            @can('manage services')
                            <a href="{{ route('service-categories.index') }}" class="flex items-center px-3 py-2 rounded-md text-sm font-medium {{ request()->routeIs('service-categories.*') ? 'bg-indigo-50 dark:bg-indigo-900/50 text-indigo-700 dark:text-indigo-300' : 'text-gray-600 dark:text-gray-400 hover:bg-gray-50 dark:hover:bg-gray-700' }}">
                                <i class="fi fi-rr-folder mr-3 text-base"></i>
                                {{ __('Categorias de Serviços') }}
                            </a>
                            @endcan
                        </div>
                    </div>
                    @endif

                    @if(auth()->user()->hasRole('client'))
                    <a href="{{ route('client.dashboard') }}" class="flex items-center px-3 py-2 rounded-md text-sm font-medium {{ request()->routeIs('client.dashboard') ? 'bg-indigo-50 dark:bg-indigo-900/50 text-indigo-700 dark:text-indigo-300' : 'text-gray-700 dark:text-gray-300 hover:bg-gray-50 dark:hover:bg-gray-700' }}">
                        <i class="fi fi-rr-dashboard mr-3 text-base"></i>
                        {{ __('Dashboard') }}
                    </a>
                    <a href="{{ route('client.projects.index') }}" class="flex items-center px-3 py-2 rounded-md text-sm font-medium {{ request()->routeIs('client.projects.*') && !request()->routeIs('client.dashboard') ? 'bg-indigo-50 dark:bg-indigo-900/50 text-indigo-700 dark:text-indigo-300' : 'text-gray-700 dark:text-gray-300 hover:bg-gray-50 dark:hover:bg-gray-700' }}">
                        <i class="fi fi-rr-home mr-3 text-base"></i>
                        {{ __('Minhas Obras') }}
                    </a>
                    @endif
                    @can('view reports')
                    <a href="{{ route('reports.index') }}" class="flex items-center px-3 py-2 rounded-md text-sm font-medium {{ request()->routeIs('reports.*') ? 'bg-indigo-50 dark:bg-indigo-900/50 text-indigo-700 dark:text-indigo-300' : 'text-gray-700 dark:text-gray-300 hover:bg-gray-50 dark:hover:bg-gray-700' }}">
                        <i class="fi fi-rr-stats mr-3 mt-1 text-base"></i>
                        {{ __('Relatórios') }}
                    </a>
                    @endcan

                    <!-- Dropdown Financeiro -->
                    @can('manage finances')
                    <div>
                        <button 
                            @click="financeiroOpen = !financeiroOpen" 
                            class="sidebar-dropdown-button w-full flex items-center justify-between px-3 py-2 rounded-md text-sm font-medium text-gray-700 dark:text-gray-300 hover:bg-gray-50 dark:hover:bg-gray-700 transition-colors"
                            :aria-expanded="financeiroOpen"
                        >
                            <div class="flex items-center">
                                <i class="fi fi-rr-money mr-3 mt-1 text-base"></i>
                                <span>{{ __('Financeiro') }}</span>
                            </div>
                            <i class="fi fi-rr-angle-small-down sidebar-dropdown-arrow flex-shrink-0" :class="{ 'rotate-180': financeiroOpen }"></i>
                        </button>
                        <div x-show="financeiroOpen" 
                             x-transition:enter="transition ease-out duration-200"
                             x-transition:enter-start="opacity-0 transform -translate-y-2"
                             x-transition:enter-end="opacity-100 transform translate-y-0"
                             x-transition:leave="transition ease-in duration-150"
                             x-transition:leave-start="opacity-100 transform translate-y-0"
                             x-transition:leave-end="opacity-0 transform -translate-y-2"
                             class="ml-3 mt-0.5 mb-1 space-y-0.5 border-l-2 border-gray-200 dark:border-gray-700 pl-3">
                            <a href="{{ route('financial.dashboard') }}" class="flex items-center px-3 py-2 rounded-md text-sm font-medium {{ request()->routeIs('financial.dashboard') ? 'bg-indigo-50 dark:bg-indigo-900/50 text-indigo-700 dark:text-indigo-300' : 'text-gray-600 dark:text-gray-400 hover:bg-gray-50 dark:hover:bg-gray-700' }}">
                                <i class="fi fi-rr-dashboard mr-3 text-base mt-1"></i>
                                {{ __('Dashboard Financeiro') }}
                            </a>
                            <a href="{{ route('financial.accounts-payable.index') }}" class="flex items-center px-3 py-2 rounded-md text-sm font-medium {{ request()->routeIs('financial.accounts-payable.*') ? 'bg-indigo-50 dark:bg-indigo-900/50 text-indigo-700 dark:text-indigo-300' : 'text-gray-600 dark:text-gray-400 hover:bg-gray-50 dark:hover:bg-gray-700' }}">
                                <i class="fi fi-rr-arrow-trend-down mr-3 text-base mt-1"></i>
                                {{ __('Contas a Pagar') }}
                            </a>
                            <a href="{{ route('financial.accounts-receivable.index') }}" class="flex items-center px-3 py-2 rounded-md text-sm font-medium {{ request()->routeIs('financial.accounts-receivable.*') ? 'bg-indigo-50 dark:bg-indigo-900/50 text-indigo-700 dark:text-indigo-300' : 'text-gray-600 dark:text-gray-400 hover:bg-gray-50 dark:hover:bg-gray-700' }}">
                                <i class="fi fi-rr-arrow-trend-up mr-3 text-base mt-1"></i>
                                {{ __('Contas a Receber') }}
                            </a>
                            <a href="{{ route('financial.invoices.index') }}" class="flex items-center px-3 py-2 rounded-md text-sm font-medium {{ request()->routeIs('financial.invoices.*') ? 'bg-indigo-50 dark:bg-indigo-900/50 text-indigo-700 dark:text-indigo-300' : 'text-gray-600 dark:text-gray-400 hover:bg-gray-50 dark:hover:bg-gray-700' }}">
                                <i class="fi fi-rr-receipt mr-3 text-base mt-1"></i>
                                {{ __('Notas Fiscais') }}
                            </a>
                            <a href="{{ route('financial.receipts.index') }}" class="flex items-center px-3 py-2 rounded-md text-sm font-medium {{ request()->routeIs('financial.receipts.*') ? 'bg-indigo-50 dark:bg-indigo-900/50 text-indigo-700 dark:text-indigo-300' : 'text-gray-600 dark:text-gray-400 hover:bg-gray-50 dark:hover:bg-gray-700' }}">
                                <i class="fi fi-rr-receipt mr-3 text-base mt-1"></i>
                                {{ __('Recibos') }}
                            </a>
                        </div>
                    </div>
                    @endcan

                    @if(auth()->user()->hasRole('employee'))
                    <a href="{{ route('attendance.index') }}" class="flex items-center px-3 py-2 rounded-md text-sm font-medium {{ request()->routeIs('attendance.index') ? 'bg-indigo-50 dark:bg-indigo-900/50 text-indigo-700 dark:text-indigo-300' : 'text-gray-700 dark:text-gray-300 hover:bg-gray-50 dark:hover:bg-gray-700' }}">
                        <i class="fi fi-rr-map mr-3 text-base"></i>
                        {{ __('Bater Ponto') }}
                    </a>
                    @endif

                    <!-- Dropdown Administração -->
                    @can('manage permissions')
                    <div>
                        <button 
                            @click="adminOpen = !adminOpen" 
                            class="sidebar-dropdown-button w-full flex items-center justify-between px-3 py-2 rounded-md text-sm font-medium text-gray-700 dark:text-gray-300 hover:bg-gray-50 dark:hover:bg-gray-700 transition-colors"
                            :aria-expanded="adminOpen"
                        >
                            <div class="flex items-center">
                                <i class="fi fi-rr-shield-check mr-3 mt-1 text-base"></i>
                                <span>{{ __('Administração') }}</span>
                            </div>
                            <i class="fi fi-rr-angle-small-down sidebar-dropdown-arrow flex-shrink-0" :class="{ 'rotate-180': adminOpen }"></i>
                        </button>
                        <div x-show="adminOpen" 
                             x-transition:enter="transition ease-out duration-200"
                             x-transition:enter-start="opacity-0 transform -translate-y-2"
                             x-transition:enter-end="opacity-100 transform translate-y-0"
                             x-transition:leave="transition ease-in duration-150"
                             x-transition:leave-start="opacity-100 transform translate-y-0"
                             x-transition:leave-end="opacity-0 transform -translate-y-2"
                             class="ml-3 mt-0.5 mb-1 space-y-0.5 border-l-2 border-gray-200 dark:border-gray-700 pl-3">
                            <a href="{{ route('admin.permissions.users') }}" class="flex items-center px-3 py-2 rounded-md text-sm font-medium {{ request()->routeIs('admin.permissions.users') ? 'bg-indigo-50 dark:bg-indigo-900/50 text-indigo-700 dark:text-indigo-300' : 'text-gray-600 dark:text-gray-400 hover:bg-gray-50 dark:hover:bg-gray-700' }}">
                                <i class="fi fi-rr-user-check mr-3 text-base mt-1"></i>
                                {{ __('Permissões: Usuários') }}
                            </a>
                            <a href="{{ route('admin.permissions.roles') }}" class="flex items-center px-3 py-2 rounded-md text-sm font-medium {{ request()->routeIs('admin.permissions.roles') ? 'bg-indigo-50 dark:bg-indigo-900/50 text-indigo-700 dark:text-indigo-300' : 'text-gray-600 dark:text-gray-400 hover:bg-gray-50 dark:hover:bg-gray-700' }}">
                                <i class="fi fi-rr-shield mr-3 text-base mt-1"></i>
                                {{ __('Permissões: Papéis') }}
                            </a>
                            <a href="{{ route('admin.email.index') }}" class="flex items-center px-3 py-2 rounded-md text-sm font-medium {{ request()->routeIs('admin.email.*') ? 'bg-indigo-50 dark:bg-indigo-900/50 text-indigo-700 dark:text-indigo-300' : 'text-gray-600 dark:text-gray-400 hover:bg-gray-50 dark:hover:bg-gray-700' }}">
                                <i class="fi fi-rr-envelope mr-3 text-base mt-1"></i>
                                {{ __('Envio de Emails') }}
                            </a>
                            <a href="{{ route('admin.settings') }}" class="flex items-center px-3 py-2 rounded-md text-sm font-medium {{ request()->routeIs('admin.settings') ? 'bg-indigo-50 dark:bg-indigo-900/50 text-indigo-700 dark:text-indigo-300' : 'text-gray-600 dark:text-gray-400 hover:bg-gray-50 dark:hover:bg-gray-700' }}">
                                <i class="fi fi-rr-settings mt-1 mr-3 text-base"></i>
                                {{ __('Configurações') }}
                            </a>
                        </div>
                    </div>
                    @endcan
                </nav>
            </aside>

            <!-- Overlay for mobile -->
            <div class="fixed inset-0 bg-black/40 dark:bg-black/60 z-30 lg:hidden" 
                 x-show="sidebarOpen"
                 x-cloak
                 x-transition:enter="transition-opacity ease-linear duration-200"
                 x-transition:enter-start="opacity-0"
                 x-transition:enter-end="opacity-100"
                 x-transition:leave="transition-opacity ease-linear duration-200"
                 x-transition:leave-start="opacity-100"
                 x-transition:leave-end="opacity-0"
                 @click="sidebarOpen = false"
                 @touchstart="sidebarOpen = false"
                 aria-hidden="true"></div>

            <!-- Top Header Bar -->
            <header class="topbar-container fixed top-0 right-0 h-16 bg-white dark:bg-gray-800 border-b border-gray-200 dark:border-gray-700 z-30 transition-all duration-200 lg:block hidden lg:left-64" 
                    :class="{ 'lg:left-64': !sidebarCollapsed, 'lg:left-0': sidebarCollapsed }">
                <div class="h-full px-4 sm:px-6 lg:px-6 flex items-center justify-between">
                    <div class="flex items-center gap-3">
                        <!-- Toggle Sidebar Button -->
                        <button 
                            @click="toggleSidebar()"
                            class="p-2 rounded-md text-gray-600 dark:text-gray-300 hover:bg-gray-100 dark:hover:bg-gray-700 transition-colors"
                            aria-label="Toggle sidebar">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"></path>
                            </svg>
                        </button>
                        
                        @if (isset($header))
                            <div class="flex-1">
                                {{ $header }}
                            </div>
                        @endif
                    </div>
                    
                    <!-- Search Bar -->
                    <div class="flex-1 max-w-xl mx-4 hidden lg:block">
                        <button 
                            @click="openSearchModal = true"
                            class="w-full flex items-center gap-3 px-4 py-2 bg-gray-100 dark:bg-gray-700 rounded-lg border border-gray-200 dark:border-gray-600 hover:bg-gray-200 dark:hover:bg-gray-600 transition-colors text-left">
                            <i class="fi fi-rr-search text-gray-400 dark:text-gray-500 mt-1"></i>
                            <span class="text-sm text-gray-500 dark:text-gray-400 flex-1">Pesquisar...</span>
                            <kbd class="hidden sm:inline-flex items-center px-2 py-1 text-xs font-semibold text-gray-500 dark:text-gray-400 bg-white dark:bg-gray-800 border border-gray-200 dark:border-gray-600 rounded">
                                <span class="text-xs align-middle">⌘</span> K
                            </kbd>
                        </button>
                    </div>
                    
                    <!-- User Menu and Theme Toggle -->
                    <div class="flex items-center gap-3 ml-6">
                        <!-- Theme Toggle -->
                        <button 
                            @click="toggleDarkMode()"
                            class="p-2 rounded-md text-gray-600 dark:text-gray-300 hover:bg-gray-100 dark:hover:bg-gray-700 transition-colors"
                            aria-label="Toggle dark mode">
                            <i class="fi fi-rr-sun text-lg dark:hidden"></i>
                            <i class="fi fi-rr-moon text-lg hidden dark:inline"></i>
                        </button>
                        
                        <!-- Notifications Dropdown -->
                        <div class="relative" 
                             x-data="notificationDropdown()"
                             @click.away="open = false">
                            <button 
                                @click="open = !open; if (open && notifications.length === 0) loadNotifications()"
                                class="relative p-2 rounded-md text-gray-600 dark:text-gray-300 hover:bg-gray-100 dark:hover:bg-gray-700 transition-colors"
                                aria-label="Notificações">
                                <i class="fi fi-rr-bell text-lg mt-1"></i>
                                <span x-show="unreadCount > 0" 
                                      x-text="unreadCount > 99 ? '99+' : unreadCount"
                                      class="absolute -top-1 -right-1 h-5 w-5 bg-red-500 text-white text-xs font-bold rounded-full flex items-center justify-center"
                                      style="min-width: 1.25rem;"></span>
                            </button>
                            
                            <!-- Dropdown de Notificações -->
                            <div 
                                x-show="open"
                                x-transition:enter="transition ease-out duration-100"
                                x-transition:enter-start="transform opacity-0 scale-95"
                                x-transition:enter-end="transform opacity-100 scale-100"
                                x-transition:leave="transition ease-in duration-75"
                                x-transition:leave-start="transform opacity-100 scale-100"
                                x-transition:leave-end="transform opacity-0 scale-95"
                                x-cloak
                                class="absolute right-0 mt-2 w-80 bg-white dark:bg-gray-800 rounded-md shadow-lg border border-gray-200 dark:border-gray-700 z-50 max-h-96 overflow-hidden flex flex-col">
                                <!-- Header -->
                                <div class="px-4 py-3 border-b border-gray-200 dark:border-gray-700 flex items-center justify-between">
                                    <h3 class="text-sm font-semibold text-gray-900 dark:text-gray-100">Notificações</h3>
                                        <a href="{{ route('notifications.index') }}" 
                                           class="text-xs text-indigo-600 dark:text-indigo-400 hover:text-indigo-800 dark:hover:text-indigo-300">
                                            Ver todas
                                        </a>
                                </div>
                                
                                <!-- Lista de Notificações -->
                                <div class="overflow-y-auto flex-1" style="max-height: 20rem;">
                                    <div x-show="loading" x-cloak class="px-4 py-8 text-center text-gray-500 dark:text-gray-400">
                                        <i class="fi fi-rr-spinner animate-spin text-2xl mb-2"></i>
                                        <p class="text-sm">Carregando...</p>
                                    </div>
                                    <div x-show="!loading && notifications.length === 0" x-cloak class="px-4 py-8 text-center text-gray-500 dark:text-gray-400">
                                        <i class="fi fi-rr-bell-slash text-3xl mb-2"></i>
                                        <p class="text-sm">Nenhuma notificação</p>
                                    </div>
                                    <div x-show="!loading && notifications.length > 0" x-cloak>
                                        <template x-for="notification in notifications" :key="notification.id">
                                            <div 
                                                @click="handleNotificationClick(notification)"
                                                :class="notification.read_at ? 'bg-white dark:bg-gray-800' : 'bg-indigo-50 dark:bg-indigo-900/20'"
                                                class="px-4 py-3 border-b border-gray-200 dark:border-gray-700 hover:bg-gray-50 dark:hover:bg-gray-700 cursor-pointer transition-colors">
                                                <div class="flex items-start gap-3">
                                                    <div class="flex-shrink-0 mt-1">
                                                        <i :class="getNotificationIcon(notification.type) + ' ' + getNotificationColor(notification.type) + ' text-lg'"></i>
                                                    </div>
                                                    <div class="flex-1 min-w-0">
                                                        <div class="flex items-start justify-between gap-2">
                                                            <p class="text-sm font-medium text-gray-900 dark:text-gray-100 flex-1" 
                                                               x-text="notification.title"></p>
                                                            <span x-show="getNotificationBadge(notification.type)" 
                                                                  :class="getNotificationBadge(notification.type)"
                                                                  class="px-2 py-0.5 text-xs font-medium rounded-full whitespace-nowrap"
                                                                  x-cloak></span>
                                                        </div>
                                                        <p class="text-xs text-gray-600 dark:text-gray-400 mt-1 line-clamp-2" 
                                                           x-text="notification.message"></p>
                                                        <p class="text-xs text-gray-500 dark:text-gray-500 mt-1" 
                                                           x-text="notification.time_ago"></p>
                                                    </div>
                                                    <div x-show="!notification.read_at" 
                                                         class="flex-shrink-0 mt-1">
                                                        <span class="h-2 w-2 bg-indigo-500 rounded-full block"></span>
                                                    </div>
                                                </div>
                                            </div>
                                        </template>
                                    </div>
                                </div>
                                
                                <!-- Footer -->
                                <div class="px-4 py-3 border-t border-gray-200 dark:border-gray-700 space-y-2">
                                    <button 
                                        @click="markAllAsRead()"
                                        x-show="unreadCount > 0"
                                        class="w-full text-center text-xs text-indigo-600 dark:text-indigo-400 hover:text-indigo-800 dark:hover:text-indigo-300 hover:bg-indigo-50 dark:hover:bg-indigo-900/20 py-2 rounded-md transition-colors">
                                        <i class="fi fi-rr-check-circle mr-1"></i> Marcar todas como lidas
                                    </button>
                                </div>
                            </div>
                        </div>
                        
                        <!-- User Menu Dropdown -->
                        <div class="relative" x-data="{ open: false }" @click.away="open = false">
                            <button 
                                @click="open = !open"
                                class="flex items-center gap-2 p-2 rounded-md hover:bg-gray-100 dark:hover:bg-gray-700 transition-colors">
                                <div class="flex-shrink-0">
                                    @if(Auth::user()->profile_photo)
                                        <img src="{{ asset('storage/' . Auth::user()->profile_photo) }}" alt="{{ Auth::user()->name }}" class="h-8 w-8 rounded-full object-cover border-2 border-gray-200 dark:border-gray-600">
                                    @else
                                        <div class="h-8 w-8 rounded-full bg-indigo-500 flex items-center justify-center text-white font-semibold text-sm border-2 border-gray-200 dark:border-gray-600">
                                            {{ strtoupper(substr(Auth::user()->name, 0, 1)) }}
                                        </div>
                                    @endif
                                </div>
                                <span class="hidden sm:block text-sm font-medium text-gray-700 dark:text-gray-300">{{ Auth::user()->name }}</span>
                                <i class="fi fi-rr-angle-small-down text-xs text-gray-600 dark:text-gray-400"></i>
                            </button>
                            
                            <!-- Dropdown Menu -->
                            <div 
                                x-show="open"
                                x-transition:enter="transition ease-out duration-100"
                                x-transition:enter-start="transform opacity-0 scale-95"
                                x-transition:enter-end="transform opacity-100 scale-100"
                                x-transition:leave="transition ease-in duration-75"
                                x-transition:leave-start="transform opacity-100 scale-100"
                                x-transition:leave-end="transform opacity-0 scale-95"
                                x-cloak
                                class="absolute right-0 mt-2 w-56 bg-white dark:bg-gray-800 rounded-md shadow-lg border border-gray-200 dark:border-gray-700 py-1 z-50">
                                <div class="px-4 py-3 border-b border-gray-200 dark:border-gray-700">
                                    <p class="text-sm font-medium text-gray-900 dark:text-gray-100">{{ Auth::user()->name }}</p>
                                    <p class="text-xs text-gray-500 dark:text-gray-400 truncate">{{ Auth::user()->email }}</p>
                                </div>
                                <a 
                                    href="{{ route('profile.edit') }}" 
                                    class="block px-4 py-2 text-sm text-gray-700 dark:text-gray-300 hover:bg-gray-100 dark:hover:bg-gray-700 transition-colors">
                                    <i class="fi fi-rr-user-pen mr-2"></i>
                                    Perfil
                                </a>
                                <form method="POST" action="{{ route('logout') }}">
                                    @csrf
                                    <button 
                                        type="submit" 
                                        class="w-full text-left px-4 mt-2 py-2 text-sm text-red-600 dark:text-red-400 hover:bg-red-50 dark:hover:bg-red-900/20 transition-colors">
                                        <i class="fi fi-rr-sign-out-alt mr-2"></i>
                                        {{ __('Sair') }}
                                    </button>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
            </header>

            <!-- Content -->
            <div class="content-container pt-16 lg:pt-16 lg:ml-64 transition-all duration-200" 
                 :class="{ 'lg:ml-64': !sidebarCollapsed, 'lg:ml-0': sidebarCollapsed }">
                <main class="p-4 sm:p-4">
                    {{ $slot }}
                </main>
            </div>
            
            <!-- Modal de Pesquisa Global -->
            <div 
                x-show="openSearchModal"
                x-cloak
                x-transition:enter="transition ease-out duration-300"
                x-transition:enter-start="opacity-0"
                x-transition:enter-end="opacity-100"
                x-transition:leave="transition ease-in duration-200"
                x-transition:leave-start="opacity-100"
                x-transition:leave-end="opacity-0"
                class="fixed inset-0 z-50 flex items-start justify-center pt-20"
                style="background-color: rgba(0, 0, 0, 0.75); backdrop-filter: blur(4px); -webkit-backdrop-filter: blur(4px);"
                @click.self="openSearchModal = false"
                @keydown.escape.window="openSearchModal = false; searchQuery = ''; searchResults = []">
                <div 
                    x-show="openSearchModal"
                    x-transition:enter="transition ease-out duration-300"
                    x-transition:enter-start="opacity-0 scale-95"
                    x-transition:enter-end="opacity-100 scale-100"
                    x-transition:leave="transition ease-in duration-200"
                    x-transition:leave-start="opacity-100 scale-100"
                    x-transition:leave-end="opacity-0 scale-95"
                    class="relative bg-white dark:bg-gray-800 rounded-lg shadow-2xl w-full max-w-2xl mx-4 border border-gray-200 dark:border-gray-700 overflow-hidden"
                    @click.stop>
                    <!-- Search Input -->
                    <div class="p-4 border-b border-gray-200 dark:border-gray-700">
                        <div class="relative">
                            <i class="fi fi-rr-search absolute left-3 top-1/2 transform -translate-y-1/2 text-gray-400 dark:text-gray-500 mt-[2px]"></i>
                            <input 
                                id="search-input"
                                type="text"
                                x-model="searchQuery"
                                placeholder="Pesquisar produtos, equipamentos, clientes, funcionários..."
                                class="w-full pl-10 pr-4 py-3 bg-gray-50 dark:bg-gray-700 border border-gray-200 dark:border-gray-600 rounded-md focus:outline-none focus:ring-2 focus:ring-indigo-500 dark:focus:ring-indigo-400 text-gray-900 dark:text-gray-100 placeholder-gray-400 dark:placeholder-gray-500"
                                @keydown.arrow-down.prevent="selectedIndex = Math.min(selectedIndex + 1, searchResults.length - 1)"
                                @keydown.arrow-up.prevent="selectedIndex = Math.max(selectedIndex - 1, -1)"
                                @keydown.enter.prevent="if (selectedIndex >= 0 && searchResults[selectedIndex]) { window.location.href = searchResults[selectedIndex].url; } else if (searchResults.length > 0) { window.location.href = searchResults[0].url; }">
                            <button 
                                x-show="searchQuery"
                                @click="searchQuery = ''; searchResults = []"
                                class="absolute mt-1 right-3 top-1/2 transform -translate-y-1/2 text-gray-400 dark:text-gray-500 hover:text-gray-600 dark:hover:text-gray-300">
                                <i class="fi fi-rr-cross-small"></i>
                            </button>
                        </div>
                    </div>
                    
                    <!-- Results -->
                    <div class="max-h-96 overflow-y-auto">
                        <!-- Loading -->
                        <div x-show="searchLoading" class="p-8 text-center">
                            <i class="fi fi-rr-spinner animate-spin text-2xl text-gray-400 dark:text-gray-500 mb-2"></i>
                            <p class="text-sm text-gray-500 dark:text-gray-400">Pesquisando...</p>
                        </div>
                        
                        <!-- History (quando não há query) -->
                        <div x-show="!searchQuery && !searchLoading && searchHistory.length > 0" class="p-4">
                            <div class="flex items-center justify-between mb-3">
                                <h3 class="text-xs font-semibold text-gray-500 dark:text-gray-400 uppercase tracking-wider">Histórico</h3>
                                <button 
                                    @click="localStorage.removeItem('searchHistory'); searchHistory = []"
                                    class="text-xs text-indigo-600 dark:text-indigo-400 hover:text-indigo-800 dark:hover:text-indigo-300">
                                    Limpar
                                </button>
                            </div>
                            <div class="space-y-1">
                                <template x-for="(item, index) in searchHistory" :key="index">
                                    <button 
                                        @click="searchQuery = item"
                                        class="w-full text-left px-3 py-2 text-sm text-gray-700 dark:text-gray-300 hover:bg-gray-100 dark:hover:bg-gray-700 rounded-md flex items-center gap-2 transition-colors">
                                        <i class="fi fi-rr-clock text-gray-400"></i>
                                        <span x-text="item"></span>
                                    </button>
                                </template>
                            </div>
                        </div>
                        
                        <!-- Empty State -->
                        <div x-show="!searchQuery && !searchLoading && searchHistory.length === 0" class="p-8 text-center">
                            <i class="fi fi-rr-search text-3xl text-gray-300 dark:text-gray-600 mb-2"></i>
                            <p class="text-sm text-gray-500 dark:text-gray-400">Digite para pesquisar...</p>
                        </div>
                        
                        <!-- No Results -->
                        <div x-show="searchQuery && !searchLoading && searchResults.length === 0" class="p-8 text-center">
                            <i class="fi fi-rr-search-alt text-3xl text-gray-300 dark:text-gray-600 mb-2"></i>
                            <p class="text-sm text-gray-500 dark:text-gray-400">Nenhum resultado encontrado</p>
                        </div>
                        
                        <!-- Results List -->
                        <div x-show="!searchLoading && searchResults.length > 0" class="p-2">
                            <template x-for="(result, index) in searchResults" :key="index">
                                <a 
                                    :href="result.url"
                                    :class="selectedIndex === index ? 'bg-indigo-50 dark:bg-indigo-900/20 border-indigo-200 dark:border-indigo-700' : 'border-transparent'"
                                    class="block px-4 py-3 border rounded-lg mb-2 hover:bg-gray-50 dark:hover:bg-gray-700 transition-colors">
                                    <div class="flex items-start gap-3">
                                        <!-- Photo/Icon -->
                                        <div class="flex-shrink-0">
                                            <template x-if="result.type === 'document'">
                                                <div class="w-12 h-12 bg-red-100 dark:bg-red-900/20 rounded-lg flex items-center justify-center">
                                                    <i class="fi fi-rr-file-pdf text-red-600 dark:text-red-400 text-xl"></i>
                                                </div>
                                            </template>
                                            <template x-if="result.type !== 'document' && result.photo">
                                                <img :src="result.photo" :alt="result.title" class="w-12 h-12 rounded-lg object-cover border border-gray-200 dark:border-gray-700">
                                            </template>
                                            <template x-if="result.type !== 'document' && !result.photo">
                                                <div class="w-12 h-12 bg-gray-100 dark:bg-gray-700 rounded-lg flex items-center justify-center">
                                                    <i :class="window.getTypeIcon(result.type)" class="text-gray-400 dark:text-gray-500 text-xl"></i>
                                                </div>
                                            </template>
                                        </div>
                                        
                                        <!-- Content -->
                                        <div class="flex-1 min-w-0">
                                            <div class="flex items-start justify-between gap-2">
                                                <h4 class="text-sm font-medium text-gray-900 dark:text-gray-100 truncate" x-text="result.title"></h4>
                                                <span class="text-xs px-2 bg-gray-100 dark:bg-gray-700 text-gray-600 dark:text-gray-400 rounded" x-text="window.getTypeLabel(result.type)"></span>
                                            </div>
                                            <p class="text-xs text-gray-500 dark:text-gray-400" x-text="result.subtitle"></p>
                                            <p x-show="result.description" class="text-xs text-gray-400 dark:text-gray-500 line-clamp-1" x-text="result.description"></p>
                                        </div>
                                    </div>
                                </a>
                            </template>
                        </div>
                    </div>
                    
                    <!-- Footer -->
                    <div class="px-4 py-3 border-t border-gray-200 dark:border-gray-700 bg-gray-50 dark:bg-gray-900/50">
                        <div class="flex items-center justify-between text-xs text-gray-500 dark:text-gray-400">
                            <div class="flex items-center gap-4">
                                <span class="flex items-center gap-1">
                                    <kbd class="px-1.5 py-0.5 bg-white dark:bg-gray-800 border border-gray-200 dark:border-gray-700 rounded">↑</kbd>
                                    <kbd class="px-1.5 py-0.5 bg-white dark:bg-gray-800 border border-gray-200 dark:border-gray-700 rounded">↓</kbd>
                                    <span>navegar</span>
                                </span>
                                <span class="flex items-center gap-1">
                                    <kbd class="px-1.5 py-0.5 bg-white dark:bg-gray-800 border border-gray-200 dark:border-gray-700 rounded">↵</kbd>
                                    <span>selecionar</span>
                                </span>
                            </div>
                            <span class="flex items-center gap-1">
                                <kbd class="px-1.5 py-0.5 bg-white dark:bg-gray-800 border border-gray-200 dark:border-gray-700 rounded">Esc</kbd>
                                <span>fechar</span>
                            </span>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <script>
            function notificationDropdown() {
                return {
                    open: false,
                    unreadCount: 0,
                    notifications: [],
                    loading: false,
                    sendingTest: false,
                    soundEnabled: localStorage.getItem('notificationSoundEnabled') !== 'false',
                    soundFile: localStorage.getItem('notificationSoundFile') || 'default',
                    audioContext: null,
                    audioElement: null,
                    init() {
                        // Carregar contador inicial apenas uma vez
                        this.loadUnreadCount();
                        
                        // Carregar notificações iniciais
                        this.loadNotifications();
                        
                        // Escutar eventos de notificação recebida via WebSocket
                        window.addEventListener('notification-received', (event) => {
                            this.handleNewNotification(event.detail);
                        });
                    },
                    async playNotificationSound() {
                        if (!this.soundEnabled || !this.soundFile || this.soundFile === 'default') return;
                        await this.playSoundFile(this.soundFile);
                    },
                    async playDefaultSound() {
                        try {
                            const ctx = new (window.AudioContext || window.webkitAudioContext)();
                            
                            if (ctx.state === 'suspended') {
                                for (let i = 0; i < 3; i++) {
                                    try {
                                        await ctx.resume();
                                        await new Promise(resolve => setTimeout(resolve, 50));
                                        if (ctx.state === 'running') break;
                                    } catch (e) {
                                        // Silenciosamente tenta novamente
                                    }
                                }
                            }
                            
                            const osc = ctx.createOscillator();
                            const gain = ctx.createGain();
                            
                            osc.connect(gain);
                            gain.connect(ctx.destination);
                            
                            const now = ctx.currentTime;
                            osc.frequency.setValueAtTime(800, now);
                            osc.frequency.setValueAtTime(600, now + 0.1);
                            osc.type = 'sine';
                            
                            gain.gain.setValueAtTime(0, now);
                            gain.gain.linearRampToValueAtTime(1.0, now + 0.01);
                            gain.gain.exponentialRampToValueAtTime(0.01, now + 0.2);
                            
                            gain.gain.setValueAtTime(0, now + 0.25);
                            gain.gain.linearRampToValueAtTime(1.0, now + 0.26);
                            gain.gain.exponentialRampToValueAtTime(0.01, now + 0.45);
                            
                            gain.gain.setValueAtTime(0, now + 0.5);
                            gain.gain.linearRampToValueAtTime(1.0, now + 0.51);
                            gain.gain.exponentialRampToValueAtTime(0.01, now + 0.7);
                            
                            osc.start(now);
                            osc.stop(now + 0.7);
                        } catch (error) {
                            console.error('Erro ao tocar som:', error);
                        }
                    },
                    async playSoundFile(filename) {
                        try {
                            const audioUrl = `/sounds/${filename}`;
                            const audio = new Audio(audioUrl);
                            audio.volume = 1.0;
                            audio.preload = 'auto';
                            
                            audio.addEventListener('error', (e) => {
                                console.error('Erro no áudio:', e, audio.error);
                            });
                            
                            return new Promise((resolve, reject) => {
                                const playIt = () => {
                                    audio.play()
                                        .then(() => resolve())
                                        .catch((err) => {
                                            console.error('Erro ao tocar:', err);
                                            reject(err);
                                        });
                                };
                                
                                if (audio.readyState >= 2) {
                                    playIt();
                                } else {
                                    audio.addEventListener('canplay', playIt, { once: true });
                                    audio.addEventListener('error', reject, { once: true });
                                    setTimeout(() => {
                                        if (audio.readyState < 2) {
                                            playIt();
                                        }
                                    }, 2000);
                                }
                            });
                        } catch (error) {
                            console.error('Erro ao tocar arquivo:', error);
                        }
                    },
                    async testSound() {
                        await this.playNotificationSound();
                    },
                    toggleSound() {
                        this.soundEnabled = !this.soundEnabled;
                        localStorage.setItem('notificationSoundEnabled', this.soundEnabled);
                        if (this.soundEnabled) {
                            this.playNotificationSound(); // Testar o som
                        }
                    },
                    setSoundFile(filename) {
                        this.soundFile = filename;
                        localStorage.setItem('notificationSoundFile', filename);
                        // Sincronizar com notification-system.js
                        if (window.notificationSystem) {
                            window.notificationSystem.setSoundFile(filename);
                        }
                        if (this.soundEnabled) {
                            this.playNotificationSound(); // Testar o novo som
                        }
                    },
                    handleNewNotification(data) {
                        // O som já foi tocado pelo notification-system.js via WebSocket
                        // Não precisa tocar novamente aqui
                        
                        // Atualizar contador
                        this.unreadCount = (this.unreadCount || 0) + 1;
                        
                        // Adicionar notificação à lista imediatamente (sempre, não só se dropdown estiver aberto)
                        const timeAgo = this.getTimeAgo(data.created_at);
                        const newNotification = {
                            ...data,
                            time_ago: timeAgo,
                        };
                        
                        // Verificar se já existe (evitar duplicatas)
                        const exists = this.notifications.find(n => n.id === data.id);
                        if (!exists) {
                            // Adicionar no início da lista
                            this.notifications.unshift(newNotification);
                            
                            // Limitar a 10 notificações
                            if (this.notifications.length > 10) {
                                this.notifications = this.notifications.slice(0, 10);
                            }
                        }
                    },
                    getTimeAgo(dateString) {
                        if (!dateString) return 'Agora';
                        const date = new Date(dateString);
                        const now = new Date();
                        const diffInSeconds = Math.floor((now - date) / 1000);
                        
                        if (diffInSeconds < 60) return 'Agora';
                        if (diffInSeconds < 3600) return `${Math.floor(diffInSeconds / 60)} min atrás`;
                        if (diffInSeconds < 86400) return `${Math.floor(diffInSeconds / 3600)} h atrás`;
                        return `${Math.floor(diffInSeconds / 86400)} dia${Math.floor(diffInSeconds / 86400) > 1 ? 's' : ''} atrás`;
                    },
                    async loadNotifications() {
                        this.loading = true;
                        try {
                            const response = await fetch('{{ route('notifications.recent') }}');
                            const data = await response.json();
                            this.notifications = data.notifications || [];
                            this.unreadCount = data.unread_count || 0;
                        } catch (error) {
                            console.error('Erro ao carregar notificações:', error);
                        } finally {
                            this.loading = false;
                        }
                    },
                    async loadUnreadCount() {
                        try {
                            const response = await fetch('{{ route('notifications.unread') }}');
                            const data = await response.json();
                            this.unreadCount = data.count || 0;
                        } catch (error) {
                            console.error('Erro ao carregar contador:', error);
                        }
                    },
                    handleNotificationClick(notification) {
                        // Tocar som quando clica na notificação (se não estiver lida)
                        if (!notification.read_at && this.soundEnabled) {
                            this.playNotificationSound();
                        }
                        
                        // Marcar como lida
                        this.markAsRead(notification.id);
                        
                        // Redirecionar se tiver URL
                        if (notification.data && notification.data.url) {
                            window.location.href = notification.data.url;
                        }
                    },
                    async markAsRead(notificationId) {
                        try {
                            const response = await fetch(`/notifications/${notificationId}/read`, {
                                method: 'POST',
                                headers: {
                                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')?.content || '',
                                    'Content-Type': 'application/json',
                                },
                            });
                            const data = await response.json();
                            if (data.success) {
                                this.unreadCount = data.unread_count || 0;
                                const notification = this.notifications.find(n => n.id === notificationId);
                                if (notification) {
                                    notification.read_at = new Date().toISOString();
                                }
                            }
                        } catch (error) {
                            console.error('Erro ao marcar como lida:', error);
                        }
                    },
                    async markAllAsRead() {
                        try {
                            const response = await fetch('{{ route('notifications.read-all') }}', {
                                method: 'POST',
                                headers: {
                                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')?.content || '',
                                    'Content-Type': 'application/json',
                                },
                            });
                            const data = await response.json();
                            if (data.success) {
                                this.unreadCount = 0;
                                this.notifications.forEach(n => n.read_at = new Date().toISOString());
                            }
                        } catch (error) {
                            console.error('Erro ao marcar todas como lidas:', error);
                        }
                    },
                    getNotificationIcon(type) {
                        const icons = {
                            'equipment_loan': 'bi-tools',
                            'material_request': 'bi-clipboard-check',
                            'budget_approval': 'bi-check-circle',
                            'budget_sent': 'bi-envelope',
                            'budget_approved_by_client': 'bi-check-circle-fill',
                            'budget_rejected_by_client': 'bi-x-circle-fill',
                            'budget_contested': 'bi-exclamation-triangle-fill',
                            'proposal_approval': 'bi-file-earmark-text',
                            'attendance': 'fi fi-rr-calendar-clock',
                            'test': 'bi-bell-fill',
                        };
                        return icons[type] || 'bi-bell';
                    },
                    getNotificationColor(type) {
                        const colors = {
                            'equipment_loan': 'text-blue-600 dark:text-blue-400',
                            'material_request': 'text-green-600 dark:text-green-400',
                            'budget_approval': 'text-green-600 dark:text-green-400',
                            'budget_sent': 'text-indigo-600 dark:text-indigo-400',
                            'budget_approved_by_client': 'text-green-600 dark:text-green-400',
                            'budget_rejected_by_client': 'text-red-600 dark:text-red-400',
                            'budget_contested': 'text-yellow-600 dark:text-yellow-400',
                            'proposal_approval': 'text-purple-600 dark:text-purple-400',
                            'attendance': 'text-gray-600 dark:text-gray-400',
                            'test': 'text-indigo-600 dark:text-indigo-400',
                        };
                        return colors[type] || 'text-indigo-600 dark:text-indigo-400';
                    },
                    getNotificationBadge(type) {
                        const badges = {
                            'budget_approved_by_client': 'bg-green-100 dark:bg-green-900/30 text-green-800 dark:text-green-300',
                            'budget_rejected_by_client': 'bg-red-100 dark:bg-red-900/30 text-red-800 dark:text-red-300',
                            'budget_contested': 'bg-yellow-100 dark:bg-yellow-900/30 text-yellow-800 dark:text-yellow-300',
                            'budget_sent': 'bg-blue-100 dark:bg-blue-900/30 text-blue-800 dark:text-blue-300',
                        };
                        return badges[type] || '';
                    },
                    async sendTestNotification() {
                        if (this.sendingTest) return;
                        
                        this.sendingTest = true;
                        try {
                            const csrfToken = document.querySelector('meta[name="csrf-token"]')?.content;
                            if (!csrfToken) {
                                console.error('CSRF token não encontrado');
                                alert('Erro: Token CSRF não encontrado');
                                this.sendingTest = false;
                                return;
                            }

                            const response = await fetch('{{ route('notifications.test') }}', {
                                method: 'POST',
                                headers: {
                                    'X-CSRF-TOKEN': csrfToken,
                                    'Content-Type': 'application/json',
                                    'Accept': 'application/json',
                                },
                            });

                            if (!response.ok) {
                                const errorData = await response.json().catch(() => ({ message: 'Erro desconhecido' }));
                                console.error('Erro na resposta:', response.status, errorData);
                                alert('Erro ao enviar notificação: ' + (errorData.message || response.status));
                                this.sendingTest = false;
                                return;
                            }

                            const data = await response.json();
                            console.log('Notificação de teste enviada:', data);
                            
                            if (data.success) {
                                // Recarregar notificações imediatamente e depois novamente após delay
                                this.loadNotifications();
                                this.loadUnreadCount();
                                
                                // Recarregar novamente após delay para pegar via WebSocket
                                setTimeout(() => {
                                    this.loadNotifications();
                                    this.loadUnreadCount();
                                }, 1000);
                            }
                        } catch (error) {
                            console.error('Erro ao enviar notificação de teste:', error);
                            alert('Erro ao enviar notificação: ' + error.message);
                        } finally {
                            this.sendingTest = false;
                        }
                    }
                };
            }
        </script>
        <!-- Employee Functions - Definir antes do Livewire -->
        @stack('employee-functions')
        
        @livewireScripts
        <script src="https://unpkg.com/imask"></script>
        <script src="{{ asset('js/pwa.js') }}"></script>
        
        <!-- Offcanvas Functions - Definir globalmente -->
        <script>
            window.openOffcanvas = function(id) {
                const offcanvas = document.getElementById(id);
                const backdrop = document.getElementById(id + '-backdrop');
                
                if (offcanvas && backdrop) {
                    backdrop.style.display = 'block';
                    offcanvas.style.display = 'block';
                    
                    // Trigger reflow
                    offcanvas.offsetHeight;
                    backdrop.offsetHeight;
                    
                    // Add classes for animation (right side)
                    setTimeout(() => {
                        offcanvas.classList.remove('translate-x-full');
                        offcanvas.classList.add('translate-x-0');
                        backdrop.classList.remove('opacity-0', 'pointer-events-none');
                        backdrop.classList.add('opacity-100');
                    }, 10);
                    
                    // Prevent body scroll
                    document.body.style.overflow = 'hidden';
                } else {
                    console.error('Offcanvas ou backdrop não encontrado:', id);
                }
            };

            window.closeOffcanvas = function(id) {
                const offcanvas = document.getElementById(id);
                const backdrop = document.getElementById(id + '-backdrop');
                
                if (offcanvas && backdrop) {
                    // Remove classes for animation (right side)
                    offcanvas.classList.remove('translate-x-0');
                    offcanvas.classList.add('translate-x-full');
                    backdrop.classList.remove('opacity-100');
                    backdrop.classList.add('opacity-0', 'pointer-events-none');
                    
                    // Hide after animation
                    setTimeout(() => {
                        offcanvas.style.display = 'none';
                        backdrop.style.display = 'none';
                        document.body.style.overflow = '';
                    }, 300);
                }
            };

            // Close on Escape key
            document.addEventListener('keydown', function(e) {
                if (e.key === 'Escape') {
                    const openOffcanvas = document.querySelector('[id$="-offcanvas"]:not(.translate-x-full)');
                    if (openOffcanvas && openOffcanvas.classList.contains('translate-x-0')) {
                        const id = openOffcanvas.id;
                        window.closeOffcanvas(id);
                    }
                }
            });
        </script>
        
        @stack('scripts')
        
        <!-- Modal de Upload de Arquivos (Global) -->
        <div id="file-upload-modal-container" 
             x-data="{ openFileModal: false }" 
             x-init="
                 window.addEventListener('open-file-upload-modal', () => { 
                     openFileModal = true; 
                     document.body.style.overflow = 'hidden';
                 });
                 window.addEventListener('close-file-upload-modal', () => { 
                     openFileModal = false; 
                     document.body.style.overflow = '';
                 });
                 $watch('openFileModal', value => {
                     if (!value) {
                         document.body.style.overflow = '';
                     }
                 });
             "
             x-show="openFileModal"
             x-cloak
             x-transition:enter="transition ease-out duration-300"
             x-transition:enter-start="opacity-0"
             x-transition:enter-end="opacity-100"
             x-transition:leave="transition ease-in duration-200"
             x-transition:leave-start="opacity-100"
             x-transition:leave-end="opacity-0"
             class="fixed inset-0 z-50 flex items-center justify-center"
             style="background-color: rgba(0, 0, 0, 0.75); backdrop-filter: blur(4px); -webkit-backdrop-filter: blur(4px);"
             @click.self="openFileModal = false"
             @keydown.escape.window="openFileModal = false">
            <div x-show="openFileModal"
                 x-transition:enter="transition ease-out duration-300"
                 x-transition:enter-start="opacity-0 scale-95"
                 x-transition:enter-end="opacity-100 scale-100"
                 x-transition:leave="transition ease-in duration-200"
                 x-transition:leave-start="opacity-100 scale-100"
                 x-transition:leave-end="opacity-0 scale-95"
                 class="relative bg-white dark:bg-gray-800 rounded-lg shadow-2xl p-6 w-full max-w-2xl mx-4 border border-gray-200 dark:border-gray-700"
                 style="max-height: calc(100vh - 3rem); overflow-y: auto;"
                 @click.stop>
                <div class="flex items-center justify-between mb-4">
                    <h3 class="font-medium text-gray-900 dark:text-gray-100 text-lg">Enviar Arquivos</h3>
                    <button @click="openFileModal = false" class="text-gray-400 dark:text-gray-500 hover:text-gray-600 dark:hover:text-gray-300 transition-colors p-1 rounded-full hover:bg-gray-100 dark:hover:bg-gray-700 focus:outline-none">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                        </svg>
                    </button>
                </div>
                <div id="file-upload-modal-content">
                    <!-- Conteúdo será injetado via JavaScript -->
                </div>
            </div>
        </div>
        
        <!-- Script para funções auxiliares de pesquisa -->
        <script>
            function getTypeIcon(type) {
                const icons = {
                    'product': 'fi fi-rr-box',
                    'equipment': 'fi fi-rr-tools',
                    'client': 'fi fi-rr-user',
                    'employee': 'fi fi-rr-user',
                    'project': 'fi fi-rr-folder',
                    'document': 'fi fi-rr-file-pdf'
                };
                return icons[type] || 'fi fi-rr-file';
            }
            
            function getTypeLabel(type) {
                const labels = {
                    'product': 'Produto',
                    'equipment': 'Equipamento',
                    'client': 'Cliente',
                    'employee': 'Funcionário',
                    'project': 'Projeto',
                    'document': 'Documento'
                };
                return labels[type] || 'Item';
            }
            
            // Tornar funções globais para uso no Alpine
            window.getTypeIcon = getTypeIcon;
            window.getTypeLabel = getTypeLabel;
        </script>
        
        <!-- Script para controlar scrollbar do sidebar -->
        <script>
            (function() {
                const sidebarContainer = document.getElementById('sidebar-container');
                const sidebarNav = document.getElementById('sidebar-nav');
                
                if (!sidebarContainer || !sidebarNav) return;
                
                // Função para mostrar scrollbar
                function showScrollbar() {
                    sidebarNav.classList.add('sidebar-scroll-visible');
                }
                
                // Função para esconder scrollbar
                function hideScrollbar() {
                    sidebarNav.classList.remove('sidebar-scroll-visible');
                }
                
                // Event listeners para hover
                sidebarContainer.addEventListener('mouseenter', showScrollbar);
                sidebarContainer.addEventListener('mouseleave', hideScrollbar);
                
                // Também controlar quando o mouse está sobre o nav diretamente
                sidebarNav.addEventListener('mouseenter', showScrollbar);
                sidebarNav.addEventListener('mouseleave', function(e) {
                    // Só esconder se o mouse não estiver mais no sidebar
                    if (!sidebarContainer.contains(e.relatedTarget)) {
                        hideScrollbar();
                    }
                });
            })();
        </script>
        
        <!-- Script para controlar scrollbar do layout principal -->
        <script>
            (function() {
                let scrollTimeout;
                let hoverTimeout;
                const body = document.body;
                const html = document.documentElement;
                
                // Função para verificar se há scroll
                function hasScroll() {
                    return html.scrollHeight > html.clientHeight || body.scrollHeight > body.clientHeight;
                }
                
                // Função para mostrar scrollbar
                function showLayoutScrollbar() {
                    if (!hasScroll()) return;
                    clearTimeout(hoverTimeout);
                    body.classList.add('layout-scroll-visible');
                    html.classList.add('layout-scroll-visible');
                }
                
                // Função para esconder scrollbar
                function hideLayoutScrollbar() {
                    hoverTimeout = setTimeout(() => {
                        body.classList.remove('layout-scroll-visible');
                        html.classList.remove('layout-scroll-visible');
                    }, 500); // Delay para suavizar a transição
                }
                
                // Mostrar scrollbar quando hover no body/html
                body.addEventListener('mouseenter', showLayoutScrollbar);
                body.addEventListener('mouseleave', hideLayoutScrollbar);
                html.addEventListener('mouseenter', showLayoutScrollbar);
                html.addEventListener('mouseleave', hideLayoutScrollbar);
                
                // Mostrar scrollbar durante o scroll
                let isScrolling = false;
                function handleScroll() {
                    if (!hasScroll()) return;
                    
                    if (!isScrolling) {
                        showLayoutScrollbar();
                        isScrolling = true;
                    }
                    clearTimeout(scrollTimeout);
                    scrollTimeout = setTimeout(() => {
                        isScrolling = false;
                        if (!body.matches(':hover') && !html.matches(':hover')) {
                            hideLayoutScrollbar();
                        }
                    }, 1000);
                }
                
                // Escutar scroll no window
                window.addEventListener('scroll', handleScroll, true);
                
                // Verificar scroll ao redimensionar
                window.addEventListener('resize', function() {
                    if (!hasScroll()) {
                        body.classList.remove('layout-scroll-visible');
                        html.classList.remove('layout-scroll-visible');
                    }
                });
            })();
        </script>
    </body>
</html>
