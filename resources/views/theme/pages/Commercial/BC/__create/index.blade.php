{{--
    New purchase order (K2), without Livewire or jQuery. The company select shows the number the
    order will get, as the former Livewire component commercial.bon-command.info did. Articles
    have no discount. Same fields as before (BCFormRequest).
--}}
@extends('layouts.admin')

@section('title', 'Nouveau bon de commande')

@section('content')
    @php($selectedCompany = (string) old('company', $companies->first()?->id))

    <div class="mx-auto w-full max-w-5xl space-y-6">
        <x-admin.page-header title="Nouveau bon de commande"
            :breadcrumbs="[['label' => 'Bons de commande', 'href' => route('commercial:bcommandes.index')], ['label' => 'Nouveau bon de commande']]" />

        <form method="POST" action="{{ route('commercial:bcommandes.createPost') }}" class="space-y-6"
            x-data="{ company: @js($selectedCompany), numbers: @js($nextNumbers) }">
            @csrf

            <x-messages.errors />

            <section aria-labelledby="bc-info-title" class="admin-card space-y-5 p-5 sm:p-6">
                <h2 id="bc-info-title" class="admin-section-title">Informations du bon de commande</h2>

                <div class="grid gap-4 md:grid-cols-3">
                    <x-admin.form-select name="company" :label="__('invoice.form.company')" :options="$companies->pluck('name', 'id')->all()" :value="$selectedCompany" :placeholder="null" required x-model="company" />
                    <x-admin.form-select2 name="provider" label="Fournisseur" :options="$providers->all()" placeholder="Choisir le fournisseur" search-placeholder="Rechercher un fournisseur…" required />
                    <div class="min-w-0">
                        <p class="admin-form-label">Numéro du bon</p>
                        <p class="flex h-10 items-center rounded-control bg-surface-muted px-3 font-semibold text-fg tabular-nums" x-text="numbers[company] ?? '—'"></p>
                        <p class="admin-form-help">Attribué à l’enregistrement.</p>
                    </div>

                    <x-admin.date-picker name="date_command" label="Date du bon" :value="old('date_command', now()->format('Y-m-d'))" required />
                </div>

                <x-admin.textarea name="admin_notes" :label="__('invoice.form.admin_note')" rows="2" help="Visible seulement dans l’application." />
            </section>

            <section aria-labelledby="bc-articles-title" class="admin-card space-y-5 p-5 sm:p-6">
                <h2 id="bc-articles-title" class="admin-section-title">Articles</h2>
                <x-commercial.articles-editor name="articles" required :discount="false" />
            </section>

            <section aria-labelledby="bc-conditions-title" class="admin-card space-y-5 p-5 sm:p-6">
                <h2 id="bc-conditions-title" class="sr-only">{{ __('invoice.form.condition_general') }}</h2>
                <x-admin.textarea name="condition_general" :label="__('invoice.form.condition_general')" rows="4" />

                <div class="flex flex-col-reverse gap-3 border-t border-line pt-6 sm:flex-row sm:justify-end">
                    <x-admin.button href="{{ route('commercial:bcommandes.index') }}" variant="secondary">Annuler</x-admin.button>
                    <x-admin.button type="submit">Enregistrer le bon de commande</x-admin.button>
                </div>
            </section>
        </form>
    </div>
@endsection
