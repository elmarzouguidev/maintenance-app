<div class="row">
    <div class="col-lg-6">
        <div class="mb-4">
            <label class="form-label">Société *</label>
            <select name="company" class="form-control  @error('company') is-invalid @enderror" readonly>
                <option value="{{ optional($command->company)->id }}" selected>
                    {{ optional($command->company)->name }}
                </option>
            </select>
            @error('company')
                <span class="invalid-feedback" role="alert">
                    <strong>{{ $message }}</strong>
                </span>
            @enderror

        </div>
    </div>
    <div class="col-lg-6">
        <div class="mb-4">
            <label class="form-label">Client *</label>
            <select name="client" class="form-control  @error('client') is-invalid @enderror" readonly>
                <option value="{{ optional($command->client)->id }}" selected>{{ optional($command->client)->entreprise }}</option>
            </select>
            @error('client')
                <span class="invalid-feedback" role="alert">
                    <strong>{{ $message }}</strong>
                </span>
            @enderror

        </div>
    </div>

</div>
