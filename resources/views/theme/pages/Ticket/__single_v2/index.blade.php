{{--
    Ticket overview (K2): the kit detail pattern, replacing the Bootstrap pill gallery and the
    jQuery lightbox (photos open full size in a new tab). Same content and role rules as before:
    edit and full report for SuperAdmin/Admin, photos for SuperAdmin/Reception, documents
    (estimate, invoice, full report) for SuperAdmin/Admin.
--}}
@extends('layouts.admin')

@section('title', 'Ticket '.$ticket->code)

@section('content')
    @php
        $user = auth()->user();
        $isManager = $user->hasAnyRole('SuperAdmin', 'Admin');
        $photos = $ticket->getMedia('tickets-images');
    @endphp

    <div class="mx-auto w-full max-w-5xl space-y-6">
        <x-admin.page-header :title="'Ticket '.$ticket->code" :eyebrow="optional($ticket->client)->entreprise"
            :breadcrumbs="[['label' => 'Tickets', 'href' => route('admin:tickets.list')], ['label' => $ticket->code]]">
            <x-slot:actions>
                <x-tickets.status-badge :ticket="$ticket" />
                @if ($user->hasAnyRole('SuperAdmin', 'Reception'))
                    <x-admin.button href="{{ $ticket->media_url }}" variant="secondary" icon="photo">Photos</x-admin.button>
                @endif
                @if ($isManager)
                    <x-admin.button href="{{ route('admin:tickets.report.generate', $ticket->uuid) }}" target="_blank" rel="noopener" variant="secondary" icon="document">Rapport complet</x-admin.button>
                    <x-admin.button href="{{ $ticket->edit }}" icon="pencil">Modifier</x-admin.button>
                @endif
            </x-slot:actions>
        </x-admin.page-header>

        <x-admin.detail-page>
            <x-admin.detail-section :title="$ticket->article">
                @if ($photos->isNotEmpty())
                    <ul class="grid grid-cols-2 gap-3 sm:grid-cols-3" aria-label="Photos">
                        @foreach ($photos as $photo)
                            <li>
                                <a href="{{ $photo->getFullUrl() }}" target="_blank" rel="noopener" class="block overflow-hidden rounded-control border border-line hover:border-line-strong">
                                    <img src="{{ $photo->getFullUrl() }}" alt="Photo {{ $loop->iteration }} : {{ $ticket->article }}" class="aspect-square w-full object-cover" loading="lazy">
                                </a>
                            </li>
                        @endforeach
                    </ul>
                @endif
                <div class="space-y-1">
                    <h3 class="admin-form-label">Description</h3>
                    <div class="text-body text-fg">{!! sanitize_html($ticket->description) !!}</div>
                </div>
            </x-admin.detail-section>

            <x-admin.detail-section title="Informations">
                <dl class="grid gap-4 sm:grid-cols-2">
                    <x-admin.detail-item label="Numéro"><span class="tabular-nums">{{ $ticket->code }}</span></x-admin.detail-item>
                    <x-admin.detail-item label="Client">{{ optional($ticket->client)->entreprise }}</x-admin.detail-item>
                    <x-admin.detail-item label="Technicien">{{ optional($ticket->technicien)->full_name ?: 'Non assigné' }}</x-admin.detail-item>
                    <x-admin.detail-item label="Entrée"><x-admin.date :value="$ticket->created_at" /></x-admin.detail-item>
                    @if ($ticket->started_at)
                        <x-admin.detail-item label="Début de réparation"><x-admin.date :value="$ticket->started_at" /></x-admin.detail-item>
                    @endif
                    @if ($ticket->finished_at)
                        <x-admin.detail-item label="Fin de réparation"><x-admin.date :value="$ticket->finished_at" /></x-admin.detail-item>
                    @endif
                    @if ($ticket->delivery_count)
                        <x-admin.detail-item label="Sortie"><x-admin.date :value="optional($ticket->delivery)->date_end" /></x-admin.detail-item>
                        <x-admin.detail-item label="Sortie par">
                            {{ optional($ticket->delivery->reception)->full_name }}
                            @if (optional($ticket->delivery)->notes)
                                <span class="block text-small text-fg-muted">{{ $ticket->delivery->notes }}</span>
                            @endif
                        </x-admin.detail-item>
                    @endif
                </dl>
            </x-admin.detail-section>

            @if ($isManager)
                <x-slot:aside>
                    <section aria-labelledby="ticket-documents-title" class="admin-card">
                        <div class="admin-card-header">
                            <h2 id="ticket-documents-title" class="admin-section-title">Documents</h2>
                        </div>
                        <ul class="admin-card-body space-y-2">
                            @if ($ticket->estimate_count > 0)
                                <li><a href="{{ route('public.show.estimate', [$ticket->estimate->uuid, 'has_header' => true]) }}" target="_blank" rel="noopener" class="text-small font-medium text-accent hover:underline">Devis {{ $ticket->estimate->full_number }}</a></li>
                            @endif
                            @if ($ticket->invoice_count > 0)
                                <li><a href="{{ route('public.show.invoice', [$ticket->invoice->uuid, 'has_header' => true]) }}" target="_blank" rel="noopener" class="text-small font-medium text-accent hover:underline">Facture {{ $ticket->invoice->full_number }}</a></li>
                            @endif
                            <li><a href="{{ route('admin:tickets.report.generate', $ticket->uuid) }}" target="_blank" rel="noopener" class="text-small font-medium text-accent hover:underline">Rapport complet</a></li>
                            <li><a href="{{ $ticket->historical_url }}" class="text-small font-medium text-accent hover:underline">Historique</a></li>
                        </ul>
                    </section>
                </x-slot:aside>
            @endif
        </x-admin.detail-page>
    </div>
@endsection
