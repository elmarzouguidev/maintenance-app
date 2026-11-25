{{-- Send the invoice by e-mail (invoices.send): to the client's address, and to the extra addresses ticked here. --}}
<x-admin.modal id="send-invoice" :title="'Envoyer la facture '.$invoice->full_number"
    :description="'La facture est envoyée à '.(optional($invoice->client)->email ?: 'l’adresse du client').'.'">
    <form id="send-invoice-form" method="POST" action="{{ route('commercial:invoices.send') }}">
        @csrf
        <input type="hidden" name="invoice" value="{{ $invoice->uuid }}">
        @if (optional($invoice->client)->emails?->isNotEmpty())
            <fieldset class="space-y-2">
                <legend class="admin-form-label">Envoyer aussi à</legend>
                @foreach ($invoice->client->emails as $email)
                    <label class="flex items-center gap-2 text-body">
                        <input type="checkbox" name="emails[{{ $invoice->id }}][]" value="{{ $email->email }}" class="admin-checkbox"> {{ $email->email }}
                    </label>
                @endforeach
            </fieldset>
        @endif
    </form>
    <x-slot:footer>
        <x-admin.button variant="secondary" onclick="this.closest('dialog').close()">Annuler</x-admin.button>
        <x-admin.button type="submit" form="send-invoice-form" icon="document">Envoyer</x-admin.button>
    </x-slot:footer>
</x-admin.modal>
