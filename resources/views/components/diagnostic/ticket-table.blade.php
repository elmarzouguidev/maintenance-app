@props(['tickets', 'ticketKey', 'showDiagnoseButton' => true, 'diagnoseUrl' => null, 'buttonText' => 'Diagnostiquer', 'buttonClass' => 'btn-warning'])

<table id="datatable-{{ $ticketKey }}" class="table table-hover align-middle dt-responsive nowrap w-100">
    <thead class="table-light">
        <tr>
            <th>Ticket N°</th>
            @if (auth()->user()->can('diagnostic.manage_assigned'))
                <th>Technicien</th>
            @endif
            <th>Client</th>
            <th>Article</th>
            <th>Date</th>
            <th>Statut</th>
            <th>État</th>
            @if($showDiagnoseButton)
                <th class="align-middle">{{ $buttonText }}</th>
            @endif
        </tr>
    </thead>

    <tbody>
        @if (Arr::exists($tickets, $ticketKey))
            @foreach ($tickets[$ticketKey] as $ticket)
                <tr>
                    <td>
                        <a href="{{ $diagnoseUrl ?? $ticket->diagnose_url ?? $ticket->repear_url }}" class="text-body fw-bold">
                            {{ $ticket->code }}
                        </a>
                    </td>

                    @if (auth()->user()->can('diagnostic.manage_assigned'))
                        <td>
                            @if ($ticket->technicien()->is(auth()->user()))
                                <span class="badge bg-primary">Moi</span>
                            @else
                                {{ optional($ticket->technicien)->full_name }}
                            @endif
                        </td>
                    @endif

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

                    @if($showDiagnoseButton)
                        <td>
                            <a href="{{ $diagnoseUrl ?? $ticket->diagnose_url ?? $ticket->repear_url }}"
                               class="btn {{ $buttonClass }} btn-sm btn-rounded">
                                {{ $buttonText }}
                            </a>
                        </td>
                    @endif
                </tr>
            @endforeach
        @endif
    </tbody>
</table>
