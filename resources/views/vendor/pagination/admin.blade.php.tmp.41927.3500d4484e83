{{--
    Paginator view for the UI kit pages: $paginator->links('pagination::admin').
    Not part of ui-kit/ (the kit has no pagination yet): built from kit components and token
    utilities only, the current page in the selection colours (accent-soft / accent-strong).
    Phones show "Page X sur Y" between the previous and next buttons instead of the page numbers.
--}}
@if ($paginator->hasPages())
    <nav aria-label="Pagination" class="flex flex-col items-center justify-between gap-3 sm:flex-row">
        <p class="text-small text-fg-muted">
            @if ($paginator->firstItem())
                <span class="tabular-nums">{{ $paginator->firstItem() }}</span>–<span class="tabular-nums">{{ $paginator->lastItem() }}</span>
                sur <span class="tabular-nums">{{ $paginator->total() }}</span>
            @else
                Aucun résultat sur cette page
            @endif
        </p>

        <ul class="flex items-center gap-1">
            <li>
                @if ($paginator->onFirstPage())
                    <x-admin.icon-button icon="chevron-left" label="Page précédente" size="sm" disabled />
                @else
                    <x-admin.icon-button icon="chevron-left" label="Page précédente" size="sm" href="{{ $paginator->previousPageUrl() }}" rel="prev" />
                @endif
            </li>

            <li class="px-2 text-small text-fg-muted tabular-nums sm:hidden">
                Page {{ $paginator->currentPage() }} sur {{ $paginator->lastPage() }}
            </li>

            @foreach ($elements as $element)
                @if (is_string($element))
                    <li class="hidden px-1 text-small text-fg-muted sm:block" aria-hidden="true">…</li>
                @endif

                @if (is_array($element))
                    @foreach ($element as $page => $url)
                        <li class="hidden sm:block">
                            @if ($page == $paginator->currentPage())
                                <span aria-current="page" class="admin-button admin-button-ghost admin-button-sm min-w-8 bg-accent-soft text-accent-strong tabular-nums hover:bg-accent-soft">{{ $page }}</span>
                            @else
                                <x-admin.button href="{{ $url }}" variant="ghost" size="sm" class="min-w-8 tabular-nums" aria-label="Page {{ $page }}">{{ $page }}</x-admin.button>
                            @endif
                        </li>
                    @endforeach
                @endif
            @endforeach

            <li>
                @if ($paginator->hasMorePages())
                    <x-admin.icon-button icon="chevron-right" label="Page suivante" size="sm" href="{{ $paginator->nextPageUrl() }}" rel="next" />
                @else
                    <x-admin.icon-button icon="chevron-right" label="Page suivante" size="sm" disabled />
                @endif
            </li>
        </ul>
    </nav>
@endif
