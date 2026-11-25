{{--
    Edit an invoice (K2), without Livewire or jQuery.
    - The main form (invoices.update) holds the details, the tickets, the new articles
      (<x-commercial.articles-editor name="articlesnew">) and the conditions.
    - Each existing article is saved on its own (invoices.update.article, replacing the Livewire
      EditArticle component): its fields belong to a small form outside the main one through the
      form="…" attribute, since forms cannot nest. Deleting calls invoices.delete.article (JSON).
    - A paid invoice (with a payment) is read-only, as before.
--}}
@extends('layouts.admin')

@section('title', 'Facture '.$invoice->full_number)

@section('content')
    @php
        $locked = $invoice->bill_count > 0;
        $paymentModes = ['Espèce', 'Virement', 'Chèque', 'Carte bancaire', 'Lettre de change', 'Prélèvement', 'Virement bancaire'];
        $status = \App\Constants\InvoiceStatus::tryFrom((string) $invoice->status);
        $attachedTickets = $invoice->tickets->pluck('id')->map(fn ($id) => (string) $id)->all();
    @endphp

    <div class="mx-auto w-full max-w-5xl space-y-6"
        x-data="{
            async removeArticle(article) {
                if (! confirm('Supprimer cet article de la facture ?')) {
                    return;
                }
                const response = await fetch(@js(route('commercial:invoices.delete.article')), {
                    method: 'DELETE',
                    headers: { 'Content-Type': 'application/json', Accept: 'application/json', 'X-CSRF-TOKEN': document.querySelector('meta[name=csrf-token]').content },
                    body: JSON.stringify({ invoice: @js($invoice->uuid), article }),
                });
                if (response.ok) {
                    window.location.reload();
                }
            },
        }">
        <x-admin.page-header :title="'Facture '.$invoice->full_number" :eyebrow="optional($invoice->company)->name"
            :description="'Créée le '.$invoice->created_at?->format('d/m/Y à H:i').' · modifiée le '.$invoice->updated_at?->format('d/m/Y à H:i')"
            :breadcrumbs="[['label' => __('navbar.invoices'), 'href' => route('commercial:invoices.index')], ['label' => $invoice->full_number]]">
            <x-slot:actions>
                @if ($status)
                    <x-admin.status-badge :status="$status" />
                @endif
                <x-admin.button href="{{ $invoice->url }}" variant="secondary" icon="eye">Aperçu</x-admin.button>
                <x-admin.button variant="secondary" icon="printer" onclick="document.getElementById('print-invoice').showModal()">Imprimer</x-admin.button>
                @unless ($invoice->is_send)
                    <x-admin.button variant="secondary" icon="document" onclick="document.getElementById('send-invoice').showModal()">Envoyer</x-admin.button>
                @endunless
                @can('invoices.delete')
                    <x-admin.delete-action :action="route('commercial:invoices.delete', ['invoiceId' => $invoice->uuid])"
                        :confirm="'Supprimer la facture '.$invoice->full_number.' ? Ses articles et son historique seront supprimés.'" />
                @endcan
            </x-slot:actions>
        </x-admin.page-header>

        @if ($locked)
            <x-admin.alert type="info" :dismissible="false">
                Cette facture a un règlement : elle ne peut plus être modifiée.
                @if ($invoice->bill)
                    <a href="{{ route('commercial:bills.edit', $invoice->bill->uuid) }}" class="underline">Voir le règlement</a>
                @endif
            </x-admin.alert>
        @endif

        <form id="invoice-form" method="POST" action="{{ $invoice->update_url }}" class="space-y-6">
            @csrf
            <input type="hidden" name="company" value="{{ $invoice->company_id }}">
            <input type="hidden" name="client" value="{{ $invoice->client_id }}">

            <x-messages.errors />

            <fieldset class="admin-card space-y-5 p-5 sm:p-6" @disabled($locked)>
                <legend class="sr-only">{{ __('invoice.form.title') }}</legend>
                <h2 class="admin-section-title">{{ __('invoice.form.title') }}</h2>

                <dl class="grid gap-4 sm:grid-cols-3">
                    <x-admin.detail-item :label="__('invoice.form.company')">{{ optional($invoice->company)->name }}</x-admin.detail-item>
                    <x-admin.detail-item :label="__('invoice.form.client')">
                        @if ($invoice->client)
                            <a href="{{ $invoice->client->url }}" class="hover:underline">{{ $invoice->client->entreprise }}</a>
                        @endif
                    </x-admin.detail-item>
                    <x-admin.detail-item :label="__('invoice.form.number')"><span class="tabular-nums">{{ $invoice->full_number }}</span></x-admin.detail-item>
                </dl>

                <div class="grid gap-4 md:grid-cols-3">
                    <x-admin.date-picker name="invoice_date" :label="__('invoice.form.date_invoice')" :value="old('invoice_date', $invoice->invoice_date?->format('Y-m-d'))" required />
                    <x-admin.date-picker name="due_date" :label="__('invoice.form.date_due')" :value="old('due_date', $invoice->due_date?->format('Y-m-d'))" required />
                    <x-admin.form-select name="payment_mode" :label="__('invoice.form.payment_method')" :options="array_combine($paymentModes, $paymentModes)" :value="(string) $invoice->payment_mode" :placeholder="null" required />
                    <x-admin.form-input name="bc_code" label="BC" :value="(string) $invoice->bc_code" />
                    <x-admin.form-input name="bl_code" label="BL" :value="(string) $invoice->bl_code" />
                    @if ($invoice->tickets_count === 0)
                        <x-admin.form-select name="ticket" label="Ticket" :options="$tickets->pluck('code', 'id')->all()" :value="(string) $invoice->ticket_id" placeholder="Aucun ticket" />
                    @endif
                </div>

                @if ($invoice->tickets_count > 0)
                    <fieldset class="space-y-2 border-t border-line pt-5">
                        <legend class="admin-form-label">Tickets</legend>
                        <div class="grid gap-2 sm:grid-cols-3 lg:grid-cols-6">
                            @foreach ($tickets as $ticket)
                                <label class="flex items-center gap-2 text-body">
                                    <input type="checkbox" name="tickets[]" value="{{ $ticket->id }}" class="admin-checkbox" @checked(in_array((string) $ticket->id, old('tickets', $attachedTickets), true))>
                                    <a href="{{ route('admin:tickets.single', $ticket->uuid) }}" class="tabular-nums hover:underline">{{ $ticket->is_retour ? $ticket->code_retour : $ticket->code }}</a>
                                </label>
                            @endforeach
                        </div>
                    </fieldset>
                @endif

                <x-admin.textarea name="admin_notes" :label="__('invoice.form.admin_note')" :value="(string) $invoice->admin_notes" rows="2" />
            </fieldset>

            <section aria-labelledby="invoice-articles-title" class="admin-card space-y-5 p-5 sm:p-6">
                <h2 id="invoice-articles-title" class="admin-section-title">Articles</h2>

                @if ($errors->article->any())
                    <x-admin.alert type="error" :message="$errors->article->first()" />
                @endif

                @forelse ($invoice->articles as $article)
                    @php($articleForm = 'article-form-'.$article->uuid)
                    <fieldset class="admin-inset space-y-4 p-4" @disabled($locked)>
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
                        @unless ($locked)
                            <div class="flex justify-end gap-2">
                                <x-admin.button variant="ghost" size="sm" icon="trash" x-on:click="removeArticle(@js($article->uuid))">Supprimer</x-admin.button>
                                <x-admin.button type="submit" variant="secondary" size="sm" form="{{ $articleForm }}">Mettre à jour l’article</x-admin.button>
                            </div>
                        @endunless
                    </fieldset>
                @empty
                    <p class="text-small text-fg-muted">Aucun article sur cette facture.</p>
                @endforelse

                <dl class="ms-auto max-w-sm space-y-1 text-small">
                    <div class="flex justify-between gap-6"><dt class="text-fg-muted">Total HT</dt><dd><x-admin.amount :value="$invoice->price_ht" /></dd></div>
                    @if ($invoice->formated_total_remise > 0)
                        <div class="flex justify-between gap-6"><dt class="text-fg-muted">Dont remises</dt><dd class="tabular-nums">{{ $invoice->formated_total_remise }} DH</dd></div>
                    @endif
                    <div class="flex justify-between gap-6"><dt class="text-fg-muted">TVA 20 %</dt><dd><x-admin.amount :value="$invoice->price_tva" /></dd></div>
                    <div class="flex justify-between gap-6 border-t border-line pt-1"><dt class="font-semibold text-fg">Total TTC</dt><dd class="font-semibold"><x-admin.amount :value="$invoice->price_total" /></dd></div>
                </dl>

                @unless ($locked)
                    <div class="space-y-3 border-t border-line pt-5">
                        <h3 class="text-small font-semibold text-fg">Ajouter des articles</h3>
                        <x-commercial.articles-editor name="articlesnew" />
                    </div>
                @endunless
            </section>

            <fieldset class="admin-card space-y-5 p-5 sm:p-6" @disabled($locked)>
                <legend class="sr-only">{{ __('invoice.form.condition_general') }}</legend>
                <x-admin.textarea name="condition_general" :label="__('invoice.form.condition_general')" :value="(string) $invoice->condition_general" rows="4" />

                @unless ($locked)
                    <div class="flex flex-col-reverse gap-3 border-t border-line pt-6 sm:flex-row sm:justify-end">
                        <x-admin.button href="{{ route('commercial:invoices.index') }}" variant="secondary">Retour à la liste</x-admin.button>
                        <x-admin.button type="submit">Enregistrer la facture</x-admin.button>
                    </div>
                @endunless
            </fieldset>
        </form>

        {{-- The forms of the existing articles (their fields above use form="…"). --}}
        @foreach ($invoice->articles as $article)
            <form id="article-form-{{ $article->uuid }}" method="POST" action="{{ route('commercial:invoices.update.article', [$invoice->uuid, $article->uuid]) }}" class="hidden">
                @csrf
                @method('PUT')
            </form>
        @endforeach

        <section aria-labelledby="invoice-history-title" class="admin-card">
            <div class="admin-card-header">
                <h2 id="invoice-history-title" class="admin-section-title">Historique</h2>
            </div>
            <div class="admin-card-body">
                @if ($invoice->histories->isEmpty())
                    <p class="text-small text-fg-muted">Aucun historique.</p>
                @else
                    <ul class="divide-y divide-line">
                        @foreach ($invoice->histories->sortByDesc('created_at') as $history)
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

    @include('theme.pages.Commercial.Invoice._print_modal', ['invoice' => $invoice])
    @include('theme.pages.Commercial.Invoice._send_modal', ['invoice' => $invoice])
@endsection
