<?php

it('feeds the sidebar badge counts to the Tailwind layout', function () {
    expect(app('events')->hasListeners('composing: theme.layouts.tailwind'))->toBeTrue()
        ->and(app('events')->hasListeners('composing: theme.layouts.app'))->toBeTrue();
});

it('keeps the legacy layout on the Bootstrap bundle', function () {
    $legacyLayout = file_get_contents(resource_path('views/theme/layouts/app.blade.php'));
    $tailwindLayout = file_get_contents(resource_path('views/theme/layouts/tailwind.blade.php'));

    expect($legacyLayout)->toContain("@vite(['resources/css/legacy.css', 'resources/js/legacy.js'])")
        ->and($tailwindLayout)->toContain("@vite(['resources/css/app.css', 'resources/js/app.js'])")
        ->and($tailwindLayout)->not->toContain('vendor-scripts')
        ->and($tailwindLayout)->not->toContain('legacy.css')
        ->and($tailwindLayout)->not->toContain('legacy.js');
});
