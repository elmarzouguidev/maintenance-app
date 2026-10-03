<x-app.page-header
    :title="__('navbar.clients')"
    :breadcrumbs="[['label' => __('navbar.clients')]]">
    <a href="{{ route('admin:clients.create') }}" class="btn btn-primary d-inline-flex align-items-center gap-2">
        <i class="bx bx-user-plus" aria-hidden="true"></i>
        <span>{{ __('navbar.clients_add') }}</span>
    </a>
</x-app.page-header>
