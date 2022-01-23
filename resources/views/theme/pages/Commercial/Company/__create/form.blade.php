<div class="row">
    <div class="col-lg-12">
        <div class="card">
            <div class="card-body">
                <h4 class="card-title mb-4">Create New Client</h4>
                <p class="card-title-desc">Here are examples of </p>
                @if (session('success'))
                    <div class="alert alert-success">
                        {{ session('success') }}
                    </div>
                @endif
                <form id="clientForm" action="{{route('admin:clients.createPost')}}" method="post" enctype="multipart/form-data">

                    @csrf
                    @honeypot
                    <div class="row mb-4">
                        <label for="entreprise" class="col-form-label col-lg-2">entreprise</label>
                        <div class="col-lg-5">
                            <input id="entreprise" name="entreprise" type="text" class="form-control @error('entreprise') is-invalid @enderror" placeholder="Enter entreprise ...">
                            @error('entreprise')
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                            @enderror
                        </div>
                    </div>
                    <div class="row mb-4">
                        <label for="email" class="col-form-label col-lg-2">email | telephone</label>
                        <div class="col-lg-5">
                            <input id="email" name="email" type="email" class="form-control @error('email') is-invalid @enderror" placeholder="Enter email ...">
                            @error('email')
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                            @enderror
                        </div>
                        <div class="col-lg-5">
                            <input id="telephone" name="telephone" type="text" class="form-control @error('telephone') is-invalid @enderror" placeholder="Enter telephone ...">
                            @error('telephone')
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                            @enderror
                        </div>
                    </div>
                    <div class="row mb-4">
                        <label for="address" class="col-form-label col-lg-2">address</label>
                        <div class="col-lg-10">
                            <textarea class="form-control @error('address') is-invalid @enderror" name="address" id="address" rows="3" placeholder="Enter address ..."></textarea>
                            @error('address')
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                            @enderror
                        </div>
                    </div>
                    <div class="row mb-4">
                        <label for="ste_name" class="col-form-label col-lg-2">Societe Info</label>
                        <div class="col-lg-4">
                            <input id="ste_name" name="ste_name" type="text" class="form-control @error('ste_name') is-invalid @enderror" placeholder="Enter Societe Nom ...">
                            @error('ste_name')
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                            @enderror
                        </div>
                        <div class="col-lg-3">
                            <input id="ste_ice" name="ste_ice" type="text" class="form-control @error('ste_ice') is-invalid @enderror" placeholder="Enter ICE ...">
                            @error('ste_ice')
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                            @enderror
                        </div>
                        <div class="col-lg-3">
                            <input id="ste_rc" name="ste_rc" type="text" class="form-control @error('ste_rc') is-invalid @enderror" placeholder="Enter RC ...">
                            @error('ste_rc')
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                            @enderror
                        </div>
                    </div>
                    <div class="row mb-4">
                        <label class="col-form-label col-lg-2">Logo</label>
                        <div class="col-lg-10">
                            <input class="form-control @error('ste_logo') is-invalid @enderror" name="ste_logo" type="file" accept="image/*" />
                            @error('ste_logo')
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                            @enderror
                        </div>
                    </div>

                </form>
                    <div class="row justify-content-end">
                        <div class="col-lg-10">
                            <button onclick="document.getElementById('clientForm').submit();" class="btn btn-primary">Ajouté Client</button>
                        </div>
                    </div>
            </div>
        </div>
    </div>
</div>