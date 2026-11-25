{{--
    A ticket's history (K2): its status changes, oldest first, with who made them and the note
    recorded. The page was empty before: the route parameter did not match the controller's
    Ticket argument (BL-86).
--}}
@extends('layouts.admin')

@section('title', 'Historique du ticket '.$ticket->code)

@section('content')
    @php($authors = \App\Models\User::query()->whereIn('id', $ticket->statuses->pluck('pivot.user_id')->filter()->unique())->get(['id', 'nom', 'prenom'])->keyBy('id'))

    <div class="mx-auto w-full max-w-3xl space-y-6">
        <x-admin.page-header :title="'Historique du ticket '.$ticket->code" :description="$ticket->article.' · '.optional($ticket->client)->entreprise"
            :breadcrumbs="[['label' => 'Tickets', 'href' => route('admin:tickets.list')], ['label' => $ticket->code, 'href' => $ticket->url], ['label' => 'Historique']]" />

        <section aria-labelledby="ticket-history-title" class="admin-card">
            <div class="admin-card-header">
                <h2 id="ticket-history-title" class="admin-section-title">Étapes</h2>
            </div>
            <div class="admin-card-body">
                @if ($ticket->statuses->isEmpty())
                    <p class="text-small text-fg-muted">Aucun historique.</p>
                @else
                    <ol class="divide-y divide-line">
                        @foreach ($ticket->statuses as $status)
                            <li class="space-y-1 py-3">
                                <div class="flex flex-col gap-1 sm:flex-row sm:items-center sm:justify-between">
                                    <span class="font-medium text-fg">{{ $status->name }}</span>
                                    <span class="text-caption text-fg-muted"><x-admin.date :value="$status->pivot->start_at ?? $status->pivot->created_at" time /></span>
                                </div>
                                @if ($status->pivot->user_id && isset($authors[$status->pivot->user_id]))
                                    <p class="text-caption text-fg-muted">Par {{ $authors[$status->pivot->user_id]->full_name }}</p>
                                @endif
                                @if ($status->pivot->description)
                                    <p class="whitespace-pre-line text-small text-fg">{{ $status->pivot->description }}</p>
                                @endif
                            </li>
                        @endforeach
                    </ol>
                @endif
            </div>
        </section>
    </div>
@endsection
