<div class="row">
    <div class="col-lg-12">

        <div class="card">
            <div class="card-body">

                <p class="card-title-desc">Entrer les information de la facture</p>

                <form action="{{ route('commercial:invoices.store') }}" method="post">
                    @csrf
                    @honeypot
                    <div class="row">
                        <div class="col-lg-6">

                            <div class="mb-3">
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
                                <div class="input-group mb-3">
                                  
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
                                                name="date"
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


                        </div>

                        <div class="col-lg-6">
                            {{-- @include('theme.pages.Commercial.Invoice.__create.__javascript.__ajax_client') --}}
                            <div class="templating-select">
                                <label class="form-label">Templating</label>
                                <select name="client" class="form-control select2-templating">
                                    <optgroup label="Alaskan/Hawaiian Time Zone">
                                        <option value="AK">Alaska</option>
                                        <option value="HI">Hawaii</option>
                                    </optgroup>
                                    <optgroup label="Pacific Time Zone">
                                        <option value="CA">California</option>
                                        <option value="NV">Nevada</option>
                                        <option value="OR">Oregon</option>
                                        <option value="WA">Washington</option>
                                    </optgroup>
                                </select>

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
