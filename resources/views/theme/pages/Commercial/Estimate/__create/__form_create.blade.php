<div class="row">
    <div class="col-lg-12">
        <form class="repeater" action="{{ route('commercial:estimates.store') }}" method="post">
            @csrf
            @honeypot
            <div class="card mb-4">
                <div class="card-body">

                    <p class="card-title-desc">Entrer les information de la facture</p>

                    <div class="row">
                        <div class="col-lg-6">

                            {{-- @include('theme.pages.Commercial.Estimate.__create.__info') --}}
                            @livewire('commercial.invoice.create.info')

                            <div class="docs-options">
                                <label class="form-label">Numéro de devis</label>
                                <div class="input-group mb-4">

                                    <span class="input-group-text" id="estimate_prefix">
                                        {{ \ticketApp::estimatePrefix() }}
                                    </span>
                                    <input type="text" class="form-control @error('estimate_code') is-invalid @enderror"
                                        name="estimate_code" value="{{ \ticketApp::nextEstimateNumber() }}"
                                        aria-describedby="invoice_prefix" readonly>
                                    @error('estimate_code')
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
                                            <input type="text" name="estimate_date"
                                                class="form-control @error('estimate_date') is-invalid @enderror"
                                                value="{{ now()->format('d-m-Y') }}" data-date-format="dd-mm-yyyy"
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
                                                name="date_due" value="{{ \ticketApp::estimateDueDate() }}"
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

                            </div>
                            <div class=" mb-4">
                                <label>Note Admin</label>
                                <textarea name="admin_notes" id="textarea"
                                    class="form-control @error('admin_notes') is-invalid @enderror" maxlength="225"
                                    rows="5" placeholder="This textarea has a limit of 225 chars.">
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
                    <div class="row">
                        <div class="col-lg-12 mb-4">
                            @include('theme.pages.Commercial.Estimate.__create.__add_articles')
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
                    {{-- @livewire('commercial.Estimate.create.articles') --}}

                </div>
            </div>
            <div class="card mb-4">
                <div class="card-body">
                    <p class="card-title-desc">Entrer les Détails de devis</p>
                    <div class="row">
                        <div class="mb-3 col-lg-12">
                            <label for="client_notes">Note Client</label>
                            <textarea name="client_notes" id="client_notes"
                                class="form-control @error('client_notes') is-invalid @enderror"></textarea>
                            @error('client_notes')
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                            @enderror
                        </div>
                        <div class="mb-3 col-lg-12">
                            <label for="condition_general">Conditions générales de vente</label>
                            <textarea name="condition_general" id="condition_general"
                                class="form-control @error('condition_general') is-invalid @enderror"></textarea>
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