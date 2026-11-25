{{--
    Payments (K2): the kit list pattern, replacing DataTables and the Bootstrap filters and modal.
    Filters send the same appFilter[...] parameters as before (client, payment mode, company,
    dates as DateBetween, periods); 30 per page. "Ajouter un règlement" opens a kit dialog that
    posts to bills.store.normal, like the former modal.
--}}
@extends('layouts.admin')

@section('title', 'Règlements')

@section('content')
    @php
        $paymentModes = ['Espèce', 'Virement', 'Chèque', 'Carte bancaire', 'Lettre de change', 'Prélèvement', 'Virement bancaire'];
        $filters = (array) request()->input('appFilter', []);
        $kept = array_filter(\Illuminate\Support\Arr::only($filters, ['GetClient', 'GetPaymentMode', 'GetCompany']), fn ($value) => $value !== null && $value !== '');
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
        $invoiceOptions = $invoices->mapWithKeys(fn ($invoice) => [$invoice->uuid => $invoice->full_number.' · '.\App\Support\UiKit\Format::money($invoice->price_total)])->all();
    @endphp

    <div class="space-y-6">
        <x-admin.page-header title="Règlements" description="Les règlements des factures, du plus récent au plus ancien.">
            <x-slot:actions>
                <x-admin.button href="{{ route('commercial:invoices.index') }}" variant="secondary" icon="banknotes">Factures</x-admin.button>
                @can('payments.create')
                    <x-admin.button icon="plus" onclick="document.getElementById('add-bill').showModal()">Ajouter un règlement</x-admin.button>
                @endcan
            </x-slot:actions>
        </x-admin.page-header>

        <section aria-labelledby="bill-filters-title" class="admin-card space-y-4 p-5">
            <h2 id="bill-filters-title" class="sr-only">Filtres</h2>

            <form method="GET" action="{{ route('commercial:bills.index') }}" class="space-y-4"
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
                    <x-admin.form-select name="appFilter[GetPaymentMode]" id="filter-mode" label="Mode de règlement" :options="array_combine($paymentModes, $paymentModes)" :value="(string) ($filters['GetPaymentMode'] ?? '')" placeholder="Tous les modes" />
                    <x-admin.form-select name="appFilter[GetCompany]" id="filter-company" label="Société" :options="$companies->pluck('name', 'id')->all()" :value="(string) ($filters['GetCompany'] ?? '')" placeholder="Toutes les sociétés" />
                    <x-admin.date-picker name="start" label="Du" :value="$dateStart" />
                    <x-admin.date-picker name="end" label="Au" :value="$dateEnd" />
                </div>
                <div class="flex flex-wrap items-center gap-3">
                    <x-admin.button type="submit" variant="secondary" icon="filter">Filtrer</x-admin.button>
                    @if ($hasFilters)
                        <x-admin.button href="{{ route('commercial:bills.index') }}" variant="ghost">Effacer les filtres</x-admin.button>
                    @endif
                </div>
            </form>

            <nav class="flex flex-wrap gap-2 border-t border-line pt-4" aria-label="Périodes">
                @foreach ($periods as $period)
                    @php($isActive = array_intersect_assoc(array_map('strval', $period['filter']), array_map('strval', $filters)) === array_map('strval', $period['filter']))
                    <x-admin.button href="{{ route('commercial:bills.index', ['appFilter' => [...$kept, ...$period['filter']]]) }}" size="sm" :variant="$isActive ? 'primary' : 'secondary'" :aria-current="$isActive ? 'true' : null">{{ $period['label'] }}</x-admin.button>
                @endforeach
            </nav>
        </section>

        <section aria-labelledby="bills-title" class="space-y-4">
            <h2 id="bills-title" class="sr-only">Liste des règlements</h2>

            @if ($bills->isEmpty())
                <div class="admin-card">
                    @if ($hasFilters)
                        <x-admin.empty-state icon="filter" title="Aucun résultat" description="Aucun règlement ne correspond à ces filtres.">
                            <x-admin.button href="{{ route('commercial:bills.index') }}" variant="secondary">Effacer les filtres</x-admin.button>
                        </x-admin.empty-state>
                    @else
                        <x-admin.empty-state icon="card" title="Aucun règlement" description="Aucun règlement n’a encore été enregistré." />
                    @endif
                </div>
            @else
                <x-admin.table :headers="['Règlement', 'Date', 'Facture', 'Client', 'Mode', 'Référence', 'Montant', 'Ajouté par', '']">
                    @foreach ($bills as $bill)
                        <tr>
                            <td>
                                @can('payments.edit')
                                    <a href="{{ $bill->edit_url }}" class="font-medium tabular-nums hover:underline">{{ $bill->code }}</a>
                                @else
                                    <span class="font-medium tabular-nums">{{ $bill->code }}</span>
                                @endcan
                                <p class="text-caption text-fg-muted">{{ optional($bill->company)->name }}</p>
                            </td>
                            <td><x-admin.date :value="$bill->bill_date" /></td>
                            <td class="tabular-nums">{{ optional($bill->billable)->full_number }}</td>
                            <td>{{ optional($bill->billable?->client)->entreprise }}</td>
                            <td>{{ $bill->bill_mode }}</td>
                            <td class="whitespace-normal">{{ $bill->reference }}</td>
                            <td><x-admin.amount :value="$bill->price_total" /></td>
                            <td>{{ $bill->added_by }}</td>
                            <td class="text-end">
                                <div class="flex items-center justify-end gap-2">
                                    @if ($bill->billable)
                                        <x-admin.icon-button icon="printer" :label="'Télécharger la facture '.$bill->billable->full_number" href="{{ route('public.show.invoice', [$bill->billable->uuid, 'has_header' => true]) }}" target="_blank" rel="noopener" size="sm" />
                                    @endif
                                    @can('payments.edit')
                                        <x-admin.icon-button icon="pencil" :label="'Modifier le règlement '.$bill->code" href="{{ $bill->edit_url }}" size="sm" />
                                    @endcan
                                    @can('payments.delete')
                                        <x-admin.delete-action :action="route('commercial:bills.delete', ['billId' => $bill->uuid])"
                                            :confirm="'Supprimer le règlement '.$bill->code.' ? La facture repassera à « À régler ».'" />
                                    @endcan
                                </div>
                            </td>
                        </tr>
                    @endforeach
                </x-admin.table>

                {{ $bills->links('pagination::admin') }}
            @endif
        </section>
    </div>

    @can('payments.create')
        <x-admin.modal id="add-bill" title="Ajouter un règlement" description="Le règlement porte sur le total TTC de la facture ; elle passe alors à « Payée ».">
            <form id="add-bill-form" method="POST" action="{{ route('commercial:bills.store.normal') }}" class="grid gap-4">
                @csrf
                <x-admin.form-select2 name="invoice" label="Facture" :options="$invoiceOptions" placeholder="Choisir la facture" search-placeholder="Numéro de facture…" required />
                <div class="grid gap-4 sm:grid-cols-2">
                    <x-admin.form-input name="bill_date" type="date" label="Date de règlement" :value="now()->format('Y-m-d')" required />
                    <x-admin.form-select name="bill_mode" label="Mode de règlement" :options="array_combine($paymentModes, $paymentModes)" value="Virement" :placeholder="null" required />
                </div>
                <x-admin.form-input name="reference" label="Référence de transaction" placeholder="Exemple : chèque n° 4552154221" />
                <x-admin.textarea name="notes" label="Note" rows="2" />
            </form>
            <x-slot:footer>
                <x-admin.button variant="secondary" onclick="this.closest('dialog').close()">Annuler</x-admin.button>
                <x-admin.button type="submit" form="add-bill-form">Enregistrer le règlement</x-admin.button>
            </x-slot:footer>
        </x-admin.modal>
    @endcan
@endsection
