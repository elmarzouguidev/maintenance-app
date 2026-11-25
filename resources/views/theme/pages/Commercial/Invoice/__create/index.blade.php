{{--
    New invoice (K2): one kit card form, without Livewire or jQuery. The company select shows the
    number the invoice will get; choosing a client loads its invoiceable tickets (repairable, on
    no invoice yet) from invoices.client.tickets, as the former Livewire components did. Articles
    use <x-commercial.articles-editor>. Same fields as before (InvoiceFormRequest).
--}}
@extends('layouts.admin')

@section('title', 'Nouvelle facture')

@section('content')
    @php
        $paymentModes = ['Espèce', 'Virement', 'Chèque', 'Carte bancaire', 'Lettre de change', 'Prélèvement', 'Virement bancaire'];
        $selectedCompany = (string) old('company', $companies->first()?->id);
    @endphp

    <div class="mx-auto w-full max-w-5xl space-y-6">
        <x-admin.page-header title="Nouvelle facture"
            :breadcrumbs="[['label' => __('navbar.invoices'), 'href' => route('commercial:invoices.index')], ['label' => 'Nouvelle facture']]" />

        <form method="POST" action="{{ route('commercial:invoices.store') }}" class="space-y-6"
            x-data="{
                company: @js($selectedCompany),
                numbers: @js($nextNumbers),
                tickets: [],
                selectedTickets: @js(array_map('strval', old('tickets', []))),
                loadingTickets: false,
                async loadTickets(client) {
                    this.tickets = [];
                    if (! client) {
                        return;
                    }
                    this.loadingTickets = true;
                    const url = @js(route('commercial:invoices.client.tickets', ['client' => '__client__'])).replace('__client__', client);
                    const response = await fetch(url, { headers: { Accept: 'application/json' } });
                    this.tickets = response.ok ? await response.json() : [];
                    this.loadingTickets = false;
                },
            }"
            x-init="loadTickets(@js((string) old('client', '')))"
            x-on:admin-select2:change.window="if ($event.detail.name === 'client') loadTickets($event.detail.value)">
            @csrf

            <x-messages.errors />

            <section aria-labelledby="invoice-info-title" class="admin-card space-y-5 p-5 sm:p-6">
                <h2 id="invoice-info-title" class="admin-section-title">{{ __('invoice.form.title') }}</h2>

                <div class="grid gap-4 md:grid-cols-3">
                    <x-admin.form-select name="company" :label="__('invoice.form.company')" :options="$companies->pluck('name', 'id')->all()" :value="$selectedCompany" :placeholder="null" required x-model="company" />
                    <x-admin.form-select2 name="client" :label="__('invoice.form.client')" :options="$clients->all()" placeholder="Choisir le client" search-placeholder="Rechercher un client…" required />
                    <div class="min-w-0">
                        <p class="admin-form-label">{{ __('invoice.form.number') }}</p>
                        <p class="flex h-10 items-center rounded-control bg-surface-muted px-3 font-semibold text-fg tabular-nums" x-text="numbers[company] ?? '—'"></p>
                        <p class="admin-form-help">Attribué à l’enregistrement.</p>
                    </div>

                    <x-admin.date-picker name="invoice_date" :label="__('invoice.form.date_invoice')" :value="old('invoice_date', now()->format('Y-m-d'))" required />
                    <x-admin.date-picker name="due_date" :label="__('invoice.form.date_due')" :value="old('due_date', \ticketApp::invoiceDueDate())" required />
                    <x-admin.form-select name="payment_mode" :label="__('invoice.form.payment_method')" :options="array_combine($paymentModes, $paymentModes)" value="Virement" :placeholder="null" required />

                    <x-admin.form-input name="bc_code" label="BC" help="Numéro du bon de commande du client." />
                    <x-admin.form-input name="bl_code" label="BL" help="Numéro du bon de livraison." />
                </div>

                <fieldset class="space-y-2 border-t border-line pt-5">
                    <legend class="admin-form-label">Tickets</legend>
                    <p x-show="loadingTickets" class="text-small text-fg-muted" role="status">Chargement des tickets…</p>
                    <p x-show="! loadingTickets && tickets.length === 0" class="text-small text-fg-muted">Choisissez un client : ses tickets réparables, sans facture, s’affichent ici.</p>
                    <div class="grid gap-2 sm:grid-cols-3 lg:grid-cols-6" x-show="tickets.length > 0" x-cloak>
                        <template x-for="ticket in tickets" :key="ticket.id">
                            <label class="flex items-center gap-2 text-body">
                                <input type="checkbox" name="tickets[]" class="admin-checkbox" :value="ticket.id" :checked="selectedTickets.includes(String(ticket.id))">
                                <span class="tabular-nums" x-text="ticket.code"></span>
                            </label>
                        </template>
                    </div>
                </fieldset>

                <x-admin.textarea name="admin_notes" :label="__('invoice.form.admin_note')" rows="2" help="Visible seulement dans l’application." />
            </section>

            <section aria-labelledby="invoice-articles-title" class="admin-card space-y-5 p-5 sm:p-6">
                <h2 id="invoice-articles-title" class="admin-section-title">Articles</h2>
                <x-commercial.articles-editor name="articles" required />
            </section>

            <section aria-labelledby="invoice-conditions-title" class="admin-card space-y-5 p-5 sm:p-6">
                <h2 id="invoice-conditions-title" class="sr-only">{{ __('invoice.form.condition_general') }}</h2>
                <x-admin.textarea name="condition_general" :label="__('invoice.form.condition_general')" rows="4" />

                <div class="flex flex-col-reverse gap-3 border-t border-line pt-6 sm:flex-row sm:justify-end">
                    <x-admin.button href="{{ route('commercial:invoices.index') }}" variant="secondary">Annuler</x-admin.button>
                    <x-admin.button type="submit">Enregistrer la facture</x-admin.button>
                </div>
            </section>
        </form>
    </div>
@endsection
