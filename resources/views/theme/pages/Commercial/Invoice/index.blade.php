{{--
    Invoices (K2): the kit list pattern, replacing DataTables and the Bootstrap filters.
    - Filters: a GET form sending the same appFilter[...] parameters as before (client, status,
      company, custom dates as DateBetween), plus the period shortcuts as links.
    - Rows: status badge (App\Constants\InvoiceStatus), payment column, row actions.
    - One payment dialog for every row, filled by Alpine (it was one Bootstrap modal per row).
    The view name stays: TicketComposer gives it $tickets_invoiceable.
--}}
@extends('layouts.admin')

@section('title', __('navbar.invoices'))

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

    <div class="space-y-6"
        x-data="{
            payment: { action: '', number: '', amount: '' },
            openPayment(action, number, amount) {
                this.payment = { action, number, amount };
                document.getElementById('add-payment').showModal();
            },
        }">
        <x-admin.page-header :title="__('navbar.invoices')" description="Les factures de la société, de la plus récente à la plus ancienne.">
            <x-slot:actions>
                @if (($tickets_invoiceable ?? 0) > 0)
                    <x-admin.button href="{{ route('admin:tickets.invoiceable') }}" variant="secondary" icon="wrench">Tickets à facturer ({{ $tickets_invoiceable }})</x-admin.button>
                @endif
                @can('invoices.create')
                    <x-admin.button href="{{ route('commercial:invoices.create') }}" icon="plus">Nouvelle facture</x-admin.button>
                @endcan
            </x-slot:actions>
        </x-admin.page-header>

        <section aria-labelledby="invoice-filters-title" class="admin-card space-y-4 p-5">
            <h2 id="invoice-filters-title" class="sr-only">Filtres</h2>

            <form method="GET" action="{{ route('commercial:invoices.index') }}" class="space-y-4"
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
                        <x-admin.button href="{{ route('commercial:invoices.index') }}" variant="ghost">Effacer les filtres</x-admin.button>
                    @endif
                </div>
            </form>

            <nav class="flex flex-wrap gap-2 border-t border-line pt-4" aria-label="Périodes">
                @foreach ($periods as $period)
                    @php($isActive = array_intersect_assoc(array_map('strval', $period['filter']), array_map('strval', $filters)) === array_map('strval', $period['filter']))
                    <x-admin.button href="{{ route('commercial:invoices.index', ['appFilter' => [...$kept, ...$period['filter']]]) }}" size="sm" :variant="$isActive ? 'primary' : 'secondary'" :aria-current="$isActive ? 'true' : null">{{ $period['label'] }}</x-admin.button>
                @endforeach
            </nav>
        </section>

        <section aria-labelledby="invoices-title" class="space-y-4">
            <h2 id="invoices-title" class="sr-only">Liste des factures</h2>

            @if ($invoices->isEmpty())
                <div class="admin-card">
                    @if ($hasFilters)
                        <x-admin.empty-state icon="filter" title="Aucun résultat" description="Aucune facture ne correspond à ces filtres.">
                            <x-admin.button href="{{ route('commercial:invoices.index') }}" variant="secondary">Effacer les filtres</x-admin.button>
                        </x-admin.empty-state>
                    @else
                        <x-admin.empty-state icon="banknotes" title="Aucune facture" description="Aucune facture n’a encore été créée." />
                    @endif
                </div>
            @else
                <x-admin.table :headers="['Facture', 'Client', 'Date', 'Total HT', 'TVA', 'Échéance', 'Statut', 'Règlement', '']">
                    @foreach ($invoices as $invoice)
                        @php($status = \App\Constants\InvoiceStatus::tryFrom((string) $invoice->status))
                        <tr>
                            <td>
                                <a href="{{ $invoice->url }}" class="font-medium tabular-nums hover:underline">{{ $invoice->code }}</a>
                                <p class="text-caption text-fg-muted">{{ optional($invoice->company)->name }}</p>
                                @if ($invoice->ticket_count > 0)
                                    <p class="text-caption text-fg-muted tabular-nums">{{ $invoice->ticket?->code }}</p>
                                @elseif ($invoice->tickets_count > 0)
                                    <p class="text-caption text-fg-muted tabular-nums">{{ $invoice->tickets->pluck('code')->join(', ') }}</p>
                                @endif
                            </td>
                            <td>
                                @if ($invoice->client)
                                    <a href="{{ $invoice->client->url }}" class="hover:underline">{{ $invoice->client->entreprise }}</a>
                                @endif
                            </td>
                            <td><x-admin.date :value="$invoice->invoice_date" /></td>
                            <td><x-admin.amount :value="$invoice->price_ht" /></td>
                            <td><x-admin.amount :value="$invoice->price_tva" /></td>
                            <td><x-admin.date :value="$invoice->due_date" /></td>
                            <td>
                                @if ($status)
                                    <x-admin.status-badge :status="$status" />
                                @else
                                    <x-admin.status-badge variant="neutral" :label="(string) $invoice->status" />
                                @endif
                            </td>
                            <td>
                                @if ($invoice->bill_count && ! $invoice->avoir_count)
                                    <a href="{{ route('commercial:bills.edit', $invoice->bill?->uuid) }}" class="text-small font-medium text-accent hover:underline">Voir le règlement</a>
                                @elseif ($invoice->avoir_count)
                                    <a href="{{ route('public.show.invoice.avoir', [$invoice->avoir?->uuid, 'has_header' => true]) }}" target="_blank" rel="noopener" class="text-small font-medium text-accent hover:underline">Avoir {{ $invoice->avoir?->code }}</a>
                                @else
                                    <x-admin.button variant="secondary" size="sm" icon="plus"
                                        x-on:click="openPayment({{ \Illuminate\Support\Js::from(route('commercial:bills.storeBill', $invoice->uuid)) }}, {{ \Illuminate\Support\Js::from($invoice->code.' · '.optional($invoice->company)->name) }}, {{ \Illuminate\Support\Js::from(\App\Support\UiKit\Format::money($invoice->price_total)) }})">Ajouter un règlement</x-admin.button>
                                @endif
                            </td>
                            <td class="text-end">
                                <div class="flex items-center justify-end gap-2">
                                    <x-admin.icon-button icon="printer" :label="'Télécharger la facture '.$invoice->code" href="{{ route('public.show.invoice', [$invoice->uuid, 'has_header' => true]) }}" target="_blank" rel="noopener" size="sm" />
                                    <x-admin.icon-button icon="pencil" :label="'Modifier la facture '.$invoice->code" href="{{ $invoice->edit_url }}" size="sm" />
                                </div>
                            </td>
                        </tr>
                    @endforeach
                </x-admin.table>

                {{ $invoices->withQueryString()->links('pagination::admin-simple') }}
            @endif
        </section>

        <x-admin.modal id="add-payment" title="Ajouter un règlement" description="Le règlement porte sur le total TTC de la facture ; elle passe alors à « Payée ».">
            <form id="add-payment-form" method="POST" :action="payment.action" class="grid gap-4">
                @csrf
                <dl class="grid gap-4 sm:grid-cols-2">
                    <x-admin.detail-item label="Facture"><span class="tabular-nums" x-text="payment.number"></span></x-admin.detail-item>
                    <x-admin.detail-item label="Montant"><span class="font-semibold tabular-nums" x-text="payment.amount"></span></x-admin.detail-item>
                </dl>
                <div class="grid gap-4 sm:grid-cols-2">
                    <x-admin.form-input name="bill_date" type="date" label="Date de règlement" :value="now()->format('Y-m-d')" required />
                    <x-admin.form-select name="bill_mode" label="Mode de règlement" :options="['Espèce' => 'Espèce', 'Virement' => 'Virement', 'Chèque' => 'Chèque']" :placeholder="null" required />
                </div>
                <x-admin.form-input name="reference" label="Référence de transaction" />
                <x-admin.textarea name="notes" label="Note" rows="2" />
            </form>
            <x-slot:footer>
                <x-admin.button variant="secondary" onclick="this.closest('dialog').close()">Annuler</x-admin.button>
                <x-admin.button type="submit" form="add-payment-form">Enregistrer le règlement</x-admin.button>
            </x-slot:footer>
        </x-admin.modal>
    </div>
@endsection
