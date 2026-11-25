{{--
    Edit an estimate (K2), without Livewire or jQuery. Same structure as the invoice edit page:
    the main form (estimates.update) with the details, tickets, new articles and conditions;
    each existing article saved on its own (estimates.update.article, replacing the Livewire
    EditArticle) through form="…"; deletion through estimates.delete.article (JSON).
--}}
@extends('layouts.admin')

@section('title', 'Devis '.$estimate->full_number)

@section('content')
    @php
        $paymentModes = ['Espèce', 'Virement', 'Chèque', 'Carte bancaire', 'Lettre de change', 'Prélèvement', 'Virement bancaire'];
        $status = \App\Constants\EstimateStatus::tryFrom((int) $estimate->status);
        $attachedTickets = $estimate->tickets->pluck('id')->map(fn ($id) => (string) $id)->all();
    @endphp

    <div class="mx-auto w-full max-w-5xl space-y-6"
        x-data="{
            async removeArticle(article) {
                if (! confirm('Supprimer cet article du devis ?')) {
                    return;
                }
                const response = await fetch(@js(route('commercial:estimates.delete.article')), {
                    method: 'DELETE',
                    headers: { 'Content-Type': 'application/json', Accept: 'application/json', 'X-CSRF-TOKEN': document.querySelector('meta[name=csrf-token]').content },
                    body: JSON.stringify({ estimate: @js($estimate->uuid), article }),
                });
                if (response.ok) {
                    window.location.reload();
                }
            },
        }">
        <x-admin.page-header :title="'Devis '.$estimate->full_number" :eyebrow="optional($estimate->company)->name"
            :description="'Créé le '.$estimate->created_at?->format('d/m/Y à H:i').' · modifié le '.$estimate->updated_at?->format('d/m/Y à H:i')"
            :breadcrumbs="[['label' => __('navbar.estimates'), 'href' => route('commercial:estimates.index')], ['label' => $estimate->full_number]]">
            <x-slot:actions>
                @if ($status)
                    <x-admin.status-badge :status="$status" />
                @endif
                <x-admin.button href="{{ $estimate->url }}" variant="secondary" icon="eye">Aperçu</x-admin.button>
                <x-admin.button variant="secondary" icon="printer" onclick="document.getElementById('print-estimate').showModal()">Imprimer</x-admin.button>
                @unless ($estimate->is_send)
                    <x-admin.button variant="secondary" icon="document" onclick="document.getElementById('send-estimate').showModal()">Envoyer</x-admin.button>
                @endunless
                @if ($estimate->invoice_count)
                    <x-admin.button href="{{ route('public.show.invoice', [$estimate->invoice?->uuid, 'has_header' => true]) }}" target="_blank" rel="noopener" variant="secondary" icon="banknotes">Voir la facture</x-admin.button>
                @elseif (! $estimate->is_invoiced)
                    <x-admin.button href="{{ $estimate->create_invoice_url }}" icon="banknotes">Créer la facture</x-admin.button>
                @endif
                @can('estimates.delete')
                    <x-admin.delete-action :action="route('commercial:estimates.delete', ['estimateId' => $estimate->uuid])"
                        :confirm="'Supprimer le devis '.$estimate->full_number.' ?'" />
                @endcan
            </x-slot:actions>
        </x-admin.page-header>

        <form id="estimate-form" method="POST" action="{{ $estimate->update_url }}" class="space-y-6">
            @csrf
            <input type="hidden" name="company" value="{{ $estimate->company_id }}">
            <input type="hidden" name="client" value="{{ $estimate->client_id }}">

            <x-messages.errors />

            <section aria-labelledby="estimate-info-title" class="admin-card space-y-5 p-5 sm:p-6">
                <h2 id="estimate-info-title" class="admin-section-title">Informations du devis</h2>

                <dl class="grid gap-4 sm:grid-cols-3">
                    <x-admin.detail-item :label="__('invoice.form.company')">{{ optional($estimate->company)->name }}</x-admin.detail-item>
                    <x-admin.detail-item :label="__('invoice.form.client')">
                        @if ($estimate->client)
                            <a href="{{ $estimate->client->url }}" class="hover:underline">{{ $estimate->client->entreprise }}</a>
                        @endif
                    </x-admin.detail-item>
                    <x-admin.detail-item :label="__('estimate.form.number')"><span class="tabular-nums">{{ $estimate->full_number }}</span></x-admin.detail-item>
                </dl>

                <div class="grid gap-4 md:grid-cols-3">
                    <x-admin.date-picker name="estimate_date" :label="__('estimate.form.date_estimate')" :value="old('estimate_date', $estimate->estimate_date?->format('Y-m-d'))" required />
                    <x-admin.date-picker name="due_date" :label="__('estimate.form.date_due')" :value="old('due_date', $estimate->due_date?->format('Y-m-d'))" required />
                    <x-admin.form-select name="payment_mode" :label="__('estimate.form.payment_method')" :options="array_combine($paymentModes, $paymentModes)" :value="(string) $estimate->payment_mode" :placeholder="null" required />
                </div>

                @if ($tickets->isNotEmpty())
                    <fieldset class="space-y-2 border-t border-line pt-5">
                        <legend class="admin-form-label">Tickets</legend>
                        <div class="grid gap-2 sm:grid-cols-3 lg:grid-cols-6">
                            @foreach ($tickets as $ticket)
                                <label class="flex items-center gap-2 text-body">
                                    <input type="checkbox" name="tickets[]" value="{{ $ticket->id }}" class="admin-checkbox" @checked(in_array((string) $ticket->id, array_map('strval', old('tickets', $attachedTickets)), true))>
                                    <a href="{{ route('admin:tickets.single', $ticket->uuid) }}" class="tabular-nums hover:underline">{{ $ticket->is_retour ? $ticket->code_retour : $ticket->code }}</a>
                                </label>
                            @endforeach
                        </div>
                    </fieldset>
                @endif

                <x-admin.textarea name="admin_notes" :label="__('estimate.form.admin_note')" :value="(string) $estimate->admin_notes" rows="2" />
            </section>

            <section aria-labelledby="estimate-articles-title" class="admin-card space-y-5 p-5 sm:p-6">
                <h2 id="estimate-articles-title" class="admin-section-title">Articles</h2>

                @if ($errors->article->any())
                    <x-admin.alert type="error" :message="$errors->article->first()" />
                @endif

                @forelse ($estimate->articles as $article)
                    @php($articleForm = 'article-form-'.$article->uuid)
                    <fieldset class="admin-inset space-y-4 p-4">
                        <legend class="sr-only">Article {{ $loop->iteration }}</legend>
                        <div class="grid gap-4 lg:grid-cols-12">
                            <div class="min-w-0 lg:col-span-5">
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
                            <div class="min-w-0 lg:col-span-1">
                                <label for="{{ $articleForm }}-remise" class="admin-form-label">Remise %</label>
                                <input id="{{ $articleForm }}-remise" type="number" min="0" max="100" step="0.01" name="remise" form="{{ $articleForm }}" value="{{ $article->remise }}" class="admin-form-input tabular-nums">
                            </div>
                            <div class="min-w-0 lg:col-span-2">
                                <p class="admin-form-label">Montant HT</p>
                                <p class="flex h-10 items-center font-semibold text-fg"><x-admin.amount :value="$article->montant_ht" /></p>
                            </div>
                        </div>
                        <div class="flex justify-end gap-2">
                            <x-admin.button variant="ghost" size="sm" icon="trash" x-on:click="removeArticle(@js($article->uuid))">Supprimer</x-admin.button>
                            <x-admin.button type="submit" variant="secondary" size="sm" form="{{ $articleForm }}">Mettre à jour l’article</x-admin.button>
                        </div>
                    </fieldset>
                @empty
                    <p class="text-small text-fg-muted">Aucun article sur ce devis.</p>
                @endforelse

                <dl class="ms-auto max-w-sm space-y-1 text-small">
                    <div class="flex justify-between gap-6"><dt class="text-fg-muted">Total HT</dt><dd><x-admin.amount :value="$estimate->price_ht" /></dd></div>
                    <div class="flex justify-between gap-6"><dt class="text-fg-muted">TVA 20 %</dt><dd><x-admin.amount :value="$estimate->price_tva" /></dd></div>
                    <div class="flex justify-between gap-6 border-t border-line pt-1"><dt class="font-semibold text-fg">Total TTC</dt><dd class="font-semibold"><x-admin.amount :value="$estimate->price_total" /></dd></div>
                </dl>

                <div class="space-y-3 border-t border-line pt-5">
                    <h3 class="text-small font-semibold text-fg">Ajouter des articles</h3>
                    <x-commercial.articles-editor name="articlesnew" />
                </div>
            </section>

            <section aria-labelledby="estimate-conditions-title" class="admin-card space-y-5 p-5 sm:p-6">
                <h2 id="estimate-conditions-title" class="sr-only">{{ __('estimate.form.condition_general') }}</h2>
                <x-admin.textarea name="condition_general" :label="__('estimate.form.condition_general')" :value="(string) $estimate->condition_general" rows="4" />

                <div class="flex flex-col-reverse gap-3 border-t border-line pt-6 sm:flex-row sm:justify-end">
                    <x-admin.button href="{{ route('commercial:estimates.index') }}" variant="secondary">Retour à la liste</x-admin.button>
                    <x-admin.button type="submit">Enregistrer le devis</x-admin.button>
                </div>
            </section>
        </form>

        {{-- The forms of the existing articles (their fields above use form="…"). --}}
        @foreach ($estimate->articles as $article)
            <form id="article-form-{{ $article->uuid }}" method="POST" action="{{ route('commercial:estimates.update.article', [$estimate->uuid, $article->uuid]) }}" class="hidden">
                @csrf
                @method('PUT')
            </form>
        @endforeach

        <section aria-labelledby="estimate-history-title" class="admin-card">
            <div class="admin-card-header">
                <h2 id="estimate-history-title" class="admin-section-title">Historique</h2>
            </div>
            <div class="admin-card-body">
                @if ($estimate->histories->isEmpty())
                    <p class="text-small text-fg-muted">Aucun historique.</p>
                @else
                    <ul class="divide-y divide-line">
                        @foreach ($estimate->histories->sortByDesc('created_at') as $history)
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

    <x-commercial.print-modal id="print-estimate" :title="'Télécharger le devis '.$estimate->full_number" route="public.show.estimate" :document="$estimate->uuid" />
    <x-commercial.send-modal id="send-estimate" :title="'Envoyer le devis '.$estimate->full_number" :action="route('commercial:estimates.send')" field="estimater"
        :document-uuid="$estimate->uuid" :document-id="$estimate->id" :client="$estimate->client" />
@endsection
