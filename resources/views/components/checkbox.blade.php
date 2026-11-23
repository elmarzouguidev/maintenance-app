{{--
    Checkbox / radio / switch (Bootstrap .form-check, .form-switch).
    The checked state falls back to old() input after a failed submit (unless wire:model is used).
    `inline` places several checks on one line; show the group's error with <x-field name="…">.
--}}
@props([
    'name' => null,
    'id' => null,
    'value' => '1',
    'label' => null,
    'checked' => false,
    'type' => 'checkbox',
    'switch' => false,
    'inline' => false,
    'error' => null,
])

@php
    $errorKey = $name !== null ? trim(str_replace(['[]', '[', ']'], ['', '.', ''], $name), '.') : null;
    $message = $error ?? ($errorKey !== null && ! $inline ? ($errors ?? null)?->first($errorKey) : null);

    $usesOld = $errorKey !== null
        && $attributes->whereStartsWith('wire:model')->isEmpty()
        && session()->hasOldInput();
    $isChecked = $usesOld
        ? in_array((string) $value, array_map('strval', (array) old($errorKey, [])), true)
        : (bool) $checked;

    $isRadio = $type === 'radio';

    $control = match (true) {
        $switch => 'h-[1em] w-[2em] rounded-full checked:border-primary checked:bg-primary',
        $isRadio => 'size-[1em] rounded-full checked:border-[0.25em] checked:border-primary checked:bg-white',
        default => 'size-[1em] rounded-[0.25em] checked:border-primary checked:bg-primary',
    };

    $classes = [
        'peer shrink-0 appearance-none border bg-white transition-colors',
        'focus:outline-none focus-visible:ring-[0.15rem] focus-visible:ring-primary/25 disabled:cursor-not-allowed',
        filled($message) ? 'border-danger' : 'border-black/25',
        $control,
    ];
@endphp

<div {{ $attributes->only('class')->class(['inline-flex me-4' => $inline, 'flex flex-col' => ! $inline]) }}>
    <label class="inline-flex cursor-pointer items-start gap-2 font-medium has-[:disabled]:cursor-not-allowed has-[:disabled]:opacity-50">
        <span class="relative mt-[0.2em] inline-flex shrink-0">
            <input
                type="{{ $isRadio ? 'radio' : 'checkbox' }}"
                @if ($name !== null) name="{{ $name }}" @endif
                @if ($id !== null) id="{{ $id }}" @endif
                value="{{ $value }}"
                @checked($isChecked)
                @if ($switch) role="switch" @endif
                @if (filled($message)) aria-invalid="true" @endif
                {{ $attributes->except('class')->class($classes) }}
            >

            @if ($switch)
                <span class="pointer-events-none absolute start-[0.125em] top-1/2 size-[0.75em] -translate-y-1/2 rounded-full bg-black/25 transition peer-checked:translate-x-[1em] peer-checked:bg-white" aria-hidden="true"></span>
            @elseif (! $isRadio)
                <i class="mdi mdi-check pointer-events-none absolute inset-0 hidden items-center justify-center text-[0.85em] leading-none text-white peer-checked:flex" aria-hidden="true"></i>
            @endif
        </span>

        @if (filled($label) || $slot->isNotEmpty())
            <span>{{ filled($label) ? $label : $slot }}</span>
        @endif
    </label>

    <x-input-error :message="$message" />
</div>
