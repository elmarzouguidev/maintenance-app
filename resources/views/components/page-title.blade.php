{{--
    Page title with breadcrumb (Skote .page-title-box).
    :breadcrumbs="['Clients' => route('admin:clients.index'), 'Nouveau' => null]"
    The last item, or any item without a URL, is shown as the current page.
    The default slot is rendered on the right, before the breadcrumb (page actions).
--}}
@props([
    'title',
    'breadcrumbs' => [],
])

<div {{ $attributes->class('flex flex-col gap-2 pb-6 sm:flex-row sm:items-center sm:justify-between') }}>
    <h4 class="mb-0 text-16 font-semibold uppercase">{{ $title }}</h4>

    <div class="flex flex-wrap items-center gap-3">
        {{ $slot }}

        @if (count($breadcrumbs))
            <nav aria-label="Fil d'Ariane">
                <ol class="flex flex-wrap items-center">
                    @foreach ($breadcrumbs as $label => $url)
                        <li @class(['flex items-center', "before:px-2 before:text-muted before:content-['/']" => ! $loop->first])>
                            @if ($url && ! $loop->last)
                                <a href="{{ $url }}" class="text-body hover:text-primary">{{ $label }}</a>
                            @else
                                <span class="text-muted" @if ($loop->last) aria-current="page" @endif>{{ $label }}</span>
                            @endif
                        </li>
                    @endforeach
                </ol>
            </nav>
        @endif
    </div>
</div>
