@extends('kit-export::gallery')

@section('demo')
    <section aria-labelledby="types-title" class="space-y-4">
        @include('kit-export::partials.demo-heading', ['id' => 'types-title', 'title' => 'Date, date and time, time only', 'text' => 'The submitted value is the hidden input: "Y-m-d", "Y-m-d H:i" (with-time) or "H:i" (time-only). Keyboard: arrows by day and week, Home/End, PageUp/PageDown by month, Enter chooses, Escape closes and returns focus.'])
        <div class="admin-card p-5 sm:p-6">
{{-- snippet:types --}}
<div class="grid gap-4 md:grid-cols-2">
    <x-admin.date-picker name="pickup_date" label="Date de départ" value="2026-11-03" />
    <x-admin.date-picker name="actual_pickup_at" label="Départ réel" value="2026-11-03 09:30" with-time />
    <x-admin.date-picker name="opening_time" label="Heure d’ouverture" value="08:30" time-only />
    <x-admin.date-picker name="due_date" label="Échéance (dans les 30 jours)" min-date="2026-11-04" max-date="2026-12-04" placeholder="Choisir une date" />
</div>
{{-- /snippet --}}
        </div>
        @include('kit-export::partials.snippet', ['name' => 'types'])
    </section>

    <section aria-labelledby="states-title" class="space-y-4">
        @include('kit-export::partials.demo-heading', ['id' => 'states-title', 'title' => 'Required, error and native on phones', 'text' => 'native: below 768 px a native date input replaces the custom trigger and writes the same hidden value (phone-first screens).'])
        <div class="admin-card p-5 sm:p-6">
{{-- snippet:states --}}
<div class="grid gap-4 md:grid-cols-2">
    <x-admin.date-picker name="error_return_date" label="Date de retour" required />
    <x-admin.date-picker name="return_at" label="Retour (natif sur téléphone)" value="2026-11-07 18:00" with-time native />
</div>
{{-- /snippet --}}
        </div>
        @include('kit-export::partials.snippet', ['name' => 'states'])
    </section>
@endsection
