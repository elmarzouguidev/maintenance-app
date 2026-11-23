<!doctype html>
{{--
    Maintenance page (php artisan down). Tailwind CSS only: no JavaScript, because Livewire's
    script route also answers 503 while the application is down.
--}}
<html lang="fr">

<head>
    <meta charset="utf-8">
    <title>Casamaintenance APP INDISPONIBLE</title>
    <meta name="robots" content="noindex, nofollow">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="Content-Security-Policy" content="upgrade-insecure-requests">

    <link rel="shortcut icon" href="{{ asset('images/fav-icon.png') }}">

    @vite(['resources/css/app.css'])
</head>

<body>
    <main class="mx-auto max-w-[1140px] px-3 py-12 text-center">
        <h1 class="mb-2 text-[calc(1.575rem+3.9vw)] font-medium leading-tight xl:text-[4.5rem]">
            5<i class="bx bx-buoy bx-spin align-middle text-[calc(1.525rem+3.3vw)] text-primary xl:text-[4rem]" aria-hidden="true"></i>3
        </h1>

        <h2 class="font-medium text-danger">Maintenance MODE</h2>
        <h4>
            Veuillez nous excuser pour la gêne occasionnée.<br> <b>Casamaintenance APP</b> sera à
            nouveau disponible sous peu, revenez bientôt !
        </h4>

        <hr class="my-4 border-black/20">

        <h2 class="font-medium text-danger">Application by WEDOAPP</h2>

        <div class="mt-12">
            <x-button href="https://wedoapp.ma" target="_blank" rel="noopener">wedoapp.ma</x-button>
        </div>

        <div class="mx-auto mt-12 w-full md:w-2/3 xl:w-1/2">
            <img src="{{ asset('assets/images/error-img.png') }}" alt="" class="h-auto max-w-full">
        </div>
    </main>
</body>

</html>
