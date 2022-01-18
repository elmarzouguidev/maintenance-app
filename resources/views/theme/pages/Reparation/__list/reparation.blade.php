<div class="row">

    <div class="col-xl-4">

      
        <h4 class="text-truncate text-center font-size-15" >Produits a réparer</h4>
        @if(Arr::exists($reports,'confirme'))     
            @foreach ($reports['confirme'] as $report)
                <div class="col-xl-12 col-sm-6">
                    <div class="card">
                        <div class="card-body">
                            <div class="d-flex">
                                <div class="flex-shrink-0 me-4">
                                    <div class="avatar-md">
                                        <span class="avatar-title rounded-circle bg-light text-danger font-size-16">
                                            <img src="{{$report->getTicket->getFirstMediaUrl('tickets-images','thumb')}}" alt="" height="30">
                                        </span>
                                    </div>
                                </div>
                                

                                <div class="flex-grow-1 overflow-hidden">
                                    <h5 class="text-truncate font-size-15"><a href="{{$report->single_url}}" class="text-dark">{{$report->ticket}}</a></h5>
                                    <p class="text-muted mb-4">It will be as simple as Occidental</p>
                                
                                </div>
                            </div>
                        </div>
                        <div class="px-4 py-3 border-top">
                            <ul class="list-inline mb-0">
                                <li class="list-inline-item me-3">
                                    <span class="badge bg-success">{{$report->status}}</span>
                                </li>
                                <li class="list-inline-item me-3">
                                    <i class= "bx bx-calendar me-1"></i> {{$report->ouvert_at}}
                                </li>
                                <li class="list-inline-item me-3">
                                    <i class= "bx bx-comment-dots me-1"></i> 214
                                </li>
                            </ul>
                        </div>
                    </div>
                </div>
            @endforeach
        @endif
  
    </div>
    <div class="col-xl-4">
        <h5 class="text-truncate text-center font-size-15" >Produits en cours de réparation</h5>
        @if(Arr::exists($reports,'encours-reparation')) 
            @foreach ($reports['encours-reparation'] as $report)
                <div class="col-xl-12 col-sm-6">
                    <div class="card">
                        <div class="card-body">
                            <div class="d-flex">
                                <div class="flex-shrink-0 me-4">
                                    <div class="avatar-md">
                                        <span class="avatar-title rounded-circle bg-light text-danger font-size-16">
                                            <img src="{{$report->getTicket->getFirstMediaUrl('tickets-images','thumb')}}" alt="" height="30">
                                        </span>
                                    </div>
                                </div>
                                

                                <div class="flex-grow-1 overflow-hidden">
                                    <h5 class="text-truncate font-size-15"><a href="{{$report->single_url}}" class="text-dark">{{$report->ticket}}</a></h5>
                                    <p class="text-muted mb-4">It will be as simple as Occidental</p>
                            
                                </div>
                            </div>
                        </div>
                        <div class="px-4 py-3 border-top">
                            <ul class="list-inline mb-0">
                                <li class="list-inline-item me-3">
                                    <span class="badge bg-warning">{{$report->status}}</span>
                                </li>
                                <li class="list-inline-item me-3">
                                    <i class= "bx bx-calendar me-1"></i> {{$report->envoyer_at}}
                                </li>
                                <li class="list-inline-item me-3">
                                    <i class= "bx bx-comment-dots me-1"></i> 214
                                </li>
                            </ul>
                        </div>
                    </div>
                </div>
            @endforeach
        @endif

    </div>
   
</div>