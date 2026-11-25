{{--
    Profile settings (K2): the kit settings pattern, section tabs as links (?tab=) then the form.
    "Mes informations" for everyone; "Société" (the company logo) for Admin and SuperAdmin only,
    as before (isAdmin()).
--}}
@extends('layouts.admin')

@section('title', 'Paramètres du compte')

@php
    $canEditCompany = isAdmin();
    $activeTab = $canEditCompany && request('tab') === 'company' ? 'company' : 'account';
    $tabs = array_filter([
        'account' => ['label' => 'Mes informations', 'icon' => 'user'],
        'company' => $canEditCompany ? ['label' => 'Société', 'icon' => 'building'] : null,
    ]);
@endphp

@section('content')
    <div class="mx-auto w-full max-w-3xl space-y-6">
        <x-admin.page-header title="Paramètres du compte"
            :breadcrumbs="[['label' => 'Mon profil', 'href' => route('admin:profile.index')], ['label' => 'Paramètres']]" />

        @if (count($tabs) > 1)
            <nav class="admin-card overflow-x-auto" aria-label="Sections des paramètres">
                <ul class="flex min-w-max gap-1 p-1.5" role="list">
                    @foreach ($tabs as $key => $tab)
                        <li>
                            <a href="{{ route('admin:profile.settings', $key === 'account' ? [] : ['tab' => $key]) }}" @if ($key === $activeTab) aria-current="page" @endif
                                @class([
                                    'flex min-h-10 items-center gap-2 rounded-control px-3 text-small font-medium whitespace-nowrap transition-colors duration-150 focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-focus',
                                    'bg-accent-soft text-accent-strong' => $key === $activeTab,
                                    'text-fg-muted hover:bg-surface-muted hover:text-fg' => $key !== $activeTab,
                                ])>
                                <x-admin.icon :name="$tab['icon']" class="size-4" /> {{ $tab['label'] }}
                            </a>
                        </li>
                    @endforeach
                </ul>
            </nav>
        @endif

        @if ($activeTab === 'account')
            <form method="POST" action="{{ route('admin:profile.settings.update') }}" class="admin-card space-y-6 p-5 sm:p-6">
                @csrf

                <h2 class="admin-section-title">Mes informations</h2>

                <div class="grid gap-4 md:grid-cols-2">
                    <x-admin.form-input name="prenom" label="Prénom" :value="$user->prenom" required autocomplete="given-name" />
                    <x-admin.form-input name="nom" label="Nom" :value="$user->nom" required autocomplete="family-name" />
                    <x-admin.form-input name="email" type="email" label="E-mail" :value="$user->email" required autocomplete="email" inputmode="email" />
                    <x-admin.form-input name="telephone" type="tel" label="Téléphone" :value="(string) $user->telephone" autocomplete="tel" inputmode="tel" help="Chiffres uniquement." />
                </div>

                <fieldset class="space-y-4 border-t border-line pt-6">
                    <legend class="admin-section-title">Changer le mot de passe</legend>
                    <p class="text-small text-fg-muted">Laissez ces champs vides pour garder le mot de passe actuel.</p>
                    <div class="grid gap-4 md:grid-cols-3">
                        <x-admin.form-input name="oldpassword" type="password" label="Mot de passe actuel" autocomplete="current-password" />
                        <x-admin.form-input name="new_password" type="password" label="Nouveau mot de passe" autocomplete="new-password" />
                        <x-admin.form-input name="new_confirm_password" type="password" label="Confirmer le nouveau mot de passe" autocomplete="new-password" />
                    </div>
                </fieldset>

                <div class="flex flex-col-reverse gap-3 border-t border-line pt-6 sm:flex-row sm:justify-end">
                    <x-admin.button href="{{ route('admin:profile.index') }}" variant="secondary">Annuler</x-admin.button>
                    <x-admin.button type="submit">Enregistrer</x-admin.button>
                </div>
            </form>
        @else
            <form method="POST" action="{{ route('admin:profile.settings.update.company') }}" enctype="multipart/form-data" class="admin-card space-y-6 p-5 sm:p-6">
                @csrf

                <h2 class="admin-section-title">Société</h2>

                <x-admin.setting-field type="file" name="logo" label="Logo" accept="image/png,image/jpeg"
                    :preview-url="filled($setting->logo) ? asset('storage/'.$setting->logo) : null"
                    help="PNG ou JPG, 1 Mo au plus. Imprimé sur les documents." />

                <div class="flex flex-col-reverse gap-3 border-t border-line pt-6 sm:flex-row sm:justify-end">
                    <x-admin.button type="submit">Enregistrer</x-admin.button>
                </div>
            </form>
        @endif
    </div>
@endsection
