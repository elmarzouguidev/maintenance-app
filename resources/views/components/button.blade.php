{{--
    Button (Bootstrap .btn). Renders <a> when `href` is given, otherwise <button>.
    Defaults to type="submit" like a native button: give type="button" to every
    button that does not submit a form (modal, dropdown, tab triggers, click handlers).
--}}
@props([
    'variant' => 'primary',
    'outline' => false,
    'size' => 'md',
    'pill' => false,
    'block' => false,
    'href' => null,
    'icon' => null,
    'type' => 'submit',
])

@php
    $solid = [
        'primary' => 'border-primary bg-primary text-white hover:border-primary-hover hover:bg-primary-hover focus-visible:ring-primary/50',
        'secondary' => 'border-secondary bg-secondary text-white hover:border-secondary-hover hover:bg-secondary-hover focus-visible:ring-secondary/50',
        'success' => 'border-success bg-success text-white hover:border-success-hover hover:bg-success-hover focus-visible:ring-success/50',
        'danger' => 'border-danger bg-danger text-white hover:border-danger-hover hover:bg-danger-hover focus-visible:ring-danger/50',
        'warning' => 'border-warning bg-warning text-white hover:border-warning-hover hover:bg-warning-hover focus-visible:ring-warning/50',
        'info' => 'border-info bg-info text-white hover:border-info-hover hover:bg-info-hover focus-visible:ring-info/50',
        'light' => 'border-light bg-light text-black hover:border-light-hover hover:bg-light-hover focus-visible:ring-light',
        'dark' => 'border-dark bg-dark text-white hover:border-dark-hover hover:bg-dark-hover focus-visible:ring-dark/50',
        'link' => 'border-transparent bg-transparent text-primary hover:text-primary-hover hover:underline focus-visible:ring-primary/25',
    ];

    $outlined = [
        'primary' => 'border-primary text-primary hover:bg-primary hover:text-white focus-visible:ring-primary/50',
        'secondary' => 'border-secondary text-secondary hover:bg-secondary hover:text-white focus-visible:ring-secondary/50',
        'success' => 'border-success text-success hover:bg-success hover:text-white focus-visible:ring-success/50',
        'danger' => 'border-danger text-danger hover:bg-danger hover:text-white focus-visible:ring-danger/50',
        'warning' => 'border-warning text-warning hover:bg-warning hover:text-white focus-visible:ring-warning/50',
        'info' => 'border-info text-info hover:bg-info hover:text-white focus-visible:ring-info/50',
        'light' => 'border-light text-body hover:bg-light focus-visible:ring-light',
        'dark' => 'border-dark text-dark hover:bg-dark hover:text-white focus-visible:ring-dark/50',
        'link' => $solid['link'],
    ];

    $sizes = [
        'sm' => 'px-2 py-1 text-[0.7109rem]',
        'md' => 'px-3 py-[0.47rem] text-13',
    ];

    $classes = [
        'inline-flex items-center justify-center gap-1 border text-center align-middle leading-normal no-underline transition-colors select-none',
        'focus:outline-none focus-visible:ring-[0.15rem] disabled:pointer-events-none disabled:opacity-65 aria-disabled:pointer-events-none aria-disabled:opacity-65',
        $pill ? 'rounded-full' : ($size === 'sm' ? 'rounded-[0.2rem]' : 'rounded-sm'),
        $sizes[$size] ?? $sizes['md'],
        ($outline ? $outlined : $solid)[$variant] ?? $solid['primary'],
        $block ? 'w-full' : '',
    ];

    $tag = $href !== null ? 'a' : 'button';
@endphp

<{{ $tag }} @if ($href !== null) href="{{ $href }}" @else type="{{ $type }}" @endif {{ $attributes->class($classes) }}>
    @if ($icon)
        <i class="{{ $icon }}" aria-hidden="true"></i>
    @endif
    {{ $slot }}
</{{ $tag }}>
