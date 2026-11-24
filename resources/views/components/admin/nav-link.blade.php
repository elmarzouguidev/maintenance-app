{{--
    Sidebar link. The current page gets aria-current="page" (violet rail).
    href stays in the attribute bag so a pre-escaped href="{{ … }}" is not escaped twice.
--}}
@props(['active' => false, 'icon' => null])

<a @if ($active) aria-current="page" @endif {{ $attributes->merge(['href' => '#'])->class(['admin-nav-link']) }}>
    @if ($icon)
        <x-admin.icon :name="$icon" />
    @endif
    <span class="min-w-0 truncate">{{ $slot }}</span>
</a>
