<div class="col-lg-6">
    <div class="table-responsive">
        <table class="table mb-0 table-bordered">
            <tbody>
            <tr>
                <th scope="row">Code</th>
                <td>{{ $ticket->code }}</td>
            </tr>
            <tr>
                <th scope="row" style="width: 400px;">Technicien</th>
                <td>{{ optional($ticket->technicien)->full_name }}</td>
            </tr>
            <tr>
                <th scope="row">Client</th>
                <td>{{ optional($ticket->client)->entreprise }}</td>
            </tr>
            <tr>
                <th scope="row">Etat</th>
                <td>{{ $ticket->etat }}</td>
            </tr>
            <tr>
                <th scope="row">Status</th>
                <td>

                    {{__('status.statuses.'.$ticket->status)}}
                    {{--$ticket->newStatus()->first()->name--}}
                </td>
            </tr>

            <tr>
                <th scope="row">Date de création</th>
                <td>{{ $ticket->full_date }}</td>
            </tr>

            @if($ticket->delivery_count)
                <tr>
                    <th scope="row">Date de sortie</th>
                    <td>{{ optional($ticket->delivery)->date_end->format('d-m-Y') }}</td>
                </tr>
                <tr>
                    <th scope="row">sortie par :</th>
                    <td>{{ optional($ticket->delivery->reception)->full_name }}</td>
                    <td>{{ optional($ticket->delivery)->notes }}</td>
                </tr>
            @endif
            </tbody>
        </table>
    </div>
</div>
