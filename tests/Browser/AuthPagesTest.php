<?php

use Illuminate\Support\Facades\Vite;

/*
 * B2: real-browser checks of the Tailwind guest pages.
 * Run with `composer test:browser` (builds the assets first).
 */

beforeEach(function () {
    // Use the production build, never a running Vite dev server (public/hot).
    Vite::useHotFile(storage_path('framework/testing/vite.hot'));
});

it('shows and hides the password on the login page', function () {
    $password = "document.querySelector('#password')";

    $page = visit(route('admin:auth:login'));

    $page->assertSee('Se connecter')
        ->assertNoJavaScriptErrors()
        ->type('password', 'secret-value');

    waitUntil($page, "{$password}.type === 'password'");

    $page->click('[aria-label="Afficher le mot de passe"]');
    waitUntil($page, "{$password}.type === 'text' && {$password}.value === 'secret-value'");

    $page->click('[aria-label="Masquer le mot de passe"]');
    waitUntil($page, "{$password}.type === 'password'");

    $page->assertNoJavaScriptErrors();
});

it('shows the error and keeps the email after a failed login', function () {
    $page = visit(route('admin:auth:login'));

    $page->type('email', 'inconnu@example.com')
        ->type('password', 'mauvais-mot-de-passe')
        ->click('button[type="submit"]')
        ->waitForText(__('auth.failed'))
        ->assertValue('email', 'inconnu@example.com')
        ->assertAttribute('#email', 'aria-invalid', 'true')
        ->assertNoJavaScriptErrors();
});

it('loads the forgot password page without JavaScript errors', function () {
    visit(route('forgotpassword'))
        ->assertSee('Reset Password')
        ->assertVisible('#useremail')
        ->assertNoJavaScriptErrors();
});
