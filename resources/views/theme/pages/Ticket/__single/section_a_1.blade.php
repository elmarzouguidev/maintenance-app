<div class="row">
    <div class="col-lg-8">
        <div class="card">
            <div class="card-body">
                <div class="d-flex">
                    <div class="flex-grow-1 overflow-hidden">
                        <h5 class="text-truncate font-size-15">{{$ticket->article}}</h5>
                       
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
    <div class="col-lg-4">
        <div class="card">
            <div class="card-body">
                <h4 class="card-title mb-4">Technicien :</h4>

                <div class="d-flex mb-4">
                    <div class="flex-shrink-0 me-3">
                        <img class="d-flex-object rounded-circle avatar-xs" alt="" src="{{asset('assets/images/users/avatar-2.jpg')}}">
                    </div>
                    <div class="flex-grow-1">
                        <h5 class="font-size-13 mb-1">David Lambert</h5>
                        <p class="text-muted mb-1">
                            Separate existence is a myth.
                        </p>
                    </div>
                    <div class="ms-3">
                        <a href="javascript: void(0);" class="text-primary">profile</a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
