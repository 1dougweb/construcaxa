<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1, viewport-fit=cover">
        <meta name="csrf-token" content="{{ csrf_token() }}">

        <title>{{ config('app.name', 'Laravel') }}</title>

        <!-- PWA Meta Tags -->
        <meta name="theme-color" content="#1E2780">
        <meta name="mobile-web-app-capable" content="yes">
        <meta name="apple-mobile-web-app-capable" content="yes">
        <meta name="apple-mobile-web-app-status-bar-style" content="black-translucent">
        <meta name="apple-mobile-web-app-title" content="{{ config('app.name', 'Stock Master') }}">
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

        <!-- Notificações de sessão flash -->
        @if(session('success'))
            <meta name="notification-success" content="{{ session('success') }}">
            <script>
                document.addEventListener('DOMContentLoaded', function() {
                    if (window.showNotification) {
                        window.showNotification('{{ session('success') }}', 'success');
                    }
                });
            </script>
        @endif
        @if(session('error'))
            <meta name="notification-error" content="{{ session('error') }}">
            <script>
                document.addEventListener('DOMContentLoaded', function() {
                    if (window.showNotification) {
                        window.showNotification('{{ session('error') }}', 'error');
                    }
                });
            </script>
        @endif
        @if(session('info'))
            <meta name="notification-info" content="{{ session('info') }}">
            <script>
                document.addEventListener('DOMContentLoaded', function() {
                    if (window.showNotification) {
                        window.showNotification('{{ session('info') }}', 'info');
                    }
                });
            </script>
        @endif

        <!-- Fonts -->
        <link rel="preconnect" href="https://fonts.bunny.net">
        <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />

        <!-- Scripts -->
        @vite(['resources/css/app.css', 'resources/js/app.js', 'resources/js/notifications.js'])

        <!-- Styles -->
        @livewireStyles
    </head>
    <body class="font-sans antialiased">
        <!-- Notification container for dynamic notifications -->
        <div id="notifications-container" class="fixed top-0 right-0 m-6 z-50 space-y-3"></div>

        <div class="min-h-screen bg-gray-100">
            <!-- Left Sidebar Navigation -->
            <nav class="fixed left-0 top-0 h-full w-64 bg-indigo-800 text-white">
                <div class="p-6">
                    <h1 class="text-2xl font-bold">{{ config('app.name') }}</h1>
                </div>
                <div class="mt-6">
                    <a href="{{ url('/') }}" target="_self" class="block px-6 py-3 hover:bg-indigo-700 {{ request()->routeIs('dashboard') ? 'bg-indigo-700' : '' }}">
                        <i class="fas fa-home mr-2"></i> Dashboard
                    </a>
                    
                    <a href="{{ url('/products') }}" target="_self" class="block px-6 py-3 hover:bg-indigo-700 {{ request()->routeIs('products.*') ? 'bg-indigo-700' : '' }}">
                        <i class="fas fa-box mr-2"></i> Produtos
                    </a>
                    
                    <a href="{{ route('categories.index') }}" target="_self" class="block px-6 py-3 hover:bg-indigo-700 {{ request()->routeIs('categories.*') ? 'bg-indigo-700' : '' }}">
                        <i class="fas fa-tags mr-2"></i> Categorias
                    </a>
                    
                    <a href="{{ url('/material-requests') }}" target="_self" class="block px-6 py-3 hover:bg-indigo-700 {{ request()->routeIs('material-requests.*') ? 'bg-indigo-700' : '' }}">
                        <i class="fas fa-clipboard-list mr-2"></i> Pedidos de Materiais
                    </a>
                    
                    <a href="{{ url('/suppliers') }}" target="_self" class="block px-6 py-3 hover:bg-indigo-700 {{ request()->routeIs('suppliers.*') ? 'bg-indigo-700' : '' }}">
                        <i class="fas fa-truck mr-2"></i> Fornecedores
                    </a>
                    
                    <a href="{{ url('/employees') }}" target="_self" class="block px-6 py-3 hover:bg-indigo-700 {{ request()->routeIs('employees.*') ? 'bg-indigo-700' : '' }}">
                        <i class="fas fa-users mr-2"></i> Funcionários
                    </a>
                    
                    <a href="{{ url('/reports') }}" target="_self" class="block px-6 py-3 hover:bg-indigo-700 {{ request()->routeIs('reports.*') ? 'bg-indigo-700' : '' }}">
                        <i class="fas fa-chart-bar mr-2"></i> Relatórios
                    </a>
                    
                    @hasanyrole('manager|admin')
                    <div class="mt-4 pt-4 border-t border-indigo-700">
                        <a href="{{ route('admin.settings') }}" target="_self" class="block px-6 py-3 hover:bg-indigo-700 {{ request()->routeIs('admin.settings') ? 'bg-indigo-700' : '' }}">
                            <i class="fas fa-cog mr-2"></i> Configurações
                        </a>
                    </div>
                    @endhasanyrole
                </div>
            </nav>

            <!-- Right Side Analytics -->
            <div class="fixed right-0 top-0 h-full w-64 bg-white shadow-lg">
                <div class="p-6">
                    <h2 class="text-lg font-semibold mb-4">Analytics</h2>
                    <div class="space-y-6">
                        <!-- Stock Status -->
                        <div>
                            <h3 class="text-sm font-medium text-gray-500 mb-2">Status do Estoque</h3>
                            <div class="bg-gray-100 rounded p-4">
                                <p class="text-2xl font-bold text-indigo-600">{{ \App\Models\Product::count() }}</p>
                                <p class="text-sm text-gray-600">Produtos Cadastrados</p>
                            </div>
                        </div>

                        <!-- Low Stock Alerts -->
                        <div>
                            <h3 class="text-sm font-medium text-gray-500 mb-2">Alertas</h3>
                            <div class="bg-red-100 rounded p-4">
                                <p class="text-2xl font-bold text-red-600">{{ \App\Models\Product::whereRaw('stock <= min_stock')->count() }}</p>
                                <p class="text-sm text-gray-600">Produtos com Estoque Baixo</p>
                            </div>
                        </div>

                        <!-- Recent Activities -->
                        <div>
                            <h3 class="text-sm font-medium text-gray-500 mb-2">Atividades Recentes</h3>
                            <div class="space-y-2">
                                @foreach(\App\Models\StockMovement::latest()->limit(5)->get() as $movement)
                                    <div class="text-sm">
                                        <p class="font-medium">{{ $movement->product->name }}</p>
                                        <p class="text-gray-500">{{ $movement->type }} - {{ $movement->quantity }}</p>
                                    </div>
                                @endforeach
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Main Content -->
            <div class="ml-64 mr-64">
                @include('navigation-menu')

                <!-- Page Heading -->
                @if (isset($header))
                    <header class="bg-white shadow">
                        <div class="max-w-7xl mx-auto py-6 px-4 sm:px-6 lg:px-8">
                            {{ $header }}
                        </div>
                    </header>
                @elseif (View::hasSection('header'))
                    <header class="bg-white shadow">
                        <div class="max-w-7xl mx-auto py-6 px-4 sm:px-6 lg:px-8">
                            @yield('header')
                        </div>
                    </header>
                @endif

                <!-- Page Content -->
                <main>
                    @yield('content')
                </main>
            </div>
        </div>

        @stack('modals')

        @livewireScripts
        
        <!-- Import IMask before using it -->
        <script src="https://unpkg.com/imask"></script>
        
        <!-- PWA Service Worker -->
        <script src="{{ asset('js/pwa.js') }}"></script>
        
        <!-- WebSocket Configuration -->
        <script>
            window.Laravel = {
                @auth
                user: @json(auth()->user()),
                @endauth
                csrfToken: '{{ csrf_token() }}',
                appUrl: '{{ config('app.url') }}'
            };
        </script>
        
        <!-- Scripts Stack -->
        @stack('scripts')
    </body>
</html>
