<?php

namespace Tests;

use Illuminate\Contracts\Console\Kernel;
use Illuminate\Foundation\Application;
use RuntimeException;

trait CreatesApplication
{
    /**
     * Creates the application.
     */
    public function createApplication(): Application
    {
        $app = require __DIR__.'/../bootstrap/app.php';

        $app->make(Kernel::class)->bootstrap();

        $this->ensureTestingDatabase($app);

        return $app;
    }

    /**
     * Refuse to run against anything but a *_testing database: RefreshDatabase drops every table.
     */
    protected function ensureTestingDatabase(Application $app): void
    {
        $connection = $app['config']->get('database.default');
        $database = (string) $app['config']->get("database.connections.{$connection}.database");

        if (! str_ends_with($database, '_testing')) {
            throw new RuntimeException(
                "Tests must run on a database whose name ends with \"_testing\"; the [{$connection}] connection points to [{$database}]. Check phpunit.xml and .env.testing."
            );
        }
    }
}
