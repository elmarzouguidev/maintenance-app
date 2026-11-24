{{--
    Segmented choice: native radios in a fieldset/legend, so arrow keys move the choice and the
    form posts name=value exactly like a select. Nothing is checked unless value/old() matches:
    an unchecked group posts nothing, which the server treats like an empty select (pinned in
    RentalExecutionFormContractTest).
--}}
@props(['name', 'label', 'options' => [], 'value' => null, 'required' => false, 'help' => null])

@php
    $id = str($name)->replace(['[', ']', '.'], '_')->slug('_')->toString();
    $selected = (string) old($name, $value);
    $hasError = $errors->has($name);
    $describedBy = trim(($help ? $id.'-help ' : '').($hasError ? $id.'-error' : ''));
@endphp

<fieldset @if ($describedBy !== '') aria-describedby="{{ $describedBy }}" @endif {{ $attributes->class('min-w-0') }}>
    <legend class="admin-form-label">
        {{ $label }}
        @if ($required)
            <span class="text-status-danger" aria-hidden="true">*</span>
        @endif
    </legend>

    <div @class(['admin-segmented', 'error' => $hasError])>
        @foreach ($options as $optionValue => $optionLabel)
            <label class="admin-segmented-option">
                <input type="radio" name="{{ $name }}" value="{{ $optionValue }}" class="sr-only"
                    @checked($selected !== '' && $selected === (string) $optionValue)
                    @if ($required) required @endif
                    @if ($hasError) aria-invalid="true" @endif>
                <span>{{ $optionLabel }}</span>
            </label>
        @endforeach
    </div>

    @if ($help)
        <p id="{{ $id }}-help" class="admin-form-help">{{ $help }}</p>
    @endif

    @error($name)
        <p id="{{ $id }}-error" class="admin-form-error" role="alert">
            <x-admin.icon name="exclamation-circle" class="size-4" />
            <span>{{ $message }}</span>
        </p>
    @enderror
</fieldset>
