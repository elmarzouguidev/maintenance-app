<!DOCTYPE html>
{{--
    Maintenance page (php artisan down), on the UI kit: the guest page structure as a standalone
    document. CSS only (resources/css/ui-kit.css), no JavaScript and no session: the app answers
    503 to every route while it is down.
--}}
<html lang="fr">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, viewport-fit=cover">
    <meta name="robots" content="noindex, nofollow">

    <title>Maintenance en cours - {{ config('brand.name') }}</title>

    @include('layouts.partials.favicon')

    @vite(['resources/css/ui-kit.css'])
</head>

<body class="admin-body flex min-h-screen flex-col font-sans">
    <main class="flex flex-1 items-center justify-center px-4 py-10">
        <div class="w-full max-w-md">
            <div class="admin-card p-6 sm:p-8">
                <x-admin.guest-heading eyebrow="Erreur 503" title="Maintenance en cours"
                    :description="'Veuillez nous excuser pour la gêne occasionnée. '.config('brand.name').' sera de nouveau disponible dans quelques minutes.'" />

                <x-admin.alert type="info" :dismissible="false" message="Revenez un peu plus tard : vos données ne sont pas affectées." />
            </div>
        </div>
    </main>

    <footer class="border-t border-line bg-surface">
        <div class="flex flex-col items-center justify-between gap-2 px-6 py-4 text-caption text-fg-muted sm:flex-row">
            <p>
                &copy; {{ date('Y') }}
                <span class="font-semibold text-fg">{{ config('brand.name') }}</span>
                <span class="tabular-nums">{{ config('brand.version') }}</span>
            </p>
            <p>
                Développé par
                <a href="{{ config('brand.vendor.url') }}" target="_blank" rel="noopener" class="font-semibold text-fg hover:underline">{{ config('brand.vendor.name') }}</a>
            </p>
        </div>
    </footer>
</body>

</html>
