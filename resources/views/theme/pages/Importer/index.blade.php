{{--
    CSV import (K2, SuperAdmin): upload form, then the progress of the import batch (?batch=),
    polled every two seconds from CSVImportController::progress(). Replaces the Livewire importer.
--}}
@extends('layouts.admin')

@section('title', 'Import CSV')

@section('content')
    <div class="space-y-6">
        <x-admin.page-header title="Import CSV" description="Le fichier est importé par lots de 1000 lignes, en arrière-plan ; la première ligne donne les noms des colonnes." />

        @if (request()->filled('batch'))
            <section aria-labelledby="import-progress-title" class="admin-card"
                x-data="{
                    progress: 0,
                    finished: false,
                    failedJobs: 0,
                    unavailable: false,
                    async poll() {
                        const response = await fetch(@js(route('admin:files.importers.csv.progress', ['batch' => request('batch')])), { headers: { Accept: 'application/json' } });
                        if (! response.ok) {
                            this.unavailable = true;
                            return;
                        }
                        Object.assign(this, await response.json());
                        if (! this.finished) {
                            setTimeout(() => this.poll(), 2000);
                        }
                    },
                }"
                x-init="poll()">
                <div class="admin-card-header">
                    <h2 id="import-progress-title" class="admin-section-title">Import en cours</h2>
                </div>
                <div class="admin-card-body space-y-3" role="status" aria-live="polite">
                    <p x-show="! finished && ! unavailable" class="text-body text-fg">
                        Import en cours… <span class="font-semibold tabular-nums" x-text="progress + ' %'">0 %</span>
                    </p>
                    <div x-show="finished && failedJobs === 0" x-cloak>
                        <x-admin.alert type="success" :dismissible="false" message="Import terminé." />
                    </div>
                    <div x-show="finished && failedJobs > 0" x-cloak>
                        <x-admin.alert type="warning" :dismissible="false">
                            Import terminé avec <span class="tabular-nums" x-text="failedJobs"></span> lot(s) en échec.
                        </x-admin.alert>
                    </div>
                    <div x-show="unavailable" x-cloak>
                        <x-admin.alert type="error" :dismissible="false" message="Le suivi de cet import est introuvable." />
                    </div>
                </div>
            </section>
        @endif

        <form method="POST" action="{{ route('admin:files.importers.csv.store') }}" enctype="multipart/form-data" class="admin-card max-w-2xl space-y-6 p-5 sm:p-6">
            @csrf

            <x-admin.file-upload name="import_file" label="Fichier CSV" accept=".csv,text/csv,text/plain" hint="Fichier CSV, ligne d’en-tête comprise." required />

            <div class="flex flex-col-reverse gap-3 border-t border-line pt-6 sm:flex-row sm:justify-end">
                <x-admin.button type="submit" icon="upload">Importer</x-admin.button>
            </div>
        </form>
    </div>
@endsection
