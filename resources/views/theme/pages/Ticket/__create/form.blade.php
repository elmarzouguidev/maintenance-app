<div class="row">
    <div class="col-lg-12">
        <div class="card">
            <div class="card-body">
                <h4 class="card-title mb-4">Create New Ticket</h4>
                <p class="card-title-desc">Here are examples of </p>
                @if (session('success'))
                    <div class="alert alert-success">
                        {{ session('success') }}
                    </div>
                @endif
                <form id="ticketForm" action="{{route('admin:tickets.createPost')}}" method="post" enctype="multipart/form-data">

                    @csrf
                    @honeypot
                    <div class="row mb-4">
                        <label for="product" class="col-form-label col-lg-2">product</label>
                        <div class="col-lg-10">
                            <input id="product" name="product" type="text" class="form-control @error('product') is-invalid @enderror" placeholder="Enter product Name...">
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
                            <textarea class="form-control @error('description') is-invalid @enderror" name="description" id="productdesc" rows="3" placeholder="Enter product Description..."></textarea>
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
                    <div class="mb-3 row">
                        <label class="col-md-2 col-form-label">client</label>
                        <div class="col-md-10">
                            <select name="client" class="form-select">
                                <option value="{{route('global:clients.create')}}">nouveaux client</option>
                                @foreach ($clients as $client )
                                  <option value="{{$client->id}}">{{$client->full_name}} : {{$client->ste_name}}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>

                </form>
                    <div class="row mb-4">
                        <label class="col-form-label col-lg-2">Attached Files</label>
                        <div class="col-lg-10">
                            <form action="/" method="post" class="dropzone">
                                <div class="fallback">
                                    <input name="photos" type="file" multiple />
                                </div>

                                <div class="dz-message needsclick">
                                    <div class="mb-3">
                                        <i class="display-4 text-muted bx bxs-cloud-upload"></i>
                                    </div>
                                    
                                    <h4>Drop files here or click to upload.</h4>
                                </div>
                            </form>
                        </div>
                    </div>
                    <div class="row justify-content-end">
                        <div class="col-lg-10">
                            <button onclick="document.getElementById('ticketForm').submit();" class="btn btn-primary">Create Ticket</button>
                        </div>
                    </div>
              

            </div>
        </div>
    </div>
</div>