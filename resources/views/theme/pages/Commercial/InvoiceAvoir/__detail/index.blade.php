{{-- Credit note overview (K2): the kit detail pattern, articles and totals, like the invoice overview. --}}
@extends('layouts.admin')

@section('title', 'Avoir '.$invoice->full_number)

@section('content')
    @php($status = \App\Constants\InvoiceStatus::tryFrom((string) $invoice->status))

    <div class="mx-auto w-full max-w-5xl space-y-6">
        <x-admin.page-header :title="'Avoir '.$invoice->full_number" :eyebrow="optional($invoice->company)->name"
            :breadcrumbs="[['label' => 'Factures d’avoir', 'href' => route('commercial:invoices.index.avoir')], ['label' => $invoice->full_number]]">
            <x-slot:actions>
                @if ($status)
                    <x-admin.status-badge :status="$status" />
                @endif
                <x-admin.button variant="secondary" icon="printer" onclick="document.getElementById('print-avoir').showModal()">Imprimer</x-admin.button>
                @can('invoices.edit')
                    <x-admin.button href="{{ $invoice->edit_url }}" icon="pencil">Modifier</x-admin.button>
                @endcan
            </x-slot:actions>
        </x-admin.page-header>

        <x-admin.detail-page>
            <x-admin.detail-section title="Avoir">
                <dl class="grid gap-4 sm:grid-cols-2">
                    <x-admin.detail-item label="Client">
                        @if ($invoice->client)
                            <a href="{{ $invoice->client->url }}" class="font-medium hover:underline">{{ $invoice->client->entreprise }}</a>
                            <span class="block text-small text-fg-muted">{{ $invoice->client->addresse }}</span>
                            <span class="block text-small text-fg-muted tabular-nums">ICE {{ $invoice->client->ice }}</span>
                        @endif
                    </x-admin.detail-item>
                    <x-admin.detail-item label="Société">
                        {{ optional($invoice->company)->name }}
                        <span class="block text-small text-fg-muted">{{ optional($invoice->company)->addresse }}</span>
                        <span class="block text-small text-fg-muted tabular-nums">ICE {{ optional($invoice->company)->ice }}</span>
                    </x-admin.detail-item>
                    <x-admin.detail-item label="Date de facture"><x-admin.date :value="$invoice->invoice_date" /></x-admin.detail-item>
                    <x-admin.detail-item label="Facture"><span class="tabular-nums">{{ $invoice->invoice_number }}</span></x-admin.detail-item>
                    <x-admin.detail-item label="Mode de paiement">{{ $invoice->payment_mode }}</x-admin.detail-item>
                </dl>
            </x-admin.detail-section>

            <x-admin.detail-section title="Articles">
                @if ($invoice->articles->isEmpty())
                    <p class="text-small text-fg-muted">Aucun article.</p>
                @else
                    <x-admin.table :headers="['Article', 'Qté', 'Prix unitaire HT', 'Montant HT']">
                        @foreach ($invoice->articles as $article)
                            <tr>
                                <td class="whitespace-normal">{{ str_replace('<br />', '', $article->designation) }}</td>
                                <td class="tabular-nums">{{ $article->quantity }}</td>
                                <td><x-admin.amount :value="$article->prix_unitaire" /></td>
                                <td><x-admin.amount :value="$article->montant_ht" /></td>
                            </tr>
                        @endforeach
                    </x-admin.table>
                @endif
            </x-admin.detail-section>

            <x-slot:aside>
                <x-admin.stats-card title="Total HT" :value="\App\Support\UiKit\Format::money($invoice->price_ht)" icon="banknotes" variant="neutral" />
                <x-admin.stats-card title="TVA 20 %" :value="\App\Support\UiKit\Format::money($invoice->price_tva)" icon="document" variant="neutral" />
                <x-admin.stats-card title="Total TTC" :value="\App\Support\UiKit\Format::money($invoice->price_total)" icon="banknotes" />
            </x-slot:aside>
        </x-admin.detail-page>
    </div>

    <x-commercial.print-modal id="print-avoir" :title="'Télécharger l’avoir '.$invoice->full_number" route="public.show.invoice.avoir" :document="$invoice->uuid" />
@endsection
