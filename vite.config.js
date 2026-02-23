import { defineConfig } from 'vite';
import laravel from 'laravel-vite-plugin';
import tailwindcss from '@tailwindcss/vite';

export default defineConfig({
    plugins: [
        laravel({
            input: ['resources/ts/index.ts'],
            refresh: true,
        }),
        tailwindcss(),
    ],
    server: {
        origin: 'http://3ddemo.virtualbox.demo',   
        watch: {
            ignored: ['**/storage/framework/views/**'],
        },
    },
});
