import { defineConfig } from 'vite';
import laravel from 'laravel-vite-plugin';

export default defineConfig({
    plugins: [
        laravel({
            input: [
                'resources/css/app.css',
                'resources/js/app.js',
                'resources/js/notifications.js'
            ],
            // Desabilitar refresh automático - usar apenas HMR (Hot Module Replacement)
            // Isso evita refresh completo da página, apenas atualiza o que mudou
            refresh: false,
        }),
    ],
    server: {
        hmr: {
            host: 'localhost',
        },
    },
});
