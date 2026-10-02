<?php

namespace App\Policies;

use App\Models\Finance\BCommand;
use App\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;
use Illuminate\Auth\Access\Response;

class BCommandPolicy
{
    use HandlesAuthorization;

    /**
     * Determine whether the user can view any models.
     *
     * @return Response|bool
     */
    public function viewAny(User $user)
    {
        return $user->can('bcommandes.browse');
    }

    /**
     * Determine whether the user can view the model.
     *
     * @return Response|bool
     */
    public function view(User $user, BCommand $bCommand)
    {
        return $user->can('bcommandes.read');
    }

    /**
     * Determine whether the user can create models.
     *
     * @return Response|bool
     */
    public function create(User $user)
    {
        return $user->can('bcommandes.create')
            ? Response::allow()
            : Response::deny("désolé vous n'avez pas l'autorisation de crée un BC .");
    }

    /**
     * Determine whether the user can update the model.
     *
     * @return Response|bool
     */
    public function update(User $user, BCommand $bCommand)
    {
        return $user->can('bcommandes.edit')
            ? Response::allow()
            : Response::deny("désolé vous n'avez pas l'autorisation de modifier ce  BC .");
    }

    /**
     * Determine whether the user can delete the model.
     *
     * @return Response|bool
     */
    public function delete(User $user, BCommand $bCommand)
    {
        return $user->can('bcommandes.delete');
    }

    /**
     * Determine whether the user can restore the model.
     *
     * @return Response|bool
     */
    public function restore(User $user, BCommand $bCommand)
    {
        return $user->can('bcommandes.delete');
    }

    /**
     * Determine whether the user can permanently delete the model.
     *
     * @return Response|bool
     */
    public function forceDelete(User $user, BCommand $bCommand)
    {
        return $user->can('bcommandes.delete');
    }
}
