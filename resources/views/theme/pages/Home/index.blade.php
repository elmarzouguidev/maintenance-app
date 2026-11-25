{{--
    Dashboard (K2): the kit dashboard, without Livewire, jQuery or the Skote widgets. Same figures
    and role rules as before:
    - everyone: new tickets and tickets ready to deliver;
    - SuperAdmin/Admin: the period and company filters (same appFilter[...] links: quarters,
      DateBetween ranges, GetCompany), revenue, VAT and payments, the invoice counts, the two
      charts of the current year, and the top ten clients over a period (?top_from=&top_to=,
      replacing the Livewire component Dasboard\Dashboard).
--}}
@extends('layouts.admin')

@section('title', __('navbar.dashboard'))

@section('content')
    @php
        $isManager = auth()->user()->can('dashboard.analytics.browse');
        $filters = (array) request()->input('appFilter', []);
        $company = array_filter(['GetCompany' => $filters['GetCompany'] ?? null]);
        $range = fn ($start, $end): array => ['DateBetween' => $start->format('Y-m-d').','.$end->format('Y-m-d')];
        $periods = [
            ['label' => 'Trimestre 1', 'filter' => ['GetPeriod' => 1]],
            ['label' => 'Trimestre 2', 'filter' => ['GetPeriod' => 2]],
            ['label' => 'Trimestre 3', 'filter' => ['GetPeriod' => 3]],
            ['label' => 'Trimestre 4', 'filter' => ['GetPeriod' => 4]],
            ['label' => '7 derniers jours', 'filter' => $range(now()->subDays(6), now())],
            ['label' => '30 derniers jours', 'filter' => $range(now()->subDays(30), now())],
            ['label' => 'Ce mois', 'filter' => $range(now()->startOfMonth(), now()->endOfMonth())],
            ['label' => 'Mois dernier', 'filter' => $range(now()->subMonthNoOverflow()->startOfMonth(), now()->subMonthNoOverflow()->endOfMonth())],
            ['label' => 'Année en cours', 'filter' => $range(now()->startOfYear(), now()->endOfYear())],
            ['label' => 'Année précédente', 'filter' => $range(now()->subYear()->startOfYear(), now()->subYear()->endOfYear())],
        ];
        $money = fn ($amount): string => \App\Support\UiKit\Format::money($amount);
    @endphp

    <div class="space-y-6">
        <x-admin.page-header :title="__('navbar.dashboard')" :description="$isManager ? ($filters === [] ? 'Société par défaut, depuis le début.' : 'Chiffres de la période choisie.') : 'Les tickets à traiter.'" />

        @if ($isManager)
            <section aria-labelledby="dashboard-filters-title" class="admin-card space-y-4 p-5">
                <h2 id="dashboard-filters-title" class="sr-only">Période et société</h2>
                <nav class="flex flex-wrap gap-2" aria-label="Périodes">
                    @foreach ($periods as $period)
                        @php($isActive = array_intersect_assoc(array_map('strval', $period['filter']), array_map('strval', $filters)) === array_map('strval', $period['filter']))
                        <x-admin.button href="{{ route('admin:home', ['appFilter' => [...$company, ...$period['filter']]]) }}" size="sm" :variant="$isActive ? 'primary' : 'secondary'" :aria-current="$isActive ? 'true' : null">{{ $period['label'] }}</x-admin.button>
                    @endforeach
                    @if ($filters !== [])
                        <x-admin.button href="{{ route('admin:home') }}" size="sm" variant="ghost">Effacer</x-admin.button>
                    @endif
                </nav>
                <nav class="flex flex-wrap gap-2 border-t border-line pt-4" aria-label="Sociétés">
                    @foreach ($companies as $item)
                        @php($isActive = (string) ($filters['GetCompany'] ?? '') === (string) $item->id)
                        <x-admin.button href="{{ route('admin:home', ['appFilter' => [...\Illuminate\Support\Arr::except($filters, 'GetCompany'), 'GetCompany' => $item->id]]) }}" size="sm" :variant="$isActive ? 'primary' : 'secondary'" :aria-current="$isActive ? 'true' : null" icon="building">{{ $item->name }}</x-admin.button>
                    @endforeach
                </nav>
            </section>

            <x-admin.stats-grid amounts>
                <x-admin.stats-card title="Chiffre d’affaires HT" :value="$money($chiffreAff)" icon="banknotes" href="{{ route('commercial:invoices.index') }}" />
                <x-admin.stats-card title="Chiffre d’affaires TTC" :value="$money($chiffreAffTTC)" icon="banknotes" variant="neutral" />
                <x-admin.stats-card title="TVA des encaissements" :value="$money($chiffreTVA)" icon="document" variant="neutral" />
                <x-admin.stats-card title="Encaissements TTC" :value="$money($chiffreBills)" icon="card" variant="success" href="{{ route('commercial:bills.index') }}" />
            </x-admin.stats-grid>
        @endif

        <x-admin.stats-grid>
            <x-admin.stats-card title="Nouveaux tickets" :value="$ticketsLast" icon="inbox" variant="warning" href="{{ route('admin:tickets.list') }}" />
            <x-admin.stats-card title="Prêts à livrer" :value="$ticketsPret" icon="truck" variant="success" href="{{ route('admin:tickets.livrable') }}" />
            @if ($isManager)
                <x-admin.stats-card title="Tickets à facturer" :value="$tickets" icon="banknotes" variant="info" href="{{ route('admin:tickets.invoiceable') }}" />
                <x-admin.stats-card title="Tickets au total" :value="$ticketsCount" icon="list" variant="neutral" href="{{ route('admin:tickets.list.old') }}" />
            @endif
        </x-admin.stats-grid>

        @if ($isManager)
            <div class="grid gap-6 xl:grid-cols-2">
                <x-charts.monthly :chart="$revenueChart" />
                <x-charts.monthly :chart="$paymentsChart" />
            </div>

            <div class="grid gap-6 lg:grid-cols-2">
                <section aria-labelledby="dashboard-invoices-title" class="admin-card">
                    <div class="admin-card-header">
                        <h2 id="dashboard-invoices-title" class="admin-section-title">Factures</h2>
                    </div>
                    <dl class="admin-card-body grid grid-cols-2 gap-4">
                        <x-admin.detail-item label="Total"><span class="font-semibold tabular-nums">{{ $allInvoices }}</span></x-admin.detail-item>
                        <x-admin.detail-item label="Payées"><span class="tabular-nums">{{ $invoicesPaid }}</span></x-admin.detail-item>
                        <x-admin.detail-item label="En attente de paiement"><span class="tabular-nums">{{ $invoicesNotPaid }}</span></x-admin.detail-item>
                        <x-admin.detail-item label="Échues"><span class="tabular-nums">{{ $invoicesRetard }}</span></x-admin.detail-item>
                    </dl>
                </section>
                <section aria-labelledby="dashboard-estimates-title" class="admin-card">
                    <div class="admin-card-header">
                        <h2 id="dashboard-estimates-title" class="admin-section-title">Devis</h2>
                    </div>
                    <dl class="admin-card-body grid grid-cols-2 gap-4">
                        <x-admin.detail-item label="Total"><span class="font-semibold tabular-nums">{{ $allEstimates }}</span></x-admin.detail-item>
                        <x-admin.detail-item label="Non facturés"><span class="tabular-nums">{{ $estimatesNotInvoiced }}</span></x-admin.detail-item>
                        <x-admin.detail-item label="Expirés"><span class="tabular-nums">{{ $estimatesExpired }}</span></x-admin.detail-item>
                    </dl>
                </section>
            </div>

            <section aria-labelledby="dashboard-clients-title" class="space-y-4">
                <div class="admin-card space-y-4 p-5">
                    <h2 id="dashboard-clients-title" class="admin-section-title">Chiffre d’affaires par client</h2>
                    <form method="GET" action="{{ route('admin:home') }}" class="flex flex-col gap-4 sm:flex-row sm:items-end">
                        @foreach ($filters as $key => $value)
                            @if (is_scalar($value))
                                <input type="hidden" name="appFilter[{{ $key }}]" value="{{ $value }}">
                            @endif
                        @endforeach
                        <x-admin.date-picker name="top_from" label="Du" :value="$topFrom" />
                        <x-admin.date-picker name="top_to" label="Au" :value="$topTo" />
                        <x-admin.button type="submit" variant="secondary" icon="filter">Afficher</x-admin.button>
                    </form>
                </div>

                @if ($topClients->isEmpty())
                    <div class="admin-card">
                        <x-admin.empty-state icon="users" title="Aucun client" description="Aucun client n’a de facture." />
                    </div>
                @else
                    <x-admin.table :headers="['#', 'Client', 'Facturé TTC', 'Encaissé HT', '']">
                        @foreach ($topClients as $client)
                            <tr>
                                <td class="tabular-nums">{{ $loop->iteration }}</td>
                                <td class="whitespace-normal"><a href="{{ $client->url }}" class="font-medium hover:underline">{{ $client->entreprise }}</a></td>
                                <td><x-admin.amount :value="$client->invoices_sum_price_total ?? 0" /></td>
                                <td><x-admin.amount :value="$client->price_total_paid ?? 0" /></td>
                                <td class="text-end"><x-admin.icon-button icon="eye" :label="'Voir le client '.$client->entreprise" href="{{ $client->url }}" size="sm" /></td>
                            </tr>
                        @endforeach
                    </x-admin.table>
                @endif
            </section>
        @endif
    </div>
@endsection
