@props(['tickets', 'ticketKey', 'showActionButton' => true, 'actionUrl' => null, 'actionText' => 'Traiter le ticket', 'actionClass' => 'btn-primary'])

<table id="datatable-{{ $ticketKey }}" class="table table-bordered dt-responsive nowrap w-100">
    <thead>
        <tr>
            <th>Ticket N°</th>
            <th>Client</th>
            <th>Article</th>
            <th>Date</th>
            <th>Status</th>
            <th>Etat</th>
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

                    <td style="white-space:normal;">
                        <i class="fas fa-building me-1"></i> 
                        {{ optional($ticket->client)->entreprise }}
                    </td>

                    <td style="white-space:normal;"> 
                        {{ $ticket->article }}
                    </td>

                    <td>
                        {{ $ticket->full_date }}
                    </td>

                    <td>
                        <i class="mdi mdi-circle text-info font-size-10"></i>
                        {{ __('status.statuses.' . $ticket->status) }}
                    </td>

                    <td>
                        <i class="mdi mdi-circle text-info font-size-10"></i>
                        {{ __('etat.etats.' . $ticket->etat) }}
                    </td>

                    <td>
                        {{ optional($ticket->technicien)->full_name }}
                    </td>

                    @if($showActionButton)
                        <td>
                            <a href="{{ $actionUrl ?? $ticket->ticket_url }}" 
                               type="button" 
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
