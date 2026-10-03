<section class="card" aria-labelledby="ticket-create-title">
    <div class="card-body">
        <div class="mb-4">
            <h2 id="ticket-create-title" class="h5 mb-1">Créer un ticket</h2>
            <p class="text-muted mb-0">Renseignez l’appareil, le client et le problème signalé.</p>
        </div>

        @if (session('success'))
            <div class="alert alert-success" role="status">{{ session('success') }}</div>
        @endif
        @if (session('error'))
            <div class="alert alert-danger" role="alert">{{ session('error') }}</div>
        @endif
        @if ($errors->any())
            <div class="alert alert-danger" role="alert">
                <p class="fw-semibold mb-2">Vérifiez les informations saisies :</p>
                <ul class="mb-0">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <form id="ticketForm" action="{{ route('admin:tickets.createPost') }}" method="post" enctype="multipart/form-data">
            @csrf

            <div class="row g-3">
                <div class="col-12">
                    <div class="border rounded p-3">
                        <div class="form-check form-switch mb-3">
                            <input class="form-check-input" name="is_retour" type="checkbox" id="is_retour" onclick="myFunction()" @checked(old('is_retour'))>
                            <label class="form-check-label fw-medium" for="is_retour">Ce ticket concerne un retour ?</label>
                        </div>

                        <label for="ticket_retoure" class="form-label">Ticket d’origine</label>
                        <select {{ old('is_retour') ? '' : 'disabled' }} name="ticket_retoure" id="ticket_retoure" class="form-select select2 @error('ticket_retoure') is-invalid @enderror">
                            <option value="">Choisir le ticket retourné</option>
                            @foreach ($tickets as $ticket)
                                <option data-client="{{ $ticket->client_id }}" data-article="{{ $ticket->article }}" value="{{ $ticket->id }}" @selected(old('ticket_retoure') == $ticket->id)>
                                    {{ $ticket->code }} — (N° retour : {{ $ticket->retour_number }})
                                </option>
                            @endforeach
                        </select>
                        @error('ticket_retoure')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                </div>

                <div class="col-12 col-lg-6">
                    <label for="article" class="form-label">Appareil <span class="text-danger" aria-hidden="true">*</span></label>
                    <input id="article" name="article" type="text" class="form-control @error('article') is-invalid @enderror" value="{{ old('article') }}" placeholder="Saisir l’appareil" required>
                    @error('article')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <div class="col-12 col-lg-6">
                    <div class="d-flex align-items-center justify-content-between gap-2 mb-1">
                        <label for="clientList" class="form-label mb-0">Client <span class="text-danger" aria-hidden="true">*</span></label>
                        <button type="button" class="btn btn-sm btn-outline-primary" data-bs-toggle="modal" data-bs-target=".createClient">
                            <i class="bx bx-plus" aria-hidden="true"></i>
                            Ajouter un client
                        </button>
                    </div>
                    <select id="clientList" name="client" class="form-select select2 @error('client') is-invalid @enderror" required>
                        <option value="">Choisir le client</option>
                        <optgroup label="Clients">
                            @foreach ($clients as $client)
                                <option data-tickclient="{{ $client->id }}" value="{{ $client->id }}" @selected(old('client') == $client->id)>
                                    {{ $client->entreprise }}
                                </option>
                            @endforeach
                        </optgroup>
                    </select>
                    @error('client')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <div class="col-12">
                    <label for="ticketdesc-editor" class="form-label">Description du problème <span class="text-danger" aria-hidden="true">*</span></label>
                    <textarea class="form-control @error('description') is-invalid @enderror" name="description" id="ticketdesc-editor" rows="6" placeholder="Décrivez le problème signalé" required>{{ old('description') }}</textarea>
                    @error('description')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <div class="col-12">
                    <label for="ticket-photo" class="form-label">Photo de l’appareil <span class="text-danger" aria-hidden="true">*</span></label>
                    <input id="ticket-photo" class="form-control @error('photo') is-invalid @enderror" name="photo" type="file" accept="image/*" required>
                    <div class="form-text">Sélectionnez une image de l’appareil reçu.</div>
                    @error('photo')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <div class="col-12 d-flex justify-content-end">
                    <button type="submit" class="btn btn-primary d-inline-flex align-items-center gap-2">
                        <i class="bx bx-save" aria-hidden="true"></i>
                        <span>{{ __('buttons.store') }}</span>
                    </button>
                </div>
            </div>
        </form>
    </div>
</section>
