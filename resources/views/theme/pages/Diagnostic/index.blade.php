{{--
    Technician board (K2): diagnostic.index for a Technicien (their tickets) or a SuperTechnicien
    (every assigned ticket, with the technician column), grouped by step as before; kit tabs and
    tables replacing the Bootstrap nav-tabs and DataTables.
--}}
@extends('layouts.admin')

@section('title', 'Diagnostics')

@section('content')
    @php
        $tabs = [
            ['key' => 'ouvert', 'label' => 'Diagnostics ouverts', 'url' => 'diagnose_url', 'action' => 'Diagnostiquer'],
            ['key' => 'en-attent-de-devis', 'label' => 'En attente de devis', 'url' => 'diagnose_url', 'action' => 'Voir le diagnostic'],
            ['key' => 'en-attent-de-bc', 'label' => 'En attente de BC', 'url' => 'ticket_url', 'action' => null],
            ['key' => 'a-preparer', 'label' => 'À réparer', 'url' => 'repear_url', 'action' => 'Réparer'],
            ['key' => 'encours-de-reparation', 'label' => 'En réparation', 'url' => 'repear_url', 'action' => 'Continuer'],
            ['key' => 'pret-a-etre-livre', 'label' => 'Réparés', 'url' => 'repear_url', 'action' => 'Voir la réparation'],
            ['key' => 'annuler', 'label' => 'Annulés par l’administration', 'url' => 'diagnose_url', 'action' => 'Voir'],
        ];
    @endphp

    <div class="space-y-6">
        <x-admin.page-header title="Diagnostics" description="Vos tickets, par étape : diagnostic, devis, réparation." />

        <x-diagnostic.board :tickets="$tickets" :tabs="$tabs" :technician="auth()->user()->hasRole('SuperTechnicien') ? 'me' : null" />
    </div>
@endsection
