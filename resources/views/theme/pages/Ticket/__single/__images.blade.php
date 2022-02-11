<div class="product-detai-imgs">
    <div class="row">
        <div class="col-md-2 col-sm-3 col-4">
            <div class="nav flex-column nav-pills " id="v-pills-tab" role="tablist" aria-orientation="vertical">
          
                @foreach ($ticket->getMedia('tickets-images') as $image )
               
                    <a class="nav-link" id="product-{{$loop->index+2}}-tab" data-bs-toggle="pill" href="#product-{{$loop->index+2}}" role="tab" aria-controls="product-{{$loop->index+2}}" aria-selected="true">
                        <img src="{{$image->getFullUrl('thumb')}}" alt="" class="img-fluid mx-auto d-block rounded">
                        
                    </a>
                @endforeach

            </div>
        </div>
        <div class="col-md-6 offset-md-1 col-sm-6 col-6">
            <div class="tab-content" id="v-pills-tabContent">
                <div class="tab-pane fade show active" id="product-1" role="tabpanel" aria-labelledby="product-1-tab">
                    <div>
                        <img src="{{$ticket->getFirstMediaUrl('tickets-images')}}" alt="" class="img-fluid mx-auto d-block">
                    </div>
                </div>
                @foreach ($ticket->getMedia('tickets-images') as $image )
                    <div class="tab-pane fade show" id="product-{{$loop->index+2}}" role="tabpanel" aria-labelledby="product-{{$loop->index+2}}-tab">
                        <div>
                            <img src="{{$image->getFullUrl()}}" alt="" class="img-fluid mx-auto d-block">
                        </div>
                    </div>
                @endforeach
                

            </div>

        </div>
    </div>
</div>