{{--
    The page title, shown once per page (the topbar carries no title). Optional eyebrow,
    description, breadcrumbs (list of ['label' => …, 'href' => …]; the last one is the
    current page) and an actions slot.
--}}
@props(['title', 'description' => null, 'eyebrow' => null, 'breadcrumbs' => []])

<header {{ $attributes->class(['admin-page-header']) }}>
    <div class="min-w-0">
        @if ($breadcrumbs !== [])
            <nav aria-label="Fil d’Ariane">
                <ol class="admin-breadcrumb">
                    @foreach ($breadcrumbs as $crumb)
                        <li class="flex items-center gap-1">
                            @if (! $loop->first)
                                <x-admin.icon name="chevron-right" class="size-3.5 rtl:rotate-180" />
                            @endif
                            @if ($loop->last || empty($crumb['href']))
                                <span @if ($loop->last) aria-current="page" @endif>{{ $crumb['label'] }}</span>
                            @else
                                <a href="{{ $crumb['href'] }}" class="rounded-control hover:text-fg hover:underline focus-visible:outline-2 focus-visible:outline-focus">{{ $crumb['label'] }}</a>
                            @endif
                        </li>
                    @endforeach
                </ol>
            </nav>
        @endif

        @if ($eyebrow)
            <p class="admin-page-eyebrow">{{ $eyebrow }}</p>
        @endif

        <h1 class="admin-page-title">{{ $title }}</h1>

        @if ($description)
            <p class="admin-page-description">{{ $description }}</p>
        @endif
    </div>

    @isset($actions)
        @if ($actions->isNotEmpty())
            <div class="flex flex-wrap items-center gap-2">{{ $actions }}</div>
        @endif
    @endisset
</header>
