<div class="card">
    <div class="card-body">
        @if (session('success'))
            <div class="alert alert-success">
                {{ session('success') }}
            </div>
        @endif
        <form action="{{ route('admin:profile.settings.update.company') }}" method="post" enctype="multipart/form-data">
            @csrf

            <div class="mb-3 row">
                <label for="addresse" class="col-md-2 col-form-label">LOGO *</label>
                <div class="col-md-10">
                  
                       <img src="{{ asset('storage/' . $setting->logo) }}" class="img-fluid" width="10%">

                    <input class="form-control @error('logo') is-invalid @enderror" name="logo" type="file"
                        accept="image/*"  />
                    @error('photo')
                        <span class="invalid-feedback" role="alert">
                            <strong>{{ $message }}</strong>
                        </span>
                    @enderror
                </div>
            </div>
            <div>
                <button type="submit" class="btn btn-primary w-md">Update</button>
            </div>
        </form>
    </div>
</div>
