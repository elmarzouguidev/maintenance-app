<div class="row">
    <div class="col-lg-12">
        <div class="mb-4">
            <label class="form-label" for="delivery-date-{{ $ticket->uuid }}">Date de sortie *</label>

            <div class="input-group" id="datepicker1">
                <input type="date" id="delivery-date-{{ $ticket->uuid }}" name="date_end" class="form-control @error('date_end') is-invalid @enderror"
                       value="{{ now()->format('Y-m-d') }}"
                       required pattern="[0-9]{4}-[0-9]{2}-[0-9]{2}" readonly>

                <span class="input-group-text"><i class="mdi mdi-calendar"></i></span>
            </div>
            @error('date_end')
                <div class="invalid-feedback d-block" role="alert">{{ $message }}</div>
            @enderror

        </div>
    </div>
    <div class="col-lg-12">
        <div class="mb-4">
            <label for="delivery-mode-{{ $ticket->uuid }}" class="form-label">Mode de sortie *</label>

            <select id="delivery-mode-{{ $ticket->uuid }}" name="mode" class="form-select @error('mode') is-invalid @enderror" required>
                <option value=""></option>
                <option value="parmoi">Par Moi</option>
                <option value="parclient">Par Client</option>
            </select>
            @error('mode')
                <div class="invalid-feedback" role="alert">{{ $message }}</div>
            @enderror

        </div>
    </div>
</div>
<div class="docs-options">
    <label class="form-label" for="delivery-client-{{ $ticket->uuid }}">Nom du client (facultatif)</label>
    <div class="input-group mb-4">

        <input id="delivery-client-{{ $ticket->uuid }}" type="text" class="form-control @error('info_client') is-invalid @enderror" name="info_client"
            value="" aria-describedby="delivery-client-help-{{ $ticket->uuid }}">
        @error('info_client')
            <div class="invalid-feedback" role="alert">{{ $message }}</div>
        @enderror
    </div>
    <div id="delivery-client-help-{{ $ticket->uuid }}" class="form-text">Vous pouvez le renseigner lorsque le client récupère l’appareil.</div>
</div>
