<section class="card" aria-labelledby="ticket-history-title">
    <div class="card-body">
        <div class="mb-4">
            <h2 id="ticket-history-title" class="h5 mb-1">Historique du ticket {{ $ticket->code }}</h2>
            <p class="text-muted mb-0">Suivi des changements enregistrés pour {{ $ticket->article }}.</p>
        </div>

        @forelse ($ticket->statuses as $status)
            <div class="d-flex gap-3 {{ $loop->last ? '' : 'pb-4 mb-4 border-bottom' }}">
                <div class="flex-shrink-0">
                    <span class="avatar-sm">
                        <span class="avatar-title rounded-circle bg-primary-subtle text-primary">
                            <i class="bx bx-history" aria-hidden="true"></i>
                        </span>
                    </span>
                </div>
                <div class="flex-grow-1 text-break">
                    <div class="d-flex flex-wrap align-items-center justify-content-between gap-2 mb-1">
                        <h3 class="h6 mb-0">{{ $status->name }}</h3>
                        <time class="small text-muted" datetime="{{ optional($status->pivot?->start_at)->toIso8601String() }}">
                            {{ $status->pivot?->start_at?->format('d-m-Y') ?? $status->created_at?->format('d-m-Y') }}
                        </time>
                    </div>
                    @if (filled($status->pivot?->description))
                        <p class="text-muted mb-0">{!! nl2br(e($status->pivot->description)) !!}</p>
                    @else
                        <p class="text-muted mb-0">Aucun commentaire enregistré.</p>
                    @endif
                </div>
            </div>
        @empty
            <div class="rounded border bg-light-subtle py-5 px-3 text-center">
                <i class="bx bx-time-five d-block fs-2 text-muted mb-2" aria-hidden="true"></i>
                <p class="text-muted mb-0">Aucun changement n’est encore enregistré pour ce ticket.</p>
            </div>
        @endforelse
    </div>
</section>
