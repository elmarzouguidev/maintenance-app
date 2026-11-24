{{-- Native select. Space fields with gap on the parent. --}}
@props(['label' => null, 'name', 'options' => [], 'value' => '', 'required' => false, 'placeholder' => 'Sélectionner une option', 'help' => null])

@php
    $id = $attributes->get('id', $name);
    $hasError = $errors->has($name);
    $describedBy = trim(($help ? $id.'-help ' : '').($hasError ? $id.'-error' : ''));
@endphp

<x-admin.form.field :id="$id" :name="$name" :label="$label" :required="$required" :help="$help" :class="$attributes->get('class')">
    <select id="{{ $id }}"
        name="{{ $name }}"
        @if ($required) required @endif
        @if ($hasError) aria-invalid="true" @endif
        @if ($describedBy !== '') aria-describedby="{{ $describedBy }}" @endif
        {{ $attributes->except(['class', 'id'])->merge([
            'class' => 'admin-form-input'.($hasError ? ' error' : ''),
        ]) }}>

        @if ($placeholder)
            <option value="">{{ $placeholder }}</option>
        @endif

        @if (is_array($options) && count($options) > 0)
            @foreach ($options as $optionValue => $optionLabel)
                <option value="{{ $optionValue }}" @selected(old($name, $value) == $optionValue)>
                    {{ $optionLabel }}
                </option>
            @endforeach
        @else
            {{ $slot }}
        @endif
    </select>
</x-admin.form.field>
