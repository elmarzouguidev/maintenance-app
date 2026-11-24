@extends('kit-export::gallery')

@section('demo')
    <section aria-labelledby="variants-title" class="space-y-4">
        @include('kit-export::partials.demo-heading', ['id' => 'variants-title', 'title' => 'Variants and meaning', 'text' => 'Red: wrong or act now. Amber: attention soon. Green: done. Blue: scheduled or informational. Neutral: inactive, cancelled or draft. Never the accent colour.'])
        <div class="admin-card flex flex-wrap items-center gap-3 p-5">
{{-- snippet:variants --}}
<x-admin.status-badge variant="danger" label="En retard" />
<x-admin.status-badge variant="warning" label="En attente" />
<x-admin.status-badge variant="success" label="Terminée" />
<x-admin.status-badge variant="info" label="Confirmée" />
<x-admin.status-badge variant="neutral" label="Annulée" />
{{-- /snippet --}}
        </div>
        @include('kit-export::partials.snippet', ['name' => 'variants'])
    </section>

    <section aria-labelledby="enum-title" class="space-y-4">
        @include('kit-export::partials.demo-heading', ['id' => 'enum-title', 'title' => 'From a status enum', 'text' => 'Pass the enum case: it implements App\Support\UiKit\HasStatusBadge (label() and badgeVariant()), so the mapping lives in one place. REFERENCE.md lists the mapping rules.'])
        <div class="admin-card p-5">
            <x-admin.table :headers="['Status', 'Badge', 'Variant']">
                <tr><td>Brouillon</td><td><x-admin.status-badge variant="neutral" label="Brouillon" /></td><td class="font-mono text-small">NEUTRAL</td></tr>
                <tr><td>Planifiée / confirmée</td><td><x-admin.status-badge variant="info" label="Confirmée" /></td><td class="font-mono text-small">INFO</td></tr>
                <tr><td>À traiter bientôt</td><td><x-admin.status-badge variant="warning" label="Partiellement réglée" /></td><td class="font-mono text-small">WARNING</td></tr>
                <tr><td>Terminée / payée</td><td><x-admin.status-badge variant="success" label="Réglée" /></td><td class="font-mono text-small">SUCCESS</td></tr>
                <tr><td>En défaut / à agir</td><td><x-admin.status-badge variant="danger" label="Impayée" /></td><td class="font-mono text-small">DANGER</td></tr>
            </x-admin.table>
        </div>
        <pre class="admin-inset overflow-x-auto px-4 py-3 text-caption text-fg"><code>&lt;x-admin.status-badge :status="$invoice-&gt;status" /&gt;</code></pre>
    </section>

    <section aria-labelledby="badge-title" class="space-y-4">
        @include('kit-export::partials.demo-heading', ['id' => 'badge-title', 'title' => 'Generic label (x-admin.badge)', 'text' => 'Same styles for a label that is not a status (a type, a count, a tag).'])
        <div class="admin-card flex flex-wrap items-center gap-3 p-5">
{{-- snippet:badge --}}
<x-admin.badge>Particulier</x-admin.badge>
<x-admin.badge variant="info">Société</x-admin.badge>
<x-admin.badge variant="success">3 véhicules</x-admin.badge>
<x-admin.badge variant="warning">Échéance proche</x-admin.badge>
<x-admin.badge variant="danger">Bloqué</x-admin.badge>
{{-- /snippet --}}
        </div>
        @include('kit-export::partials.snippet', ['name' => 'badge'])
    </section>
@endsection
