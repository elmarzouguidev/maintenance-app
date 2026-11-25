{{--
    Edit a delivery note (K2), without jQuery or SweetAlert. Same structure as the purchase order
    edit page: the main form (blivraison.update) with the details, new articles and conditions;
    each existing article saved on its own (blivraison.update.article) through form="…";
    deletion through blivraison.delete.article (JSON). No "send" action: delivery notes were
    never sent (the former dialog posted to "#"). The administration note is now filled in: the
    former form posted it empty and erased it on every save (BL-81).
--}}
@extends('layouts.admin')

@section('title', 'Bon de livraison '.$command->full_number)

@section('content')
    <div class="mx-auto w-full max-w-5xl space-y-6"
        x-data="{
            async removeArticle(article) {
                if (! confirm('Supprimer cet article du bon de livraison ?')) {
                    return;
                }
                const response = await fetch(@js(route('commercial:blivraison.delete.article')), {
                    method: 'DELETE',
                    headers: { 'Content-Type': 'application/json', Accept: 'application/json', 'X-CSRF-TOKEN': document.querySelector('meta[name=csrf-token]').content },
                    body: JSON.stringify({ command: @js($command->uuid), article }),
                });
                if (response.ok) {
                    window.location.reload();
                }
            },
        }">
        <x-admin.page-header :title="'Bon de livraison '.$command->full_number" :eyebrow="optional($command->company)->name"
            :description="'Créé le '.$command->created_at?->format('d/m/Y à H:i').' · modifié le '.$command->updated_at?->format('d/m/Y à H:i')"
            :breadcrumbs="[['label' => 'Bons de livraison', 'href' => route('commercial:blivraison.index')], ['label' => $command->full_number]]">
            <x-slot:actions>
                <x-admin.button href="{{ $command->url }}" variant="secondary" icon="eye">Aperçu</x-admin.button>
                <x-admin.button variant="secondary" icon="printer" onclick="document.getElementById('print-bl').showModal()">Imprimer</x-admin.button>
                @can('blivraison.delete')
                    <x-admin.delete-action :action="route('commercial:blivraison.delete', ['commandId' => $command->uuid])"
                        :confirm="'Supprimer le bon de livraison '.$command->full_number.' ?'" />
                @endcan
            </x-slot:actions>
        </x-admin.page-header>

        <form id="bl-form" method="POST" action="{{ route('commercial:blivraison.update', $command->uuid) }}" class="space-y-6">
            @csrf
            <input type="hidden" name="company" value="{{ $command->company_id }}">
            <input type="hidden" name="client" value="{{ $command->client_id }}">

            <x-messages.errors />

            <section aria-labelledby="bl-info-title" class="admin-card space-y-5 p-5 sm:p-6">
                <h2 id="bl-info-title" class="admin-section-title">Informations du bon de livraison</h2>

                <dl class="grid gap-4 sm:grid-cols-3">
                    <x-admin.detail-item :label="__('invoice.form.company')">{{ optional($command->company)->name }}</x-admin.detail-item>
                    <x-admin.detail-item :label="__('invoice.form.client')">
                        @if ($command->client)
                            <a href="{{ $command->client->url }}" class="hover:underline">{{ $command->client->entreprise }}</a>
                        @endif
                    </x-admin.detail-item>
                    <x-admin.detail-item label="Numéro"><span class="tabular-nums">{{ $command->full_number }}</span></x-admin.detail-item>
                </dl>

                <div class="grid gap-4 md:grid-cols-3">
                    <x-admin.date-picker name="date_bl" label="Date du BL" :value="old('date_bl', $command->date_bl?->format('Y-m-d'))" required />
                    <x-admin.form-input name="bc_number" label="Numéro de BC" :value="(string) $command->bc_number" help="Le bon de commande du client." required />
                </div>

                <x-admin.textarea name="admin_notes" :label="__('invoice.form.admin_note')" :value="(string) $command->admin_notes" rows="2" />
            </section>

            <section aria-labelledby="bl-articles-title" class="admin-card space-y-5 p-5 sm:p-6">
                <h2 id="bl-articles-title" class="admin-section-title">Articles</h2>

                @if ($errors->article->any())
                    <x-admin.alert type="error" :message="$errors->article->first()" />
                @endif

                @forelse ($command->articles as $article)
                    @php($articleForm = 'article-form-'.$article->uuid)
                    <fieldset class="admin-inset space-y-4 p-4">
                        <legend class="sr-only">Article {{ $loop->iteration }}</legend>
                        <div class="grid gap-4 lg:grid-cols-12">
                            <div class="min-w-0 lg:col-span-6">
                                <label for="{{ $articleForm }}-designation" class="admin-form-label">Désignation</label>
                                <textarea id="{{ $articleForm }}-designation" name="designation" form="{{ $articleForm }}" rows="2" class="admin-form-input min-h-20 py-2" required>{{ str_replace('<br />', '', $article->designation) }}</textarea>
                            </div>
                            <div class="min-w-0 lg:col-span-2">
                                <label for="{{ $articleForm }}-quantity" class="admin-form-label">Quantité</label>
                                <input id="{{ $articleForm }}-quantity" type="number" min="1" step="1" name="quantity" form="{{ $articleForm }}" value="{{ $article->quantity }}" class="admin-form-input tabular-nums" required>
                            </div>
                            <div class="min-w-0 lg:col-span-2">
                                <label for="{{ $articleForm }}-prix" class="admin-form-label">Prix unitaire HT</label>
                                <input id="{{ $articleForm }}-prix" type="text" inputmode="decimal" name="prix_unitaire" form="{{ $articleForm }}" value="{{ $article->prix_unitaire }}" class="admin-form-input tabular-nums" required>
                            </div>
                            <div class="min-w-0 lg:col-span-2">
                                <p class="admin-form-label">Montant HT</p>
                                <p class="flex h-10 items-center font-semibold text-fg"><x-admin.amount :value="$article->montant_ht" /></p>
                            </div>
                        </div>
                        <div class="flex justify-end gap-2">
                            @can('blivraison.delete')
                                <x-admin.button variant="ghost" size="sm" icon="trash" x-on:click="removeArticle(@js($article->uuid))">Supprimer</x-admin.button>
                            @endcan
                            <x-admin.button type="submit" variant="secondary" size="sm" form="{{ $articleForm }}">Mettre à jour l’article</x-admin.button>
                        </div>
                    </fieldset>
                @empty
                    <p class="text-small text-fg-muted">Aucun article sur ce bon de livraison.</p>
                @endforelse

                <dl class="ms-auto max-w-sm space-y-1 text-small">
                    <div class="flex justify-between gap-6"><dt class="text-fg-muted">Total HT</dt><dd><x-admin.amount :value="$command->price_ht" /></dd></div>
                    <div class="flex justify-between gap-6"><dt class="text-fg-muted">TVA 20 %</dt><dd><x-admin.amount :value="$command->price_tva" /></dd></div>
                    <div class="flex justify-between gap-6 border-t border-line pt-1"><dt class="font-semibold text-fg">Total TTC</dt><dd class="font-semibold"><x-admin.amount :value="$command->price_total" /></dd></div>
                </dl>

                <div class="space-y-3 border-t border-line pt-5">
                    <h3 class="text-small font-semibold text-fg">Ajouter des articles</h3>
                    <x-commercial.articles-editor name="articles" :discount="false" />
                </div>
            </section>

            <section aria-labelledby="bl-conditions-title" class="admin-card space-y-5 p-5 sm:p-6">
                <h2 id="bl-conditions-title" class="sr-only">{{ __('invoice.form.condition_general') }}</h2>
                <x-admin.textarea name="condition_general" :label="__('invoice.form.condition_general')" :value="str_replace('<br />', '', (string) $command->condition_general)" rows="4" />

                <div class="flex flex-col-reverse gap-3 border-t border-line pt-6 sm:flex-row sm:justify-end">
                    <x-admin.button href="{{ route('commercial:blivraison.index') }}" variant="secondary">Retour à la liste</x-admin.button>
                    <x-admin.button type="submit">Enregistrer le bon de livraison</x-admin.button>
                </div>
            </section>
        </form>

        {{-- The forms of the existing articles (their fields above use form="…"). --}}
        @foreach ($command->articles as $article)
            <form id="article-form-{{ $article->uuid }}" method="POST" action="{{ route('commercial:blivraison.update.article', [$command->uuid, $article->uuid]) }}" class="hidden">
                @csrf
                @method('PUT')
            </form>
        @endforeach

        <section aria-labelledby="bl-history-title" class="admin-card">
            <div class="admin-card-header">
                <h2 id="bl-history-title" class="admin-section-title">Historique</h2>
            </div>
            <div class="admin-card-body">
                @if ($command->histories->isEmpty())
                    <p class="text-small text-fg-muted">Aucun historique.</p>
                @else
                    <ul class="divide-y divide-line">
                        @foreach ($command->histories->sortByDesc('created_at') as $history)
                            <li class="flex flex-col gap-1 py-2.5 sm:flex-row sm:items-center sm:justify-between">
                                <span class="text-body text-fg"><span class="font-medium">{{ $history->user }}</span> {{ $history->detail }}</span>
                                <span class="text-caption text-fg-muted"><x-admin.date :value="$history->created_at" time /></span>
                            </li>
                        @endforeach
                    </ul>
                @endif
            </div>
        </section>
    </div>

    <x-commercial.print-modal id="print-bl" :title="'Télécharger le bon de livraison '.$command->full_number" route="public.show.bl" :document="$command->uuid" />
@endsection
