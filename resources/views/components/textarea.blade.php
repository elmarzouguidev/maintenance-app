{{-- Textarea (Bootstrap textarea.form-control), wrapped in <x-field>. Same error/old() rules as <x-input>. --}}
@props([
    'name' => null,
    'id' => null,
    'value' => null,
    'rows' => 3,
    'label' => null,
    'hint' => null,
    'error' => null,
    'required' => false,
    'horizontal' => false,
])

@php
    $errorKey = $name !== null ? trim(str_replace(['[]', '[', ']'], ['', '.', ''], $name), '.') : null;
    $message = $error ?? ($errorKey !== null ? ($errors ?? null)?->first($errorKey) : null);
    $id ??= $name !== null && ! str_ends_with($name, '[]') ? str_replace(['[', ']', '.'], ['-', '', '-'], $name) : null;

    $usesOld = $errorKey !== null && $attributes->whereStartsWith('wire:model')->isEmpty();
    $resolvedValue = $usesOld ? old($errorKey, $value) : $value;

    $classes = [
        'block w-full rounded-sm border bg-white px-3 py-[0.47rem] text-13 leading-normal text-body transition-colors placeholder:text-muted',
        'focus:outline-none disabled:bg-light read-only:bg-light',
        filled($message)
            ? 'border-danger focus:border-danger focus:ring-[0.15rem] focus:ring-danger/25'
            : 'border-input focus:border-input-focus',
    ];
@endphp

<x-field :label="$label" :for="$id" :message="$message" :hint="$hint" :required="$required" :horizontal="$horizontal">
    <textarea
        @if ($name !== null) name="{{ $name }}" @endif
        @if ($id !== null) id="{{ $id }}" @endif
        rows="{{ $rows }}"
        @if ($required) required @endif
        @if (filled($message)) aria-invalid="true" @endif
        {{ $attributes->class($classes) }}
    >{{ $resolvedValue ?? $slot }}</textarea>
</x-field>
