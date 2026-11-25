{{--
    Add a payment to one invoice (K2): bills.addBill, posting to bills.storeBill like the former
    page. The payment covers the invoice's total; the invoice is then marked paid.
--}}
@extends('layouts.admin')

@section('title', 'Règlement de la facture '.$invoice->full_number)

@section('content')
    @php($paymentModes = ['Espèce', 'Virement', 'Chèque', 'Carte bancaire', 'Lettre de change', 'Prélèvement', 'Virement bancaire'])

    <div class="mx-auto w-full max-w-3xl space-y-6">
        <x-admin.page-header :title="'Règlement de la facture '.$invoice->full_number" :eyebrow="optional($invoice->company)->name"
            :breadcrumbs="[['label' => 'Règlements', 'href' => route('commercial:bills.index')], ['label' => $invoice->full_number]]" />

        <form method="POST" action="{{ route('commercial:bills.storeBill', $invoice->uuid) }}" class="space-y-6">
            @csrf

            <x-messages.errors />

            <section aria-labelledby="bill-invoice-title" class="admin-card space-y-5 p-5 sm:p-6">
                <h2 id="bill-invoice-title" class="admin-section-title">Facture</h2>
                <dl class="grid gap-4 sm:grid-cols-3">
                    <x-admin.detail-item label="Client">{{ optional($invoice->client)->entreprise }}</x-admin.detail-item>
                    <x-admin.detail-item label="Date de facture"><x-admin.date :value="$invoice->invoice_date" /></x-admin.detail-item>
                    <x-admin.detail-item label="Échéance"><x-admin.date :value="$invoice->due_date" /></x-admin.detail-item>
                    <x-admin.detail-item label="Montant TTC"><span class="font-semibold"><x-admin.amount :value="$invoice->price_total" /></span></x-admin.detail-item>
                </dl>
            </section>

            <section aria-labelledby="bill-info-title" class="admin-card space-y-5 p-5 sm:p-6">
                <h2 id="bill-info-title" class="admin-section-title">Règlement</h2>
                <div class="grid gap-4 sm:grid-cols-2">
                    <x-admin.date-picker name="bill_date" label="Date de règlement" :value="old('bill_date', now()->format('Y-m-d'))" required />
                    <x-admin.form-select name="bill_mode" label="Mode de règlement" :options="array_combine($paymentModes, $paymentModes)" value="Virement" :placeholder="null" required />
                </div>
                <x-admin.form-input name="reference" label="Référence de transaction" placeholder="Exemple : chèque n° 4552154221" />
                <x-admin.textarea name="notes" label="Note" rows="3" />

                <div class="flex flex-col-reverse gap-3 border-t border-line pt-6 sm:flex-row sm:justify-end">
                    <x-admin.button href="{{ route('commercial:bills.index') }}" variant="secondary">Annuler</x-admin.button>
                    <x-admin.button type="submit">Enregistrer le règlement</x-admin.button>
                </div>
            </section>
        </form>
    </div>
@endsection
