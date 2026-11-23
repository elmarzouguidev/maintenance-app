<?php

use Illuminate\Support\Facades\File;
use Illuminate\Support\Str;

/*
 * <x-button> defaults to type="submit" like a native button, so a trigger without an
 * explicit type submits the surrounding form. Every <x-button> carrying a client-side
 * handler must say what it is (MIGRATION_AUDIT.md §11.4, item 2).
 */
it('gives an explicit type to every x-button that has a click handler', function () {
    $offenders = collect(File::allFiles(resource_path('views')))
        ->filter(fn (SplFileInfo $file): bool => Str::endsWith($file->getFilename(), '.blade.php'))
        ->flatMap(function (SplFileInfo $file): array {
            preg_match_all('/<x-button\b((?:[^>"\']|"[^"]*"|\'[^\']*\')*)>/s', File::get($file->getPathname()), $matches);

            return collect($matches[1])
                ->filter(fn (string $attributes): bool => preg_match('/(x-on:|@click|\$dispatch|wire:click|onclick)/', $attributes) === 1)
                ->reject(fn (string $attributes): bool => preg_match('/(^|\s):?type=/', $attributes) === 1)
                ->map(fn (string $attributes): string => Str::after($file->getPathname(), resource_path('views').'/')
                    .': <x-button'.Str::limit((string) preg_replace('/\s+/', ' ', $attributes), 100).'>')
                ->all();
        });

    expect($offenders->all())->toBe([]);
});
