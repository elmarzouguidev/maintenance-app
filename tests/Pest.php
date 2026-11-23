<?php

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

/*
|--------------------------------------------------------------------------
| Test Case
|--------------------------------------------------------------------------
|
| Function-style Pest tests in tests/Feature run on Laravel's TestCase, so they
| can use helpers such as $this->blade(), $this->get() and withViewErrors().
| Class-based PHPUnit tests are not affected.
|
| Browser tests (pestphp/pest-plugin-browser) run against an in-memory SQLite
| database (phpunit.xml), rebuilt for every test. Build the assets first:
| `npm run build`, then `vendor/bin/pest tests/Browser`.
|
*/

pest()->extend(TestCase::class)->in('Feature');

pest()->extend(TestCase::class)->use(RefreshDatabase::class)->in('Browser');

/*
|--------------------------------------------------------------------------
| Helpers
|--------------------------------------------------------------------------
*/

/**
 * Wait until a JavaScript condition is true on the page (polled every animation frame),
 * instead of waiting a fixed time. Fails after the timeout.
 */
function waitUntil(mixed $page, string $condition, int $timeoutMs = 5000): mixed
{
    $met = $page->script(<<<JS
        () => new Promise((resolve) => {
            const startedAt = Date.now();
            const check = () => {
                let result = false;
                try { result = Boolean({$condition}); } catch (error) { result = false; }
                if (result) { return resolve(true); }
                if (Date.now() - startedAt > {$timeoutMs}) { return resolve(false); }
                requestAnimationFrame(check);
            };
            check();
        })
        JS);

    expect($met)->toBeTrue("Timed out after {$timeoutMs}ms waiting for: {$condition}");

    return $page;
}
