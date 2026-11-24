{{-- Gallery home: every section and page of the kit (from the manifest). --}}
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, viewport-fit=cover">
    <title>UI kit · @{{ brand }}</title>
    <link rel="icon" href="#">
</head>
<body class="admin-body font-sans">
    <main class="mx-auto max-w-6xl px-4 py-8 lg:px-8">
        <header class="mb-10 flex flex-wrap items-center gap-4">
            <img src="assets/brand/logo-mark.svg" alt="" class="size-12 rounded-control">
            <div class="min-w-0">
                <p class="admin-page-eyebrow">Reference kit</p>
                <h1 class="admin-page-title">UI kit · @{{ brand }}</h1>
                <p class="admin-page-description">Every layout, component and pattern of the interface, with fictional data. Blade + Alpine.js + Tailwind CSS v4. Rules: REFERENCE.md. Rebranding: BRANDING.md.</p>
            </div>
        </header>

        <div class="space-y-10">
            @foreach ($sections as $section)
                <section aria-labelledby="section-{{ $section['dir'] }}">
                    <h2 id="section-{{ $section['dir'] }}" class="admin-section-title">{{ $section['title'] }}</h2>
                    <p class="mt-1 text-body text-fg-muted">{{ $section['description'] }}</p>
                    <ul class="mt-4 grid gap-3 sm:grid-cols-2 lg:grid-cols-3">
                        @foreach ($section['pages'] as $item)
                            <li class="min-w-0">
                                <a href="{{ $section['dir'] }}/{{ $item['key'] }}.html" class="admin-filter-card h-full">
                                    <span class="block text-body font-semibold text-fg">{{ $item['title'] }}</span>
                                    <span class="mt-1 block text-small text-fg-muted">{{ $item['description'] }}</span>
                                </a>
                            </li>
                        @endforeach
                    </ul>
                </section>
            @endforeach
        </div>
    </main>
</body>
</html>
