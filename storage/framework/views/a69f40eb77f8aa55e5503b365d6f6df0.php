<!DOCTYPE html>
<html lang="<?php echo e(str_replace('_', '-', app()->getLocale())); ?>">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="<?php echo e(csrf_token()); ?>">

        <title><?php echo e(config('app.name', 'Laravel')); ?></title>

        <!-- Notificações de sessão flash -->
        <?php if(session('success')): ?>
            <meta name="notification-success" content="<?php echo e(session('success')); ?>">
        <?php endif; ?>
        <?php if(session('error')): ?>
            <meta name="notification-error" content="<?php echo e(session('error')); ?>">
        <?php endif; ?>
        <?php if(session('info')): ?>
            <meta name="notification-info" content="<?php echo e(session('info')); ?>">
        <?php endif; ?>
        <?php if(session('test_success')): ?>
            <meta name="notification-info" content="<?php echo e(session('test_success')); ?>">
        <?php endif; ?>
        <?php if(session('test_error')): ?>
            <meta name="notification-error" content="<?php echo e(session('test_error')); ?>">
        <?php endif; ?>

        <!-- PWA Meta Tags -->
        <meta name="theme-color" content="#1E2780">
        <meta name="mobile-web-app-capable" content="yes">
        <meta name="apple-mobile-web-app-capable" content="yes">
        <meta name="apple-mobile-web-app-status-bar-style" content="black-translucent">
        <meta name="apple-mobile-web-app-title" content="Stock Master">
        <meta name="description" content="Sistema de gestão de estoque e projetos">
        
        <!-- PWA Manifest -->
        <link rel="manifest" href="<?php echo e(url('manifest.json')); ?>">
        
        <!-- Apple Touch Icons (opcional - só carrega se existir) -->
        <?php if(file_exists(public_path('icons/icon-192x192.png'))): ?>
        <link rel="apple-touch-icon" href="<?php echo e(asset('icons/icon-192x192.png')); ?>">
        <link rel="apple-touch-icon" sizes="152x152" href="<?php echo e(asset('icons/icon-152x152.png')); ?>">
        <link rel="apple-touch-icon" sizes="180x180" href="<?php echo e(asset('icons/icon-192x192.png')); ?>">
        <?php endif; ?>
        
        <!-- Favicon (opcional - só carrega se existir) -->
        <?php if(file_exists(public_path('icons/icon-192x192.png'))): ?>
        <link rel="icon" type="image/png" sizes="192x192" href="<?php echo e(asset('icons/icon-192x192.png')); ?>">
        <link rel="icon" type="image/png" sizes="512x512" href="<?php echo e(asset('icons/icon-512x512.png')); ?>">
        <?php endif; ?>

        <!-- Fonts -->
        <link rel="preconnect" href="https://fonts.bunny.net">
        <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />

        <!-- Scripts -->
        <?php echo app('Illuminate\Foundation\Vite')(['resources/css/app.css', 'resources/js/app.js', 'resources/js/notifications.js']); ?>

        <!-- Icons -->
        <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">

        <!-- Styles -->
        <?php echo \Livewire\Mechanisms\FrontendAssets\FrontendAssets::styles(); ?>

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
            
            /* Prevenir flash branco - aplicar background escuro imediatamente */
            html.dark body {
                background-color: #111827;
            }
        </style>
        
        <!-- Script inline para prevenir flash branco no dark mode -->
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
            })();
        </script>
    </head>
    <body class="font-sans antialiased">
        <!-- Notification container for dynamic notifications -->
        <div id="notifications-container" class="fixed top-0 right-0 m-6 space-y-3" style="z-index: 9999; pointer-events: none;"></div>

        <div class="min-h-screen bg-gray-100 dark:bg-gray-900 transition-colors duration-200" 
             x-data="{ 
                sidebarOpen: false, 
                userMenuOpen: false,
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
                }
             }">
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
                        <a href="<?php echo e(route('dashboard')); ?>" class="flex items-center">
                            <img src="<?php echo e(asset('assets/images/logo.svg')); ?>" alt="Logo" class="h-8 w-auto" />
                        </a>
                    </div>
                    <!-- Logo modo escuro -->
                    <div x-show="darkMode">
                        <a href="<?php echo e(route('dashboard')); ?>" class="flex items-center">
                            <img src="<?php echo e(asset('assets/images/logo-light.svg')); ?>" alt="Logo" class="h-8 w-auto" />
                        </a>
                    </div>
                </div>
                <!-- Espaçador para balancear o layout -->
                <div class="w-10"></div>
            </div>

            <!-- Sidebar -->
            <aside class="fixed inset-y-0 left-0 w-64 bg-white dark:bg-gray-800 border-r border-gray-200 dark:border-gray-700 z-40 transform transition-transform duration-200 ease-in-out -translate-x-full lg:translate-x-0 flex flex-col" :class="{ '-translate-x-full': !sidebarOpen, 'translate-x-0': sidebarOpen }" @keydown.window.escape="sidebarOpen = false">
                <div class="h-16 px-6 flex items-center border-b border-gray-200 dark:border-gray-700 flex-shrink-0" x-cloak>
                    <div class="flex-1 flex items-center justify-center lg:justify-start">
                        <!-- Logo modo claro -->
                        <div x-show="!darkMode">
                            <a href="<?php echo e(route('dashboard')); ?>" class="flex items-center gap-2">
                                <img src="<?php echo e(asset('assets/images/logo.svg')); ?>" alt="Logo" class="h-12 w-auto" />
                            </a>
                        </div>
                        <!-- Logo modo escuro -->
                        <div x-show="darkMode">
                            <a href="<?php echo e(route('dashboard')); ?>" class="flex items-center gap-2">
                                <img src="<?php echo e(asset('assets/images/logo-light.svg')); ?>" alt="Logo" class="h-12 w-auto" />
                            </a>
                        </div>
                    </div>
                </div>
                <nav class="flex-1 overflow-y-auto sidebar-scroll p-4 space-y-1" x-data="{ 
                    estoqueOpen: <?php echo e(request()->routeIs('products.*') || request()->routeIs('equipment.*') || request()->routeIs('material-requests.*') || request()->routeIs('equipment-requests.*') || request()->routeIs('suppliers.*') ? 'true' : 'false'); ?>,
                    gestaoOpen: <?php echo e(request()->routeIs('employees.*') || request()->routeIs('attendance.manage') || request()->routeIs('budgets.*') || (request()->routeIs('projects.*') && !request()->routeIs('client.projects.*')) || request()->routeIs('services.*') || request()->routeIs('labor-types.*') || request()->routeIs('service-categories.*') || request()->routeIs('map.*') || request()->routeIs('clients.*') || request()->routeIs('contracts.*') || request()->routeIs('inspections.*') ? 'true' : 'false'); ?>,
                    financeiroOpen: <?php echo e(request()->routeIs('financial.*') ? 'true' : 'false'); ?>,
                    adminOpen: <?php echo e(request()->routeIs('admin.permissions.*') || request()->routeIs('admin.email.*') ? 'true' : 'false'); ?>

                }">
                    <!-- Dashboard sempre visível -->
                    <a href="<?php echo e(route('dashboard')); ?>" class="flex items-center px-3 py-2 rounded-md text-sm font-medium <?php echo e(request()->routeIs('dashboard') ? 'bg-indigo-50 dark:bg-indigo-900/50 text-indigo-700 dark:text-indigo-300' : 'text-gray-700 dark:text-gray-300 hover:bg-gray-50 dark:hover:bg-gray-700'); ?>">
                        <i class="bi bi-speedometer2 mr-3 text-base"></i>
                        <?php echo e(__('Dashboard')); ?>

                    </a>

                    <!-- Dropdown Estoque -->
                    <?php if(auth()->user()->can('view products') || auth()->user()->can('view service-orders') || auth()->user()->can('view suppliers') || auth()->user()->hasAnyRole(['admin', 'manager'])): ?>
                    <div>
                        <button @click="estoqueOpen = !estoqueOpen" class="w-full flex items-center justify-between px-3 py-2 rounded-md text-sm font-medium text-gray-700 dark:text-gray-300 hover:bg-gray-50 dark:hover:bg-gray-700 transition-colors">
                            <div class="flex items-center">
                                <i class="bi bi-boxes mr-3 text-base"></i>
                                <span><?php echo e(__('Estoque')); ?></span>
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
                             class="ml-3 mt-0.5 mb-1 space-y-0.5 border-l-2 border-gray-200 dark:border-gray-700 pl-3">
                            <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('view products')): ?>
                            <a href="<?php echo e(route('products.index')); ?>" class="flex items-center px-3 py-2 rounded-md text-sm font-medium <?php echo e(request()->routeIs('products.*') ? 'bg-indigo-50 dark:bg-indigo-900/50 text-indigo-700 dark:text-indigo-300 -ml-3 pl-5' : 'text-gray-600 dark:text-gray-400 hover:bg-gray-50 dark:hover:bg-gray-700'); ?>">
                                <i class="bi bi-box-seam mr-3 text-base"></i>
                                <?php echo e(__('Produtos')); ?>

                            </a>
                            <?php endif; ?>
                            <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('view products')): ?>
                            <a href="<?php echo e(route('equipment.index')); ?>" class="flex items-center px-3 py-2 rounded-md text-sm font-medium <?php echo e(request()->routeIs('equipment.*') ? 'bg-indigo-50 dark:bg-indigo-900/50 text-indigo-700 dark:text-indigo-300 -ml-3 pl-5' : 'text-gray-600 dark:text-gray-400 hover:bg-gray-50 dark:hover:bg-gray-700'); ?>">
                                <i class="bi bi-tools mr-3 text-base"></i>
                                <?php echo e(__('Equipamentos')); ?>

                            </a>
                            <?php endif; ?>
                            <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('view service-orders')): ?>
                            <a href="<?php echo e(route('material-requests.index')); ?>" class="flex items-center px-3 py-2 rounded-md text-sm font-medium <?php echo e(request()->routeIs('material-requests.*') ? 'bg-indigo-50 dark:bg-indigo-900/50 text-indigo-700 dark:text-indigo-300 -ml-3 pl-5' : 'text-gray-600 dark:text-gray-400 hover:bg-gray-50 dark:hover:bg-gray-700'); ?>">
                                <i class="bi bi-clipboard-check mr-3 text-base"></i>
                                <?php echo e(__('Requisições de Material')); ?>

                            </a>
                            <?php endif; ?>
                            <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('view service-orders')): ?>
                            <a href="<?php echo e(route('equipment-requests.index')); ?>" class="flex items-center px-3 py-2 rounded-md text-sm font-medium <?php echo e(request()->routeIs('equipment-requests.*') ? 'bg-indigo-50 dark:bg-indigo-900/50 text-indigo-700 dark:text-indigo-300 -ml-3 pl-5' : 'text-gray-600 dark:text-gray-400 hover:bg-gray-50 dark:hover:bg-gray-700'); ?>">
                                <i class="bi bi-wrench-adjustable mr-3 text-base"></i>
                                <?php echo e(__('Requisições de Equipamento')); ?>

                            </a>
                            <?php endif; ?>
                            <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('view suppliers')): ?>
                            <a href="<?php echo e(route('suppliers.index')); ?>" class="flex items-center px-3 py-2 rounded-md text-sm font-medium <?php echo e(request()->routeIs('suppliers.*') ? 'bg-indigo-50 dark:bg-indigo-900/50 text-indigo-700 dark:text-indigo-300 -ml-3 pl-5' : 'text-gray-600 dark:text-gray-400 hover:bg-gray-50 dark:hover:bg-gray-700'); ?>">
                                <i class="bi bi-truck mr-3 text-base"></i>
                                <?php echo e(__('Fornecedores')); ?>

                            </a>
                            <?php endif; ?>
                        </div>
                    </div>
                    <?php endif; ?>

                    <!-- Dropdown Gestão -->
                    <?php if(auth()->user()->can('view employees') || auth()->user()->can('manage attendance') || auth()->user()->can('view budgets') || auth()->user()->can('view projects') || auth()->user()->can('manage services') || auth()->user()->can('view clients') || auth()->user()->can('view inspections') || auth()->user()->hasAnyRole(['admin', 'manager'])): ?>
                    <div>
                        <button @click="gestaoOpen = !gestaoOpen" class="w-full flex items-center justify-between px-3 py-2 rounded-md text-sm font-medium text-gray-700 dark:text-gray-300 hover:bg-gray-50 dark:hover:bg-gray-700 transition-colors">
                            <div class="flex items-center">
                                <i class="bi bi-gear mr-3 text-base"></i>
                                <span><?php echo e(__('Gestão')); ?></span>
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
                             class="ml-3 mt-0.5 mb-1 space-y-0.5 border-l-2 border-gray-200 dark:border-gray-700 pl-3">
                            <?php
                                $user = auth()->user();
                                // Mesma lógica das rotas: role_or_permission:manager|admin|view clients
                                $showClients = $user->hasAnyRole(['admin', 'manager']) || $user->can('view clients');
                                // Mesma lógica das rotas: role_or_permission:manager|admin|view contracts
                                $showContracts = $user->hasAnyRole(['admin', 'manager']) || $user->can('view contracts');
                            ?>
                            <?php if($showClients): ?>
                            <a href="<?php echo e(route('clients.index')); ?>" 
                               class="flex items-center px-3 py-2 rounded-md text-sm font-medium <?php echo e(request()->routeIs('clients.*') ? 'bg-indigo-50 dark:bg-indigo-900/50 text-indigo-700 dark:text-indigo-300 -ml-3 pl-5' : 'text-gray-600 dark:text-gray-400 hover:bg-gray-50 dark:hover:bg-gray-700'); ?>">
                                <i class="bi bi-person-badge mr-3 text-base"></i>
                                <?php echo e(__('Clientes')); ?>

                            </a>
                            <?php endif; ?>
                            <?php if($showContracts): ?>
                            <a href="<?php echo e(route('contracts.index')); ?>" 
                               class="flex items-center px-3 py-2 rounded-md text-sm font-medium <?php echo e(request()->routeIs('contracts.*') ? 'bg-indigo-50 dark:bg-indigo-900/50 text-indigo-700 dark:text-indigo-300 -ml-3 pl-5' : 'text-gray-600 dark:text-gray-400 hover:bg-gray-50 dark:hover:bg-gray-700'); ?>">
                                <i class="bi bi-file-earmark-text mr-3 text-base"></i>
                                <?php echo e(__('Contratos')); ?>

                            </a>
                            <?php endif; ?>
                            <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('view employees')): ?>
                            <a href="<?php echo e(route('employees.index')); ?>" class="flex items-center px-3 py-2 rounded-md text-sm font-medium <?php echo e(request()->routeIs('employees.*') ? 'bg-indigo-50 dark:bg-indigo-900/50 text-indigo-700 dark:text-indigo-300 -ml-3 pl-5' : 'text-gray-600 dark:text-gray-400 hover:bg-gray-50 dark:hover:bg-gray-700'); ?>">
                                <i class="bi bi-people mr-3 text-base"></i>
                                <?php echo e(__('Funcionários')); ?>

                            </a>
                            <?php endif; ?>
                            <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('manage attendance')): ?>
                            <a href="<?php echo e(route('attendance.manage')); ?>" class="flex items-center px-3 py-2 rounded-md text-sm font-medium <?php echo e(request()->routeIs('attendance.manage') ? 'bg-indigo-50 dark:bg-indigo-900/50 text-indigo-700 dark:text-indigo-300 -ml-3 pl-5' : 'text-gray-600 dark:text-gray-400 hover:bg-gray-50 dark:hover:bg-gray-700'); ?>">
                                <i class="bi bi-calendar-check mr-3 text-base"></i>
                                <?php echo e(__('Gestão de Pontos')); ?>

                            </a>
                            <?php endif; ?>
                            <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('view budgets')): ?>
                            <a href="<?php echo e(route('budgets.index')); ?>" class="flex items-center px-3 py-2 rounded-md text-sm font-medium <?php echo e(request()->routeIs('budgets.*') ? 'bg-indigo-50 dark:bg-indigo-900/50 text-indigo-700 dark:text-indigo-300 -ml-3 pl-5' : 'text-gray-600 dark:text-gray-400 hover:bg-gray-50 dark:hover:bg-gray-700'); ?>">
                                <i class="bi bi-receipt mr-3 text-base"></i>
                                <?php echo e(__('Orçamentos')); ?>

                            </a>
                            <?php endif; ?>
                            <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('view projects')): ?>
                            <a href="<?php echo e(route('projects.index')); ?>" class="flex items-center px-3 py-2 rounded-md text-sm font-medium <?php echo e(request()->routeIs('projects.*') && !request()->routeIs('client.projects.*') ? 'bg-indigo-50 dark:bg-indigo-900/50 text-indigo-700 dark:text-indigo-300 -ml-3 pl-5' : 'text-gray-600 dark:text-gray-400 hover:bg-gray-50 dark:hover:bg-gray-700'); ?>">
                                <i class="bi bi-building mr-3 text-base"></i>
                                <?php echo e(__('Obras')); ?>

                            </a>
                            <?php endif; ?>
                            <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('view projects')): ?>
                            <a href="<?php echo e(route('map.index')); ?>" class="flex items-center px-3 py-2 rounded-md text-sm font-medium <?php echo e(request()->routeIs('map.*') ? 'bg-indigo-50 dark:bg-indigo-900/50 text-indigo-700 dark:text-indigo-300 -ml-3 pl-5' : 'text-gray-600 dark:text-gray-400 hover:bg-gray-50 dark:hover:bg-gray-700'); ?>">
                                <i class="bi bi-geo-alt mr-3 text-base"></i>
                                <?php echo e(__('Mapa')); ?>

                            </a>
                            <?php endif; ?>
                            <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('view inspections')): ?>
                            <a href="<?php echo e(route('inspections.index')); ?>" class="flex items-center px-3 py-2 rounded-md text-sm font-medium <?php echo e(request()->routeIs('inspections.*') ? 'bg-indigo-50 dark:bg-indigo-900/50 text-indigo-700 dark:text-indigo-300 -ml-3 pl-5' : 'text-gray-600 dark:text-gray-400 hover:bg-gray-50 dark:hover:bg-gray-700'); ?>">
                                <i class="bi bi-clipboard-check mr-3 text-base"></i>
                                <?php echo e(__('Vistorias')); ?>

                            </a>
                            <?php endif; ?>
                            <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('manage services')): ?>
                            <a href="<?php echo e(route('services.index')); ?>" class="flex items-center px-3 py-2 rounded-md text-sm font-medium <?php echo e(request()->routeIs('services.*') ? 'bg-indigo-50 dark:bg-indigo-900/50 text-indigo-700 dark:text-indigo-300 -ml-3 pl-5' : 'text-gray-600 dark:text-gray-400 hover:bg-gray-50 dark:hover:bg-gray-700'); ?>">
                                <i class="bi bi-tools mr-3 text-base"></i>
                                <?php echo e(__('Serviços')); ?>

                            </a>
                            <?php endif; ?>
                            <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('manage services')): ?>
                            <a href="<?php echo e(route('labor-types.index')); ?>" class="flex items-center px-3 py-2 rounded-md text-sm font-medium <?php echo e(request()->routeIs('labor-types.*') ? 'bg-indigo-50 dark:bg-indigo-900/50 text-indigo-700 dark:text-indigo-300 -ml-3 pl-5' : 'text-gray-600 dark:text-gray-400 hover:bg-gray-50 dark:hover:bg-gray-700'); ?>">
                                <i class="bi bi-people mr-3 text-base"></i>
                                <?php echo e(__('Tipos de Mão de Obra')); ?>

                            </a>
                            <?php endif; ?>
                            <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('manage services')): ?>
                            <a href="<?php echo e(route('service-categories.index')); ?>" class="flex items-center px-3 py-2 rounded-md text-sm font-medium <?php echo e(request()->routeIs('service-categories.*') ? 'bg-indigo-50 dark:bg-indigo-900/50 text-indigo-700 dark:text-indigo-300 -ml-3 pl-5' : 'text-gray-600 dark:text-gray-400 hover:bg-gray-50 dark:hover:bg-gray-700'); ?>">
                                <i class="bi bi-folder mr-3 text-base"></i>
                                <?php echo e(__('Categorias de Serviços')); ?>

                            </a>
                            <?php endif; ?>
                        </div>
                    </div>
                    <?php endif; ?>

                    <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('view client-projects')): ?>
                    <a href="<?php echo e(route('client.dashboard')); ?>" class="flex items-center px-3 py-2 rounded-md text-sm font-medium <?php echo e(request()->routeIs('client.dashboard') ? 'bg-indigo-50 dark:bg-indigo-900/50 text-indigo-700 dark:text-indigo-300' : 'text-gray-700 dark:text-gray-300 hover:bg-gray-50 dark:hover:bg-gray-700'); ?>">
                        <i class="bi bi-speedometer2 mr-3 text-base"></i>
                        <?php echo e(__('Dashboard')); ?>

                    </a>
                    <a href="<?php echo e(route('client.projects.index')); ?>" class="flex items-center px-3 py-2 rounded-md text-sm font-medium <?php echo e(request()->routeIs('client.projects.*') && !request()->routeIs('client.dashboard') ? 'bg-indigo-50 dark:bg-indigo-900/50 text-indigo-700 dark:text-indigo-300' : 'text-gray-700 dark:text-gray-300 hover:bg-gray-50 dark:hover:bg-gray-700'); ?>">
                        <i class="bi bi-house-door mr-3 text-base"></i>
                        <?php echo e(__('Minhas Obras')); ?>

                    </a>
                    <?php endif; ?>
                    <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('view reports')): ?>
                    <a href="<?php echo e(route('reports.index')); ?>" class="flex items-center px-3 py-2 rounded-md text-sm font-medium <?php echo e(request()->routeIs('reports.*') ? 'bg-indigo-50 dark:bg-indigo-900/50 text-indigo-700 dark:text-indigo-300' : 'text-gray-700 dark:text-gray-300 hover:bg-gray-50 dark:hover:bg-gray-700'); ?>">
                        <i class="bi bi-graph-up mr-3 text-base"></i>
                        <?php echo e(__('Relatórios')); ?>

                    </a>
                    <?php endif; ?>

                    <!-- Dropdown Financeiro -->
                    <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('manage finances')): ?>
                    <div>
                        <button @click="financeiroOpen = !financeiroOpen" class="w-full flex items-center justify-between px-3 py-2 rounded-md text-sm font-medium text-gray-700 dark:text-gray-300 hover:bg-gray-50 dark:hover:bg-gray-700 transition-colors">
                            <div class="flex items-center">
                                <i class="bi bi-cash-coin mr-3 text-base"></i>
                                <span><?php echo e(__('Financeiro')); ?></span>
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
                             class="ml-3 mt-0.5 mb-1 space-y-0.5 border-l-2 border-gray-200 dark:border-gray-700 pl-3">
                            <a href="<?php echo e(route('financial.dashboard')); ?>" class="flex items-center px-3 py-2 rounded-md text-sm font-medium <?php echo e(request()->routeIs('financial.dashboard') ? 'bg-indigo-50 dark:bg-indigo-900/50 text-indigo-700 dark:text-indigo-300 border-l-2 border-indigo-500 dark:border-indigo-400 -ml-3 pl-5' : 'text-gray-600 dark:text-gray-400 hover:bg-gray-50 dark:hover:bg-gray-700'); ?>">
                                <i class="bi bi-speedometer2 mr-3 text-base"></i>
                                <?php echo e(__('Dashboard Financeiro')); ?>

                            </a>
                            <a href="<?php echo e(route('financial.accounts-payable.index')); ?>" class="flex items-center px-3 py-2 rounded-md text-sm font-medium <?php echo e(request()->routeIs('financial.accounts-payable.*') ? 'bg-indigo-50 dark:bg-indigo-900/50 text-indigo-700 dark:text-indigo-300 border-l-2 border-indigo-500 dark:border-indigo-400 -ml-3 pl-5' : 'text-gray-600 dark:text-gray-400 hover:bg-gray-50 dark:hover:bg-gray-700'); ?>">
                                <i class="bi bi-arrow-down-circle mr-3 text-base"></i>
                                <?php echo e(__('Contas a Pagar')); ?>

                            </a>
                            <a href="<?php echo e(route('financial.accounts-receivable.index')); ?>" class="flex items-center px-3 py-2 rounded-md text-sm font-medium <?php echo e(request()->routeIs('financial.accounts-receivable.*') ? 'bg-indigo-50 dark:bg-indigo-900/50 text-indigo-700 dark:text-indigo-300 border-l-2 border-indigo-500 dark:border-indigo-400 -ml-3 pl-5' : 'text-gray-600 dark:text-gray-400 hover:bg-gray-50 dark:hover:bg-gray-700'); ?>">
                                <i class="bi bi-arrow-up-circle mr-3 text-base"></i>
                                <?php echo e(__('Contas a Receber')); ?>

                            </a>
                            <a href="<?php echo e(route('financial.invoices.index')); ?>" class="flex items-center px-3 py-2 rounded-md text-sm font-medium <?php echo e(request()->routeIs('financial.invoices.*') ? 'bg-indigo-50 dark:bg-indigo-900/50 text-indigo-700 dark:text-indigo-300 border-l-2 border-indigo-500 dark:border-indigo-400 -ml-3 pl-5' : 'text-gray-600 dark:text-gray-400 hover:bg-gray-50 dark:hover:bg-gray-700'); ?>">
                                <i class="bi bi-receipt-cutoff mr-3 text-base"></i>
                                <?php echo e(__('Notas Fiscais')); ?>

                            </a>
                            <a href="<?php echo e(route('financial.receipts.index')); ?>" class="flex items-center px-3 py-2 rounded-md text-sm font-medium <?php echo e(request()->routeIs('financial.receipts.*') ? 'bg-indigo-50 dark:bg-indigo-900/50 text-indigo-700 dark:text-indigo-300 border-l-2 border-indigo-500 dark:border-indigo-400 -ml-3 pl-5' : 'text-gray-600 dark:text-gray-400 hover:bg-gray-50 dark:hover:bg-gray-700'); ?>">
                                <i class="bi bi-receipt mr-3 text-base"></i>
                                <?php echo e(__('Recibos')); ?>

                            </a>
                        </div>
                    </div>
                    <?php endif; ?>

                    <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('view attendance')): ?>
                    <a href="<?php echo e(route('attendance.index')); ?>" class="flex items-center px-3 py-2 rounded-md text-sm font-medium <?php echo e(request()->routeIs('attendance.index') ? 'bg-indigo-50 dark:bg-indigo-900/50 text-indigo-700 dark:text-indigo-300' : 'text-gray-700 dark:text-gray-300 hover:bg-gray-50 dark:hover:bg-gray-700'); ?>">
                        <i class="bi bi-geo-alt mr-3 text-base"></i>
                        <?php echo e(__('Bater Ponto')); ?>

                    </a>
                    <?php endif; ?>

                    <!-- Dropdown Administração -->
                    <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('manage permissions')): ?>
                    <div>
                        <button @click="adminOpen = !adminOpen" class="w-full flex items-center justify-between px-3 py-2 rounded-md text-sm font-medium text-gray-700 dark:text-gray-300 hover:bg-gray-50 dark:hover:bg-gray-700 transition-colors">
                            <div class="flex items-center">
                                <i class="bi bi-shield-check mr-3 text-base"></i>
                                <span><?php echo e(__('Administração')); ?></span>
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
                             class="ml-3 mt-0.5 mb-1 space-y-0.5 border-l-2 border-gray-200 dark:border-gray-700 pl-3">
                            <a href="<?php echo e(route('admin.permissions.users')); ?>" class="flex items-center px-3 py-2 rounded-md text-sm font-medium <?php echo e(request()->routeIs('admin.permissions.users') ? 'bg-indigo-50 dark:bg-indigo-900/50 text-indigo-700 dark:text-indigo-300 -ml-3 pl-5' : 'text-gray-600 dark:text-gray-400 hover:bg-gray-50 dark:hover:bg-gray-700'); ?>">
                                <i class="bi bi-person-check mr-3 text-base"></i>
                                <?php echo e(__('Permissões: Usuários')); ?>

                            </a>
                            <a href="<?php echo e(route('admin.permissions.roles')); ?>" class="flex items-center px-3 py-2 rounded-md text-sm font-medium <?php echo e(request()->routeIs('admin.permissions.roles') ? 'bg-indigo-50 dark:bg-indigo-900/50 text-indigo-700 dark:text-indigo-300 -ml-3 pl-5' : 'text-gray-600 dark:text-gray-400 hover:bg-gray-50 dark:hover:bg-gray-700'); ?>">
                                <i class="bi bi-shield-lock mr-3 text-base"></i>
                                <?php echo e(__('Permissões: Papéis')); ?>

                            </a>
                            <a href="<?php echo e(route('admin.email.index')); ?>" class="flex items-center px-3 py-2 rounded-md text-sm font-medium <?php echo e(request()->routeIs('admin.email.*') ? 'bg-indigo-50 dark:bg-indigo-900/50 text-indigo-700 dark:text-indigo-300 -ml-3 pl-5' : 'text-gray-600 dark:text-gray-400 hover:bg-gray-50 dark:hover:bg-gray-700'); ?>">
                                <i class="bi bi-envelope-at mr-3 text-base"></i>
                                <?php echo e(__('Envio de Emails')); ?>

                            </a>
                        </div>
                    </div>
                    <?php endif; ?>
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
            <header class="fixed top-0 right-0 left-0 lg:left-64 h-16 bg-white dark:bg-gray-800 border-b border-gray-200 dark:border-gray-700 z-30 transition-all duration-200 lg:block hidden">
                <div class="h-full px-4 sm:px-6 lg:px-6 flex items-center justify-between">
                    <?php if(isset($header)): ?>
                        <div class="flex-1">
                            <?php echo e($header); ?>

                        </div>
                    <?php else: ?>
                        <div></div>
                    <?php endif; ?>
                    
                    <!-- User Menu and Theme Toggle -->
                    <div class="flex items-center gap-3">
                        <!-- Theme Toggle -->
                        <button 
                            @click="toggleDarkMode()"
                            class="p-2 rounded-md text-gray-600 dark:text-gray-300 hover:bg-gray-100 dark:hover:bg-gray-700 transition-colors"
                            aria-label="Toggle dark mode">
                            <i class="bi bi-sun-fill text-lg dark:hidden"></i>
                            <i class="bi bi-moon-fill text-lg hidden dark:inline"></i>
                        </button>
                        
                        <!-- User Menu Dropdown -->
                        <div class="relative" x-data="{ open: false }" @click.away="open = false">
                            <button 
                                @click="open = !open"
                                class="flex items-center gap-2 p-2 rounded-md hover:bg-gray-100 dark:hover:bg-gray-700 transition-colors">
                                <div class="flex-shrink-0">
                                    <?php if(Auth::user()->profile_photo): ?>
                                        <img src="<?php echo e(asset('storage/' . Auth::user()->profile_photo)); ?>" alt="<?php echo e(Auth::user()->name); ?>" class="h-8 w-8 rounded-full object-cover border-2 border-gray-200 dark:border-gray-600">
                                    <?php else: ?>
                                        <div class="h-8 w-8 rounded-full bg-indigo-500 flex items-center justify-center text-white font-semibold text-sm border-2 border-gray-200 dark:border-gray-600">
                                            <?php echo e(strtoupper(substr(Auth::user()->name, 0, 1))); ?>

                                        </div>
                                    <?php endif; ?>
                                </div>
                                <span class="hidden sm:block text-sm font-medium text-gray-700 dark:text-gray-300"><?php echo e(Auth::user()->name); ?></span>
                                <i class="bi bi-chevron-down text-xs text-gray-600 dark:text-gray-400"></i>
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
                                    <p class="text-sm font-medium text-gray-900 dark:text-gray-100"><?php echo e(Auth::user()->name); ?></p>
                                    <p class="text-xs text-gray-500 dark:text-gray-400 truncate"><?php echo e(Auth::user()->email); ?></p>
                                </div>
                                <a 
                                    href="<?php echo e(route('profile.edit')); ?>" 
                                    class="block px-4 py-2 text-sm text-gray-700 dark:text-gray-300 hover:bg-gray-100 dark:hover:bg-gray-700 transition-colors">
                                    <i class="bi bi-person-gear mr-2"></i>
                                    Perfil
                                </a>
                                <form method="POST" action="<?php echo e(route('logout')); ?>">
                                    <?php echo csrf_field(); ?>
                                    <button 
                                        type="submit" 
                                        class="w-full text-left px-4 py-2 text-sm text-red-600 dark:text-red-400 hover:bg-red-50 dark:hover:bg-red-900/20 transition-colors">
                                        <i class="bi bi-box-arrow-right mr-2"></i>
                                        <?php echo e(__('Sair')); ?>

                                    </button>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
            </header>

            <!-- Content -->
            <div class="lg:ml-64 pt-16 lg:pt-16">
                <main class="p-4 sm:p-4">
                    <?php echo e($slot); ?>

                </main>
            </div>
        </div>

        <?php echo \Livewire\Mechanisms\FrontendAssets\FrontendAssets::scripts(); ?>

        <script src="https://unpkg.com/imask"></script>
        <script src="<?php echo e(asset('js/pwa.js')); ?>"></script>
        <?php echo $__env->yieldPushContent('scripts'); ?>
        
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
    </body>
</html>
<?php /**PATH C:\Users\drdes\OneDrive\Documentos\Projetos\2025\Novembro\construcaxa\resources\views/components/app-layout.blade.php ENDPATH**/ ?>