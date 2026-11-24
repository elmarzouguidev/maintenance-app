{{--
    Platform sidebar navigation (desktop sidebar and phone drawer), on the light surface. The
    current page is marked with aria-current and the accent (selection colour).
    Expects: $navigation (list of ['label' => ?string, 'items' => list of ['href', 'label', 'icon', 'active' => bool]]), $navId.
--}}
<nav aria-label="Navigation de la plateforme" class="space-y-5">
    @foreach ($navigation as $index => $section)
        <div>
            @if ($section['label'] ?? null)
                <h2 id="{{ $navId }}-{{ $index }}" class="mb-1 px-3 text-caption font-medium text-fg-muted">{{ $section['label'] }}</h2>
            @endif
            <ul class="space-y-0.5" @if ($section['label'] ?? null) aria-labelledby="{{ $navId }}-{{ $index }}" @endif>
                @foreach ($section['items'] as $item)
                    @php($isCurrent = (bool) ($item['active'] ?? false))
                    <li>
                        <a href="{{ $item['href'] }}"
                            @if ($isCurrent) aria-current="page" @endif
                            @class([
                                'flex min-h-10 items-center gap-3 rounded-control px-3 text-small font-medium transition-colors duration-150 focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-focus',
                                'bg-accent-soft text-accent-strong' => $isCurrent,
                                'text-fg hover:bg-surface-muted' => ! $isCurrent,
                            ])>
                            <x-admin.icon :name="$item['icon']" @class(['size-5 shrink-0', 'text-accent' => $isCurrent, 'text-fg-muted' => ! $isCurrent]) />
                            {{ $item['label'] }}
                        </a>
                    </li>
                @endforeach
            </ul>
        </div>
    @endforeach
</nav>
