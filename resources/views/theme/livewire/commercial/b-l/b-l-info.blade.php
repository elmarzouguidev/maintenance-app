<div class="row">
    <div class="col-lg-6">
        <div class="mb-4">
            <label class="form-label">{{ __('invoice.form.company') }} *</label>
            <select wire:model="selectCompany" name="company" class="form-control @error('company') is-invalid @enderror"
                required>
                <option value="">Choisir</option>

                @foreach ($companies as $company)
                    <option value="{{ $company->id }}" wire:key="{{ $loop->index }}">
                        {{ $company->name }}
                    </option>
                @endforeach

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
            <select name="client" class="form-control  @error('client') is-invalid @enderror" required>
                <option value="">Choisir</option>
                @foreach ($clients as $client)
                    <option value="{{ $client->id }}" wire:key="{{ $loop->index }}">{{ $client->entreprise }}
                    </option>
                @endforeach

            </select>
            @error('client')
                <span class="invalid-feedback" role="alert">
                    <strong>{{ $message }}</strong>
                </span>
            @enderror

        </div>
    </div>

    <div class="docs-options">
        <label class="form-label">Numéro de BL</label>
        <div class="input-group mb-4">

            <span class="input-group-text" id="prefix_blivraison">
                {{ $bCommandPrefix }}
            </span>

            <input type="text" class="form-control @error('b_code') is-invalid @enderror" name="b_code"
                value="" wire:model.defer="bCommandCode" aria-describedby="prefix_blivraison" readonly>

            @error('b_code')
                <span class="invalid-feedback" role="alert">
                    <strong>{{ $message }}</strong>
                </span>
            @enderror
        </div>
    </div>


</div>
