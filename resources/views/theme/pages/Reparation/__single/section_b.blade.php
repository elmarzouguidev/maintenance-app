<div class="row">
    <div class="col-lg-8">
        <div class="card">
            <div class="card-body">
                @if (session('success'))
                    <div class="alert alert-success">
                        {{ session('success') }}
                    </div>
                @endif
                <h4 class="card-title mb-4">Commencer la réparation</h4>

                <div>
                    <form action="{{route('admin:reparations.store',['slug'=>$ticket->external_id])}}" method="post">
                        @csrf
                        <div class="row mb-4">
                            <textarea name="content" class="form-control"  id="ticketdesc-editor" rows="3">
                                {{optional($ticket->reparationReports)->content}}
                            </textarea>
                        </div>
                        
                        <button class="btn btn-primary mr-auto" type="submit"> Enregistre le rapport</button>
  
                    </form>
                    
                    @if($ticket->status !=='finalizer-reparation')

                        <form method="post" id="completRepear" action="{{route('admin:reparations.complet',['slug'=>$ticket->external_id])}}">
                            @csrf
                            <button class="btn btn-danger"> 
                            Terminé la Reparation
                        </button>
                        </form>

                    @endif

                </div>
            </div>
        </div>
    </div>
</div>