{{--
    Clients (K2): the UI kit list pattern with a server-side table, replacing DataTables. Search
    (?q=) on code, company, phone, e-mail or ICE, 30 per page, Excel and PDF exports of the same
    search, the client import in a kit dialog. Edit and delete stay for Admin and SuperAdmin, as
    before; the delete still does nothing on the server (BL-68).
--}}
@extends('layouts.admin')

@section('title', 'Clients')

@section('content')
    @php
        $exportQuery = $search !== '' ? ['q' => $search] : [];
        $canManage = auth()->user()->hasAnyRole('Admin', 'SuperAdmin');
    @endphp

    <div class="space-y-6">
        <x-admin.page-header title="Clients" description="Les sociétés clientes, de la plus récente à la plus ancienne.">
            <x-slot:actions>
                <x-admin.button href="{{ route('admin:clients.export', ['format' => 'xlsx', ...$exportQuery]) }}" variant="secondary" icon="download">Exporter en Excel</x-admin.button>
                <x-admin.button href="{{ route('admin:clients.export', ['format' => 'pdf', ...$exportQuery]) }}" variant="secondary" icon="printer">Exporter en PDF</x-admin.button>
                <x-admin.button variant="secondary" icon="upload" onclick="document.getElementById('import-clients').showModal()">Importer</x-admin.button>
                <x-admin.button href="{{ route('admin:clients.create') }}" icon="plus">{{ __('navbar.clients_add') }}</x-admin.button>
            </x-slot:actions>
        </x-admin.page-header>

        <form method="GET" action="{{ route('admin:clients.index') }}" role="search" class="flex flex-col gap-3 sm:flex-row sm:items-end">
            <x-admin.form-input name="q" type="search" label="Rechercher un client" :value="$search" placeholder="Code, société, téléphone, e-mail ou ICE" class="sm:w-96" />
            <div class="flex gap-3">
                <x-admin.button type="submit" variant="secondary" icon="search">Rechercher</x-admin.button>
                @if ($search !== '')
                    <x-admin.button href="{{ route('admin:clients.index') }}" variant="ghost">Effacer</x-admin.button>
                @endif
            </div>
        </form>

        <section aria-labelledby="clients-title" class="space-y-4">
            <h2 id="clients-title" class="sr-only">Liste des clients</h2>

            @if ($clients->isEmpty())
                <div class="admin-card">
                    @if ($search !== '')
                        <x-admin.empty-state icon="filter" title="Aucun résultat" :description="'Aucun client ne correspond à « '.$search.' ».'">
                            <x-admin.button href="{{ route('admin:clients.index') }}" variant="secondary">Effacer la recherche</x-admin.button>
                        </x-admin.empty-state>
                    @else
                        <x-admin.empty-state icon="users" title="Aucun client" description="Ajoutez le premier client ou importez une liste.">
                            <x-admin.button href="{{ route('admin:clients.create') }}" icon="plus">{{ __('navbar.clients_add') }}</x-admin.button>
                        </x-admin.empty-state>
                    @endif
                </div>
            @else
                <x-admin.table :headers="array_merge(['Code client', 'Entreprise', 'Téléphone', 'E-mail', 'ICE'], $canManage ? [''] : [])">
                    @foreach ($clients as $client)
                        <tr>
                            <td class="font-medium tabular-nums"><a href="{{ $client->url }}" class="hover:underline">{{ $client->code }}</a></td>
                            <td>{{ $client->entreprise }}</td>
                            <td class="tabular-nums">{{ $client->telephone }}</td>
                            <td>{{ $client->email }}</td>
                            <td class="tabular-nums">{{ $client->ice }}</td>
                            @if ($canManage)
                                <td class="text-end">
                                    <div class="flex items-center justify-end gap-2">
                                        <x-admin.icon-button icon="pencil" :label="'Modifier '.$client->entreprise" href="{{ $client->edit }}" size="sm" />
                                        <x-admin.delete-action :action="route('admin:clients.delete', ['clientId' => $client->uuid])"
                                            :confirm="'Supprimer le client '.$client->entreprise.' ?'" />
                                    </div>
                                </td>
                            @endif
                        </tr>
                    @endforeach
                </x-admin.table>

                {{ $clients->links('pagination::admin') }}
            @endif
        </section>
    </div>

    <x-admin.modal id="import-clients" title="Importer une liste de clients" description="Fichier Excel (xlsx, xls) ou CSV, une ligne par client.">
        <form id="import-clients-form" method="POST" action="{{ route('admin:clients.import') }}" enctype="multipart/form-data">
            @csrf
            <x-admin.file-upload name="file" label="Liste des clients" accept=".xlsx,.xls,.csv" hint="Excel ou CSV." required />
        </form>
        <x-slot:footer>
            <x-admin.button variant="secondary" onclick="this.closest('dialog').close()">Annuler</x-admin.button>
            <x-admin.button type="submit" form="import-clients-form" icon="upload">Importer</x-admin.button>
        </x-slot:footer>
    </x-admin.modal>

    @error('file')
        {{-- Show the import error where the file was chosen. --}}
        @push('scripts')
            <script>document.getElementById('import-clients').showModal();</script>
        @endpush
    @enderror
@endsection
