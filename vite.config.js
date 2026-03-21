import { defineConfig } from 'vite';
import laravel from 'laravel-vite-plugin';
import tailwindcss from '@tailwindcss/vite';

export default defineConfig({
    plugins: [
        laravel({
            input: [
                'resources/css/app.css', 
                'resources/ts/index.ts', 
                'resources/ts/sim.ts', 
                'resources/ts/sim2.ts', 
                'resources/ts/sim3.ts'
            ],
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
