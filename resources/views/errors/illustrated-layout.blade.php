@php
    $errorMessage = strip_tags(trim($__env->yieldContent('message')));
@endphp
<!doctype html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="robots" content="noindex, nofollow">
    <title>{{ trim($__env->yieldContent('title')) ?: 'Erreur' }}</title>
    <link rel="shortcut icon" href="{{ asset('assets/images/favicon.ico') }}">
    @vite(['resources/css/app.css'])
</head>
<body>
    <main class="container min-vh-100 d-flex align-items-center justify-content-center py-5">
        <div class="row align-items-center justify-content-center g-4 w-100" style="max-width: 64rem">
            <div class="col-12 col-md-6 text-center">
                <img src="{{ asset('assets/images/error-img.png') }}" alt="" class="img-fluid" loading="lazy">
            </div>
            <div class="col-12 col-md-6">
                <section class="card border-0 shadow-sm" aria-labelledby="error-title">
                    <div class="card-body p-4 p-md-5">
                        <span class="avatar-md rounded-circle bg-warning-subtle text-warning-emphasis d-inline-flex align-items-center justify-content-center mb-4" aria-hidden="true">
                            <i class="bx bx-time-five font-size-24"></i>
                        </span>
                        <h1 id="error-title" class="h3 mb-3">{{ trim($__env->yieldContent('title')) ?: 'Une erreur est survenue' }}</h1>
                        <p class="text-muted mb-4">{{ $errorMessage }}</p>
                        <a href="{{ url('/') }}" class="btn btn-primary">
                            <i class="bx bx-home-alt me-1" aria-hidden="true"></i>Retour à l’accueil
                        </a>
                    </div>
                </section>
            </div>
        </div>
    </main>
</body>
</html>
