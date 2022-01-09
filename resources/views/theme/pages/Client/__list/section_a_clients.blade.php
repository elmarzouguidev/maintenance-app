<div class="row">
    <div class="col-lg-12">
        <div class="card">
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table align-middle table-nowrap table-hover">
                        <thead class="table-light">
                            <tr>
                                <th scope="col" style="width: 70px;">#</th>
                                <th scope="col">entreprise</th>
                                <th scope="col">telephone</th>
                                <th scope="col">email</th>
                                <th scope="col">addresse</th>
                                <th scope="col">RC</th>
                                <th scope="col">ICE</th>
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
                            
                                    <td>
                                        <h5 class="font-size-14 mb-1"><a href="javascript: void(0);" class="text-dark">{{$client->entreprise}}</a></h5>
                                        <p class="text-muted mb-0">{{$client->contact}}</p>
                                    </td>
                                    <td>{{$client->telephone}}</td>
                                    <td>{{$client->email}}</td>
                                    <td>{{$client->addresse}}</td>
                                    <td>{{$client->rc}}</td>
                                    <td>{{$client->ice}}</td>
                                   
                                    <td>
                                        125
                                    </td>
                                    <td>
                                        <ul class="list-inline font-size-20 contact-links mb-0">
                                            <li class="list-inline-item px-2">
                                                <a href="javascript: void(0);" title="Message"><i class="bx bx-message-square-dots"></i></a>
                                            </li>
                                            <li class="list-inline-item px-2">
                                                <a href="javascript: void(0);" title="Profile"><i class="bx bx-user-circle"></i></a>
                                            </li>
                                        </ul>
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