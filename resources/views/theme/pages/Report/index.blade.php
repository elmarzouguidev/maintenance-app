{{--
    Client report (K1): the UI kit dashboard pattern. Monthly invoiced and collected totals
    (ReportController), then the clients ranked by invoiced total with the collected part.
--}}
@extends('layouts.admin')

@section('title', 'Rapport clients')

@section('content')
    <div class="space-y-6">
        <x-admin.page-header title="Rapport clients" description="Chiffre d’affaires et encaissements par mois, puis le classement des clients par chiffre d’affaires." />

        <div class="grid gap-6 xl:grid-cols-2">
            @include('theme.pages.Report._monthly_chart', ['chart' => $invoiceChart])
            @include('theme.pages.Report._monthly_chart', ['chart' => $billChart])
        </div>

        <section aria-labelledby="ranking-title" class="space-y-4">
            <h2 id="ranking-title" class="admin-section-title">Classement des clients</h2>

            @if ($clients->isEmpty())
                <div class="admin-card">
                    <x-admin.empty-state icon="chart" title="Aucun client facturé" description="Le classement apparaît dès la première facture d’un client." />
                </div>
            @else
                <x-admin.table :headers="['Rang', 'Client', 'Chiffre d’affaires', 'Encaissé', '']">
                    @foreach ($clients as $client)
                        <tr>
                            <td class="text-fg-muted tabular-nums">{{ $loop->iteration }}</td>
                            <td class="font-medium"><a href="{{ $client->url }}" class="hover:underline">{{ $client->entreprise }}</a></td>
                            <td><x-admin.amount :value="$client->invoices_sum_price_total" /></td>
                            <td><x-admin.amount :value="$client->price_total_paid ?? 0" /></td>
                            <td class="text-end"><x-admin.icon-button icon="eye" :label="'Voir la fiche de '.$client->entreprise" href="{{ $client->url }}" size="sm" /></td>
                        </tr>
                    @endforeach
                </x-admin.table>
            @endif
        </section>
    </div>
@endsection
