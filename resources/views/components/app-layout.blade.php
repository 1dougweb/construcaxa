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

        <!-- Scripts -->
        @vite(['resources/css/app.css', 'resources/js/app.js', 'resources/js/notifications.js'])

        <!-- Icons -->
        <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">

        <!-- Styles -->
        @livewireStyles
        <style>
            /* Scrollbar customizada para o sidebar */
            .sidebar-scroll::-webkit-scrollbar {
                width: 6px;
            }
            
            .sidebar-scroll::-webkit-scrollbar-track {
                background: transparent;
            }
            
            .sidebar-scroll::-webkit-scrollbar-thumb {
                background: #cbd5e1;
                border-radius: 3px;
            }
            
            .sidebar-scroll::-webkit-scrollbar-thumb:hover {
                background: #94a3b8;
            }
            
            /* Para Firefox */
            .sidebar-scroll {
                scrollbar-width: thin;
                scrollbar-color: #cbd5e1 transparent;
            }
            
            /* x-cloak para Alpine.js */
            [x-cloak] {
                display: none !important;
            }
            
            /* Transições suaves para dropdowns */
            .dropdown-chevron {
                transition: transform 0.2s ease-in-out;
            }
        </style>
    </head>
    <body class="font-sans antialiased">
        <!-- Notification container for dynamic notifications -->
        <div id="notifications-container" class="fixed top-0 right-0 m-6 space-y-3" style="z-index: 9999; pointer-events: none;"></div>

        <div class="min-h-screen bg-gray-100" x-data="{ sidebarOpen: false }">
            <!-- Mobile top bar -->
            <div class="h-16 bg-white border-b border-gray-200 px-4 flex items-center justify-between lg:hidden">
                <button @click="sidebarOpen = true" class="p-2 rounded-md text-gray-600 hover:bg-gray-100" aria-label="Open menu">
                    <svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"/></svg>
                </button>
                <a href="{{ route('dashboard') }}" class="flex items-center gap-2">
                    <img src="{{ asset('assets/images/logo.svg') }}" alt="Logo" class="h-8 w-auto" />
                </a>
                <div class="w-6"></div>
            </div>

            <!-- Sidebar -->
            <aside class="fixed inset-y-0 left-0 w-64 bg-white border-r border-gray-200 z-40 transform transition-transform duration-200 ease-in-out -translate-x-full lg:translate-x-0 flex flex-col" :class="{ '-translate-x-full': !sidebarOpen, 'translate-x-0': sidebarOpen }" @keydown.window.escape="sidebarOpen = false">
                <div class="h-16 px-6 flex items-center border-b border-gray-200 justify-center flex-shrink-0">
                    <a href="{{ route('dashboard') }}" class="flex items-center gap-3">
                        <img src="{{ asset('assets/images/logo.svg') }}" alt="Logo" class="h-12 w-auto m-4" />
                    </a>
                </div>
                <nav class="flex-1 overflow-y-auto sidebar-scroll p-4 space-y-1" x-data="{ 
                    estoqueOpen: {{ request()->routeIs('products.*') || request()->routeIs('equipment.*') || request()->routeIs('material-requests.*') || request()->routeIs('equipment-requests.*') || request()->routeIs('suppliers.*') ? 'true' : 'false' }},
                    gestaoOpen: {{ request()->routeIs('employees.*') || request()->routeIs('attendance.manage') || request()->routeIs('budgets.*') || (request()->routeIs('projects.*') && !request()->routeIs('client.projects.*')) || request()->routeIs('services.*') || request()->routeIs('labor-types.*') || request()->routeIs('service-categories.*') || request()->routeIs('map.*') || request()->routeIs('clients.*') || request()->routeIs('contracts.*') ? 'true' : 'false' }},
                    financeiroOpen: {{ request()->routeIs('financial.*') ? 'true' : 'false' }},
                    adminOpen: {{ request()->routeIs('admin.permissions.*') ? 'true' : 'false' }}
                }">
                    <!-- Dashboard sempre visível -->
                    <a href="{{ route('dashboard') }}" class="flex items-center px-3 py-2 rounded-md text-sm font-medium {{ request()->routeIs('dashboard') ? 'bg-indigo-50 text-indigo-700' : 'text-gray-700 hover:bg-gray-50' }}">
                        <i class="bi bi-speedometer2 mr-3 text-base"></i>
                        {{ __('Dashboard') }}
                    </a>

                    <!-- Dropdown Estoque -->
                    @if(auth()->user()->can('view products') || auth()->user()->can('view service-orders') || auth()->user()->can('view suppliers') || auth()->user()->hasAnyRole(['admin', 'manager']))
                    <div>
                        <button @click="estoqueOpen = !estoqueOpen" class="w-full flex items-center justify-between px-3 py-2 rounded-md text-sm font-medium text-gray-700 hover:bg-gray-50 transition-colors">
                            <div class="flex items-center">
                                <i class="bi bi-boxes mr-3 text-base"></i>
                                <span>{{ __('Estoque') }}</span>
                            </div>
                            <i class="bi bi-chevron-down text-xs transition-transform duration-200" :class="{ 'rotate-180': estoqueOpen }"></i>
                        </button>
                        <div x-show="estoqueOpen" 
                             x-transition:enter="transition ease-out duration-200"
                             x-transition:enter-start="opacity-0 transform -translate-y-2"
                             x-transition:enter-end="opacity-100 transform translate-y-0"
                             x-transition:leave="transition ease-in duration-150"
                             x-transition:leave-start="opacity-100 transform translate-y-0"
                             x-transition:leave-end="opacity-0 transform -translate-y-2"
                             class="ml-3 mt-0.5 mb-1 space-y-0.5 border-l-2 border-gray-200 pl-3">
                            @can('view products')
                            <a href="{{ route('products.index') }}" class="flex items-center px-3 py-2 rounded-md text-sm font-medium {{ request()->routeIs('products.*') ? 'bg-indigo-50 text-indigo-700 -ml-3 pl-5' : 'text-gray-600 hover:bg-gray-50' }}">
                                <i class="bi bi-box-seam mr-3 text-base"></i>
                                {{ __('Produtos') }}
                            </a>
                            @endcan
                            @can('view products')
                            <a href="{{ route('equipment.index') }}" class="flex items-center px-3 py-2 rounded-md text-sm font-medium {{ request()->routeIs('equipment.*') ? 'bg-indigo-50 text-indigo-700 -ml-3 pl-5' : 'text-gray-600 hover:bg-gray-50' }}">
                                <i class="bi bi-tools mr-3 text-base"></i>
                                {{ __('Equipamentos') }}
                            </a>
                            @endcan
                            @can('view service-orders')
                            <a href="{{ route('material-requests.index') }}" class="flex items-center px-3 py-2 rounded-md text-sm font-medium {{ request()->routeIs('material-requests.*') ? 'bg-indigo-50 text-indigo-700 -ml-3 pl-5' : 'text-gray-600 hover:bg-gray-50' }}">
                                <i class="bi bi-clipboard-check mr-3 text-base"></i>
                                {{ __('Requisições de Material') }}
                            </a>
                            @endcan
                            @can('view service-orders')
                            <a href="{{ route('equipment-requests.index') }}" class="flex items-center px-3 py-2 rounded-md text-sm font-medium {{ request()->routeIs('equipment-requests.*') ? 'bg-indigo-50 text-indigo-700 -ml-3 pl-5' : 'text-gray-600 hover:bg-gray-50' }}">
                                <i class="bi bi-wrench-adjustable mr-3 text-base"></i>
                                {{ __('Requisições de Equipamento') }}
                            </a>
                            @endcan
                            @can('view suppliers')
                            <a href="{{ route('suppliers.index') }}" class="flex items-center px-3 py-2 rounded-md text-sm font-medium {{ request()->routeIs('suppliers.*') ? 'bg-indigo-50 text-indigo-700 -ml-3 pl-5' : 'text-gray-600 hover:bg-gray-50' }}">
                                <i class="bi bi-truck mr-3 text-base"></i>
                                {{ __('Fornecedores') }}
                            </a>
                            @endcan
                        </div>
                    </div>
                    @endif

                    <!-- Dropdown Gestão -->
                    @if(auth()->user()->can('view employees') || auth()->user()->can('manage attendance') || auth()->user()->can('view budgets') || auth()->user()->can('view projects') || auth()->user()->can('manage services') || auth()->user()->can('view clients') || auth()->user()->hasAnyRole(['admin', 'manager']))
                    <div>
                        <button @click="gestaoOpen = !gestaoOpen" class="w-full flex items-center justify-between px-3 py-2 rounded-md text-sm font-medium text-gray-700 hover:bg-gray-50 transition-colors">
                            <div class="flex items-center">
                                <i class="bi bi-gear mr-3 text-base"></i>
                                <span>{{ __('Gestão') }}</span>
                            </div>
                            <i class="bi bi-chevron-down text-xs transition-transform duration-200" :class="{ 'rotate-180': gestaoOpen }"></i>
                        </button>
                        <div x-show="gestaoOpen" 
                             x-transition:enter="transition ease-out duration-200"
                             x-transition:enter-start="opacity-0 transform -translate-y-2"
                             x-transition:enter-end="opacity-100 transform translate-y-0"
                             x-transition:leave="transition ease-in duration-150"
                             x-transition:leave-start="opacity-100 transform translate-y-0"
                             x-transition:leave-end="opacity-0 transform -translate-y-2"
                             class="ml-3 mt-0.5 mb-1 space-y-0.5 border-l-2 border-gray-200 pl-3">
                            @php
                                $user = auth()->user();
                                // Mesma lógica das rotas: role_or_permission:manager|admin|view clients
                                $showClients = $user->hasAnyRole(['admin', 'manager']) || $user->can('view clients');
                                // Mesma lógica das rotas: role_or_permission:manager|admin|view contracts
                                $showContracts = $user->hasAnyRole(['admin', 'manager']) || $user->can('view contracts');
                            @endphp
                            @if($showClients)
                            <a href="{{ route('clients.index') }}" 
                               class="flex items-center px-3 py-2 rounded-md text-sm font-medium {{ request()->routeIs('clients.*') ? 'bg-indigo-50 text-indigo-700 -ml-3 pl-5' : 'text-gray-600 hover:bg-gray-50' }}">
                                <i class="bi bi-person-badge mr-3 text-base"></i>
                                {{ __('Clientes') }}
                            </a>
                            @endif
                            @if($showContracts)
                            <a href="{{ route('contracts.index') }}" 
                               class="flex items-center px-3 py-2 rounded-md text-sm font-medium {{ request()->routeIs('contracts.*') ? 'bg-indigo-50 text-indigo-700 -ml-3 pl-5' : 'text-gray-600 hover:bg-gray-50' }}">
                                <i class="bi bi-file-earmark-text mr-3 text-base"></i>
                                {{ __('Contratos') }}
                            </a>
                            @endif
                            @can('view employees')
                            <a href="{{ route('employees.index') }}" class="flex items-center px-3 py-2 rounded-md text-sm font-medium {{ request()->routeIs('employees.*') ? 'bg-indigo-50 text-indigo-700 -ml-3 pl-5' : 'text-gray-600 hover:bg-gray-50' }}">
                                <i class="bi bi-people mr-3 text-base"></i>
                                {{ __('Funcionários') }}
                            </a>
                            @endcan
                            @can('manage attendance')
                            <a href="{{ route('attendance.manage') }}" class="flex items-center px-3 py-2 rounded-md text-sm font-medium {{ request()->routeIs('attendance.manage') ? 'bg-indigo-50 text-indigo-700 -ml-3 pl-5' : 'text-gray-600 hover:bg-gray-50' }}">
                                <i class="bi bi-calendar-check mr-3 text-base"></i>
                                {{ __('Gestão de Pontos') }}
                            </a>
                            @endcan
                            @can('view budgets')
                            <a href="{{ route('budgets.index') }}" class="flex items-center px-3 py-2 rounded-md text-sm font-medium {{ request()->routeIs('budgets.*') ? 'bg-indigo-50 text-indigo-700 -ml-3 pl-5' : 'text-gray-600 hover:bg-gray-50' }}">
                                <i class="bi bi-receipt mr-3 text-base"></i>
                                {{ __('Orçamentos') }}
                            </a>
                            @endcan
                            @can('view projects')
                            <a href="{{ route('projects.index') }}" class="flex items-center px-3 py-2 rounded-md text-sm font-medium {{ request()->routeIs('projects.*') && !request()->routeIs('client.projects.*') ? 'bg-indigo-50 text-indigo-700 -ml-3 pl-5' : 'text-gray-600 hover:bg-gray-50' }}">
                                <i class="bi bi-building mr-3 text-base"></i>
                                {{ __('Obras') }}
                            </a>
                            @endcan
                            @can('view projects')
                            <a href="{{ route('map.index') }}" class="flex items-center px-3 py-2 rounded-md text-sm font-medium {{ request()->routeIs('map.*') ? 'bg-indigo-50 text-indigo-700 -ml-3 pl-5' : 'text-gray-600 hover:bg-gray-50' }}">
                                <i class="bi bi-geo-alt mr-3 text-base"></i>
                                {{ __('Mapa') }}
                            </a>
                            @endcan
                            @can('manage services')
                            <a href="{{ route('services.index') }}" class="flex items-center px-3 py-2 rounded-md text-sm font-medium {{ request()->routeIs('services.*') ? 'bg-indigo-50 text-indigo-700 -ml-3 pl-5' : 'text-gray-600 hover:bg-gray-50' }}">
                                <i class="bi bi-tools mr-3 text-base"></i>
                                {{ __('Serviços') }}
                            </a>
                            @endcan
                            @can('manage services')
                            <a href="{{ route('labor-types.index') }}" class="flex items-center px-3 py-2 rounded-md text-sm font-medium {{ request()->routeIs('labor-types.*') ? 'bg-indigo-50 text-indigo-700 -ml-3 pl-5' : 'text-gray-600 hover:bg-gray-50' }}">
                                <i class="bi bi-people mr-3 text-base"></i>
                                {{ __('Tipos de Mão de Obra') }}
                            </a>
                            @endcan
                            @can('manage services')
                            <a href="{{ route('service-categories.index') }}" class="flex items-center px-3 py-2 rounded-md text-sm font-medium {{ request()->routeIs('service-categories.*') ? 'bg-indigo-50 text-indigo-700 -ml-3 pl-5' : 'text-gray-600 hover:bg-gray-50' }}">
                                <i class="bi bi-folder mr-3 text-base"></i>
                                {{ __('Categorias de Serviços') }}
                            </a>
                            @endcan
                        </div>
                    </div>
                    @endif

                    @can('view client-projects')
                    <a href="{{ route('client.dashboard') }}" class="flex items-center px-3 py-2 rounded-md text-sm font-medium {{ request()->routeIs('client.dashboard') ? 'bg-indigo-50 text-indigo-700' : 'text-gray-700 hover:bg-gray-50' }}">
                        <i class="bi bi-speedometer2 mr-3 text-base"></i>
                        {{ __('Dashboard') }}
                    </a>
                    <a href="{{ route('client.projects.index') }}" class="flex items-center px-3 py-2 rounded-md text-sm font-medium {{ request()->routeIs('client.projects.*') && !request()->routeIs('client.dashboard') ? 'bg-indigo-50 text-indigo-700' : 'text-gray-700 hover:bg-gray-50' }}">
                        <i class="bi bi-house-door mr-3 text-base"></i>
                        {{ __('Minhas Obras') }}
                    </a>
                    @endcan
                    @can('view reports')
                    <a href="{{ route('reports.index') }}" class="flex items-center px-3 py-2 rounded-md text-sm font-medium {{ request()->routeIs('reports.*') ? 'bg-indigo-50 text-indigo-700' : 'text-gray-700 hover:bg-gray-50' }}">
                        <i class="bi bi-graph-up mr-3 text-base"></i>
                        {{ __('Relatórios') }}
                    </a>
                    @endcan

                    <!-- Dropdown Financeiro -->
                    @can('manage finances')
                    <div>
                        <button @click="financeiroOpen = !financeiroOpen" class="w-full flex items-center justify-between px-3 py-2 rounded-md text-sm font-medium text-gray-700 hover:bg-gray-50 transition-colors">
                            <div class="flex items-center">
                                <i class="bi bi-cash-coin mr-3 text-base"></i>
                                <span>{{ __('Financeiro') }}</span>
                            </div>
                            <i class="bi bi-chevron-down text-xs transition-transform duration-200" :class="{ 'rotate-180': financeiroOpen }"></i>
                        </button>
                        <div x-show="financeiroOpen" 
                             x-transition:enter="transition ease-out duration-200"
                             x-transition:enter-start="opacity-0 transform -translate-y-2"
                             x-transition:enter-end="opacity-100 transform translate-y-0"
                             x-transition:leave="transition ease-in duration-150"
                             x-transition:leave-start="opacity-100 transform translate-y-0"
                             x-transition:leave-end="opacity-0 transform -translate-y-2"
                             class="ml-3 mt-0.5 mb-1 space-y-0.5 border-l-2 border-gray-200 pl-3">
                            <a href="{{ route('financial.dashboard') }}" class="flex items-center px-3 py-2 rounded-md text-sm font-medium {{ request()->routeIs('financial.dashboard') ? 'bg-indigo-50 text-indigo-700 border-l-2 border-indigo-500 -ml-3 pl-5' : 'text-gray-600 hover:bg-gray-50' }}">
                                <i class="bi bi-speedometer2 mr-3 text-base"></i>
                                {{ __('Dashboard Financeiro') }}
                            </a>
                            <a href="{{ route('financial.accounts-payable.index') }}" class="flex items-center px-3 py-2 rounded-md text-sm font-medium {{ request()->routeIs('financial.accounts-payable.*') ? 'bg-indigo-50 text-indigo-700 border-l-2 border-indigo-500 -ml-3 pl-5' : 'text-gray-600 hover:bg-gray-50' }}">
                                <i class="bi bi-arrow-down-circle mr-3 text-base"></i>
                                {{ __('Contas a Pagar') }}
                            </a>
                            <a href="{{ route('financial.accounts-receivable.index') }}" class="flex items-center px-3 py-2 rounded-md text-sm font-medium {{ request()->routeIs('financial.accounts-receivable.*') ? 'bg-indigo-50 text-indigo-700 border-l-2 border-indigo-500 -ml-3 pl-5' : 'text-gray-600 hover:bg-gray-50' }}">
                                <i class="bi bi-arrow-up-circle mr-3 text-base"></i>
                                {{ __('Contas a Receber') }}
                            </a>
                            <a href="{{ route('financial.invoices.index') }}" class="flex items-center px-3 py-2 rounded-md text-sm font-medium {{ request()->routeIs('financial.invoices.*') ? 'bg-indigo-50 text-indigo-700 border-l-2 border-indigo-500 -ml-3 pl-5' : 'text-gray-600 hover:bg-gray-50' }}">
                                <i class="bi bi-receipt-cutoff mr-3 text-base"></i>
                                {{ __('Notas Fiscais') }}
                            </a>
                            <a href="{{ route('financial.receipts.index') }}" class="flex items-center px-3 py-2 rounded-md text-sm font-medium {{ request()->routeIs('financial.receipts.*') ? 'bg-indigo-50 text-indigo-700 border-l-2 border-indigo-500 -ml-3 pl-5' : 'text-gray-600 hover:bg-gray-50' }}">
                                <i class="bi bi-receipt mr-3 text-base"></i>
                                {{ __('Recibos') }}
                            </a>
                        </div>
                    </div>
                    @endcan

                    @can('view attendance')
                    <a href="{{ route('attendance.index') }}" class="flex items-center px-3 py-2 rounded-md text-sm font-medium {{ request()->routeIs('attendance.index') ? 'bg-indigo-50 text-indigo-700' : 'text-gray-700 hover:bg-gray-50' }}">
                        <i class="bi bi-geo-alt mr-3 text-base"></i>
                        {{ __('Bater Ponto') }}
                    </a>
                    @endcan

                    <!-- Dropdown Administração -->
                    @can('manage permissions')
                    <div>
                        <button @click="adminOpen = !adminOpen" class="w-full flex items-center justify-between px-3 py-2 rounded-md text-sm font-medium text-gray-700 hover:bg-gray-50 transition-colors">
                            <div class="flex items-center">
                                <i class="bi bi-shield-check mr-3 text-base"></i>
                                <span>{{ __('Administração') }}</span>
                            </div>
                            <i class="bi bi-chevron-down text-xs transition-transform duration-200" :class="{ 'rotate-180': adminOpen }"></i>
                        </button>
                        <div x-show="adminOpen" 
                             x-transition:enter="transition ease-out duration-200"
                             x-transition:enter-start="opacity-0 transform -translate-y-2"
                             x-transition:enter-end="opacity-100 transform translate-y-0"
                             x-transition:leave="transition ease-in duration-150"
                             x-transition:leave-start="opacity-100 transform translate-y-0"
                             x-transition:leave-end="opacity-0 transform -translate-y-2"
                             class="ml-3 mt-0.5 mb-1 space-y-0.5 border-l-2 border-gray-200 pl-3">
                            <a href="{{ route('admin.permissions.users') }}" class="flex items-center px-3 py-2 rounded-md text-sm font-medium {{ request()->routeIs('admin.permissions.users') ? 'bg-indigo-50 text-indigo-700 -ml-3 pl-5' : 'text-gray-600 hover:bg-gray-50' }}">
                                <i class="bi bi-person-check mr-3 text-base"></i>
                                {{ __('Permissões: Usuários') }}
                            </a>
                            <a href="{{ route('admin.permissions.roles') }}" class="flex items-center px-3 py-2 rounded-md text-sm font-medium {{ request()->routeIs('admin.permissions.roles') ? 'bg-indigo-50 text-indigo-700 -ml-3 pl-5' : 'text-gray-600 hover:bg-gray-50' }}">
                                <i class="bi bi-shield-lock mr-3 text-base"></i>
                                {{ __('Permissões: Papéis') }}
                            </a>
                        </div>
                    </div>
                    @endcan
                </nav>
                <div class="flex-shrink-0 w-full border-t border-gray-200 bg-white">
                    <div class="p-4">
                        <div class="flex items-center gap-3 mb-3">
                            <div class="flex-shrink-0">
                                @if(Auth::user()->profile_photo)
                                    <img src="{{ asset('storage/' . Auth::user()->profile_photo) }}" alt="{{ Auth::user()->name }}" class="h-10 w-10 rounded-full object-cover border-2 border-gray-200">
                                @else
                                    <div class="h-10 w-10 rounded-full bg-indigo-500 flex items-center justify-center text-white font-semibold text-sm border-2 border-gray-200">
                                        {{ strtoupper(substr(Auth::user()->name, 0, 1)) }}
                                    </div>
                                @endif
                            </div>
                            <div class="flex-1 min-w-0">
                                <p class="text-sm font-medium text-gray-900 truncate">{{ Auth::user()->name }}</p>
                                <p class="text-xs text-gray-500 truncate">{{ Auth::user()->email }}</p>
                            </div>
                        </div>
                        <div class="flex items-center gap-2">
                            <a href="{{ route('profile.edit') }}" class="flex-1 text-center px-3 py-2 rounded-md text-xs font-medium text-gray-700 hover:bg-gray-50 border border-gray-200 transition-colors">
                                <i class="bi bi-person-gear mr-1"></i>
                                Perfil
                            </a>
                            <form method="POST" action="{{ route('logout') }}" class="flex-1">
                                @csrf
                                <button type="submit" class="w-full px-3 py-2 rounded-md text-xs font-medium text-red-600 hover:bg-red-50 border border-red-200 transition-colors">
                                    <i class="bi bi-box-arrow-right mr-1"></i>
                                    {{ __('Sair') }}
                                </button>
                            </form>
                        </div>
                    </div>
                </div>
            </aside>

            <!-- Overlay for mobile -->
            <div class="fixed inset-0 bg-black/40 z-30 lg:hidden" 
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

            <!-- Content -->
            <div class="lg:ml-64">
                @if (isset($header))
                    <header class="bg-white border-b border-gray-200">
                        <div class="px-4 sm:px-6 lg:px-6 py-4">
                            {{ $header }}
                        </div>
                    </header>
                @endif

                <main class="p-4 sm:p-4">
                    {{ $slot }}
                </main>
            </div>
        </div>

        @livewireScripts
        <script src="https://unpkg.com/imask"></script>
        <script src="{{ asset('js/pwa.js') }}"></script>
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
                 class="relative bg-white rounded-lg shadow-2xl p-6 w-full max-w-2xl mx-4"
                 style="max-height: calc(100vh - 3rem); overflow-y: auto;"
                 @click.stop>
                <div class="flex items-center justify-between mb-4">
                    <h3 class="font-medium text-gray-900 text-lg">Enviar Arquivos</h3>
                    <button @click="openFileModal = false" class="text-gray-400 hover:text-gray-600 transition-colors p-1 rounded-full hover:bg-gray-100 focus:outline-none">
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
    </body>
</html>
