<?php

use App\Constants\Etat;
use App\Constants\Status;
use App\Models\Client;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Notification;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;

/*
 * Access comes from permissions, not role names (App\Support\RolePresets): these users have no
 * role, only permissions, and get the access a role used to give.
 */

uses(RefreshDatabase::class);

/**
 * @param  list<string>  $permissions
 */
function userWithPermissions(array $permissions): User
{
    $user = User::factory()->create(['active' => true]);
    $user->givePermissionTo(collect($permissions)->map(fn (string $name) => Permission::findOrCreate($name, 'admin')));

    return $user;
}

beforeEach(function () {
    useFakeViteServer();
    $this->client = Client::factory()->create()->refresh();
});

it('lets a technician by permission work on their own tickets only', function () {
    $technician = userWithPermissions(['ticket.work', 'diagnostic.assigned.browse', 'diagnostic.edit', 'reparations.edit']);
    $colleague = userWithPermissions(['ticket.work']);
    $own = insertTicket($this->client->id, '1042', ['etat' => Etat::REPARABLE, 'status' => Status::EN_COURS_DE_DIAGNOSTIC, 'user_id' => $technician->id]);
    $other = insertTicket($this->client->id, '1043', ['etat' => Etat::REPARABLE, 'status' => Status::EN_COURS_DE_DIAGNOSTIC, 'user_id' => $colleague->id]);

    expect($technician->worksOnTickets())->toBeTrue()
        ->and($technician->writesDiagnosticOf($own))->toBeTrue()
        ->and($technician->writesDiagnosticOf($other))->toBeFalse()
        ->and($technician->repairs($own))->toBeTrue()
        ->and($technician->repairs($other))->toBeFalse();

    $this->actingAs($technician)
        ->get(route('admin:diagnostic.index'))
        ->assertOk()
        ->assertSee('>1042<', false)
        ->assertDontSee('>1043<', false);
});

it('lets diagnostic.manage_assigned act on every technician’s tickets, with the technician column', function () {
    $lead = userWithPermissions(['diagnostic.manage_assigned', 'reparations.manage_assigned']);
    $colleague = userWithPermissions(['ticket.work']);
    $ticket = insertTicket($this->client->id, '1043', ['etat' => Etat::REPARABLE, 'status' => Status::EN_COURS_DE_DIAGNOSTIC, 'user_id' => $colleague->id]);

    expect($lead->writesDiagnosticOf($ticket))->toBeTrue()
        ->and($lead->repairs($ticket))->toBeTrue()
        ->and($lead->worksOnTickets())->toBeFalse();

    $this->actingAs($lead)
        ->get(route('admin:diagnostic.index'))
        ->assertOk()
        ->assertSee('>1043<', false)
        ->assertSee('Technicien');
});

it('does not make a SuperAdmin a technician, though every check passes', function () {
    $superAdmin = User::factory()->create();
    $superAdmin->assignRole(Role::findOrCreate('SuperAdmin', 'admin'));
    $ticket = insertTicket($this->client->id, '1044', ['etat' => Etat::REPARABLE, 'status' => Status::EN_COURS_DE_DIAGNOSTIC, 'user_id' => null]);

    expect($superAdmin->can('ticket.work'))->toBeTrue()
        ->and($superAdmin->worksOnTickets())->toBeFalse()
        ->and($superAdmin->writesDiagnosticOf($ticket))->toBeFalse();
});

it('shows the admin board to diagnostic.browse and nothing without a diagnostic permission', function () {
    $this->actingAs(userWithPermissions(['diagnostic.browse']))
        ->withViewErrors([])
        ->get(route('admin:diagnostic.index'))
        ->assertOk()
        ->assertSee('En attente de devis');

    $this->actingAs(userWithPermissions([]))
        ->get(route('admin:diagnostic.index'))
        ->assertForbidden();
});

it('gates the deliveries by the delivery permissions', function () {
    $ticket = insertTicket($this->client->id, '1045', ['etat' => Etat::REPARABLE, 'status' => Status::PRET_A_ETRE_LIVRE, 'user_id' => userWithPermissions(['ticket.work'])->id]);

    $this->actingAs(userWithPermissions([]))->get(route('admin:tickets.livrable'))->assertForbidden();
    $this->actingAs(userWithPermissions(['ticket.delivery.browse']))
        ->post(route('admin:tickets.livrablePostAdmin'), ['ticket' => $ticket->uuid])
        ->assertForbidden();
    $this->actingAs(userWithPermissions(['ticket.delivery.browse_all', 'ticket.delivery.admin_confirm']))
        ->post(route('admin:tickets.livrablePostAdmin'), ['ticket' => $ticket->uuid])
        ->assertRedirect();

    expect((bool) $ticket->fresh()->livrable)->toBeTrue();
});

it('lets only account.password.reset ask for a password reset link', function () {
    $technician = User::factory()->create(['email' => 'tech@casamaintenance.test']);
    $technician->assignRole(Role::findOrCreate('Technicien', 'admin'));
    $assistant = User::factory()->create(['email' => 'assistante@casamaintenance.test']);
    $assistant->assignRole(Role::findOrCreate('ASSISTANTE DIRECTEUR', 'admin'));

    $refusal = "vous n'avez pas l'autorisation de résiliez votre mot de pass contacter l'administration en cas d'urgence";

    $this->post(route('forgotpasswordPost'), ['email' => 'tech@casamaintenance.test'])->assertSessionHasErrors();
    expect(session('errors')->first())->toBe($refusal);

    Notification::fake();
    $this->post(route('forgotpasswordPost'), ['email' => 'assistante@casamaintenance.test']);
    expect(session('errors')?->first())->not->toBe($refusal);
});
