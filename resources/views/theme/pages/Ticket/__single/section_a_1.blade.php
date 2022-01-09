<div class="row">
    <div class="col-lg-8">
        <div class="card">
            <div class="card-body">
                <div class="d-flex">
                    <div class="flex-grow-1 overflow-hidden">
                        <h5 class="text-truncate font-size-15">{{$ticket->product}}</h5>
                       
                    </div>
                </div>

                @include('theme.pages.Ticket.__single.__images')

                <h5 class="font-size-15 mt-4">Ticket Details :</h5>

                <div class="mt-4">
                    {!! $ticket->description !!}
                </div>
                
                <div class="row task-dates">
                    <div class="col-sm-4 col-6">
                        <div class="mt-4">
                            <h5 class="font-size-14"><i class="bx bx-calendar me-1 text-primary"></i>Date de Création</h5>
                            <p class="text-muted mb-0">{{$ticket->full_date}}</p>
                        </div>
                    </div>

                    <div class="col-sm-4 col-6">
                        <div class="mt-4">
                            <h5 class="font-size-14"><i class="bx bx-calendar-check me-1 text-primary"></i> Date de sortie</h5>
                            <p class="text-muted mb-0">{{$ticket->full_date}}</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- end col -->

    <div class="col-lg-4">
        <div class="card">
            <div class="card-body">
                <h4 class="card-title mb-4">Techniciens</h4>

                <div class="table-responsive">
                    <table class="table align-middle table-nowrap">
                        <tbody>
                            <tr>
                                <td style="width: 50px;"><img src="{{asset('assets/images/users/avatar-2.jpg')}}" class="rounded-circle avatar-xs" alt=""></td>
                                <td><h5 class="font-size-14 m-0"><a href="javascript: void(0);" class="text-dark">Daniel Canales</a></h5></td>
                                <td>
                                    <div>
                                        <a href="javascript: void(0);" class="badge bg-primary bg-soft text-primary font-size-11">Frontend</a>
                                        <a href="javascript: void(0);" class="badge bg-primary bg-soft text-primary font-size-11">UI</a>
                                    </div>
                                </td>
                            </tr>
                            <tr>
                                <td><img src="{{asset('assets/images/users/avatar-1.jpg')}}" class="rounded-circle avatar-xs" alt=""></td>
                                <td><h5 class="font-size-14 m-0"><a href="javascript: void(0);" class="text-dark">Jennifer Walker</a></h5></td>
                                <td>
                                    <div>
                                        <a href="javascript: void(0);" class="badge bg-primary bg-soft text-primary font-size-11">UI / UX</a>
                                    </div>
                                </td>
                            </tr>
                            <tr>
                                <td>
                                    <div class="avatar-xs">
                                        <span class="avatar-title rounded-circle bg-primary text-white font-size-16">
                                            C
                                        </span>
                                    </div>
                                </td>
                                <td><h5 class="font-size-14 m-0"><a href="javascript: void(0);" class="text-dark">Carl Mackay</a></h5></td>
                                <td>
                                    <div>
                                        <a href="javascript: void(0);" class="badge bg-primary bg-soft text-primary font-size-11">Backend</a>
                                    </div>
                                </td>
                            </tr>
                            <tr>
                                <td><img src="{{asset('assets/images/users/avatar-4.jpg')}}" class="rounded-circle avatar-xs" alt=""></td>
                                <td><h5 class="font-size-14 m-0"><a href="javascript: void(0);" class="text-dark">Janice Cole</a></h5></td>
                                <td>
                                    <div>
                                        <a href="javascript: void(0);" class="badge bg-primary bg-soft text-primary font-size-11">Frontend</a>
                                        <a href="javascript: void(0);" class="badge bg-primary bg-soft text-primary font-size-11">UI</a>
                                    </div>
                                </td>
                            </tr>
                            <tr>
                                <td>
                                    <div class="avatar-xs">
                                        <span class="avatar-title rounded-circle bg-primary text-white font-size-16">
                                            T
                                        </span>
                                    </div>
                                </td>
                                <td><h5 class="font-size-14 m-0"><a href="javascript: void(0);" class="text-dark">Tony Brafford</a></h5></td>
                                <td>
                                    <div>
                                        <a href="javascript: void(0);" class="badge bg-primary bg-soft text-primary font-size-11">Backend</a>
                                    </div>
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
    <!-- end col -->
</div>
<!-- end row -->