const mix = require('laravel-mix');


/*mix.js('resources/js/app.js', 'public/js')
    .postCss('resources/css/app.css', 'public/css', [
        //
    ]);*/

mix.sass('resources/css/app.scss', 'public/css')
    .js('resources/js/app.js', 'public/js');

mix.scripts(
    [
        "public/assets/libs/tui-dom/tui-dom.min.js",
        "public/assets/libs/tui-time-picker/tui-time-picker.min.js",
        "public/assets/libs/tui-date-picker/tui-date-picker.min.js",
        "public/assets/libs/moment/min/moment.min.js",
        "public/assets/libs/chance/chance.min.js",
        "public/assets/libs/tui-calendar/tui-calendar.min.js",

        "public/js/pages/calendars.js",
        "public/js/pages/schedules.js",
        "public/js/pages/calendar.init.js",
    ],
    "public/js/fullcalendar.js"
);
mix.styles(
    [
        "public/assets/libs/tui-time-picker/tui-time-picker.min.css",
        "public/assets/libs/tui-date-picker/tui-date-picker.min.css",
        "public/assets/libs/tui-calendar/tui-calendar.min.css"
    ],
    "public/css/fullcalendar.css"
);    