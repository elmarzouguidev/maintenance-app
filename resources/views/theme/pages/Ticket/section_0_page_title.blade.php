@php($ticketPageTitle = $title ?? 'Tickets')

<x-app.page-header
    :title="$ticketPageTitle"
    :breadcrumbs="[['label' => 'Tickets']]">
    @can('ticket.create')
        <a href="{{ route('admin:tickets.create') }}" class="btn btn-primary d-inline-flex align-items-center gap-2">
            <i class="bx bx-plus" aria-hidden="true"></i>
            <span>Créer un ticket</span>
        </a>
    @endcan
</x-app.page-header>
