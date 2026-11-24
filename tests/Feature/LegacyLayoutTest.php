<?php

/*
 * The Bootstrap layout (theme/layouts/app), still used by the screens not yet on the UI kit.
 */

it('keeps the legacy layout on the Bootstrap bundle', function () {
    $legacyLayout = file_get_contents(resource_path('views/theme/layouts/app.blade.php'));

    expect($legacyLayout)->toContain("@vite(['resources/css/legacy.css', 'resources/js/legacy.js'])")
        ->and($legacyLayout)->not->toContain('resources/js/app.js');
});
