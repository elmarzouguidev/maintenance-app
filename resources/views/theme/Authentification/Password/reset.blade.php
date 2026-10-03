<!doctype html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>

    <meta charset="utf-8" />
    <title>Reset password | ERP CASAMAINTENANCE</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="robots" content="noindex, nofollow" />
    <link rel="shortcut icon" href="{{ asset('assets/images/favicon.ico') }}">

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
                                        <h5 class="text-primary">Définir un nouveau mot de passe</h5>

                                    </div>
                                </div>
                                <div class="col-5 align-self-end">
                                    <img src="{{ asset('assets/images/profile-img.png') }}" alt=""
                                        class="img-fluid">
                                </div>
                            </div>
                        </div>
                        <div class="card-body pt-0">
                            <div>
                                <a href="{{ route('admin:home') }}">
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
                                <form autocomplete="off" class="form-horizontal" action="{{ route('password.update') }}"
                                    method="post">

                                    @csrf
                                    <input type="hidden" name="token" value="{{ $token }}">
                                    <div class="mb-3">
                                        <label for="email" class="form-label">Adresse e-mail</label>
                                        <input id="email" type="email"
                                            class="form-control @error('email') is-invalid @enderror" name="email"
                                            value="{{ $email ?? old('email') }}" required autocomplete="email" autofocus
                                            readonly>
                                        @error('email')
                                            <div class="invalid-feedback" role="alert">{{ $message }}</div>
                                        @enderror

                                    </div>
                                    <div class="mb-3">
                                        <label for="password" class="form-label">Mot de passe</label>
                                        <input id="password" type="password"
                                            class="form-control @error('password') is-invalid @enderror" name="password"
                                            required autocomplete="new-password">
                                        @error('password')
                                            <div class="invalid-feedback" role="alert">{{ $message }}</div>
                                        @enderror

                                    </div>
                                    <div class="mb-3">
                                        <label for="password-confirm" class="form-label">Confirmer le mot de passe</label>
                                        <input id="password-confirm" type="password"
                                            class="form-control @error('password_confirmation') is-invalid @enderror"
                                            name="password_confirmation" required autocomplete="new-password">
                                        @error('password_confirmation')
                                            <div class="invalid-feedback" role="alert">{{ $message }}</div>
                                        @enderror

                                    </div>

                                    <div class="text-end">
                                        <button class="btn btn-primary w-md waves-effect waves-light"
                                            type="submit">Réinitialiser le mot de passe
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
