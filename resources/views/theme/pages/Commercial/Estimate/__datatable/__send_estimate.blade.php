@if (!$estimate->is_send)

    <div class="modal fade sendEstimate-{{ $estimate->uuid }}" tabindex="-1" role="dialog"
         aria-labelledby=orderdetailsModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id=orderdetailsModalLabel">Confirmer L'envoi du devis</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <p class="mb-2">Devis N° : <span
                            class="text-primary">{{ $estimate->full_number }}</span></p>
                    <form class="sendEstimate" id="sendEstimate"
                          action="{{ route('commercial:estimates.send') }}" method="post">
                        @csrf
                        <input type="hidden" name="estimater" value="{{$estimate->uuid}}">
                    </form>

                </div>

                <div class="modal-footer">
                    <button type="button" class="btn btn-primary"
                            onclick="document.getElementById('sendEstimate').submit();"
                    >
                        Envoyer
                    </button>
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                </div>
            </div>
        </div>
    </div>
@endif
