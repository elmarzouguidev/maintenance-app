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
                <form id="clientForm" action="{{route('global:clients.createPost')}}" method="post" enctype="multipart/form-data">
                    @csrf
                    @honeypot
                    <div class="row">
                        <div class="col-sm-6">
                            <div class="mb-3">
                                <label for="entreprise">Entreprise *</label>
                                <input id="entreprise" name="entreprise" type="text" class="form-control @error('entreprise') is-invalid @enderror" value="{{old('entreprise')}}" required>
                                @error('entreprise')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>
                            <div class="mb-3">
                                <label for="contact">Contact *</label>
                                <input id="contact" name="contact" type="text" class="form-control @error('contact') is-invalid @enderror" value="{{old('contact')}}" required>
                                @error('contact')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>
                            <div class="mb-3">
                                <label for="telephone">Telephone *</label>
                                <input id="telephone" name="telephone" type="text" class="form-control @error('telephone') is-invalid @enderror" value="{{old('telephone')}}" required>
                                @error('telephone')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
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
                        </div>

                        <div class="col-sm-6">
                            <div class="mb-3">
                                <label for="rc">RC *</label>
                                <input id="rc" name="rc" type="number" class="form-control @error('rc') is-invalid @enderror" value="{{old('rc')}}" required>
                                @error('rc')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>
                            <div class="mb-3">
                                <label for="ice">ICE *</label>
                                <input id="ice" name="ice" type="number" class="form-control @error('ice') is-invalid @enderror" value="{{old('ice')}}" required>
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
                                <textarea class="form-control @error('description') is-invalid @enderror" id="description" rows="5" name="description">
                                {{old('description')}}
                                </textarea>
                                @error('description')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>
                            
                        </div>
                    </div>

                    <div class="d-flex flex-wrap gap-2">
                        <button type="submit" class="btn btn-primary waves-effect waves-light">Save</button>
                        <button type="button" class="btn btn-secondary waves-effect waves-light">Cancel</button>
                    </div>
                </form>

            </div>
        </div>

    </div>
</div>