<?php

use Illuminate\Foundation\Testing\RefreshDatabase;

/*
 * The guest pages (login, forgot / reset password) run on the UI kit guest layout (K0); the
 * maintenance page is a CSS-only document on the kit bundle.
 */

uses(RefreshDatabase::class);

beforeEach(fn () => useFakeViteServer());

it('renders the login page on the kit guest layout without jQuery, Bootstrap or Livewire', function () {
    $this->get(route('admin:auth:login'))
        ->assertOk()
        ->assertSee('<title>Connexion - CASAMAINTENANCE</title>', false)
        ->assertSee('http://vite.test/resources/css/ui-kit.css', false)
        ->assertDontSee('http://vite.test/resources/css/app.css', false)
        ->assertSee('http://vite.test/resources/js/app.js', false)
        ->assertDontSee('data-update-uri=', false)
        ->assertSee('Se connecter')
        ->assertSee('action="'.route('admin:auth:loginPost').'"', false)
        ->assertSee('name="email"', false)
        ->assertSee('name="password"', false)
        ->assertSee('name="remember"', false)
        ->assertSee(route('forgotpassword'), false)
        ->assertDontSee('legacy', false)
        ->assertDontSee('bootstrap.bundle', false)
        ->assertDontSee('jquery', false);
});

it('shows the login error next to the email field and keeps the email', function () {
    $this->followingRedirects()
        ->from(route('admin:auth:login'))
        ->post(route('admin:auth:loginPost'), ['email' => 'inconnu@example.com', 'password' => 'mauvais'])
        ->assertOk()
        ->assertSee(__('auth.failed'))
        ->assertSee('aria-invalid="true"', false)
        ->assertSee('value="inconnu@example.com"', false);
});

it('renders the forgot password page with the reset link status', function () {
    $this->withSession(['status' => 'Nous vous avons envoyé le lien de réinitialisation par e-mail.'])
        ->get(route('forgotpassword'))
        ->assertOk()
        ->assertSee('Mot de passe oublié')
        ->assertSee('action="'.route('forgotpasswordPost').'"', false)
        ->assertSee('id="useremail"', false)
        ->assertSee('Nous vous avons envoyé le lien de réinitialisation par e-mail.')
        ->assertDontSee('legacy', false);
});

it('renders the reset password page with the token and the email', function () {
    $this->get(route('password.reset', ['token' => 'abc123', 'email' => 'agent@example.com']))
        ->assertOk()
        ->assertSee('name="token" value="abc123"', false)
        ->assertSee('value="agent@example.com"', false)
        ->assertSee('readonly', false)
        ->assertSee('name="password_confirmation"', false)
        ->assertDontSee('legacy', false);
});

it('renders the maintenance page on the kit, with CSS only', function () {
    $this->view('errors.503')
        ->assertSee('Maintenance en cours')
        ->assertSee('<title>Maintenance en cours - CASAMAINTENANCE</title>', false)
        ->assertSee('http://vite.test/resources/css/ui-kit.css', false)
        ->assertDontSee('resources/js/app.js', false)
        ->assertDontSee('livewire', false)
        ->assertDontSee('legacy', false);
});
