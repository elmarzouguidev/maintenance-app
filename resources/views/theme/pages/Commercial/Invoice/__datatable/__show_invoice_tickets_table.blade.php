<div class="table-responsive">
    <table class="table align-middle table-nowrap table-hover">
        <thead class="table-light">
            <tr>
                <th scope="col">Code</th>
                <th scope="col">Date d'ajout</th>

            </tr>
        </thead>
        <tbody>
            @if($invoice->ticket)
            <tr>

                <td>{{ $ticket->code }}</td>
                
                <td>
                    {{ $ticket->created_at?->format('d-m-Y') }}
                </td>

            </tr>
            @else
            @foreach ($invoice->tickets as $tickett)
                <tr>

                    <td>{{ $tickett->code }}</td>
                    
                    <td>
                        {{ $tickett->created_at?->format('d-m-Y') }}
                    </td>

                </tr>

            @endforeach
            @endif
        </tbody>
    </table>
</div>