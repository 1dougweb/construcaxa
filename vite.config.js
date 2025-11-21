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
            refresh: true,
        }),
    ],
    server: {
        hmr: {
            host: process.env.VITE_HOST || 'localhost',
            protocol: process.env.VITE_PROTOCOL || 'http',
        },
    },
    build: {
        manifest: true,
    },
});
