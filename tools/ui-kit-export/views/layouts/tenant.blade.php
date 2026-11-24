@extends('layouts.admin')

@section('title', 'Réservations')

@section('content')
    <div class="space-y-6">
        <x-admin.page-header title="Réservations" description="Départs, retours et réservations à venir de l’agence.">
            <x-slot:actions>
                <x-admin.button href="#" variant="secondary" icon="calendar">Calendrier</x-admin.button>
                <x-admin.button href="#" icon="plus">Nouvelle réservation</x-admin.button>
            </x-slot:actions>
        </x-admin.page-header>

        <x-admin.stats-grid>
            <x-admin.stats-card title="Départs du jour" value="6" icon="departure" href="#" />
            <x-admin.stats-card title="Retours du jour" value="4" icon="return" variant="info" href="#" />
            <x-admin.stats-card title="Retours en retard" value="1" icon="warning" variant="danger" href="#" />
            <x-admin.stats-card title="En attente" value="3" icon="clock" variant="warning" href="#" />
        </x-admin.stats-grid>

        <x-admin.table :headers="['Réservation', 'Client', 'Véhicule', 'Départ', 'Montant', 'Statut']">
            <tr><td class="font-medium">RES-2026-0148</td><td>Amal Benali</td><td><x-admin.plate value="12345-أ-6" /></td><td><x-admin.date value="2026-11-04 14:00" time /></td><td><x-admin.amount :value="2450" /></td><td><x-admin.status-badge variant="info" label="Confirmée" /></td></tr>
            <tr><td class="font-medium">RES-2026-0145</td><td>Youssef El Idrissi</td><td><x-admin.plate value="48213-ب-26" /></td><td><x-admin.date value="2026-10-30 09:00" time /></td><td><x-admin.amount :value="1750" /></td><td><x-admin.status-badge variant="danger" label="En retard" /></td></tr>
            <tr><td class="font-medium">RES-2026-0151</td><td>Atlas Voyages SARL</td><td><x-admin.plate value="77812-د-1" /></td><td><x-admin.date value="2026-11-06 08:30" time /></td><td><x-admin.amount :value="5600" /></td><td><x-admin.status-badge variant="warning" label="En attente" /></td></tr>
        </x-admin.table>
    </div>
@endsection
