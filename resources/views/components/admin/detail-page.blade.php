{{--
    Detail page body: main column of x-admin.detail-section blocks, plus an optional aside
    for key summaries (amounts, deposit), which keep a bordered panel.
--}}
<div {{ $attributes->class('grid gap-8 lg:grid-cols-3') }}>
    <div class="min-w-0 {{ isset($aside) ? 'lg:col-span-2' : 'lg:col-span-3' }}">
        {{ $slot }}
    </div>
    @isset($aside)
        <aside class="min-w-0 space-y-6">{{ $aside }}</aside>
    @endisset
</div>
