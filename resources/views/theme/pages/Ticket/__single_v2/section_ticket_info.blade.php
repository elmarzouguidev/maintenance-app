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
                    {{ $ticket->status }}
                    {{$ticket->statfuses()->first()->name}}
                </td>
            </tr>

            <tr>
                <th scope="row">Date de création</th>
                <td>{{ $ticket->full_date }}</td>
            </tr>
            </tbody>
        </table>
    </div>
</div>
