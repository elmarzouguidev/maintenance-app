{{--
    Users (K2, SuperAdmin): the kit list pattern. Same data as before, including the password
    column (BL-61: passwords are stored in clear) and the protected developer account (no
    actions, e-mail masked). The delete action still does nothing on the server (BL-65).
--}}
@extends('layouts.admin')

@section('title', 'Utilisateurs')

@section('content')
    @php($protectedEmail = 'abdelgha4or@gmail.com')

    <div class="space-y-6">
        <x-admin.page-header title="Utilisateurs" description="Les comptes qui accèdent à l’application, avec leurs rôles.">
            <x-slot:actions>
                <x-admin.button href="{{ route('admin:admins.create') }}" icon="plus">Ajouter un utilisateur</x-admin.button>
            </x-slot:actions>
        </x-admin.page-header>

        @if ($admins->isEmpty())
            <div class="admin-card">
                <x-admin.empty-state icon="users" title="Aucun utilisateur" description="Ajoutez le premier utilisateur.">
                    <x-admin.button href="{{ route('admin:admins.create') }}" icon="plus">Ajouter un utilisateur</x-admin.button>
                </x-admin.empty-state>
            </div>
        @else
            <x-admin.table :headers="['Utilisateur', 'Statut', 'E-mail', 'Mot de passe', 'Ajouté le', '']">
                @foreach ($admins as $admin)
                    @php($isProtected = $admin->email === $protectedEmail)
                    <tr>
                        <td>
                            <p class="font-medium text-fg">
                                @if ($isProtected)
                                    {{ $admin->full_name }}
                                @else
                                    <a href="{{ route('admin:admins.edit', $admin->uuid) }}" class="hover:underline">{{ $admin->full_name }}</a>
                                @endif
                            </p>
                            @if ($admin->getRoleNames()->isNotEmpty())
                                <p class="mt-1 flex flex-wrap gap-1">
                                    @foreach ($admin->getRoleNames() as $roleName)
                                        <x-admin.badge>{{ $roleName }}</x-admin.badge>
                                    @endforeach
                                </p>
                            @endif
                        </td>
                        <td>
                            @if ($admin->active)
                                Actif
                            @else
                                <span class="text-fg-muted">Désactivé (ne peut pas se connecter)</span>
                            @endif
                        </td>
                        <td>{{ $isProtected ? \Illuminate\Support\Str::mask($admin->email, '*', 2, 9) : $admin->email }}</td>
                        <td class="tabular-nums">{{ $admin->public_password }}</td>
                        <td><x-admin.date :value="$admin->created_at" /></td>
                        <td class="text-end">
                            @unless ($isProtected)
                                <div class="flex items-center justify-end gap-2">
                                    <x-admin.icon-button icon="pencil" :label="'Modifier '.$admin->full_name" href="{{ route('admin:admins.edit', $admin->uuid) }}" size="sm" />
                                    <x-admin.delete-action :action="route('admin:admins.delete', ['adminId' => $admin->uuid])"
                                        :confirm="'Supprimer le compte de '.$admin->full_name.' ?'" />
                                </div>
                            @endunless
                        </td>
                    </tr>
                @endforeach
            </x-admin.table>
        @endif
    </div>
@endsection
