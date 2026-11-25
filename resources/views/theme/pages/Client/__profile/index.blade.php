{{--
    Client page (K2): the kit detail pattern. Contact details, the monthly invoiced total and the
    client's tickets; the logo and the key figures in the aside. The chart crashed before (BL-67).
--}}
@extends('layouts.admin')

@section('title', $client->entreprise)

@section('content')
    @php($logoUrl = $client->getFirstMediaUrl('clients-logo', 'thumb'))

    <div class="mx-auto w-full max-w-5xl space-y-6">
        <x-admin.page-header :title="$client->entreprise" :eyebrow="'Client '.$client->code" :description="$client->contact"
            :breadcrumbs="[['label' => 'Clients', 'href' => route('admin:clients.index')], ['label' => $client->entreprise]]">
            @can('client.edit')
                <x-slot:actions>
                    <x-admin.button href="{{ $client->edit }}" icon="pencil">Modifier</x-admin.button>
                </x-slot:actions>
            @endcan
        </x-admin.page-header>

        <x-admin.detail-page>
            <x-admin.detail-section title="Coordonnées">
                <dl class="grid gap-4 sm:grid-cols-2">
                    <x-admin.detail-item label="Contact">{{ $client->contact }}</x-admin.detail-item>
                    <x-admin.detail-item label="Téléphone"><span class="tabular-nums">{{ $client->telephone }}</span></x-admin.detail-item>
                    <x-admin.detail-item label="E-mail">{{ $client->email }}</x-admin.detail-item>
                    <x-admin.detail-item label="Siège social">{{ $client->addresse }}</x-admin.detail-item>
                    <x-admin.detail-item label="ICE"><span class="tabular-nums">{{ $client->ice }}</span></x-admin.detail-item>
                    <x-admin.detail-item label="RC">
                        @if (filled($client->rc))
                            <span class="tabular-nums">{{ $client->rc }}</span>
                        @else
                            <span class="text-fg-muted">Non renseigné</span>
                        @endif
                    </x-admin.detail-item>
                </dl>
                @if (filled($client->description))
                    <p class="mt-4 text-body text-fg">{{ $client->description }}</p>
                @endif
            </x-admin.detail-section>

            <x-admin.detail-section title="Chiffre d’affaires">
                <x-charts.monthly :chart="$chart" />
            </x-admin.detail-section>

            <x-admin.detail-section title="Tickets">
                @if ($client->tickets->isEmpty())
                    <x-admin.empty-state icon="wrench" title="Aucun ticket" description="Ce client n’a pas encore déposé d’appareil." />
                @else
                    <x-admin.table :headers="['Ticket', 'Produit', 'Date', 'Statut']">
                        @foreach ($client->tickets as $ticket)
                            <tr>
                                <td class="font-medium tabular-nums"><a href="{{ $ticket->url }}" class="hover:underline">{{ $ticket->code }}</a></td>
                                <td>{{ $ticket->article }}</td>
                                <td><x-admin.date :value="$ticket->created_at" /></td>
                                <td>{{ __('status.statuses.'.$ticket->status) }}</td>
                            </tr>
                        @endforeach
                    </x-admin.table>
                @endif
            </x-admin.detail-section>

            <x-slot:aside>
                @if ($logoUrl !== '')
                    <div class="admin-card flex justify-center p-5">
                        <img src="{{ $logoUrl }}" alt="Logo de {{ $client->entreprise }}" class="h-20 w-auto object-contain">
                    </div>
                @endif
                <x-admin.stats-card title="Tickets" :value="$client->tickets->count()" icon="wrench" variant="info" />
                <x-admin.stats-card title="Chiffre d’affaires" :value="\App\Support\UiKit\Format::money($client->invoices_sum_price_total ?? 0)" icon="banknotes" />
                <x-admin.stats-card title="Encaissé" :value="\App\Support\UiKit\Format::money($client->price_total_paid ?? 0)" icon="check-circle" variant="success" />
            </x-slot:aside>
        </x-admin.detail-page>
    </div>
@endsection
