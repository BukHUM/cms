import { defineConfig } from 'vite';
import laravel from 'laravel-vite-plugin';
import tailwindcss from '@tailwindcss/vite';

export default defineConfig({
    plugins: [
        laravel({
            input: ['resources/css/app.css', 'resources/css/settings.css', 'resources/css/profile.css', 'resources/css/user-management.css', 'resources/js/app.js'],
            refresh: true,
        }),
        tailwindcss(),
    ],
    server: {
        host: '0.0.0.0',
        port: 5173,
        cors: {
            origin: ['http://backend.local', 'http://localhost', 'http://127.0.0.1'],
            credentials: true,
        },
        hmr: {
            host: 'localhost',
        },
    },
});
