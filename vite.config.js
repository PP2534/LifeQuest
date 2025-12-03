import { defineConfig } from 'vite';
import laravel from 'laravel-vite-plugin';
import { fileURLToPath, URL } from 'node:url';

const resolveFromRoot = (relativePath) => fileURLToPath(new URL(relativePath, import.meta.url));

export default defineConfig({
    resolve: {
        alias: {
            'laravel-echo': resolveFromRoot('./node_modules/laravel-echo/dist/echo.js'),
            'pusher-js': resolveFromRoot('./node_modules/pusher-js/dist/web/pusher.js'),
        },
    },
    plugins: [
        laravel({
            input: ['resources/css/app.css', 'resources/js/app.js'],
            refresh: true,
        }),
    ],
});
