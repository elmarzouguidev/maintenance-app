<?php

use Illuminate\Foundation\Testing\RefreshDatabase;

/*
 * B2: the guest pages (login, forgot / reset password) and the maintenance page run on Tailwind.
 */

uses(RefreshDatabase::class);

beforeEach(fn () => useFakeViteServer());

it('renders the login page on the Tailwind bundle without jQuery, Bootstrap or Livewire', function () {
    $this->get(route('admin:auth:login'))
        ->assertOk()
        ->assertSee('http://vite.test/resources/css/app.css', false)
        ->assertSee('http://vite.test/resources/js/app.js', false)
        ->assertDontSee('data-update-uri=', false)
        ->assertSee('Se connecter')
        ->assertSee('action="'.route('admin:auth:loginPost').'"', false)
        ->assertSee('name="email"', false)
        ->assertSee('name="password"', false)
        ->assertSee('x-bind:type="revealed ? \'text\' : \'password\'"', false)
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
        ->assertSee('Reset Password')
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

it('renders the maintenance page with CSS only', function () {
    $this->view('errors.503')
        ->assertSee('Maintenance MODE')
        ->assertSee('http://vite.test/resources/css/app.css', false)
        ->assertDontSee('resources/js/app.js', false)
        ->assertDontSee('livewire', false)
        ->assertDontSee('legacy', false);
});
