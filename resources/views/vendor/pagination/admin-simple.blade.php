{{--
    Simple paginator view for the UI kit pages (simplePaginate(): no total, so previous / next
    only): $paginator->links('pagination::admin-simple'). Built like pagination::admin, from kit
    components and token utilities only.
--}}
@if ($paginator->hasPages())
    <nav aria-label="Pagination" class="flex items-center justify-between gap-3">
        <p class="text-small text-fg-muted tabular-nums">Page {{ $paginator->currentPage() }}</p>

        <ul class="flex items-center gap-1">
            <li>
                @if ($paginator->onFirstPage())
                    <x-admin.icon-button icon="chevron-left" label="Page précédente" size="sm" disabled />
                @else
                    <x-admin.icon-button icon="chevron-left" label="Page précédente" size="sm" href="{{ $paginator->previousPageUrl() }}" rel="prev" />
                @endif
            </li>
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
