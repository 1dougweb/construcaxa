<!DOCTYPE html>
<html lang="<?php echo e(str_replace('_', '-', app()->getLocale())); ?>">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="<?php echo e(csrf_token()); ?>">

        <title><?php echo e(config('app.name', 'Laravel')); ?></title>

        <!-- Fonts -->
        <link rel="preconnect" href="https://fonts.bunny.net">
        <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />

        <!-- Scripts -->
        <!-- Tailwind CSS & JS CDNs -->
        <script src="https://cdn.tailwindcss.com?plugins=forms,typography,aspect-ratio"></script>
        <script>
            tailwind.config = {
                darkMode: 'class',
                theme: {
                    extend: {
                        colors: {
                            indigo: {
                                500: '#6366f1',
                                600: '#4f46e5',
                                700: '#4338ca',
                                800: '#3730a3',
                                900: '#312e81',
                            }
                        }
                    }
                }
            }
        </script>

        
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
        
        <style>
            [x-cloak] { display: none !important; }
            
            /* Prevenir flash branco - aplicar background escuro imediatamente */
            html.dark body {
                background-color: #111827;
            }
        </style>
    </head>
    <body data-auth-page="true">
        <div class="font-sans text-gray-900 dark:text-gray-100 antialiased">
            <?php echo e($slot); ?>

        </div>
    </body>
</html>
<?php /**PATH C:\Users\Douglas\Documents\Projetos\construcaxa\resources\views/components/guest-layout.blade.php ENDPATH**/ ?>