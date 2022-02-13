<div class="row">
    @php
        $readOnly = '';
        $disabled = '';
        if ($invoice->bill_count) {
            $readOnly = 'readonly';
            $disabled = 'disabled';
        }
    @endphp
    <div class="col-lg-12">
        <form class="repeater" action="{{ $invoice->update_url }}" method="post">
            @csrf
            @honeypot
            <div class="card mb-4">
                <div class="card-body">
                    <p class="card-title-desc">{{ __('invoice.form.title') }}</p>
                    <div class="row">
                        <div class="col-lg-6">

                            @include('theme.pages.Commercial.InvoiceAvoir.__edit.__info')

                            <div class="docs-options">
                                <label class="form-label">Numéro de facture</label>
                                <div class="input-group mb-4">

                                    <span class="input-group-text" id="invoice_prefix">
                                        {{ \ticketApp::invoicePrefix() }}
                                    </span>
                                    <input type="text" class="form-control @error('invoice_code') is-invalid @enderror"
                                        name="invoice_code" value="{{ $invoice->invoice_code }}"
                                        aria-describedby="invoice_prefix" readonly>
                                    @error('invoice_code')
                                        <span class="invalid-feedback" role="alert">
                                            <strong>{{ $message }}</strong>
                                        </span>
                                    @enderror
                                </div>
                            </div>
                            <div class="col-lg-12">
                                <div class="row">
                                    <div class="col-lg-6 mb-4">
                                        <label>{{ __('invoice.form.date_invoice') }} *</label>
                                        <div class="input-group" id="datepicker1">
                                            <input type="text" name="date_invoice"
                                                class="form-control @error('date_invoice') is-invalid @enderror"
                                                value="{{ $invoice->date_invoice }}" data-date-format="dd-mm-yyyy"
                                                data-date-container='#datepicker1' data-provide="datepicker"
                                                {{ $readOnly }}>

                                            <span class="input-group-text"><i class="mdi mdi-calendar"></i></span>
                                            @error('date_invoice')
                                                <span class="invalid-feedback" role="alert">
                                                    <strong>{{ $message }}</strong>
                                                </span>
                                            @enderror
                                        </div>
                                    </div>

                                    <div class="col-lg-6 mb-4">
                                        <label> {{ __('invoice.form.date_due') }}</label>
                                        <div class="input-group" id="datepicker2">
                                            <input type="text"
                                                class="form-control @error('date_due') is-invalid @enderror"
                                                name="date_due" value="{{ $invoice->date_due }}"
                                                data-date-format="dd-mm-yyyy" data-date-container='#datepicker2'
                                                data-provide="datepicker" data-date-autoclose="true"
                                                {{ $readOnly }}>
                                            <span class="input-group-text"><i class="mdi mdi-calendar"></i></span>
                                            @error('date_due')
                                                <span class="invalid-feedback" role="alert">
                                                    <strong>{{ $message }}</strong>
                                                </span>
                                            @enderror
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="col-lg-6">
                            {{-- @include('theme.pages.Commercial.InvoiceAvoir.__create.__javascript.__ajax_client') --}}
                            <div class="templating-select mb-4">
                                <label class="form-label">{{ __('invoice.form.payment_method') }}</label>
                                <select name="payment_method"
                                    class="form-control select2-templating @error('payment_method') is-invalid @enderror"
                                    {{ $readOnly }}>

                                    <option value="espece">{{ __('invoice.form.paympent_method_espece') }}</option>
                                    <option value="virement" selected>
                                        {{ __('invoice.form.paympent_method_virement') }}
                                    </option>
                                    <option value="cheque">{{ __('invoice.form.paympent_method_cheque') }}</option>

                                </select>
                                @error('payment_method')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror

                            </div>
                            <div class=" mb-4">
                                <label>{{ __('invoice.form.admin_note') }}</label>
                                <textarea name="admin_notes" id="textarea"
                                    class="form-control @error('admin_notes') is-invalid @enderror" maxlength="225"
                                    rows="5" {{ $readOnly }}>{{ $invoice->admin_notes }}
                                </textarea>
                                @error('admin_notes')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>
                        </div>
                    </div>


                </div>
            </div>
            <div class="card mb-4">
                <div class="card-body">
                    <p class="card-title-desc">{{ __('invoice.form.title') }}</p>
                    <div class="row">
                        <div class="col-lg-4 mb-4">

                        </div>
                        <div class="col-lg-4 mb-4">

                        </div>

                    </div>
                    <div class="row" id="articles_list">
                        <div class="col-lg-12 mb-4">
                            @include('theme.pages.Commercial.InvoiceAvoir.__edit.__edit_articles')
                            {{-- @include('theme.pages.Commercial.InvoiceAvoir.__edit.__add_article') --}}
                        </div>
                        <div class="col-lg-12">
                            <div class="justify-content-end">
                                <div class="card border border-primary">
                                    <div class="card-header bg-transparent border-primary">
                                        <h5 class="my-0 text-primary">
                                            <i class="mdi mdi-alarm-panel-outline me-3"></i>
                                            {{ __('invoice.form.total_ht') }} : {{ $invoice->formated_price_ht }}
                                            DH
                                        </h5>
                                        <hr>
                                        <h5 class="my-0 text-info">
                                            <i class="mdi mdi-alarm-panel-outline me-3"></i>
                                            {{ __('invoice.form.total_ttc') }} :
                                            {{ $invoice->formated_price_total }} DH
                                        </h5>
                                        <hr>
                                        <h5 class="my-0 text-danger">
                                            <i class="mdi mdi-alarm-panel-outline me-3"></i>
                                            Montant TVA : {{ $invoice->formated_total_tva }} DH
                                        </h5>
                                    </div>
                                    {{-- <div class="card-body">
                                        <h5 class="card-title">card title</h5>
                                        <p class="card-text">Some quick example text to build on the card title and
                                            make up the bulk of the card's content.</p>
                                    </div> --}}
                                </div>
                            </div>
                        </div>
                    </div>
                    {{-- @livewire('commercial.invoice.create.articles') --}}

                </div>
            </div>
            <div class="card mb-4">
                <div class="card-body">
                    <p class="card-title-desc">{{ __('invoice.form.title') }}</p>
                    <div class="row">
                        <div class="mb-3 col-lg-12">
                            <label for="client_notes">{{ __('invoice.form.client_note') }}</label>
                            <textarea name="client_notes" id="client_notes"
                                class="form-control @error('client_notes') is-invalid @enderror"
                                {{ $readOnly }}>{{ $invoice->client_notes }}</textarea>
                            @error('client_notes')
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                            @enderror
                        </div>
                        <div class="mb-3 col-lg-12">
                            <label for="condition_general">{{ __('invoice.form.condition_general') }}</label>
                            <textarea name="condition_general" id="condition_general"
                                class="form-control @error('condition_general') is-invalid @enderror"
                                {{ $readOnly }}>{{ $invoice->condition_general }}</textarea>
                            @error('condition_general')
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                            @enderror
                        </div>
                    </div>
                </div>
            </div>
            <div class="d-flex flex-wrap gap-2 justify-content-end mb-4">
                <div class="">
                    <button type="submit" class="btn btn-primary waves-effect waves-light" {{$disabled}}>
                        {{ __('buttons.store') }}
                    </button>
                    <button type="submit" class="btn btn-secondary waves-effect waves-light" {{$disabled}}>
                        {{ __('buttons.store_draft') }}
                    </button>
                </div>
            </div>

        </form>
    </div>
</div>