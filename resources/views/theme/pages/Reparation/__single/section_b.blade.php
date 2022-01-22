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
                    <form action="{{route('admin:reparations.store',['slug'=>$ticket->uuid])}}" method="post">

                        @csrf
                        <div class="row mb-4">
                            <textarea name="content" class="form-control"  id="ticketdesc-editor" rows="3">
                                {{optional($ticket->reparationReports)->content ?? old('content')}}
                            </textarea>
                        </div>
                        <input  type="hidden" name="etat" value="{{$ticket->etat}}">
                        <input id="reparation-end" type="hidden" name="reparation_done" value="no">
                        <button class="btn btn-primary mr-auto" type="submit"> Enregistre le rapport</button>

                        <button 
                            class="btn btn-danger"
                            type="submit"
                            onclick="document.getElementById('reparation-end').value='reparation_done';"
                        > 
                          Enregistre et Terminé la Reparation
                        </button>
                    </form>

                </div>
            </div>
        </div>
    </div>
</div>