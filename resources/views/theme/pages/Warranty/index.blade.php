{{--
    Warranties (K1): the UI kit list pattern with a server-side table, replacing DataTables.
    Search by ticket code (?q=), 30 per page, Excel and PDF exports of the same search.
--}}
@extends('layouts.admin')

@section('title', 'Garanties')

@section('content')
    @php($exportQuery = $search !== '' ? ['q' => $search] : [])

    <div class="space-y-6">
        <x-admin.page-header title="Garanties" description="Les garanties des tickets réparés, de la plus récente à la plus ancienne.">
            <x-slot:actions>
                <x-admin.button href="{{ route('admin:warranty.export', ['format' => 'xlsx', ...$exportQuery]) }}" variant="secondary" icon="download">Exporter en Excel</x-admin.button>
                <x-admin.button href="{{ route('admin:warranty.export', ['format' => 'pdf', ...$exportQuery]) }}" variant="secondary" icon="printer">Exporter en PDF</x-admin.button>
            </x-slot:actions>
        </x-admin.page-header>

        <form method="GET" action="{{ route('admin:warranty.index') }}" role="search" class="flex flex-col gap-3 sm:flex-row sm:items-end">
            <x-admin.form-input name="q" type="search" label="Rechercher un ticket" :value="$search" placeholder="Code du ticket, ex. TCK-0042" class="sm:w-80" />
            <div class="flex gap-3">
                <x-admin.button type="submit" variant="secondary" icon="search">Rechercher</x-admin.button>
                @if ($search !== '')
                    <x-admin.button href="{{ route('admin:warranty.index') }}" variant="ghost">Effacer</x-admin.button>
                @endif
            </div>
        </form>

        <section aria-labelledby="warranties-title" class="space-y-4">
            <h2 id="warranties-title" class="sr-only">Liste des garanties</h2>

            @if ($warranties->isEmpty())
                <div class="admin-card">
                    @if ($search !== '')
                        <x-admin.empty-state icon="filter" title="Aucun résultat" :description="'Aucune garantie ne correspond au ticket « '.$search.' ».'">
                            <x-admin.button href="{{ route('admin:warranty.index') }}" variant="secondary">Effacer la recherche</x-admin.button>
                        </x-admin.empty-state>
                    @else
                        <x-admin.empty-state icon="shield" title="Aucune garantie" description="Aucune garantie n’a encore été enregistrée." />
                    @endif
                </div>
            @else
                <x-admin.table :headers="['Ticket', 'Statut du ticket', 'Date de début', 'Date de fin']">
                    @foreach ($warranties as $warranty)
                        <tr>
                            <td class="font-medium tabular-nums">{{ $warranty->ticket?->code ?? '—' }}</td>
                            <td>{{ $warranty->ticket ? __('status.statuses.'.$warranty->ticket->status) : '—' }}</td>
                            <td><x-admin.date :value="$warranty->start_at" /></td>
                            <td><x-admin.date :value="$warranty->end_at" /></td>
                        </tr>
                    @endforeach
                </x-admin.table>

                {{ $warranties->links('pagination::admin') }}
            @endif
        </section>
    </div>

    {{--
        Add-warranty modal (BL-40, dead): nothing opens it and WarrantyController::store() is empty.
        Kept as is by decision of 2026-11-23, moved to the kit components with its known form bugs.
    --}}
    <x-admin.modal id="addWarranty" title="Ajouter une garantie">
        <form id="addWarrantyForm" method="POST" action="{{ route('admin:warranty.store') }}" class="grid gap-4">
            @csrf

            <x-admin.form-select name="ticket" label="Ticket" :placeholder="null" required
                :options="['espece' => 'Choisir le ticket'] + $tickets->pluck('code', 'uuid')->all()" />
            <x-admin.form-input name="start_at" type="date" label="Date de départ" :value="now()->format('d-m-Y')" required pattern="[0-9]{4}-[0-9]{2}-[0-9]{2}" />
            <x-admin.form-input name="end_at" type="date" label="Date de fin" :value="now()->format('d-m-Y')" required pattern="[0-9]{4}-[0-9]{2}-[0-9]{2}" />
            <x-admin.textarea name="notes" label="Description" maxlength="225" rows="2" />
        </form>

        <x-slot:footer>
            <x-admin.button variant="secondary" onclick="this.closest('dialog').close()">Annuler</x-admin.button>
            <x-admin.button type="submit" form="addWarrantyForm">{{ __('buttons.store') }}</x-admin.button>
        </x-slot:footer>
    </x-admin.modal>
@endsection
