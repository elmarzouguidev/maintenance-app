<?php

use App\Jobs\Importer\CSV\CSVImporterJob;
use App\Models\User;
use Illuminate\Bus\BatchRepository;
use Illuminate\Bus\PendingBatch;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Bus;
use Spatie\Permission\Models\Role;

/*
 * K2: the CSV import page (files.importers.csv) on the UI kit, without Livewire: an upload form
 * that dispatches a batch of CSVImporterJob, and a JSON endpoint the page polls for progress.
 */

uses(RefreshDatabase::class);

beforeEach(function () {
    useFakeViteServer();
});

function csvImportSuperAdmin(): User
{
    $user = User::factory()->create();
    $user->assignRole(Role::findOrCreate('SuperAdmin', 'admin'));

    return $user;
}

it('renders the import form on the kit layout', function () {
    $this->actingAs(csvImportSuperAdmin())
        ->get(route('admin:files.importers.csv'))
        ->assertOk()
        ->assertSee('<title>Import CSV - CASAMAINTENANCE</title>', false)
        ->assertSee('http://vite.test/resources/css/ui-kit.css', false)
        ->assertDontSee('data-update-uri=', false)
        ->assertSee('action="'.route('admin:files.importers.csv.store').'"', false)
        ->assertSee('type="file" name="import_file"', false)
        ->assertDontSee('Import en cours');
});

it('forbids the page to a user who is not SuperAdmin', function () {
    $this->actingAs(User::factory()->create())
        ->get(route('admin:files.importers.csv'))
        ->assertForbidden();
});

it('rejects a file that is not a CSV', function () {
    Bus::fake();

    $this->actingAs(csvImportSuperAdmin())
        ->from(route('admin:files.importers.csv'))
        ->post(route('admin:files.importers.csv.store'), ['import_file' => UploadedFile::fake()->image('photo.png')])
        ->assertRedirect(route('admin:files.importers.csv'))
        ->assertSessionHasErrors('import_file');

    Bus::assertNothingBatched();
});

it('imports the file in one batch, one job per 1000 lines, with the header of the first line', function () {
    Bus::fake();
    $lines = ["reference,quantite\n"];
    foreach (range(1, 1500) as $number) {
        $lines[] = "REF-{$number},{$number}\n";
    }

    $response = $this->actingAs(csvImportSuperAdmin())
        ->post(route('admin:files.importers.csv.store'), ['import_file' => UploadedFile::fake()->createWithContent('ventes.csv', implode('', $lines))]);

    Bus::assertBatched(function (PendingBatch $batch): bool {
        return $batch->jobs->count() === 2
            && $batch->jobs->every(fn ($job): bool => $job instanceof CSVImporterJob && $job->header === ['reference', 'quantite'])
            && count($batch->jobs[0]->data) === 999
            && count($batch->jobs[1]->data) === 501
            && $batch->jobs[0]->data[1] === ['REF-1', '1'];
    });
    $response->assertRedirect();
    expect($response->headers->get('Location'))->toContain(route('admin:files.importers.csv').'?batch=');
});

it('shows the progress panel when a batch is followed', function () {
    $this->actingAs(csvImportSuperAdmin())
        ->get(route('admin:files.importers.csv', ['batch' => 'abc-123']))
        ->assertOk()
        ->assertSee('Import en cours')
        ->assertSee('files-importers\\/csv\\/batches\\/abc-123', false);
});

it('returns the progress of a stored batch', function () {
    $batch = app(BatchRepository::class)->store(Bus::batch([]));

    $this->actingAs(csvImportSuperAdmin())
        ->getJson(route('admin:files.importers.csv.progress', ['batch' => $batch->id]))
        ->assertOk()
        ->assertExactJson(['progress' => 0, 'finished' => false, 'failedJobs' => 0]);
});

it('returns 404 for an unknown batch', function () {
    $this->actingAs(csvImportSuperAdmin())
        ->getJson(route('admin:files.importers.csv.progress', ['batch' => 'unknown']))
        ->assertNotFound();
});
