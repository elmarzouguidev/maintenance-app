{{--
    Edit a supplier (K2): the kit card form, then the extra phone numbers and e-mail addresses
    (they were Bootstrap modals). Adding posts the existing forms; deleting calls the existing
    JSON endpoints (providers.delete.phone / providers.delete.email) with fetch, then reloads.
--}}
@extends('layouts.admin')

@section('title', 'Modifier '.$provider->entreprise)

@section('content')
    <div class="mx-auto w-full max-w-3xl space-y-6"
        x-data="{
            async remove(url, payload, question) {
                if (! confirm(question)) {
                    return;
                }
                const response = await fetch(url, {
                    method: 'DELETE',
                    headers: { 'Content-Type': 'application/json', Accept: 'application/json', 'X-CSRF-TOKEN': document.querySelector('meta[name=csrf-token]').content },
                    body: JSON.stringify(payload),
                });
                if (response.ok) {
                    window.location.reload();
                }
            },
        }">
        <x-admin.page-header :title="$provider->entreprise" :eyebrow="'Fournisseur '.$provider->code"
            :breadcrumbs="[['label' => 'Fournisseurs', 'href' => route('commercial:providers.index')], ['label' => $provider->entreprise]]" />

        <form method="POST" action="{{ route('commercial:providers.update', $provider->uuid) }}" class="admin-card space-y-6 p-5 sm:p-6">
            @csrf

            <x-messages.errors />

            <div class="grid gap-4 md:grid-cols-2">
                <x-admin.form-input name="entreprise" label="Entreprise" :value="$provider->entreprise" required />
                <x-admin.form-input name="contact" label="Contact" :value="(string) $provider->contact" required />
                <x-admin.form-input name="telephone" type="tel" label="Téléphone" :value="(string) $provider->telephone" required inputmode="tel" />
                <x-admin.form-input name="email" type="email" label="E-mail principal" :value="(string) $provider->email" required inputmode="email" />
                <x-admin.form-input name="addresse" label="Adresse" :value="(string) $provider->addresse" required class="md:col-span-2" />
                <x-admin.form-input name="ice" label="ICE" :value="(string) $provider->ice" required inputmode="numeric" help="15 ou 16 chiffres." />
                <x-admin.form-input name="rc" label="RC" :value="(string) $provider->rc" inputmode="numeric" />
                <x-admin.textarea name="description" label="Description" :value="(string) $provider->description" rows="4" class="md:col-span-2" />
            </div>

            <div class="flex flex-col-reverse gap-3 border-t border-line pt-6 sm:flex-row sm:justify-end">
                <x-admin.button href="{{ route('commercial:providers.index') }}" variant="secondary">Retour à la liste</x-admin.button>
                <x-admin.button type="submit">Enregistrer</x-admin.button>
            </div>
        </form>

        <div class="grid gap-6 lg:grid-cols-2">
            <section aria-labelledby="provider-phones-title" class="admin-card min-w-0">
                <div class="admin-card-header">
                    <h2 id="provider-phones-title" class="admin-section-title">Autres numéros</h2>
                </div>
                <div class="admin-card-body space-y-5">
                    @if ($provider->telephones->isEmpty())
                        <p class="text-small text-fg-muted">Aucun autre numéro.</p>
                    @else
                        <ul class="divide-y divide-line">
                            @foreach ($provider->telephones as $phone)
                                <li class="flex items-center justify-between gap-3 py-2.5">
                                    <span class="min-w-0 tabular-nums text-fg">{{ $phone->telephone }}</span>
                                    <x-admin.icon-button icon="trash" :label="'Supprimer le numéro '.$phone->telephone" size="sm"
                                        x-on:click="remove({{ \Illuminate\Support\Js::from(route('commercial:providers.delete.phone')) }}, { provider: {{ \Illuminate\Support\Js::from($provider->uuid) }}, phone: {{ \Illuminate\Support\Js::from($phone->uuid) }} }, 'Supprimer ce numéro ?')" />
                                </li>
                            @endforeach
                        </ul>
                    @endif

                    <form method="POST" action="{{ route('commercial:providers.add.phones', $provider->uuid) }}" class="flex flex-col gap-3 border-t border-line pt-5 sm:flex-row sm:items-end">
                        @csrf
                        <x-admin.form-input name="secend_phone" type="tel" label="Ajouter un numéro" inputmode="tel" required class="sm:flex-1" />
                        <x-admin.button type="submit" variant="secondary" icon="plus">Ajouter</x-admin.button>
                    </form>
                </div>
            </section>

            <section aria-labelledby="provider-emails-title" class="admin-card min-w-0">
                <div class="admin-card-header">
                    <h2 id="provider-emails-title" class="admin-section-title">Autres e-mails</h2>
                </div>
                <div class="admin-card-body space-y-5">
                    @if ($provider->emails->isEmpty())
                        <p class="text-small text-fg-muted">Aucune autre adresse.</p>
                    @else
                        <ul class="divide-y divide-line">
                            @foreach ($provider->emails as $email)
                                <li class="flex items-center justify-between gap-3 py-2.5">
                                    <span class="min-w-0 truncate text-fg">{{ $email->email }}</span>
                                    <x-admin.icon-button icon="trash" :label="'Supprimer l’adresse '.$email->email" size="sm"
                                        x-on:click="remove({{ \Illuminate\Support\Js::from(route('commercial:providers.delete.email')) }}, { provider: {{ \Illuminate\Support\Js::from($provider->uuid) }}, email: {{ \Illuminate\Support\Js::from($email->uuid) }} }, 'Supprimer cette adresse ?')" />
                                </li>
                            @endforeach
                        </ul>
                    @endif

                    <form method="POST" action="{{ route('commercial:providers.add.emails', $provider->uuid) }}" class="flex flex-col gap-3 border-t border-line pt-5 sm:flex-row sm:items-end">
                        @csrf
                        <x-admin.form-input name="secend_email" type="email" label="Ajouter une adresse" inputmode="email" required class="sm:flex-1" />
                        <x-admin.button type="submit" variant="secondary" icon="plus">Ajouter</x-admin.button>
                    </form>
                </div>
            </section>
        </div>
    </div>
@endsection
