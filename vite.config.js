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
        host: process.env.VITE_HOST || '0.0.0.0',
        port: parseInt(process.env.VITE_PORT) || 5223,
        hmr: {
            host: process.env.VITE_HMR_HOST || 'localhost',
        },
        cors: {
            origin: process.env.VITE_CORS_ORIGINS ? 
                process.env.VITE_CORS_ORIGINS.split(',').map(origin => origin.trim()) : 
                ['http://localhost:8000'],
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
