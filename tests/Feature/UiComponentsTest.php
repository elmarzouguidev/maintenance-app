<?php

/*
 * Rendering tests for the Tailwind component kit (resources/views/components).
 * They only render Blade, so they never touch the database.
 */

describe('button', function () {
    it('submits by default, like a native button', function () {
        $this->blade('<x-button>Enregistrer</x-button>')
            ->assertSee('type="submit"', false)
            ->assertSee('bg-primary', false);
    });

    it('keeps an explicit type and renders the requested variant and size', function () {
        $this->blade('<x-button type="button" variant="danger" size="sm" outline>Supprimer</x-button>')
            ->assertSee('type="button"', false)
            ->assertSee('border-danger text-danger', false)
            ->assertSee('text-[0.7109rem]', false)
            ->assertDontSee('type="submit"', false);
    });

    it('renders a link without a type when href is given', function () {
        $this->blade('<x-button href="/app/clients" icon="bx bx-user">Clients</x-button>')
            ->assertSee('<a', false)
            ->assertSee('href="/app/clients"', false)
            ->assertSee('<i class="bx bx-user"', false)
            ->assertDontSee('type=', false);
    });
});

describe('input', function () {
    it('links the label to the input and marks required fields', function () {
        $this->blade('<x-input name="email" label="E-mail" required />')
            ->assertSee('for="email"', false)
            ->assertSee('id="email"', false)
            ->assertSee('required', false)
            ->assertSee('text-danger', false);
    });

    it('shows the validation message and the error state', function () {
        $this->withViewErrors(['email' => "L'adresse e-mail n'est pas valide."])
            ->blade('<x-input name="email" label="E-mail" />')
            ->assertSee("L'adresse e-mail n'est pas valide.")
            ->assertSee('aria-invalid="true"', false)
            ->assertSee('border-danger', false);
    });

    it('reads bracket names from the dotted error key', function () {
        $this->withViewErrors(['items.0.qty' => 'Quantité invalide.'])
            ->blade('<x-input name="items[0][qty]" />')
            ->assertSee('Quantité invalide.')
            ->assertSee('id="items-0-qty"', false);
    });

    it('falls back to old input but never for passwords', function () {
        $session = app('session.store');
        $session->flashInput(['company' => 'CASA MAINTENANCE', 'secret' => 'hunter2']);
        request()->setLaravelSession($session);

        $this->blade('<x-input name="company" value="Ancienne valeur" /><x-input name="secret" type="password" />')
            ->assertSee('value="CASA MAINTENANCE"', false)
            ->assertDontSee('hunter2');
    });

    it('builds an input group with prepend and append addons', function () {
        $this->blade('<x-input name="amount" prepend="MAD" append-icon="mdi mdi-calendar" />')
            ->assertSeeInOrder(['MAD', 'name="amount"', 'mdi mdi-calendar'], false)
            ->assertSee('rounded-none', false);
    });
});

describe('select, textarea and checks', function () {
    it('renders options with the placeholder and the selected value', function () {
        $this->blade('<x-select name="status" placeholder="Choisir" :options="[\'open\' => \'Ouvert\', \'closed\' => \'Fermé\']" value="closed" />')
            ->assertSeeInOrder(['<option value="">Choisir</option>', 'Ouvert', 'value="closed" selected'], false);
    });

    it('renders the textarea value and error', function () {
        $this->withViewErrors(['notes' => 'La description est obligatoire.'])
            ->blade('<x-textarea name="notes" value="Texte" />')
            ->assertSee('>Texte</textarea>', false)
            ->assertSee('La description est obligatoire.');
    });

    it('renders checkboxes, radios and switches', function () {
        $this->blade('<x-checkbox name="sms" label="SMS" checked /><x-radio name="period" value="month" label="Mois" /><x-checkbox name="active" switch />')
            ->assertSee('type="checkbox"', false)
            ->assertSee('checked', false)
            ->assertSee('type="radio"', false)
            ->assertSee('value="month"', false)
            ->assertSee('role="switch"', false);
    });
});

describe('feedback and layout components', function () {
    it('renders a dismissible alert with a non-submitting close button', function () {
        $this->blade('<x-alert variant="success" dismissible>Enregistré</x-alert>')
            ->assertSee('role="alert"', false)
            ->assertSee('bg-success/20', false)
            ->assertSee('type="button"', false);
    });

    it('renders session flash messages and validation errors', function () {
        session()->now('success', 'Facture envoyée.');

        $this->withViewErrors(['email' => 'E-mail invalide.'])
            ->blade('<x-flash-messages />')
            ->assertSeeInOrder(['Facture envoyée.', 'E-mail invalide.']);
    });

    it('renders soft and pill badges', function () {
        $this->blade('<x-badge variant="warning" soft pill>3</x-badge>')
            ->assertSee('bg-warning/18 text-warning', false)
            ->assertSee('rounded-full', false);
    });

    it('renders the page title with its breadcrumb', function () {
        $this->blade('<x-page-title title="Clients" :breadcrumbs="[\'Accueil\' => \'/app\', \'Clients\' => null]" />')
            ->assertSee('Clients')
            ->assertSee('<a href="/app"', false)
            ->assertSee('aria-current="page"', false);
    });

    it('renders a card with title, description, actions and footer', function () {
        $this->blade('<x-card title="Filtres" description="Affiner la liste"><x-slot:actions>Actions</x-slot:actions> Corps <x-slot:footer>Pied</x-slot:footer> </x-card>')
            ->assertSeeInOrder(['Filtres', 'Actions', 'Affiner la liste', 'Corps', 'Pied']);
    });

    it('puts table attributes on the table element inside a responsive wrapper', function () {
        $this->blade('<x-table id="datatable" bordered><x-slot:head><tr><th>N°</th></tr></x-slot:head><tr><td>1</td></tr></x-table>')
            ->assertSeeInOrder(['overflow-x-auto', '<table', 'id="datatable"', '<thead>', '<tbody>'], false);
    });

    it('renders avatars as initials or as an image', function () {
        $this->blade('<x-avatar size="xs" soft>AB</x-avatar><x-avatar src="/logo.png" alt="Logo" />')
            ->assertSee('size-8', false)
            ->assertSee('<img src="/logo.png" alt="Logo"', false);
    });
});

describe('interactive components', function () {
    it('renders a named, accessible modal that is hidden until opened', function () {
        $this->blade('<x-modal name="sendInvoice-abc" title="Envoyer">Corps</x-modal>')
            ->assertSee('role="dialog"', false)
            ->assertSee('aria-labelledby="sendinvoice-abc-title"', false)
            ->assertSee("name: 'sendInvoice-abc'", false)
            ->assertSee('style="display: none"', false)
            ->assertSee('x-trap.inert.noscroll="show"', false);
    });

    it('opens the modal on load when asked', function () {
        $this->blade('<x-modal name="edit" :show="true">Corps</x-modal>')
            ->assertSee('show: true', false)
            ->assertDontSee('style="display: none"', false);
    });

    it('renders a dropdown anchored to its trigger', function () {
        $this->blade('<x-dropdown align="end"><x-slot:trigger><x-button type="button">Actions</x-button></x-slot:trigger><x-dropdown-item href="/edit" active>Modifier</x-dropdown-item><x-dropdown-divider /><x-dropdown-item danger>Supprimer</x-dropdown-item></x-dropdown>')
            ->assertSee('x-anchor.bottom-end.offset.2="$refs.trigger"', false)
            ->assertSee('<a href="/edit" role="menuitem"', false)
            ->assertSee('aria-current="true"', false)
            ->assertSee('role="separator"', false)
            ->assertSee('text-danger', false);
    });

    it('renders the active tab and panel on the server and hides the others', function () {
        $this->blade('<x-tabs active="open" variant="pills"><x-slot:nav><x-tab name="open">Ouverts</x-tab><x-tab name="closed">Fermés</x-tab></x-slot:nav><x-tab-panel name="open">Panneau-ouvert</x-tab-panel><x-tab-panel name="closed">Panneau-ferme</x-tab-panel></x-tabs>')
            ->assertSee('data-active="true" aria-selected="true"', false)
            ->assertSee('aria-selected="false"', false)
            ->assertSee('data-active:bg-primary', false)
            ->assertSee("\$dispatch('tab-shown', value)", false)
            ->assertSeeInOrder(['Panneau-ouvert', 'style="display: none"', 'Panneau-ferme'], false);
    });

    it('renders an accordion item open or closed', function () {
        $this->blade('<x-accordion-item title="Historique" open>Contenu</x-accordion-item><x-accordion-item title="Paiements">Rien</x-accordion-item>')
            ->assertSeeInOrder(['aria-expanded="true"', 'Historique', 'aria-expanded="false"', 'Paiements'], false)
            ->assertSee('x-collapse', false);
    });
});
