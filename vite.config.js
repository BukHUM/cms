import { defineConfig } from 'vite';
import laravel from 'laravel-vite-plugin';

export default defineConfig({
    plugins: [
        laravel({
            input: [
                'resources/css/fonts.css',
                'resources/css/app.css',
                'resources/css/frontend.css',
                'resources/js/app.js'
            ],
            refresh: true,
        }),
    ],
    server: {
        host: process.env.VITE_HOST || '0.0.0.0',
        port: parseInt(process.env.VITE_PORT) || 5223,
        hmr: {
            host: process.env.VITE_HMR_HOST || 'localhost',
        },
        cors: {
            origin: ['http://core.local', 'http://localhost', 'http://127.0.0.1'],
            credentials: true,
        },
        headers: {
            'Access-Control-Allow-Origin': process.env.VITE_CORS_ALLOW_ORIGIN || '*',
            'Access-Control-Allow-Methods': process.env.VITE_CORS_ALLOW_METHODS ? 
                process.env.VITE_CORS_ALLOW_METHODS.replace(/_/g, ', ').replace(/\s+/g, ', ') : 
                'GET, POST, PUT, DELETE, OPTIONS',
            'Access-Control-Allow-Headers': process.env.VITE_CORS_ALLOW_HEADERS || 'Content-Type, Authorization',
        },
    },
});
