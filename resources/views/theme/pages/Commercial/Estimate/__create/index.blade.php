{{--
    New estimate (K2), without Livewire or jQuery. Also the "estimate from a ticket" page
    (estimates.create.ticket, $ticket set): client and ticket are then fixed.
    The company select shows the number the estimate will get; choosing a client loads its
    tickets waiting for an estimate from estimates.client.tickets (the former Livewire Tickets
    component). Same fields as before (EstimateFormRequest); the "note client" field is gone:
    it was never saved (BL-77).
--}}
@extends('layouts.admin')

@php
    $pageTitle = $ticket ? 'Devis du ticket '.$ticket->code : 'Nouveau devis';
@endphp

@section('title', $pageTitle)

@section('content')
    @php
        $paymentModes = ['Espèce', 'Virement', 'Chèque', 'Carte bancaire', 'Lettre de change', 'Prélèvement', 'Virement bancaire'];
        $selectedCompany = (string) old('company', $companies->first()?->id);
    @endphp

    <div class="mx-auto w-full max-w-5xl space-y-6">
        <x-admin.page-header :title="$pageTitle"
            :breadcrumbs="[['label' => __('navbar.estimates'), 'href' => route('commercial:estimates.index')], ['label' => $pageTitle]]" />

        <form method="POST" action="{{ route('commercial:estimates.store') }}" class="space-y-6"
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
                    const url = @js(route('commercial:estimates.client.tickets', ['client' => '__client__'])).replace('__client__', client);
                    const response = await fetch(url, { headers: { Accept: 'application/json' } });
                    this.tickets = response.ok ? await response.json() : [];
                    this.loadingTickets = false;
                },
            }"
            @unless ($ticket)
                x-init="loadTickets(@js((string) old('client', '')))"
                x-on:admin-select2:change.window="if ($event.detail.name === 'client') loadTickets($event.detail.value)"
            @endunless>
            @csrf

            <x-messages.errors />

            <section aria-labelledby="estimate-info-title" class="admin-card space-y-5 p-5 sm:p-6">
                <h2 id="estimate-info-title" class="admin-section-title">Informations du devis</h2>

                <div class="grid gap-4 md:grid-cols-3">
                    <x-admin.form-select name="company" :label="__('invoice.form.company')" :options="$companies->pluck('name', 'id')->all()" :value="$selectedCompany" :placeholder="null" required x-model="company" />
                    @if ($ticket)
                        <input type="hidden" name="client" value="{{ $ticket->client_id }}">
                        <input type="hidden" name="ticket" value="{{ $ticket->id }}">
                        <dl class="contents">
                            <x-admin.detail-item :label="__('invoice.form.client')">{{ optional($ticket->client)->entreprise }}</x-admin.detail-item>
                        </dl>
                    @else
                        <x-admin.form-select2 name="client" :label="__('invoice.form.client')" :options="$clients->all()" placeholder="Choisir le client" search-placeholder="Rechercher un client…" required />
                    @endif
                    <div class="min-w-0">
                        <p class="admin-form-label">{{ __('estimate.form.number') }}</p>
                        <p class="flex h-10 items-center rounded-control bg-surface-muted px-3 font-semibold text-fg tabular-nums" x-text="numbers[company] ?? '—'"></p>
                        <p class="admin-form-help">Attribué à l’enregistrement.</p>
                    </div>

                    <x-admin.date-picker name="estimate_date" :label="__('estimate.form.date_estimate')" :value="old('estimate_date', now()->format('Y-m-d'))" required />
                    <x-admin.date-picker name="due_date" :label="__('estimate.form.date_due')" :value="old('due_date', \ticketApp::estimateDueDate())" required />
                    <x-admin.form-select name="payment_mode" :label="__('estimate.form.payment_method')" :options="array_combine($paymentModes, $paymentModes)" value="Virement" :placeholder="null" required />
                </div>

                @if ($ticket)
                    <dl class="border-t border-line pt-5">
                        <x-admin.detail-item label="Ticket"><span class="tabular-nums">{{ $ticket->code }}</span> · {{ $ticket->article }}</x-admin.detail-item>
                    </dl>
                @else
                    <fieldset class="space-y-2 border-t border-line pt-5">
                        <legend class="admin-form-label">Tickets</legend>
                        <p x-show="loadingTickets" class="text-small text-fg-muted" role="status">Chargement des tickets…</p>
                        <p x-show="! loadingTickets && tickets.length === 0" class="text-small text-fg-muted">Choisissez un client : ses tickets en attente de devis s’affichent ici.</p>
                        <div class="grid gap-2 sm:grid-cols-3 lg:grid-cols-6" x-show="tickets.length > 0" x-cloak>
                            <template x-for="ticket in tickets" :key="ticket.id">
                                <label class="flex items-center gap-2 text-body">
                                    <input type="checkbox" name="tickets[]" class="admin-checkbox" :value="ticket.id" :checked="selectedTickets.includes(String(ticket.id))">
                                    <span class="tabular-nums" x-text="ticket.code"></span>
                                </label>
                            </template>
                        </div>
                    </fieldset>
                @endif

                <x-admin.textarea name="admin_notes" :label="__('estimate.form.admin_note')" rows="2" help="Visible seulement dans l’application." />
            </section>

            <section aria-labelledby="estimate-articles-title" class="admin-card space-y-5 p-5 sm:p-6">
                <h2 id="estimate-articles-title" class="admin-section-title">Articles</h2>
                <x-commercial.articles-editor name="articles" required />
            </section>

            <section aria-labelledby="estimate-conditions-title" class="admin-card space-y-5 p-5 sm:p-6">
                <h2 id="estimate-conditions-title" class="sr-only">{{ __('estimate.form.condition_general') }}</h2>
                <x-admin.textarea name="condition_general" :label="__('estimate.form.condition_general')" rows="4" />

                <div class="flex flex-col-reverse gap-3 border-t border-line pt-6 sm:flex-row sm:justify-end">
                    <x-admin.button href="{{ route('commercial:estimates.index') }}" variant="secondary">Annuler</x-admin.button>
                    <x-admin.button type="submit">Enregistrer le devis</x-admin.button>
                </div>
            </section>
        </form>
    </div>
@endsection
