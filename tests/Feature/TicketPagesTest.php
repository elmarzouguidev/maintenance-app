<?php

use App\Constants\Etat;
use App\Constants\Status;
use App\Models\Client;
use App\Models\Ticket;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Spatie\Permission\Models\Role;

/*
 * K2: the ticket pages (theme/pages/Ticket) on the UI kit layout, without jQuery, DataTables
 * or Bootstrap.
 */

uses(RefreshDatabase::class);

beforeEach(function () {
    useFakeViteServer();

    $this->user = User::factory()->create();
    $this->user->assignRole(Role::findOrCreate('SuperAdmin', 'admin'));
    $this->actingAs($this->user);

    $this->client = Client::factory()->create(['entreprise' => 'Atlas Services'])->refresh();
});

/**
 * A ticket inserted directly: TicketFactory downloads a photo after creating, and the model
 * numbers tickets from the settings.
 *
 * @param  array<string, mixed>  $attributes
 */
function insertTicket(int $clientId, string $code, array $attributes = []): Ticket
{
    $id = DB::table('tickets')->insertGetId([
        'uuid' => (string) Str::uuid(), 'code' => $code, 'article' => 'Imprimante laser', 'description' => 'Ne démarre plus', 'client_id' => $clientId,
        'etat' => Etat::NON_DIAGNOSTIQUER, 'status' => Status::NON_TRAITE, 'created_at' => '2026-11-20 09:00:00', 'updated_at' => '2026-11-20 09:00:00',
        ...$attributes,
    ]);

    return Ticket::findOrFail($id);
}

/**
 * The statuses lookup rows with the ids App\Constants\Status uses (a seeder run inside a rolled
 * back test would number them past 13: MySQL keeps the auto-increment).
 */
function insertTicketStatuses(): void
{
    DB::table('statuses')->insert(collect(Status::TICKET_STATUS)->map(fn (int $id, string $slug): array => ['id' => $id, 'uuid' => (string) Str::uuid(), 'name' => __('status.statuses.'.$id), 'slug' => $slug])->values()->all());
}

it('lists the new tickets on the kit layout', function () {
    $new = insertTicket($this->client->id, '1042');
    insertTicket($this->client->id, '1043', ['etat' => Etat::REPARABLE, 'status' => Status::A_REPARER]);

    $this->get(route('admin:tickets.list'))
        ->assertOk()
        ->assertSee('<title>Nouveaux tickets - CASAMAINTENANCE</title>', false)
        ->assertDontSee('dataTables', false)
        ->assertSee('href="'.$new->url.'"', false)
        ->assertSee('Non traité')
        ->assertDontSee('1043');
});

it('lists all the tickets with their state', function () {
    insertTicket($this->client->id, '1043', ['etat' => Etat::REPARABLE, 'status' => Status::A_REPARER]);

    $this->get(route('admin:tickets.list.old'))
        ->assertOk()
        ->assertSee('Tous les tickets')
        ->assertSee('1043')
        ->assertSee('Réparable');
});

it('filters the tickets by status and by d-m-Y dates', function () {
    insertTicket($this->client->id, '1042');
    insertTicket($this->client->id, '1043', ['status' => Status::A_REPARER, 'created_at' => '2026-10-01 09:00:00']);

    $this->get(route('admin:tickets.list', ['appFilter' => ['GetStatus' => Status::NON_TRAITE]]))
        ->assertOk()
        ->assertSee('1042')
        ->assertDontSee('>1043<', false);

    $this->get(route('admin:tickets.list', ['appFilter' => ['GetStartDate' => '15-11-2026', 'GetEndDate' => '30-11-2026']]))
        ->assertOk()
        ->assertSee('1042')
        ->assertDontSee('>1043<', false)
        ->assertSee("datePicker('2026-11-15'", false);
});

it('renders the new ticket form with the returned tickets and the client dialog', function () {
    insertTicket($this->client->id, '1042');

    $this->withViewErrors([])
        ->get(route('admin:tickets.create'))
        ->assertOk()
        ->assertSee('<title>Nouveau ticket - CASAMAINTENANCE</title>', false)
        ->assertSee('1042 · Imprimante laser')
        ->assertSee('<dialog id="create-client"', false)
        ->assertDontSee('select2(', false);
});

it('creates a ticket with its photo', function () {
    Storage::fake('public');
    insertTicketStatuses();
    insertTicket($this->client->id, '1042');

    $this->post(route('admin:tickets.createPost'), [
        'article' => 'Écran Dell', 'description' => 'Rayures', 'client' => $this->client->id,
        'photo' => UploadedFile::fake()->image('ecran.jpg', 40, 40),
    ])->assertRedirect(route('admin:tickets.list'))->assertSessionHasNoErrors();

    $ticket = Ticket::latest('id')->first();
    expect($ticket->article)->toBe('Écran Dell')
        ->and($ticket->getMedia('tickets-images'))->toHaveCount(1)
        ->and($ticket->statuses()->count())->toBe(1);
});

it('renders the ticket overview with its documents', function () {
    $ticket = insertTicket($this->client->id, '1042', ['etat' => Etat::REPARABLE, 'status' => Status::A_REPARER]);

    $this->get($ticket->url)
        ->assertOk()
        ->assertSee('<title>Ticket 1042 - CASAMAINTENANCE</title>', false)
        ->assertSee('Imprimante laser')
        ->assertSee('Ne démarre plus')
        ->assertSee('Réparable')
        ->assertSee('href="'.route('admin:tickets.report.generate', $ticket->uuid).'"', false)
        ->assertDontSee('magnific-popup', false);
});

it('renders the photos page with the upload form', function () {
    $ticket = insertTicket($this->client->id, '1042');

    $this->withViewErrors([])
        ->get($ticket->media_url)
        ->assertOk()
        ->assertSee('Photos du ticket 1042')
        ->assertSee('action="'.route('admin:tickets.attachements', $ticket->uuid).'"', false)
        ->assertSee('Aucune photo');
});

it('shows the history of the ticket from its own page', function () {
    insertTicketStatuses();
    $ticket = insertTicket($this->client->id, '1042');
    $ticket->statuses()->attach(Status::NON_TRAITE, ['user_id' => $this->user->id, 'start_at' => '2026-11-20 09:00:00', 'description' => 'Ticket ajouté à l’accueil']);

    $this->get($ticket->historical_url)
        ->assertOk()
        ->assertSee('Historique du ticket 1042')
        ->assertSee('Ticket ajouté à l’accueil');
});

it('renders the edit form with the rich text description and the reassignment', function () {
    $ticket = insertTicket($this->client->id, '1042', ['description' => '<p>Ne démarre plus</p>']);
    $technician = User::factory()->create(['nom' => 'Amrani', 'prenom' => 'Youssef', 'active' => true]);
    $technician->assignRole(Role::findOrCreate('Technicien', 'admin'));

    $this->withViewErrors([])
        ->get($ticket->edit)
        ->assertOk()
        ->assertSee('Modifier le ticket 1042')
        ->assertSee('assets/libs/tinymce/tinymce.min.js', false)
        ->assertSee('&lt;p&gt;Ne démarre plus&lt;/p&gt;', false)
        ->assertSee('id="reassignment"', false)
        ->assertSee($technician->full_name)
        ->assertDontSee('sweetalert2', false);
});

it('updates a ticket with its d-m-Y entry date', function () {
    $ticket = insertTicket($this->client->id, '1042');

    $this->put($ticket->update_url, [
        'article' => 'Imprimante HP', 'description' => '<p>Bourrage papier</p>', 'client_id' => $this->client->id, 'created_at' => '18-11-2026',
    ])->assertRedirect($ticket->edit)->assertSessionHasNoErrors();

    $ticket->refresh();
    expect($ticket->article)->toBe('Imprimante HP')
        ->and($ticket->created_at->format('Y-m-d'))->toBe('2026-11-18');
});

it('lets an admin create the estimate and record the answer from the processing page', function () {
    $ticket = insertTicket($this->client->id, '1042', ['etat' => Etat::REPARABLE, 'status' => Status::EN_ATTENTE_DE_DEVIS]);

    $this->withViewErrors([])
        ->get($ticket->diagnose_url)
        ->assertOk()
        ->assertSee('Traitement du ticket 1042')
        ->assertSee('href="'.route('commercial:estimates.create.ticket', $ticket->uuid).'"', false)
        ->assertSee('action="'.route('admin:tickets.diagnose.send-confirm', $ticket->uuid).'"', false)
        ->assertDontSee('sweetalert2', false);
});

it('lets the technician write and send the diagnostic report', function () {
    insertTicketStatuses();
    $technician = User::factory()->create();
    $technician->assignRole(Role::findOrCreate('Technicien', 'admin'));
    $ticket = insertTicket($this->client->id, '1042');

    $this->actingAs($technician)
        ->withViewErrors([])
        ->get($ticket->diagnose_url)
        ->assertOk()
        ->assertSee('name="sendreport" value="yessendit"', false);

    expect($ticket->fresh()->user_id)->toBe($technician->id);

    $this->actingAs($technician)
        ->post($ticket->diagnose_url, ['type' => 'diagnostique', 'etat' => Etat::REPARABLE, 'content' => '<p>Carte mère à remplacer</p>', 'sendreport' => 'yessendit'])
        ->assertRedirect()
        ->assertSessionHasNoErrors();

    expect((int) $ticket->fresh()->status)->toBe(Status::EN_ATTENTE_DE_DEVIS)
        ->and((bool) $ticket->diagnoseReports()->first()->close_report)->toBeTrue();
});

it('lets an admin release a ready ticket and the reception confirm its delivery', function () {
    insertTicketStatuses();
    $ticket = insertTicket($this->client->id, '1042', ['etat' => Etat::REPARABLE, 'status' => Status::PRET_A_ETRE_LIVRE]);

    $this->get(route('admin:tickets.livrable'))
        ->assertOk()
        ->assertSee('<title>Tickets à livrer - CASAMAINTENANCE</title>', false)
        ->assertSee('Autoriser la livraison')
        ->assertDontSee('dataTables', false);

    $this->post(route('admin:tickets.livrablePostAdmin'), ['ticket' => $ticket->uuid])->assertRedirect();

    $reception = User::factory()->create();
    $reception->assignRole(Role::findOrCreate('Reception', 'admin'));

    $this->actingAs($reception)
        ->withViewErrors([])
        ->get(route('admin:tickets.livrable'))
        ->assertOk()
        ->assertSee('<dialog id="confirm-delivery"', false)
        ->assertSee('Confirmer la livraison');

    $this->actingAs($reception)
        ->post(route('admin:tickets.livrablePost'), ['ticket' => $ticket->uuid, 'date_end' => '2026-11-25', 'mode' => 'parclient', 'info_client' => 'M. Idrissi'])
        ->assertRedirect()
        ->assertSessionHasNoErrors();

    expect((int) $ticket->fresh()->status)->toBe(Status::LIVRE)
        ->and($ticket->delivery()->count())->toBe(1);
});

it('lists the delivered tickets to invoice with a link to the invoice form', function () {
    $ticket = insertTicket($this->client->id, '1042', ['etat' => Etat::REPARABLE, 'status' => Status::LIVRE, 'can_invoiced' => true]);

    $this->get(route('admin:tickets.invoiceable'))
        ->assertOk()
        ->assertSee('Tickets à facturer')
        ->assertSee('href="'.route('commercial:invoices.create', ['ticket' => $ticket->uuid]).'"', false);

    DB::table('companies')->insert(['id' => 1, 'uuid' => (string) Str::uuid(), 'name' => 'CASA MAINTENANCE', 'addresse' => 'Casablanca', 'ice' => '001234567890123']);

    $this->withViewErrors([])
        ->get(route('commercial:invoices.create', ['ticket' => $ticket->uuid]))
        ->assertOk()
        ->assertSee("selectedTickets: JSON.parse('[\\u0022{$ticket->id}\\u0022]')", false);
});
