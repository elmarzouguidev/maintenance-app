const mix = require('laravel-mix');

require('laravel-mix-purgecss');
/*mix.js('resources/js/app.js', 'public/js')
    .postCss('resources/css/app.css', 'public/css', [
        //
    ]);*/

mix.js('resources/js/app.js', 'public/js')
    .sass('resources/css/app.scss', 'public/css');
   /* .purgeCss({
        enabled: true
    });*/

