{{--
    Suppliers (K2): the UI kit list pattern with a server-side table, replacing DataTables. Search
    (?q=) on code, company, phone, e-mail, ICE or RC, 30 per page, Excel and PDF exports of the
    same search. The delete still does nothing on the server (BL-70).
--}}
@extends('layouts.admin')

@section('title', 'Fournisseurs')

@section('content')
    @php($exportQuery = $search !== '' ? ['q' => $search] : [])

    <div class="space-y-6">
        <x-admin.page-header title="Fournisseurs" description="Les fournisseurs des bons de commande, du plus récent au plus ancien.">
            <x-slot:actions>
                <x-admin.button href="{{ route('commercial:providers.export', ['format' => 'xlsx', ...$exportQuery]) }}" variant="secondary" icon="download">Exporter en Excel</x-admin.button>
                <x-admin.button href="{{ route('commercial:providers.export', ['format' => 'pdf', ...$exportQuery]) }}" variant="secondary" icon="printer">Exporter en PDF</x-admin.button>
                <x-admin.button href="{{ route('commercial:providers.create') }}" icon="plus">Ajouter un fournisseur</x-admin.button>
            </x-slot:actions>
        </x-admin.page-header>

        <form method="GET" action="{{ route('commercial:providers.index') }}" role="search" class="flex flex-col gap-3 sm:flex-row sm:items-end">
            <x-admin.form-input name="q" type="search" label="Rechercher un fournisseur" :value="$search" placeholder="Code, société, téléphone, e-mail, ICE ou RC" class="sm:w-96" />
            <div class="flex gap-3">
                <x-admin.button type="submit" variant="secondary" icon="search">Rechercher</x-admin.button>
                @if ($search !== '')
                    <x-admin.button href="{{ route('commercial:providers.index') }}" variant="ghost">Effacer</x-admin.button>
                @endif
            </div>
        </form>

        <section aria-labelledby="providers-title" class="space-y-4">
            <h2 id="providers-title" class="sr-only">Liste des fournisseurs</h2>

            @if ($providers->isEmpty())
                <div class="admin-card">
                    @if ($search !== '')
                        <x-admin.empty-state icon="filter" title="Aucun résultat" :description="'Aucun fournisseur ne correspond à « '.$search.' ».'">
                            <x-admin.button href="{{ route('commercial:providers.index') }}" variant="secondary">Effacer la recherche</x-admin.button>
                        </x-admin.empty-state>
                    @else
                        <x-admin.empty-state icon="building" title="Aucun fournisseur" description="Ajoutez le premier fournisseur.">
                            <x-admin.button href="{{ route('commercial:providers.create') }}" icon="plus">Ajouter un fournisseur</x-admin.button>
                        </x-admin.empty-state>
                    @endif
                </div>
            @else
                <x-admin.table :headers="['Code fournisseur', 'Entreprise', 'Téléphone', 'E-mail', 'ICE', 'RC', '']">
                    @foreach ($providers as $provider)
                        <tr>
                            <td class="font-medium tabular-nums">{{ $provider->code }}</td>
                            <td>
                                <p class="font-medium text-fg">{{ $provider->entreprise }}</p>
                                <p class="text-small text-fg-muted">{{ $provider->contact }}</p>
                            </td>
                            <td class="tabular-nums">{{ $provider->telephone }}</td>
                            <td>{{ $provider->email }}</td>
                            <td class="tabular-nums">{{ $provider->ice }}</td>
                            <td class="tabular-nums">{{ $provider->rc }}</td>
                            <td class="text-end">
                                <div class="flex items-center justify-end gap-2">
                                    <x-admin.icon-button icon="pencil" :label="'Modifier '.$provider->entreprise" href="{{ $provider->edit }}" size="sm" />
                                    <x-admin.delete-action :action="route('commercial:providers.delete', ['providerId' => $provider->uuid])"
                                        :confirm="'Supprimer le fournisseur '.$provider->entreprise.' ?'" />
                                </div>
                            </td>
                        </tr>
                    @endforeach
                </x-admin.table>

                {{ $providers->links('pagination::admin') }}
            @endif
        </section>
    </div>
@endsection
