<div class="row">
    <div class="col-lg-12">
        <div class="">
            <div class="table-responsive">
                <table class="table project-list-table table-nowrap align-middle table-borderless">
                    <thead>
                        <tr>
                            <th scope="col" style="width: 100px">#</th>
                            <th scope="col">Tickets</th>
                            <th scope="col">Due Date</th>
                            <th scope="col">Status</th>
                            <th scope="col">Team</th>
                            <th scope="col">Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($tickets as $ticket)
                            <tr>
                                <td><img src="{{$ticket->image}}" alt="{{$ticket->product}}" class="avatar-xl"></td>
                                <td>

                                    <h5 class="text-truncate font-size-14">
                                        <a href="{{$ticket->url}}" class="text-dark">
                                            {{$ticket->product}}
                                        </a>
                                    </h5>

                                    <p class="text-muted mb-0">
                                       Lorem ipsum dolor sit amet consectetur adipisicing elit. Unde, nam.
                                    </p>
                                </td>
                                <td>{{$ticket->created_at}}</td>
                                <td><span class="badge bg-success">Completed</span></td>
                                <td>
                                    <div class="avatar-group">
                                        <div class="avatar-group-item">
                                            <a href="javascript: void(0);" class="d-inline-block">
                                                <img src="{{asset('assets/images/users/avatar-5.jpg')}}" alt="" class="rounded-circle avatar-xs">
                                            </a>
                                        </div>
                                        <div class="avatar-group-item">
                                            <a href="javascript: void(0);" class="d-inline-block">
                                                <div class="avatar-xs">
                                                    <span class="avatar-title rounded-circle bg-warning text-white font-size-16">
                                                        R
                                                    </span>
                                                </div>
                                            </a>
                                        </div>
                                        <div class="avatar-group-item">
                                            <a href="javascript: void(0);" class="d-inline-block">
                                                <img src="{{asset('assets/images/users/avatar-2.jpg')}}" alt="" class="rounded-circle avatar-xs">
                                            </a>
                                        </div>
                                    </div>
                                </td>
                                <td>
                                    <div class="dropdown">
                                        <a href="#" class="dropdown-toggle card-drop" data-bs-toggle="dropdown" aria-expanded="false">
                                            <i class="mdi mdi-dots-horizontal font-size-18"></i>
                                        </a>
                                        <div class="dropdown-menu dropdown-menu-end">
                                            <a class="dropdown-item" href="{{$ticket->edit}}">Edit</a>
                                            <a class="dropdown-item" href="#">Another action</a>
                                            <a class="dropdown-item" href="#">Something else here</a>
                                        </div>
                                    </div>
                                </td>
                            </tr>
                        @endforeach
  
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
<!-- end row -->