<?php

use App\Constants\Etat;
use App\Constants\Status;
use App\Models\Client;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\DB;
use Spatie\Permission\Models\Role;

/*
 * K2: the ticket report pages (theme/pages/TicketRapport) on the UI kit layout, without
 * DataTables or jQuery.
 */

uses(RefreshDatabase::class);

beforeEach(function () {
    useFakeViteServer();

    $this->user = User::factory()->create(['nom' => 'Amrani', 'prenom' => 'Youssef']);
    $this->user->assignRole(Role::findOrCreate('SuperAdmin', 'admin'));
    $this->actingAs($this->user);

    $client = Client::factory()->create(['entreprise' => 'Atlas Services'])->refresh();
    $this->ticket = insertTicket($client->id, '1042', ['etat' => Etat::REPARABLE, 'status' => Status::PRET_A_ETRE_LIVRE, 'user_id' => $this->user->id]);

    foreach (['diagnostique' => '<p>Carte mère HS</p>', 'reparation' => '<p>Carte mère remplacée</p>'] as $type => $content) {
        DB::table('reports')->insert(['ticket_id' => $this->ticket->id, 'user_id' => $this->user->id, 'type' => $type, 'content' => $content, 'created_at' => now(), 'updated_at' => now()]);
    }
});

it('lists the reports with their PDF', function () {
    $this->get(route('admin:rapports.index'))
        ->assertOk()
        ->assertSee('<title>Rapports - CASAMAINTENANCE</title>', false)
        ->assertSee('Réparation')
        ->assertSee('Youssef')
        ->assertSee('href="'.route('admin:rapports.report.generate', [$this->ticket->uuid, 'has_header' => true]).'"', false)
        ->assertDontSee('dataTables', false);
});

it('lists the tickets whose reports can be edited', function () {
    $this->get(route('admin:rapports.editions.index'))
        ->assertOk()
        ->assertSee('href="'.route('admin:rapports.editions.edit', $this->ticket->uuid).'"', false);
});

it('edits both reports of a ticket', function () {
    $this->withViewErrors([])
        ->get(route('admin:rapports.editions.edit', $this->ticket->uuid))
        ->assertOk()
        ->assertSee('&lt;p&gt;Carte mère HS&lt;/p&gt;', false)
        ->assertSee('assets/libs/tinymce/tinymce.min.js', false);

    $this->put(route('admin:rapports.editions.update', $this->ticket->uuid), [
        'report_diagnose' => '<p>Carte mère et alimentation HS</p>', 'report_reparation' => '<p>Les deux remplacées</p>',
    ])->assertRedirect()->assertSessionHasNoErrors();

    expect($this->ticket->diagnoseReports()->first()->content)->toBe('<p>Carte mère et alimentation HS</p>');
});
