{{--
    New ticket (K2), without jQuery or Select2. Same fields as before (TicketFormRequest): a
    returned device ("Retour ?") picks the original ticket, which fills the article and the
    client as the former script did; "Ajouter un client" opens a kit dialog posting to
    clients.createPost, like the former modal (it then opens the new client's page).
--}}
@extends('layouts.admin')

@section('title', 'Nouveau ticket')

@section('content')
    @php
        $returnChoices = $tickets->mapWithKeys(fn ($ticket) => [$ticket->id => ['client' => (string) $ticket->client_id, 'article' => $ticket->article]])->all();
        $returnOptions = $tickets->mapWithKeys(fn ($ticket) => [$ticket->id => $ticket->code.' · '.$ticket->article.($ticket->retour_number ? ' · '.$ticket->retour_number.' retour(s)' : '')])->all();
    @endphp

    <div class="mx-auto w-full max-w-3xl space-y-6">
        <x-admin.page-header title="Nouveau ticket"
            :breadcrumbs="[['label' => 'Tickets', 'href' => route('admin:tickets.list')], ['label' => 'Nouveau ticket']]" />

        <form method="POST" action="{{ route('admin:tickets.createPost') }}" enctype="multipart/form-data" class="space-y-6"
            x-data="{
                returned: @js((bool) old('is_retour')),
                choices: @js($returnChoices),
                article: @js((string) old('article', '')),
                chooseReturn(ticket) {
                    const choice = this.choices[ticket];
                    if (! choice) {
                        return;
                    }
                    this.article = choice.article;
                    window.dispatchEvent(new CustomEvent('admin-select2:value', { detail: { name: 'client', value: choice.client } }));
                },
            }"
            x-on:admin-select2:change.window="if ($event.detail.name === 'ticket_retoure') chooseReturn($event.detail.value)">
            @csrf

            <x-messages.errors />

            <section aria-labelledby="ticket-device-title" class="admin-card space-y-5 p-5 sm:p-6">
                <h2 id="ticket-device-title" class="admin-section-title">Appareil</h2>

                <div class="space-y-3">
                    <label class="flex items-center gap-2 text-body">
                        <input type="checkbox" name="is_retour" value="on" class="admin-checkbox" x-model="returned">
                        Retour d’un appareil déjà réparé
                    </label>
                    <template x-if="returned">
                        <x-admin.form-select2 name="ticket_retoure" label="Ticket d’origine" :options="$returnOptions" placeholder="Choisir le ticket retourné" search-placeholder="Numéro ou article…" required />
                    </template>
                </div>

                <x-admin.form-input name="article" label="Article" placeholder="Exemple : imprimante HP LaserJet" x-model="article" required />

                <div class="flex flex-col gap-3 sm:flex-row sm:items-end">
                    <div class="min-w-0 flex-1">
                        <x-admin.form-select2 name="client" label="Client" :options="$clients->pluck('entreprise', 'id')->all()" placeholder="Choisir le client" search-placeholder="Rechercher un client…" required />
                    </div>
                    <x-admin.button variant="secondary" icon="plus" onclick="document.getElementById('create-client').showModal()">Ajouter un client</x-admin.button>
                </div>

                <x-admin.textarea name="description" label="Description de la panne" rows="4" required />
                <x-admin.file-upload name="photo" label="Photo" accept="image/png,image/jpeg" required />
            </section>

            <div class="flex flex-col-reverse gap-3 sm:flex-row sm:justify-end">
                <x-admin.button href="{{ route('admin:tickets.list') }}" variant="secondary">Annuler</x-admin.button>
                <x-admin.button type="submit">Enregistrer le ticket</x-admin.button>
            </div>
        </form>
    </div>

    <x-admin.modal id="create-client" title="Ajouter un client" description="Le client est enregistré et sa fiche s’ouvre ; revenez ensuite créer le ticket.">
        <form id="create-client-form" method="POST" action="{{ route('admin:clients.createPost') }}" class="grid gap-4 sm:grid-cols-2">
            @csrf
            <x-admin.form-input name="entreprise" label="Entreprise" required />
            <x-admin.form-input name="contact" label="Contact" required />
            <x-admin.form-input name="telephone" label="Téléphone" type="tel" required />
            <x-admin.form-input name="email" label="E-mail" type="email" required />
            <div class="sm:col-span-2">
                <x-admin.form-input name="addresse" label="Adresse" required />
            </div>
            <x-admin.form-input name="rc" label="RC" inputmode="numeric" />
            <x-admin.form-input name="ice" label="ICE" inputmode="numeric" help="15 ou 16 chiffres." required />
        </form>
        <x-slot:footer>
            <x-admin.button variant="secondary" onclick="this.closest('dialog').close()">Annuler</x-admin.button>
            <x-admin.button type="submit" form="create-client-form">Enregistrer le client</x-admin.button>
        </x-slot:footer>
    </x-admin.modal>
@endsection
