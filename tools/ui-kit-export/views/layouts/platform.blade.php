@extends('layouts.platform')

@section('title', 'Organisations')

@section('content')
    <div class="space-y-6">
        <x-admin.page-header title="Organisations" description="Toutes les organisations clientes, leur abonnement et leur état.">
            <x-slot:actions>
                <x-admin.button href="#" icon="plus">Nouvelle organisation</x-admin.button>
            </x-slot:actions>
        </x-admin.page-header>

        <x-admin.table :headers="['Organisation', 'Code', 'Offre', 'Création', 'État']">
            <tr><td class="font-medium">Atlas Location</td><td class="tabular-nums">ORG-ATLAS</td><td>Business</td><td><x-admin.date value="2026-10-01" /></td><td><x-admin.status-badge variant="success" label="Active" /></td></tr>
            <tr><td class="font-medium">Sud Cars</td><td class="tabular-nums">ORG-SUD</td><td>Essentiel</td><td><x-admin.date value="2026-10-02" /></td><td><x-admin.status-badge variant="neutral" label="Suspendue" /></td></tr>
        </x-admin.table>
    </div>
@endsection
