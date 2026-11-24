<?php

use App\Models\User;
use Illuminate\Support\Facades\Route;

/*
 * The UI kit install check page (/_ui-kit-check): only registered in the local environment,
 * behind auth, rendered with the kit's tenant layout and bundle. No database: the user is
 * made, not stored, and the page data is fictional.
 */

/**
 * Register routes/web.php again as the local environment does, since its developer pages are
 * only registered when APP_ENV=local (the suite boots with APP_ENV=testing).
 */
function registerWebRoutesAsLocal(): void
{
    app()->detectEnvironment(fn (): string => 'local');

    Route::middleware('web')->group(base_path('routes/web.php'));
    Route::getRoutes()->refreshNameLookups();
}

it('returns 404 outside the local environment, even when signed in', function () {
    $this->actingAs(User::factory()->make());

    $response = $this->get('/_ui-kit-check');

    $response->assertNotFound();
    expect(Route::has('dev.ui-kit'))->toBeFalse();
});

it('redirects guests to the sign-in page in the local environment', function () {
    registerWebRoutesAsLocal();

    $response = $this->get('/_ui-kit-check');

    $response->assertRedirect(route('admin:auth:login'));
});

it('renders the kit layout with the kit bundle and the app\'s Alpine, without Livewire, in the local environment', function () {
    useFakeViteServer();
    registerWebRoutesAsLocal();
    $user = User::factory()->make(['email' => 'technicien@example.com']);

    $response = $this->actingAs($user)->get('/_ui-kit-check');

    $response->assertOk()
        ->assertSee('<title>Vérification du kit - CASAMAINTENANCE</title>', false)
        ->assertSee('http://vite.test/resources/css/ui-kit.css', false)
        ->assertDontSee('http://vite.test/resources/css/app.css', false)
        ->assertSee('http://vite.test/resources/js/app.js', false)
        ->assertDontSee('data-update-uri=', false)
        ->assertSee('class="admin-shell"', false)
        ->assertSee('x-data="adminNavGroups"', false)
        ->assertSee('class="admin-bottom-bar"', false)
        ->assertSee('technicien@example.com')
        ->assertSee('action="'.route('admin:auth:logout').'"', false);
});

it('shows every kit section with its states', function () {
    useFakeViteServer();
    registerWebRoutesAsLocal();

    $response = $this->actingAs(User::factory()->make())->get('/_ui-kit-check');

    $response->assertOk()
        ->assertSeeInOrder([
            '<h1 class="admin-page-title">Vérification du kit</h1>',
            'admin-stat-card',
            'TCK-2026-0001',
            'admin-status-badge-info',
            '?page=2',
            'Des erreurs sont survenues.',
            'L’adresse e-mail n’est pas valide.',
            'type="file" name="files[]"',
            'admin-button-ghost',
            'admin-alert-warning',
            'admin-empty-state',
            '<dialog id="cancel-ticket"',
        ], false);
});
