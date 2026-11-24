{{-- Roles (K1): the UI kit list pattern, with the add form beside the list on large screens. --}}
@extends('layouts.admin')

@section('title', 'Rôles')

@section('content')
    <div class="space-y-6">
        <x-admin.page-header title="Rôles" description="Les rôles regroupent les permissions données aux utilisateurs." />

        <div class="grid gap-6 lg:grid-cols-3">
            <section aria-labelledby="roles-title" class="min-w-0 space-y-4 lg:col-span-2">
                <h2 id="roles-title" class="sr-only">Liste des rôles</h2>

                @if ($roles->isEmpty())
                    <div class="admin-card">
                        <x-admin.empty-state icon="key" title="Aucun rôle" description="Ajoutez le premier rôle avec le formulaire." />
                    </div>
                @else
                    <x-admin.table :headers="['N°', 'Nom', 'Garde']">
                        @foreach ($roles as $role)
                            <tr>
                                <td class="text-fg-muted tabular-nums">{{ $role->id }}</td>
                                <td class="font-medium">{{ $role->name }}</td>
                                <td class="text-fg-muted">{{ $role->guard_name }}</td>
                            </tr>
                        @endforeach
                    </x-admin.table>

                    {{ $roles->links('pagination::admin') }}
                @endif
            </section>

            <section aria-labelledby="role-form-title" class="min-w-0">
                <form method="POST" action="{{ route('admin:permissions-roles.add') }}" class="admin-card space-y-6 p-5 sm:p-6">
                    @csrf

                    <h2 id="role-form-title" class="admin-section-title">Ajouter un rôle</h2>

                    <x-admin.form-input name="name" label="Nom du rôle" required />

                    <div class="flex flex-col-reverse gap-3 border-t border-line pt-6 sm:flex-row sm:justify-end">
                        <x-admin.button type="submit">Enregistrer</x-admin.button>
                    </div>
                </form>
            </section>
        </div>
    </div>
@endsection
