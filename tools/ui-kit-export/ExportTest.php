<?php

/*
 * UI kit exporter: renders every page of the manifest through the real app (its layouts and
 * x-admin.* components) with fictional data, then writes ui-kit/static/ (StaticPage removes
 * everything app-specific). Not part of the app test suite. From the repository root:
 *
 *     vendor/bin/pest --configuration=tools/ui-kit-export/phpunit.xml --test-directory=tools/ui-kit-export
 *     (cd ui-kit && npm run build)
 *
 * KIT_PAGE=buttons exports one page only.
 */

use App\Models\User;
use Illuminate\Foundation\Testing\LazilyRefreshDatabase;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\View;
use Illuminate\Support\MessageBag;
use Illuminate\Support\ViewErrorBag;
use Tests\TestCase;
use UiKitExport\LayoutWorkspace;
use UiKitExport\Manifest;
use UiKitExport\StaticPage;

require_once __DIR__.'/Support/Manifest.php';
require_once __DIR__.'/Support/StaticPage.php';
require_once __DIR__.'/Support/Snippets.php';
require_once __DIR__.'/Support/LayoutWorkspace.php';
foreach (glob(dirname(__DIR__, 2).'/ui-kit/laravel/support/*.php') as $kitSupportFile) {
    require_once $kitSupportFile;
}

uses(TestCase::class, LazilyRefreshDatabase::class);

beforeEach(function (): void {
    $this->travelTo(Carbon::parse('2026-11-04 10:00'));
    View::addNamespace('kit-export', __DIR__.'/views');
    $this->withoutVite();
    StaticPage::$productName = (string) config('app.name');
    StaticPage::$versionStrings = [RentCar()::version().' | '.RentCar()::versionDate(), RentCar()::version()];
});

/** @return list<array<string, string>> */
function kitPagesOfKind(string ...$kinds): array
{
    $only = getenv('KIT_PAGE') ?: null;

    return array_values(array_filter(Manifest::pages(), fn (array $page): bool => in_array($page['kind'], $kinds, true) && ($only === null || $only === $page['key'])));
}

/**
 * Session of every gallery page: validation errors for the error-state demos (field names
 * starting with error_), previous input, and flash messages for x-messages.
 *
 * @return array<string, mixed>
 */
function kitGallerySession(): array
{
    return [
        'errors' => (new ViewErrorBag)->put('default', new MessageBag([
            'error_mileage' => 'Le kilométrage au retour doit être supérieur ou égal au kilométrage au départ (24 500 km).',
            'error_email' => 'L’adresse e-mail doit être une adresse valide.',
            'error_notes' => 'Les notes ne peuvent pas dépasser 500 caractères.',
            'error_agency' => 'Choisissez l’agence de départ.',
            'error_customer' => 'Choisissez un client.',
            'error_fuel' => 'Indiquez le niveau de carburant.',
            'error_return_date' => 'La date de retour doit être postérieure à la date de départ.',
            'error_document' => 'Le fichier doit être une image ou un PDF de 5 Mo au plus.',
            'error_setting' => 'Le préfixe ne peut contenir que des lettres majuscules.',
        ])),
        '_old_input' => ['old_customer_name' => 'Amal Benali'],
        'success' => 'La réservation RES-2026-0148 a été confirmée.',
        'error' => 'Le véhicule n’est pas disponible sur cette période.',
        'warning' => 'La caution n’est pas encore sécurisée.',
    ];
}

it('exports the gallery pages and the index', function (): void {
    Route::middleware('web')->get('/__kit/{key}', function (string $key) {
        $page = collect(Manifest::pages())->firstWhere('key', $key);

        return view($page['view'], ['page' => $page, 'snippets' => kitSnippets($page['view'])]);
    });

    foreach (kitPagesOfKind('gallery') as $page) {
        $html = $this->withSession(kitGallerySession())->get('/__kit/'.$page['key'])->assertOk()->getContent();
        StaticPage::write($page, $html);
    }

    if (getenv('KIT_PAGE') === false) {
        $index = ['key' => 'index', 'kind' => 'index', 'path' => 'index.html', 'section' => 'Index', 'title' => 'All pages', 'source' => '', 'kit' => '', 'pattern' => '', 'states' => ''];
        StaticPage::write($index, view('kit-export::index', ['sections' => Manifest::sections()])->render());
    }

    expect(getenv('KIT_PAGE') !== false || file_exists(StaticPage::kitPath('static/index.html')))->toBeTrue();
});

it('exports the layout pages', function (): void {
    foreach (kitPagesOfKind('tenant', 'tenant-restricted', 'tenant-field', 'platform', 'guest') as $page) {
        $html = LayoutWorkspace::render($this, $page);
        StaticPage::write($page, $html);
    }

    expect(true)->toBeTrue();
});

/*
 * Fidelity: the kit's decoupled Blade components (ui-kit/laravel/components) render the same
 * markup as the app components for every gallery page. The only expected difference is the
 * brand (the app name and logo versus config('brand.*')), normalised before comparing.
 */
it('renders the kit Blade components exactly like the app components', function (): void {
    Route::middleware('web')->get('/__kit/{key}', function (string $key) {
        $page = collect(Manifest::pages())->firstWhere('key', $key);

        return view($page['view'], ['page' => $page, 'snippets' => kitSnippets($page['view'])]);
    });
    $pages = kitPagesOfKind('gallery');
    $normalise = fn (array $page, string $html): string => preg_replace('/\s+/', ' ', StaticPage::convert($page, str_replace(config('brand.name'), StaticPage::$productName, $html)));

    $appPages = [];
    foreach ($pages as $page) {
        $appPages[$page['key']] = $normalise($page, $this->withSession(kitGallerySession())->get('/__kit/'.$page['key'])->getContent());
    }

    config(['brand' => require StaticPage::kitPath('brand/brand.php')]);
    View::getFinder()->prependLocation(StaticPage::kitPath('laravel'));
    View::getFinder()->flush();

    $different = [];
    foreach ($pages as $page) {
        $kitPage = $normalise($page, $this->withSession(kitGallerySession())->get('/__kit/'.$page['key'])->getContent());
        if ($kitPage !== $appPages[$page['key']]) {
            similar_text($kitPage, $appPages[$page['key']], $percent);
            $position = strspn($kitPage ^ $appPages[$page['key']], "\0");
            $different[] = $page['key'].' ('.round($percent, 2).'% similar; first difference: …'.substr($appPages[$page['key']], max(0, $position - 80), 200).'… vs …'.substr($kitPage, max(0, $position - 80), 200).'…)';
        }
    }

    expect($different)->toBe([]);
});

/*
 * The kit's decoupled layouts (ui-kit/laravel/layouts) render with plain view data only: no
 * app model, permission or route is involved. Same demo content as the layout pages.
 */
it('renders the kit layouts from plain view data', function (): void {
    config(['brand' => require StaticPage::kitPath('brand/brand.php')]);
    View::getFinder()->prependLocation(StaticPage::kitPath('laravel'));
    View::getFinder()->flush();
    $user = User::factory()->create(['email' => 'salma.idrissi@example.com']);

    $shared = [
        'navigation' => [
            ['label' => null, 'items' => [['href' => '#', 'label' => 'Tableau de bord', 'icon' => 'home', 'active' => false]]],
            ['label' => 'Exploitation', 'items' => [
                ['href' => '#', 'label' => 'Réservations', 'icon' => 'calendar', 'active' => true],
                ['href' => '#', 'label' => 'Clients', 'icon' => 'users', 'active' => false],
            ]],
        ],
        'bottomBar' => [['href' => '#', 'label' => 'Départs du jour', 'icon' => 'departure', 'current' => false]],
        'contextSwitcher' => [
            'organisation' => 'Atlas Location', 'agency' => 'Rabat Centre', 'organisationAction' => '#', 'agencyAction' => '#',
            'organisations' => [['value' => 1, 'name' => 'Atlas Location', 'current' => true], ['value' => 2, 'name' => 'Atlas Location Nord', 'current' => false]],
            'agencies' => [['value' => 1, 'name' => 'Rabat Centre', 'current' => true], ['value' => 2, 'name' => 'Casablanca Maarif', 'current' => false]],
        ],
        'searchAction' => '#',
        'headerNotifications' => [['title' => 'Retour en retard', 'body' => 'RES-2026-0145', 'time' => 'il y a 2 heures', 'unread' => true, 'markReadUrl' => '#']],
        'accountMenu' => [['href' => '#', 'label' => 'Profil', 'icon' => 'user']],
        'logoutAction' => '#',
        'page' => ['title' => 'Demo'],
    ];

    foreach (['tenant', 'tenant-field', 'platform', 'guest'] as $layout) {
        Route::middleware('web')->get('/__kit-plain/'.$layout, fn () => view('kit-export::layouts.'.$layout, $shared));
        $html = $this->actingAs($user)->get('/__kit-plain/'.$layout)->assertOk()->getContent();

        expect($html)->toContain(config('brand.name'))->not->toContain('NidamCar');
        if (str_starts_with($layout, 'tenant')) {
            expect($html)->toContain('aria-current="page"')->toContain('Atlas Location Nord')->toContain('Retour en retard')->toContain('admin-sidebar');
        }
        if ($layout === 'tenant') {
            expect($html)->toContain('admin-bottom-bar');
        }
        if ($layout === 'tenant-field') {
            expect($html)->not->toContain('class="admin-bottom-bar"');
        }
    }
});
