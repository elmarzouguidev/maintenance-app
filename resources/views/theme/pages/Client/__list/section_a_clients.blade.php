<div class="row">
    <div class="col-lg-12">
        <div class="card">
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table align-middle table-nowrap table-hover">
                        <thead class="table-light">
                            <tr>
                                <th scope="col" style="width: 70px;">#</th>
                                <th scope="col">Code Client</th>
                                <th scope="col">Entreprise</th>
                                <th scope="col">Telephone</th>
                                <th scope="col">Email</th>
                                <th scope="col">Addresse</th>
                                <th scope="col">ICE</th>
                                <th scope="col">RC</th>
                                <th scope="col">Tickets</th>
                                <th scope="col">Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($clients as $client)
                                    
                                <tr>
                                    <td>
                                        <div class="avatar-xs">
                                            <span class="avatar-title rounded-circle">
                                                D
                                            </span>
                                        </div>
                                        {{--<div>
                                            <img class="rounded-circle avatar-xs" src="{{asset('assets/images/users/avatar-2.jpg')}}" alt="">
                                        </div>--}}
                                    </td>
                                    <td>{{$client->client_ref}}</td>
                                    <td>
                                        <h5 class="font-size-14 mb-1"><a href="{{$client->url}}" class="text-dark">{{$client->entreprise}}</a></h5>
                                        <p class="text-muted mb-0">{{$client->contact}}</p>
                                    </td>
                                    <td>{{$client->telephone}}</td>
                                    <td>{{$client->email}}</td>
                                    <td>{{$client->addresse}}</td>
                                    <td>{{$client->ice}}</td>
                                    <td>{{$client->rc}}</td>
                
                                    <td>
                                       {{$client->tickets_count}}
                                    </td>
                                    <td>
                                        <div class="d-flex gap-3">
                                            <a href="{{$client->edit}}" class="text-success"><i class="mdi mdi-pencil font-size-18"></i></a>
                                            <a 
                                                href="#" 
                                                class="text-danger"
                                                onclick="document.getElementById('delete-ticket-{{$client->id}}').submit();"
                                            >
                                                <i class="mdi mdi-delete font-size-18"></i>
                                            </a>
                                        </div>
                                     

                                    </td>
                                </tr>

                            @endforeach
                        </tbody>
                    </table>
                </div>
                <div class="row">
                    <div class="col-lg-12">
                        <ul class="pagination pagination-rounded justify-content-center mt-4">
                            <li class="page-item disabled">
                                <a href="javascript: void(0);" class="page-link"><i class="mdi mdi-chevron-left"></i></a>
                            </li>
                            <li class="page-item">
                                <a href="javascript: void(0);" class="page-link">1</a>
                            </li>
                            <li class="page-item active">
                                <a href="javascript: void(0);" class="page-link">2</a>
                            </li>
                            <li class="page-item">
                                <a href="javascript: void(0);" class="page-link">3</a>
                            </li>
                            <li class="page-item">
                                <a href="javascript: void(0);" class="page-link">4</a>
                            </li>
                            <li class="page-item">
                                <a href="javascript: void(0);" class="page-link">5</a>
                            </li>
                            <li class="page-item">
                                <a href="javascript: void(0);" class="page-link"><i class="mdi mdi-chevron-right"></i></a>
                            </li>
                        </ul>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>