<?php

use App\Constants\Etat;
use App\Constants\Status;
use App\Models\Ticket;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Vite;
use Illuminate\Support\Str;
use Tests\TestCase;

/*
|--------------------------------------------------------------------------
| Test Case
|--------------------------------------------------------------------------
|
| Function-style Pest tests in tests/Feature run on Laravel's TestCase, so they
| can use helpers such as $this->blade(), $this->get() and withViewErrors().
| Class-based PHPUnit tests are not affected. All tests run on the dedicated
| MySQL database maintenanceapp_testing (phpunit.xml + .env.testing); the
| suite refuses to start on any other database (tests/CreatesApplication.php).
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
 * (http://vite.test/resources/css/ui-kit.css) without a build or a running dev server.
 */
function useFakeViteServer(): void
{
    File::ensureDirectoryExists(storage_path('framework/testing'));
    File::put($hotFile = storage_path('framework/testing/vite-feature.hot'), 'http://vite.test');

    Vite::useHotFile($hotFile);
}

/**
 * A ticket inserted directly: TicketFactory downloads a photo after creating, and the model
 * numbers tickets from the settings.
 *
 * @param  array<string, mixed>  $attributes
 */
function insertTicket(int $clientId, string $code, array $attributes = []): Ticket
{
    $id = DB::table('tickets')->insertGetId([
        'uuid' => (string) Str::uuid(), 'code' => $code, 'article' => 'Imprimante laser', 'description' => 'Ne démarre plus', 'client_id' => $clientId,
        'etat' => Etat::NON_DIAGNOSTIQUER, 'status' => Status::NON_TRAITE, 'created_at' => '2026-11-20 09:00:00', 'updated_at' => '2026-11-20 09:00:00',
        ...$attributes,
    ]);

    return Ticket::findOrFail($id);
}

/**
 * The statuses lookup rows with the ids App\Constants\Status uses (a seeder run inside a rolled
 * back test would number them past 13: MySQL keeps the auto-increment).
 */
function insertTicketStatuses(): void
{
    DB::table('statuses')->insert(collect(Status::TICKET_STATUS)->map(fn (int $id, string $slug): array => ['id' => $id, 'uuid' => (string) Str::uuid(), 'name' => __('status.statuses.'.$id), 'slug' => $slug])->values()->all());
}
