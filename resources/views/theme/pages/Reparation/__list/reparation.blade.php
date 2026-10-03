<div class="row g-4">
    @php
        $repairStages = [
            ['key' => 'a-preparer', 'title' => 'À réparer', 'description' => 'Appareils en attente de prise en charge', 'badge' => 'bg-primary-subtle text-primary', 'action' => 'Commencer la réparation'],
            ['key' => 'encours-de-reparation', 'title' => 'En cours', 'description' => 'Réparations actuellement suivies', 'badge' => 'bg-warning-subtle text-warning-emphasis', 'action' => 'Continuer la réparation'],
            ['key' => 'pret-a-livre', 'title' => 'Réparés', 'description' => 'Appareils prêts à être livrés', 'badge' => 'bg-success-subtle text-success', 'action' => 'Réparation terminée'],
        ];
    @endphp

    @foreach ($repairStages as $stage)
        <section class="col-12 col-xl-4" aria-labelledby="repair-stage-{{ $stage['key'] }}">
            <div class="card h-100">
                <div class="card-header bg-transparent border-bottom">
                    <div class="d-flex align-items-start justify-content-between gap-2">
                        <div>
                            <h2 class="h5 mb-1" id="repair-stage-{{ $stage['key'] }}">{{ $stage['title'] }}</h2>
                            <p class="text-muted small mb-0">{{ $stage['description'] }}</p>
                        </div>
                        <span class="badge {{ $stage['badge'] }}">
                            {{ isset($tickets[$stage['key']]) ? $tickets[$stage['key']]->count() : 0 }}
                        </span>
                    </div>
                </div>

                <div class="card-body">
                    @if (Arr::exists($tickets, $stage['key']))
                        <div class="d-flex flex-column gap-3">
                            @foreach ($tickets[$stage['key']] as $ticket)
                                <article class="border rounded p-3">
                                    <div class="d-flex align-items-start gap-3">
                                        <div class="flex-shrink-0">
                                            @if ($ticket->getFirstMediaUrl('tickets-images', 'thumb'))
                                                <img src="{{ $ticket->getFirstMediaUrl('tickets-images', 'thumb') }}"
                                                    alt="Photo de {{ $ticket->article }}" class="rounded object-fit-cover"
                                                    width="56" height="56">
                                            @else
                                                <div class="avatar-sm rounded bg-light d-flex align-items-center justify-content-center" aria-hidden="true">
                                                    <i class="bx bx-wrench text-muted font-size-20"></i>
                                                </div>
                                            @endif
                                        </div>

                                        <div class="flex-grow-1">
                                            <h3 class="h6 mb-1 text-break">
                                                <a href="{{ $ticket->repear_url }}" class="text-body">{{ $ticket->article }}</a>
                                            </h3>
                                            <p class="small text-muted mb-2">{{ $ticket->unique_code }}</p>
                                            <span class="badge {{ $stage['badge'] }}">{{ $ticket->status }}</span>
                                        </div>
                                    </div>

                                    <div class="d-flex flex-column flex-sm-row align-items-sm-center justify-content-between gap-2 border-top mt-3 pt-3">
                                        <span class="small text-muted">
                                            <i class="bx bx-calendar me-1" aria-hidden="true"></i>
                                            @if ($stage['key'] === 'a-preparer')
                                                {{ $ticket->updated_at }}
                                            @elseif ($stage['key'] === 'encours-de-reparation')
                                                {{ $ticket->envoyer_at }}
                                            @else
                                                {{ $ticket->created_at }}
                                            @endif
                                        </span>

                                        @if ($stage['key'] === 'pret-a-livre')
                                            <span class="btn btn-light btn-sm disabled" aria-disabled="true">{{ $stage['action'] }}</span>
                                        @else
                                            <a href="{{ $ticket->repear_url }}" class="btn btn-primary btn-sm">
                                                {{ $stage['action'] }}
                                                <i class="bx bx-right-arrow-alt ms-1" aria-hidden="true"></i>
                                            </a>
                                        @endif
                                    </div>
                                </article>
                            @endforeach
                        </div>
                    @else
                        <div class="text-center text-muted py-4">
                            <i class="bx bx-check-circle font-size-24 d-block mb-2" aria-hidden="true"></i>
                            <p class="mb-0">Aucun ticket dans cette étape.</p>
                        </div>
                    @endif
                </div>
            </div>
        </section>
    @endforeach
</div>
