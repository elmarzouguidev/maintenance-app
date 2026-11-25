{{--
    Edit a company (K2): one kit card form in sections (identity, legal identifiers, logo,
    document numbering). Same fields and route as before; the fields stored NOT NULL are required
    here, since the rules accept them empty (BL-74).
--}}
@extends('layouts.admin')

@section('title', 'Modifier '.$company->name)

@section('content')
    @php
        $numbering = [
            ['prefix' => 'prefix_invoice', 'start' => 'invoice_start_number', 'label' => 'Factures'],
            ['prefix' => 'prefix_invoice_avoir', 'start' => 'invoice_avoir_start_number', 'label' => 'Factures d’avoir'],
            ['prefix' => 'prefix_estimate', 'start' => 'estimate_start_number', 'label' => 'Devis'],
            ['prefix' => 'prefix_bcommand', 'start' => 'bcommand_start_number', 'label' => 'Bons de commande'],
            ['prefix' => 'prefix_blivraison', 'start' => 'blivraison_start_number', 'label' => 'Bons de livraison'],
        ];
    @endphp

    <div class="mx-auto w-full max-w-3xl space-y-6">
        <x-admin.page-header :title="$company->name" eyebrow="Modifier la société"
            :breadcrumbs="[['label' => __('navbar.companies'), 'href' => route('commercial:companies.index')], ['label' => $company->name]]" />

        <form method="POST" action="{{ route('commercial:companies.update', $company->uuid) }}" enctype="multipart/form-data" class="admin-card space-y-6 p-5 sm:p-6">
            @csrf

            <x-messages.errors />

            <section aria-labelledby="company-identity-title" class="space-y-4">
                <h2 id="company-identity-title" class="admin-section-title">Identité</h2>
                <div class="grid gap-4 md:grid-cols-2">
                    <x-admin.form-input name="name" :label="__('company.form.company')" :value="$company->name" required />
                    <x-admin.form-input name="website" type="url" :label="__('company.form.website')" :value="(string) $company->website" placeholder="https://" />
                    <x-admin.form-input name="city" :label="__('company.form.city')" :value="(string) $company->city" required />
                    <x-admin.form-input name="addresse" :label="__('company.form.addresse')" :value="(string) $company->addresse" required />
                    <x-admin.form-input name="telephone" type="tel" :label="__('company.form.phone')" :value="(string) $company->telephone" inputmode="tel" />
                    <x-admin.form-input name="email" type="email" :label="__('company.form.email')" :value="(string) $company->email" inputmode="email" />
                    <x-admin.textarea name="description" :label="__('company.form.description')" :value="(string) $company->description" rows="3" class="md:col-span-2" />
                </div>
            </section>

            <section aria-labelledby="company-legal-title" class="space-y-4 border-t border-line pt-6">
                <h2 id="company-legal-title" class="admin-section-title">Identifiants légaux</h2>
                <div class="grid gap-4 md:grid-cols-3">
                    <x-admin.form-input name="ice" :label="__('company.form.ice')" :value="(string) $company->ice" required inputmode="numeric" />
                    <x-admin.form-input name="rc" :label="__('company.form.rc')" :value="(string) $company->rc" inputmode="numeric" />
                    <x-admin.form-input name="cnss" :label="__('company.form.cnss')" :value="(string) $company->cnss" inputmode="numeric" />
                    <x-admin.form-input name="patente" :label="__('company.form.patente')" :value="(string) $company->patente" inputmode="numeric" />
                    <x-admin.form-input name="if" :label="__('company.form.if')" :value="(string) $company->if" inputmode="numeric" />
                </div>
            </section>

            <section aria-labelledby="company-logo-title" class="space-y-4 border-t border-line pt-6">
                <h2 id="company-logo-title" class="admin-section-title">{{ __('company.form.logo') }}</h2>
                <x-admin.setting-field type="file" name="logo" :label="__('company.form.logo')" accept="image/png,image/jpeg"
                    :preview-url="filled($company->logo) ? asset('storage/'.$company->logo) : null"
                    help="PNG ou JPG. Imprimé sur les documents de la société." />
            </section>

            <section aria-labelledby="company-numbering-title" class="space-y-4 border-t border-line pt-6">
                <div>
                    <h2 id="company-numbering-title" class="admin-section-title">Numérotation des documents</h2>
                    <p class="mt-1 text-small text-fg-muted">Le préfixe et le premier numéro de chaque type de document.</p>
                </div>
                <div class="grid gap-x-4 gap-y-5 md:grid-cols-2">
                    @foreach ($numbering as $document)
                        <fieldset class="grid min-w-0 grid-cols-2 gap-3">
                            <legend class="admin-form-label">{{ $document['label'] }}</legend>
                            <x-admin.form-input :name="$document['prefix']" label="Préfixe" :value="(string) $company->{$document['prefix']}" required />
                            <x-admin.form-input :name="$document['start']" label="Premier numéro" :value="(string) $company->{$document['start']}" inputmode="numeric" required />
                        </fieldset>
                    @endforeach
                </div>
            </section>

            <div class="flex flex-col-reverse gap-3 border-t border-line pt-6 sm:flex-row sm:justify-end">
                <x-admin.button href="{{ route('commercial:companies.index') }}" variant="secondary">Retour à la liste</x-admin.button>
                <x-admin.button type="submit">Enregistrer</x-admin.button>
            </div>
        </form>
    </div>
@endsection
