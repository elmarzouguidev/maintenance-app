<div class="card mb-4">
    <div class="card-body">

        <p class="card-title-desc">Actions disponible :</p>

        <div class="row">

            <div class="col-lg-12">
                <div class="button-items">
                    <a target="_blank" href="{{ route('public.show.estimate',['estimate'=>$estimate->uuid, 'logo' => optional($estimate->company)->logo])}}" class="btn btn-primary waves-effect waves-light w-sm">
                        <i class="mdi mdi-file-pdf d-block font-size-16"></i> Télécharger
                    </a>
                    <button type="button" class="btn btn-light waves-effect waves-light w-sm">
                        <i class="mdi mdi-mail d-block font-size-16"></i> Envoyer
                    </button>
                    <a href="{{ $estimate->create_invoice_url }}" class="btn btn-success waves-effect waves-light w-sm">
                        <i class="mdi mdi-pencil d-block font-size-16"></i>
                        Convertir en facture
                    </a>
                    <button type="button" class="btn btn-danger waves-effect waves-light w-sm"
                            onclick="
                                var result = confirm('Are you sure you want to delete this estimate ?');

                                if(result){
                                event.preventDefault();
                                document.getElementById('delete-estimate-single-{{ $estimate->uuid }}').submit();
                                }"
                    >

                        <i class="mdi mdi-trash-can d-block font-size-16"></i> Supprimer
                    </button>

                    <form id="delete-estimate-single-{{ $estimate->uuid }}" method="post"
                          action="{{ route('commercial:estimates.delete') }}">
                        @csrf
                        @method('DELETE')
                        <input type="hidden" name="estimateId" value="{{ $estimate->uuid }}">
                    </form>
                </div>
            </div>

        </div>

    </div>
</div>
