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
            <a href="{{route('admin:tickets.create')}}" type="button" class="btn btn-success btn-rounded waves-effect waves-light mb-2 me-2">
                <i class="mdi mdi-plus me-1"></i> créer un nouveau ticket
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
                            <th class="align-middle">ID</th>
                            <th class="align-middle">Nom</th>
                            {{--<th class="align-middle">Website</th>--}}
                            {{--<th class="align-middle">Description</th>--}}
                            <th class="align-middle">Ville</th>
                            <th class="align-middle">Addresse</th>
                            <th class="align-middle">Telephone</th>
                            <th class="align-middle">email</th>
                            <th class="align-middle">RC</th>
                            <th class="align-middle">ICE</th>
                            <th class="align-middle">CNSS</th>
                            <th class="align-middle">Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($companies as $company)
                            <tr>
                                <td>
                                    <div class="form-check font-size-16">
                                        <input class="form-check-input" type="checkbox" id="orderidcheck01">
                                        <label class="form-check-label" for="orderidcheck01"></label>
                                    </div>
                                </td>
                                <td><a href="{{$company->url}}" class="text-body fw-bold">{{$company->id}}</a> </td>
                                <td> {{$company->name}}</td>
                                {{--<td>
                                    {{$company->website}}
                                </td>--}}
                                {{--<td>
                                    {{$company->description}}
                                </td>--}}
                                <td>
                                    {{$company->city}}
                                </td>
                                <td>
                                   {{$company->addresse}}
                                </td>
                                <td>
                                     {{$company->telephone}}
                                </td>
                                <td>
                                     {{$company->email}}
                                </td>
                                <td>
                                    {{$company->rc}}
                                </td>
                                <td>
                                    {{$company->ice}}
                                </td>
                                <td>
                                    {{$company->cnss}}
                                </td>
               
                                <td>
                                    <div class="d-flex gap-3">

                                        <a href="{{$company->edit}}" class="text-success"><i class="mdi mdi-pencil font-size-18"></i></a>
                                        <a 
                                            href="#" 
                                            class="text-danger"
                                            onclick="document.getElementById('delete-company-{{$company->id}}').submit();"
                                        >
                                            <i class="mdi mdi-delete font-size-18"></i>
                                        </a>
                                    </div>
                                </td>
                
                            </tr>
                            <form id="delete-company-{{$company->id}}" method="post" action="{{route('admin:tickets.delete')}}">
                                @csrf
                                @method('DELETE')
                                <input type="hidden" name="company" value="{{$company->id}}">
                            </form>
                        @endforeach
                    </tbody>
                </table>
            </div>
            <ul class="pagination pagination-rounded justify-content-end mb-2">
                {{ $companies->links('vendor.pagination.bootstrap-4') }}
            </ul>
        </div>
    </div>
</div>
</div>
<!-- end row -->