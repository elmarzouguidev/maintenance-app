<div class="row">
    <div class="card mb-4">
        <div class="card-body">
            <p class="card-title-desc">Actions disponible :</p>
            <div class="col-lg-12">
                <div class="button-items">
                    <a target="_blank"
                       href="{{ route('public.show.estimate',$ticket->uuid)}}"
                       class="btn btn-primary waves-effect waves-light w-sm">
                        <i class="mdi mdi-file-pdf d-block font-size-16"></i> Télécharger le Rapport
                    </a>

                    <button type="button" class="btn btn-danger waves-effect waves-light w-sm" id="deleteTicket">
                        <i class="mdi mdi-trash-can d-block font-size-16"></i> Supprimer
                    </button>

                    <form id="delete-ticket-single-{{ $ticket->uuid }}" method="post"
                          action="{{ route('admin:tickets.delete') }}">
                        @csrf
                        @method('DELETE')
                        <input type="hidden" name="ticket" value="{{ $ticket->uuid }}">
                    </form>
                </div>
            </div>

        </div>

    </div>
</div>
<div class="row">
    <div class="card mb-4">
        <div class="card-body">
            <p class="card-title-desc">Historique :</p>
            <ul>
                @foreach ($ticket->statuses as $history)
                    <li>
                        {{ $history->user }} : {{ $history->detail }} :
                        {{ $history->created_at->format('d-m-Y H:i:s') }}
                    </li>
                @endforeach
            </ul>
        </div>
    </div>
</div>
