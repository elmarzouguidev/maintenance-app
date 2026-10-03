<form action="{{ route('admin:tickets.livrablePost') }}" method="post">
    @csrf
    <div class="card">
        <div class="card-body">
            <div class="row">
                <div class="col-lg-12">
                    @include('theme.pages.Ticket.__pret_livre.__datatable.confirm_form_info')
                    <input type="hidden" name="ticket" value="{{ $ticket->uuid }}">
                    <div class="mb-4">
                        <label for="delivery-notes-{{ $ticket->uuid }}" class="form-label">Note</label>
                        <textarea name="notes" id="delivery-notes-{{ $ticket->uuid }}"
                            class="form-control @error('notes') is-invalid @enderror" maxlength="225" rows="3"></textarea>
                        @error('notes')
                            <div class="invalid-feedback" role="alert">{{ $message }}</div>
                        @enderror
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="d-flex flex-wrap gap-2 justify-content-end mb-4">
        <button type="button" class="btn btn-light" data-bs-dismiss="modal">Annuler</button>
        <button type="submit" class="btn btn-primary">
            {{ __('buttons.store') }}
        </button>
    </div>

</form>
