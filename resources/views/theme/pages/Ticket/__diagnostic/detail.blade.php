<div class="row">
    <div class="col-xl-4">
        <div class="card overflow-hidden">
            <div class="bg-primary bg-soft">
                <div class="row">
                    <div class="col-7">
                        <div class="text-primary p-3">
                            <h5 class="text-primary">{{$ticket->product}}</h5>
                      
                        </div>
                    </div>
                    <div class="col-5 align-self-end">
                        <img src="{{asset('assets/images/profile-img.png')}}" alt="" class="img-fluid">
                    </div>
                </div>
            </div>
            <div class="card-body pt-0">
                <div class="row">
                    <div class="col-sm-4">
                        <div class="avatar-md profile-user-wid mb-4">
                            <img src="{{asset('assets/images/profile-img.png')}}" alt="" class="img-thumbnail rounded-circle">
                        </div>
                        <h5 class="font-size-15 text-truncate">{{auth()->user()->full_name}}</h5>
                        <p class="text-muted mb-0 text-truncate">Technicien</p>
                    </div>

                    <div class="col-sm-8">
                        <div class="pt-4">
                           
                            <div class="row">
                                <div class="col-6">
                                    <h5 class="font-size-15">8</h5>
                                    <p class="text-muted mb-0">Tickets</p>
                                </div>
                                <div class="col-6">
                                    <h5 class="font-size-15">$1245</h5>
                                    <p class="text-muted mb-0">Revenue</p>
                                </div>
                            </div>
                            {{--<div class="mt-4">
                                <a href="javascript: void(0);" class="btn btn-primary waves-effect waves-light btn-sm">View Profile <i class="mdi mdi-arrow-right ms-1"></i></a>
                            </div>--}}
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <!-- end card -->

        <div class="card">
            <div class="card-body">
                <h4 class="card-title mb-4">Mes diagnostiques </h4>
                <div class="table-responsive">
                    <table class="table table-nowrap table-hover mb-0">
                        <thead>
                            <tr>
                                <th scope="col">Ticket ID</th>
                                <th scope="col">Produit</th>
                                <th scope="col">Date</th>
                                <th scope="col">Etat</th>
                               
                            </tr>
                        </thead>
                        <tbody>
                           @forelse (auth()->user()->tickets as $ticket )
                                <tr>
                                    <th scope="row"><a href="{{$ticket->diagnose_url}}">{{$ticket->unique_code}}</a></th>
                                    <td>{{$ticket->product}}</td>
                                    <td>{{$ticket->full_date}}</td>
                                    <td>{{$ticket->etat}}</td>
                               
                                </tr>
                            @empty
                  
                             <tr>
                                <th scope="row">No Tickets pour le mement</th>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
        <!-- end card -->

        <div class="card">
            <div class="card-body">
                <h4 class="card-title mb-5">Experience</h4>
                <div class="">
                    <ul class="verti-timeline list-unstyled">
                        <li class="event-list active">
                            <div class="event-timeline-dot">
                                <i class="bx bx-right-arrow-circle bx-fade-right"></i>
                            </div>
                            <div class="d-flex">
                                <div class="flex-shrink-0 me-3">
                                    <i class="bx bx-server h4 text-primary"></i>
                                </div>
                                <div class="flex-grow-1">
                                    <div>
                                        <h5 class="font-size-15"><a href="javascript: void(0);" class="text-dark">Back end Developer</a></h5>
                                        <span class="text-primary">2016 - 19</span>
                                    </div>
                                </div>
                            </div>
                        </li>
                        <li class="event-list">
                            <div class="event-timeline-dot">
                                <i class="bx bx-right-arrow-circle"></i>
                            </div>
                            <div class="d-flex">
                                <div class="flex-shrink-0 me-3">
                                    <i class="bx bx-code h4 text-primary"></i>
                                </div>
                                <div class="flex-grow-1">
                                    <div>
                                        <h5 class="font-size-15"><a href="javascript: void(0);" class="text-dark">Front end Developer</a></h5>
                                        <span class="text-primary">2013 - 16</span>
                                    </div>
                                </div>
                            </div>
                        </li>
                        <li class="event-list">
                            <div class="event-timeline-dot">
                                <i class="bx bx-right-arrow-circle"></i>
                            </div>
                            <div class="d-flex">
                                <div class="flex-shrink-0 me-3">
                                    <i class="bx bx-edit h4 text-primary"></i>
                                </div>
                                <div class="flex-grow-1">
                                    <div>
                                        <h5 class="font-size-15"><a href="javascript: void(0);" class="text-dark">UI /UX Designer</a></h5>
                                        <span class="text-primary">2011 - 13</span>
                                        
                                    </div>
                                </div>
                            </div>
                        </li>
                    </ul>
                </div>

            </div>
        </div>  
        <!-- end card -->
    </div>         
    
    <div class="col-xl-8">

        <div class="row">
            <div class="col-md-4">
                <div class="card mini-stats-wid">
                    <div class="card-body">
                        <div class="d-flex">
                            <div class="flex-grow-1">
                                <p class="text-muted fw-medium mb-2">Completed Tickets</p>
                                <h4 class="mb-0">125</h4>
                            </div>

                            <div class="flex-shrink-0 align-self-center">
                                <div class="mini-stat-icon avatar-sm rounded-circle bg-primary">
                                    <span class="avatar-title">
                                        <i class="bx bx-check-circle font-size-24"></i>
                                    </span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-md-4">
                <div class="card mini-stats-wid">
                    <div class="card-body">
                        <div class="d-flex">
                            <div class="flex-grow-1">
                                <p class="text-muted fw-medium mb-2">Pending Tickets</p>
                                <h4 class="mb-0">12</h4>
                            </div>

                            <div class="flex-shrink-0 align-self-center">
                                <div class="avatar-sm mini-stat-icon rounded-circle bg-primary">
                                    <span class="avatar-title">
                                        <i class="bx bx-hourglass font-size-24"></i>
                                    </span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-md-4">
                <div class="card mini-stats-wid">
                    <div class="card-body">
                        <div class="d-flex">
                            <div class="flex-grow-1">
                                <p class="text-muted fw-medium mb-2">Total Revenue</p>
                                <h4 class="mb-0">$36,524</h4>
                            </div>

                            <div class="flex-shrink-0 align-self-center">
                                <div class="avatar-sm mini-stat-icon rounded-circle bg-primary">
                                    <span class="avatar-title">
                                        <i class="bx bx-package font-size-24"></i>
                                    </span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        {{--<div class="card">
            <div class="card-body">
                <h4 class="card-title mb-4">Revenue</h4>
                <div id="revenue-chart" class="apex-charts" dir="ltr"></div>
            </div>
        </div>--}}

        <div class="card">
            <div class="card-body">
                <h4 class="card-title mb-4">{{$ticket->product}}</h4>
                <div class="row">
                    <div class="col-xl-12">
                        <div class="product-detai-imgs">
                            <div class="row">
                                <div class="col-md-2 col-sm-3 col-4">
                                    <div class="nav flex-column nav-pills " id="v-pills-tab" role="tablist" aria-orientation="vertical">

                                        @foreach ($ticket->getMedia('tickets-images') as $image )
                                            <a class="nav-link {{$loop->first ? 'active' :''}}" id="product-{{$loop->index+2}}-tab" data-bs-toggle="pill" href="#product-{{$loop->index+2}}" role="tab" aria-controls="product-{{$loop->index+2}}" aria-selected="true">
                                                <img src="{{$image->getUrl('thumb')}}" alt="" class="img-fluid mx-auto d-block rounded">
                                                
                                            </a>
                                        @endforeach
                                    </div>
                                </div>
                                <div class="col-md-7 offset-md-1 col-sm-9 col-8">
                                    <div class="tab-content" id="v-pills-tabContent">
    
                                        @foreach ($ticket->getMedia('tickets-images') as $image )
                                            <div class="tab-pane fade show {{$loop->first ? 'active' :''}}" id="product-{{$loop->index+2}}" role="tabpanel" aria-labelledby="product-{{$loop->index+2}}-tab">
                                                <div>
                                                    <img src="{{$image->getUrl()}}" alt="" class="img-fluid mx-auto d-block">
                                                </div>
                                            </div>
                                        @endforeach

                                    </div>
                                    <div class="text-center">
                                        <button type="button" class="btn btn-primary waves-effect waves-light mt-2 me-1">
                                            <i class="bx bx-cart me-2"></i> Réparable
                                        </button>
                                        <button type="button" class="btn btn-success waves-effect  mt-2 waves-light">
                                            <i class="bx bx-shopping-bag me-2"></i>Non Réparable
                                        </button>
                                    </div>
                                    
                                </div>
                            </div>
                        </div>
                    </div>
                    
                </div>
            </div>
        </div>
    </div>
</div>
<!-- end row -->