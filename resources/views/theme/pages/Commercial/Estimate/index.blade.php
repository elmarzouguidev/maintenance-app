{{--
    Estimates (K2): the kit list pattern, replacing DataTables and the Bootstrap filters.
    Filters send the same appFilter[...] parameters as before (client, status, sent, company,
    dates as DateBetween, periods); 30 per page (the list loaded every estimate before).
--}}
@extends('layouts.admin')

@section('title', __('navbar.estimates'))

@section('content')
    @php
        $filters = (array) request()->input('appFilter', []);
        $kept = array_filter(\Illuminate\Support\Arr::only($filters, ['GetClient', 'GetStatus', 'GetSend', 'GetCompany']), fn ($value) => $value !== null && $value !== '');
        [$dateStart, $dateEnd] = array_pad(explode(',', (string) ($filters['DateBetween'] ?? '')), 2, '');
        $currentYear = now()->startOfYear()->format('Y-m-d').','.now()->endOfYear()->format('Y-m-d');
        $lastYear = now()->subYear()->startOfYear()->format('Y-m-d').','.now()->subYear()->endOfYear()->format('Y-m-d');
        $periods = [
            ['label' => 'Trimestre 1', 'filter' => ['GetPeriod' => 1]],
            ['label' => 'Trimestre 2', 'filter' => ['GetPeriod' => 2]],
            ['label' => 'Trimestre 3', 'filter' => ['GetPeriod' => 3]],
            ['label' => 'Trimestre 4', 'filter' => ['GetPeriod' => 4]],
            ['label' => 'Année en cours', 'filter' => ['DateBetween' => $currentYear]],
            ['label' => 'Année précédente', 'filter' => ['DateBetween' => $lastYear]],
        ];
        $hasFilters = $filters !== [];
        $statusOptions = collect(\App\Constants\EstimateStatus::cases())->mapWithKeys(fn ($status) => [$status->value => $status->label()])->all();
    @endphp

    <div class="space-y-6">
        <x-admin.page-header :title="__('navbar.estimates')" description="Les devis, du plus récent au plus ancien.">
            @can('estimates.create')
                <x-slot:actions>
                    <x-admin.button href="{{ route('commercial:estimates.create') }}" icon="plus">Nouveau devis</x-admin.button>
                </x-slot:actions>
            @endcan
        </x-admin.page-header>

        <section aria-labelledby="estimate-filters-title" class="admin-card space-y-4 p-5">
            <h2 id="estimate-filters-title" class="sr-only">Filtres</h2>

            <form method="GET" action="{{ route('commercial:estimates.index') }}" class="space-y-4"
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
                <div class="grid gap-4 md:grid-cols-3 xl:grid-cols-6">
                    <x-admin.form-select2 name="appFilter[GetClient]" label="Client" :options="$clients->pluck('entreprise', 'id')->all()" :value="(string) ($filters['GetClient'] ?? '')" placeholder="Tous les clients" />
                    <x-admin.form-select name="appFilter[GetStatus]" id="filter-status" label="Statut" :options="$statusOptions" :value="(string) ($filters['GetStatus'] ?? '')" placeholder="Tous les statuts" />
                    <x-admin.form-select name="appFilter[GetSend]" id="filter-send" label="Envoi" :options="['1' => 'Envoyé', '0' => 'Non envoyé']" :value="(string) ($filters['GetSend'] ?? '')" placeholder="Tous" />
                    <x-admin.form-select name="appFilter[GetCompany]" id="filter-company" label="Société" :options="$companies->pluck('name', 'id')->all()" :value="(string) ($filters['GetCompany'] ?? '')" placeholder="Toutes les sociétés" />
                    <x-admin.date-picker name="start" label="Du" :value="$dateStart" />
                    <x-admin.date-picker name="end" label="Au" :value="$dateEnd" />
                </div>
                <div class="flex flex-wrap items-center gap-3">
                    <x-admin.button type="submit" variant="secondary" icon="filter">Filtrer</x-admin.button>
                    @if ($hasFilters)
                        <x-admin.button href="{{ route('commercial:estimates.index') }}" variant="ghost">Effacer les filtres</x-admin.button>
                    @endif
                </div>
            </form>

            <nav class="flex flex-wrap gap-2 border-t border-line pt-4" aria-label="Périodes">
                @foreach ($periods as $period)
                    @php($isActive = array_intersect_assoc(array_map('strval', $period['filter']), array_map('strval', $filters)) === array_map('strval', $period['filter']))
                    <x-admin.button href="{{ route('commercial:estimates.index', ['appFilter' => [...$kept, ...$period['filter']]]) }}" size="sm" :variant="$isActive ? 'primary' : 'secondary'" :aria-current="$isActive ? 'true' : null">{{ $period['label'] }}</x-admin.button>
                @endforeach
            </nav>
        </section>

        <section aria-labelledby="estimates-title" class="space-y-4">
            <h2 id="estimates-title" class="sr-only">Liste des devis</h2>

            @if ($estimates->isEmpty())
                <div class="admin-card">
                    @if ($hasFilters)
                        <x-admin.empty-state icon="filter" title="Aucun résultat" description="Aucun devis ne correspond à ces filtres.">
                            <x-admin.button href="{{ route('commercial:estimates.index') }}" variant="secondary">Effacer les filtres</x-admin.button>
                        </x-admin.empty-state>
                    @else
                        <x-admin.empty-state icon="document" title="Aucun devis" description="Aucun devis n’a encore été créé." />
                    @endif
                </div>
            @else
                <x-admin.table :headers="['Devis', 'Client', 'Date', 'Total HT', 'Total TTC', 'Échéance', 'Statut', 'Facture', '']">
                    @foreach ($estimates as $estimate)
                        @php($status = \App\Constants\EstimateStatus::tryFrom((int) $estimate->status))
                        <tr>
                            <td>
                                <a href="{{ $estimate->url }}" class="font-medium tabular-nums hover:underline">{{ $estimate->code }}</a>
                                <p class="text-caption text-fg-muted">{{ optional($estimate->company)->name }}</p>
                                @if ($estimate->ticket_count > 0)
                                    <p class="text-caption text-fg-muted tabular-nums">{{ $estimate->ticket?->code }}</p>
                                @elseif ($estimate->tickets_count > 0)
                                    <p class="text-caption text-fg-muted tabular-nums">{{ $estimate->tickets->pluck('code')->join(', ') }}</p>
                                @endif
                            </td>
                            <td>{{ optional($estimate->client)->entreprise }}</td>
                            <td><x-admin.date :value="$estimate->estimate_date" /></td>
                            <td><x-admin.amount :value="$estimate->price_ht" /></td>
                            <td><x-admin.amount :value="$estimate->price_total" /></td>
                            <td><x-admin.date :value="$estimate->due_date" /></td>
                            <td>
                                @if ($status)
                                    <x-admin.status-badge :status="$status" />
                                @endif
                                @if ($estimate->is_send)
                                    <p class="mt-1 text-caption text-fg-muted">Envoyé</p>
                                @endif
                            </td>
                            <td>
                                @if ($estimate->invoice_count)
                                    <a href="{{ route('public.show.invoice', [$estimate->invoice?->uuid, 'has_header' => true]) }}" target="_blank" rel="noopener" class="text-small font-medium text-accent hover:underline">Voir la facture</a>
                                @elseif (! $estimate->is_invoiced)
                                    <x-admin.button href="{{ $estimate->create_invoice_url }}" variant="secondary" size="sm" icon="plus">Créer la facture</x-admin.button>
                                @endif
                            </td>
                            <td class="text-end">
                                <div class="flex items-center justify-end gap-2">
                                    <x-admin.icon-button icon="printer" :label="'Télécharger le devis '.$estimate->code" href="{{ route('public.show.estimate', [$estimate->uuid, 'has_header' => true]) }}" target="_blank" rel="noopener" size="sm" />
                                    <x-admin.icon-button icon="pencil" :label="'Modifier le devis '.$estimate->code" href="{{ $estimate->edit_url }}" size="sm" />
                                    @can('estimates.delete')
                                        <x-admin.delete-action :action="route('commercial:estimates.delete', ['estimateId' => $estimate->uuid])" :confirm="'Supprimer le devis '.$estimate->code.' ?'" />
                                    @endcan
                                </div>
                            </td>
                        </tr>
                    @endforeach
                </x-admin.table>

                {{ $estimates->links('pagination::admin') }}
            @endif
        </section>
    </div>
@endsection
