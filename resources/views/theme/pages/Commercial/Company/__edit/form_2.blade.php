<div class="row">
    <div class="col-12">
        <div class="card">
            <div class="card-body">

                <h4 class="card-title">Basic Information</h4>
                <p class="card-title-desc">Fill all information below</p>
                @if (session('success'))
                    <div class="alert alert-success">
                        {{ session('success') }}
                    </div>
                @endif

                <form id="companyForm" action="{{route('commercial:companies.update',$company->uuid)}}" method="post" enctype="multipart/form-data">
                    @csrf
                    @honeypot
                    <div class="row">
                        <div class="col-sm-6">
                            <div class="mb-3">
                                <label for="name">Entreprise *</label>
                                <input id="name" name="name" type="text" class="form-control @error('name') is-invalid @enderror" value="{{$company->name}}" >
                                @error('name')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>
                            <div class="mb-3">
                                <label for="website">website *</label>
                                <input id="website" name="website" type="text" class="form-control @error('website') is-invalid @enderror" value="{{$company->website}}" >
                                @error('website')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>
                            <div class="mb-3">
                                <label for="city">Ville *</label>
                                <input id="city" name="city" type="text" class="form-control @error('city') is-invalid @enderror" value="{{$company->city}}" >
                                @error('city')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>
                            <div class="mb-3">
                                <label for="telephone">Telephone </label>
                                <input id="telephone" name="telephone" type="text" class="form-control @error('telephone') is-invalid @enderror" value="{{$company->telephone}}" >
                                @error('telephone')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>

                            <div class="mb-3">
                                <label for="email">Email</label>
                                <input id="email" name="email" type="email" class="form-control @error('email') is-invalid @enderror" value="{{$company->email}}">
                                @error('email')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>
                            <div class="mb-3">
                                <label for="addresse">Addresse</label>
                                <input id="addresse" name="addresse" type="text" class="form-control @error('addresse') is-invalid @enderror" value="{{$company->addresse}}">
                                @error('addresse')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>
                            <div class="mb-3">
                                <label>Logo</label>
                                
                                    <input class="form-control @error('logo') is-invalid @enderror" name="logo" type="file" accept="image/*" />
                                    @error('logo')
                                        <span class="invalid-feedback" role="alert">
                                            <strong>{{ $message }}</strong>
                                        </span>
                                    @enderror
                               
                            </div>
                            <div>
                                <img src="{{$company->logo}}" alt="" class="img-fluid mx-auto d-block">
                            </div>
                        </div>

                        <div class="col-sm-6">
                            <div class="mb-3">
                                <label for="rc">RC</label>
                                <input id="rc" name="rc" type="number" class="form-control @error('rc') is-invalid @enderror" value="{{$company->rc}}" >
                                @error('rc')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>
                            <div class="mb-3">
                                <label for="ice">ICE *</label>
                                <input id="ice" name="ice" type="number" class="form-control @error('ice') is-invalid @enderror" value="{{$company->ice}}" >
                                @error('ice')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>
                            <div class="mb-3">
                                <label for="cnss">CNSS *</label>
                                <input id="cnss" name="cnss" type="number" class="form-control @error('cnss') is-invalid @enderror" value="{{$company->cnss}}" >
                                @error('cnss')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>
                            <div class="mb-3">
                                <label for="patente">PATENTE *</label>
                                <input id="patente" name="patente" type="number" class="form-control @error('patente') is-invalid @enderror" value="{{$company->patente}}" >
                                @error('patente')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>
                            <div class="mb-3">
                                <label for="if">IF *</label>
                                <input id="if" name="if" type="number" class="form-control @error('if') is-invalid @enderror" value="{{$company->if}}" >
                                @error('if')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>

                            <div class="mb-3">
                                <label for="description">Description de la société</label>
                                <textarea class="form-control @error('description') is-invalid @enderror" id="description" rows="5" name="description">{{$company->description}}</textarea>
                                
                                @error('description')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>
                            
                        </div>
                    </div>

                    <div class="d-flex flex-wrap gap-2">
                        <button type="submit" class="btn btn-primary waves-effect waves-light">Enregistrer</button>
                        {{--<button type="button" class="btn btn-secondary waves-effect waves-light">Cancel</button>--}}
                    </div>
                </form>

            </div>
        </div>

    </div>
</div>