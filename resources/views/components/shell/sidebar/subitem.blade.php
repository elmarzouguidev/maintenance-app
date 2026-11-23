{{-- Sidebar sub-menu link (Skote ul.sub-menu li a). Marked aria-current="page" when its path is the current path. --}}
@props([
    'href',
    'icon' => null,
    'badge' => null,
    'badgeVariant' => 'warning',
])

@php
    $isActive = trim((string) parse_url($href, PHP_URL_PATH), '/') === trim(request()->path(), '/');
    $showBadge = $badge !== null && $badge !== false && $badge !== '';
@endphp

<li>
    <a
        href="{{ $href }}"
        @if ($isActive) aria-current="page" @endif
        @class([
            'group/link flex items-center py-[0.4rem] pe-6 ps-14 text-14 transition-colors',
            'lg:sidebar-collapsed:w-[190px] lg:sidebar-collapsed:px-5 lg:sidebar-collapsed:py-2',
            $isActive ? 'text-white' : 'text-sidebar-sublink hover:text-white',
        ])
    >
        @if ($icon)
            <i @class([
                $icon,
                'inline-block min-w-7 pb-[0.125em] align-middle text-[1.25rem] leading-[1.40625rem] transition-colors',
                $isActive ? 'text-white' : 'text-sidebar-icon group-hover/link:text-white',
            ]) aria-hidden="true"></i>
        @endif

        <span class="min-w-0 flex-1">{{ $slot }}</span>

        @if ($showBadge)
            <x-badge :variant="$badgeVariant" pill class="ms-2">{{ $badge }}</x-badge>
        @endif
    </a>
</li>
