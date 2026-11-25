<?php

use App\Constants\Etat;
use App\Constants\Status;
use App\Models\Client;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Spatie\Permission\Models\Role;

/*
 * K2: the diagnostic boards (theme/pages/Diagnostic) on the UI kit layout, without jQuery,
 * Select2, Bootstrap tabs or DataTables. Tickets come from insertTicket() (TicketPagesTest).
 */

uses(RefreshDatabase::class);

beforeEach(function () {
    useFakeViteServer();

    $this->technician = User::factory()->create(['nom' => 'Amrani', 'prenom' => 'Youssef', 'active' => true]);
    $this->technician->assignRole(Role::findOrCreate('Technicien', 'admin'));
    $this->client = Client::factory()->create(['entreprise' => 'Atlas Services'])->refresh();
});

it('shows the admins the diagnosed tickets by step and filters them by state', function () {
    $admin = User::factory()->create();
    $admin->assignRole(Role::findOrCreate('SuperAdmin', 'admin'));
    insertTicket($this->client->id, '1042', ['etat' => Etat::REPARABLE, 'status' => Status::EN_ATTENTE_DE_DEVIS, 'user_id' => $this->technician->id]);
    insertTicket($this->client->id, '1043', ['etat' => Etat::NON_REPARABLE, 'status' => Status::RETOUR_NON_REPARABLE, 'user_id' => $this->technician->id]);

    $this->actingAs($admin)
        ->get(route('admin:diagnostic.index'))
        ->assertOk()
        ->assertSee('<title>Diagnostics - CASAMAINTENANCE</title>', false)
        ->assertSee('En attente de devis')
        ->assertSee('1042')
        ->assertSee('1043')
        ->assertSee('Youssef')
        ->assertDontSee('select2(', false);

    $this->actingAs($admin)
        ->get(route('admin:diagnostic.index', ['appFilter' => ['GetEtat' => Etat::REPARABLE]]))
        ->assertOk()
        ->assertSee('>1042<', false)
        ->assertDontSee('>1043<', false);
});

it('shows a technician their own tickets by step', function () {
    insertTicket($this->client->id, '1042', ['etat' => Etat::NON_DIAGNOSTIQUER, 'status' => Status::EN_COURS_DE_DIAGNOSTIC, 'user_id' => $this->technician->id]);
    $ticket = insertTicket($this->client->id, '1044', ['etat' => Etat::REPARABLE, 'status' => Status::A_REPARER, 'user_id' => $this->technician->id]);

    $this->actingAs($this->technician)
        ->get(route('admin:diagnostic.index'))
        ->assertOk()
        ->assertSeeText('Diagnostics ouverts (1)')
        ->assertSeeText('À réparer (1)')
        ->assertSee('href="'.$ticket->repear_url.'"', false);
});

it('lets the technician write the repair report and close the repair', function () {
    insertTicketStatuses();
    $ticket = insertTicket($this->client->id, '1044', ['etat' => Etat::REPARABLE, 'status' => Status::A_REPARER, 'user_id' => $this->technician->id]);

    $this->actingAs($this->technician)
        ->withViewErrors([])
        ->get($ticket->repear_url)
        ->assertOk()
        ->assertSee('Réparation du ticket 1044')
        ->assertSee('name="reparation_done" value="reparation_done"', false)
        ->assertDontSee('sweetalert2', false);

    expect((int) $ticket->fresh()->status)->toBe(Status::EN_COURS_DE_REPARATION);

    $this->actingAs($this->technician)
        ->post(route('admin:reparations.store', $ticket->uuid), ['content' => '<p>Carte mère remplacée</p>', 'reparation_done' => 'reparation_done'])
        ->assertRedirect(route('admin:diagnostic.index'));

    expect((int) $ticket->fresh()->status)->toBe(Status::PRET_A_ETRE_LIVRE)
        ->and((bool) $ticket->fresh()->can_invoiced)->toBeTrue();
});
