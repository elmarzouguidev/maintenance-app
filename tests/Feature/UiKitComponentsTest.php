<?php

use App\Support\UiKit\BadgeVariant;
use App\Support\UiKit\HasStatusBadge;

/*
 * Rendering tests for the UI kit components copied from ui-kit/laravel/components
 * (resources/views/components/admin and messages). They only render Blade, so they never
 * touch the database.
 */

enum UiKitTestStatus: string implements HasStatusBadge
{
    case Overdue = 'overdue';

    public function label(): string
    {
        return 'En retard';
    }

    public function badgeVariant(): BadgeVariant
    {
        return BadgeVariant::DANGER;
    }
}

beforeEach(function () {
    // The kit's form components read $errors, which the web middleware shares on every page.
    $this->withViewErrors([]);
});

it('renders the :dataset component', function (string $template, array $expected) {
    $view = $this->blade($template);

    $view->assertSeeInOrder($expected, false);
})->with([
    'alert' => ['<x-admin.alert type="danger" message="Paiement refusé." />', ['role="alert"', 'admin-alert-error', 'Paiement refusé.', 'aria-label="Fermer le message"']],
    'amount' => ['<x-admin.amount :value="1234.5" />', ["1\u{00A0}234,50\u{00A0}DH"]],
    'badge' => ['<x-admin.badge variant="info">Atelier</x-admin.badge>', ['admin-status-badge admin-status-badge-info', 'Atelier']],
    'button' => ['<x-admin.button variant="secondary" size="sm" icon="plus">Ajouter</x-admin.button>', ['<button type="button"', 'admin-button admin-button-secondary admin-button-sm', '<svg', 'Ajouter']],
    'date' => ['<x-admin.date value="2026-11-03 09:30" time />', ['<time', '03/11/2026 à 09:30']],
    'date-picker' => ['<x-admin.date-picker name="due_at" label="Échéance" value="2026-11-24" />', ['x-data="datePicker(', 'Échéance', 'type="hidden" name="due_at"']],
    'delete-action' => ['<x-admin.delete-action action="/app/clients/7" confirm="Supprimer ce client ?" />', ['method="POST" action="/app/clients/7"', 'name="_method" value="DELETE"', 'admin-button-danger', 'Supprimer']],
    'detail-item' => ['<x-admin.detail-item label="Client">Société Atlas</x-admin.detail-item>', ['<dt', 'Client', '<dd', 'Société Atlas']],
    'detail-page' => ['<x-admin.detail-page>Corps<x-slot:aside>Résumé</x-slot:aside></x-admin.detail-page>', ['lg:col-span-2', 'Corps', '<aside', 'Résumé']],
    'detail-section' => ['<x-admin.detail-section title="Informations générales">Contenu</x-admin.detail-section>', ['aria-labelledby="section-informations-generales"', 'id="section-informations-generales"', 'Informations générales', 'Contenu']],
    'empty-state' => ['<x-admin.empty-state title="Aucun ticket" description="Créez le premier ticket." />', ['admin-empty-state', 'Aucun ticket', 'Créez le premier ticket.']],
    'file-upload' => ['<x-admin.file-upload name="photo" label="Photo" />', ['for="file-photo"', 'type="file" name="photo"', 'id="file-photo"', 'id="preview-photo"']],
    'form.field' => ['<x-admin.form.field id="reference" name="reference" label="Référence" required><input id="reference"></x-admin.form.field>', ['for="reference"', 'Référence', 'text-status-danger', '<input id="reference">']],
    'form-input' => ['<x-admin.form-input name="email" type="email" label="E-mail" required />', ['for="email"', 'type="email"', 'id="email"', 'name="email"', 'required', 'admin-form-input']],
    'form-select' => ['<x-admin.form-select name="city" label="Ville" :options="[\'cas\' => \'Casablanca\', \'rab\' => \'Rabat\']" value="rab" />', ['<select id="city"', '<option value="">', 'value="rab" selected', 'Rabat']],
    'form-select2' => ['<x-admin.form-select2 name="client_id" label="Client" :options="[1 => \'Atlas\']" />', ['x-data="adminSelect2(', 'type="hidden" name="client_id"']],
    'guest-heading' => ['<x-admin.guest-heading title="Connexion" />', ['vendor/ui-kit/brand/logo-mark.svg', 'alt="CASAMAINTENANCE"', 'Connexion']],
    'icon' => ['<x-admin.icon name="search" />', ['<svg', 'aria-hidden="true"']],
    'icon-button' => ['<x-admin.icon-button icon="trash" label="Supprimer la ligne" />', ['<button type="button" aria-label="Supprimer la ligne" title="Supprimer la ligne"', 'admin-icon-button']],
    'modal' => ['<x-admin.modal id="confirm" title="Confirmer" description="Cette action est définitive.">Texte</x-admin.modal>', ['<dialog id="confirm"', 'aria-labelledby="confirm-title"', 'aria-describedby="confirm-description"', 'admin-dialog', 'id="confirm-title"', 'Confirmer', 'Texte']],
    'nav-link' => ['<x-admin.nav-link href="/app/tickets" icon="document" active>Tickets</x-admin.nav-link>', ['aria-current="page"', 'admin-nav-link', 'href="/app/tickets"', 'Tickets']],
    'page-header' => ['<x-admin.page-header title="Tickets" :breadcrumbs="[[\'label\' => \'Accueil\', \'href\' => \'/app\'], [\'label\' => \'Tickets\']]" />', ['href="/app"', 'Accueil', 'aria-current="page"', 'Tickets</span>', '<h1 class="admin-page-title">Tickets</h1>']],
    'plate' => ['<x-admin.plate value="12345-أ-6" />', ['<bdo dir="ltr"', '12345-أ-6']],
    'segmented-radio' => ['<x-admin.segmented-radio name="priority" label="Priorité" :options="[\'low\' => \'Basse\', \'high\' => \'Haute\']" value="high" />', ['<legend', 'Priorité', 'type="radio" name="priority" value="low"', 'type="radio" name="priority" value="high"', 'checked']],
    'setting-field' => ['<x-admin.setting-field type="toggle" name="notify" label="Notifications" :value="true" />', ['type="checkbox" name="notify"', 'checked', 'admin-switch']],
    'stats-card' => ['<x-admin.stats-card title="Tickets ouverts" value="42" variant="warning" icon="document" />', ['admin-stat-marker admin-stat-warning', 'Tickets ouverts', '42']],
    'stats-grid' => ['<x-admin.stats-grid amounts>Cartes</x-admin.stats-grid>', ['lg:grid-cols-3', 'Cartes']],
    'status-badge' => ['<x-admin.status-badge variant="success" label="Livré" />', ['admin-status-badge admin-status-badge-success', 'Livré']],
    'table' => ['<x-admin.table :headers="[\'Code\', \'Client\']"><tr><td>TCK-1</td></tr></x-admin.table>', ['<th scope="col" class="admin-table-head">Code</th>', 'Client', '<tbody>', 'TCK-1']],
    'textarea' => ['<x-admin.textarea name="notes" label="Notes" rows="6" />', ['for="notes"', '<textarea id="notes"', 'rows="6"']],
]);

it('marks an invalid field and links it to its error message', function () {
    $view = $this->withViewErrors(['email' => "L'adresse e-mail n'est pas valide."])
        ->blade('<x-admin.form-input name="email" label="E-mail" />');

    $view->assertSee('aria-invalid="true"', false)
        ->assertSee('aria-describedby="email-error"', false)
        ->assertSee('id="email-error"', false)
        ->assertSee("L'adresse e-mail n'est pas valide.");
});

it('takes the badge label and variant from a status enum', function () {
    $view = $this->blade('<x-admin.status-badge :status="$status" />', ['status' => UiKitTestStatus::Overdue]);

    $view->assertSee('admin-status-badge-danger', false)
        ->assertSee('En retard');
});

it('renders the session flash messages', function () {
    session()->put('success', 'Ticket enregistré.');
    session()->put('error', 'Envoi impossible.');

    $view = $this->blade('<x-messages />');

    $view->assertSeeInOrder(['admin-alert-success', 'Ticket enregistré.', 'admin-alert-error', 'Envoi impossible.'], false);
});

it('renders nothing when there is no flash message', function () {
    $view = $this->blade('<x-messages />');

    expect(trim((string) $view))->toBe('');
});

it('summarises the validation errors above a form', function () {
    $view = $this->withViewErrors(['email' => 'E-mail invalide.', 'amount' => 'Montant requis.'])
        ->blade('<x-messages.errors />');

    $view->assertSee('role="alert"', false)
        ->assertSeeInOrder(['Des erreurs sont survenues.', 'E-mail invalide.', 'Montant requis.']);
});

describe('file upload', function () {
    it('keeps each field name, including names such as files[]', function () {
        $view = $this->blade('<x-admin.file-upload name="photo" /><x-admin.file-upload name="files[]" multiple />');

        $view->assertSee('type="file" name="photo"', false)
            ->assertSee('type="file" name="files[]"', false)
            ->assertSee('id="file-files[]"', false)
            ->assertSee('multiple', false);
    });

    it('prints one script that binds every upload inside its own container', function () {
        $view = $this->blade('<x-admin.file-upload name="photo" /><x-admin.file-upload name="files[]" multiple />@stack(\'scripts\')');

        $html = (string) $view;

        expect(substr_count($html, 'function initFileUpload(container)'))->toBe(1)
            ->and($html)->toContain("document.querySelectorAll('.file-upload-container').forEach(initFileUpload)")
            ->and($html)->toContain("container.querySelector('.file-preview')")
            ->and($html)->toContain('function removeFile(index)')
            ->and($html)->not->toContain('window.removeFile')
            ->and($html)->not->toContain("document.querySelector('.file-preview')");
    });
});
