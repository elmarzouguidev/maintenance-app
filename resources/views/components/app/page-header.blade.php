@props([
    'title',
    'breadcrumbs' => [],
])

<section {{ $attributes->merge(['class' => 'page-title-box']) }}>
    <div class="d-flex flex-column flex-sm-row align-items-sm-center justify-content-between gap-3">
        <h1 class="mb-0 font-size-18 fw-semibold">{{ $title }}</h1>

        <div class="d-flex flex-column flex-sm-row align-items-sm-center gap-3">
            @if ($slot->isNotEmpty())
                <div class="d-flex align-items-center gap-2">
                    {{ $slot }}
                </div>
            @endif

            @if ($breadcrumbs !== [])
                <nav aria-label="Fil d’Ariane">
                    <ol class="breadcrumb mb-0">
                        @foreach ($breadcrumbs as $breadcrumb)
                            @if (! empty($breadcrumb['route']))
                                <li class="breadcrumb-item">
                                    <a href="{{ route($breadcrumb['route'], $breadcrumb['parameters'] ?? []) }}">
                                        {{ $breadcrumb['label'] }}
                                    </a>
                                </li>
                            @else
                                <li class="breadcrumb-item active" aria-current="page">
                                    {{ $breadcrumb['label'] }}
                                </li>
                            @endif
                        @endforeach
                    </ol>
                </nav>
            @endif
        </div>
    </div>
</section>
