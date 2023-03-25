<div class="row">
    <div class="col-lg-6" wire:ignore>
        <div class="mb-4">
            <label class="form-label">{{ __('invoice.form.company') }} *</label>
            <select name="company" id="selectcompany" class="form-control @error('company') is-invalid @enderror"
                required>
                @foreach ($companies as $company)
                    <option value="{{ $company->id }}">
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

    <div class="col-lg-6" wire:ignore>
        <div class="mb-4">
            <label class="form-label">{{ __('invoice.form.client') }} *</label>

            <select name="client" id="selectclient" class="form-select @error('client') is-invalid @enderror" required>

                <option value="">Choisir</option>
                @foreach ($clients as $client)
                    <option value="{{ $client->id }}">{{ $client->entreprise }}
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
    <div class="docs-options" wire:ignore>
        <label class="form-label">{{ __('invoice.form.number') }}</label>
        <div class="input-group mb-4">

            <span class="input-group-text" id="invoice_prefix">
                {{ $invoicePrefix }}
            </span>

            <input type="text" class="form-control @error('code') is-invalid @enderror" name="code" value=""
                wire:model="invoiceCode" aria-describedby="invoice_prefix" readonly>

            @error('code')
                <span class="invalid-feedback" role="alert">
                    <strong>{{ $message }}</strong>
                </span>
            @enderror
        </div>
    </div>
</div>
