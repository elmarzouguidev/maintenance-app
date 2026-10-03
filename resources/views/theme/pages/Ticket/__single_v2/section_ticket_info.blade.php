<section class="card h-100" aria-labelledby="ticket-information-title">
    <div class="card-body">
        <h2 id="ticket-information-title" class="h5 mb-3">Informations du ticket</h2>

        <div class="table-responsive">
            <table class="table table-sm align-middle mb-0">
                <tbody>
                    <tr>
                        <th scope="row" class="text-muted fw-normal">Code</th>
                        <td class="text-end fw-medium">{{ $ticket->code }}</td>
                    </tr>
                    <tr>
                        <th scope="row" class="text-muted fw-normal">Technicien</th>
                        <td class="text-end">{{ optional($ticket->technicien)->full_name ?: 'Non attribué' }}</td>
                    </tr>
                    <tr>
                        <th scope="row" class="text-muted fw-normal">Client</th>
                        <td class="text-end">{{ optional($ticket->client)->entreprise }}</td>
                    </tr>
                    <tr>
                        <th scope="row" class="text-muted fw-normal">État</th>
                        <td class="text-end">{{ __('etat.etats.' . $ticket->etat) }}</td>
                    </tr>
                    <tr>
                        <th scope="row" class="text-muted fw-normal">Statut</th>
                        <td class="text-end">{{ __('status.statuses.' . $ticket->status) }}</td>
                    </tr>
                    @if (! is_null($ticket->started_at))
                        <tr>
                            <th scope="row" class="text-muted fw-normal">Date de départ</th>
                            <td class="text-end">{{ $ticket->started_at->format('d-m-Y') }}</td>
                        </tr>
                    @endif
                    @if (! is_null($ticket->finished_at))
                        <tr>
                            <th scope="row" class="text-muted fw-normal">Date de finalisation</th>
                            <td class="text-end">{{ $ticket->finished_at->format('d-m-Y') }}</td>
                        </tr>
                    @endif
                    @if ($ticket->delivery_count)
                        <tr>
                            <th scope="row" class="text-danger fw-normal">Date de sortie</th>
                            <td class="text-end text-danger">{{ optional($ticket->delivery)->date_end->format('d-m-Y') }}</td>
                        </tr>
                        <tr>
                            <th scope="row" class="text-danger fw-normal">Sortie par / note</th>
                            <td class="text-end text-danger">
                                {{ optional($ticket->delivery->reception)->full_name }}
                                @if (optional($ticket->delivery)->notes)
                                    <div class="small">{{ $ticket->delivery->notes }}</div>
                                @endif
                            </td>
                        </tr>
                    @endif
                </tbody>
            </table>
        </div>
    </div>
</section>
