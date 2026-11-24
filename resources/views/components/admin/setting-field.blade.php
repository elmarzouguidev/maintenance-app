{{-- One settings field (text-like, textarea, select, file or toggle), on the kit form classes. --}}
@props([
    'type' => 'text',
    'name' => '',
    'label' => '',
    'value' => '',
    'placeholder' => '',
    'help' => '',
    'required' => false,
    'options' => [],
    'rows' => 3,
    'previewUrl' => null,
    'accept' => 'image/*',
])

@php
    $hasError = $errors->has($name);
    $describedBy = trim(($help ? $name.'-help ' : '').($hasError ? $name.'-error' : ''));
    $describedByAttribute = $describedBy !== '' ? 'aria-describedby="'.e($describedBy).'"' : '';
    $controlClasses = 'admin-form-input'.($hasError ? ' error' : '');
@endphp

<div class="min-w-0">
    @if ($label)
        <label for="{{ $name }}" class="admin-form-label">
            {{ $label }}
            @if ($required)
                <span class="text-status-danger" aria-hidden="true">*</span>
            @endif
        </label>
    @endif

    @if (in_array($type, ['text', 'email', 'password', 'url', 'number', 'time'], true))
        <input type="{{ $type }}" name="{{ $name }}" id="{{ $name }}"
            value="{{ old($name, $value) }}" placeholder="{{ $placeholder }}" {{ $required ? 'required' : '' }}
            @if ($hasError) aria-invalid="true" @endif {!! $describedByAttribute !!}
            class="{{ $controlClasses }}">
    @elseif ($type === 'textarea')
        <textarea name="{{ $name }}" id="{{ $name }}" rows="{{ $rows }}"
            placeholder="{{ $placeholder }}" {{ $required ? 'required' : '' }}
            @if ($hasError) aria-invalid="true" @endif {!! $describedByAttribute !!}
            class="{{ $controlClasses }} admin-form-textarea">{{ old($name, $value) }}</textarea>
    @elseif ($type === 'select')
        <select name="{{ $name }}" id="{{ $name }}" {{ $required ? 'required' : '' }}
            @if ($hasError) aria-invalid="true" @endif {!! $describedByAttribute !!}
            class="{{ $controlClasses }}">
            @foreach ($options as $key => $optionLabel)
                <option value="{{ $key }}" {{ old($name, $value) == $key ? 'selected' : '' }}>
                    {{ $optionLabel }}
                </option>
            @endforeach
        </select>
    @elseif ($type === 'file')
        {{-- Current image, then a live preview of the chosen file (display only: same input, saved on submit). --}}
        <div class="space-y-3" x-data="{ selected: null, fileName: '' }">
            <div class="flex flex-wrap items-end gap-4">
                @if ($previewUrl)
                    <figure>
                        <img src="{{ $previewUrl }}" alt="Image actuelle" class="h-20 w-auto rounded-control border border-line bg-surface-muted object-contain p-1">
                        <figcaption class="mt-1 text-caption text-fg-muted">Actuel</figcaption>
                    </figure>
                @endif
                <figure x-cloak x-show="selected">
                    <img :src="selected" alt="Aperçu du nouveau fichier" class="h-20 w-auto rounded-control border border-accent bg-surface-muted object-contain p-1">
                    <figcaption class="mt-1 text-caption text-fg-muted">Nouveau</figcaption>
                </figure>
            </div>
            <input type="file" name="{{ $name }}" id="{{ $name }}" accept="{{ $accept }}"
                @if ($hasError) aria-invalid="true" @endif {!! $describedByAttribute !!}
                x-on:change="const file = $event.target.files[0]; if (selected) { URL.revokeObjectURL(selected) } selected = file ? URL.createObjectURL(file) : null; fileName = file ? file.name : ''"
                class="admin-file-input">
            <p x-cloak x-show="fileName" class="text-caption text-fg-muted" aria-live="polite">Fichier choisi : <span class="text-fg" x-text="fileName"></span>. Il sera enregistré avec le formulaire.</p>
        </div>
    @elseif ($type === 'toggle' || $type === 'checkbox')
        <label class="relative inline-flex cursor-pointer items-center">
            <input type="checkbox" name="{{ $name }}" id="{{ $name }}" value="1"
                {{ old($name, $value) ? 'checked' : '' }} {!! $describedByAttribute !!} class="peer sr-only">
            <span class="admin-switch" aria-hidden="true"></span>
        </label>
    @endif

    @if ($help)
        <p id="{{ $name }}-help" class="admin-form-help">{{ $help }}</p>
    @endif

    @error($name)
        <p id="{{ $name }}-error" class="admin-form-error" role="alert">
            <x-admin.icon name="exclamation-circle" class="size-4" />
            <span>{{ $message }}</span>
        </p>
    @enderror
</div>
