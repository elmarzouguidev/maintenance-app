<div class="modal fade confirmLivrable-{{ $ticket->uuid }}" tabindex="-1" aria-labelledby="delivery-confirm-title-{{ $ticket->uuid }}" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <div>
                    <h2 class="modal-title h5" id="delivery-confirm-title-{{ $ticket->uuid }}">Confirmer la livraison</h2>
                    <p class="text-muted small mb-0">Ticket {{ $ticket->code }}</p>
                </div>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Fermer"></button>
            </div>
            <div class="modal-body">
                @include('theme.pages.Ticket.__pret_livre.__datatable.confirm_form')
            </div>
        </div>
    </div>
</div>

