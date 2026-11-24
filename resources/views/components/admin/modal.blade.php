@props(['id', 'title', 'description' => null])

{{--
    Native <dialog>: open with document.getElementById('{{ $id }}').showModal().
    The browser traps focus, closes on Escape and returns focus to the trigger.
--}}
<dialog id="{{ $id }}" aria-labelledby="{{ $id }}-title" @if ($description) aria-describedby="{{ $id }}-description" @endif {{ $attributes->class('admin-dialog') }}>
    <div class="p-5">
        <h2 id="{{ $id }}-title" class="text-section-title font-semibold text-fg">{{ $title }}</h2>
        @if ($description)
            <p id="{{ $id }}-description" class="mt-1 text-body text-fg-muted">{{ $description }}</p>
        @endif
        <div class="mt-4">{{ $slot }}</div>
        @isset($footer)
            <div class="mt-5 flex flex-col-reverse gap-2 sm:flex-row sm:justify-end">{{ $footer }}</div>
        @endisset
    </div>
</dialog>
