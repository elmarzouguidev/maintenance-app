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

                <form class="outer-repeater" id="clientForm" action="{{route('admin:clients.createPost')}}" method="post" enctype="multipart/form-data">
                    @csrf
                    @honeypot
                    <div class="row">
                        <div class="col-sm-6">
                            <div class="mb-3">
                                <label for="entreprise">Entreprise *</label>
                                <input id="entreprise" name="entreprise" type="text" class="form-control @error('entreprise') is-invalid @enderror" value="{{old('entreprise')}}" >
                                @error('entreprise')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>
                            <div class="mb-3">
                                <label for="contact">Contact *</label>
                                <input id="contact" name="contact" type="text" class="form-control @error('contact') is-invalid @enderror" value="{{old('contact')}}" >
                                @error('contact')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>
                            <div class="mb-3">
                                <label for="telephone">Telephone FIX (05...) *</label>
                                <input id="telephone" name="telephone" type="text" class="form-control @error('telephone') is-invalid @enderror" value="{{old('telephone')}}" >
                                @error('telephone')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>

                            @if($errors->has('clients.*'))
                                
                                <ul>
                                    @foreach($errors->get('clients.*') as $errors)
                                        @foreach($errors as $error)
                                            <li>{{ $error }}</li>
                                        @endforeach
                                    @endforeach
                                </ul>
                            @endif

                            <div data-repeater-list="clients" class="outer">
                                <div data-repeater-item class="outer">
                                    <div class="inner-repeater mb-3">
                                        <div data-repeater-list="telephones" class="inner mb-3">
                                            <label>autre numéros de téléphones :</label>
                                            <div data-repeater-item class="inner mb-3 row">
                                                <div class="col-md-10 col-8">
                                                    <input type="text" name="telephone" class="inner form-control" placeholder="Enter le numéro ..."/>
                                             
                                                </div>
                                                <div class="col-md-2 col-4">
                                                    <div class="d-grid">
                                                        <input data-repeater-delete type="button" class="btn btn-primary inner" value="Supprimer"/>
                                                    </div>
                                                </div>
                                                
                                            </div>
                                        </div>
                                        <input data-repeater-create type="button" class="btn btn-success inner" value="Ajouter un numéro"/>
                                    </div>
                                </div>
                            </div>

                            <div class="mb-3">
                                <label for="email">Email</label>
                                <input id="email" name="email" type="email" class="form-control @error('email') is-invalid @enderror" value="{{old('email')}}">
                                @error('email')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>
                            <div class="mb-3">
                                <label for="addresse">Addresse</label>
                                <input id="addresse" name="addresse" type="text" class="form-control @error('addresse') is-invalid @enderror" value="{{old('addresse')}}">
                                @error('addresse')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>
                            <div class="mb-3">
                                <label>Logo</label>
                                
                                    <input class="form-control @error('logo') is-invalid @enderror" name="logo" type="file" accept="image/*" />
                                    @error('photo')
                                        <span class="invalid-feedback" role="alert">
                                            <strong>{{ $message }}</strong>
                                        </span>
                                    @enderror
                               
                            </div>
                        </div>

                        <div class="col-sm-6">
                            <div class="mb-3">
                                <label for="rc">RC *</label>
                                <input id="rc" name="rc" type="number" class="form-control @error('rc') is-invalid @enderror" value="{{old('rc')}}" >
                                @error('rc')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>
                            <div class="mb-3">
                                <label for="ice">ICE *</label>
                                <input id="ice" name="ice" type="number" class="form-control @error('ice') is-invalid @enderror" value="{{old('ice')}}" >
                                @error('ice')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>
                            <div class="mb-3">

                                <label class="control-label">Category</label>

                                <select name="category" class="form-control @error('category') is-invalid @enderror">

                                    <option value="">Select</option>
                                    @foreach ($categories as $category)
                                          <option value="{{$category->id}}">{{$category->name}}</option>
                                    @endforeach
                                
                                </select>

                                @error('category')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror

                            </div>
                            {{--<div class="mb-3">
                                <label class="control-label">Features</label>

                                <select class="select2 form-control select2-multiple" multiple="multiple" data-placeholder="Choose ...">
                                    <option value="WI">Wireless</option>
                                    <option value="BE">Battery life</option>
                                    <option value="BA">Bass</option>
                                </select>

                            </div>--}}
                            <div class="mb-3">
                                <label for="description">Description de la société</label>
                                <textarea class="form-control @error('description') is-invalid @enderror" id="description" rows="5" name="description">{{old('description')}}</textarea>
                                
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