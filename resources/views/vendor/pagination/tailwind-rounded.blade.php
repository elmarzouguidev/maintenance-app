{{--
    Round, right-aligned pager without the "Showing x to y" line: Tailwind version of the
    customised vendor/pagination/bootstrap-4 view (Skote .pagination-rounded).
    Usage: {{ $items->links('vendor.pagination.tailwind-rounded') }}
--}}
@php
    $item = 'flex size-8 items-center justify-center rounded-full leading-none transition-colors';
    $link = $item.' bg-white text-muted hover:bg-light hover:text-primary-hover focus:outline-none focus-visible:ring-[0.15rem] focus-visible:ring-primary/25';
    $disabled = $item.' cursor-default bg-white text-input';
    $current = $item.' bg-primary text-white';
@endphp

@if ($paginator->hasPages())
    <nav aria-label="{{ __('Pagination Navigation') }}" class="flex justify-end">
        <ul class="mb-2 flex flex-wrap items-center gap-1.5">
            @if ($paginator->onFirstPage())
                <li aria-disabled="true" aria-label="{{ __('pagination.previous') }}"><span class="{{ $disabled }}" aria-hidden="true">&lsaquo;</span></li>
            @else
                <li><a class="{{ $link }}" href="{{ $paginator->previousPageUrl() }}" rel="prev" aria-label="{{ __('pagination.previous') }}">&lsaquo;</a></li>
            @endif

            @foreach ($elements as $element)
                @if (is_string($element))
                    <li aria-disabled="true"><span class="{{ $disabled }}">{{ $element }}</span></li>
                @endif

                @if (is_array($element))
                    @foreach ($element as $page => $url)
                        @if ($page == $paginator->currentPage())
                            <li><span class="{{ $current }}" aria-current="page">{{ $page }}</span></li>
                        @else
                            <li><a class="{{ $link }}" href="{{ $url }}" aria-label="{{ __('Go to page :page', ['page' => $page]) }}">{{ $page }}</a></li>
                        @endif
                    @endforeach
                @endif
            @endforeach

            @if ($paginator->hasMorePages())
                <li><a class="{{ $link }}" href="{{ $paginator->nextPageUrl() }}" rel="next" aria-label="{{ __('pagination.next') }}">&rsaquo;</a></li>
            @else
                <li aria-disabled="true" aria-label="{{ __('pagination.next') }}"><span class="{{ $disabled }}" aria-hidden="true">&rsaquo;</span></li>
            @endif
        </ul>
    </nav>
@endif
