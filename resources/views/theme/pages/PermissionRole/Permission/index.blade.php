{{-- Permissions (K1): the UI kit list pattern, with the add form beside the list on large screens. --}}
@extends('layouts.admin')

@section('title', 'Permissions')

@section('content')
    <div class="space-y-6">
        <x-admin.page-header title="Permissions" description="Chaque permission autorise une action ; elles sont attribuées aux rôles et aux utilisateurs." />

        <div class="grid gap-6 lg:grid-cols-3">
            <section aria-labelledby="permissions-title" class="min-w-0 space-y-4 lg:col-span-2">
                <h2 id="permissions-title" class="sr-only">Liste des permissions</h2>

                @if ($permissions->isEmpty())
                    <div class="admin-card">
                        <x-admin.empty-state icon="shield" title="Aucune permission" description="Ajoutez la première permission avec le formulaire." />
                    </div>
                @else
                    <x-admin.table :headers="['N°', 'Nom', 'Garde']">
                        @foreach ($permissions as $permission)
                            <tr>
                                <td class="text-fg-muted tabular-nums">{{ $permission->id }}</td>
                                <td class="font-medium">{{ $permission->name }}</td>
                                <td class="text-fg-muted">{{ $permission->guard_name }}</td>
                            </tr>
                        @endforeach
                    </x-admin.table>

                    {{ $permissions->links('pagination::admin') }}
                @endif
            </section>

            <section aria-labelledby="permission-form-title" class="min-w-0">
                <form method="POST" action="{{ route('admin:permissions-roles.add.permissions') }}" class="admin-card space-y-6 p-5 sm:p-6">
                    @csrf

                    <h2 id="permission-form-title" class="admin-section-title">Ajouter une permission</h2>

                    <x-admin.form-input name="name" label="Nom de la permission" placeholder="Ex. invoices.browse" required />

                    <div class="flex flex-col-reverse gap-3 border-t border-line pt-6 sm:flex-row sm:justify-end">
                        <x-admin.button type="submit">Enregistrer</x-admin.button>
                    </div>
                </form>
            </section>
        </div>
    </div>
@endsection
