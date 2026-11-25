<?php

namespace App\Policies;

use App\Models\Finance\BLivraison;
use App\Models\User;
use Illuminate\Auth\Access\Response;

/**
 * Delivery notes, like BCommandPolicy: the matching blivraison.* permission (SuperAdmin passes
 * Gate::before). Without a policy, BLController's authorize() calls refused everyone but
 * SuperAdmin (BL-82).
 */
class BLivraisonPolicy
{
    public function view(User $user, BLivraison $bLivraison): bool
    {
        return $this->allows($user, 'blivraison.read');
    }

    public function create(User $user): Response
    {
        return $this->allows($user, 'blivraison.create')
            ? Response::allow()
            : Response::deny("Désolé, vous n'avez pas l'autorisation de créer un bon de livraison.");
    }

    public function update(User $user, BLivraison $bLivraison): Response
    {
        return $this->allows($user, 'blivraison.edit')
            ? Response::allow()
            : Response::deny("Désolé, vous n'avez pas l'autorisation de modifier ce bon de livraison.");
    }

    public function delete(User $user, BLivraison $bLivraison): bool
    {
        return $this->allows($user, 'blivraison.delete');
    }

    /**
     * The permission (checkPermissionTo: false, not an exception, when it is not seeded).
     */
    private function allows(User $user, string $permission): bool
    {
        return $user->checkPermissionTo($permission);
    }
}
