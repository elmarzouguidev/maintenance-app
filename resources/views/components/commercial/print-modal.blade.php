{{--
    Download a commercial document as PDF, with or without the company header.
    <x-commercial.print-modal id="print-invoice" :title="'Télécharger la facture …'" route="public.show.invoice" :document="$invoice->uuid" />
--}}
@props(['id', 'title', 'route', 'document'])

<x-admin.modal :id="$id" :title="$title" description="Choisissez le modèle du PDF.">
    <div class="flex flex-col gap-3 sm:flex-row">
        <x-admin.button href="{{ route($route, [$document, 'has_header' => true]) }}" target="_blank" rel="noopener" icon="printer">Avec en-tête</x-admin.button>
        <x-admin.button href="{{ route($route, [$document, 'has_header' => false]) }}" target="_blank" rel="noopener" variant="secondary" icon="printer">Sans en-tête</x-admin.button>
    </div>
    <x-slot:footer>
        <x-admin.button variant="secondary" onclick="this.closest('dialog').close()">Fermer</x-admin.button>
    </x-slot:footer>
</x-admin.modal>
