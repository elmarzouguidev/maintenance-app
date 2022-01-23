<div class="row">
    <div class="col-lg-12">

        <div class="card mb-4">
            <div class="card-body">

                <p class="card-title-desc">Entrer les information de la facture</p>

                <form action="{{ route('commercial:invoices.store') }}" method="post">
                    @csrf
                    @honeypot
                    <div class="row">
                        <div class="col-lg-6">

                            <div class="mb-4">
                                <label class="form-label">Client *</label>
                                <select name="client" class="form-control select2">
                                    <option value="">Select</option>

                                    @foreach ($clients as $client)
                                        <option value="{{ $client->id }}">{{ $client->entreprise }}</option>
                                    @endforeach

                                </select>

                            </div>
                            <div class="docs-options">
                                <label class="form-label">Numéro de facture</label>
                                <div class="input-group mb-4">
                                  
                                    <span class="input-group-text" id="invoice_prefix">
                                        {{\ticketApp::invoicePrefix()}}
                                    </span>
                                    <input type="text" class="form-control" name="invoice_code"
                                      value="{{\ticketApp::nextInvoiceNumber()}}"
                                        aria-describedby="invoice_prefix" placeholder="">
                                </div>
                            </div>
                            <div class="col-lg-12">
                                <div class="row">
                                    <div class="col-lg-6 mb-4">
                                        <label>* Date de facture</label>
                                        <div class="input-group" id="datepicker1">
                                            <input 
                                                type="text"
                                                name="invoice_date" 
                                                class="form-control"
                                                value="{{now()->format('d-m-Y')}}"
                                                data-date-format="dd-mm-yyyy" 
                                                data-date-container='#datepicker1' 
                                                data-provide="datepicker"
                                            >

                                            <span class="input-group-text"><i class="mdi mdi-calendar"></i></span>
                                        </div>
                                    </div>

                                    <div class="col-lg-6 mb-4">
                                        <label> Date d'échéance</label>
                                        <div class="input-group" id="datepicker2">
                                            <input 
                                                type="text" 
                                                class="form-control" 
                                                name="invoice_due_date" 
                                                value="{{now()->format('d-m-Y')}}"
                                                data-date-format="dd-mm-yyyy" 
                                                data-date-container='#datepicker2' 
                                                data-provide="datepicker"
                                                data-date-autoclose="true"
                                            >
                                            <span class="input-group-text"><i class="mdi mdi-calendar"></i></span>
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
                                <label class="form-label">Autoriser les moyens de règlement pour cette facture</label>
                                <select name="payment_method" class="form-control select2-templating">
                                  
                                        <option value="AK">Espèce</option>
                                        <option value="HI">Virement</option>
                                        <option value="HI">Chèque</option>
                                   
                                </select>

                            </div>
                            <div class=" mb-4">
                                <label>Note Admin</label>
                                <textarea id="textarea" class="form-control" maxlength="225" rows="5"
                                placeholder="This textarea has a limit of 225 chars."></textarea>
                            
                            </div>
                        </div>
                    </div>
                    <div class="d-flex flex-wrap gap-2">
                        <button type="submit" class="btn btn-primary waves-effect waves-light">Enregistrer</button>

                    </div>
                </form>

            </div>
        </div>

    </div>
</div>
