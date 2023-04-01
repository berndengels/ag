import { defineConfig } from 'vite';
import laravel from 'laravel-vite-plugin';
import { fileURLToPath, URL } from "url";
import vue from '@vitejs/plugin-vue'

export default defineConfig({
    resolve: {
        alias: [
            { find: '@', replacement: fileURLToPath(new URL('./resources/js', import.meta.url)) },
            { find: '@v', replacement: fileURLToPath(new URL('./resources/js/vue', import.meta.url)) },
        ],
    },
    plugins: [
        laravel({
            input: [
                'resources/css/app.css',
                'resources/sass/app.scss',
                'resources/js/app.js',
            ],
            refresh: true,
        }),
        vue({
            template: {
                compilerOptions: {
                    // ...
                },
                transformAssetUrls: {
                    // ...
                },
            },
        }),
    ],
});
