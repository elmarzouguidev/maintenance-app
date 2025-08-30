<div class="row">
    <div class="col-lg-12">
        <div class="mb-4">
            <label class="form-label">Ticket *</label>
            <input id="article" name="article" type="text"
                   class="form-control @error('article') is-invalid @enderror"
                   value="{{$ticket->article}}">
            @error('article')
                <span class="invalid-feedback" role="alert">
                    <strong>{{ $message }}</strong>
                </span>
            @enderror
        </div>
    </div>

    <div class="col-lg-12">
        <div class="mb-4">
            <label class="form-label">Client *</label>
            @if($ticket->client)
                <div class="alert alert-info mb-2">
                    <i class="bx bx-info-circle me-1"></i>
                    <strong>Client actuel:</strong> {{ $ticket->client->entreprise }}
                </div>
            @endif
            <select id="client_id" name="client_id" class="form-select select2 @error('client_id') is-invalid @enderror">
                <option value="">Sélectionner un client</option>
                @foreach ($clients as $client)
                    <option value="{{ $client->id }}" {{ $ticket->client_id == $client->id ? 'selected' : '' }}>
                        {{ $client->entreprise }}
                    </option>
                @endforeach
            </select>
            @error('client_id')
                <span class="invalid-feedback" role="alert">
                    <strong>{{ $message }}</strong>
                </span>
            @enderror
        </div>
    </div>
</div>
