@extends('kit-export::gallery')

@php
    $iconNames = ['home', 'menu', 'close', 'search', 'bell', 'inbox', 'chevron-down', 'chevron-up', 'chevron-left', 'chevron-right', 'chevron-up-down', 'arrow-left', 'plus', 'minus', 'check', 'check-circle', 'x-circle', 'pencil', 'trash', 'eye', 'ellipsis', 'filter', 'squares', 'list', 'chart', 'calendar', 'clock', 'users', 'user', 'document', 'card', 'banknotes', 'key', 'shield', 'arrows', 'warning', 'exclamation-circle', 'info', 'sliders', 'departure', 'return', 'pin', 'download', 'upload', 'printer', 'photo', 'truck', 'wrench', 'cog', 'building', 'logout'];
@endphp

@section('demo')
    <section aria-labelledby="set-title" class="space-y-4">
        @include('kit-export::partials.demo-heading', ['id' => 'set-title', 'title' => 'The icon set', 'text' => 'Heroicons v2 outline (MIT), inlined in x-admin.icon: no icon package. 20 px by default (size-5), currentColor. An unknown name fails loudly.'])
        <ul class="admin-card grid grid-cols-2 gap-3 p-5 sm:grid-cols-3 lg:grid-cols-6">
            @foreach ($iconNames as $iconName)
                <li class="flex min-w-0 items-center gap-2 text-small text-fg">
                    <x-admin.icon :name="$iconName" class="size-5 text-fg-muted" />
                    <span class="truncate font-mono text-caption">{{ $iconName }}</span>
                </li>
            @endforeach
        </ul>
    </section>

    <section aria-labelledby="use-title" class="space-y-4">
        @include('kit-export::partials.demo-heading', ['id' => 'use-title', 'title' => 'Decorative and labelled', 'text' => 'Icons are decorative (aria-hidden) next to text. Pass a label only when the icon alone carries meaning; an icon-only button uses x-admin.icon-button, whose label is required.'])
        <div class="admin-card flex flex-wrap items-center gap-6 p-5">
{{-- snippet:use --}}
<span class="inline-flex items-center gap-2 text-body text-fg">
    <x-admin.icon name="calendar" class="size-5 text-fg-muted" /> 3 réservations aujourd’hui
</span>
<x-admin.icon name="warning" label="Retour en retard" class="size-5 text-status-danger" />
<x-admin.icon-button icon="pencil" label="Modifier la réservation" />
{{-- /snippet --}}
        </div>
        @include('kit-export::partials.snippet', ['name' => 'use'])
    </section>
@endsection
