<?php

namespace App\Policies;

use App\Models\Finance\Bill;
use App\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;
use Illuminate\Auth\Access\Response;

class BillPolicy
{
    use HandlesAuthorization;

    /**
     * Determine whether the user can view any models.
     *
     * @return Response|bool
     */
    public function viewAny(User $user)
    {
        return $user->can('payments.browse');
    }

    /**
     * Determine whether the user can view the model.
     *
     * @return Response|bool
     */
    public function view(User $user, Bill $bill)
    {
        return $user->can('payments.read');
    }

    /**
     * Determine whether the user can create models.
     *
     * @return Response|bool
     */
    public function create(User $user)
    {
        return $user->can('payments.create')
            ? Response::allow()
            : Response::deny("désolé vous n'avez pas l'autorisation de ajouter un Règlement .");
    }

    /**
     * Determine whether the user can update the model.
     *
     * @return Response|bool
     */
    public function update(User $user, Bill $bill)
    {
        return $user->can('payments.edit')
            ? Response::allow()
            : Response::deny("désolé vous n'avez pas l'autorisation de modifier un Règlement .");
    }

    /**
     * Determine whether the user can delete the model.
     *
     * @return Response|bool
     */
    public function delete(User $user, Bill $bill)
    {
        return $user->can('payments.delete')
            ? Response::allow()
            : Response::deny("désolé vous n'avez pas l'autorisation de supprimer un Règlement .");
    }

    /**
     * Determine whether the user can restore the model.
     *
     * @return Response|bool
     */
    public function restore(User $user, Bill $bill)
    {
        //
    }

    /**
     * Determine whether the user can permanently delete the model.
     *
     * @return Response|bool
     */
    public function forceDelete(User $user, Bill $bill)
    {
        //
    }
}
