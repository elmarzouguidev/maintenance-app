{{--
    Send a commercial document by e-mail: to the client's address, and to the extra addresses
    ticked here (posted as emails[{documentId}][]). $field is the name of the hidden field that
    carries the document uuid ("invoice" for invoices, "estimater" for estimates…).
--}}
@props(['id', 'title', 'action', 'field', 'documentUuid', 'documentId', 'client' => null])

<x-admin.modal :id="$id" :title="$title" :description="'Le document est envoyé à '.(optional($client)->email ?: 'l’adresse du client').'.'">
    <form id="{{ $id }}-form" method="POST" action="{{ $action }}">
        @csrf
        <input type="hidden" name="{{ $field }}" value="{{ $documentUuid }}">
        @if (optional($client)->emails?->isNotEmpty())
            <fieldset class="space-y-2">
                <legend class="admin-form-label">Envoyer aussi à</legend>
                @foreach ($client->emails as $email)
                    <label class="flex items-center gap-2 text-body">
                        <input type="checkbox" name="emails[{{ $documentId }}][]" value="{{ $email->email }}" class="admin-checkbox"> {{ $email->email }}
                    </label>
                @endforeach
            </fieldset>
        @endif
    </form>
    <x-slot:footer>
        <x-admin.button variant="secondary" onclick="this.closest('dialog').close()">Annuler</x-admin.button>
        <x-admin.button type="submit" form="{{ $id }}-form" icon="document">Envoyer</x-admin.button>
    </x-slot:footer>
</x-admin.modal>
