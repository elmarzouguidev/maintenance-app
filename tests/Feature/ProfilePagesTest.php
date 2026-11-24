<?php

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Hash;
use Spatie\Permission\Models\Role;

/*
 * K2: my profile and my settings (theme/pages/Profile) on the UI kit layout.
 */

uses(RefreshDatabase::class);

beforeEach(function () {
    useFakeViteServer();
});

function profileUser(array $roles = []): User
{
    $user = User::factory()->create([
        'prenom' => 'Amal',
        'nom' => 'Benali',
        'email' => 'amal@example.com',
        'telephone' => '0612345678',
        'password' => Hash::make('ancien-secret'),
    ]);

    foreach ($roles as $role) {
        $user->assignRole(Role::findOrCreate($role, 'admin'));
    }

    return $user;
}

it('shows the signed-in user\'s details instead of the demo profile', function () {
    $this->actingAs(profileUser(['Reception']))
        ->get(route('admin:profile.index'))
        ->assertOk()
        ->assertSee('<title>Mon profil - CASAMAINTENANCE</title>', false)
        ->assertSee('http://vite.test/resources/css/ui-kit.css', false)
        ->assertSeeInOrder(['Amal Benali', 'amal@example.com', 'Amal', 'Benali', '0612345678', 'Reception'])
        ->assertSee('href="'.route('admin:profile.settings').'"', false)
        ->assertDontSee('Cynthia');
});

it('shows the information form with the user\'s values and no company tab to a non-admin', function () {
    $this->actingAs(profileUser())
        ->get(route('admin:profile.settings', ['tab' => 'company']))
        ->assertOk()
        ->assertSee('action="'.route('admin:profile.settings.update').'"', false)
        ->assertSee('value="Benali"', false)
        ->assertSee('value="amal@example.com"', false)
        ->assertDontSee('Sections des paramètres')
        ->assertDontSee('action="'.route('admin:profile.settings.update.company').'"', false);
});

it('shows the company tab with the logo field to an admin', function () {
    $this->actingAs(profileUser(['Admin']))
        ->get(route('admin:profile.settings', ['tab' => 'company']))
        ->assertOk()
        ->assertSee('Sections des paramètres')
        ->assertSee('action="'.route('admin:profile.settings.update.company').'"', false)
        ->assertSee('type="file" name="logo"', false);
});

it('updates the user\'s information', function () {
    $user = profileUser();

    $this->actingAs($user)
        ->followingRedirects()
        ->from(route('admin:profile.settings'))
        ->post(route('admin:profile.settings.update'), ['prenom' => 'Amina', 'nom' => 'Benali', 'email' => 'amina@example.com', 'telephone' => '0698765432'])
        ->assertOk()
        ->assertSee('Profile Updated');

    expect($user->fresh())
        ->prenom->toBe('Amina')
        ->email->toBe('amina@example.com')
        ->telephone->toBe('0698765432');
});

it('shows the validation error next to the field', function () {
    $this->actingAs(profileUser())
        ->followingRedirects()
        ->from(route('admin:profile.settings'))
        ->post(route('admin:profile.settings.update'), ['prenom' => 'Amal', 'nom' => '', 'email' => 'amal@example.com'])
        ->assertOk()
        ->assertSee('aria-invalid="true"', false)
        ->assertSee('id="nom-error"', false);
});

it('changes the password when the current one is given', function () {
    $user = profileUser();

    $this->actingAs($user)
        ->post(route('admin:profile.settings.update'), [
            'prenom' => 'Amal', 'nom' => 'Benali', 'email' => 'amal@example.com',
            'oldpassword' => 'ancien-secret', 'new_password' => 'nouveau-secret', 'new_confirm_password' => 'nouveau-secret',
        ])
        ->assertRedirect();

    expect(Hash::check('nouveau-secret', $user->fresh()->password))->toBeTrue();
});
