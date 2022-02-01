<div class="row">
    <div class="col-12">
        <div class="card">
            <div class="card-body">
                <table id="datatable-buttons" class="table table-bordered dt-responsive nowrap w-100">
                    <thead>
                        <tr>
                            <th style="width: 20px;" class="align-middle">
                                <div class="form-check font-size-16">
                                    <input class="form-check-input" type="checkbox" id="checkAll">
                                    <label class="form-check-label" for="checkAll"></label>
                                </div>
                            </th>
                            <th>{{__('estimate.table.number')}}</th>
                            <th>{{__('estimate.table.client')}}</th>
                            <th>{{__('estimate.table.date_estimate')}}</th>
                            <th>{{__('estimate.table.total_ht')}}</th>
                            <th>{{__('estimate.table.total_total')}}</th>
                            <th>{{__('estimate.table.total_tva')}}</th>
                            <th>{{__('estimate.table.date_due')}}</th>
                            <th>{{__('estimate.table.company')}}</th>
                            <th class="align-middle">Facture</th>
                            <th colspan="2">{{__('estimate.table.detail')}}</th>
                
                        </tr>
                    </thead>

                    <tbody>

                        @foreach ($estimates as $estimate)
                            <tr>
                                <td>
                                    <div class="form-check font-size-16">
                                        <input class="form-check-input" type="checkbox"
                                            id="orderidcheck-{{ $estimate->id }}">
                                        <label class="form-check-label" for="orderidcheck-{{ $estimate->id }}"></label>
                                    </div>
                                </td>
                                <td><a href="{{ $estimate->url }}"
                                        class="text-body fw-bold">{{ $estimate->full_number }}</a> </td>
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
                                    <i class="fas fas fa-user me-1"></i> {{ $estimate->company->name ?? '' }}
                                </td>
                                
                                <td>

                                    <a href="{{ $estimate->create_invoice_url }}" type="button"
                                        class="btn btn-primary btn-sm btn-rounded">
                                        Créer une facture
                                    </a>
                                </td>
                                <td>

                                    <a href="{{ $estimate->url }}" type="button"
                                        class="btn btn-primary btn-sm btn-rounded">
                                        Voir les détails
                                    </a>
                                </td>

                                <td>
                                    <div class="d-flex gap-3">

                                        <a href="{{ $estimate->pdf_url }}" target="__blank" class="text-success">
                                            <i class="mdi mdi-file-pdf-outline font-size-18"></i>
                                        </a>

                                        <a href="{{ $estimate->edit_url }}" class="text-success">
                                            <i class="mdi mdi-pencil font-size-18"></i>
                                        </a>
                                        <a href="#" class="text-danger" onclick="
                                                var result = confirm('Are you sure you want to delete this invoice ?');
                                                
                                                if(result){
                                                    event.preventDefault();
                                                    document.getElementById('delete-estimate-{{ $estimate->id }}').submit();
                                                }">
                                            <i class="mdi mdi-delete font-size-18"></i>
                                        </a>
                                    </div>
                                </td>
                                <form id="delete-estimate-{{ $estimate->id }}" method="post"
                                    action="{{ route('commercial:estimates.delete') }}">
                                    @csrf
                                    @method('DELETE')
                                    <input type="hidden" name="estimateId" value="{{ $estimate->uuid }}">
                                </form>
                            </tr>

                        @endforeach

                    </tbody>
                </table>
            </div>
        </div>
    </div> <!-- end col -->
</div> <!-- end row -->
