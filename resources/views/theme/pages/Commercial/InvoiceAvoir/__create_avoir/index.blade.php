{{--
    New credit note (K2), without Livewire or jQuery. Choosing the invoice (one without a credit
    note yet) gives the company, the client and the credit note number, as the former Livewire
    component commercial.avoir.create.info did. Articles have no discount. Same fields as before
    (AvoirFormRequest).
--}}
@extends('layouts.admin')

@section('title', 'Nouvel avoir')

@section('content')
    @php
        $paymentModes = ['Espèce', 'Virement', 'Chèque', 'Carte bancaire', 'Lettre de change', 'Prélèvement', 'Virement bancaire'];
        $invoiceOptions = $invoices->mapWithKeys(fn ($invoice) => [$invoice->id => $invoice->full_number.' · '.optional($invoice->client)->entreprise])->all();
    @endphp

    <div class="mx-auto w-full max-w-5xl space-y-6">
        <x-admin.page-header title="Nouvel avoir"
            :breadcrumbs="[['label' => 'Factures d’avoir', 'href' => route('commercial:invoices.index.avoir')], ['label' => 'Nouvel avoir']]" />

        <form method="POST" action="{{ route('commercial:invoices.store.avoir') }}" class="space-y-6"
            x-data="{
                choices: @js($invoiceChoices),
                invoice: @js((string) old('invoice_number', '')),
                get chosen() { return this.choices[this.invoice] ?? null },
            }"
            x-on:admin-select2:change.window="if ($event.detail.name === 'invoice_number') invoice = String($event.detail.value)">
            @csrf
            <input type="hidden" name="company" :value="chosen?.company ?? ''">
            <input type="hidden" name="client" :value="chosen?.client ?? ''">

            <x-messages.errors />

            <section aria-labelledby="avoir-info-title" class="admin-card space-y-5 p-5 sm:p-6">
                <h2 id="avoir-info-title" class="admin-section-title">Informations de l’avoir</h2>

                <div class="grid gap-4 md:grid-cols-2">
                    <x-admin.form-select2 name="invoice_number" label="Facture" :options="$invoiceOptions" placeholder="Choisir la facture" search-placeholder="Numéro ou client…" required />
                    <div class="min-w-0">
                        <p class="admin-form-label">Numéro de l’avoir</p>
                        <p class="flex h-10 items-center rounded-control bg-surface-muted px-3 font-semibold text-fg tabular-nums" x-text="chosen?.number ?? '—'"></p>
                        <p class="admin-form-help">Attribué à l’enregistrement.</p>
                    </div>
                </div>

                <dl class="grid gap-4 sm:grid-cols-2" x-show="chosen" x-cloak>
                    <x-admin.detail-item :label="__('invoice.form.company')"><span x-text="chosen?.companyName"></span></x-admin.detail-item>
                    <x-admin.detail-item :label="__('invoice.form.client')"><span x-text="chosen?.clientName"></span></x-admin.detail-item>
                </dl>

                <div class="grid gap-4 md:grid-cols-2">
                    <x-admin.date-picker name="invoice_date" :label="__('invoice.form.date_invoice')" :value="old('invoice_date', now()->format('Y-m-d'))" required />
                    <x-admin.form-select name="payment_mode" :label="__('invoice.form.payment_method')" :options="array_combine($paymentModes, $paymentModes)" value="Virement" :placeholder="null" required />
                </div>

                <x-admin.textarea name="admin_notes" :label="__('invoice.form.admin_note')" rows="2" help="Visible seulement dans l’application." />
            </section>

            <section aria-labelledby="avoir-articles-title" class="admin-card space-y-5 p-5 sm:p-6">
                <h2 id="avoir-articles-title" class="admin-section-title">Articles</h2>
                <x-commercial.articles-editor name="articles" required :discount="false" />
            </section>

            <section aria-labelledby="avoir-conditions-title" class="admin-card space-y-5 p-5 sm:p-6">
                <h2 id="avoir-conditions-title" class="sr-only">{{ __('invoice.form.condition_general') }}</h2>
                <x-admin.textarea name="condition_general" :label="__('invoice.form.condition_general')" rows="4" />

                <div class="flex flex-col-reverse gap-3 border-t border-line pt-6 sm:flex-row sm:justify-end">
                    <x-admin.button href="{{ route('commercial:invoices.index.avoir') }}" variant="secondary">Annuler</x-admin.button>
                    <x-admin.button type="submit">Enregistrer l’avoir</x-admin.button>
                </div>
            </section>
        </form>
    </div>
@endsection
