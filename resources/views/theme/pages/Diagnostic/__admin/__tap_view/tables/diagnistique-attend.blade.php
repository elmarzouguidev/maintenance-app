<table id="datatable" class="table table-bordered dt-responsive nowrap w-100">
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
        <th>Technicien</th>
        <th class="align-middle">Traiter le ticket</th>
    </tr>
    </thead>

    <tbody>
    @if (Arr::exists($tickets, 'en-attent-de-devis'))
        @foreach ($tickets['en-attent-de-devis'] as $ticket)

            <tr>
                {{-- <td>
                <div class="form-check font-size-16">
                    <input class="form-check-input" type="checkbox" id="orderidcheck01">
                    <label class="form-check-label" for="orderidcheck01"></label>
                </div>
            </td> --}}
                <td><a href="{{ $ticket->url }}" class="text-body fw-bold">{{ $ticket->code }}</a></td>
                <td> {{ $ticket->article }}</td>
                <td>
                    {{ $ticket->full_date }}
                </td>
                <td>
                    @php
                        $status = $ticket->status;
                        $textt = '';
                        $color = '';
                        if ($status === \App\Constants\Status::EN_ATTENTE_DE_DEVIS) {
                            $textt = 'En attent de devis';
                            $color = 'danger';
                        } else {
                            $textt = 'Inconnu';
                            $color = 'warning';
                        }
                    @endphp
                    {{-- <span class="badge badge-pill badge-soft-success font-size-12">
                {{ $ticket->etat }}
            </span> --}}
                    <i class="mdi mdi-circle text-{{ $color }} font-size-10"></i>
                    {{ $textt }}
                    {{-- <div class="spinner-grow text-{{ $color }} m-1" role="status">
                    <span class="sr-only"> {{ $textt }}</span>
                </div> --}}

                </td>
                <td>
                        <span class="badge badge-pill badge-soft-success font-size-12">
                            {{ $ticket->etat }}
                        </span>
                </td>
                <td>
                    <i class="fas fas fa-building me-1"></i> {{ optional($ticket->client)->entreprise }}
                </td>
                <td>
                    <i class="fas fas fa-building me-1"></i> {{ optional($ticket->technicien)->full_name }}
                </td>
                <td>
                    <!-- Button trigger modal -->
                    <a href="{{$ticket->ticket_url}}" type="button" class="btn btn-primary btn-sm btn-rounded">
                        Traiter le ticket
                    </a>
                </td>
            </tr>

        @endforeach
    @endif
    </tbody>
</table>
