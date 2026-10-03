<x-app.page-header title="Bons de livraison" :breadcrumbs="[['label' => 'Commercial'], ['label' => 'Bons de livraison']]">
    <a href="{{ route('commercial:blivraison.create') }}" class="btn btn-primary d-inline-flex align-items-center gap-2">
        <i class="bx bx-plus" aria-hidden="true"></i>
        <span>Créer un bon de livraison</span>
    </a>
</x-app.page-header>
