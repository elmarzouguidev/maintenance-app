{{--
    Edit a ticket (K2), without jQuery, Select2 or SweetAlert. Same fields as before
    (TicketUpdateFormRequest): article, client, entry date (posted as d-m-Y, converted from the
    kit date picker on submit) and description (rich text). Aside: actions, the SuperAdmin
    reassignment (#reassignment, linked from the lists) and the status history.
--}}
@extends('layouts.admin')

@section('title', 'Modifier le ticket '.$ticket->code)

@section('content')
    @php($canReassign = auth()->user()->can('ticket.reassign'))

    <div class="mx-auto w-full max-w-7xl space-y-6">
        <x-admin.page-header :title="'Modifier le ticket '.$ticket->code" :eyebrow="optional($ticket->client)->entreprise"
            :breadcrumbs="[['label' => 'Tickets', 'href' => route('admin:tickets.list')], ['label' => $ticket->code, 'href' => $ticket->url], ['label' => 'Modifier']]">
            <x-slot:actions>
                <x-tickets.status-badge :ticket="$ticket" />
                <x-admin.button href="{{ route('admin:tickets.report.generate', $ticket->uuid) }}" target="_blank" rel="noopener" variant="secondary" icon="document">Rapport</x-admin.button>
                <x-admin.button href="{{ $ticket->media_url }}" variant="secondary" icon="photo">Photos</x-admin.button>
                @can('ticket.delete')
                    <x-admin.delete-action :action="route('admin:tickets.delete', ['ticket' => $ticket->uuid])"
                        :confirm="'Supprimer le ticket '.$ticket->code.' ? Ses statuts, devis, factures, garantie, livraison et rapports liés seront détachés ou supprimés.'" />
                @endcan
            </x-slot:actions>
        </x-admin.page-header>

        <x-messages.errors />

        <div class="grid gap-6 lg:grid-cols-3">
            <form method="POST" action="{{ $ticket->update_url }}" class="space-y-6 lg:col-span-2"
                x-on:submit="
                    const entry = $el.elements.namedItem('entry_date')?.value;
                    $refs.createdAt.value = entry ? entry.split('-').reverse().join('-') : '';
                    $el.elements.namedItem('entry_date').disabled = true;
                ">
                @csrf
                @method('PUT')
                <input type="hidden" name="created_at" x-ref="createdAt">

                <section aria-labelledby="ticket-info-title" class="admin-card space-y-5 p-5 sm:p-6">
                    <h2 id="ticket-info-title" class="admin-section-title">Ticket</h2>

                    <div class="grid gap-4 sm:grid-cols-2">
                        <x-admin.form-input name="article" label="Article" :value="$ticket->article" required />
                        <x-admin.date-picker name="entry_date" label="Date d’entrée" :value="$ticket->created_at?->format('Y-m-d')" required />
                    </div>
                    <x-admin.form-select2 name="client_id" label="Client" :options="$clients->pluck('entreprise', 'id')->all()" :value="(string) $ticket->client_id" placeholder="Choisir le client" search-placeholder="Rechercher un client…" required />
                    <x-forms.rich-text name="description" label="Description" :value="$ticket->description" required />
                </section>

                <div class="flex flex-col-reverse gap-3 sm:flex-row sm:justify-end">
                    <x-admin.button href="{{ $ticket->url }}" variant="secondary">Annuler</x-admin.button>
                    <x-admin.button type="submit">Enregistrer le ticket</x-admin.button>
                </div>
            </form>

            <div class="space-y-6">
                @php($photo = $ticket->getFirstMediaUrl('tickets-images'))
                @if ($photo)
                    <a href="{{ $photo }}" target="_blank" rel="noopener" class="admin-card block overflow-hidden">
                        <img src="{{ $photo }}" alt="Photo : {{ $ticket->article }}" class="aspect-video w-full object-cover">
                    </a>
                @endif

                @if ($canReassign)
                    <section id="reassignment" aria-labelledby="ticket-reassign-title" class="admin-card space-y-4 p-5">
                        <h2 id="ticket-reassign-title" class="admin-section-title">Réassigner le ticket</h2>
                        <dl>
                            <x-admin.detail-item label="Technicien actuel">{{ optional($ticket->technicien)->full_name ?: 'Non assigné' }}</x-admin.detail-item>
                        </dl>
                        <form method="POST" action="{{ route('admin:tickets.reassign', $ticket->uuid) }}" class="space-y-4">
                            @csrf
                            <x-admin.form-select name="technicien_id" label="Nouveau technicien" :options="$techniciens->reject(fn ($technicien) => $technicien->id === $ticket->user_id)->mapWithKeys(fn ($technicien) => [$technicien->id => $technicien->full_name])->all()" placeholder="Choisir un technicien" required />
                            <x-admin.textarea name="reassignment_reason" label="Raison" rows="3" placeholder="Expliquez la raison de cette réassignation…" help="Enregistrée dans l’historique du ticket." required />
                            <x-admin.button type="submit" variant="secondary" icon="arrows">Réassigner</x-admin.button>
                        </form>
                    </section>
                @endif

                <section aria-labelledby="ticket-history-title" class="admin-card">
                    <div class="admin-card-header flex items-center justify-between gap-3">
                        <h2 id="ticket-history-title" class="admin-section-title">Historique</h2>
                        <a href="{{ $ticket->historical_url }}" class="text-small font-medium text-accent hover:underline">Tout voir</a>
                    </div>
                    <div class="admin-card-body">
                        @if ($ticket->statuses->isEmpty())
                            <p class="text-small text-fg-muted">Aucun historique.</p>
                        @else
                            <ol class="divide-y divide-line">
                                @foreach ($ticket->statuses as $history)
                                    <li class="space-y-1 py-2.5">
                                        <p class="whitespace-pre-line text-small text-fg">{{ $history->pivot?->description ?: $history->name }}</p>
                                        <p class="text-caption text-fg-muted"><x-admin.date :value="$history->pivot?->start_at" time /></p>
                                    </li>
                                @endforeach
                            </ol>
                        @endif
                    </div>
                </section>
            </div>
        </div>
    </div>
@endsection
