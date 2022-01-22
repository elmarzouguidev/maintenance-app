<div class="row">
    <div class="col-xl-8">
        <div class="card">
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table align-middle mb-0 table-nowrap">
                        <thead class="table-light">
                            <tr>
                                <th>Fichier</th>
                                <th>Nom du Fichier</th>
                                <th>Type</th>
                                <th>Collection</th>
                                <th colspan="2">Size</th>
                            </tr>
                        </thead>
                        <tbody>

                            @foreach ($ticket->getMedia('tickets-images') as $image )
                                <tr>
                                    <td>
                                        <img src="{{$image->getFullUrl('thumb')}}" alt="{{$image->name}}"
                                            title="product-img" class="avatar-md" />
                                    </td>
                                    <td>
                                        <h5 class="font-size-14 text-truncate">
                                            <a href="ecommerce-product-detail.html" class="text-dark">{{$image->name}}</a>
                                        </h5>
                                    </td>
                                    <td>
                                        {{$image->mime_type}}
                                    </td>
                                    <td>
                                        <div style="width: 120px;">
                                            {{$image->collection_name}}
                                        </div>
                                    </td>
                                    <td>
                                        {{$image->human_readable_size}}
                                    </td>
                                    <td>
                                        <a 
                                            href="#" 
                                            class="action-icon text-danger"
                                            onclick="document.getElementById('delete-ticket-media-{{$image->id}}').submit();"
                                        >
                                            <i class="mdi mdi-trash-can font-size-18"></i>
                                        </a>
                                    </td>
                                </tr>

                                <form id="delete-ticket-media-{{$image->id}}" method="post" action="{{route('admin:tickets.media.delete',$ticket->uuid)}}">
                                    @csrf
                                    @method('DELETE')
                                    <input type="hidden" name="mediaId" value="{{$image->id}}">
                                </form>
                            @endforeach

                        </tbody>
                    </table>
                </div>
                <div class="row mt-4">
                    <div class="col-sm-6">
                        <a href="{{route('admin:tickets.list')}}" class="btn btn-secondary">
                            <i class="mdi mdi-arrow-left me-1"></i> return </a>
                    </div>
                    {{--<div class="col-sm-6">
                        <div class="text-sm-end mt-2 mt-sm-0">
                            <a href="ecommerce-checkout.html" class="btn btn-success">
                                <i class="mdi mdi-cart-arrow-right me-1"></i> Delete All </a>
                        </div>
                    </div>--}}
                </div> 
            </div>
        </div>
    </div>
    <div class="col-xl-4">
        <div class="card">
            <div class="card-body">
                <h5 class="card-title mb-4">{{$ticket->article}}</h5>
                
            </div>
         </div>
         <div class="card">
            <div class="card-body">
                <h4 class="card-title mb-3">Détails</h4>

                <div class="table-responsive">
                    <table class="table mb-0">
                        <tbody>
                            <tr>
                                <th>Total :</th>
                                <th>{{$ticket->media_count}}</th>
                            </tr>
                        </tbody>
                    </table>
                </div>
                <!-- end table-responsive -->
            </div>
        </div>
        <!-- end card -->
    </div>
</div>