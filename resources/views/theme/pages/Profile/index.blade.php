{{--
    My profile (K2): the signed-in user's details, on the kit detail pattern. Replaces a Skote
    demo page (a fictional "Cynthia Price" with sample projects) that ignored the user.
--}}
@extends('layouts.admin')

@section('title', 'Mon profil')

@section('content')
    <div class="space-y-6">
        <x-admin.page-header eyebrow="Mon profil" :title="$user->name" :description="$user->email">
            <x-slot:actions>
                <x-admin.button href="{{ route('admin:profile.settings') }}" icon="pencil">Modifier mon profil</x-admin.button>
            </x-slot:actions>
        </x-admin.page-header>

        <x-admin.detail-page>
            <x-admin.detail-section title="Informations personnelles">
                <dl class="grid gap-4 sm:grid-cols-2">
                    <x-admin.detail-item label="Prénom">{{ $user->prenom }}</x-admin.detail-item>
                    <x-admin.detail-item label="Nom">{{ $user->nom }}</x-admin.detail-item>
                    <x-admin.detail-item label="E-mail">{{ $user->email }}</x-admin.detail-item>
                    <x-admin.detail-item label="Téléphone">
                        @if (filled($user->telephone))
                            <span class="tabular-nums">{{ $user->telephone }}</span>
                        @else
                            <span class="text-fg-muted">Non renseigné</span>
                        @endif
                    </x-admin.detail-item>
                </dl>
            </x-admin.detail-section>

            <x-admin.detail-section title="Accès">
                <dl class="grid gap-4 sm:grid-cols-2">
                    <x-admin.detail-item label="Rôles">
                        <span class="flex flex-wrap gap-1.5">
                            @forelse ($user->getRoleNames() as $role)
                                <x-admin.badge>{{ $role }}</x-admin.badge>
                            @empty
                                <span class="text-fg-muted">Aucun rôle</span>
                            @endforelse
                        </span>
                    </x-admin.detail-item>
                    <x-admin.detail-item label="Compte">{{ $user->active ? 'Actif' : 'Désactivé' }}</x-admin.detail-item>
                </dl>
            </x-admin.detail-section>
        </x-admin.detail-page>
    </div>
@endsection
