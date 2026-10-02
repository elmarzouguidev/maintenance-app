import { defineConfig } from 'vite';
import laravel from 'laravel-vite-plugin';

export default defineConfig({
    plugins: [
        laravel({
            input: ['resources/css/app.css', 'resources/js/app.js'],
            refresh: [
                'app/Livewire/**',
                'app/View/Components/**',
                'lang/**',
                'resources/lang/**',
                'resources/views/**/*.blade.php',
                'routes/**',
            ],
        }),
    ],
    server: {
        watch: {
            ignored: [
                '**/app/**',
                '**/bootstrap/cache/**',
                '**/database/**',
                '**/lang/**',
                '**/public/**',
                '**/resources/lang/**',
                '**/resources/views/**',
                '**/routes/**',
                '**/storage/**',
                '**/tests/**',
                '**/vendor/**',
            ],
        },
    },
});
