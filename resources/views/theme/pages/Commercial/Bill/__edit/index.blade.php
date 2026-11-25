{{--
    Edit a payment (K2): bills.update, same fields as before (date, mode, reference, note). The
    invoice and the amount are shown only: the amount is the invoice's total.
--}}
@extends('layouts.admin')

@section('title', 'Règlement '.$bill->code)

@section('content')
    @php
        $paymentModes = ['Espèce', 'Virement', 'Chèque', 'Carte bancaire', 'Lettre de change', 'Prélèvement', 'Virement bancaire'];
        $invoice = $bill->billable;
    @endphp

    <div class="mx-auto w-full max-w-3xl space-y-6">
        <x-admin.page-header :title="'Règlement '.$bill->code" :eyebrow="optional($invoice?->company)->name"
            :description="'Ajouté par '.$bill->added_by.' · modifié le '.$bill->updated_at?->format('d/m/Y à H:i')"
            :breadcrumbs="[['label' => 'Règlements', 'href' => route('commercial:bills.index')], ['label' => $bill->code]]">
            @if ($invoice)
                <x-slot:actions>
                    <x-admin.button href="{{ route('public.show.invoice', [$invoice->uuid, 'has_header' => true]) }}" target="_blank" rel="noopener" variant="secondary" icon="printer">Facture</x-admin.button>
                </x-slot:actions>
            @endif
        </x-admin.page-header>

        <form method="POST" action="{{ route('commercial:bills.update', $bill->uuid) }}" class="space-y-6">
            @csrf

            <x-messages.errors />

            <section aria-labelledby="bill-invoice-title" class="admin-card space-y-5 p-5 sm:p-6">
                <h2 id="bill-invoice-title" class="admin-section-title">Facture</h2>
                <dl class="grid gap-4 sm:grid-cols-3">
                    <x-admin.detail-item label="Facture"><span class="tabular-nums">{{ $invoice?->full_number }}</span></x-admin.detail-item>
                    <x-admin.detail-item label="Client">{{ optional($invoice?->client)->entreprise }}</x-admin.detail-item>
                    <x-admin.detail-item label="Montant TTC"><span class="font-semibold"><x-admin.amount :value="$bill->price_total" /></span></x-admin.detail-item>
                    <x-admin.detail-item label="Date de facture"><x-admin.date :value="$invoice?->invoice_date" /></x-admin.detail-item>
                    <x-admin.detail-item label="Échéance"><x-admin.date :value="$invoice?->due_date" /></x-admin.detail-item>
                </dl>
            </section>

            <section aria-labelledby="bill-info-title" class="admin-card space-y-5 p-5 sm:p-6">
                <h2 id="bill-info-title" class="admin-section-title">Règlement</h2>
                <div class="grid gap-4 sm:grid-cols-2">
                    <x-admin.date-picker name="bill_date" label="Date de règlement" :value="old('bill_date', $bill->bill_date?->format('Y-m-d'))" required />
                    <x-admin.form-select name="bill_mode" label="Mode de règlement" :options="array_combine($paymentModes, $paymentModes)" :value="(string) $bill->bill_mode" :placeholder="null" required />
                </div>
                <x-admin.form-input name="reference" label="Référence de transaction" :value="(string) $bill->reference" />
                <x-admin.textarea name="notes" label="Note" :value="(string) $bill->notes" rows="3" />

                <div class="flex flex-col-reverse gap-3 border-t border-line pt-6 sm:flex-row sm:justify-end">
                    <x-admin.button href="{{ route('commercial:bills.index') }}" variant="secondary">Retour à la liste</x-admin.button>
                    <x-admin.button type="submit">Enregistrer le règlement</x-admin.button>
                </div>
            </section>
        </form>
    </div>
@endsection
