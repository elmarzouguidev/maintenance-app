{{-- Textarea. Space fields with gap on the parent. --}}
@props(['label' => null, 'name', 'value' => '', 'required' => false, 'placeholder' => '', 'rows' => 4, 'help' => null])

@php
    $id = $attributes->get('id', $name);
    $hasError = $errors->has($name);
    $describedBy = trim(($help ? $id.'-help ' : '').($hasError ? $id.'-error' : ''));
@endphp

<x-admin.form.field :id="$id" :name="$name" :label="$label" :required="$required" :help="$help" :class="$attributes->get('class')">
    <textarea id="{{ $id }}"
        name="{{ $name }}"
        rows="{{ $rows }}"
        placeholder="{{ $placeholder }}"
        @if ($required) required @endif
        @if ($hasError) aria-invalid="true" @endif
        @if ($describedBy !== '') aria-describedby="{{ $describedBy }}" @endif
        {{ $attributes->except(['class', 'id'])->merge([
            'class' => 'admin-form-input admin-form-textarea'.($hasError ? ' error' : ''),
        ]) }}>{{ old($name, $value) }}</textarea>
</x-admin.form.field>
