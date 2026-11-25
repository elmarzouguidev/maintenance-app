import { defineConfig } from 'vite';
import laravel from 'laravel-vite-plugin';
import tailwindcss from '@tailwindcss/vite';

export default defineConfig({
    plugins: [
        laravel({
            input: [
                // UI kit (ui-kit/): Tailwind CSS v4 with the kit's tokens and Alpine, loaded by resources/views/layouts.
                'resources/css/ui-kit.css',
                'resources/js/app.js',
            ],
            refresh: [
                'app/View/Components/**',
                'lang/**',
                'resources/lang/**',
                'resources/views/**/*.blade.php',
                'routes/**',
            ],
        }),
        tailwindcss(),
    ],
    server: {
        watch: {
            // resources/views is watched so Tailwind picks up new classes during `npm run dev`.
            ignored: [
                '**/app/**',
                '**/bootstrap/cache/**',
                '**/database/**',
                '**/lang/**',
                '**/public/**',
                '**/resources/lang/**',
                '**/routes/**',
                '**/storage/**',
                '**/tests/**',
                '**/vendor/**',
            ],
        },
    },
});
