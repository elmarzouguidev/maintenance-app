<x-app.page-header title="Règlements" :breadcrumbs="[['label' => 'Commercial'], ['label' => 'Règlements']]">
    @can('payments.create')
        <a href="{{ route('commercial:bills.create') }}" class="btn btn-primary d-inline-flex align-items-center gap-2">
            <i class="bx bx-plus" aria-hidden="true"></i>
            <span>Ajouter un règlement</span>
        </a>
    @endcan
</x-app.page-header>
