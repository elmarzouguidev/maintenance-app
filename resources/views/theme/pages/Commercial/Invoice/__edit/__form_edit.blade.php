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
        <div class="col-lg-12">
            @include('theme.pages.Commercial.Invoice.__edit.__invoice_actions')
        </div>
        @include('theme.layouts._parts.__messages')

        <form class="repeater" action="{{ $invoice->update_url }}" method="post">
            @csrf
            <div class="card mb-4">
                <div class="card-body">
                    <p class="card-title-desc">{{ __('invoice.form.title') }}</p>
                    <hr>
                    <p class="card-title-desc">
                        Date de création : {{ $invoice->created_at?->format('d-m-Y H:i') }}<br>
                        Date de modification : {{ $invoice->updated_at?->format('d-m-Y H:i') }}<br>
                    </p>
                    <hr>
                    <div class="row">
                        <div class="col-lg-6">
                            @if ($invoice->tickets_count > 0)
                                @include('theme.pages.Commercial.Invoice.__edit.__info_with_tickets')
                            @else
                                @include('theme.pages.Commercial.Invoice.__edit.__info')
                            @endif
                            <div class="docs-options">
                                <label class="form-label">Numéro de facture</label>
                                <div class="input-group mb-4">

                                    <span class="input-group-text" id="invoice_prefix">
                                        {{ \ticketApp::invoicePrefix() }}
                                    </span>
                                    <input type="text" class="form-control @error('code') is-invalid @enderror"
                                        name="code" value="{{ $invoice->code }}" aria-describedby="invoice_prefix"
                                        readonly>
                                    @error('code')
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
                                            <input type="text" name="invoice_date"
                                                class="form-control @error('invoice_date') is-invalid @enderror"
                                                value="{{ $invoice->invoice_date->format('Y-m-d') }}"
                                                data-date-format="yyyy-mm-dd" data-date-container='#datepicker1'
                                                data-provide="datepicker" {{ $readOnly }}>

                                            <span class="input-group-text"><i class="mdi mdi-calendar"></i></span>
                                            @error('invoice_date')
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
                                                class="form-control @error('due_date') is-invalid @enderror"
                                                name="due_date" value="{{ $invoice->due_date->format('Y-m-d') }}"
                                                data-date-format="yyyy-mm-dd" data-date-container='#datepicker2'
                                                data-provide="datepicker" data-date-autoclose="true"
                                                {{ $readOnly }}>
                                            <span class="input-group-text"><i class="mdi mdi-calendar"></i></span>
                                            @error('due_date')
                                                <span class="invalid-feedback" role="alert">
                                                    <strong>{{ $message }}</strong>
                                                </span>
                                            @enderror
                                        </div>
                                    </div>
                                </div>
                            </div>

                            @if ($invoice->tickets_count > 0)
                                @include('theme.pages.Commercial.Invoice.__edit.__tickets')
                            @endif

                            @include('theme.pages.Commercial.Invoice.__edit.b_info')
                        </div>

                        <div class="col-lg-6">
                            <div class="templating-select mb-4">
                                <label class="form-label">{{ __('invoice.form.payment_method') }}</label>
                                <select name="payment_mode"
                                    class="form-control select2-templating @error('payment_mode') is-invalid @enderror"
                                    {{ $readOnly }}>

                                    <option value="Espèce" {{ $invoice->payment_mode === 'Espèce' ? 'selected' : '' }}>
                                        {{ __('Espèce') }}</option>
                                    <option value="Virement"
                                        {{ $invoice->payment_mode === 'Virement' ? 'selected' : '' }}>
                                        {{ __('Virement') }}</option>
                                    <option value="Chèque" {{ $invoice->payment_mode === 'Chèque' ? 'selected' : '' }}>
                                        {{ __('Chèque') }}</option>
                                    <option value="Carte bancaire"
                                        {{ $invoice->payment_mode === 'Carte bancaire' ? 'selected' : '' }}>
                                        {{ __('Carte bancaire') }}</option>
                                    <option value="Lettre de change"
                                        {{ $invoice->payment_mode === 'Lettre de change' ? 'selected' : '' }}>
                                        {{ __('Lettre de change') }}</option>
                                    <option value="Prélèvement"
                                        {{ $invoice->payment_mode === 'Prélèvement' ? 'selected' : '' }}>
                                        {{ __('Prélèvement') }}
                                    </option>
                                    <option value="Virement bancaire"
                                        {{ $invoice->payment_mode === 'Virement bancaire' ? 'selected' : '' }}>
                                        {{ __('Virement bancaire') }}</option>

                                </select>
                                @error('payment_mode')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror

                            </div>
                            <div class=" mb-4">
                                <label>{{ __('invoice.form.admin_note') }}</label>
                                <textarea name="admin_notes" id="textarea" class="form-control @error('admin_notes') is-invalid @enderror"
                                    maxlength="225" rows="5" {{ $readOnly }}>{{ $invoice->admin_notes }}
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
                    </div>
                    <div class="row" id="articles_list">
                        <div class="col-lg-12 mb-4">

                            @if ($invoice->articles->count() > 0)
                                @include('theme.pages.Commercial.Invoice.__edit.__edit_articles')
                            @endif
                            <hr>
                            <p class="card-title-desc">Ajouter des nouveaux articles</p>
                            @include('theme.pages.Commercial.Invoice.__edit.__add_articles')
                        </div>
                        <div class="col-lg-12">
                            <div class="justify-content-end">
                                <div class="card border border-primary">
                                    <div class="card-header bg-transparent border-primary">

                                        <h5 class="my-0 text-primary">
                                            <i class="mdi mdi-alarm-panel-outline me-3"></i>

                                            Montant HT : {{ $invoice->formated_price_ht }} DH

                                        </h5>
                                        <hr>

                                        @if ($invoice->formated_total_remise > 0)
                                            <h5 class="my-0 text-danger">
                                                <i class="mdi mdi-alarm-panel-outline me-3"></i>
                                                REMISE : {{ $invoice->formated_total_remise }} DH
                                            </h5>
                                            <hr>
                                        @endif

                                        <h5 class="my-0 text-danger">
                                            <i class="mdi mdi-alarm-panel-outline me-3"></i>
                                            Montant TVA : {{ $invoice->formated_total_tva }} DH
                                        </h5>
                                        <hr>
                                        <h5 class="my-0 text-info">
                                            <i class="mdi mdi-alarm-panel-outline me-3"></i>
                                            NET TTC A PAYER : {{ $invoice->formated_price_total }} DH
                                        </h5>


                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="card mb-4">
                <div class="card-body">
                    <p class="card-title-desc">{{ __('invoice.form.title') }}</p>
                    <div class="row">
                        <div class="mb-3 col-lg-12">
                            <label for="condition_general">{{ __('invoice.form.condition_general') }}</label>
                            <textarea name="condition_general" id="condition_general" rows="5"
                                class="form-control @error('condition_general') is-invalid @enderror" {{ $readOnly }}>{{ $invoice->condition }}</textarea>
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
                    <button type="submit" class="btn btn-primary waves-effect waves-light" {{ $disabled }}>
                        {{ __('buttons.store') }}
                    </button>
                    <button type="submit" class="btn btn-secondary waves-effect waves-light" {{ $disabled }}>
                        {{ __('buttons.store_draft') }}
                    </button>
                </div>
            </div>

        </form>
    </div>

 

</div>

@include('theme.pages.Commercial.Invoice.__datatable.__send_invoice')

@include('theme.pages.Commercial.Invoice.__edit.__print_document')
