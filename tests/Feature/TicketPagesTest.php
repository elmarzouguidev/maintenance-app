<?php

use App\Constants\Etat;
use App\Constants\Status;
use App\Models\Client;
use App\Models\Ticket;
use App\Models\User;
use Database\Seeders\StatusSeeder;
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
    $this->seed(StatusSeeder::class);
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
