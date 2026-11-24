@extends('kit-export::gallery')

@section('demo')
    <section aria-labelledby="types-title" class="space-y-4">
        @include('kit-export::partials.demo-heading', ['id' => 'types-title', 'title' => 'Types', 'text' => 'success, error (or danger), warning, info. Errors are announced immediately (role="alert"), the others politely (role="status"). Dismissible by default.'])
        <div class="space-y-3">
{{-- snippet:types --}}
<x-admin.alert type="success" message="La réservation a été confirmée." />
<x-admin.alert type="error" message="Le véhicule n’est pas disponible sur cette période." />
<x-admin.alert type="warning" message="La caution n’est pas encore sécurisée." />
<x-admin.alert type="info" message="Le retour sera enregistré dans l’agence courante." />
{{-- /snippet --}}
        </div>
        @include('kit-export::partials.snippet', ['name' => 'types'])
    </section>

    <section aria-labelledby="persistent-title" class="space-y-4">
        @include('kit-export::partials.demo-heading', ['id' => 'persistent-title', 'title' => 'Not dismissible, with rich content', 'text' => 'Use dismissible="false" for a state the user must see (an access notice, a locked record). The slot accepts markup.'])
        <div class="space-y-3">
{{-- snippet:persistent --}}
<x-admin.alert type="warning" :dismissible="false">
    Cette facture est émise : elle ne peut plus être modifiée. <a href="#" class="underline">Créer un avoir</a>
</x-admin.alert>
<x-admin.alert type="info" :dismissible="false" message="Votre abonnement se termine dans 5 jours." />
{{-- /snippet --}}
        </div>
        @include('kit-export::partials.snippet', ['name' => 'persistent'])
    </section>

    <section aria-labelledby="flash-title" class="space-y-4">
        @include('kit-export::partials.demo-heading', ['id' => 'flash-title', 'title' => 'Flash messages (x-messages)', 'text' => 'The layouts render x-messages under the topbar: one alert per session flash (success, status, error, warning), set with redirect()->with(...).'])
        <div class="admin-card">
{{-- snippet:flash --}}
<x-messages />
{{-- /snippet --}}
            <div class="h-5"></div>
        </div>
        @include('kit-export::partials.snippet', ['name' => 'flash'])
    </section>

    <section aria-labelledby="summary-title" class="space-y-4">
        @include('kit-export::partials.demo-heading', ['id' => 'summary-title', 'title' => 'Validation summary (x-messages.errors)', 'text' => 'Above a long form: every validation error of the request. Each field also shows its own error (see Text fields).'])
        <div>
{{-- snippet:summary --}}
<x-messages.errors />
{{-- /snippet --}}
        </div>
        @include('kit-export::partials.snippet', ['name' => 'summary'])
    </section>
@endsection
