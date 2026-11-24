<?php

use App\Constants\Status;
use App\Exports\WarrantiesExport;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use Maatwebsite\Excel\Facades\Excel;

/*
 * K1: the warranties page (theme/pages/Warranty) runs on the UI kit layout with a server-side
 * table, search and Excel/PDF exports, replacing the DataTables setup of B5.
 */

uses(RefreshDatabase::class);

beforeEach(function () {
    useFakeViteServer();

    $this->actingAs(User::factory()->create());
});

/**
 * A ticket and its warranty, inserted directly: the ticket and warranty factories cannot be
 * used offline (BL-31, BL-53).
 */
function makeWarranty(string $ticketCode, string $startAt = '2026-11-01', string $endAt = '2027-05-01'): void
{
    $ticketId = DB::table('tickets')->insertGetId([
        'uuid' => (string) Str::uuid(),
        'code' => $ticketCode,
        'article' => 'Imprimante HP 4200',
        'description' => 'Ne démarre plus',
        'status' => Status::NON_TRAITE,
    ]);

    DB::table('warranties')->insert([
        'uuid' => (string) Str::uuid(),
        'ticket_id' => $ticketId,
        'start_at' => $startAt.' 00:00:00',
        'end_at' => $endAt.' 00:00:00',
    ]);
}

it('renders the warranties table on the kit layout, without DataTables', function () {
    makeWarranty('TCK-0042');

    $this->get(route('admin:warranty.index'))
        ->assertOk()
        ->assertSee('<title>Garanties - CASAMAINTENANCE</title>', false)
        ->assertSee('http://vite.test/resources/css/ui-kit.css', false)
        ->assertDontSee('http://vite.test/resources/css/app.css', false)
        ->assertDontSee('legacy', false)
        ->assertDontSee('dataTables', false)
        ->assertSee('TCK-0042')
        ->assertSee('Non traité')
        ->assertSee('01/11/2026')
        ->assertSee('01/05/2027')
        ->assertSee('href="'.route('admin:warranty.export', ['format' => 'xlsx']).'"', false)
        ->assertSee('href="'.route('admin:warranty.export', ['format' => 'pdf']).'"', false);
});

it('renders the add-warranty modal closed, with its form (BL-40)', function () {
    $this->get(route('admin:warranty.index'))
        ->assertOk()
        ->assertSee('<dialog id="addWarranty"', false)
        ->assertSee('action="'.route('admin:warranty.store').'"', false)
        ->assertSee('name="start_at"', false)
        ->assertSee('name="end_at"', false);
});

it('filters the warranties by ticket code and keeps the search in the exports', function () {
    makeWarranty('TCK-0042');
    makeWarranty('TCK-0099');

    $this->get(route('admin:warranty.index', ['q' => '0042']))
        ->assertOk()
        ->assertSee('TCK-0042')
        ->assertDontSee('TCK-0099')
        ->assertSee('value="0042"', false)
        ->assertSee('href="'.route('admin:warranty.export', ['format' => 'xlsx', 'q' => '0042']).'"', false);
});

it('shows the no-result state when the search matches nothing', function () {
    makeWarranty('TCK-0042');

    $this->get(route('admin:warranty.index', ['q' => 'ZZZ']))
        ->assertOk()
        ->assertSee('Aucun résultat')
        ->assertSee('Aucune garantie ne correspond au ticket « ZZZ ».')
        ->assertDontSee('admin-table', false);
});

it('shows the empty state when there is no warranty', function () {
    $this->get(route('admin:warranty.index'))
        ->assertOk()
        ->assertSee('Aucune garantie')
        ->assertDontSee('admin-table', false);
});

it('lists the warranties newest first, thirty per page', function () {
    foreach (range(1, 31) as $day) {
        makeWarranty(sprintf('TCK-%04d', $day), sprintf('2026-10-%02d', $day));
    }

    $this->get(route('admin:warranty.index'))
        ->assertOk()
        ->assertSeeInOrder(['TCK-0031', 'TCK-0030'])
        ->assertDontSee('TCK-0001')
        ->assertSee('href="'.route('admin:warranty.index', ['page' => 2]).'"', false);
});

it('downloads the listed warranties as an Excel file, with the search', function () {
    Excel::fake();

    $this->get(route('admin:warranty.export', ['format' => 'xlsx', 'q' => '0042']))
        ->assertOk();

    Excel::assertDownloaded('garanties.xlsx', fn (WarrantiesExport $export): bool => $export->search === '0042');
});

it('exports one row per matching warranty, with the list\'s formats', function () {
    makeWarranty('TCK-0042');
    makeWarranty('TCK-0099');
    $export = new WarrantiesExport('0042');

    $rows = $export->query()->get()->map(fn ($warranty): array => $export->map($warranty))->all();

    expect($export->headings())->toBe(['Ticket', 'Statut du ticket', 'Date de début', 'Date de fin'])
        ->and($rows)->toBe([['TCK-0042', 'Non traité', '01/11/2026', '01/05/2027']]);
});

it('downloads the list as a PDF written by mPDF', function () {
    makeWarranty('TCK-0042');

    $response = $this->get(route('admin:warranty.export', ['format' => 'pdf']));

    $response->assertOk()
        ->assertDownload('garanties.pdf');
    expect($response->baseResponse->getFile()->getContent())->toStartWith('%PDF');
});

it('rejects an unknown export format with a 404', function () {
    $this->get('/app/warranties/export/csv')->assertNotFound();
});
