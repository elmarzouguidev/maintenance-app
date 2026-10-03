@php
    $ticketImages = $ticket->getMedia('tickets-images');
    $diagnosticReport = $ticket->diagnoseReports;
@endphp

<div class="row g-4">
    <div class="col-12 col-xl-4">
        <div class="card h-100">
            <div class="card-body">
                <div class="d-flex align-items-start justify-content-between gap-3 mb-4">
                    <div>
                        <h2 class="h5 card-title mb-1">Résumé du ticket</h2>
                        <p class="text-muted mb-0">Informations de prise en charge</p>
                    </div>
                    <span class="badge bg-primary-subtle text-primary">{{ $ticket->code }}</span>
                </div>

                <dl class="row mb-0">
                    <dt class="col-sm-5 text-muted fw-medium">Appareil</dt>
                    <dd class="col-sm-7">{{ $ticket->article }}</dd>

                    <dt class="col-sm-5 text-muted fw-medium">Technicien</dt>
                    <dd class="col-sm-7">{{ optional($ticket->technicien)->full_name ?? 'Non assigné' }}</dd>

                    <dt class="col-sm-5 text-muted fw-medium">Client</dt>
                    <dd class="col-sm-7">{{ optional($ticket->client)->entreprise }}</dd>

                    <dt class="col-sm-5 text-muted fw-medium">État</dt>
                    <dd class="col-sm-7">{{ __('etat.etats.' . $ticket->etat) }}</dd>

                    <dt class="col-sm-5 text-muted fw-medium">Statut</dt>
                    <dd class="col-sm-7">{{ __('status.statuses.' . $ticket->status) }}</dd>

                    <dt class="col-sm-5 text-muted fw-medium">Créé le</dt>
                    <dd class="col-sm-7 mb-0">{{ $ticket->full_date }}</dd>
                </dl>
            </div>
        </div>
    </div>

    <div class="col-12 col-xl-8">
        <div class="card mb-4">
            <div class="card-body">
                <div class="d-flex align-items-center justify-content-between flex-wrap gap-2 mb-3">
                    <div>
                        <h2 class="h5 card-title mb-1">{{ $ticket->article }}</h2>
                        <p class="text-muted mb-0">Description du problème signalé</p>
                    </div>
                </div>

                <div class="row g-4 align-items-start">
                    <div class="col-12 {{ $ticketImages->isNotEmpty() ? 'col-lg-6' : '' }}">
                        <div class="text-body-secondary">{!! $ticket->description !!}</div>
                    </div>

                    @if ($ticketImages->isNotEmpty())
                        <div class="col-12 col-lg-6">
                            <div id="ticket-diagnostic-images" class="carousel slide" data-bs-ride="false" aria-label="Photos du ticket">
                                <div class="carousel-inner rounded bg-light">
                                    @foreach ($ticketImages as $image)
                                        <div class="carousel-item {{ $loop->first ? 'active' : '' }}">
                                            <a class="image-popup-no-margins d-block text-center" href="{{ $image->getFullUrl() }}">
                                                <img class="img-fluid mx-auto d-block" alt="Photo {{ $loop->iteration }} du ticket {{ $ticket->code }}"
                                                    src="{{ $image->getFullUrl('normal') }}" style="max-height: 20rem; object-fit: contain;">
                                            </a>
                                        </div>
                                    @endforeach
                                </div>
                                @if ($ticketImages->count() > 1)
                                    <button class="carousel-control-prev" type="button" data-bs-target="#ticket-diagnostic-images" data-bs-slide="prev" aria-label="Photo précédente">
                                        <span class="carousel-control-prev-icon" aria-hidden="true"></span>
                                    </button>
                                    <button class="carousel-control-next" type="button" data-bs-target="#ticket-diagnostic-images" data-bs-slide="next" aria-label="Photo suivante">
                                        <span class="carousel-control-next-icon" aria-hidden="true"></span>
                                    </button>
                                @endif
                            </div>
                        </div>
                    @else
                        <div class="col-12">
                            <div class="alert alert-light border mb-0">
                                <i class="bx bx-image-alt me-1" aria-hidden="true"></i>
                                Aucune photo n’est jointe à ce ticket.
                            </div>
                        </div>
                    @endif
                </div>
            </div>
        </div>

        @include('theme.layouts._parts.__messages')

        @canany(['diagnostic.browse', 'diagnostic.confirm', 'estimates.browse', 'estimates.create'])
            <div class="card mb-4">
                <div class="card-body">
                    <h2 class="h5 card-title mb-3">Suivi du devis</h2>

                    @canany(['estimates.browse', 'estimates.create'])
                        @if ($ticket->estimate_count == 1)
                            <a target="_blank" rel="noopener noreferrer"
                                href="{{ route('public.show.estimate', [$ticket->estimate->uuid, 'has_header' => true]) }}"
                                class="btn btn-outline-warning mb-3">
                                <i class="bx bx-file me-1" aria-hidden="true"></i> Devis déjà créé
                            </a>
                        @elseif (auth()->user()->can('estimates.create'))
                            <a href="{{ route('commercial:estimates.create.ticket', $ticket->uuid) }}" class="btn btn-primary mb-3">
                                <i class="bx bx-plus me-1" aria-hidden="true"></i> Créer un devis
                            </a>
                        @endif
                    @endcanany

                    @can('diagnostic.confirm')
                        <form method="post" action="{{ route('admin:tickets.diagnose.send-confirm', $ticket->uuid) }}">
                            @csrf
                            <fieldset>
                                <legend class="h6 mb-3">Réponse au devis</legend>
                                <div class="form-check mb-2">
                                    <input class="form-check-input" type="radio" name="response" id="response1"
                                        value="{{ \App\Constants\Response::DEVIS_ACCEPTE }}"
                                        {{ $ticket->status == \App\Constants\Status::A_REPARER ? 'checked' : '' }}>
                                    <label class="form-check-label" for="response1">Devis accepté, commencer la réparation</label>
                                </div>
                                <div class="form-check mb-3">
                                    <input class="form-check-input" type="radio" name="response" id="response2"
                                        value="{{ \App\Constants\Response::DEVIS_NON_ACCEPTE }}"
                                        {{ $ticket->status == \App\Constants\Status::RETOUR_DEVIS_NON_CONFIRME ? 'checked' : '' }}>
                                    <label class="form-check-label" for="response2">Devis refusé, décliner la réparation</label>
                                </div>
                            </fieldset>
                            <button class="btn btn-primary" type="submit">
                                <i class="bx bx-save me-1" aria-hidden="true"></i> Enregistrer la réponse
                            </button>
                        </form>
                    @endcan

                    @if ($diagnosticReport)
                        <hr class="my-4">
                        <h3 class="h6">Rapport de diagnostic</h3>
                        <div class="text-body-secondary">{!! $diagnosticReport->content !!}</div>
                    @endif
                </div>
            </div>
        @endcanany

        @canany(['diagnostic.edit', 'diagnostic.manage_assigned'])
            @php
                $reportIsClosed = isset($diagnosticReport) && $diagnosticReport->close_report;
                $disabled = $reportIsClosed ? 'disabled' : '';
                $readOnly = $reportIsClosed ? 'readonly' : '';
            @endphp

            <div class="card">
                <div class="card-body">
                    <div class="mb-4">
                        <h2 class="h5 card-title mb-1">Rapport de diagnostic</h2>
                        <p class="text-muted mb-0">Évaluez la réparabilité et consignez vos observations.</p>
                    </div>

                    <form action="{{ $ticket->diagnose_url }}" method="post" id="TicketReportForm">
                        @csrf
                        <fieldset class="mb-4">
                            <legend class="h6 mb-3">État de l’appareil</legend>
                            <div class="form-check form-check-inline">
                                <input class="form-check-input" type="radio" name="etat" id="etat1"
                                    value="{{ \App\Constants\Etat::REPARABLE }}" {{ $disabled }}
                                    {{ $ticket->etat == \App\Constants\Etat::REPARABLE ? 'checked' : '' }}>
                                <label class="form-check-label" for="etat1">Réparable</label>
                            </div>
                            <div class="form-check form-check-inline">
                                <input class="form-check-input" type="radio" name="etat" id="etat2"
                                    value="{{ \App\Constants\Etat::NON_REPARABLE }}" {{ $disabled }}
                                    {{ $ticket->etat == \App\Constants\Etat::NON_REPARABLE ? 'checked' : '' }}>
                                <label class="form-check-label" for="etat2">Non réparable</label>
                            </div>
                        </fieldset>

                        <input type="hidden" name="ticket" value="{{ $ticket->uuid }}" {{ $readOnly }}>
                        <input type="hidden" name="type" value="diagnostique" {{ $readOnly }}>
                        <input id="send-report" type="hidden" name="sendreport" value="no" {{ $readOnly }}>

                        <div class="mb-4">
                            <label class="form-label" for="ticketdesc-editor">Observations du diagnostic</label>
                            <textarea class="form-control @error('content') is-invalid @enderror" name="content" id="ticketdesc-editor"
                                rows="6" {{ $readOnly }}>{{ $diagnosticReport->content ?? old('content') }}</textarea>
                            @error('content')
                                <div class="invalid-feedback" role="alert">{{ $message }}</div>
                            @enderror
                        </div>
                    </form>

                    <div class="d-flex flex-wrap gap-2">
                        <button class="btn btn-primary" type="submit" {{ $disabled }}
                            onclick="document.getElementById('TicketReportForm').submit();">
                            <i class="bx bx-save me-1" aria-hidden="true"></i> Enregistrer le rapport
                        </button>
                        @can('diagnostic.send_report')
                            <button class="btn btn-outline-danger" id="sendTicketReport" type="button" {{ $disabled }}>
                                <i class="bx bx-send me-1" aria-hidden="true"></i> Enregistrer et envoyer
                            </button>
                        @endcan
                    </div>
                </div>
            </div>
        @endcanany
    </div>
</div>
