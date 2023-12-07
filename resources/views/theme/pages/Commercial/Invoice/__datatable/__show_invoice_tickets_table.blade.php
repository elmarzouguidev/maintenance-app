<div class="table-responsive">
    <table class="table align-middle table-nowrap table-hover">
        <thead class="table-light">
            <tr>
                <th scope="col">Ticket N°</th>
                <th scope="col">Date d'ajout</th>

            </tr>
        </thead>
        <tbody>
            @if($invoice->ticket_count)
            <tr>

                <td>
                    <a href="{{ $invoice->ticket->url }}" class="text-body fw-bold"
                        style="color:#556ee6 !important">

                        {{ $invoice->ticket->code }}

                    </a>
                </td>
                
                <td>
                    {{ $invoice->ticket->created_at?->format('d-m-Y') }}
                </td>

            </tr>
            @else
            @foreach ($invoice->tickets as $tickett)
                <tr>

                    <td>
                    
                        <a href="{{ $tickett->url }}" class="text-body fw-bold"
                            style="color:#556ee6 !important">
    
                            {{ $tickett->code }}
    
                        </a>
                    </td>
                    
                    <td>
                        {{ $tickett->created_at?->format('d-m-Y') }}
                    </td>

                </tr>

            @endforeach
            @endif
        </tbody>
    </table>
</div>