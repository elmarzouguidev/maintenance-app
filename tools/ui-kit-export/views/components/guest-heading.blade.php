@extends('kit-export::gallery')

@section('demo')
    <section aria-labelledby="full-title" class="space-y-4">
        @include('kit-export::partials.demo-heading', ['id' => 'full-title', 'title' => 'With logo, eyebrow and description', 'text' => 'Guest layout only (sign-in, password reset, two-factor, onboarding). The logo comes from the brand config.'])
        <div class="admin-card mx-auto max-w-md p-6 sm:p-8">
{{-- snippet:full --}}
<x-admin.guest-heading eyebrow="Espace agence" title="Connexion" description="Saisissez vos identifiants pour accéder à votre espace." />
{{-- /snippet --}}
        </div>
        @include('kit-export::partials.snippet', ['name' => 'full'])
    </section>

    <section aria-labelledby="nologo-title" class="space-y-4">
        @include('kit-export::partials.demo-heading', ['id' => 'nologo-title', 'title' => 'Without logo (later steps of a flow)'])
        <div class="admin-card mx-auto max-w-md p-6 sm:p-8">
{{-- snippet:nologo --}}
<x-admin.guest-heading :logo="false" eyebrow="Étape 2 sur 2" title="Votre première agence" description="Elle reçoit vos véhicules et vos réservations." />
{{-- /snippet --}}
        </div>
        @include('kit-export::partials.snippet', ['name' => 'nologo'])
    </section>
@endsection
