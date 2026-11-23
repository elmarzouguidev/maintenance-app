{{-- Session flash messages (success, status, error) and validation errors. Tailwind replacement for theme/layouts/_parts/__messages. --}}
@php
    $validationErrors = isset($errors) ? $errors->all() : [];
@endphp

@if (session('success') || session('status') || session('error') || count($validationErrors))
    <div {{ $attributes->class('mb-4 space-y-3') }}>
        @if (session('success'))
            <x-alert variant="success" icon="mdi mdi-check-all" dismissible>{{ session('success') }}</x-alert>
        @endif

        @if (session('status'))
            <x-alert variant="success" icon="mdi mdi-check-all" dismissible>{{ session('status') }}</x-alert>
        @endif

        @if (session('error'))
            <x-alert variant="danger" icon="mdi mdi-block-helper" dismissible>{{ session('error') }}</x-alert>
        @endif

        @foreach ($validationErrors as $validationError)
            <x-alert variant="danger">{{ $validationError }}</x-alert>
        @endforeach
    </div>
@endif
