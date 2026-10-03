<div class="modal fade confirmLivrableAdmin-{{ $ticket->uuid }}" tabindex="-1" aria-labelledby="delivery-admin-confirm-title-{{ $ticket->uuid }}" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <div>
                    <h2 class="modal-title h5" id="delivery-admin-confirm-title-{{ $ticket->uuid }}">Autoriser la livraison</h2>
                    <p class="text-muted small mb-0">Ticket {{ $ticket->code }}</p>
                </div>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Fermer"></button>
            </div>
            <div class="modal-body">
                <form action="{{route('admin:tickets.livrablePostAdmin')}}" method="post">
                    @csrf
                    <input type="hidden" name="ticket" value="{{$ticket->uuid}}">
                    <div class="d-flex flex-wrap gap-2 justify-content-end">
                        <button type="button" class="btn btn-light" data-bs-dismiss="modal">Annuler</button>
                        <button type="submit" class="btn btn-primary">
                            Confirmer
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

