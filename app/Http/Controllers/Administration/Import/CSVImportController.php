<?php

namespace App\Http\Controllers\Administration\Import;

use App\Http\Controllers\Controller;
use App\Jobs\Importer\CSV\CSVImporterJob;
use Illuminate\Contracts\View\View;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Bus;

/**
 * CSV import (SuperAdmin): the file is cut into chunks of 1000 lines, each imported by a queued
 * CSVImporterJob in one batch; the page polls the batch progress. Replaces the Livewire
 * component application.importer.c-s-v-file-importer (same chunking, header and jobs).
 */
class CSVImportController extends Controller
{
    private const LINES_PER_JOB = 1000;

    public function index(): View
    {
        return view('theme.pages.Importer.index');
    }

    /**
     * Dispatch the import batch, then show the page with the batch to follow.
     */
    public function store(Request $request): RedirectResponse
    {
        $request->validate([
            'import_file' => ['required', 'file', 'mimes:csv,txt'],
        ]);

        $jobs = [];
        $header = [];

        foreach (array_chunk(file($request->file('import_file')->getRealPath()), self::LINES_PER_JOB) as $index => $lines) {
            $rows = array_map('str_getcsv', $lines);

            if ($index === 0) {
                $header = $rows[0];
                unset($rows[0]);
            }

            $jobs[] = new CSVImporterJob($rows, $header);
        }

        $batch = Bus::batch($jobs)->dispatch();

        return redirect()->route('admin:files.importers.csv', ['batch' => $batch->id]);
    }

    /**
     * Progress of an import batch, polled by the page.
     */
    public function progress(string $batch): JsonResponse
    {
        $importBatch = Bus::findBatch($batch);

        abort_if($importBatch === null, 404);

        return response()->json([
            'progress' => $importBatch->progress(),
            'finished' => $importBatch->finished(),
            'failedJobs' => $importBatch->failedJobs,
        ]);
    }
}
