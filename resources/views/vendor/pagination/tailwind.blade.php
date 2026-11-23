{{-- Laravel's default pagination view, restyled to match Bootstrap/Skote .pagination for the Tailwind layout. --}}
@php
    $item = 'relative -ms-px inline-flex items-center border border-input bg-white px-3 py-2 leading-tight transition-colors first:ms-0 first:rounded-s-sm last:rounded-e-sm';
    $link = $item.' text-muted hover:z-10 hover:bg-light hover:text-primary-hover focus:z-10 focus:outline-none focus-visible:ring-[0.15rem] focus-visible:ring-primary/25';
    $disabled = $item.' cursor-default text-input';
    $current = $item.' z-20 border-primary bg-primary text-white';
@endphp

@if ($paginator->hasPages())
    <nav role="navigation" aria-label="{{ __('Pagination Navigation') }}" class="flex items-center justify-between gap-4">
        <div class="flex flex-1 justify-between gap-2 sm:hidden">
            @if ($paginator->onFirstPage())
                <span class="{{ $disabled }} rounded-sm">{!! __('pagination.previous') !!}</span>
            @else
                <a href="{{ $paginator->previousPageUrl() }}" rel="prev" class="{{ $link }} rounded-sm">{!! __('pagination.previous') !!}</a>
            @endif

            @if ($paginator->hasMorePages())
                <a href="{{ $paginator->nextPageUrl() }}" rel="next" class="{{ $link }} rounded-sm">{!! __('pagination.next') !!}</a>
            @else
                <span class="{{ $disabled }} rounded-sm">{!! __('pagination.next') !!}</span>
            @endif
        </div>

        <div class="hidden sm:flex sm:flex-1 sm:items-center sm:justify-between sm:gap-4">
            <p class="mb-0 text-muted">
                {!! __('Showing') !!}
                @if ($paginator->firstItem())
                    <span class="font-medium text-body">{{ $paginator->firstItem() }}</span>
                    {!! __('to') !!}
                    <span class="font-medium text-body">{{ $paginator->lastItem() }}</span>
                @else
                    {{ $paginator->count() }}
                @endif
                {!! __('of') !!}
                <span class="font-medium text-body">{{ $paginator->total() }}</span>
                {!! __('results') !!}
            </p>

            <div class="inline-flex">
                @if ($paginator->onFirstPage())
                    <span class="{{ $disabled }}" aria-disabled="true" aria-label="{{ __('pagination.previous') }}">
                        <i class="mdi mdi-chevron-left leading-none" aria-hidden="true"></i>
                    </span>
                @else
                    <a href="{{ $paginator->previousPageUrl() }}" rel="prev" class="{{ $link }}" aria-label="{{ __('pagination.previous') }}">
                        <i class="mdi mdi-chevron-left leading-none" aria-hidden="true"></i>
                    </a>
                @endif

                @foreach ($elements as $element)
                    @if (is_string($element))
                        <span class="{{ $disabled }}" aria-disabled="true">{{ $element }}</span>
                    @endif

                    @if (is_array($element))
                        @foreach ($element as $page => $url)
                            @if ($page == $paginator->currentPage())
                                <span class="{{ $current }}" aria-current="page">{{ $page }}</span>
                            @else
                                <a href="{{ $url }}" class="{{ $link }}" aria-label="{{ __('Go to page :page', ['page' => $page]) }}">{{ $page }}</a>
                            @endif
                        @endforeach
                    @endif
                @endforeach

                @if ($paginator->hasMorePages())
                    <a href="{{ $paginator->nextPageUrl() }}" rel="next" class="{{ $link }}" aria-label="{{ __('pagination.next') }}">
                        <i class="mdi mdi-chevron-right leading-none" aria-hidden="true"></i>
                    </a>
                @else
                    <span class="{{ $disabled }}" aria-disabled="true" aria-label="{{ __('pagination.next') }}">
                        <i class="mdi mdi-chevron-right leading-none" aria-hidden="true"></i>
                    </span>
                @endif
            </div>
        </div>
    </nav>
@endif
