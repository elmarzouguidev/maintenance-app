<div class="modal fade addWarranty" tabindex="-1" aria-labelledby="add-warranty-title" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <div>
                    <h2 class="modal-title h5 mb-1" id="add-warranty-title">Ajouter une garantie</h2>
                    <p class="text-muted small mb-0">Associez une période de garantie à un ticket.</p>
                </div>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Fermer"></button>
            </div>

            <div class="modal-body">
                <form class="addWarrantyForm" id="addWarrantyForm" action="{{ route('admin:warranty.store') }}" method="post">
                    @csrf
                    <div class="row g-3">
                        <div class="col-12">
                            <label for="warranty-ticket" class="form-label">Ticket <span class="text-danger">*</span></label>
                            <select id="warranty-ticket" name="ticket" class="form-select @error('ticket') is-invalid @enderror" required>
                                <option value="espece">Choisir le ticket</option>
                                @foreach ($tickets as $ticket)
                                    <option value="{{ $ticket->uuid }}">{{ $ticket->code }}</option>
                                @endforeach
                            </select>
                            @error('ticket')
                                <div class="invalid-feedback" role="alert">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="col-12 col-md-6">
                            <label for="warranty-start" class="form-label">Date de départ <span class="text-danger">*</span></label>
                            <input id="warranty-start" type="date" name="start_at"
                                class="form-control @error('start_at') is-invalid @enderror"
                                value="{{ old('start_at', now()->format('Y-m-d')) }}" required>
                            @error('start_at')
                                <div class="invalid-feedback" role="alert">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="col-12 col-md-6">
                            <label for="warranty-end" class="form-label">Date de fin <span class="text-danger">*</span></label>
                            <input id="warranty-end" type="date" name="end_at"
                                class="form-control @error('end_at') is-invalid @enderror"
                                value="{{ old('end_at', now()->format('Y-m-d')) }}" required>
                            @error('end_at')
                                <div class="invalid-feedback" role="alert">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="col-12">
                            <label for="warranty-notes" class="form-label">Description</label>
                            <textarea id="warranty-notes" name="notes"
                                class="form-control @error('description') is-invalid @enderror" maxlength="225" rows="3">{{ old('notes') }}</textarea>
                            @error('description')
                                <div class="invalid-feedback" role="alert">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>

                    <div class="d-flex flex-wrap gap-2 justify-content-end mt-4">
                        <button type="button" class="btn btn-light" data-bs-dismiss="modal">Annuler</button>
                        <button type="submit" class="btn btn-primary">{{ __('buttons.store') }}</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
