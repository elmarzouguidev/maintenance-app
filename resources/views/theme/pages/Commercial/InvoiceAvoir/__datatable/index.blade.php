{{--
    Credit notes (K2): the kit list pattern, replacing DataTables and the Bootstrap filters, like
    the invoices list (same appFilter[...] filters and periods); 30 per page (it loaded every
    credit note before).
--}}
@extends('layouts.admin')

@section('title', 'Factures d’avoir')

@section('content')
    @php
        $filters = (array) request()->input('appFilter', []);
        $kept = array_filter(\Illuminate\Support\Arr::only($filters, ['GetClient', 'GetStatus', 'GetCompany']), fn ($value) => $value !== null && $value !== '');
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
    @endphp

    <div class="space-y-6">
        <x-admin.page-header title="Factures d’avoir" description="Les avoirs émis sur les factures, du plus récent au plus ancien.">
            @can('invoices.create')
                <x-slot:actions>
                    <x-admin.button href="{{ route('commercial:invoices.create.avoir') }}" icon="plus">Nouvel avoir</x-admin.button>
                </x-slot:actions>
            @endcan
        </x-admin.page-header>

        <section aria-labelledby="invoice-filters-title" class="admin-card space-y-4 p-5">
            <h2 id="invoice-filters-title" class="sr-only">Filtres</h2>

            <form method="GET" action="{{ route('commercial:invoices.index.avoir') }}" class="space-y-4"
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
                <div class="grid gap-4 md:grid-cols-2 xl:grid-cols-5">
                    <x-admin.form-select2 name="appFilter[GetClient]" label="Client" :options="$clients->pluck('entreprise', 'id')->all()" :value="(string) ($filters['GetClient'] ?? '')" placeholder="Tous les clients" />
                    <x-admin.form-select name="appFilter[GetStatus]" id="filter-status" label="Statut" :options="['non-paid' => 'À régler', 'paid' => 'Payée']" :value="(string) ($filters['GetStatus'] ?? '')" placeholder="Tous les statuts" />
                    <x-admin.form-select name="appFilter[GetCompany]" id="filter-company" label="Société" :options="$companies->pluck('name', 'id')->all()" :value="(string) ($filters['GetCompany'] ?? '')" placeholder="Toutes les sociétés" />
                    <x-admin.date-picker name="start" label="Du" :value="$dateStart" />
                    <x-admin.date-picker name="end" label="Au" :value="$dateEnd" />
                </div>
                <div class="flex flex-wrap items-center gap-3">
                    <x-admin.button type="submit" variant="secondary" icon="filter">Filtrer</x-admin.button>
                    @if ($hasFilters)
                        <x-admin.button href="{{ route('commercial:invoices.index.avoir') }}" variant="ghost">Effacer les filtres</x-admin.button>
                    @endif
                </div>
            </form>

            <nav class="flex flex-wrap gap-2 border-t border-line pt-4" aria-label="Périodes">
                @foreach ($periods as $period)
                    @php($isActive = array_intersect_assoc(array_map('strval', $period['filter']), array_map('strval', $filters)) === array_map('strval', $period['filter']))
                    <x-admin.button href="{{ route('commercial:invoices.index.avoir', ['appFilter' => [...$kept, ...$period['filter']]]) }}" size="sm" :variant="$isActive ? 'primary' : 'secondary'" :aria-current="$isActive ? 'true' : null">{{ $period['label'] }}</x-admin.button>
                @endforeach
            </nav>
        </section>

        <section aria-labelledby="invoices-title" class="space-y-4">
            <h2 id="invoices-title" class="sr-only">Liste des factures d’avoir</h2>

            @if ($invoices->isEmpty())
                <div class="admin-card">
                    @if ($hasFilters)
                        <x-admin.empty-state icon="filter" title="Aucun résultat" description="Aucun avoir ne correspond à ces filtres.">
                            <x-admin.button href="{{ route('commercial:invoices.index.avoir') }}" variant="secondary">Effacer les filtres</x-admin.button>
                        </x-admin.empty-state>
                    @else
                        <x-admin.empty-state icon="banknotes" title="Aucun avoir" description="Aucune facture d’avoir n’a encore été créée." />
                    @endif
                </div>
            @else
                <x-admin.table :headers="['Avoir', 'Facture', 'Client', 'Date', 'Total HT', 'TVA', 'Total TTC', 'Statut', '']">
                    @foreach ($invoices as $invoice)
                        @php($status = \App\Constants\InvoiceStatus::tryFrom((string) $invoice->status))
                        <tr>
                            <td>
                                <a href="{{ $invoice->url }}" class="font-medium tabular-nums hover:underline">{{ $invoice->full_number }}</a>
                                <p class="text-caption text-fg-muted">{{ optional($invoice->company)->name }}</p>
                            </td>
                            <td class="tabular-nums">{{ $invoice->invoice_number }}</td>
                            <td>{{ optional($invoice->client)->entreprise }}</td>
                            <td><x-admin.date :value="$invoice->invoice_date" /></td>
                            <td><x-admin.amount :value="$invoice->price_ht" /></td>
                            <td><x-admin.amount :value="$invoice->price_tva" /></td>
                            <td><x-admin.amount :value="$invoice->price_total" /></td>
                            <td>
                                @if ($status)
                                    <x-admin.status-badge :status="$status" />
                                @endif
                            </td>
                            <td class="text-end">
                                <div class="flex items-center justify-end gap-2">
                                    <x-admin.icon-button icon="printer" :label="'Télécharger l’avoir '.$invoice->full_number" href="{{ route('public.show.invoice.avoir', [$invoice->uuid, 'has_header' => true]) }}" target="_blank" rel="noopener" size="sm" />
                                    <x-admin.icon-button icon="pencil" :label="'Modifier l’avoir '.$invoice->full_number" href="{{ $invoice->edit_url }}" size="sm" />
                                </div>
                            </td>
                        </tr>
                    @endforeach
                </x-admin.table>

                {{ $invoices->links('pagination::admin') }}
            @endif
        </section>

    </div>
@endsection
