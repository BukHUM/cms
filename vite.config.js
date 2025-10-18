import { defineConfig } from 'vite';
import laravel from 'laravel-vite-plugin';

export default defineConfig({
    plugins: [
        laravel({
            input: ['resources/css/app.css', 'resources/js/app.js', 'resources/js/auth.js'],
            refresh: true,
        }),
    ],
    server: {
        host: '0.0.0.0',
        port: 5223,
        hmr: {
            host: 'localhost',
        },
        cors: {
            origin: ['http://backend.local', 'http://localhost:8000'],
            credentials: true,
        },
        headers: {
            'Access-Control-Allow-Origin': '*',
            'Access-Control-Allow-Methods': 'GET, POST, PUT, DELETE, OPTIONS',
            'Access-Control-Allow-Headers': 'Content-Type, Authorization',
        },
    },
});
