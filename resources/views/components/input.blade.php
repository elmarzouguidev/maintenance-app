{{--
    Text input (Bootstrap .form-control + .input-group), wrapped in <x-field>.
    - The error state and message come from the error bag (`items[0][qty]` reads `items.0.qty`), or from `error`.
    - The value falls back to old() input, except for passwords, files and wire:model inputs.
    - `prepend` / `append` (text) and `prepend-icon` / `append-icon` (icon classes) build an input group.
    - `revealable` (type="password") adds a show/hide button (Alpine), replacing Skote's #password-addon.
    - Other attributes (placeholder, wire:model, x-*, class…) go on the <input>.
--}}
@props([
    'name' => null,
    'id' => null,
    'type' => 'text',
    'value' => null,
    'label' => null,
    'hint' => null,
    'error' => null,
    'required' => false,
    'horizontal' => false,
    'size' => 'md',
    'prepend' => null,
    'append' => null,
    'prependIcon' => null,
    'appendIcon' => null,
    'revealable' => false,
])

@php
    $errorKey = $name !== null ? trim(str_replace(['[]', '[', ']'], ['', '.', ''], $name), '.') : null;
    $message = $error ?? ($errorKey !== null ? ($errors ?? null)?->first($errorKey) : null);
    $id ??= $name !== null && ! str_ends_with($name, '[]') ? str_replace(['[', ']', '.'], ['-', '', '-'], $name) : null;

    $usesOld = $errorKey !== null
        && ! in_array($type, ['password', 'file'], true)
        && $attributes->whereStartsWith('wire:model')->isEmpty();
    $resolvedValue = $usesOld ? old($errorKey, $value) : $value;

    $hasPrepend = filled($prepend) || filled($prependIcon);
    $isRevealable = $revealable && $type === 'password';
    $hasAppend = filled($append) || filled($appendIcon) || $isRevealable;

    $sizes = [
        'sm' => 'px-2 py-1 text-[0.7109rem]',
        'md' => 'px-3 py-[0.47rem] text-13',
    ];

    $radius = match (true) {
        $hasPrepend && $hasAppend => 'rounded-none',
        $hasPrepend => 'rounded-e-sm',
        $hasAppend => 'rounded-s-sm',
        default => 'rounded-sm',
    };

    $classes = [
        'block w-full min-w-0 flex-1 border bg-white leading-normal text-body transition-colors placeholder:text-muted',
        'focus:outline-none disabled:bg-light read-only:bg-light',
        $sizes[$size] ?? $sizes['md'],
        $radius,
        filled($message)
            ? 'border-danger focus:border-danger focus:ring-[0.15rem] focus:ring-danger/25'
            : 'border-input focus:border-input-focus',
    ];

    $addon = 'flex items-center whitespace-nowrap border border-input bg-light text-body '
        .($size === 'sm' ? 'px-2 text-[0.7109rem]' : 'px-3 text-13');
@endphp

<x-field :label="$label" :for="$id" :message="$message" :hint="$hint" :required="$required" :horizontal="$horizontal">
    @if ($hasPrepend || $hasAppend)
        <div class="flex w-full items-stretch" @if ($isRevealable) x-data="{ revealed: false }" @endif>
    @endif

    @if ($hasPrepend)
        <span class="{{ $addon }} -me-px rounded-s-sm">
            @if ($prependIcon)<i class="{{ $prependIcon }}" aria-hidden="true"></i>@endif{{ $prepend }}
        </span>
    @endif

    <input
        type="{{ $type }}"
        @if ($name !== null) name="{{ $name }}" @endif
        @if ($id !== null) id="{{ $id }}" @endif
        @if (! is_null($resolvedValue)) value="{{ $resolvedValue }}" @endif
        @if ($required) required @endif
        @if (filled($message)) aria-invalid="true" @endif
        @if ($isRevealable) x-bind:type="revealed ? 'text' : 'password'" @endif
        {{ $attributes->class($classes) }}
    >

    @if ($isRevealable)
        <button
            type="button"
            class="{{ $addon }} -ms-px rounded-e-sm transition-colors hover:bg-light-hover focus:outline-none focus-visible:ring-[0.15rem] focus-visible:ring-primary/25"
            x-on:click="revealed = ! revealed"
            x-bind:aria-pressed="revealed"
            x-bind:aria-label="revealed ? 'Masquer le mot de passe' : 'Afficher le mot de passe'"
            aria-label="Afficher le mot de passe"
            aria-pressed="false"
        >
            <i class="mdi mdi-eye-outline" x-bind:class="revealed ? 'mdi-eye-off-outline' : 'mdi-eye-outline'" aria-hidden="true"></i>
        </button>
    @elseif ($hasAppend)
        <span class="{{ $addon }} -ms-px rounded-e-sm">
            @if ($appendIcon)<i class="{{ $appendIcon }}" aria-hidden="true"></i>@endif{{ $append }}
        </span>
    @endif

    @if ($hasPrepend || $hasAppend)
        </div>
    @endif
</x-field>
