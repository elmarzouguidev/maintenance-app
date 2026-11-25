{{--
    Edit a user (K2, SuperAdmin): the account form, then the permissions grouped by module.
    Roles are kit checkboxes posting roles[] (they were a Select2 multiple select); "active" is a
    kit switch. Same routes, fields and messages as before.
--}}
@extends('layouts.admin')

@section('title', 'Modifier '.$admin->full_name)

@section('content')
    @php
        $selectedRoles = old('roles', $admin->getRoleNames()->all());
        $selectedPermissions = $admin->getPermissionNames()->all();
    @endphp

    <div class="mx-auto w-full max-w-4xl space-y-6">
        <x-admin.page-header :title="$admin->full_name" eyebrow="Modifier l’utilisateur"
            :breadcrumbs="[['label' => 'Utilisateurs', 'href' => route('admin:admins')], ['label' => $admin->full_name]]" />

        <form method="POST" action="{{ route('admin:admins.update', $admin->uuid) }}" class="admin-card space-y-6 p-5 sm:p-6">
            @csrf

            <h2 class="admin-section-title">Compte</h2>

            <div class="grid gap-4 md:grid-cols-2">
                <x-admin.form-input name="prenom" label="Prénom" :value="$admin->prenom" required autocomplete="off" />
                <x-admin.form-input name="nom" label="Nom" :value="$admin->nom" required autocomplete="off" />
                <x-admin.form-input name="email" type="email" label="E-mail" :value="$admin->email" required inputmode="email" autocomplete="off" />
                <x-admin.form-input name="telephone" type="tel" label="Téléphone" :value="(string) $admin->telephone" required inputmode="tel" autocomplete="off" help="Numéro marocain, ex. 0612345678." />
                <x-admin.form-input name="password" label="Nouveau mot de passe" autocomplete="off" help="Laissez vide pour garder le mot de passe actuel." />

                <label class="flex min-h-10 cursor-pointer items-center gap-3 self-end text-body font-medium text-fg">
                    <input type="checkbox" name="active" value="1" class="peer sr-only" @checked(old('active', $admin->active))>
                    <span class="admin-switch" aria-hidden="true"></span>
                    Compte actif (peut se connecter)
                </label>
            </div>

            <fieldset class="space-y-2 border-t border-line pt-6" @error('roles') aria-describedby="roles-error" @enderror>
                <legend class="admin-form-label">Rôles</legend>
                <div class="grid gap-2 sm:grid-cols-3">
                    @foreach ($roles as $role)
                        <label class="flex items-center gap-2 text-body">
                            <input type="checkbox" name="roles[]" value="{{ $role->name }}" class="admin-checkbox" @checked(in_array($role->name, $selectedRoles, true))>
                            {{ $role->name }}
                        </label>
                    @endforeach
                </div>
                @error('roles')
                    <p id="roles-error" class="admin-form-error" role="alert"><x-admin.icon name="exclamation-circle" class="size-4" /><span>{{ $message }}</span></p>
                @enderror
            </fieldset>

            <div class="flex flex-col-reverse gap-3 border-t border-line pt-6 sm:flex-row sm:justify-end">
                <x-admin.button href="{{ route('admin:admins') }}" variant="secondary">Retour à la liste</x-admin.button>
                <x-admin.button type="submit">Enregistrer</x-admin.button>
            </div>
        </form>

        <form method="POST" action="{{ route('admin:admins.syncPermissions', $admin->uuid) }}" class="admin-card space-y-6 p-5 sm:p-6">
            @csrf
            @method('PUT')
            <input type="hidden" name="adminId" value="{{ $admin->uuid }}">

            <div>
                <h2 class="admin-section-title">Permissions</h2>
                <p class="mt-1 text-small text-fg-muted">Les permissions données à {{ $admin->full_name }} en plus de celles de ses rôles.</p>
            </div>

            @if (session('permissions'))
                <x-admin.alert type="success" :message="session('permissions')" />
            @endif

            @error('permissions')
                <x-admin.alert type="error" :message="$message" />
            @enderror

            <div class="grid gap-6 sm:grid-cols-2 lg:grid-cols-3">
                @foreach ($permissions as $module => $modulePermissions)
                    <fieldset class="min-w-0 space-y-2">
                        <legend class="admin-form-label">{{ $module }}</legend>
                        @foreach ($modulePermissions as $permission)
                            <label class="flex items-start gap-2 text-body">
                                <input type="checkbox" name="permissions[]" value="{{ $permission['name'] }}" id="permission-{{ $permission['id'] }}" class="admin-checkbox mt-0.5"
                                    @checked(in_array($permission['name'], $selectedPermissions, true))>
                                <span>{{ $permission['public_name'] ?: $permission['name'] }}</span>
                            </label>
                        @endforeach
                    </fieldset>
                @endforeach
            </div>

            <div class="flex flex-col-reverse gap-3 border-t border-line pt-6 sm:flex-row sm:justify-end">
                <x-admin.button type="submit">Synchroniser les permissions</x-admin.button>
            </div>
        </form>
    </div>
@endsection
