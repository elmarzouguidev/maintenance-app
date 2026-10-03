@props(['tickets', 'ticketKey', 'showActionButton' => true, 'actionUrl' => null, 'actionText' => 'Traiter le ticket', 'actionClass' => 'btn-primary'])

<table id="datatable-{{ $ticketKey }}" class="table table-hover align-middle dt-responsive nowrap w-100">
    <thead class="table-light">
        <tr>
            <th>Ticket N°</th>
            <th>Client</th>
            <th>Article</th>
            <th>Date</th>
            <th>Statut</th>
            <th>État</th>
            <th>Technicien</th>
            @if($showActionButton)
                <th class="align-middle">{{ $actionText }}</th>
            @endif
        </tr>
    </thead>

    <tbody>
        @if (Arr::exists($tickets, $ticketKey))
            @foreach ($tickets[$ticketKey] as $ticket)
                <tr>
                    <td>
                        <a href="{{ $ticket->url }}" class="text-body fw-bold">
                            {{ $ticket->code }}
                        </a>
                    </td>

                    <td class="text-wrap">
                        <i class="mdi mdi-domain me-1 text-muted" aria-hidden="true"></i>
                        {{ optional($ticket->client)->entreprise }}
                    </td>

                    <td class="text-wrap">
                        {{ $ticket->article }}
                    </td>

                    <td>
                        {{ $ticket->full_date }}
                    </td>

                    <td>
                        <i class="mdi mdi-circle text-info font-size-10 me-1" aria-hidden="true"></i>
                        {{ __('status.statuses.' . $ticket->status) }}
                    </td>

                    <td>
                        <i class="mdi mdi-circle text-info font-size-10 me-1" aria-hidden="true"></i>
                        {{ __('etat.etats.' . $ticket->etat) }}
                    </td>

                    <td>
                        {{ optional($ticket->technicien)->full_name }}
                    </td>

                    @if($showActionButton)
                        <td>
                            <a href="{{ $actionUrl ?? $ticket->ticket_url }}"
                               class="btn {{ $actionClass }} btn-sm btn-rounded">
                                {{ $actionText }}
                            </a>
                        </td>
                    @endif
                </tr>
            @endforeach
        @endif
    </tbody>
</table>
