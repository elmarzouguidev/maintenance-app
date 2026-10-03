@php($ticketImages = $ticket->getMedia('tickets-images'))

<div class="row g-3">
    <div class="col-12 col-xl-7">
        <section class="card h-100" aria-labelledby="ticket-media-title">
            <div class="card-body">
                <div class="mb-3">
                    <h2 id="ticket-media-title" class="h5 mb-1">Photos de l’appareil</h2>
                    <p class="text-muted mb-0">{{ $ticket->article }}</p>
                </div>

                @if ($ticketImages->isNotEmpty())
                    <div class="row g-3">
                        <div class="col-3 col-sm-2">
                            <div class="nav flex-column nav-pills gap-2" id="ticket-photo-tabs" role="tablist" aria-orientation="vertical">
                                @foreach ($ticketImages as $image)
                                    <button
                                        class="nav-link p-1 {{ $loop->first ? 'active' : '' }}"
                                        id="ticket-photo-tab-{{ $loop->index }}"
                                        data-bs-toggle="pill"
                                        data-bs-target="#ticket-photo-panel-{{ $loop->index }}"
                                        type="button"
                                        role="tab"
                                        aria-controls="ticket-photo-panel-{{ $loop->index }}"
                                        aria-selected="{{ $loop->first ? 'true' : 'false' }}">
                                        <img src="{{ $image->getFullUrl() }}" alt="Aperçu de la photo {{ $loop->iteration }} du ticket {{ $ticket->code }}" class="img-fluid rounded">
                                    </button>
                                @endforeach
                            </div>
                        </div>

                        <div class="col-9 col-sm-10">
                            <div class="tab-content" id="ticket-photo-panels">
                                @foreach ($ticketImages as $image)
                                    <div
                                        class="tab-pane fade {{ $loop->first ? 'show active' : '' }}"
                                        id="ticket-photo-panel-{{ $loop->index }}"
                                        role="tabpanel"
                                        aria-labelledby="ticket-photo-tab-{{ $loop->index }}"
                                        tabindex="0">
                                        <a class="image-popup-no-margins d-block" href="{{ $image->getFullUrl() }}" aria-label="Agrandir la photo {{ $loop->iteration }} du ticket {{ $ticket->code }}">
                                            <img src="{{ $image->getFullUrl() }}" alt="{{ $ticket->article }} — photo {{ $loop->iteration }}" class="img-fluid w-100 rounded">
                                        </a>
                                    </div>
                                @endforeach
                            </div>
                        </div>
                    </div>
                @else
                    <div class="rounded border bg-light-subtle d-flex flex-column align-items-center justify-content-center text-center px-3 py-5">
                        <i class="bx bx-image font-size-32 text-muted mb-2" aria-hidden="true"></i>
                        <p class="text-muted mb-0">Aucune photo n’est associée à ce ticket.</p>
                    </div>
                @endif
            </div>
        </section>
    </div>

    <div class="col-12 col-xl-5">
        <section class="card h-100" aria-labelledby="ticket-summary-title">
            <div class="card-body d-flex flex-column">
                <div class="d-flex flex-wrap align-items-start justify-content-between gap-3 mb-3">
                    <div>
                        <p class="text-muted text-uppercase small fw-semibold mb-1">Appareil</p>
                        <h2 id="ticket-summary-title" class="h4 mb-1">{{ $ticket->article }}</h2>
                        <p class="text-muted mb-0">{{ optional($ticket->client)->entreprise }}</p>
                    </div>
                    <div class="text-sm-end">
                        <span class="badge bg-light text-body border rounded-pill">{{ __('status.statuses.' . $ticket->status) }}</span>
                        @if ($ticket->etat != App\Constants\Etat::NON_DIAGNOSTIQUER)
                            <div class="mt-2">
                                <span class="badge bg-info-subtle text-info rounded-pill">{{ __('etat.etats.' . $ticket->etat) }}</span>
                            </div>
                        @endif
                    </div>
                </div>

                <div class="border-top pt-3">
                    <h3 class="h6 mb-2">Description du problème</h3>
                    <div class="text-muted ticket-description">{!! $ticket->description !!}</div>
                </div>

                <div class="mt-auto pt-4 d-flex flex-wrap gap-2">
                    @can('ticket.edit')
                        <a href="{{ $ticket->edit }}" class="btn btn-primary d-inline-flex align-items-center gap-2">
                            <i class="bx bx-edit" aria-hidden="true"></i>
                            <span>Modifier le ticket</span>
                        </a>
                        <a target="_blank" rel="noopener noreferrer" href="{{ route('admin:tickets.report.generate', $ticket->uuid) }}" class="btn btn-outline-primary d-inline-flex align-items-center gap-2">
                            <i class="bx bx-file" aria-hidden="true"></i>
                            <span>Rapport complet</span>
                        </a>
                    @endcan
                    @can('ticket.read')
                        <a href="{{ $ticket->media_url }}" class="btn btn-outline-secondary d-inline-flex align-items-center gap-2">
                            <i class="bx bx-image-add" aria-hidden="true"></i>
                            <span>Gérer les photos</span>
                        </a>
                        <a href="{{ route('admin:tickets.historical', $ticket->uuid) }}" class="btn btn-outline-secondary d-inline-flex align-items-center gap-2">
                            <i class="bx bx-history" aria-hidden="true"></i>
                            <span>Historique</span>
                        </a>
                    @endcan
                </div>
            </div>
        </section>
    </div>

    <div class="col-12 col-xl-6">
        @include('theme.pages.Ticket.__single_v2.section_ticket_info')
    </div>

    @can('ticket.read')
        <div class="col-12 col-xl-6">
            @include('theme.pages.Ticket.__single_v2.section_attached_files')
        </div>
    @endcan
</div>
