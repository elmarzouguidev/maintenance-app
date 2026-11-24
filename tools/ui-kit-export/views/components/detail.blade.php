@extends('kit-export::gallery')

@section('demo')
    <section aria-labelledby="page-title" class="space-y-4">
        @include('kit-export::partials.demo-heading', ['id' => 'page-title', 'title' => 'Detail page with aside', 'text' => 'Sections on the white page separated by hairlines ("Encre" structure). Only key summaries (amounts, deposit) keep a bordered panel, in the aside. Label/value pairs go in a <dl> grid of x-admin.detail-item.'])
        <div class="admin-card p-5 sm:p-6">
{{-- snippet:page --}}
<x-admin.detail-page>
    <x-admin.detail-section title="Période et agences">
        <dl class="grid gap-4 sm:grid-cols-2">
            <x-admin.detail-item label="Départ réel"><x-admin.date value="2026-11-03 09:30" time /></x-admin.detail-item>
            <x-admin.detail-item label="Retour prévu"><x-admin.date value="2026-11-07" long /></x-admin.detail-item>
            <x-admin.detail-item label="Agence de départ">Rabat Centre</x-admin.detail-item>
            <x-admin.detail-item label="Agence de retour">Casablanca Maarif</x-admin.detail-item>
        </dl>
    </x-admin.detail-section>

    <x-admin.detail-section title="Client" description="Conducteur principal du contrat.">
        <x-slot:actions><x-admin.button href="#" variant="ghost" size="sm">Voir la fiche</x-admin.button></x-slot:actions>
        <dl class="grid gap-4 sm:grid-cols-2">
            <x-admin.detail-item label="Nom">Amal Benali</x-admin.detail-item>
            <x-admin.detail-item label="Téléphone"><span class="tabular-nums">06 12 34 56 78</span></x-admin.detail-item>
            <x-admin.detail-item label="Permis"><span class="tabular-nums">P-778899</span></x-admin.detail-item>
            <x-admin.detail-item label="Adresse">Non renseignée</x-admin.detail-item>
        </dl>
    </x-admin.detail-section>

    <x-admin.detail-section title="Paiements">
        <x-slot:actions><x-admin.button variant="secondary" size="sm" icon="plus">Encaisser</x-admin.button></x-slot:actions>
        <x-admin.table :headers="['Date', 'Type', 'Mode', 'Montant']">
            <tr><td><x-admin.date value="2026-11-03 09:15" time /></td><td>Avance</td><td>Espèces</td><td><x-admin.amount :value="1000" /></td></tr>
            <tr><td><x-admin.date value="2026-11-05 17:40" time /></td><td>Solde</td><td>Carte bancaire</td><td><x-admin.amount :value="450" /></td></tr>
        </x-admin.table>
    </x-admin.detail-section>

    <x-slot:aside>
        <div class="admin-card p-5">
            <h3 class="text-small font-semibold text-fg">Montants</h3>
            <dl class="mt-3 space-y-2 text-body">
                <div class="flex justify-between gap-4"><dt class="text-fg-muted">Total</dt><dd><x-admin.amount :value="2450" /></dd></div>
                <div class="flex justify-between gap-4"><dt class="text-fg-muted">Encaissé</dt><dd><x-admin.amount :value="1450" /></dd></div>
                <div class="flex justify-between gap-4 border-t border-line pt-2 font-semibold"><dt>Reste dû</dt><dd><x-admin.amount :value="1000" /></dd></div>
            </dl>
        </div>
        <div class="admin-card p-5">
            <div class="flex items-center justify-between gap-3">
                <h3 class="text-small font-semibold text-fg">Caution</h3>
                <x-admin.status-badge variant="info" label="Sécurisée" />
            </div>
            <p class="mt-2 text-section-title font-semibold"><x-admin.amount :value="3000" /></p>
            <p class="mt-1 text-caption text-fg-muted">Le retour du véhicule ne libère jamais la caution.</p>
        </div>
    </x-slot:aside>
</x-admin.detail-page>
{{-- /snippet --}}
        </div>
        @include('kit-export::partials.snippet', ['name' => 'page'])
    </section>
@endsection
