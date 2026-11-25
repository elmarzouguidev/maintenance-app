{{--
    Edit a purchase order (K2), without Livewire, jQuery or SweetAlert. Same structure as the
    credit note edit page: the main form (bcommandes.update) with the details, new articles and
    conditions; each existing article saved on its own (bcommandes.update.article) through
    form="…"; deletion through bcommandes.delete.article (JSON). The administration note is now
    filled in: the former form posted it empty and erased it on every save (BL-81).
--}}
@extends('layouts.admin')

@section('title', 'Bon de commande '.$command->full_number)

@section('content')
    <div class="mx-auto w-full max-w-5xl space-y-6"
        x-data="{
            async removeArticle(article) {
                if (! confirm('Supprimer cet article du bon de commande ?')) {
                    return;
                }
                const response = await fetch(@js(route('commercial:bcommandes.delete.article')), {
                    method: 'DELETE',
                    headers: { 'Content-Type': 'application/json', Accept: 'application/json', 'X-CSRF-TOKEN': document.querySelector('meta[name=csrf-token]').content },
                    body: JSON.stringify({ command: @js($command->uuid), article }),
                });
                if (response.ok) {
                    window.location.reload();
                }
            },
        }">
        <x-admin.page-header :title="'Bon de commande '.$command->full_number" :eyebrow="optional($command->company)->name"
            :description="'Créé le '.$command->created_at?->format('d/m/Y à H:i').' · modifié le '.$command->updated_at?->format('d/m/Y à H:i')"
            :breadcrumbs="[['label' => 'Bons de commande', 'href' => route('commercial:bcommandes.index')], ['label' => $command->full_number]]">
            <x-slot:actions>
                <x-admin.button href="{{ $command->url }}" variant="secondary" icon="eye">Aperçu</x-admin.button>
                <x-admin.button variant="secondary" icon="printer" onclick="document.getElementById('print-bc').showModal()">Imprimer</x-admin.button>
                @if ($command->is_send)
                    <x-admin.badge variant="neutral">Envoyé</x-admin.badge>
                @else
                    <x-admin.button variant="secondary" icon="document" onclick="document.getElementById('send-bc').showModal()">Envoyer</x-admin.button>
                @endif
                @can('bcommandes.delete')
                    <x-admin.delete-action :action="route('commercial:bcommandes.delete', ['commandId' => $command->uuid])"
                        :confirm="'Supprimer le bon de commande '.$command->full_number.' ?'" />
                @endcan
            </x-slot:actions>
        </x-admin.page-header>

        <form id="bc-form" method="POST" action="{{ route('commercial:bcommandes.update', $command->uuid) }}" class="space-y-6">
            @csrf
            <input type="hidden" name="company" value="{{ $command->company_id }}">
            <input type="hidden" name="provider" value="{{ $command->provider_id }}">

            <x-messages.errors />

            <section aria-labelledby="bc-info-title" class="admin-card space-y-5 p-5 sm:p-6">
                <h2 id="bc-info-title" class="admin-section-title">Informations du bon de commande</h2>

                <dl class="grid gap-4 sm:grid-cols-3">
                    <x-admin.detail-item :label="__('invoice.form.company')">{{ optional($command->company)->name }}</x-admin.detail-item>
                    <x-admin.detail-item label="Fournisseur">{{ optional($command->provider)->entreprise }}</x-admin.detail-item>
                    <x-admin.detail-item label="Numéro"><span class="tabular-nums">{{ $command->full_number }}</span></x-admin.detail-item>
                </dl>

                <div class="grid gap-4 md:grid-cols-3">
                    <x-admin.date-picker name="date_command" label="Date du bon" :value="old('date_command', $command->date_command?->format('Y-m-d'))" required />
                </div>

                <x-admin.textarea name="admin_notes" :label="__('invoice.form.admin_note')" :value="(string) $command->admin_notes" rows="2" />
            </section>

            <section aria-labelledby="bc-articles-title" class="admin-card space-y-5 p-5 sm:p-6">
                <h2 id="bc-articles-title" class="admin-section-title">Articles</h2>

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
                            @can('bcommandes.delete')
                                <x-admin.button variant="ghost" size="sm" icon="trash" x-on:click="removeArticle(@js($article->uuid))">Supprimer</x-admin.button>
                            @endcan
                            <x-admin.button type="submit" variant="secondary" size="sm" form="{{ $articleForm }}">Mettre à jour l’article</x-admin.button>
                        </div>
                    </fieldset>
                @empty
                    <p class="text-small text-fg-muted">Aucun article sur ce bon de commande.</p>
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

            <section aria-labelledby="bc-conditions-title" class="admin-card space-y-5 p-5 sm:p-6">
                <h2 id="bc-conditions-title" class="sr-only">{{ __('invoice.form.condition_general') }}</h2>
                <x-admin.textarea name="condition_general" :label="__('invoice.form.condition_general')" :value="str_replace('<br />', '', (string) $command->condition_general)" rows="4" />

                <div class="flex flex-col-reverse gap-3 border-t border-line pt-6 sm:flex-row sm:justify-end">
                    <x-admin.button href="{{ route('commercial:bcommandes.index') }}" variant="secondary">Retour à la liste</x-admin.button>
                    <x-admin.button type="submit">Enregistrer le bon de commande</x-admin.button>
                </div>
            </section>
        </form>

        {{-- The forms of the existing articles (their fields above use form="…"). --}}
        @foreach ($command->articles as $article)
            <form id="article-form-{{ $article->uuid }}" method="POST" action="{{ route('commercial:bcommandes.update.article', [$command->uuid, $article->uuid]) }}" class="hidden">
                @csrf
                @method('PUT')
            </form>
        @endforeach

        <section aria-labelledby="bc-history-title" class="admin-card">
            <div class="admin-card-header">
                <h2 id="bc-history-title" class="admin-section-title">Historique</h2>
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

    <x-commercial.print-modal id="print-bc" :title="'Télécharger le bon de commande '.$command->full_number" route="public.show.bcommand" :document="$command->uuid" />
    @unless ($command->is_send)
        <x-commercial.send-modal id="send-bc" :title="'Envoyer le bon de commande '.$command->full_number" :action="route('commercial:bcommandes.send')" field="bc"
            :document-uuid="$command->uuid" :document-id="$command->id" :client="$command->provider" />
    @endunless
@endsection
