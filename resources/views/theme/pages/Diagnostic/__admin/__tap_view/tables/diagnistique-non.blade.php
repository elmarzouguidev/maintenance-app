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
    </tr>
    </thead>

    <tbody>
    @if (Arr::exists($tickets, 'retour-non-reparable'))
        @foreach ($tickets['retour-non-reparable'] as $ticket)

            <tr>
                {{-- <td>
                <div class="form-check font-size-16">
                    <input class="form-check-input" type="checkbox" id="orderidcheck01">
                    <label class="form-check-label" for="orderidcheck01"></label>
                </div>
            </td> --}}
                <td><a href="{{$ticket->url}}"
                       class="text-body fw-bold">{{ $ticket->code }}</a></td>
                <td> {{ $ticket->article }}</td>
                <td>
                    {{ $ticket->full_date }}
                </td>
                <td>
                    @php
                        $status = $ticket->status;
                        $textt = '';
                        $color = '';
                        if ($status === \App\Constants\Status::RETOUR_NON_REPARABLE) {
                            $textt = __('status.statuses.'.\App\Constants\Status::RETOUR_NON_REPARABLE);
                            $color = 'danger';
                        } else {
                            $textt = 'Inconnu';
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
                    <i class="fas fas fa-building me-1"></i> {{ optional($ticket->client)->entreprise }}
                </td>
                <td>
                    <i class="fas fas fa-building me-1"></i> {{ optional($ticket->technicien)->full_name }}
                </td>

            </tr>
        @endforeach
    @endif
    </tbody>
</table>
