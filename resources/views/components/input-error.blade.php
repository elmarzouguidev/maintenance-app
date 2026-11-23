{{--
    Validation message (Bootstrap .invalid-feedback).
    Give `name` (bracket or dot notation) to read the error bag, or `message` directly.
--}}
@props([
    'name' => null,
    'message' => null,
])

@php
    if ($message === null && $name !== null) {
        $message = ($errors ?? null)?->first(trim(str_replace(['[]', '[', ']'], ['', '.', ''], $name), '.'));
    }
@endphp

@if (filled($message))
    <p {{ $attributes->class('mt-1 text-[80%] font-semibold text-danger') }} role="alert">{{ $message }}</p>
@endif
