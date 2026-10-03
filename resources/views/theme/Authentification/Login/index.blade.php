<!doctype html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>

    <meta charset="utf-8" />
    <title>Connexion - CASAMAINTENANCE APP</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="robots" content="noindex, nofollow" />
    <link rel="shortcut icon" href="{{ asset('assets/images/favicon.ico') }}">
    <meta content="app_creator" name="Elmarzougui Abdelghafour" />
    <meta content="app_version" name="v 1.1" />
    <meta name="csrf-token" content="{{ csrf_token() }}">

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
                                        <h5 class="text-primary">Se connecter</h5>

                                    </div>
                                </div>
                                <div class="col-5 align-self-end">
                                    <img src="{{ asset('assets/images/profile-img.png') }}" alt=""
                                        class="img-fluid">
                                </div>
                            </div>
                        </div>
                        <div class="card-body pt-0">
                            <div class="auth-logo">
                                <a href="{{ route('home') }}" class="auth-logo-light">
                                    <div class="avatar-md profile-user-wid mb-4">
                                        <span class="avatar-title rounded-circle bg-light">
                                            <img src="{{ asset('assets/images/logo-light.svg') }}" alt=""
                                                class="rounded-circle" height="34">
                                        </span>
                                    </div>
                                </a>

                                <a href="{{ route('home') }}" class="auth-logo-dark">
                                    <div class="avatar-md profile-user-wid mb-4">
                                        <span class="avatar-title rounded-circle bg-light">
                                            <img src="{{ asset('assets/images/logo.svg') }}" alt=""
                                                class="rounded-circle" height="34">
                                        </span>
                                    </div>
                                </a>
                            </div>
                            <div class="p-2">
                                @if (session('success'))
                                    <div class="alert alert-success" role="status">
                                        {{ session('success') }}
                                    </div>
                                @endif
                                @if ($errors->any())
                                    @foreach ($errors->all() as $error)
                                        <div class="alert alert-danger" role="alert">{{ $error }}</div>
                                    @endforeach
                                @endif
                                <form class="form-horizontal" action="{{ route('admin:auth:loginPost') }}"
                                    method="post">
                                    @csrf
                                    <div class="mb-3">
                                        <label for="email" class="form-label">Email</label>
                                        <input type="email" name="email"
                                            class="form-control  @error('email') is-invalid @enderror" id="email"
                                            placeholder="Saisissez votre adresse e-mail" value="{{ old('email') }}" autocomplete="email" autofocus>

                                        @error('email')
                                            <span class="invalid-feedback" role="alert">{{ $message }}</span>
                                        @enderror

                                    </div>
                                    <div class="mb-3">
                                        <label class="form-label" for="password">Mot de passe</label>
                                        <div class="input-group auth-pass-inputgroup">
                                            <input id="password" type="password" name="password"
                                                class="form-control @error('password') is-invalid @enderror"
                                                placeholder="Saisissez votre mot de passe" value="" aria-label="Mot de passe"
                                                aria-describedby="password-addon">
                                            <button class="btn btn-light " type="button" id="password-addon"><i
                                                    class="mdi mdi-eye-outline"></i></button>
                                            @error('password')
                                                <span class="invalid-feedback" role="alert">{{ $message }}</span>
                                            @enderror

                                        </div>
                                    </div>

                                    <div class="form-check">
                                        <input class="form-check-input" type="checkbox" name="remember"
                                            id="remember-check" {{ old('remember') ? 'checked' : '' }}>
                                        <label class="form-check-label" for="remember-check">
                                            Se souvenir de moi
                                        </label>
                                    </div>

                                    <div class="mt-3 d-grid">
                                        <button class="btn btn-primary waves-effect waves-light" type="submit">Se
                                            connecter
                                        </button>
                                    </div>

                                    @if (Route::has('forgotpassword'))
                                        <div class="mt-4 text-center">
                                            <a href="{{ route('forgotpassword') }}" class="text-muted">
                                                <i class="mdi mdi-lock me-1"></i>
                                                Mot de passe oublié ?
                                            </a>
                                        </div>
                                    @endif
                                </form>
                            </div>

                        </div>
                    </div>
                    @include('theme.Authentification.auth_footer')

                </div>
            </div>
        </div>
    </div>
    <!-- end account-pages -->

</body>

</html>
