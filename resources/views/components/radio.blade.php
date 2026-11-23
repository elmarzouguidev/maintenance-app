{{-- Radio button (Bootstrap .form-check with type="radio"). See <x-checkbox>. --}}
@props([
    'name' => null,
    'id' => null,
    'value' => '1',
    'label' => null,
    'checked' => false,
    'inline' => false,
    'error' => null,
])

<x-checkbox
    type="radio"
    :name="$name"
    :id="$id"
    :value="$value"
    :label="$label"
    :checked="$checked"
    :inline="$inline"
    :error="$error"
    {{ $attributes }}
>{{ $slot }}</x-checkbox>
