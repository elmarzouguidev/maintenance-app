const mix = require('laravel-mix');

require('laravel-mix-purgecss');
/*mix.js('resources/js/app.js', 'public/js')
    .postCss('resources/css/app.css', 'public/css', [
        //
    ]);*/

mix.js('resources/js/app.js', 'public/js')
    .sass('resources/css/app.scss', 'public/css')
    .purgeCss({
        enabled: true,
        extend: {
            content: [
                "app/**/*.php",
                "resources/**/*.html",
                "resources/**/*.js",
                "resources/**/*.jsx",
                "resources/**/*.ts",
                "resources/**/*.tsx",
                "resources/**/*.php",
                "resources/**/*.vue",
                "resources/**/*.twig"
            ],
            safelist: { deep: [/hljs/] }
        }
    });

