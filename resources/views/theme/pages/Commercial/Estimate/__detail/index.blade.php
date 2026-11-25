{{--
    Estimate overview (K2): the kit detail pattern, articles and totals, like the invoice overview.
--}}
@extends('layouts.admin')

@section('title', 'Devis '.$estimate->full_number)

@section('content')
    @php($status = \App\Constants\EstimateStatus::tryFrom((int) $estimate->status))

    <div class="mx-auto w-full max-w-5xl space-y-6">
        <x-admin.page-header :title="'Devis '.$estimate->full_number" :eyebrow="optional($estimate->company)->name"
            :breadcrumbs="[['label' => __('navbar.estimates'), 'href' => route('commercial:estimates.index')], ['label' => $estimate->full_number]]">
            <x-slot:actions>
                @if ($status)
                    <x-admin.status-badge :status="$status" />
                @endif
                <x-admin.button variant="secondary" icon="printer" onclick="document.getElementById('print-estimate').showModal()">Imprimer</x-admin.button>
                @can('estimates.edit')
                    <x-admin.button href="{{ $estimate->edit_url }}" icon="pencil">Modifier</x-admin.button>
                @endcan
            </x-slot:actions>
        </x-admin.page-header>

        <x-admin.detail-page>
            <x-admin.detail-section title="Devis">
                <dl class="grid gap-4 sm:grid-cols-2">
                    <x-admin.detail-item label="Client">
                        @if ($estimate->client)
                            <a href="{{ $estimate->client->url }}" class="font-medium hover:underline">{{ $estimate->client->entreprise }}</a>
                            <span class="block text-small text-fg-muted">{{ $estimate->client->addresse }}</span>
                            <span class="block text-small text-fg-muted tabular-nums">ICE {{ $estimate->client->ice }}</span>
                        @endif
                    </x-admin.detail-item>
                    <x-admin.detail-item label="Société">
                        {{ optional($estimate->company)->name }}
                        <span class="block text-small text-fg-muted">{{ optional($estimate->company)->addresse }}</span>
                        <span class="block text-small text-fg-muted tabular-nums">ICE {{ optional($estimate->company)->ice }}</span>
                    </x-admin.detail-item>
                    <x-admin.detail-item label="Date du devis"><x-admin.date :value="$estimate->estimate_date" /></x-admin.detail-item>
                    <x-admin.detail-item label="Échéance"><x-admin.date :value="$estimate->due_date" /></x-admin.detail-item>
                    <x-admin.detail-item label="Mode de paiement">{{ $estimate->payment_mode }}</x-admin.detail-item>
                </dl>
            </x-admin.detail-section>

            <x-admin.detail-section title="Articles">
                @if ($estimate->articles->isEmpty())
                    <p class="text-small text-fg-muted">Aucun article.</p>
                @else
                    <x-admin.table :headers="['Article', 'Qté', 'Prix unitaire HT', 'Remise', 'Montant HT']">
                        @foreach ($estimate->articles as $article)
                            <tr>
                                <td class="whitespace-normal">{{ str_replace('<br />', '', $article->designation) }}</td>
                                <td class="tabular-nums">{{ $article->quantity }}</td>
                                <td><x-admin.amount :value="$article->prix_unitaire" /></td>
                                <td class="tabular-nums">{{ (float) $article->remise > 0 ? $article->remise.' %' : '—' }}</td>
                                <td><x-admin.amount :value="$article->montant_ht" /></td>
                            </tr>
                        @endforeach
                    </x-admin.table>
                @endif
            </x-admin.detail-section>

            <x-slot:aside>
                <x-admin.stats-card title="Total HT" :value="\App\Support\UiKit\Format::money($estimate->price_ht)" icon="banknotes" variant="neutral" />
                <x-admin.stats-card title="TVA 20 %" :value="\App\Support\UiKit\Format::money($estimate->price_tva)" icon="document" variant="neutral" />
                <x-admin.stats-card title="Total TTC" :value="\App\Support\UiKit\Format::money($estimate->price_total)" icon="banknotes" />
            </x-slot:aside>
        </x-admin.detail-page>
    </div>

    <x-commercial.print-modal id="print-estimate" :title="'Télécharger le devis '.$estimate->full_number" route="public.show.estimate" :document="$estimate->uuid" />
@endsection
