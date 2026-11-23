<?php

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
