<div class="row">
    <div class="col-12">
        <div class="card">
            <div class="card-body">
                <form>
                    <div class="row">

                        <div class="col-xl col-sm-6">
                            <div class="form-group mt-3 mb-0">
                                <label>CODE :</label>
                                <input type="text" name="appFilter[unique_code]" class="form-control"
                                    placeholder="Select date">
                            </div>
                        </div>

                        <div class="col-xl col-sm-6">
                            <div class="form-group mt-3 mb-0">
                                <label>Etat</label>
                                <select name="appFilter[etat]" class="form-control select2-search-disable">
                                    <option value="" selected>select</option>
                                    <option value="reparable">réparable</option>
                                    <option value="non-reparable">non réparable</option>
                                    <option value="non-traite">non traité</option>
                                </select>
                            </div>
                        </div>

                        <div class="col-xl col-sm-6">
                            <div class="form-group mt-3 mb-0">
                                <label>Status</label>
                                <select name="appFilter[status]" class="form-control select2-search-disable">
                                    <option value="" selected>seletc</option>
                                    <option value="BU">Buy</option>
                                    <option value="SE">Sell</option>
                                </select>
                            </div>
                        </div>

                        <div class="col-xl col-sm-6">
                            <div class="form-group mt-3 mb-0">
                                <label>Status</label>
                                <select class="form-control select2-search-disable">
                                    <option value="CO" selected>Completed</option>
                                    <option value="PE">Pending</option>
                                </select>
                            </div>
                        </div>

                        <div class="col-xl col-sm-6 align-self-end">
                            <div class="mt-3">
                                <button type="submit" class="btn btn-primary w-md">Filter</button>
                            </div>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
    <div class="col-12" id="tickets_data">
        <div class="col-8">
            <div class="text-sm-end">
                <a href="{{ route('commercial:estimates.create') }}" type="button"
                    class="btn btn-success btn-rounded waves-effect waves-light mb-2 me-2">
                    <i class="mdi mdi-plus me-1"></i> créer une nouveau Devis
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
                            <th class="align-middle">Société</th>
                            <th class="align-middle">Numéro</th>
                            <th class="align-middle">Client</th>
                            <th class="align-middle">Date de devis</th>
                            <th class="align-middle">Montant HT</th>
                            <th class="align-middle">Montant TOTAL</th>
                            <th class="align-middle">Montant TVA</th>
                            <th class="align-middle">Date d'échéance</th>
                            <th class="align-middle">Status</th>
                            <th class="align-middle">Facture</th>
                            <th class="align-middle">Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($estimates as $estimate)
                            <tr>
                                <td>
                                    <div class="form-check font-size-16">
                                        <input class="form-check-input" type="checkbox" id="orderidcheck01">
                                        <label class="form-check-label" for="orderidcheck01"></label>
                                    </div>
                                </td>
                                <td>
                                    <i class="fas fas fa-user me-1"></i> {{ $estimate->company->name ?? '' }}
                                </td>
                                <td>
                                    <a href="{{ $estimate->url }}" class="text-body fw-bold">
                                        {{ $estimate->estimate_code }}
                                    </a>
                                </td>

                                <td> {{ $estimate->client->entreprise }}</td>
                                <td>
                                    {{ $estimate->estimate_date }}
                                </td>
                                <td>
                                    {{ $estimate->formated_price_ht }} DH
                                </td>
                                <td>
                                    {{ $estimate->formated_price_total }} DH
                                </td>
                                <td>
                                    {{ $estimate->formated_total_tva }} DH
                                </td>
                                <td>
                                    {{ $estimate->date_due }}
                                </td>

                                <td>
                                    {{ $estimate->status }}
                                </td>

                                <td>

                                    <a href="{{ $estimate->create_invoice_url }}" type="button"
                                        class="btn btn-primary btn-sm btn-rounded">
                                        Créer une facture
                                    </a>
                                </td>

                                <td>
                                    <div class="d-flex gap-3">

                                        <a href="{{ $estimate->edit_url }}" class="text-success">
                                            <i class="mdi mdi-pencil font-size-18"></i>
                                        </a>
                                        <a href="#" class="text-danger" onclick="
                                                var result = confirm('Are you sure you want to delete this estimate ?');
                                                
                                                if(result){
                                                    event.preventDefault();
                                                    document.getElementById('delete-estimate-{{ $estimate->id }}').submit();
                                                }">
                                            <i class="mdi mdi-delete font-size-18"></i>
                                        </a>
                                    </div>
                                </td>

                            </tr>
                            <form id="delete-estimate-{{ $estimate->id }}" method="post"
                                action="{{ route('commercial:estimates.delete') }}">
                                @csrf
                                @method('DELETE')
                                <input type="hidden" name="estimateId" value="{{ $estimate->uuid }}">
                            </form>
                        @endforeach
                    </tbody>
                </table>
            </div>
            <ul class="pagination pagination-rounded justify-content-end mb-2">
                {{ $estimates->links('vendor.pagination.bootstrap-4') }}
            </ul>
        </div>
    </div>
</div>
</div>
<!-- end row -->
