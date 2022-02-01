<div class="row">
    <div class="col-12">
        <div class="card">
            <div class="card-body">

                <h4 class="card-title">{{ __('company.companies_add') }}</h4>
                <p class="card-title-desc">{{ __('company.form.title') }}</p>
                @if (session('success'))
                    <div class="alert alert-success">
                        {{ session('success') }}
                    </div>
                @endif

                <form id="companyForm" action="{{ route('commercial:companies.update', $company->uuid) }}" method="post"
                    enctype="multipart/form-data">
                    @csrf
                    @honeypot
                    <div class="row">
                        <div class="col-sm-6">
                            <div class="mb-3">
                                <label for="name">{{ __('company.form.company') }} *</label>
                                <input id="name" name="name" type="text"
                                    class="form-control @error('name') is-invalid @enderror"
                                    value="{{ $company->name }}">
                                @error('name')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>
                            <div class="mb-3">
                                <label for="website">{{ __('company.form.website') }} </label>
                                <input id="website" name="website" type="text"
                                    class="form-control @error('website') is-invalid @enderror"
                                    value="{{ $company->website }}">
                                @error('website')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>
                            <div class="mb-3">
                                <label for="city">{{ __('company.form.city') }} *</label>
                                <input id="city" name="city" type="text"
                                    class="form-control @error('city') is-invalid @enderror"
                                    value="{{ $company->city }}">
                                @error('city')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>
                            <div class="mb-3">
                                <label for="telephone">{{ __('company.form.phone') }} </label>
                                <input id="telephone" name="telephone" type="text"
                                    class="form-control @error('telephone') is-invalid @enderror"
                                    value="{{ $company->telephone }}">
                                @error('telephone')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>

                            <div class="mb-3">
                                <label for="email">{{ __('company.form.email') }}</label>
                                <input id="email" name="email" type="email"
                                    class="form-control @error('email') is-invalid @enderror"
                                    value="{{ $company->email }}">
                                @error('email')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>
                            <div class="mb-3">
                                <label for="addresse">{{ __('company.form.addresse') }}</label>
                                <input id="addresse" name="addresse" type="text"
                                    class="form-control @error('addresse') is-invalid @enderror"
                                    value="{{ $company->addresse }}">
                                @error('addresse')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>
                            <div class="mb-3">
                                <label>{{ __('company.form.logo') }}</label>

                                <input class="form-control @error('logo') is-invalid @enderror" name="logo" type="file"
                                    accept="image/*" />
                                @error('logo')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror

                            </div>
                        </div>

                        <div class="col-sm-6">
                            <div class="col-sm-12">
                                <div class="row">
                                    <div class="mb-3 col-sm-6">
                                        <label for="rc">{{ __('company.form.rc') }}</label>
                                        <input id="rc" name="rc" type="number"
                                            class="form-control @error('rc') is-invalid @enderror"
                                            value="{{ $company->rc }}">
                                        @error('rc')
                                            <span class="invalid-feedback" role="alert">
                                                <strong>{{ $message }}</strong>
                                            </span>
                                        @enderror
                                    </div>
                                    <div class="mb-3 col-sm-6">
                                        <label for="ice">{{ __('company.form.ice') }} *</label>
                                        <input id="ice" name="ice" type="number"
                                            class="form-control @error('ice') is-invalid @enderror"
                                            value="{{ $company->ice }}">
                                        @error('ice')
                                            <span class="invalid-feedback" role="alert">
                                                <strong>{{ $message }}</strong>
                                            </span>
                                        @enderror
                                    </div>
                                </div>
                            </div>
                            <div class="mb-3">
                                <label for="cnss">{{ __('company.form.cnss') }} *</label>
                                <input id="cnss" name="cnss" type="number"
                                    class="form-control @error('cnss') is-invalid @enderror"
                                    value="{{ $company->cnss }}">
                                @error('cnss')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>

                            <div class="col-sm-12">
                                <div class="row">
                                    <div class="mb-3 col-sm-6">
                                        <label for="patente">{{ __('company.form.patente') }} *</label>
                                        <input id="patente" name="patente" type="number"
                                            class="form-control @error('patente') is-invalid @enderror"
                                            value="{{ $company->patente }}">
                                        @error('patente')
                                            <span class="invalid-feedback" role="alert">
                                                <strong>{{ $message }}</strong>
                                            </span>
                                        @enderror
                                    </div>
                                    <div class="mb-3 col-sm-6">
                                        <label for="if">{{ __('company.form.if') }} *</label>
                                        <input id="if" name="if" type="number"
                                            class="form-control @error('if') is-invalid @enderror"
                                            value="{{ $company->if }}">
                                        @error('if')
                                            <span class="invalid-feedback" role="alert">
                                                <strong>{{ $message }}</strong>
                                            </span>
                                        @enderror
                                    </div>
                                </div>
                            </div>

                            <div class="col-sm-12">
                                <div class="row">
                                    <div class="mb-3 col-sm-6">
                                        <label for="prefix_invoice">{{ __('company.form.prefix_invoice') }}
                                            (FACTURE-)
                                        </label>
                                        <input id="prefix_invoice" name="prefix_invoice" type="text"
                                            class="form-control @error('prefix_invoice') is-invalid @enderror"
                                            value="{{ $company->prefix_invoice }}">
                                        @error('prefix_invoice')
                                            <span class="invalid-feedback" role="alert">
                                                <strong>{{ $message }}</strong>
                                            </span>
                                        @enderror
                                    </div>
                                    <div class="mb-3 col-sm-6">
                                        <label
                                            for="invoice_start_number">{{ __('company.form.invoice_number_from') }}</label>
                                        <input id="invoice_start_number" name="invoice_start_number" type="text"
                                            class="form-control @error('invoice_start_number') is-invalid @enderror"
                                            value="{{ $company->invoice_start_number }}">
                                        @error('invoice_start_number')
                                            <span class="invalid-feedback" role="alert">
                                                <strong>{{ $message }}</strong>
                                            </span>
                                        @enderror
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="mb-3 col-sm-6">
                                        <label for="prefix_estimate">{{ __('company.form.prefix_estimate') }}
                                            (DEVIS-)
                                        </label>
                                        <input id="prefix_estimate" name="prefix_estimate" type="text"
                                            class="form-control @error('prefix_estimate') is-invalid @enderror"
                                            value="{{ $company->prefix_estimate }}">
                                        @error('prefix_estimate')
                                            <span class="invalid-feedback" role="alert">
                                                <strong>{{ $message }}</strong>
                                            </span>
                                        @enderror
                                    </div>
                                    <div class="mb-3 col-sm-6">
                                        <label
                                            for="estimate_start_number">{{ __('company.form.estimate_number_from') }}</label>
                                        <input id="estimate_start_number" name="estimate_start_number" type="text"
                                            class="form-control @error('estimate_start_number') is-invalid @enderror"
                                            value="{{ $company->estimate_start_number }}">
                                        @error('estimate_start_number')
                                            <span class="invalid-feedback" role="alert">
                                                <strong>{{ $message }}</strong>
                                            </span>
                                        @enderror
                                    </div>
                                </div>
                            </div>

                            <div class="mb-3">
                                <label for="description">{{ __('company.form.description') }}</label>
                                <textarea class="form-control @error('description') is-invalid @enderror"
                                    id="description" rows="4" name="description">{{ $company->description }}</textarea>

                                @error('description')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>

                        </div>
                    </div>

                    <div class="d-flex flex-wrap gap-2">
                        <button type="submit" class="btn btn-primary waves-effect waves-light">
                            {{ __('buttons.store') }}
                        </button>
                        {{-- <button type="button" class="btn btn-secondary waves-effect waves-light">Cancel</button> --}}
                    </div>
                </form>

            </div>
        </div>

    </div>
</div>
