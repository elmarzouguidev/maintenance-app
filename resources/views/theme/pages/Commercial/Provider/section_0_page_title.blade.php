<x-app.page-header title="Fournisseurs" :breadcrumbs="[['label' => 'Commercial'], ['label' => 'Fournisseurs']]">
    @can('providers.create')
        <a href="{{ route('commercial:providers.create') }}" class="btn btn-primary d-inline-flex align-items-center gap-2">
            <i class="bx bx-plus" aria-hidden="true"></i>
            <span>Ajouter un fournisseur</span>
        </a>
    @endcan
</x-app.page-header>
