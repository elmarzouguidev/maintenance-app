<?php

namespace App\Policies;

use App\Models\Finance\Invoice;
use App\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;
use Illuminate\Auth\Access\Response;

class InvoicePolicy
{
    use HandlesAuthorization;

    /**
     * Determine whether the user can view any models.
     *
     * @return Response|bool
     */
    public function viewAny(User $user)
    {
        //
    }

    /**
     * Determine whether the user can view the model.
     *
     * @return Response|bool
     */
    public function view(User $user, Invoice $invoice)
    {
        //
    }

    /**
     * Determine whether the user can create models.
     *
     * @return Response|bool
     */
    public function create(User $user)
    {
        return $user->can('invoices.create')
            ? Response::allow()
            : Response::deny("désolé vous n'avez pas l'autorisation de crée une Facture .");
    }

    /**
     * Determine whether the user can update the model.
     *
     * @return Response|bool
     */
    public function update(User $user, Invoice $invoice)
    {
        return $user->can('invoices.edit')
            ? Response::allow()
            : Response::deny("désolé vous n'avez pas l'autorisation de modifier cette Facture .");
    }

    /**
     * Determine whether the user can delete the model.
     *
     * @return Response|bool
     */
    public function delete(User $user, Invoice $invoice)
    {
        return $user->can('invoices.delete')
            ? Response::allow()
            : Response::deny("désolé vous n'avez pas l'autorisation de supprimer cette Facture .");
    }

    /**
     * Determine whether the user can restore the model.
     *
     * @return Response|bool
     */
    public function restore(User $user, Invoice $invoice)
    {
        //
    }

    /**
     * Determine whether the user can permanently delete the model.
     *
     * @return Response|bool
     */
    public function forceDelete(User $user, Invoice $invoice)
    {
        //
    }
}
