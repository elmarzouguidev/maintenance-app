<?php

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Route;

/*
 * The UI kit reference page (/_ui-kit-check): only registered in the local environment, behind
 * auth, rendered with the kit's tenant layout and bundle, and fed by AdminLayoutComposer like
 * every page of that layout. The page content is fictional.
 */

uses(RefreshDatabase::class);

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
    $user = User::factory()->create(['prenom' => 'Amal', 'nom' => 'Benali', 'email' => 'technicien@example.com']);

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

it('feeds the kit layout with the user\'s navigation, name and account menu', function () {
    useFakeViteServer();
    registerWebRoutesAsLocal();
    $user = User::factory()->create(['prenom' => 'Amal', 'nom' => 'Benali']);

    $response = $this->actingAs($user)->get('/_ui-kit-check');

    $response->assertOk()
        ->assertSee('href="'.route('admin:home').'?appFilter', false)
        ->assertSee('Tableau de bord')
        ->assertSee('Amal Benali')
        ->assertSee('href="'.route('admin:profile.index').'"', false)
        ->assertSee('href="'.route('admin:profile.settings').'"', false)
        ->assertDontSee('Gestion commerciale');
});

it('shows every kit section with its states', function () {
    useFakeViteServer();
    registerWebRoutesAsLocal();

    $response = $this->actingAs(User::factory()->create())->get('/_ui-kit-check');

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
