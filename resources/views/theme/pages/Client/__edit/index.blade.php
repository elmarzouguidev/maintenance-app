{{--
    Edit a client (K2): the kit card form, then the extra phone numbers and e-mail addresses
    (they were Bootstrap modals). Adding posts the existing forms; deleting calls the existing
    JSON endpoints (client.delete.phone / client.delete.email) with fetch, then reloads.
--}}
@extends('layouts.admin')

@section('title', 'Modifier '.$client->entreprise)

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
        <x-admin.page-header :title="$client->entreprise" eyebrow="Modifier le client"
            :breadcrumbs="[['label' => 'Clients', 'href' => route('admin:clients.index')], ['label' => $client->entreprise, 'href' => $client->url], ['label' => 'Modifier']]">
            <x-slot:actions>
                <x-admin.button href="{{ $client->url }}" variant="secondary" icon="eye">Voir la fiche</x-admin.button>
            </x-slot:actions>
        </x-admin.page-header>

        <form method="POST" action="{{ $client->update }}" class="admin-card space-y-6 p-5 sm:p-6">
            @csrf

            <x-messages.errors />

            <div class="grid gap-4 md:grid-cols-2">
                <x-admin.form-input name="entreprise" label="Entreprise" :value="$client->entreprise" required />
                <x-admin.form-input name="contact" label="Contact" :value="$client->contact" required />
                <x-admin.form-input name="telephone" type="tel" label="Téléphone" :value="(string) $client->telephone" required inputmode="tel" />
                <x-admin.form-input name="email" type="email" label="E-mail principal" :value="(string) $client->email" required inputmode="email" />
                <x-admin.form-input name="addresse" label="Siège social" :value="(string) $client->addresse" required class="md:col-span-2" />
                <x-admin.form-input name="ice" label="ICE" :value="(string) $client->ice" required inputmode="numeric" help="15 ou 16 chiffres." />
                <x-admin.form-input name="rc" label="RC" :value="(string) $client->rc" inputmode="numeric" />
                <x-admin.form-select name="category" label="Catégorie" :options="$categories->pluck('name', 'id')->all()" :value="(string) $client->category_id" placeholder="Aucune catégorie" />
                <x-admin.textarea name="description" label="Description de la société" :value="(string) $client->description" rows="4" class="md:col-span-2" />
            </div>

            <div class="flex flex-col-reverse gap-3 border-t border-line pt-6 sm:flex-row sm:justify-end">
                <x-admin.button href="{{ $client->url }}" variant="secondary">Annuler</x-admin.button>
                <x-admin.button type="submit">Enregistrer</x-admin.button>
            </div>
        </form>

        <div class="grid gap-6 lg:grid-cols-2">
            <section aria-labelledby="client-phones-title" class="admin-card min-w-0">
                <div class="admin-card-header">
                    <h2 id="client-phones-title" class="admin-section-title">Autres numéros</h2>
                </div>
                <div class="admin-card-body space-y-5">
                    @if ($client->telephones->isEmpty())
                        <p class="text-small text-fg-muted">Aucun autre numéro.</p>
                    @else
                        <ul class="divide-y divide-line">
                            @foreach ($client->telephones as $phone)
                                <li class="flex items-center justify-between gap-3 py-2.5">
                                    <span class="min-w-0">
                                        <span class="block tabular-nums text-fg">{{ $phone->telephone }}</span>
                                        <span class="block text-caption text-fg-muted">{{ ucfirst((string) $phone->type) }}</span>
                                    </span>
                                    <x-admin.icon-button icon="trash" :label="'Supprimer le numéro '.$phone->telephone" size="sm"
                                        x-on:click="remove({{ \Illuminate\Support\Js::from(route('admin:client.delete.phone')) }}, { client: {{ \Illuminate\Support\Js::from($client->uuid) }}, phone: {{ \Illuminate\Support\Js::from($phone->uuid) }} }, 'Supprimer ce numéro ?')" />
                                </li>
                            @endforeach
                        </ul>
                    @endif

                    <form method="POST" action="{{ route('admin:client.add.phones', $client->uuid) }}" class="flex flex-col gap-3 border-t border-line pt-5 sm:flex-row sm:items-end">
                        @csrf
                        <x-admin.form-input name="telephone" id="new-telephone" type="tel" label="Ajouter un numéro" inputmode="tel" required class="sm:flex-1" />
                        <x-admin.form-select name="type" id="new-telephone-type" label="Type" :options="['fix' => 'Fixe', 'portable' => 'Portable', 'autre' => 'Autre']" value="fix" :placeholder="null" required />
                        <x-admin.button type="submit" variant="secondary" icon="plus">Ajouter</x-admin.button>
                    </form>
                </div>
            </section>

            <section aria-labelledby="client-emails-title" class="admin-card min-w-0">
                <div class="admin-card-header">
                    <h2 id="client-emails-title" class="admin-section-title">Autres e-mails</h2>
                </div>
                <div class="admin-card-body space-y-5">
                    @if ($client->emails->isEmpty())
                        <p class="text-small text-fg-muted">Aucune autre adresse.</p>
                    @else
                        <ul class="divide-y divide-line">
                            @foreach ($client->emails as $email)
                                <li class="flex items-center justify-between gap-3 py-2.5">
                                    <span class="min-w-0 truncate text-fg">{{ $email->email }}</span>
                                    <x-admin.icon-button icon="trash" :label="'Supprimer l’adresse '.$email->email" size="sm"
                                        x-on:click="remove({{ \Illuminate\Support\Js::from(route('admin:client.delete.email')) }}, { client: {{ \Illuminate\Support\Js::from($client->uuid) }}, email: {{ \Illuminate\Support\Js::from($email->uuid) }} }, 'Supprimer cette adresse ?')" />
                                </li>
                            @endforeach
                        </ul>
                    @endif

                    <form method="POST" action="{{ route('admin:client.add.emails', $client->uuid) }}" class="flex flex-col gap-3 border-t border-line pt-5 sm:flex-row sm:items-end">
                        @csrf
                        <x-admin.form-input name="secend_email" type="email" label="Ajouter une adresse" inputmode="email" required class="sm:flex-1" />
                        <x-admin.button type="submit" variant="secondary" icon="plus">Ajouter</x-admin.button>
                    </form>
                </div>
            </section>
        </div>
    </div>
@endsection
