<?php

use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Vite;
use Tests\TestCase;

/*
|--------------------------------------------------------------------------
| Test Case
|--------------------------------------------------------------------------
|
| Function-style Pest tests in tests/Feature run on Laravel's TestCase, so they
| can use helpers such as $this->blade(), $this->get() and withViewErrors().
| Class-based PHPUnit tests are not affected. All tests run on an in-memory
| SQLite database (phpunit.xml).
|
*/

pest()->extend(TestCase::class)->in('Feature');

/*
|--------------------------------------------------------------------------
| Helpers
|--------------------------------------------------------------------------
*/

/**
 * Point @vite at a fake dev server, so pages render predictable asset tags
 * (http://vite.test/resources/css/app.css) without a build or a running dev server.
 */
function useFakeViteServer(): void
{
    File::ensureDirectoryExists(storage_path('framework/testing'));
    File::put($hotFile = storage_path('framework/testing/vite-feature.hot'), 'http://vite.test');

    Vite::useHotFile($hotFile);
}
