<?php

use App\Http\Controllers\Developper\UiShowcaseController;
use App\Models\User;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Vite;
use Spatie\Permission\Models\Role;

/*
 * Real-browser checks of the Tailwind shell and component kit, against the /_ui showcase.
 * Run in every migration batch (MIGRATION_AUDIT.md §11.4): `npm run build && vendor/bin/pest tests/Browser`.
 */

beforeEach(function () {
    // /_ui is only registered when APP_ENV=local; register the same route for the test run.
    Route::middleware(['web', 'auth'])->get('/_ui', UiShowcaseController::class)->name('dev.ui');
    Route::getRoutes()->refreshNameLookups();

    // Use the production build, never a running Vite dev server (public/hot).
    Vite::useHotFile(storage_path('framework/testing/vite.hot'));

    $user = User::factory()->create();
    $user->assignRole(Role::create(['name' => 'SuperAdmin', 'guard_name' => $user->guard_name]));

    $this->actingAs($user);
});

it('opens a modal, keeps the focus inside, and closes it with Escape and the backdrop', function () {
    $modal = "document.querySelector('[data-test=\"send-modal\"]')";

    $page = visit('/_ui');

    $page->assertNoJavaScriptErrors()
        ->click('@open-send-modal');

    waitUntil($page, "{$modal}.checkVisibility()");
    waitUntil($page, "{$modal}.contains(document.activeElement)");

    $page->keys('@send-modal', ['Tab', 'Tab', 'Tab', 'Tab', 'Tab', 'Tab', 'Tab', 'Tab']);
    expect($page->script("() => {$modal}.contains(document.activeElement)"))->toBeTrue();

    $page->keys('@send-modal', 'Escape');
    waitUntil($page, "! {$modal}.checkVisibility()");

    $page->click('@open-send-modal');
    waitUntil($page, "{$modal}.checkVisibility()");

    // Click the backdrop, away from the centred dialog.
    $page->script("() => document.querySelector('[data-test=\"send-modal\"]').closest('[role=\"dialog\"]').querySelector('.bg-black\\\\/50').click()");
    waitUntil($page, "! {$modal}.checkVisibility()");

    $page->assertNoJavaScriptErrors();
});

it('opens a dropdown, closes it on an outside click, and renders the menu in the body', function () {
    $menu = "[...document.querySelectorAll('[role=\"menu\"]')].find((menu) => menu.textContent.includes('Trimestre 1'))";

    $page = visit('/_ui');

    $page->click('@period-dropdown');
    waitUntil($page, "{$menu}.checkVisibility()");

    expect($page->script("() => {$menu}.parentElement === document.body"))->toBeTrue();

    $page->click('UI Kit — Tailwind');
    waitUntil($page, "! {$menu}.checkVisibility()");

    // A row action menu inside the scrolling table is not clipped by the table wrapper.
    $page->script("() => document.querySelector('#showcase-table button[aria-label=\"Actions\"]').click()");
    waitUntil($page, "[...document.querySelectorAll('[role=\"menu\"]')].some((menu) => menu.checkVisibility() && menu.parentElement === document.body && menu.textContent.includes('Voir'))");

    $page->assertNoJavaScriptErrors();
});

it('switches tabs and dispatches the tab-shown event', function () {
    $page = visit('/_ui');

    $page->click('@tab-waiting');

    waitUntil($page, "[...document.querySelectorAll('[role=\"tabpanel\"]')].some((panel) => panel.checkVisibility() && panel.textContent.includes('« En attente »'))");
    waitUntil($page, "document.querySelector('[data-test=\"last-tab-shown\"]').textContent === 'waiting'");

    $page->assertAttribute('@tab-waiting', 'aria-selected', 'true')
        ->assertNoJavaScriptErrors();
});

it('remembers the collapsed desktop sidebar after a reload', function () {
    $page = visit('/_ui')->resize(1440, 900);

    $page->click('@sidebar-toggle');
    waitUntil($page, "document.body.hasAttribute('data-sidebar-collapsed')");
    waitUntil($page, "document.querySelector('[data-test=\"sidebar\"]').getBoundingClientRect().width === 70");

    $page->refresh();
    waitUntil($page, "document.body.hasAttribute('data-sidebar-collapsed')");
    waitUntil($page, "document.querySelector('[data-test=\"sidebar\"]').getBoundingClientRect().width === 70");

    $page->click('@sidebar-toggle');
    waitUntil($page, "! document.body.hasAttribute('data-sidebar-collapsed')");
    waitUntil($page, "document.querySelector('[data-test=\"sidebar\"]').getBoundingClientRect().width === 250");

    $page->assertNoJavaScriptErrors();
});

it('opens and closes the off-canvas sidebar on mobile', function () {
    $sidebar = "document.querySelector('[data-test=\"sidebar\"]')";

    $page = visit('/_ui')->resize(390, 844);

    waitUntil($page, "{$sidebar}.getBoundingClientRect().right <= 0");

    $page->click('@sidebar-toggle');
    waitUntil($page, "{$sidebar}.hasAttribute('data-open') && {$sidebar}.getBoundingClientRect().left === 0");
    waitUntil($page, "document.querySelector('[data-test=\"sidebar-backdrop\"]').checkVisibility()");

    $page->script("() => document.querySelector('[data-test=\"sidebar-backdrop\"]').click()");
    waitUntil($page, "! {$sidebar}.hasAttribute('data-open') && {$sidebar}.getBoundingClientRect().right <= 0");

    $page->assertNoJavaScriptErrors();
});
