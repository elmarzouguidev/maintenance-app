<div class="row">
    <div class="col-lg-4">
        <div class="mb-4">
            <label class="form-label">Société *</label>
            <select name="company" class="form-control select2 @error('company') is-invalid @enderror" required>
                <option value="">Select</option>
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
    <div class="col-lg-4">
        <div class="mb-4">
            <label class="form-label">Client *</label>
            <select name="client" class="form-control select2 @error('client') is-invalid @enderror" required>
                <option value="">Select</option>

                @foreach ($clients as $client)
                    <option value="{{ $client->id }}">{{ $client->entreprise }}</option>
                @endforeach

            </select>
            @error('client')
                <span class="invalid-feedback" role="alert">
                    <strong>{{ $message }}</strong>
                </span>
            @enderror

        </div>
    </div>
    <div class="col-lg-4">
        <div class="mb-4">
            <label class="form-label">Facture annulée *</label>
            <input type="text" class="form-control @error('invoice_number') is-invalid @enderror" name="invoice_number"
            required>
            @error('invoice_number')
                <span class="invalid-feedback" role="alert">
                    <strong>{{ $message }}</strong>
                </span>
            @enderror

        </div>
    </div>
</div>

<div class="docs-options">
    <label class="form-label">Numéro de facture avoir *</label>
    <div class="input-group mb-4">

        <span class="input-group-text" id="invoice_prefix">
            {{ \ticketApp::invoicePrefix() }}
        </span>
        <input type="text" class="form-control @error('invoice_code') is-invalid @enderror"
            name="invoice_code" value="{{ \ticketApp::nextInvoiceNumber() }}"
            aria-describedby="invoice_prefix" readonly>
        @error('invoice_code')
            <span class="invalid-feedback" role="alert">
                <strong>{{ $message }}</strong>
            </span>
        @enderror
    </div>
</div>
