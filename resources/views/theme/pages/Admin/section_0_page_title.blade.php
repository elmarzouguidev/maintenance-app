<x-app.page-header title="Utilisateurs" :breadcrumbs="[['label' => 'Administration'], ['label' => 'Utilisateurs']]">
    <a href="{{ route('admin:admins.create') }}" class="btn btn-primary d-inline-flex align-items-center gap-2">
        <i class="bx bx-user-plus" aria-hidden="true"></i>
        <span>Ajouter un utilisateur</span>
    </a>
</x-app.page-header>
