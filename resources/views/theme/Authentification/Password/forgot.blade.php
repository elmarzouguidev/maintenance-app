<!doctype html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>

    <meta charset="utf-8"/>
    <title>Forgot | ERP CASAMAINTENANCE</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <!-- App favicon -->
    <link rel="shortcut icon" href="{{ asset('assets/images/favicon.ico') }}">
    <meta name="robots" content="noindex, nofollow" />
    @include('theme.layouts._parts.vendor-scripts')
    @vite(['resources/css/app.css', 'resources/js/app.js'])

</head>

<body>
<div class="account-pages my-5 pt-sm-5">
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-8 col-lg-6 col-xl-5">
                <div class="card overflow-hidden">
                    <div class="bg-primary bg-soft">
                        <div class="row">
                            <div class="col-7">
                                <div class="text-primary p-4">
                                    <h5 class="text-primary">Mot de passe oublié</h5>
                            
                                </div>
                            </div>
                            <div class="col-5 align-self-end">
                                <img src="{{asset('assets/images/profile-img.png')}}" alt="" class="img-fluid">
                            </div>
                        </div>
                    </div>
                    <div class="card-body pt-0">
                        <div>
                            <a href="{{route('admin:home')}}">
                                <div class="avatar-md profile-user-wid mb-4">
                                        <span class="avatar-title rounded-circle bg-light">
                                            <img src="{{asset('assets/images/logo.svg')}}" alt="" class="rounded-circle"
                                                 height="34">
                                        </span>
                                </div>
                            </a>
                        </div>

                        <div class="p-2">
                            @if ($errors->any())
                                @foreach ($errors->all() as $error)
                                    <div class="alert alert-danger" role="alert">{{ $error }}</div>
                                @endforeach
                            @endif
                            @if (session('status'))
                                <div class="alert alert-success" role="status">
                                    {{ session('status') }}
                                </div>
                            @endif
                            <form autocomplete="off" class="form-horizontal" action="{{ route('forgotpasswordPost') }}"
                                  method="post">
                                @csrf

                                <div class="mb-3">
                                    <label for="useremail" class="form-label">Adresse e-mail</label>
                                    <input id="useremail" type="email"
                                           class="form-control @error('email') is-invalid @enderror" name="email"
                                           value="{{ old('email') }}" placeholder="Saisissez votre adresse e-mail" required
                                           autocomplete="email" autofocus>
                                    @error('email')
                                        <div class="invalid-feedback" role="alert">{{ $message }}</div>
                                    @enderror

                                </div>

                                <div class="text-end">
                                    <button class="btn btn-primary w-md waves-effect waves-light"
                                            type="submit">Envoyer le lien
                                    </button>
                                </div>

                            </form>
                        </div>

                    </div>
                </div>
                @include('theme.Authentification.auth_footer')

            </div>
        </div>
    </div>
</div>

</body>

</html>
