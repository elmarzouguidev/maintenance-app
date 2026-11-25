{{--
    Diagnostics, admin view (K2): diagnostic.index for SuperAdmin/Admin/ASSISTANTE DIRECTEUR,
    the diagnosed tickets waiting for an estimate, for a purchase order or not repairable; kit
    filters, tabs and tables. Filters send the same appFilter[...] parameters (client, state,
    technician, DateBetween); the state now posts the Etat value: it posted "reparable", which
    never matched the integer column (BL-88).
--}}
@extends('layouts.admin')

@section('title', 'Diagnostics')

@section('content')
    @php
        use App\Constants\Etat;

        $filters = (array) request()->input('appFilter', []);
        [$dateStart, $dateEnd] = array_pad(explode(',', (string) ($filters['DateBetween'] ?? '')), 2, '');
        $hasFilters = $filters !== [];
        $listUrl = route('admin:diagnostic.index');
        $tabs = [
            ['key' => 'en-attent-de-devis', 'label' => 'En attente de devis', 'url' => 'ticket_url', 'action' => 'Traiter le ticket'],
            ['key' => 'en-attent-de-bc', 'label' => 'En attente de BC', 'url' => 'ticket_url', 'action' => 'Traiter le ticket'],
            ['key' => 'retour-non-reparable', 'label' => 'Non réparables', 'url' => 'ticket_url', 'action' => null],
        ];
    @endphp

    <div class="space-y-6">
        <x-admin.page-header title="Diagnostics" description="Les tickets diagnostiqués : en attente de devis, de bon de commande, ou non réparables." />

        <section aria-labelledby="diagnostic-filters-title" class="admin-card p-5">
            <h2 id="diagnostic-filters-title" class="sr-only">Filtres</h2>
            <form method="GET" action="{{ $listUrl }}" class="space-y-4"
                x-on:submit="
                    const start = $el.elements.namedItem('start')?.value;
                    const end = $el.elements.namedItem('end')?.value;
                    $refs.dateBetween.value = start && end ? start + ',' + end : '';
                    for (const field of $el.elements) {
                        if (field.name && (field.value === '' || field.name === 'start' || field.name === 'end')) {
                            field.disabled = true;
                        }
                    }
                ">
                <input type="hidden" name="appFilter[DateBetween]" x-ref="dateBetween">
                <div class="grid gap-4 md:grid-cols-3 xl:grid-cols-5">
                    <x-admin.form-select2 name="appFilter[GetClient]" label="Client" :options="$clients->pluck('entreprise', 'id')->all()" :value="(string) ($filters['GetClient'] ?? '')" placeholder="Tous les clients" />
                    <x-admin.form-select name="appFilter[GetEtat]" id="filter-etat" label="État" :options="[Etat::REPARABLE => 'Réparable', Etat::NON_REPARABLE => 'Non réparable']" :value="(string) ($filters['GetEtat'] ?? '')" placeholder="Tous les états" />
                    <x-admin.form-select name="appFilter[GetTechnicien]" id="filter-technician" label="Technicien" :options="$techniciens->mapWithKeys(fn ($technicien) => [$technicien->id => $technicien->full_name])->all()" :value="(string) ($filters['GetTechnicien'] ?? '')" placeholder="Tous les techniciens" />
                    <x-admin.date-picker name="start" label="Du" :value="$dateStart" />
                    <x-admin.date-picker name="end" label="Au" :value="$dateEnd" />
                </div>
                <div class="flex flex-wrap items-center gap-3">
                    <x-admin.button type="submit" variant="secondary" icon="filter">Filtrer</x-admin.button>
                    @if ($hasFilters)
                        <x-admin.button href="{{ $listUrl }}" variant="ghost">Effacer les filtres</x-admin.button>
                    @endif
                </div>
            </form>
        </section>

        <x-diagnostic.board :tickets="$tickets" :tabs="$tabs" technician="column" />
    </div>
@endsection
