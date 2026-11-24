@extends('kit-export::gallery')

@section('demo')
    <section aria-labelledby="list-title" class="space-y-4">
        @include('kit-export::partials.demo-heading', ['id' => 'list-title', 'title' => 'List table', 'text' => 'Compact rows, hairlines, no uppercase headers. Cells never wrap; the table scrolls horizontally inside its panel. Status, amount, plate and date cells use their components.'])
{{-- snippet:list --}}
<x-admin.table :headers="['Réservation', 'Client', 'Véhicule', 'Départ', 'Montant', 'Statut', '']">
    <tr>
        <td class="font-medium"><a href="#" class="hover:underline">RES-2026-0148</a></td>
        <td>Amal Benali</td>
        <td><x-admin.plate value="12345-أ-6" /></td>
        <td><x-admin.date value="2026-11-03 09:30" time /></td>
        <td><x-admin.amount :value="2450" /></td>
        <td><x-admin.status-badge variant="info" label="En cours" /></td>
        <td class="text-end"><x-admin.icon-button icon="eye" label="Voir RES-2026-0148" href="#" size="sm" /></td>
    </tr>
    <tr>
        <td class="font-medium"><a href="#" class="hover:underline">RES-2026-0149</a></td>
        <td>Youssef El Idrissi</td>
        <td><x-admin.plate value="48213-ب-26" /></td>
        <td><x-admin.date value="2026-11-04 10:00" time /></td>
        <td><x-admin.amount :value="1250.5" /></td>
        <td><x-admin.status-badge variant="warning" label="En attente" /></td>
        <td class="text-end"><x-admin.icon-button icon="eye" label="Voir RES-2026-0149" href="#" size="sm" /></td>
    </tr>
    <tr>
        <td class="font-medium"><a href="#" class="hover:underline">RES-2026-0150</a></td>
        <td>Atlas Voyages SARL</td>
        <td><x-admin.plate :value="null" /></td>
        <td><x-admin.date value="2026-11-05 08:00" time /></td>
        <td><x-admin.amount :value="18400" /></td>
        <td><x-admin.status-badge variant="neutral" label="Annulée" /></td>
        <td class="text-end"><x-admin.icon-button icon="eye" label="Voir RES-2026-0150" href="#" size="sm" /></td>
    </tr>
</x-admin.table>
{{-- /snippet --}}
        @include('kit-export::partials.snippet', ['name' => 'list'])
    </section>

    <section aria-labelledby="empty-title" class="space-y-4">
        @include('kit-export::partials.demo-heading', ['id' => 'empty-title', 'title' => 'Empty table', 'text' => 'Inside a table, an empty row spans every column. For a whole empty list, prefer x-admin.empty-state.'])
{{-- snippet:empty --}}
<x-admin.table :headers="['Date', 'Type', 'Mode', 'Montant']">
    <tr>
        <td colspan="4" class="py-8 text-center text-fg-muted">Aucun paiement enregistré.</td>
    </tr>
</x-admin.table>
{{-- /snippet --}}
        @include('kit-export::partials.snippet', ['name' => 'empty'])
    </section>
@endsection
