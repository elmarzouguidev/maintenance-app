@props(['tickets', 'ticketKey', 'showDiagnoseButton' => true, 'diagnoseUrl' => null])

<table id="datatable-{{ $ticketKey }}" class="table table-bordered dt-responsive nowrap w-100">
    <thead>
        <tr>
            <th>Ticket N°</th>
            @if (auth()->user()->hasRole('SuperTechnicien'))
                <th>Technicien</th>
            @endif
            <th>Client</th>
            <th>Article</th>
            <th>Date</th>
            <th>Status</th>
            <th>Etat</th>
            @if($showDiagnoseButton)
                <th class="align-middle">Diagnostiquer</th>
            @endif
        </tr>
    </thead>

    <tbody>
        @if (Arr::exists($tickets, $ticketKey))
            @foreach ($tickets[$ticketKey] as $ticket)
                <tr>
                    <td>
                        <a href="{{ $diagnoseUrl ?? $ticket->diagnose_url }}" class="text-body fw-bold">
                            {{ $ticket->code }}
                        </a>
                    </td>

                    @if (auth()->user()->hasRole('SuperTechnicien'))
                        <td>
                            @if ($ticket->technicien()->is(auth()->user()))
                                <span class="badge bg-primary">Moi</span>
                            @else
                                {{ optional($ticket->technicien)->full_name }}
                            @endif
                        </td>
                    @endif

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

                    @if($showDiagnoseButton)
                        <td>
                            <a href="{{ $diagnoseUrl ?? $ticket->diagnose_url }}" 
                               type="button" 
                               class="btn btn-warning btn-sm btn-rounded">
                                Diagnostiquer
                            </a>
                        </td>
                    @endif
                </tr>
            @endforeach
        @endif
    </tbody>
</table>
