<div class="row">
    <div class="col-12">
        <div class="card" >
            <div class="card-body" >
                <form>
                    <div class="row">
                        
                        <div class="col-xl col-sm-6">
                            <div class="form-group mt-3 mb-0">
                                <label>CODE :</label>
                                <input type="text" name="appFilter[unique_code]" class="form-control" placeholder="Select date" >
                            </div>
                        </div>

                        <div class="col-xl col-sm-6">
                            <div class="form-group mt-3 mb-0">
                                <label>Etat</label>
                                <select name="appFilter[etat]" class="form-control select2-search-disable">
                                    <option value="" selected>select</option>
                                    <option value="reparable" >réparable</option>
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
                                    <option value="BU" >Buy</option>
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
            <a href="{{route('commercial:invoices.create')}}" type="button" class="btn btn-success btn-rounded waves-effect waves-light mb-2 me-2">
                <i class="mdi mdi-plus me-1"></i> créer une nouveau Facture
            </a>
        </div>
    </div>
 
</div>
    <div class="card" >
        <div class="card-body" >


            <div class="table-responsive" >
                <table class="table align-middle table-nowrap table-check">
                    <thead class="table-light">
                        <tr>
                            <th style="width: 20px;" class="align-middle">
                                <div class="form-check font-size-16">
                                    <input class="form-check-input" type="checkbox" id="checkAll">
                                    <label class="form-check-label" for="checkAll"></label>
                                </div>
                            </th>
                            <th class="align-middle">Code Facture</th>
                            <th class="align-middle">Client</th>
                            <th class="align-middle">Date</th>
                            <th class="align-middle"> Status</th>
                            <th class="align-middle"> Etat</th>
                            <th class="align-middle"> Client</th>
                            <th class="align-middle"> Technicien</th>
                            <th class="align-middle">Détails</th>
                            <th class="align-middle">Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($invoices as $ticket)
                            <tr>
                                <td>
                                    <div class="form-check font-size-16">
                                        <input class="form-check-input" type="checkbox" id="orderidcheck01">
                                        <label class="form-check-label" for="orderidcheck01"></label>
                                    </div>
                                </td>
                                <td><a href="{{$ticket->url}}" class="text-body fw-bold">{{$ticket->unique_code}}</a> </td>
                                <td> {{$ticket->article}}</td>
                                <td>
                                    {{$ticket->full_date}}
                                </td>
                                <td>
                                    <span class="badge badge-pill badge-soft-success font-size-12">{{$ticket->status}}</span>
                                </td>
                                <td>
                                    <span class="badge badge-pill badge-soft-success font-size-12">{{$ticket->etat}}</span>
                                </td>
                                <td>
                                    <i class="fas fas fa-building me-1"></i> {{$ticket->client->entreprise ?? ''}}
                                </td>
                                <td>
                                    <i class="fas fas fa-user me-1"></i> {{$ticket->technicien->full_name ?? ''}}
                                </td>
                                <td>
                                    <!-- Button trigger modal -->
                                    <a href="{{$ticket->url}}" type="button" class="btn btn-primary btn-sm btn-rounded">
                                        Voir les détails
                                    </a>
                                </td>
               
                                <td>
                                    <div class="d-flex gap-3">

                                        <a href="{{$ticket->media_url}}" class="text-success"><i class="mdi mdi-file-image font-size-18"></i></a>

                                        <a href="{{$ticket->edit}}" class="text-success"><i class="mdi mdi-pencil font-size-18"></i></a>
                                        <a 
                                            href="#" 
                                            class="text-danger"
                                            onclick="document.getElementById('delete-ticket-{{$ticket->id}}').submit();"
                                        >
                                            <i class="mdi mdi-delete font-size-18"></i>
                                        </a>
                                    </div>
                                </td>
                
                            </tr>
                            <form id="delete-ticket-{{$ticket->id}}" method="post" action="{{route('admin:tickets.delete')}}">
                                @csrf
                                @method('DELETE')
                                <input type="hidden" name="ticket" value="{{$ticket->id}}">
                            </form>
                        @endforeach
                    </tbody>
                </table>
            </div>
            <ul class="pagination pagination-rounded justify-content-end mb-2">
                {{ $invoices->links('vendor.pagination.bootstrap-4') }}
            </ul>
        </div>
    </div>
</div>
</div>
<!-- end row -->