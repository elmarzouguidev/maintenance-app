<div class="row g-4 mb-4">
    <div class="col-12 col-xl-8">
        <div class="card h-100">
            <div class="card-body">
                <div class="d-flex flex-column flex-sm-row align-items-sm-center gap-3 mb-4">
                    @if ($ticket->getFirstMediaUrl('tickets-images', 'thumb'))
                        <img src="{{ $ticket->getFirstMediaUrl('tickets-images', 'thumb') }}" alt="Photo de {{ $ticket->article }}"
                            class="rounded object-fit-cover" width="64" height="64">
                    @else
                        <div class="avatar-md rounded bg-light d-flex align-items-center justify-content-center" aria-hidden="true">
                            <i class="bx bx-wrench text-muted font-size-24"></i>
                        </div>
                    @endif

                    <div class="flex-grow-1">
                        <h2 class="h5 mb-1 text-break">{{ $ticket->article }}</h2>
                        <span class="text-muted">{{ $ticket->code }}</span>
                    </div>
                </div>

                <div class="d-flex align-items-center justify-content-between flex-wrap gap-2 mb-3">
                    <h3 class="h6 mb-0">Diagnostic transmis</h3>
                    <span class="badge bg-info-subtle text-info-emphasis">Rapport de diagnostic</span>
                </div>

                <div class="text-body-secondary">
                    {!! optional($ticket->diagnoseReports)->content !!}
                </div>
            </div>
        </div>
    </div>

    <div class="col-12 col-xl-4">
        <div class="card h-100">
            <div class="card-body">
                <h2 class="h5 card-title mb-4">Technicien responsable</h2>
                <div class="d-flex align-items-center gap-3">
                    <div class="avatar-md rounded-circle bg-primary-subtle text-primary d-flex align-items-center justify-content-center" aria-hidden="true">
                        <i class="bx bx-user font-size-24"></i>
                    </div>
                    <div class="flex-grow-1">
                        <p class="text-muted small mb-1">Assigné au ticket</p>
                        <p class="fw-medium text-body mb-0 text-break">{{ optional($ticket->technicien)->full_name ?? 'Non assigné' }}</p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
