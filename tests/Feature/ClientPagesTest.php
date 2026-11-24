<?php

use App\Exports\ClientsListExport;
use App\Models\Client;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use Maatwebsite\Excel\Facades\Excel;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;

/*
 * K2: the clients pages (theme/pages/Client) on the UI kit layout: a server-side list with
 * search and exports (replacing DataTables), the create and edit forms with the extra phones
 * and e-mails, and the client page, whose chart crashed before (BL-67).
 */

uses(RefreshDatabase::class);

beforeEach(function () {
    useFakeViteServer();

    $this->superAdmin = User::factory()->create();
    $this->superAdmin->assignRole(Role::findOrCreate('SuperAdmin', 'admin'));
});

function clientInvoice(int $clientId, string $createdAt, string $total): void
{
    $companyId = DB::table('companies')->value('id') ?? DB::table('companies')->insertGetId([
        'uuid' => (string) Str::uuid(), 'name' => 'CASA MAINTENANCE', 'addresse' => 'Casablanca', 'ice' => '000000000000000',
    ]);
    $code = Str::random(8);

    DB::table('invoices')->insert([
        'uuid' => (string) Str::uuid(), 'code' => $code, 'full_number' => 'FAC-'.$code,
        'company_id' => $companyId, 'client_id' => $clientId, 'price_total' => $total, 'created_at' => $createdAt,
    ]);
}

it('lists the clients on the kit layout, with the exports, the import and the row actions', function () {
    $client = Client::factory()->create(['entreprise' => 'Atlas Services'])->refresh();

    $this->actingAs($this->superAdmin)
        ->get(route('admin:clients.index'))
        ->assertOk()
        ->assertSee('<title>Clients - CASAMAINTENANCE</title>', false)
        ->assertSee('http://vite.test/resources/css/ui-kit.css', false)
        ->assertDontSee('dataTables', false)
        ->assertSee('Atlas Services')
        ->assertSee('href="'.route('admin:clients.export', ['format' => 'xlsx']).'"', false)
        ->assertSee('<dialog id="import-clients"', false)
        ->assertSee('action="'.route('admin:clients.import').'"', false)
        ->assertSee('href="'.$client->edit.'"', false)
        ->assertSee('action="'.route('admin:clients.delete', ['clientId' => $client->uuid]).'"', false);
});

it('hides the row actions from a user who is not Admin or SuperAdmin', function () {
    $client = Client::factory()->create()->refresh();
    $reception = User::factory()->create();
    $reception->givePermissionTo(Permission::findOrCreate('client.browse', 'admin'));

    $this->actingAs($reception)
        ->get(route('admin:clients.index'))
        ->assertOk()
        ->assertSee($client->entreprise)
        ->assertDontSee('href="'.$client->edit.'"', false);
});

it('filters the clients and keeps the search in the exports', function () {
    Client::factory()->create(['entreprise' => 'Atlas Services']);
    Client::factory()->create(['entreprise' => 'Souss Agro']);

    $this->actingAs($this->superAdmin)
        ->get(route('admin:clients.index', ['q' => 'Atlas']))
        ->assertOk()
        ->assertSee('Atlas Services')
        ->assertDontSee('Souss Agro')
        ->assertSee('href="'.route('admin:clients.export', ['format' => 'pdf', 'q' => 'Atlas']).'"', false);
});

it('lists thirty clients per page', function () {
    Client::factory()->count(31)->create();

    $this->actingAs($this->superAdmin)
        ->get(route('admin:clients.index'))
        ->assertOk()
        ->assertSee('href="'.route('admin:clients.index', ['page' => 2]).'"', false);
});

it('exports the listed clients with the search', function () {
    Excel::fake();
    Client::factory()->create(['entreprise' => 'Atlas Services', 'email' => 'atlas@example.com']);
    Client::factory()->create(['entreprise' => 'Souss Agro']);

    $this->actingAs($this->superAdmin)
        ->get(route('admin:clients.export', ['format' => 'xlsx', 'q' => 'Atlas']))
        ->assertOk();

    Excel::assertDownloaded('clients.xlsx', function (ClientsListExport $export): bool {
        $rows = $export->query()->get()->map(fn ($client): array => $export->map($client));

        return $export->search === 'Atlas' && $rows->count() === 1 && $rows[0][1] === 'Atlas Services' && $rows[0][3] === 'atlas@example.com';
    });
});

it('creates a client and opens its edit page', function () {
    DB::table('categories')->insert(['name' => 'Imprimantes', 'slug' => 'imprimantes']);

    $this->actingAs($this->superAdmin)
        ->get(route('admin:clients.create'))
        ->assertOk()
        ->assertSee('action="'.route('admin:clients.createPost').'"', false)
        ->assertSee('Imprimantes');

    $this->actingAs($this->superAdmin)
        ->followingRedirects()
        ->post(route('admin:clients.createPost'), [
            'entreprise' => 'Nour SARL', 'contact' => 'Amal Benali', 'telephone' => '0522123456', 'email' => 'nour@example.com',
            'addresse' => 'Casablanca', 'ice' => '001234567890123',
        ])
        ->assertOk()
        ->assertSee("L'ajoute a éte effectuer avec success")
        ->assertSee('Autres numéros');

    expect(Client::where('email', 'nour@example.com')->exists())->toBeTrue();
});

it('shows the extra phones and e-mails on the edit page, with their actions', function () {
    $client = Client::factory()->create()->refresh();
    $phone = $client->telephones()->create(['telephone' => '0612345678', 'type' => 'portable'])->refresh();
    $client->emails()->create(['email' => 'compta@example.com']);

    $this->actingAs($this->superAdmin)
        ->get($client->edit)
        ->assertOk()
        ->assertSee('action="'.$client->update.'"', false)
        ->assertSee('0612345678')
        ->assertSee('compta@example.com')
        ->assertSee($phone->uuid)
        ->assertSee('action="'.route('admin:client.add.phones', $client->uuid).'"', false)
        ->assertSee('action="'.route('admin:client.add.emails', $client->uuid).'"', false);
});

it('deletes an extra phone with the request the edit page sends', function () {
    $client = Client::factory()->create()->refresh();
    $phone = $client->telephones()->create(['telephone' => '0612345678', 'type' => 'portable'])->refresh();

    $this->actingAs($this->superAdmin)
        ->deleteJson(route('admin:client.delete.phone'), ['client' => $client->uuid, 'phone' => $phone->uuid])
        ->assertOk()
        ->assertJson(['success' => 'Record deleted successfully!']);

    expect($client->telephones()->count())->toBe(0);
});

it('renders the client page with its monthly invoiced total and its tickets', function () {
    $client = Client::factory()->create(['entreprise' => 'Atlas Services'])->refresh();
    $other = Client::factory()->create();
    clientInvoice($client->id, '2026-01-10 09:00:00', '1000.50');
    clientInvoice($client->id, '2026-01-20 09:00:00', '499.50');
    clientInvoice($other->id, '2026-02-10 09:00:00', '9999.00');
    DB::table('tickets')->insert(['uuid' => (string) Str::uuid(), 'code' => 'TCK-0042', 'article' => 'Imprimante', 'description' => 'Panne', 'client_id' => $client->id]);

    $this->actingAs($this->superAdmin)
        ->get($client->url)
        ->assertOk()
        ->assertSee('<title>Atlas Services - CASAMAINTENANCE</title>', false)
        ->assertViewHas('chart', fn (array $chart): bool => $chart['labels'] === ['Janvier 2026'] && $chart['values'] === [1500.0])
        ->assertSee('TCK-0042')
        ->assertSee("1\u{00A0}500,00\u{00A0}DH")
        ->assertSee('href="'.$client->edit.'"', false);
});

it('keeps the delete action as before: success message, client kept (BL-68)', function () {
    $client = Client::factory()->create()->refresh();

    $this->actingAs($this->superAdmin)
        ->followingRedirects()
        ->from(route('admin:clients.index'))
        ->delete(route('admin:clients.delete', ['clientId' => $client->uuid]))
        ->assertOk()
        ->assertSee('Le client a été supprimer avec success');

    expect(Client::whereKey($client->id)->exists())->toBeTrue();
});

it('reopens the import dialog with the error when the file is not a spreadsheet', function () {
    $this->actingAs($this->superAdmin)
        ->followingRedirects()
        ->from(route('admin:clients.index'))
        ->post(route('admin:clients.import'), ['file' => UploadedFile::fake()->image('photo.png')])
        ->assertOk()
        ->assertSee('id="file-file-error"', false)
        ->assertSee("document.getElementById('import-clients').showModal();", false);
});
