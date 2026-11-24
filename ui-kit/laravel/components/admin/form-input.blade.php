{{--
    Text-like input. Space fields with gap on the parent. plate keeps plates such as
    "12345-أ-6" in typed order while typing (display only; the submitted value is unchanged).
--}}
@props([
    'label' => null,
    'name',
    'type' => 'text',
    'value' => '',
    'required' => false,
    'placeholder' => '',
    'help' => null,
    'inputmode' => null,
    'autocomplete' => null,
    'plate' => false,
])

@php
    $id = $attributes->get('id', $name);
    $hasError = $errors->has($name);
    $describedBy = trim(($help ? $id.'-help ' : '').($hasError ? $id.'-error' : ''));
@endphp

<x-admin.form.field :id="$id" :name="$name" :label="$label" :required="$required" :help="$help" :class="$attributes->get('class')">
    <input type="{{ $type }}"
        id="{{ $id }}"
        name="{{ $name }}"
        value="{{ old($name, $value) }}"
        placeholder="{{ $placeholder }}"
        @if ($inputmode) inputmode="{{ $inputmode }}" @endif
        @if ($autocomplete) autocomplete="{{ $autocomplete }}" @endif
        @if ($required) required @endif
        @if ($hasError) aria-invalid="true" @endif
        @if ($describedBy !== '') aria-describedby="{{ $describedBy }}" @endif
        {{ $attributes->except(['class', 'id'])->merge([
            'class' => 'admin-form-input'.($hasError ? ' error' : '').($plate ? ' admin-plate-input' : ''),
        ]) }}>
</x-admin.form.field>
