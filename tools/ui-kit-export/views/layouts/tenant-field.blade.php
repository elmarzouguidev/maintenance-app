@extends('layouts.admin')

@section('title', 'Départ')
@section('hide-bottom-bar', '1')

@section('content')
    <div class="admin-field-screen mx-auto max-w-2xl space-y-6">
        <x-admin.page-header title="Départ · RES-2026-0148" description="Amal Benali · Dacia Sandero · 12345-أ-6"
            :breadcrumbs="[['label' => 'Réservations', 'href' => '#'], ['label' => 'RES-2026-0148', 'href' => '#'], ['label' => 'Départ']]" />

        <form method="POST" action="#" class="space-y-6">
            <section class="admin-card space-y-4 p-5" aria-labelledby="departure-vehicle">
                <h2 id="departure-vehicle" class="admin-section-title">Relevés du véhicule</h2>
                <x-admin.form-input name="pickup_mileage" label="Kilométrage au départ" value="24500" inputmode="numeric" required help="Dernier relevé : 24 390 km." />
                <x-admin.segmented-radio name="pickup_fuel_level" label="Niveau de carburant" :options="['EMPTY' => 'Vide', 'QUARTER' => '1/4', 'HALF' => '1/2', 'THREE_QUARTERS' => '3/4', 'FULL' => 'Plein']" value="FULL" required />
                <x-admin.date-picker name="actual_pickup_at" label="Heure de départ" value="2026-11-04 14:05" with-time native required />
            </section>

            <section class="admin-card space-y-4 p-5" aria-labelledby="departure-condition">
                <h2 id="departure-condition" class="admin-section-title">État du véhicule</h2>
                <x-admin.vehicle-condition-map name="pickup_vehicle_marks" :value="['front']" variant="checkout" />
                <x-admin.textarea name="pickup_vehicle_condition" label="Observations" rows="3" placeholder="Rayures, accessoires remis…" />
            </section>

            <div class="admin-action-bar">
                <div class="flex flex-col-reverse gap-3 sm:flex-row sm:justify-end">
                    <x-admin.button href="#" variant="secondary">Annuler</x-admin.button>
                    <x-admin.button type="submit" icon="departure">Confirmer le départ</x-admin.button>
                </div>
            </div>
        </form>
    </div>
@endsection
