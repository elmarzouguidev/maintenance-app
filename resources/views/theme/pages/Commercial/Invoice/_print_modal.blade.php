{{-- Download the invoice PDF, with or without the company header (public.show.invoice). --}}
<x-admin.modal id="print-invoice" :title="'Télécharger la facture '.$invoice->full_number" description="Choisissez le modèle du PDF.">
    <div class="flex flex-col gap-3 sm:flex-row">
        <x-admin.button href="{{ route('public.show.invoice', [$invoice->uuid, 'has_header' => true]) }}" target="_blank" rel="noopener" icon="printer">Avec en-tête</x-admin.button>
        <x-admin.button href="{{ route('public.show.invoice', [$invoice->uuid, 'has_header' => false]) }}" target="_blank" rel="noopener" variant="secondary" icon="printer">Sans en-tête</x-admin.button>
    </div>
    <x-slot:footer>
        <x-admin.button variant="secondary" onclick="this.closest('dialog').close()">Fermer</x-admin.button>
    </x-slot:footer>
</x-admin.modal>
