{{--
    Form field wrapper: label + control (slot) + validation message + hint.
    Used by <x-input>, <x-select> and <x-textarea>; use it directly around custom
    controls (file uploads, checkbox groups, plugin widgets).
    `horizontal` reproduces Bootstrap's `row` + `col-form-label col-lg-2` / `col-lg-10`.
--}}
@props([
    'label' => null,
    'for' => null,
    'name' => null,
    'message' => null,
    'hint' => null,
    'required' => false,
    'horizontal' => false,
])

@php
    if ($message === null && $name !== null) {
        $message = ($errors ?? null)?->first(trim(str_replace(['[]', '[', ']'], ['', '.', ''], $name), '.'));
    }
@endphp

<div {{ $attributes->class(['lg:grid lg:grid-cols-12 lg:gap-x-6' => $horizontal]) }}>
    @if (filled($label))
        <label @if ($for) for="{{ $for }}" @endif @class([
            'mb-2 inline-block font-medium',
            'lg:col-span-2 lg:mb-0 lg:pt-[calc(0.47rem+1px)]' => $horizontal,
        ])>
            {{ $label }}
            @if ($required)
                <span class="text-danger" aria-hidden="true">*</span>
            @endif
        </label>
    @endif

    <div @class(['lg:col-span-10' => $horizontal, 'lg:col-start-3' => $horizontal && blank($label)])>
        {{ $slot }}

        <x-input-error :message="$message" />

        @if (filled($hint))
            <p class="mt-1 text-[80%] text-muted">{{ $hint }}</p>
        @endif
    </div>
</div>
