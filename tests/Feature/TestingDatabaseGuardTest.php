<?php

/*
 * RefreshDatabase drops every table, so the suite must never boot against a non-testing database.
 */

it('runs the suite on the MySQL testing database', function () {
    expect(config('database.default'))->toBe('mysql')
        ->and(config('database.connections.mysql.database'))->toBe('maintenanceapp_testing');
});

it('refuses a database whose name does not end with _testing', function () {
    config(['database.connections.mysql.database' => 'maintenanceapp']);

    expect(fn () => $this->ensureTestingDatabase($this->app))
        ->toThrow(RuntimeException::class, 'must run on a database whose name ends with "_testing"');
});
