<x-app.page-header title="Bons de commande" :breadcrumbs="[['label' => 'Commercial'], ['label' => 'Bons de commande']]">
    <a href="{{ route('commercial:bcommandes.create') }}" class="btn btn-primary d-inline-flex align-items-center gap-2">
        <i class="bx bx-plus" aria-hidden="true"></i>
        <span>Créer un bon de commande</span>
    </a>
</x-app.page-header>
