{{--
    Gallery page (exporter only, not part of the kit): the real x-admin.* components with
    fictional data, on the kit tokens. StaticPage adds the kit assets and the gallery bar.
--}}
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, viewport-fit=cover">
    <title>{{ $page['title'] }} · UI kit · @{{ brand }}</title>
    <link rel="icon" href="#">
</head>
<body class="admin-body font-sans">
    <main class="mx-auto max-w-6xl px-4 py-8 lg:px-8">
        <header class="mb-8 max-w-3xl">
            <p class="admin-page-eyebrow">{{ $page['section'] }}</p>
            <h1 class="admin-page-title">{{ $page['title'] }}</h1>
            <p class="admin-page-description">{{ $page['description'] }}</p>
        </header>
        <div class="space-y-12">
            @yield('demo')
        </div>
    </main>
    @include('layouts.date_picker_js')
    @stack('scripts')
</body>
</html>
