{{--
    Top-level sidebar entry. A link when `href` is given, otherwise a group whose
    sub-items (<x-shell.sidebar.subitem>) go in the slot.
    Active state: the link's path matches the current path (like the legacy URL matching
    in resources/js/main.js); a group is open and active when one of its sub-items is.
    Collapsed desktop sidebar: icon only, label and sub-menu shown as a flyout on hover.
--}}
@props([
    'label',
    'icon' => null,
    'href' => null,
    'badge' => null,
    'badgeVariant' => 'warning',
])

@php
    $isGroup = $href === null;
    $isActive = ! $isGroup && trim((string) parse_url($href, PHP_URL_PATH), '/') === trim(request()->path(), '/');
    $childActive = $isGroup && str_contains((string) $slot, 'aria-current="page"');
    $highlighted = $isActive || $childActive;
    $showBadge = $badge !== null && $badge !== false && $badge !== '';

    $linkClasses = [
        'group/link flex w-full items-center px-6 py-2.5 text-start text-13 transition-colors',
        'lg:sidebar-collapsed:min-h-[55px] lg:sidebar-collapsed:px-5 lg:sidebar-collapsed:py-[15px]',
        'lg:sidebar-collapsed:group-hover:w-[260px] lg:sidebar-collapsed:group-hover:bg-sidebar-hover lg:sidebar-collapsed:group-hover:text-white',
        $highlighted ? 'text-white' : 'text-sidebar-link hover:text-white',
    ];

    $iconClasses = [
        $icon,
        'inline-block min-w-7 pb-[0.125em] align-middle text-[1.25rem] leading-[1.40625rem] transition-colors',
        'lg:sidebar-collapsed:ms-1 lg:sidebar-collapsed:text-[1.45rem] lg:sidebar-collapsed:group-hover:text-white',
        $highlighted ? 'text-white' : 'text-sidebar-icon group-hover/link:text-white',
    ];

    $labelClasses = 'min-w-0 flex-1 lg:sidebar-collapsed:hidden lg:sidebar-collapsed:ps-[25px] lg:sidebar-collapsed:group-hover:inline';
@endphp

<li class="group relative lg:sidebar-collapsed:whitespace-nowrap" @if ($isGroup) x-data="{ open: @js($childActive) }" @endif>
    @if ($isGroup)
        <button type="button" x-on:click="open = ! open" x-bind:aria-expanded="open" aria-expanded="{{ $childActive ? 'true' : 'false' }}" @class($linkClasses)>
    @else
        <a href="{{ $href }}" @if ($isActive) aria-current="page" @endif @class($linkClasses)>
    @endif
        @if ($icon)
            <i @class($iconClasses) aria-hidden="true"></i>
        @endif

        <span class="{{ $labelClasses }}">{{ $label }}</span>

        @if ($showBadge)
            <x-badge :variant="$badgeVariant" pill class="ms-2 lg:sidebar-collapsed:hidden">{{ $badge }}</x-badge>
        @endif

        @if ($isGroup)
            <i class="mdi mdi-chevron-down ms-2 text-16 leading-none transition-transform lg:sidebar-collapsed:hidden" x-bind:class="open && 'rotate-180'" aria-hidden="true"></i>
        @endif
    @if ($isGroup)
        </button>
    @else
        </a>
    @endif

    @if ($isGroup)
        <ul
            x-show="open"
            x-collapse
            @unless ($childActive) style="display: none" @endunless
            class="lg:sidebar-collapsed:absolute lg:sidebar-collapsed:start-[70px] lg:sidebar-collapsed:z-[9999] lg:sidebar-collapsed:w-[190px] lg:sidebar-collapsed:bg-sidebar lg:sidebar-collapsed:py-[5px] lg:sidebar-collapsed:shadow-[3px_5px_10px_0_rgba(54,61,71,0.1)] lg:sidebar-collapsed:hidden! lg:sidebar-collapsed:group-hover:block! lg:sidebar-collapsed:group-hover:h-auto! lg:sidebar-collapsed:group-hover:overflow-visible!"
        >
            {{ $slot }}
        </ul>
    @endif
</li>
