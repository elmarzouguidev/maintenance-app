{{--
    Select (Bootstrap .form-select), wrapped in <x-field>.
    Options come from `:options="[value => label]"`, or from the slot (custom <option> / <optgroup>).
    The selected value falls back to old() input. Add `multiple` for a multi-select (name="x[]").
    `select2` adds the Select2 hook class and a wire:ignore wrapper so Livewire leaves it alone.
--}}
@props([
    'name' => null,
    'id' => null,
    'options' => [],
    'value' => null,
    'placeholder' => null,
    'label' => null,
    'hint' => null,
    'error' => null,
    'required' => false,
    'horizontal' => false,
    'size' => 'md',
    'select2' => false,
])

@php
    $errorKey = $name !== null ? trim(str_replace(['[]', '[', ']'], ['', '.', ''], $name), '.') : null;
    $message = $error ?? ($errorKey !== null ? ($errors ?? null)?->first($errorKey) : null);
    $id ??= $name !== null && ! str_ends_with($name, '[]') ? str_replace(['[', ']', '.'], ['-', '', '-'], $name) : null;

    $multiple = (bool) $attributes->get('multiple');
    $usesOld = $errorKey !== null && $attributes->whereStartsWith('wire:model')->isEmpty();
    $selectedValues = array_map('strval', (array) ($usesOld ? old($errorKey, $value) : $value));
    $showChevron = ! $multiple && ! $select2;

    $sizes = [
        'sm' => 'py-1 ps-2 text-[0.7109rem]',
        'md' => 'py-[0.47rem] ps-3 text-13',
    ];

    $classes = [
        'block w-full appearance-none rounded-sm border bg-white leading-normal text-body transition-colors',
        'focus:outline-none disabled:bg-light',
        $sizes[$size] ?? $sizes['md'],
        $showChevron ? 'pe-8' : 'pe-3',
        $select2 ? 'select2' : '',
        filled($message)
            ? 'border-danger focus:border-danger focus:ring-[0.15rem] focus:ring-danger/25'
            : 'border-input focus:border-input-focus',
    ];
@endphp

<x-field :label="$label" :for="$id" :message="$message" :hint="$hint" :required="$required" :horizontal="$horizontal">
    <div class="relative" @if ($select2) wire:ignore @endif>
        <select
            @if ($name !== null) name="{{ $name }}" @endif
            @if ($id !== null) id="{{ $id }}" @endif
            @if ($required) required @endif
            @if (filled($message)) aria-invalid="true" @endif
            {{ $attributes->class($classes) }}
        >
            @if (filled($placeholder))
                <option value="">{{ $placeholder }}</option>
            @endif

            @if ($slot->isNotEmpty())
                {{ $slot }}
            @else
                @foreach ($options as $optionValue => $optionLabel)
                    <option value="{{ $optionValue }}" @selected(in_array((string) $optionValue, $selectedValues, true))>{{ $optionLabel }}</option>
                @endforeach
            @endif
        </select>

        @if ($showChevron)
            <i class="mdi mdi-chevron-down pointer-events-none absolute end-2.5 top-1/2 -translate-y-1/2 text-16 leading-none text-dark" aria-hidden="true"></i>
        @endif
    </div>
</x-field>
