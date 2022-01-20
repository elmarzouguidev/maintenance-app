<div class="row">
    <div class="col-6">
        <div class="card">
            <div class="card-body">

                <h4 class="card-title">Edit: {{$technicien->full_name}}</h4>
                @if (session('success'))
                    <div class="alert alert-success">
                        {{ session('success') }}
                    </div>
                @endif
                <form action="{{route('admin:techniciens.update',$technicien->id)}}" method="post">
                    @csrf
                    @honeypot
                    <div class="mb-3 row">
                        <label for="nom" class="col-md-2 col-form-label">Nom</label>
                        <div class="col-md-10">
                            <input class="form-control @error('nom') is-invalid @enderror" type="text" name="nom" value="{{$technicien->nom}}"
                                id="nom">
                            @error('nom')
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                            @enderror
                        </div>
                    </div>
                    <div class="mb-3 row">
                        <label for="prenom" class="col-md-2 col-form-label">Prénom</label>
                        <div class="col-md-10">
                            <input class="form-control @error('prenom') is-invalid @enderror" type="text" name="prenom" value="{{$technicien->prenom}}"
                                id="prenom">
                            @error('prenom')
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                            @enderror
                        </div>
                    </div>

                    <div class="mb-3 row">
                        <label for="example-email-input" class="col-md-2 col-form-label">Email</label>
                        <div class="col-md-10">
                            <input class="form-control @error('email') is-invalid @enderror" type="email" name="email" value="{{$technicien->email}}"
                                id="example-email-input">
                            @error('email')
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                            @enderror
                        </div>
                    </div>

                    <div class="mb-3 row">
                        <label for="example-tel-input" class="col-md-2 col-form-label">Telephone</label>
                        <div class="col-md-10">
                            <input class="form-control @error('telephone') is-invalid @enderror" name="telephone" type="tel" value="{{$technicien->telephone}}"
                                id="example-tel-input">
                            @error('telephone')
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                            @enderror
                        </div>
                    </div>

                    {{--<div class="mb-3 row">
                        <label for="example-password-input" class="col-md-2 col-form-label">Password</label>
                        <div class="col-md-10">
                            <input class="form-control @error('password') is-invalid @enderror" name="password" type="password" readonly
                                id="example-password-input">
                            @error('password')
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                            @enderror
                        </div>
                    </div>--}}
                    {{--@include('theme.pages.Auth.Technicien.__profile.__select_multi_permissions')--}}
                    <div>
                        <button type="submit" class="btn btn-primary w-md">Enregistrer</button>
                    </div>
                </form>
            </div>
        </div>
    </div> 
    <div class="col-6">
        <div class="card">
            <div class="card-body">

                <h4 class="card-title">Permissions : {{$technicien->full_name}}</h4>
                @if (session('success'))
                    <div class="alert alert-success">
                        {{ session('success') }}
                    </div>
                @endif
                <form action="{{route('admin:techniciens.syncPermissions',$technicien->id)}}" method="post">
                    @csrf
        
                    <div class="mb-3 row">
                        <label for="nom" class="col-md-2 col-form-label">Nom</label>
                        <div class="col-md-10">
                            <input class="form-control @error('nom') is-invalid @enderror" type="text" name="nom" value="{{$technicien->full_name}}" readonly
                                id="nom">
                            @error('nom')
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                            @enderror
                        </div>
                    </div>

                    {{--@include('theme.pages.Auth.Technicien.__profile.__select_multi_permissions')--}}
                    @include('theme.pages.Auth.Technicien.__profile.__checkbox_permissions')
            
                    <div class="mb-3 row">
                        <label for="example-password-input" class="col-md-2 col-form-label">Permissions</label>
                        <div class="col-md-10">
                            <button type="submit" class="btn btn-primary w-md">Sync Permissions</button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
