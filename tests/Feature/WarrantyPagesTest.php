<?php

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

/*
 * B5: the warranties page (theme/pages/Warranty) runs on the Tailwind layout with the
 * Tailwind DataTables setup (no Bootstrap 4 integration).
 */

uses(RefreshDatabase::class);

beforeEach(function () {
    useFakeViteServer();

    $this->actingAs(User::factory()->create());
});

it('renders the warranties table with the Tailwind DataTables setup', function () {
    $ticketId = DB::table('tickets')->insertGetId([
        'uuid' => (string) Str::uuid(),
        'code' => 'TCK-0042',
        'article' => 'Imprimante HP 4200',
        'description' => 'Ne démarre plus',
    ]);

    DB::table('warranties')->insert([
        'uuid' => (string) Str::uuid(),
        'ticket_id' => $ticketId,
        'start_at' => '2026-11-01 00:00:00',
        'end_at' => '2027-05-01 00:00:00',
    ]);

    $this->get(route('admin:warranty.index'))
        ->assertOk()
        ->assertSee('http://vite.test/resources/css/app.css', false)
        ->assertDontSee('legacy', false)
        ->assertDontSee('datatables.net-bs4', false)
        ->assertDontSee('bootstrap4', false)
        ->assertSee('id="datatable-buttons"', false)
        ->assertSee('dt-responsive', false)
        ->assertSee('assets/libs/datatables.net/js/jquery.dataTables.min.js', false)
        ->assertSee('js/pages/tailwind/datatables.init.js', false)
        ->assertSee('TCK-0042')
        ->assertSee('01-11-2026')
        ->assertSee('01-05-2027');
});

it('renders the add-warranty modal hidden, with its form', function () {
    $this->get(route('admin:warranty.index'))
        ->assertOk()
        ->assertSee("name: 'addWarranty'", false)
        ->assertSee('action="'.route('admin:warranty.store').'"', false)
        ->assertSee('name="start_at"', false)
        ->assertSee('name="end_at"', false);
});
