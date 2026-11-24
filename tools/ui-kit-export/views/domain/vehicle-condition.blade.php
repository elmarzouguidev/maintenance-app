@extends('kit-export::gallery')

@section('demo')
    <section aria-labelledby="checkout-title" class="space-y-4">
        @include('kit-export::partials.demo-heading', ['id' => 'checkout-title', 'title' => 'Check-out, zones already marked', 'text' => 'Optional domain component. The top view takes pointer input; the same zones are labelled toggle buttons (aria-pressed) for keyboard and screen-reader users. Selected zones post as hidden inputs name[].'])
        <div class="admin-card admin-field-screen p-5 sm:p-6">
{{-- snippet:checkout --}}
<x-admin.vehicle-condition-map name="pickup_vehicle_marks" label="État du véhicule au départ" :value="['front', 'left_side']" variant="checkout" />
{{-- /snippet --}}
        </div>
        @include('kit-export::partials.snippet', ['name' => 'checkout'])
    </section>

    <section aria-labelledby="checkin-title" class="space-y-4">
        @include('kit-export::partials.demo-heading', ['id' => 'checkin-title', 'title' => 'Check-in, nothing marked'])
        <div class="admin-card admin-field-screen p-5 sm:p-6">
{{-- snippet:checkin --}}
<x-admin.vehicle-condition-map name="return_vehicle_marks" label="État du véhicule au retour" variant="checkin" />
{{-- /snippet --}}
        </div>
        @include('kit-export::partials.snippet', ['name' => 'checkin'])
    </section>

    <section aria-labelledby="plate-title" class="space-y-4">
        @include('kit-export::partials.demo-heading', ['id' => 'plate-title', 'title' => 'Plate input', 'text' => 'plate keeps mixed Latin and Arabic plates in typed order while typing; the submitted value is unchanged.'])
        <div class="admin-card max-w-md p-5">
{{-- snippet:plate --}}
<x-admin.form-input name="registration_number" label="Immatriculation" value="12345-أ-6" plate required />
{{-- /snippet --}}
        </div>
        @include('kit-export::partials.snippet', ['name' => 'plate'])
    </section>
@endsection
