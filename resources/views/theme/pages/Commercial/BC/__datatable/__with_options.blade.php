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
                            <th>Numéro</th>
                            <th>Founisseur</th>
                            <th>date de BON</th>
                            <th>Montant HT</th>
                            <th>Montant TOTAL</th>
                            <th>Montant TVA</th>
                            <th>Date d'échéance</th>
                            <th>Société</th>
                            <th>Détails</th>
                            <th>Action</th>
                        </tr>
                    </thead>

                    <tbody>

                        @foreach ($commandes as $document)
                            <tr>
                                <td>
                                    <div class="form-check font-size-16">
                                        <input class="form-check-input" type="checkbox"
                                            id="orderidcheck-{{ $document->id }}">
                                        <label class="form-check-label" for="orderidcheck-{{ $document->id }}"></label>
                                    </div>
                                </td>
                                <td>
                                    <a href="{{ $document->url }}" class="text-body fw-bold">
                                       {{ $document->b_code }}
                                    </a> 
                                </td>
                                <td> {{ $document->provider->entreprise }}</td>
                                <td>
                                    {{ $document->date_command }}
                                </td>
                                <td>
                                    {{ $document->formated_price_ht }}
                                </td>
                                <td>
                                    {{ $document->formated_price_total }}
                                </td>
                                <td>
                                    {{ $document->formated_total_tva }}
                                </td>
                                <td>
                                    {{ $document->date_due }}
                                </td>
                                <td>
                                    {{ $document->company->name }}
                                </td>
 
                                <td>
                                    <a href="{{ $document->url }}" type="button"
                                        class="btn btn-primary btn-sm btn-rounded">
                                        Voir les détails
                                    </a>
                                </td>
                                <td>
                                    <div class="d-flex gap-3">

                                        <a href="{{ $document->edit_url }}" class="text-success">
                                            <i class="mdi mdi-pencil font-size-18"></i>
                                        </a>
                                        <a href="#" class="text-danger" onclick="
                                                var result = confirm('Are you sure you want to delete this invoice ?');
                                                
                                                if(result){
                                                    event.preventDefault();
                                                    document.getElementById('delete-command-{{ $document->uuid }}').submit();
                                                }">
                                            <i class="mdi mdi-delete font-size-18"></i>
                                        </a>
                                    </div>
                                </td>
                                <form id="delete-command-{{ $document->uuid }}" method="post"
                                    action="{{ route('commercial:bcommandes.delete') }}">
                                    @csrf
                                    @method('DELETE')
                                    <input type="hidden" name="commandId" value="{{ $document->uuid }}">
                                </form>
                            </tr>

                        @endforeach

                    </tbody>
                </table>
            </div>
        </div>
    </div> <!-- end col -->
</div> <!-- end row -->
