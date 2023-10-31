import { defineConfig } from 'vite';
import laravel from 'laravel-vite-plugin';

export default defineConfig({
    plugins: [
        laravel({
            input: [
                // CSS
                "resources/sass/app.scss",

                // JS
                "resources/js/app.js",

                // ADMIN CSS
                "resources/sass/admin/app.scss",
                "resources/sass/admin/navigation.scss",

                // ADMIN JS
                "resources/js/admin/app.js",
            ],
            refresh: true,
        }),
    ],
});
