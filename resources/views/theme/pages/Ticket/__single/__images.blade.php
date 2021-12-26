<div class="product-detai-imgs">
    <div class="row">
        <div class="col-md-2 col-sm-3 col-4">
            <div class="nav flex-column nav-pills " id="v-pills-tab" role="tablist" aria-orientation="vertical">
                <a class="nav-link active" id="product-1-tab" data-bs-toggle="pill" href="#product-1" role="tab" aria-controls="product-1" aria-selected="true">
                    <img src="{{$ticket->image}}" alt="" class="img-fluid mx-auto d-block rounded">
                </a>
                @foreach ($ticket->all_images as $image )
                    <a class="nav-link" id="product-{{$loop->index+2}}-tab" data-bs-toggle="pill" href="#product-{{$loop->index+2}}" role="tab" aria-controls="product-{{$loop->index+2}}" aria-selected="true">
                        <img src="{{$image}}" alt="" class="img-fluid mx-auto d-block rounded">
                    </a>
                @endforeach

            </div>
        </div>
        <div class="col-md-7 offset-md-1 col-sm-9 col-8">
            <div class="tab-content" id="v-pills-tabContent">
                <div class="tab-pane fade show active" id="product-1" role="tabpanel" aria-labelledby="product-1-tab">
                    <div>
                        <img src="{{$ticket->image}}" alt="" class="img-fluid mx-auto d-block">
                    </div>
                </div>
                @foreach ($ticket->all_images as $image )
                    <div class="tab-pane fade show" id="product-{{$loop->index+2}}" role="tabpanel" aria-labelledby="product-{{$loop->index+2}}-tab">
                        <div>
                            <img src="{{$image}}" alt="" class="img-fluid mx-auto d-block">
                        </div>
                    </div>
                @endforeach
                

            </div>
            <div class="text-left">
                <button type="button" class="btn btn-primary waves-effect waves-light mt-2 me-1">
                    <i class="bx bx-cart me-2"></i> Créer un devis
                </button>
                {{--<button type="button" class="btn btn-success waves-effect  mt-2 waves-light">
                    <i class="bx bx-shopping-bag me-2"></i>Buy now
                </button>--}}
            </div>
            
        </div>
    </div>
</div>