<div class="row">
    <div class="col-12">
        <div class="card">
            <div class="card-body">

                <div class="row">
                    <div class="col-xl col-sm-4">
                        <div class="mt-4">

                            @foreach ($companies as $company)

                                <div class="form-check mt-4">
                                    <input class="form-check-input" type="radio" name="company"
                                        value="{{ $company->uuid }}" id="formCheck{{ $company->id }}"
                                        {{ in_array($company->uuid, explode(',', request()->input('appFilter.GetCompany'))) ? 'checked' : '' }}>
                                    <label class="form-check-label" for="formCheck{{ $company->id }}">
                                        {{ $company->name }}
                                    </label>
                                </div>
                            @endforeach

                        </div>
                    </div>
                    <div class="col-xl col-sm-4">
                        <div class="mt-4">

                            <div class="form-check mt-4">
                                <input class="form-check-input" type="checkbox" name="status" value="payee"
                                    id="checkbox1"
                                    {{ in_array('payee', explode(',', request()->input('appFilter.GetStatus'))) ? 'checked' : '' }}>

                                <label class="form-check-label" for="checkbox1">
                                    Payée
                                </label>
                            </div>
                            <div class="form-check mt-4">
                                <input class="form-check-input" type="checkbox" name="status" value="impayee"
                                    id="checkbox2"
                                    {{ in_array('impayee', explode(',', request()->input('appFilter.GetStatus'))) ? 'checked' : '' }}>
                                <label class="form-check-label" for="checkbox2">
                                    Impayée
                                </label>
                            </div>
                        </div>
                    </div>

                    <div class="col-xl col-sm-4 align-self-end">
                        <div class="mt-4">
                            <button type="button" class="btn btn-primary w-md" id="filter">Filter</button>
                        </div>
                    </div>
                </div>

            </div>
        </div>
    </div>
    <div class="col-12" id="tickets_data">
        <div class="col-8">
            <div class="text-sm-end">
                <a href="{{ route('commercial:invoices.create') }}" type="button"
                    class="btn btn-success btn-rounded waves-effect waves-light mb-2 me-2">
                    <i class="mdi mdi-plus me-1"></i> créer une nouveau Facture
                </a>
            </div>
        </div>

    </div>
    <div class="card">
        <div class="card-body">


            <div class="table-responsive">
                <table class="table align-middle table-nowrap table-check">
                    <thead class="table-light">
                        <tr>
                            <th style="width: 20px;" class="align-middle">
                                <div class="form-check font-size-16">
                                    <input class="form-check-input" type="checkbox" id="checkAll">
                                    <label class="form-check-label" for="checkAll"></label>
                                </div>
                            </th>
                            <th class="align-middle">Numéro</th>
                            <th class="align-middle">Client</th>
                            <th class="align-middle">Date de facture</th>
                            <th class="align-middle">Montant HT</th>
                            <th class="align-middle">Montant TOTAL</th>
                            <th class="align-middle">Montant TVA</th>
                            <th class="align-middle">Date d'échéance</th>
                            <th class="align-middle">Société</th>
                            <th class="align-middle">Détails</th>
                            <th class="align-middle">Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($invoices as $invoice)
                            <tr>
                                <td>
                                    <div class="form-check font-size-16">
                                        <input class="form-check-input" type="checkbox" id="orderidcheck01">
                                        <label class="form-check-label" for="orderidcheck01"></label>
                                    </div>
                                </td>
                                <td><a href="{{ $invoice->url }}"
                                        class="text-body fw-bold">{{ $invoice->full_number }}</a> </td>
                                <td> {{ $invoice->client->entreprise }}</td>
                                <td>
                                    {{ $invoice->date_invoice }}
                                </td>
                                <td>
                                    {{ $invoice->formated_price_ht }} DH
                                </td>
                                <td>
                                    {{ $invoice->formated_price_total }} DH
                                </td>
                                <td>
                                    {{ $invoice->formated_total_tva }} DH
                                </td>
                                <td>
                                    {{ $invoice->date_due }}
                                </td>
                                <td>
                                    <i class="fas fas fa-user me-1"></i> {{ $invoice->company->name ?? '' }}
                                </td>
                                <td>

                                    <a href="{{ $invoice->url }}" type="button"
                                        class="btn btn-primary btn-sm btn-rounded">
                                        Voir les détails
                                    </a>
                                </td>

                                <td>
                                    <div class="d-flex gap-3">

                                        <a href="{{ $invoice->pdf_url }}" target="__blank" class="text-success">
                                            <i class="mdi mdi-file-pdf-outline font-size-18"></i>
                                        </a>

                                        <a href="{{ $invoice->edit_url }}" class="text-success">
                                            <i class="mdi mdi-pencil font-size-18"></i>
                                        </a>
                                        <a href="#" class="text-danger" onclick="
                                                var result = confirm('Are you sure you want to delete this invoice ?');
                                                
                                                if(result){
                                                    event.preventDefault();
                                                    document.getElementById('delete-invoice-{{ $invoice->id }}').submit();
                                                }">
                                            <i class="mdi mdi-delete font-size-18"></i>
                                        </a>
                                    </div>
                                </td>

                            </tr>
                            <form id="delete-invoice-{{ $invoice->id }}" method="post"
                                action="{{ route('commercial:invoices.delete') }}">
                                @csrf
                                @method('DELETE')
                                <input type="hidden" name="invoiceId" value="{{ $invoice->uuid }}">
                            </form>
                        @endforeach
                    </tbody>
                </table>
            </div>

        </div>
        <ul class="pagination pagination-rounded justify-content-center mb-2">
            {{ $invoices->links('vendor.pagination.bootstrap-4') }}
        </ul>
    </div>
</div>
</div>
<!-- end row -->
