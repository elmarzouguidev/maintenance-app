<div class="row">
    <div class="col-lg-12">
        <form class="repeater" action="{{ $estimate->update_url}}" method="post">
            @csrf
            @honeypot
            <div class="card mb-4">
                <div class="card-body">

                    <p class="card-title-desc">Entrer les information de la facture</p>

                    <div class="row">
                        <div class="col-lg-6">

                             @include('theme.pages.Commercial.Estimate.__edit.__info')
                           
                            <div class="docs-options">
                                <label class="form-label">Numéro de Devis</label>
                                <div class="input-group mb-4">

                                    <span class="input-group-text" id="invoice_prefix">
                                        {{ \ticketApp::estimatePrefix() }}
                                    </span>
                                    <input type="text" class="form-control @error('invoice_code') is-invalid @enderror"
                                        name="invoice_code" value="{{ $estimate->estimate_code }}"
                                        aria-describedby="estimate_prefix" readonly>
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
                                        <label>* Date de Devis</label>
                                        <div class="input-group" id="datepicker1">
                                            <input type="text" name="estimate_date"
                                                class="form-control @error('estimate_date') is-invalid @enderror"
                                                value="{{ $estimate->estimate_date }}" data-date-format="dd-mm-yyyy"
                                                data-date-container='#datepicker1' data-provide="datepicker">

                                            <span class="input-group-text"><i class="mdi mdi-calendar"></i></span>
                                            @error('estimate_date')
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
                                                name="date_due" value="{{ $estimate->date_due }}"
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
                            {{-- @include('theme.pages.Commercial.Estimate.__create.__javascript.__ajax_client') --}}
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
                                <textarea name="admin_notes" id="textarea"
                                    class="form-control @error('admin_notes') is-invalid @enderror" maxlength="225"
                                    rows="5" placeholder="This textarea has a limit of 225 chars.">{{$estimate->admin_notes}}
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
                    <p class="card-title-desc">Entrer les Détails de la facture</p>
                    <div class="row">
                        <div class="col-lg-4 mb-4">

                        </div>
                        <div class="col-lg-4 mb-4">

                        </div>

                    </div>
                    <div class="row" id="articles_list">
                        <div class="col-lg-12 mb-4">
                            @include('theme.pages.Commercial.Estimate.__edit.__edit_articles')
                            {{--@include('theme.pages.Commercial.Estimate.__edit.__add_article')--}}
                        </div>
                        <div class="col-lg-12">
                            <div class="justify-content-end">
                                <div class="card border border-primary">
                                    <div class="card-header bg-transparent border-primary">
                                        <h5 class="my-0 text-primary">
                                            <i class="mdi mdi-alarm-panel-outline me-3"></i>
                                            Montant HT :	{{$estimate->formated_price_ht}} DH
                                        </h5>
                                        <hr>
                                        <h5 class="my-0 text-info">
                                            <i class="mdi mdi-alarm-panel-outline me-3"></i>
                                            Montant TTC :  {{$estimate->formated_price_total}} DH
                                        </h5>
                                        <hr>
                                        <h5 class="my-0 text-danger">
                                            <i class="mdi mdi-alarm-panel-outline me-3"></i>
                                            Montant TVA :  {{$estimate->formated_total_tva}} DH
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
                    {{-- @livewire('commercial.Estimate.create.articles') --}}

                </div>
            </div>
            <div class="card mb-4">
                <div class="card-body">
                    <p class="card-title-desc">Autre information</p>
                    <div class="row">
                        <div class="mb-3 col-lg-12">
                            <label for="client_notes">Note Client</label>
                            <textarea name="client_notes" id="client_notes"
                                class="form-control @error('client_notes') is-invalid @enderror">{{$estimate->client_notes}}</textarea>
                            @error('client_notes')
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                            @enderror
                        </div>
                        <div class="mb-3 col-lg-12">
                            <label for="condition_general">Conditions générales de vente</label>
                            <textarea name="condition_general" id="condition_general"
                                class="form-control @error('condition_general') is-invalid @enderror">{{$estimate->condition_general}}</textarea>
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
                    <button type="submit" class="btn btn-primary waves-effect waves-light">
                        Update
                    </button>
                    <button type="submit" class="btn btn-secondary waves-effect waves-light">
                        Sauvegarder en tant que brouillon
                    </button>
                </div>
            </div>

        </form>
    </div>
</div>