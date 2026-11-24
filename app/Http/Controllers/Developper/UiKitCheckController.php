<?php

declare(strict_types=1);

namespace App\Http\Controllers\Developper;

use App\Http\Controllers\Controller;
use Illuminate\Contracts\View\View;
use Illuminate\Http\Request;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Facades\View as ViewFactory;
use Illuminate\Support\MessageBag;
use Illuminate\Support\ViewErrorBag;

class UiKitCheckController extends Controller
{
    private const TICKETS_PER_PAGE = 5;

    private const TICKETS_TOTAL = 42;

    /**
     * Show the UI kit install check: the tenant layout (resources/views/layouts/admin) with a
     * sample of every kit component and state (ui-kit/laravel/INSTALL.md, section 8).
     *
     * Only registered when APP_ENV=local (see routes/web.php). The layout data, a flash message,
     * validation errors and a paginator are faked so every state is visible.
     */
    public function __invoke(Request $request): View
    {
        $request->session()->now('success', 'Exemple de message flash (session).');

        ViewFactory::share('errors', (new ViewErrorBag)->put('default', new MessageBag([
            'client_email' => ['L’adresse e-mail n’est pas valide.'],
            'category_id' => ['Choisissez une catégorie.'],
            'priority' => ['Choisissez une priorité.'],
            'received_at' => ['La date de réception est obligatoire.'],
            'photo' => ['Le fichier doit être une image.'],
        ])));

        $currentPage = max(1, $request->integer('page', 1));

        $tickets = new LengthAwarePaginator(
            items: $this->sampleTickets($currentPage),
            total: self::TICKETS_TOTAL,
            perPage: self::TICKETS_PER_PAGE,
            currentPage: $currentPage,
            options: ['path' => $request->url()],
        );

        return view('theme.pages.UiKitCheck.index', [
            'tickets' => $tickets,
            ...$this->layoutData(),
        ]);
    }

    /**
     * View data read by layouts/admin.blade.php (the shapes are documented at the top of it).
     *
     * @return array{navigation: list<array{label: ?string, items: list<array{href: string, label: string, icon: string, active: bool}>}>, bottomBar: list<array{href: string, label: string, icon: string, current: bool}>, searchAction: string, headerNotifications: list<array{title: string, body: string, time: string, unread: bool, markReadUrl: ?string}>, notificationsUrl: string, accountMenu: list<array{href: string, label: string, icon: string}>, logoutAction: string}
     */
    private function layoutData(): array
    {
        $checkUrl = route('dev.ui-kit');

        return [
            'navigation' => [
                ['label' => null, 'items' => [
                    ['href' => $checkUrl, 'label' => 'Vérification du kit', 'icon' => 'home', 'active' => true],
                ]],
                ['label' => 'Atelier', 'items' => [
                    ['href' => '#', 'label' => 'Tickets', 'icon' => 'wrench', 'active' => false],
                    ['href' => '#', 'label' => 'Diagnostics', 'icon' => 'list', 'active' => false],
                    ['href' => '#', 'label' => 'Livraisons', 'icon' => 'truck', 'active' => false],
                ]],
                ['label' => 'Commercial', 'items' => [
                    ['href' => '#', 'label' => 'Devis', 'icon' => 'document', 'active' => false],
                    ['href' => '#', 'label' => 'Factures', 'icon' => 'banknotes', 'active' => false],
                    ['href' => '#', 'label' => 'Clients', 'icon' => 'users', 'active' => false],
                ]],
            ],
            'bottomBar' => [
                ['href' => $checkUrl, 'label' => 'Accueil', 'icon' => 'home', 'current' => true],
                ['href' => '#', 'label' => 'Tickets', 'icon' => 'wrench', 'current' => false],
                ['href' => '#', 'label' => 'Clients', 'icon' => 'users', 'current' => false],
            ],
            'searchAction' => $checkUrl,
            'headerNotifications' => [
                ['title' => 'Nouveau ticket', 'body' => 'TCK-2026-0142 a été créé par l’accueil.', 'time' => 'Il y a 5 min', 'unread' => true, 'markReadUrl' => null],
                ['title' => 'Devis accepté', 'body' => 'Le client Atlas Services a accepté le devis DEV-2026-0087.', 'time' => 'Hier', 'unread' => false, 'markReadUrl' => null],
            ],
            'notificationsUrl' => '#',
            'accountMenu' => [
                ['href' => route('admin:profile.index'), 'label' => 'Profil', 'icon' => 'user'],
            ],
            'logoutAction' => route('admin:auth:logout'),
        ];
    }

    /**
     * One page of fictional tickets, one per status variant.
     *
     * @return list<array{code: string, client: string, article: string, received_at: string, amount: ?float, status: array{variant: string, label: string}}>
     */
    private function sampleTickets(int $page): array
    {
        $statuses = [
            ['variant' => 'info', 'label' => 'En diagnostic'],
            ['variant' => 'warning', 'label' => 'En attente de pièce'],
            ['variant' => 'success', 'label' => 'Livré'],
            ['variant' => 'danger', 'label' => 'En retard'],
            ['variant' => 'neutral', 'label' => 'Annulé'],
        ];

        $clients = ['Atlas Services', 'Amal Benali', 'Société Nour', 'Youssef El Idrissi', 'Cabinet Rami'];
        $articles = ['Imprimante HP 4200', 'PC portable Dell Latitude', 'Onduleur APC 1500', 'Écran Samsung 27"', 'Serveur HPE ML30'];

        $tickets = [];

        foreach (range(0, self::TICKETS_PER_PAGE - 1) as $offset) {
            $number = ($page - 1) * self::TICKETS_PER_PAGE + $offset + 1;

            if ($number > self::TICKETS_TOTAL) {
                break;
            }

            $tickets[] = [
                'code' => sprintf('TCK-2026-%04d', $number),
                'client' => $clients[$offset],
                'article' => $articles[$offset],
                'received_at' => sprintf('2026-11-%02d 09:30', ($number % 28) + 1),
                'amount' => $offset === 4 ? null : 350 + $number * 125.5,
                'status' => $statuses[$offset],
            ];
        }

        return $tickets;
    }
}
