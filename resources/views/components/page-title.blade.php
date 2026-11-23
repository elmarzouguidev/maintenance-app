{{--
    Page title with breadcrumb (Skote .page-title-box).
    :breadcrumbs="['Clients' => route('admin:clients.index'), 'Nouveau' => null]"
    or, when labels repeat, a list: :breadcrumbs="[['Clients', route('admin:clients.index')], ['Clients']]"
    The last item is the current page (muted); earlier items are links when they have a URL.
    The default slot is rendered on the right, before the breadcrumb (page actions).
--}}
@props([
    'title',
    'breadcrumbs' => [],
])

@php
    $items = array_is_list($breadcrumbs)
        ? array_map(fn ($item) => is_array($item)
            ? ['label' => $item[0], 'url' => $item[1] ?? null]
            : ['label' => $item, 'url' => null], $breadcrumbs)
        : array_map(fn ($label, $url) => ['label' => $label, 'url' => $url], array_keys($breadcrumbs), $breadcrumbs);
@endphp

<div {{ $attributes->class('flex flex-col gap-2 pb-6 sm:flex-row sm:items-center sm:justify-between') }}>
    <h4 class="mb-0 text-16 font-semibold uppercase">{{ $title }}</h4>

    <div class="flex flex-wrap items-center gap-3">
        {{ $slot }}

        @if (count($items))
            <nav aria-label="Fil d'Ariane">
                <ol class="flex flex-wrap items-center">
                    @foreach ($items as $item)
                        <li @class(['flex items-center', "before:px-2 before:text-muted before:content-['/']" => ! $loop->first])>
                            @if ($loop->last)
                                <span class="text-muted" aria-current="page">{{ $item['label'] }}</span>
                            @elseif ($item['url'])
                                <a href="{{ $item['url'] }}" class="text-body hover:text-primary">{{ $item['label'] }}</a>
                            @else
                                <span class="text-body">{{ $item['label'] }}</span>
                            @endif
                        </li>
                    @endforeach
                </ol>
            </nav>
        @endif
    </div>
</div>
