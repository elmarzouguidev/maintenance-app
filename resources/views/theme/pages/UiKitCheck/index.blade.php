{{--
    UI kit install check (/_ui-kit-check, local only): the tenant layout with a sample of every
    kit component and state, to compare with ui-kit/static/index.html at 360, 768 and 1440 px.
    Fictional data comes from Developper\UiKitCheckController. The form never posts.
--}}
@extends('layouts.admin')

@section('title', 'Vérification du kit')

@section('content')
    <div class="space-y-8">
        <x-admin.page-header title="Vérification du kit"
            description="Chaque composant du kit et ses états, dans la mise en page de l’espace atelier."
            :breadcrumbs="[['label' => 'Accueil', 'href' => url('/app')], ['label' => 'Vérification du kit']]">
            <x-slot:actions>
                <x-admin.button href="#" variant="secondary" icon="download">Exporter</x-admin.button>
                <x-admin.button href="#" icon="plus">Nouveau ticket</x-admin.button>
            </x-slot:actions>
        </x-admin.page-header>

        {{-- Stats grid: counts (4 per row), then amounts (3 per row, never wrapped). --}}
        <section aria-labelledby="check-stats" class="space-y-4">
            <h2 id="check-stats" class="admin-section-title">Indicateurs</h2>
            <x-admin.stats-grid>
                <x-admin.stats-card title="Tickets ouverts" value="42" icon="wrench" />
                <x-admin.stats-card title="En retard" value="3" icon="warning" variant="danger" href="#" button-text="Voir les tickets en retard" />
                <x-admin.stats-card title="En attente de pièce" value="7" icon="clock" variant="warning" href="#" />
                <x-admin.stats-card title="Filtre actif" value="5" icon="check" href="#" selected />
            </x-admin.stats-grid>
            <x-admin.stats-grid amounts>
                <x-admin.stats-card title="Facturé ce mois" :value="\App\Support\UiKit\Format::money(184250.5)" icon="banknotes" variant="success" />
                <x-admin.stats-card title="Reste à encaisser" :value="\App\Support\UiKit\Format::money(12400)" icon="card" variant="warning" />
                <x-admin.stats-card title="Devis en attente" :value="\App\Support\UiKit\Format::money(36000)" icon="document" variant="info" />
            </x-admin.stats-grid>
        </section>

        {{-- Table with status badges, formatted cells, row actions and pagination. --}}
        <section aria-labelledby="check-table" class="space-y-4">
            <h2 id="check-table" class="admin-section-title">Tickets</h2>
            <x-admin.table :headers="['Ticket', 'Client', 'Article', 'Réception', 'Montant', 'Statut', '']">
                @foreach ($tickets as $ticket)
                    <tr>
                        <td class="font-medium tabular-nums"><a href="#" class="hover:underline">{{ $ticket['code'] }}</a></td>
                        <td>{{ $ticket['client'] }}</td>
                        <td>{{ $ticket['article'] }}</td>
                        <td><x-admin.date :value="$ticket['received_at']" time /></td>
                        <td><x-admin.amount :value="$ticket['amount']" /></td>
                        <td><x-admin.status-badge :variant="$ticket['status']['variant']" :label="$ticket['status']['label']" /></td>
                        <td class="text-end"><x-admin.icon-button icon="eye" :label="'Voir '.$ticket['code']" href="#" size="sm" /></td>
                    </tr>
                @endforeach
            </x-admin.table>
            {{ $tickets->links('pagination::admin') }}
        </section>

        {{-- Form: every field type, with validation errors (faked by the controller). --}}
        <section aria-labelledby="check-form" class="space-y-4">
            <h2 id="check-form" class="admin-section-title">Formulaire</h2>
            <form method="POST" action="#" class="admin-card space-y-6 p-5 sm:p-6" x-on:submit.prevent>
                <x-messages.errors />

                <div class="grid gap-4 md:grid-cols-2">
                    <x-admin.form-input name="client_name" label="Nom du client" placeholder="Ex. Amal Benali" required autocomplete="name" />
                    <x-admin.form-input name="client_email" type="email" label="E-mail" value="amal.benali@" inputmode="email" />
                    <x-admin.form-input name="client_phone" label="Téléphone" inputmode="tel" autocomplete="tel" help="Format : 06 12 34 56 78." />
                    <x-admin.form-input name="estimate_amount" type="number" label="Montant estimé (DH)" value="1250" inputmode="decimal" />
                    <x-admin.form-input name="ticket_code" label="Code" value="TCK-2026-0148" readonly />
                    <x-admin.form-input name="disabled_field" label="Champ désactivé" value="Non modifiable" disabled />

                    <x-admin.form-select name="technician_id" label="Technicien" :options="['1' => 'Karim Alaoui', '2' => 'Sara Bennani', '3' => 'Omar Tazi']" value="2" placeholder="Aucun technicien" />
                    <x-admin.form-select2 name="category_id" label="Catégorie" :options="['1' => 'Imprimantes', '2' => 'Ordinateurs portables', '3' => 'Onduleurs', '4' => 'Écrans', '5' => 'Serveurs']" required />

                    <x-admin.segmented-radio name="priority" label="Priorité" :options="['LOW' => 'Basse', 'NORMAL' => 'Normale', 'HIGH' => 'Haute', 'URGENT' => 'Urgente']" required />
                    <x-admin.segmented-radio name="warranty" label="Sous garantie" :options="['YES' => 'Oui', 'NO' => 'Non']" value="NO" help="Selon la facture d’achat du client." />

                    <x-admin.date-picker name="received_at" label="Date de réception" required />
                    <x-admin.date-picker name="promised_at" label="Livraison promise" value="2026-11-28 17:00" with-time />
                    <x-admin.date-picker name="pickup_time" label="Heure de passage" value="10:30" time-only />
                    <x-admin.date-picker name="delivered_at" label="Livraison (natif sur téléphone)" value="2026-11-30 18:00" with-time native />

                    <fieldset class="space-y-2">
                        <legend class="admin-form-label">Accessoires déposés</legend>
                        <label class="flex items-center gap-2 text-body"><input type="checkbox" name="accessories[]" value="charger" class="admin-checkbox" checked> Chargeur</label>
                        <label class="flex items-center gap-2 text-body"><input type="checkbox" name="accessories[]" value="bag" class="admin-checkbox"> Sacoche</label>
                        <label class="flex items-center gap-2 text-body text-fg-muted"><input type="checkbox" name="accessories[]" value="dock" class="admin-checkbox" disabled> Station d’accueil (non reçue)</label>
                    </fieldset>
                    <label class="relative inline-flex cursor-pointer items-center gap-3 self-start text-body">
                        <input type="checkbox" name="notify_client" value="1" class="peer sr-only" checked>
                        <span class="admin-switch" aria-hidden="true"></span>
                        Prévenir le client par SMS à la livraison
                    </label>

                    <x-admin.textarea name="description" label="Panne déclarée" placeholder="Ce que le client a constaté" rows="3" required class="md:col-span-2" />

                    <x-admin.file-upload name="photo" label="Photo de l’appareil" preview :preview-url="asset(config('brand.logo_mark'))" help="Visible sur la fiche de dépôt." />
                    <x-admin.file-upload name="files[]" label="Documents du client" accept="image/*,application/pdf" multiple max-size="5 Mo" hint="Images ou PDF, 5 Mo au plus par fichier." />
                </div>

                <div class="flex flex-col-reverse gap-3 border-t border-line pt-6 sm:flex-row sm:justify-end">
                    <x-admin.button href="#" variant="secondary">Annuler</x-admin.button>
                    <x-admin.button type="submit">Enregistrer le ticket</x-admin.button>
                </div>
            </form>
        </section>

        {{-- Buttons: variants, sizes, states, links, icon buttons and delete actions. --}}
        <section aria-labelledby="check-buttons" class="admin-card">
            <div class="admin-card-header">
                <h2 id="check-buttons" class="admin-section-title">Boutons</h2>
            </div>
            <div class="admin-card-body space-y-5">
                <div class="flex flex-wrap items-center gap-3">
                    <x-admin.button icon="plus">Nouveau ticket</x-admin.button>
                    <x-admin.button variant="secondary">Annuler</x-admin.button>
                    <x-admin.button variant="ghost">Plus tard</x-admin.button>
                    <x-admin.button variant="danger" icon="trash">Supprimer</x-admin.button>
                </div>
                <div class="flex flex-wrap items-center gap-3">
                    <x-admin.button size="sm" variant="secondary" icon="pencil">Petit</x-admin.button>
                    <x-admin.button>Moyen</x-admin.button>
                    <x-admin.button size="lg">Grand</x-admin.button>
                </div>
                <div class="flex flex-wrap items-center gap-3">
                    <x-admin.button loading>Enregistrement</x-admin.button>
                    <x-admin.button variant="secondary" disabled aria-describedby="check-disabled-reason">Envoyer le devis</x-admin.button>
                    <p id="check-disabled-reason" class="text-caption text-fg-muted">Ajoutez au moins une ligne au devis pour l’envoyer.</p>
                </div>
                <div class="flex flex-wrap items-center gap-3">
                    <x-admin.button href="#" variant="secondary" icon="printer">Imprimer la fiche de dépôt</x-admin.button>
                    <x-admin.button href="#" icon="truck">Livrer le ticket</x-admin.button>
                </div>
                <div class="flex flex-wrap items-center gap-3">
                    <x-admin.icon-button icon="pencil" label="Modifier" />
                    <x-admin.icon-button icon="trash" label="Supprimer" size="sm" />
                    <x-admin.icon-button icon="eye" label="Voir la fiche" href="#" />
                    <x-admin.icon-button icon="ellipsis" label="Plus d’actions" disabled />
                </div>
                <div class="flex flex-wrap items-center gap-3">
                    <x-admin.delete-action action="#" confirm="Supprimer le ticket TCK-2026-0148 ? Cette action est définitive." />
                    <x-admin.delete-action action="#tickets/2" confirm="Supprimer ce ticket ?" refusal="Impossible : ce ticket a une facture émise." />
                </div>
            </div>
        </section>

        {{-- Alerts. --}}
        <section aria-labelledby="check-alerts" class="space-y-3">
            <h2 id="check-alerts" class="admin-section-title">Alertes</h2>
            <x-admin.alert type="success" message="Le ticket a été livré au client." />
            <x-admin.alert type="error" message="Le devis n’a pas pu être envoyé : l’adresse e-mail du client est absente." />
            <x-admin.alert type="warning" message="La pièce commandée n’est pas encore arrivée." />
            <x-admin.alert type="info" :dismissible="false" message="Ce ticket est facturé : il ne peut plus être modifié." />
        </section>

        {{-- Empty state. --}}
        <section aria-labelledby="check-empty" class="space-y-4">
            <h2 id="check-empty" class="admin-section-title">État vide</h2>
            <div class="admin-card">
                <x-admin.empty-state icon="filter" title="Aucun résultat" description="Aucun ticket ne correspond à ces filtres.">
                    <x-admin.button href="#" variant="secondary">Effacer les filtres</x-admin.button>
                </x-admin.empty-state>
            </div>
        </section>

        {{-- Modal (native dialog: focus trap, Escape, focus return). --}}
        <section aria-labelledby="check-modal" class="space-y-4">
            <h2 id="check-modal" class="admin-section-title">Fenêtre modale</h2>
            <x-admin.button variant="danger" icon="x-circle" onclick="document.getElementById('cancel-ticket').showModal()">Annuler le ticket</x-admin.button>

            <x-admin.modal id="cancel-ticket" title="Annuler le ticket ?" description="Le client sera prévenu. Le diagnostic reste enregistré.">
                <x-admin.textarea name="cancel_reason" label="Motif" rows="2" required />
                <x-slot:footer>
                    <x-admin.button variant="secondary" onclick="this.closest('dialog').close()">Garder le ticket</x-admin.button>
                    <x-admin.button variant="danger" onclick="this.closest('dialog').close()">Annuler le ticket</x-admin.button>
                </x-slot:footer>
            </x-admin.modal>
        </section>
    </div>
@endsection
