import { defineConfig } from 'vite';
import laravel from 'laravel-vite-plugin';
import tailwindcss from '@tailwindcss/vite';

export default defineConfig({
    plugins: [
        laravel({
            input: [
                // Tailwind CSS v4: loaded by the new layout.
                'resources/css/app.css',
                'resources/js/app.js',
                // UI kit (ui-kit/): Tailwind CSS v4 with the kit's tokens, loaded by resources/views/layouts.
                'resources/css/ui-kit.css',
                // Bootstrap 5.0.1 + Skote: loaded by the current layout until every view is migrated.
                'resources/css/legacy.css',
                'resources/js/legacy.js',
            ],
            refresh: [
                'app/Livewire/**',
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
