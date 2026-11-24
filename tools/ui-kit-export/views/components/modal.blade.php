@extends('kit-export::gallery')

@section('demo')
    <section aria-labelledby="confirm-title" class="space-y-4">
        @include('kit-export::partials.demo-heading', ['id' => 'confirm-title', 'title' => 'Destructive confirmation', 'text' => 'A native <dialog>: the browser traps focus, closes on Escape and returns focus to the trigger. The destructive action is the danger button; the safe choice comes first.'])
        <div class="admin-card p-5">
{{-- snippet:confirm --}}
<x-admin.button variant="danger" icon="x-circle" onclick="document.getElementById('cancel-reservation').showModal()">Annuler la réservation</x-admin.button>

<x-admin.modal id="cancel-reservation" title="Annuler la réservation ?" description="Le véhicule redevient disponible sur la période. Les paiements restent enregistrés.">
    <x-slot:footer>
        <x-admin.button variant="secondary" onclick="this.closest('dialog').close()">Garder la réservation</x-admin.button>
        <x-admin.button variant="danger" onclick="this.closest('dialog').close()">Annuler la réservation</x-admin.button>
    </x-slot:footer>
</x-admin.modal>
{{-- /snippet --}}
        </div>
        @include('kit-export::partials.snippet', ['name' => 'confirm'])
    </section>

    <section aria-labelledby="form-title" class="space-y-4">
        @include('kit-export::partials.demo-heading', ['id' => 'form-title', 'title' => 'Form in a dialog', 'text' => 'Short forms only (one to three fields). Use method="dialog" on a cancel form, or close() from a button.'])
        <div class="admin-card p-5">
{{-- snippet:form --}}
<x-admin.button variant="secondary" icon="pencil" onclick="document.getElementById('correct-mileage').showModal()">Corriger le kilométrage</x-admin.button>

<x-admin.modal id="correct-mileage" title="Corriger le kilométrage" description="La correction est historisée avec son motif.">
    <form method="POST" action="#" id="correct-mileage-form" class="grid gap-4">
        <x-admin.form-input name="corrected_mileage" label="Nouveau kilométrage" value="24650" inputmode="numeric" required />
        <x-admin.textarea name="correction_reason" label="Motif" rows="2" required />
    </form>
    <x-slot:footer>
        <x-admin.button variant="secondary" onclick="this.closest('dialog').close()">Annuler</x-admin.button>
        <x-admin.button type="submit" form="correct-mileage-form">Enregistrer la correction</x-admin.button>
    </x-slot:footer>
</x-admin.modal>
{{-- /snippet --}}
        </div>
        @include('kit-export::partials.snippet', ['name' => 'form'])
    </section>
@endsection
