{{--
    Internal wrapper shared by the kit form controls: label, control slot, help and error,
    wired with ids for aria-describedby. Not used directly by views.
--}}
@props(['id', 'name', 'label' => null, 'required' => false, 'help' => null, 'labelFor' => null])

<div {{ $attributes->class(['min-w-0']) }}>
    @if ($label)
        <label for="{{ $labelFor ?? $id }}" id="{{ $id }}-label" class="admin-form-label">
            {{ $label }}
            @if ($required)
                <span class="text-status-danger" aria-hidden="true">*</span>
            @endif
        </label>
    @endif

    {{ $slot }}

    @if ($help)
        <p id="{{ $id }}-help" class="admin-form-help">{{ $help }}</p>
    @endif

    @error($name)
        <p id="{{ $id }}-error" class="admin-form-error" role="alert">
            <x-admin.icon name="exclamation-circle" class="size-4" />
            <span>{{ $message }}</span>
        </p>
    @enderror
</div>
