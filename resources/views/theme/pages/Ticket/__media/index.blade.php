{{--
    A ticket's photos (K2): the photos with their details and a delete action each
    (tickets.media.delete), and the upload form (tickets.attachements, several images).
--}}
@extends('layouts.admin')

@section('title', 'Photos du ticket '.$ticket->code)

@section('content')
    @php($photos = $ticket->getMedia('tickets-images'))

    <div class="mx-auto w-full max-w-5xl space-y-6">
        <x-admin.page-header :title="'Photos du ticket '.$ticket->code" :description="$ticket->article.' · '.$ticket->media_count.' fichier(s)'"
            :breadcrumbs="[['label' => 'Tickets', 'href' => route('admin:tickets.list')], ['label' => $ticket->code, 'href' => $ticket->url], ['label' => 'Photos']]" />

        <x-messages.errors />

        <section aria-labelledby="ticket-upload-title" class="admin-card space-y-4 p-5 sm:p-6">
            <h2 id="ticket-upload-title" class="admin-section-title">Ajouter des photos</h2>
            <form method="POST" action="{{ route('admin:tickets.attachements', $ticket->uuid) }}" enctype="multipart/form-data" class="space-y-4">
                @csrf
                <x-admin.file-upload name="photos[]" label="Photos" accept="image/png,image/jpeg" multiple required />
                <div class="flex justify-end">
                    <x-admin.button type="submit" icon="upload">Ajouter les photos</x-admin.button>
                </div>
            </form>
        </section>

        <section aria-labelledby="ticket-photos-title" class="space-y-4">
            <h2 id="ticket-photos-title" class="admin-section-title">Photos</h2>
            @if ($photos->isEmpty())
                <div class="admin-card">
                    <x-admin.empty-state icon="photo" title="Aucune photo" description="Ce ticket n’a pas encore de photo." />
                </div>
            @else
                <ul class="grid gap-4 sm:grid-cols-2 lg:grid-cols-3">
                    @foreach ($photos as $photo)
                        <li class="admin-card overflow-hidden">
                            <a href="{{ $photo->getFullUrl() }}" target="_blank" rel="noopener" class="block">
                                <img src="{{ $photo->getFullUrl() }}" alt="{{ $photo->name }}" class="aspect-video w-full object-cover" loading="lazy">
                            </a>
                            <div class="flex items-start justify-between gap-3 p-4">
                                <div class="min-w-0">
                                    <p class="truncate text-small font-medium text-fg">{{ $photo->name }}</p>
                                    <p class="text-caption text-fg-muted">{{ $photo->mime_type }} · {{ $photo->human_readable_size }}</p>
                                </div>
                                <x-admin.delete-action :action="route('admin:tickets.media.delete', ['ticket' => $ticket->uuid, 'mediaId' => $photo->id])"
                                    :confirm="'Supprimer la photo '.$photo->name.' ?'" />
                            </div>
                        </li>
                    @endforeach
                </ul>
            @endif
        </section>

        <div>
            <x-admin.button href="{{ $ticket->url }}" variant="secondary" icon="arrow-left">Retour au ticket</x-admin.button>
        </div>
    </div>
@endsection
