<table id="datatable-buttons" class="table table-bordered dt-responsive nowrap w-100">
    <thead>
        <tr>
            {{-- <th style="width: 20px;" class="align-middle">
                <div class="form-check font-size-16">
                    <input class="form-check-input" type="checkbox" id="checkAll">
                    <label class="form-check-label" for="checkAll"></label>
                </div>
            </th> --}}
            <th>Ticket N°</th>
            <th>Article</th>
            <th>Date</th>
            <th>Status</th>
            <th>Etat</th>
            <th>Client</th>
            {{-- <th>Détails</th> --}}
            @auth('technicien')
                <th class="align-middle">Diagnostiquer</th>
            @endauth
        </tr>
    </thead>

    <tbody>
        @if (Arr::exists($tickets, 'annuler'))
            @foreach ($tickets['annuler'] as $ticket)

                <tr>
                    {{-- <td>
                    <div class="form-check font-size-16">
                        <input class="form-check-input" type="checkbox" id="orderidcheck01">
                        <label class="form-check-label" for="orderidcheck01"></label>
                    </div>
                </td> --}}
                    <td><a href="{{--$ticket->diagnose_url--}}"
                            class="text-body fw-bold">{{ $ticket->unique_code }}</a> </td>
                    <td> {{ $ticket->article }}</td>
                    <td>
                        {{ $ticket->full_date }}
                    </td>
                    <td>
                        @php
                            $status = $ticket->stat;
                            $textt = '';
                            $color = '';
                            if ($status === 'retour-devis-non-confirme') {
                                $textt = 'Retour devis non confirmé';
                                $color = 'danger';
                            } else {
                                $textt = 'IMPAYÉE';
                                $color = 'warning';
                            }
                        @endphp

                        <i class="mdi mdi-circle text-{{ $color }} font-size-10"></i>
                        {{ $textt }}

                    </td>
                    <td>
                        <span class="badge badge-pill badge-soft-success font-size-12">

                            {{ $ticket->etat }}
                        </span>
                    </td>
                    <td>
                        <i class="fas fas fa-building me-1"></i> {{ $ticket->client->entreprise ?? '' }}
                    </td>


                    @auth('technicien')

                        <td>
                            <a href="{{-- $ticket->diagnose_url --}}" type="button" class="btn btn-warning btn-sm btn-rounded">
                                Annuler
                            </a>
                        </td>
                    @endauth

                </tr>
            @endforeach
        @endif
    </tbody>
</table>
