{{--
    Rich text field for the HTML the ticket pages store (descriptions, diagnostics, reports):
    the self-hosted TinyMCE 5 (public/assets/libs/tinymce) on a kit field, with a short
    toolbar. The textarea keeps the field name, so the forms post the same HTML as before.
    "required" is shown on the label only: TinyMCE hides the textarea, so the browser could not
    focus it to report a missing value; the form request checks it.
--}}
@props(['label' => null, 'name', 'value' => '', 'required' => false, 'rows' => 8, 'help' => null])

@php
    $id = $attributes->get('id', str($name)->replace(['[', ']', '.'], '_')->toString());
    $hasError = $errors->has($name);
@endphp

<x-admin.form.field :id="$id" :name="$name" :label="$label" :required="$required" :help="$help" :class="$attributes->get('class')">
    <textarea id="{{ $id }}" name="{{ $name }}" rows="{{ $rows }}" class="admin-form-input min-h-40 py-2{{ $hasError ? ' error' : '' }}"
        @if ($hasError) aria-invalid="true" @endif
        x-data
        x-init="window.tinymce?.init({
            target: $el,
            height: 320,
            menubar: false,
            branding: false,
            plugins: 'lists link autolink paste',
            toolbar: 'undo redo | bold italic underline | bullist numlist | link | removeformat',
            setup: (editor) => editor.on('change input', () => editor.save()),
        })">{{ old($name, $value) }}</textarea>
</x-admin.form.field>

@once
    @push('scripts')
        <script src="{{ asset('assets/libs/tinymce/tinymce.min.js') }}"></script>
    @endpush
@endonce
