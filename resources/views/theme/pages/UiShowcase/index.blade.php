@extends('theme.layouts.tailwind')

{{-- Local-only showcase of the Tailwind component kit (route "dev.ui", /_ui). Not linked from the menu. --}}

@section('content')
    <x-page-title title="UI Kit — Tailwind" :breadcrumbs="['Accueil' => route('admin:home'), 'Développement' => null, 'UI Kit' => null]">
        <x-badge variant="info" soft>Local uniquement</x-badge>
    </x-page-title>

    <x-flash-messages />

    {{-- Buttons --}}
    <x-card title="Boutons" description="x-button : variantes, contour, tailles, pilule, icône, pleine largeur, lien et état désactivé.">
        <div class="space-y-4">
            <div class="flex flex-wrap items-center gap-2">
                @foreach (['primary', 'secondary', 'success', 'danger', 'warning', 'info', 'light', 'dark', 'link'] as $variant)
                    <x-button type="button" :variant="$variant">{{ ucfirst($variant) }}</x-button>
                @endforeach
            </div>

            <div class="flex flex-wrap items-center gap-2">
                @foreach (['primary', 'secondary', 'success', 'danger', 'warning', 'info', 'light', 'dark'] as $variant)
                    <x-button type="button" :variant="$variant" outline>{{ ucfirst($variant) }}</x-button>
                @endforeach
            </div>

            <div class="flex flex-wrap items-center gap-2">
                <x-button type="button" size="sm">Petit (sm)</x-button>
                <x-button type="button">Normal (md)</x-button>
                <x-button type="button" variant="warning" size="sm" pill>Pilule sm</x-button>
                <x-button type="button" variant="primary" pill>Pilule</x-button>
                <x-button type="button" variant="success" icon="bx bx-check-double">Avec icône</x-button>
                <x-button type="button" variant="danger" icon="bx bx-trash" size="sm">Supprimer</x-button>
                <x-button href="#buttons" variant="info" icon="bx bx-link-external">Lien (&lt;a&gt;)</x-button>
                <x-button type="button" disabled>Désactivé</x-button>
                <x-button type="button" variant="secondary" outline disabled>Contour désactivé</x-button>
            </div>

            <div class="max-w-sm">
                <x-button type="button" variant="primary" block icon="bx bx-save">Pleine largeur (block)</x-button>
            </div>
        </div>
    </x-card>

    <div class="grid grid-cols-12 gap-x-6">
        {{-- Badges --}}
        <div class="col-span-12 xl:col-span-6">
            <x-card title="Badges" description="x-badge : plein, doux (soft) et pilule.">
                <div class="space-y-3">
                    @foreach ([false, true] as $soft)
                        <div class="flex flex-wrap items-center gap-2">
                            @foreach (['primary', 'secondary', 'success', 'danger', 'warning', 'info', 'light', 'dark', 'pink'] as $variant)
                                <x-badge :variant="$variant" :soft="$soft">{{ $variant }}</x-badge>
                            @endforeach
                        </div>
                    @endforeach
                    <div class="flex flex-wrap items-center gap-2">
                        @foreach (['primary', 'success', 'danger', 'warning', 'info'] as $variant)
                            <x-badge :variant="$variant" pill>{{ $loop->iteration * 3 }}</x-badge>
                            <x-badge :variant="$variant" pill soft>Pilule soft</x-badge>
                        @endforeach
                    </div>
                </div>
            </x-card>
        </div>

        {{-- Avatars --}}
        <div class="col-span-12 xl:col-span-6">
            <x-card title="Avatars" description="x-avatar : tailles, couleurs, doux, carré et image.">
                <div class="space-y-4">
                    <div class="flex flex-wrap items-end gap-3">
                        @foreach (['xs', 'sm', 'md', 'lg', 'xl'] as $size)
                            <x-avatar :size="$size">{{ strtoupper($size) }}</x-avatar>
                        @endforeach
                    </div>
                    <div class="flex flex-wrap items-center gap-3">
                        @foreach (['primary', 'success', 'danger', 'warning', 'info', 'dark'] as $variant)
                            <x-avatar :variant="$variant" soft><i class="bx bx-user" aria-hidden="true"></i></x-avatar>
                        @endforeach
                        <x-avatar variant="info" square>AB</x-avatar>
                        <x-avatar size="sm" :src="asset('assets/images/logo.png')" alt="Logo" />
                    </div>
                </div>
            </x-card>
        </div>
    </div>

    {{-- Alerts --}}
    <x-card title="Alertes" description="x-alert : toutes les variantes, avec icône et fermable.">
        <div class="space-y-3">
            @foreach (['primary', 'secondary', 'success', 'danger', 'warning', 'info'] as $variant)
                <x-alert :variant="$variant">Alerte <strong>{{ $variant }}</strong> : un message simple.</x-alert>
            @endforeach
            <x-alert variant="success" icon="mdi mdi-check-all" dismissible>Alerte avec icône, fermable (×).</x-alert>
            <x-alert variant="danger" icon="mdi mdi-block-helper" dismissible>Erreur fermable avec icône.</x-alert>
        </div>
    </x-card>

    {{-- Forms --}}
    <x-card title="Formulaires" description="x-input, x-select, x-textarea, x-checkbox, x-radio, x-field. Les erreurs ci-dessous sont simulées.">
        <form action="#" onsubmit="return false" class="grid grid-cols-12 gap-x-6 gap-y-6">
            <div class="col-span-12 md:col-span-6">
                <x-input name="name" label="Champ normal" placeholder="Saisir un texte" required hint="Texte d'aide sous le champ." />
            </div>
            <div class="col-span-12 md:col-span-6">
                <x-input name="email" type="email" label="Champ en erreur" value="pas-un-email" />
            </div>
            <div class="col-span-12 md:col-span-6">
                <x-input name="amount" label="Prepend + append (texte)" prepend="MAD" append=",00" value="0" />
            </div>
            <div class="col-span-12 md:col-span-6">
                <x-input name="end_at" label="Append (icône)" placeholder="jj-mm-aaaa" append-icon="mdi mdi-calendar" />
            </div>
            <div class="col-span-12 md:col-span-6">
                <x-input name="search" label="Prepend (icône), petite taille" size="sm" prepend-icon="bx bx-search" placeholder="Rechercher…" />
            </div>
            <div class="col-span-12 md:col-span-3">
                <x-input name="locked" label="Désactivé" value="Non modifiable" disabled />
            </div>
            <div class="col-span-12 md:col-span-3">
                <x-input name="readonly" label="Lecture seule" value="FAC-2026-0001" readonly />
            </div>

            <div class="col-span-12">
                <x-input name="horizontal" label="Horizontal" placeholder="Disposition label à gauche (col-lg-2 / col-lg-10)" horizontal />
            </div>

            <div class="col-span-12 md:col-span-4">
                <x-select name="status" label="Select" placeholder="Choisir un statut" :options="['open' => 'Ouvert', 'pending' => 'En attente', 'closed' => 'Fermé']" value="pending" />
            </div>
            <div class="col-span-12 md:col-span-4">
                <x-select name="company" label="Select en erreur" placeholder="Choisir une société" :options="['1' => 'CASA MAINTENANCE', '2' => 'WEDO APP']" required />
            </div>
            <div class="col-span-12 md:col-span-4">
                <x-select name="size_sm" label="Select petite taille" size="sm" :options="['10' => '10 lignes', '25' => '25 lignes', '50' => '50 lignes']" />
            </div>
            <div class="col-span-12 md:col-span-6">
                <x-select name="tags[]" label="Select multiple" multiple :options="['a' => 'Diagnostic', 'b' => 'Réparation', 'c' => 'Livraison', 'd' => 'Garantie']" :value="['b', 'c']" size="sm" />
            </div>
            <div class="col-span-12 md:col-span-6">
                <x-select name="grouped" label="Select avec optgroup (slot)">
                    <optgroup label="Clients">
                        <option value="1">Client A</option>
                        <option value="2" selected>Client B</option>
                    </optgroup>
                    <optgroup label="Fournisseurs">
                        <option value="3">Fournisseur C</option>
                    </optgroup>
                </x-select>
            </div>

            <div class="col-span-12 md:col-span-6">
                <x-textarea name="comment" label="Textarea" placeholder="Votre commentaire…" />
            </div>
            <div class="col-span-12 md:col-span-6">
                <x-textarea name="notes" label="Textarea en erreur" required />
            </div>

            <div class="col-span-12 md:col-span-4">
                <x-field label="Cases à cocher">
                    <div class="space-y-1">
                        <x-checkbox name="options[]" value="sms" label="Notifier par SMS" checked />
                        <x-checkbox name="options[]" value="email" label="Notifier par e-mail" />
                        <x-checkbox name="options[]" value="none" label="Désactivée" disabled />
                        <x-checkbox name="terms" label="Case en erreur" />
                    </div>
                </x-field>
            </div>
            <div class="col-span-12 md:col-span-4">
                <x-field label="Boutons radio (inline)" name="period">
                    <x-radio name="period" value="month" label="Mois" inline checked />
                    <x-radio name="period" value="quarter" label="Trimestre" inline />
                    <x-radio name="period" value="year" label="Année" inline />
                </x-field>
            </div>
            <div class="col-span-12 md:col-span-4">
                <x-field label="Interrupteurs (switch)">
                    <div class="space-y-1">
                        <x-checkbox name="is_active" label="Actif" switch checked />
                        <x-checkbox name="is_public" label="Public" switch />
                        <x-checkbox name="is_locked" label="Désactivé" switch disabled checked />
                    </div>
                </x-field>
            </div>

            <div class="col-span-12 flex flex-wrap gap-2">
                <x-button icon="bx bx-save">Enregistrer (submit)</x-button>
                <x-button type="reset" variant="light">Réinitialiser (reset)</x-button>
            </div>
        </form>
    </x-card>

    <div class="grid grid-cols-12 gap-x-6">
        {{-- Modal --}}
        <div class="col-span-12 lg:col-span-4">
            <x-card title="Modal" description="x-modal : ouverture par nom, Échap, clic sur le fond, focus piégé.">
                <div class="flex flex-wrap gap-2">
                    <x-button type="button" data-test="open-send-modal" x-on:click="$dispatch('open-modal', 'showcase-send-invoice')" icon="bx bx-send">Envoyer la facture</x-button>
                    <x-button type="button" variant="secondary" outline x-on:click="$dispatch('open-modal', 'showcase-large')">Grande (lg)</x-button>
                </div>
            </x-card>
        </div>

        {{-- Dropdown --}}
        <div class="col-span-12 lg:col-span-8">
            <x-card title="Dropdown" description="x-dropdown : alignement début/fin, élément actif, danger, séparateur, menu qui reste ouvert.">
                <div class="flex flex-wrap items-start gap-3">
                    <x-dropdown>
                        <x-slot:trigger>
                            <x-button type="button" variant="primary">Actions <i class="mdi mdi-chevron-down" aria-hidden="true"></i></x-button>
                        </x-slot:trigger>
                        <x-dropdown-item href="#" icon="bx bx-show">Voir</x-dropdown-item>
                        <x-dropdown-item href="#" icon="bx bx-pencil">Modifier</x-dropdown-item>
                        <x-dropdown-item icon="bx bx-printer">Imprimer (bouton)</x-dropdown-item>
                        <x-dropdown-divider />
                        <x-dropdown-item icon="bx bx-trash" danger>Supprimer</x-dropdown-item>
                    </x-dropdown>

                    <x-dropdown align="end">
                        <x-slot:trigger>
                            <x-button type="button" variant="primary" outline icon="bx bx-calendar" data-test="period-dropdown">Période <i class="mdi mdi-chevron-down" aria-hidden="true"></i></x-button>
                        </x-slot:trigger>
                        <x-dropdown-item href="#">Trimestre 1</x-dropdown-item>
                        <x-dropdown-item href="#" active>Trimestre 2 (actif)</x-dropdown-item>
                        <x-dropdown-item href="#">Trimestre 3</x-dropdown-item>
                        <x-dropdown-divider />
                        <x-dropdown-item href="#">Année en cours</x-dropdown-item>
                    </x-dropdown>

                    <x-dropdown size="lg" keep-open>
                        <x-slot:trigger>
                            <x-button type="button" variant="light" icon="bx bx-filter-alt">Filtres (reste ouvert)</x-button>
                        </x-slot:trigger>
                        <div class="space-y-2 px-4 py-2">
                            <x-checkbox name="filter_paid" label="Payées" checked />
                            <x-checkbox name="filter_unpaid" label="À régler" />
                            <x-input name="filter_client" size="sm" placeholder="Client…" prepend-icon="bx bx-search" />
                        </div>
                    </x-dropdown>
                </div>
            </x-card>
        </div>
    </div>

    <div class="grid grid-cols-12 gap-x-6">
        {{-- Tabs --}}
        <div class="col-span-12 xl:col-span-7">
            <x-card title="Onglets" description="x-tabs : onglets et pilules ; l'événement tab-shown remplace shown.bs.tab.">
                <div x-data="{ lastShown: 'aucun' }" x-on:tab-shown="lastShown = $event.detail" class="space-y-6">
                    <x-tabs active="open">
                        <x-slot:nav>
                            <x-tab name="open">Ouverts <x-badge variant="info" pill>4</x-badge></x-tab>
                            <x-tab name="waiting" data-test="tab-waiting">En attente <x-badge variant="warning" pill>2</x-badge></x-tab>
                            <x-tab name="done">Réparés <x-badge variant="success" pill>9</x-badge></x-tab>
                        </x-slot:nav>
                        <x-tab-panel name="open" class="p-3 text-muted">Contenu de l'onglet « Ouverts ».</x-tab-panel>
                        <x-tab-panel name="waiting" class="p-3 text-muted">Contenu de l'onglet « En attente ».</x-tab-panel>
                        <x-tab-panel name="done" class="p-3 text-muted">Contenu de l'onglet « Réparés ».</x-tab-panel>
                    </x-tabs>

                    <x-tabs active="profile" variant="pills" justified>
                        <x-slot:nav>
                            <x-tab name="profile">Profil</x-tab>
                            <x-tab name="security">Sécurité</x-tab>
                            <x-tab name="company">Société</x-tab>
                        </x-slot:nav>
                        <x-tab-panel name="profile" class="p-3 text-muted">Pilules justifiées : Profil.</x-tab-panel>
                        <x-tab-panel name="security" class="p-3 text-muted">Pilules justifiées : Sécurité.</x-tab-panel>
                        <x-tab-panel name="company" class="p-3 text-muted">Pilules justifiées : Société.</x-tab-panel>
                    </x-tabs>

                    <p class="mb-0 text-muted">Dernier événement <code>tab-shown</code> : <strong data-test="last-tab-shown" x-text="lastShown"></strong></p>
                </div>
            </x-card>
        </div>

        {{-- Accordion --}}
        <div class="col-span-12 xl:col-span-5">
            <x-card title="Accordéon" description="x-accordion-item (x-collapse) : le premier est ouvert.">
                <div>
                    <x-accordion-item title="Historique de la facture" open>
                        <p class="mb-0 text-muted">Facture créée le 12-11-2026 par Admin.</p>
                    </x-accordion-item>
                    <x-accordion-item title="Paiements">
                        <p class="mb-0 text-muted">Aucun paiement enregistré.</p>
                    </x-accordion-item>
                    <x-accordion-item title="Envois par e-mail">
                        <p class="mb-0 text-muted">Envoyée au client le 13-11-2026.</p>
                    </x-accordion-item>
                </div>
            </x-card>
        </div>
    </div>

    {{-- Table --}}
    <x-card title="Tableau" description="x-table bordered hover nowrap light-head avec avatars, badges, actions et pagination (vendor/pagination/tailwind).">
        <x-slot:actions>
            <x-button type="button" size="sm" variant="success" icon="bx bx-plus">Nouveau</x-button>
        </x-slot:actions>

        <x-table bordered hover nowrap light-head id="showcase-table">
            <x-slot:head>
                <tr>
                    <th>Ticket N°</th>
                    <th>Client</th>
                    <th>Article</th>
                    <th>Date d'entrée</th>
                    <th>Statut</th>
                    <th class="text-end">Actions</th>
                </tr>
            </x-slot:head>

            @foreach ([
                ['TCK-1042', 'Atlas Services', 'Imprimante HP 4200', '18-11-2026', 'success', 'Réparé'],
                ['TCK-1043', 'Rif Logistique', 'Ordinateur Dell 7490', '19-11-2026', 'warning', 'En attente de devis'],
                ['TCK-1044', 'Souss Agro', 'Écran Samsung 27"', '20-11-2026', 'info', 'Diagnostic'],
                ['TCK-1045', 'Casa Textile', 'Onduleur APC 1500', '21-11-2026', 'danger', 'Annulé'],
            ] as [$code, $client, $article, $date, $color, $status])
                <tr>
                    <td class="font-semibold text-primary">{{ $code }}</td>
                    <td>
                        <div class="flex items-center gap-2">
                            <x-avatar size="xs" soft>{{ \Illuminate\Support\Str::of($client)->explode(' ')->map(fn ($word) => mb_substr($word, 0, 1))->take(2)->implode('') }}</x-avatar>
                            {{ $client }}
                        </div>
                    </td>
                    <td>{{ $article }}</td>
                    <td>{{ $date }}</td>
                    <td><x-badge :variant="$color" soft pill>{{ $status }}</x-badge></td>
                    <td class="text-end">
                        <x-dropdown align="end">
                            <x-slot:trigger>
                                <x-button type="button" size="sm" variant="light" aria-label="Actions"><i class="mdi mdi-dots-horizontal" aria-hidden="true"></i></x-button>
                            </x-slot:trigger>
                            <x-dropdown-item href="#" icon="bx bx-show">Voir</x-dropdown-item>
                            <x-dropdown-item href="#" icon="bx bx-pencil">Modifier</x-dropdown-item>
                        </x-dropdown>
                    </td>
                </tr>
            @endforeach
        </x-table>

        {{ $paginator->links() }}
    </x-card>

    {{-- Card variants --}}
    <div class="grid grid-cols-12 gap-x-6">
        <div class="col-span-12 md:col-span-4">
            <x-card title="Carte simple" icon="bx bx-info-circle" description="Titre, icône et description (card-title-desc).">
                <p class="mb-0">Contenu de la carte.</p>
            </x-card>
        </div>
        <div class="col-span-12 md:col-span-4">
            <x-card>
                <x-slot:header class="font-semibold">En-tête (card-header)</x-slot:header>
                <p class="mb-0">Carte avec en-tête et pied.</p>
                <x-slot:footer class="text-end">
                    <x-button type="button" size="sm" variant="light">Annuler</x-button>
                    <x-button type="button" size="sm">Valider</x-button>
                </x-slot:footer>
            </x-card>
        </div>
        <div class="col-span-12 md:col-span-4">
            <x-card :padding="false" title="">
                <div class="bg-primary/25 p-5">
                    <h5 class="mb-1 text-primary">Sans padding</h5>
                    <p class="mb-0 text-primary">Fond doux bg-primary/25 (Skote bg-soft).</p>
                </div>
                <div class="p-5">Contenu sous l'en-tête coloré.</div>
            </x-card>
        </div>
    </div>

    {{-- Modals --}}
    <x-modal name="showcase-send-invoice" title="Confirmer l'envoi de la facture" data-test="send-modal">
        <p class="mb-2">FACTURE N° : <span class="text-primary">FAC-2026-0042</span></p>
        <p class="mb-4">Client : <span class="text-primary">Atlas Services</span></p>

        <form id="showcase-send-form" action="#" onsubmit="return false" class="space-y-2">
            <p class="mb-1 font-medium">Sélectionner les e-mails :</p>
            <x-checkbox label="contact@atlas.ma (e-mail principal)" checked disabled />
            <x-checkbox name="emails[]" value="compta@atlas.ma" label="compta@atlas.ma" />
            <x-checkbox name="emails[]" value="direction@atlas.ma" label="direction@atlas.ma" />
        </form>

        <x-slot:footer>
            <x-button form="showcase-send-form" icon="bx bx-send">Envoyer</x-button>
            <x-button type="button" variant="secondary" x-on:click="show = false">Fermer</x-button>
        </x-slot:footer>
    </x-modal>

    <x-modal name="showcase-large" title="Modal grande (lg)" max-width="lg">
        <p class="mb-4 text-muted">Les modales de l'application sont toutes centrées ; la largeur par défaut (md) est celle de Bootstrap (500px).</p>
        <x-input name="modal_field" label="Champ dans une modal" placeholder="Le focus reste dans la modal (Tab)" />

        <x-slot:footer>
            <x-button type="button" variant="light" x-on:click="$dispatch('close-modal', 'showcase-large')">Fermer via close-modal</x-button>
        </x-slot:footer>
    </x-modal>
@endsection
