{{--
    Purchase orders (K2): the kit list pattern, replacing DataTables and the Bootstrap filters.
    Filters send the same appFilter[...] parameters as before (supplier, company, dates as
    DateBetween, periods); 30 per page. The status filter is gone: no purchase order has any of
    the statuses it offered (BL-81).
--}}
@extends('layouts.admin')

@section('title', 'Bons de commande')

@section('content')
    @php
        $filters = (array) request()->input('appFilter', []);
        $kept = array_filter(\Illuminate\Support\Arr::only($filters, ['GetProvider', 'GetCompany']), fn ($value) => $value !== null && $value !== '');
        [$dateStart, $dateEnd] = array_pad(explode(',', (string) ($filters['DateBetween'] ?? '')), 2, '');
        $range = fn ($start, $end): string => $start->format('Y-m-d').','.$end->format('Y-m-d');
        $periods = [
            ['label' => 'Trimestre 1', 'filter' => ['GetPeriod' => 1]],
            ['label' => 'Trimestre 2', 'filter' => ['GetPeriod' => 2]],
            ['label' => 'Trimestre 3', 'filter' => ['GetPeriod' => 3]],
            ['label' => 'Trimestre 4', 'filter' => ['GetPeriod' => 4]],
            ['label' => 'Ce mois', 'filter' => ['DateBetween' => $range(now()->startOfMonth(), now()->endOfMonth())]],
            ['label' => 'Mois dernier', 'filter' => ['DateBetween' => $range(now()->subMonthNoOverflow()->startOfMonth(), now()->subMonthNoOverflow()->endOfMonth())]],
            ['label' => 'Année en cours', 'filter' => ['DateBetween' => $range(now()->startOfYear(), now()->endOfYear())]],
            ['label' => 'Année précédente', 'filter' => ['DateBetween' => $range(now()->subYear()->startOfYear(), now()->subYear()->endOfYear())]],
        ];
        $hasFilters = $filters !== [];
    @endphp

    <div class="space-y-6">
        <x-admin.page-header title="Bons de commande" description="Les bons de commande aux fournisseurs, du plus récent au plus ancien.">
            @can('bcommandes.create')
                <x-slot:actions>
                    <x-admin.button href="{{ route('commercial:bcommandes.create') }}" icon="plus">Nouveau bon de commande</x-admin.button>
                </x-slot:actions>
            @endcan
        </x-admin.page-header>

        <section aria-labelledby="bc-filters-title" class="admin-card space-y-4 p-5">
            <h2 id="bc-filters-title" class="sr-only">Filtres</h2>

            <form method="GET" action="{{ route('commercial:bcommandes.index') }}" class="space-y-4"
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
                <div class="grid gap-4 md:grid-cols-2 xl:grid-cols-4">
                    <x-admin.form-select2 name="appFilter[GetProvider]" label="Fournisseur" :options="$providers->pluck('entreprise', 'id')->all()" :value="(string) ($filters['GetProvider'] ?? '')" placeholder="Tous les fournisseurs" />
                    <x-admin.form-select name="appFilter[GetCompany]" id="filter-company" label="Société" :options="$companies->pluck('name', 'id')->all()" :value="(string) ($filters['GetCompany'] ?? '')" placeholder="Toutes les sociétés" />
                    <x-admin.date-picker name="start" label="Du" :value="$dateStart" />
                    <x-admin.date-picker name="end" label="Au" :value="$dateEnd" />
                </div>
                <div class="flex flex-wrap items-center gap-3">
                    <x-admin.button type="submit" variant="secondary" icon="filter">Filtrer</x-admin.button>
                    @if ($hasFilters)
                        <x-admin.button href="{{ route('commercial:bcommandes.index') }}" variant="ghost">Effacer les filtres</x-admin.button>
                    @endif
                </div>
            </form>

            <nav class="flex flex-wrap gap-2 border-t border-line pt-4" aria-label="Périodes">
                @foreach ($periods as $period)
                    @php($isActive = array_intersect_assoc(array_map('strval', $period['filter']), array_map('strval', $filters)) === array_map('strval', $period['filter']))
                    <x-admin.button href="{{ route('commercial:bcommandes.index', ['appFilter' => [...$kept, ...$period['filter']]]) }}" size="sm" :variant="$isActive ? 'primary' : 'secondary'" :aria-current="$isActive ? 'true' : null">{{ $period['label'] }}</x-admin.button>
                @endforeach
            </nav>
        </section>

        <section aria-labelledby="bc-list-title" class="space-y-4">
            <h2 id="bc-list-title" class="sr-only">Liste des bons de commande</h2>

            @if ($commandes->isEmpty())
                <div class="admin-card">
                    @if ($hasFilters)
                        <x-admin.empty-state icon="filter" title="Aucun résultat" description="Aucun bon de commande ne correspond à ces filtres.">
                            <x-admin.button href="{{ route('commercial:bcommandes.index') }}" variant="secondary">Effacer les filtres</x-admin.button>
                        </x-admin.empty-state>
                    @else
                        <x-admin.empty-state icon="list" title="Aucun bon de commande" description="Aucun bon de commande n’a encore été créé." />
                    @endif
                </div>
            @else
                <x-admin.table :headers="['Bon de commande', 'Fournisseur', 'Date', 'Total HT', 'TVA', 'Total TTC', '']">
                    @foreach ($commandes as $command)
                        <tr>
                            <td>
                                <a href="{{ $command->url }}" class="font-medium tabular-nums hover:underline">{{ $command->full_number }}</a>
                                <p class="text-caption text-fg-muted">{{ optional($command->company)->name }}</p>
                            </td>
                            <td>{{ optional($command->provider)->entreprise }}</td>
                            <td><x-admin.date :value="$command->date_command" /></td>
                            <td><x-admin.amount :value="$command->price_ht" /></td>
                            <td><x-admin.amount :value="$command->price_tva" /></td>
                            <td><x-admin.amount :value="$command->price_total" /></td>
                            <td class="text-end">
                                <div class="flex items-center justify-end gap-2">
                                    <x-admin.icon-button icon="printer" :label="'Télécharger le bon de commande '.$command->full_number" href="{{ route('public.show.bcommand', [$command->uuid, 'has_header' => true]) }}" target="_blank" rel="noopener" size="sm" />
                                    @can('bcommandes.edit')
                                        <x-admin.icon-button icon="pencil" :label="'Modifier le bon de commande '.$command->full_number" href="{{ $command->edit_url }}" size="sm" />
                                    @endcan
                                </div>
                            </td>
                        </tr>
                    @endforeach
                </x-admin.table>

                {{ $commandes->links('pagination::admin') }}
            @endif
        </section>
    </div>
@endsection
