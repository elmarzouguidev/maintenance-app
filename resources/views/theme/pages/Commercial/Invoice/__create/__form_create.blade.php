<div class="row">
    <div class="col-lg-12">
        <form class="repeater" action="{{ route('commercial:invoices.store') }}" method="post">
            @csrf
            @honeypot
            <div class="card mb-4">
                <div class="card-body">

                    <p class="card-title-desc">Entrer les information de la facture</p>

                    <div class="row">
                        <div class="col-lg-6">

                            {{-- @include('theme.pages.Commercial.Invoice.__create.__info') --}}
                            @livewire('commercial.invoice.create.info')

                            <div class="docs-options">
                                <label class="form-label">Numéro de facture</label>
                                <div class="input-group mb-4">

                                    <span class="input-group-text" id="invoice_prefix">
                                        {{ \ticketApp::invoicePrefix() }}
                                    </span>
                                    <input type="text" class="form-control @error('invoice_code') is-invalid @enderror"
                                        name="invoice_code" value="{{ \ticketApp::nextInvoiceNumber() }}"
                                        aria-describedby="invoice_prefix" placeholder="">
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
                                        <label>* Date de facture</label>
                                        <div class="input-group" id="datepicker1">
                                            <input type="text" name="date_invoice"
                                                class="form-control @error('date_invoice') is-invalid @enderror"
                                                value="{{ now()->format('d-m-Y') }}" data-date-format="dd-mm-yyyy"
                                                data-date-container='#datepicker1' data-provide="datepicker">

                                            <span class="input-group-text"><i class="mdi mdi-calendar"></i></span>
                                            @error('date_invoice')
                                                <span class="invalid-feedback" role="alert">
                                                    <strong>{{ $message }}</strong>
                                                </span>
                                            @enderror
                                        </div>
                                    </div>

                                    <div class="col-lg-6 mb-4">
                                        <label> Date d'échéance</label>
                                        <div class="input-group" id="datepicker2">
                                            <input type="text"
                                                class="form-control @error('date_due') is-invalid @enderror"
                                                name="date_due" value="{{ now()->format('d-m-Y') }}"
                                                data-date-format="dd-mm-yyyy" data-date-container='#datepicker2'
                                                data-provide="datepicker" data-date-autoclose="true">
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

                            <div class="col-lg-6">
                                <h5 class="font-size-14 mb-3">
                                    Empêcher l'envoi de rappels pour cette facture
                                </h5>
                                <div>
                                    <input type="checkbox" id="switch1" switch="none" checked />
                                    <label for="switch1" data-on-label="Oui" data-off-label="Non"></label>
                                </div>
                            </div>
                        </div>

                        <div class="col-lg-6">
                            {{-- @include('theme.pages.Commercial.Invoice.__create.__javascript.__ajax_client') --}}
                            <div class="templating-select mb-4">
                                <label class="form-label">Autoriser les moyens de règlement pour cette
                                    facture</label>
                                <select name="payment_method"
                                    class="form-control select2-templating @error('payment_method') is-invalid @enderror">

                                    <option value="espece">Espèce</option>
                                    <option value="virement" selected>Virement</option>
                                    <option value="cheque">Chèque</option>

                                </select>
                                @error('payment_method')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror

                            </div>
                            <div class=" mb-4">
                                <label>Note Admin</label>
                                <textarea name="note_admin" id="textarea"
                                    class="form-control @error('note_admin') is-invalid @enderror" maxlength="225"
                                    rows="5" placeholder="This textarea has a limit of 225 chars.">
                                </textarea>
                                @error('note_admin')
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
                    <p class="card-title-desc">Entrer les Détails de la facture</p>
                    <div class="row">
                        <div class="col-lg-4 mb-4">
                            <div class="docs-actions">
                                <div class="input-group mb-3">
                                    <button type="button" class="btn btn-primary" data-method="getDate"
                                        data-target="#putDate">

                                        Ajouter un article

                                    </button>
                                    <input type="text" class="form-control" id="putDate">
                                </div>
                            </div>
                        </div>
                        <div class="col-lg-4 mb-4">

                            <input type="number" name="quantity" id="quantity" class="form-control" />
                        </div>

                    </div>
                    <div class="row">
                        <div class="col-lg-12 mb-4">
                            @include('theme.pages.Commercial.Invoice.__create.__add_articles')
                        </div>
                        <div class="col-lg-12">
                            <div class="justify-content-end">
                                <div class="card border border-primary">
                                    <div class="card-header bg-transparent border-primary">
                                        <h5 class="my-0 text-primary">
                                            <i class="mdi mdi-alarm-panel-outline me-3"></i>
                                            Total HT :	
                                        </h5>
                                        <hr>
                                        <h5 class="my-0 text-danger">
                                            <i class="mdi mdi-alarm-panel-outline me-3"></i>
                                            Total TTC :
                                        </h5>
                                    </div>
                                    {{--<div class="card-body">
                                        <h5 class="card-title">card title</h5>
                                        <p class="card-text">Some quick example text to build on the card title and
                                            make up the bulk of the card's content.</p>
                                    </div>--}}
                                </div>
                            </div>
                        </div>
                    </div>
                    {{-- @livewire('commercial.invoice.create.articles') --}}

                </div>
            </div>
            <div class="card mb-4">
                <div class="card-body">
                    <p class="card-title-desc">Entrer les Détails de la facture</p>
                    <div class="row">
                        <div class="mb-3 col-lg-12">
                            <label for="client_note">Note Client</label>
                            <textarea name="client_note" id="client_note"
                                class="form-control @error('client_note') is-invalid @enderror"></textarea>
                            @error('client_note')
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                            @enderror
                        </div>
                        <div class="mb-3 col-lg-12">
                            <label for="condition">Conditions générales de vente</label>
                            <textarea name="condition" id="condition"
                                class="form-control @error('condition') is-invalid @enderror"></textarea>
                            @error('client_note')
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
                    <button type="submit" class="btn btn-primary waves-effect waves-light">
                        Enregistrer
                    </button>
                    <button type="submit" class="btn btn-secondary waves-effect waves-light">
                        Sauvegarder en tant que brouillon
                    </button>
                </div>
            </div>

        </form>
    </div>
</div>
