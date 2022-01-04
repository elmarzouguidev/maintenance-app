<div class="row">
    <div class="col-lg-12">
        <div class="card">
            <div class="card-body">
                <h4 class="card-title mb-4">Edit  Ticket :  {{$ticket->product}}</h4>
                <p class="card-title-desc">{{$ticket->product}} </p>
                @if (session('success'))
                    <div class="alert alert-success">
                        {{ session('success') }}
                    </div>
                @endif
                <form id="ticketForm" action="{{$ticket->update_url}}" method="post" enctype="multipart/form-data">

                    @csrf
                    @honeypot
                    @method('PUT')
                    <div class="row mb-4">
                        <label for="product" class="col-form-label col-lg-2">product</label>
                        <div class="col-lg-10">
                            <input id="product" name="product" type="text" class="form-control @error('product') is-invalid @enderror" value="{{$ticket->product}}">
                            @error('product')
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                            @enderror
                        </div>
                    </div>
                    <div class="row mb-4">
                        <label for="productdesc" class="col-form-label col-lg-2">Description</label>
                        <div class="col-lg-10">
                            <textarea class="form-control @error('description') is-invalid @enderror" name="description" id="productdesc" rows="6">
                                {{$ticket->description}}
                            </textarea>

                            @error('description')
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                            @enderror
                        </div>
                    </div>

                    <div class="row mb-4">
                        <label class="col-form-label col-lg-2">Photo</label>
                        <div class="col-lg-10">
                            <input class="form-control @error('photo') is-invalid @enderror" name="photo" type="file" accept="image/*" />
                            @error('photo')
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                            @enderror
                        </div>
                    </div>

                    <div class="row mb-4">
                        <label class="col-form-label col-lg-2"></label>
                        <div class="col-lg-10">
                            <img src="{{$ticket->image}}" alt="" class="avatar-xl">
                        </div>
                    </div>


                </form>
                    <div class="row mb-4">
                        <label class="col-form-label col-lg-2">Attached Files</label>
                        <div class="col-lg-10">
                            <form id="ticketFormAttachements" action="{{route('admin:tickets.attachements',$ticket->id)}}" method="post" enctype="multipart/form-data">
                                <div class="row mb-4">
                           
                                    <div class="col-lg-10">
                                        <input class="form-control @error('photos') is-invalid @enderror" name="photos[]" type="file" accept="image/*" multiple />
                                        @error('photo')
                                            <span class="invalid-feedback" role="alert">
                                                <strong>{{ $message }}</strong>
                                            </span>
                                        @enderror
                                    </div>
                                </div>
                                @error('photos')
                                    <span role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                                @csrf
                                @honeypot
                
                            </form>
                            <div class="row mb-4">
                                
                                <div class="col-lg-10">
                                    <img src="{{$ticket->image}}" alt="" class="avatar-xl">
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="row justify-content-end">
                        <div class="col-lg-10">
                            <button onclick="document.getElementById('ticketForm').submit();" class="btn btn-primary">Update Ticket</button>
                            <button onclick="document.getElementById('ticketFormAttachements').submit();" class="btn btn-info">attaches images</button>

                        </div>
                    </div>
              

            </div>
        </div>
    </div>
</div>