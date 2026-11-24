<?php

namespace App\View\Composers;

use App\Models\User;
use App\Support\AdminNavigation;
use Illuminate\View\View;

/**
 * Feeds the UI kit tenant layout (resources/views/layouts/admin.blade.php) with the signed-in
 * user's navigation, phone shortcuts, account menu and logout action. The layout makes no
 * permission decision itself; AdminNavigation filters the entries.
 */
class AdminLayoutComposer
{
    public function __construct(private AdminNavigation $navigation) {}

    /**
     * Bind the layout data for the current user and page.
     */
    public function compose(View $view): void
    {
        $user = auth()->user();

        if (! $user instanceof User) {
            return;
        }

        $view->with([
            ...$this->navigation->forUser($user, request()->path()),
            'accountMenu' => [
                ['href' => route('admin:profile.index'), 'label' => 'Profil', 'icon' => 'user'],
                ['href' => route('admin:profile.settings'), 'label' => 'Paramètres', 'icon' => 'cog'],
            ],
            'logoutAction' => route('admin:auth:logout'),
        ]);
    }
}
