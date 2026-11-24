{{--
    Companies (K2): the kit list pattern, 20 per page as before. No add button: creating a
    company is disabled (create() and store() redirect to this list, BL-73). The delete still
    does nothing on the server (BL-73).
--}}
@extends('layouts.admin')

@section('title', __('navbar.companies'))

@section('content')
    <div class="space-y-6">
        <x-admin.page-header :title="__('navbar.companies')" description="Les sociétés émettrices des factures, devis et bons." />

        @if ($companies->isEmpty())
            <div class="admin-card">
                <x-admin.empty-state icon="building" title="Aucune société" description="Aucune société n’est enregistrée." />
            </div>
        @else
            <x-admin.table :headers="[__('company.table.id'), __('company.table.name'), __('company.table.city'), __('company.table.addresse'), __('company.table.phone'), __('company.table.email'), __('company.table.ice'), __('company.table.rc'), __('company.table.cnss'), '']">
                @foreach ($companies as $company)
                    <tr>
                        <td class="text-fg-muted tabular-nums">{{ $company->id }}</td>
                        <td class="font-medium"><a href="{{ $company->edit_url }}" class="hover:underline">{{ $company->name }}</a></td>
                        <td>{{ $company->city }}</td>
                        <td>{{ $company->addresse }}</td>
                        <td class="tabular-nums">{{ $company->telephone }}</td>
                        <td>{{ $company->email }}</td>
                        <td class="tabular-nums">{{ $company->ice }}</td>
                        <td class="tabular-nums">{{ $company->rc }}</td>
                        <td class="tabular-nums">{{ $company->cnss }}</td>
                        <td class="text-end">
                            <div class="flex items-center justify-end gap-2">
                                <x-admin.icon-button icon="pencil" :label="'Modifier '.$company->name" href="{{ $company->edit_url }}" size="sm" />
                                <x-admin.delete-action :action="route('commercial:companies.delete', ['companyId' => $company->uuid])"
                                    :confirm="'Supprimer la société '.$company->name.' ?'" />
                            </div>
                        </td>
                    </tr>
                @endforeach
            </x-admin.table>

            {{ $companies->links('pagination::admin') }}
        @endif
    </div>
@endsection
