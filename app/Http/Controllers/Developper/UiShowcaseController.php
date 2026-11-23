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

class UiShowcaseController extends Controller
{
    /**
     * Show every Tailwind component and variant inside the new shell.
     *
     * Only registered when APP_ENV=local (see routes/web.php). Sample validation errors,
     * a session flash message and a paginator are faked so their states are visible.
     */
    public function __invoke(Request $request): View
    {
        $request->session()->now('success', 'Exemple de message de succès (session flash).');

        ViewFactory::share('errors', (new ViewErrorBag)->put('default', new MessageBag([
            'email' => ["L'adresse e-mail n'est pas valide."],
            'amount' => ['Le montant doit être supérieur à 0.'],
            'company' => ['Veuillez choisir une société.'],
            'notes' => ['La description est obligatoire.'],
            'terms' => ['Vous devez accepter les conditions.'],
        ])));

        $paginator = new LengthAwarePaginator(
            items: range(1, 10),
            total: 240,
            perPage: 10,
            currentPage: max(1, $request->integer('page', 1)),
            options: ['path' => $request->url()],
        );

        return view('theme.pages.UiShowcase.index', [
            'paginator' => $paginator,
        ]);
    }
}
