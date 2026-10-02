<?php

namespace App\Policies;

use App\Constants\Status;
use App\Models\Ticket;
use App\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;
use Illuminate\Auth\Access\Response;

class TicketPolicy
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
    public function view(User $user, Ticket $ticket)
    {
        return $user->can('ticket.read')
            ? Response::allow()
            : Response::deny("désolé vous n'avez pas l'autorisation de voir ce ticket .");
    }

    /**
     * Determine whether the user can create models.
     *
     * @return Response|bool
     */
    public function create(User $user)
    {
        return $user->can('ticket.create')
            ? Response::allow()
            : Response::deny("désolé vous n'avez pas l'autorisation de crée un ticket .");
    }

    /**
     * Determine whether the user can update the model.
     *
     * @return Response|bool
     */
    public function update(User $user, Ticket $ticket)
    {
        return $user->can('ticket.edit')
            ? Response::allow()
            : Response::deny("désolé vous n'avez pas l'autorisation de modifier ce ticket .");
    }

    /**
     * Determine whether the user can delete the model.
     *
     * @return Response|bool
     */
    public function delete(User $user, Ticket $ticket)
    {
        return $user->can('ticket.delete')
            ? Response::allow()
            : Response::deny("désolé vous n'avez pas l'autorisation de supprimer ce ticket .");
    }

    /**
     * Determine whether the user can restore the model.
     *
     * @return Response|bool
     */
    public function restore(User $user, Ticket $ticket)
    {
        //
    }

    /**
     * @return Response
     */
    public function forceDelete(User $user, Ticket $ticket)
    {
        return $user->can('ticket.delete')
            ? Response::allow()
            : Response::deny("désolé vous n'avez pas l'autorisation de supprimer ce ticket .");
    }

    /**
     * @return Response
     */
    public function canDiagnose(User $user, Ticket $ticket)
    {
        $assignedToUser = $ticket->technicien()->is($user);
        $unassigned = $ticket->user_id === null;

        return $user->can('diagnostic.browse')
            || ($user->can('diagnostic.manage_assigned') && ! $unassigned)
            || (($user->can('diagnostic.assigned.browse') || $user->can('diagnostic.edit')) && $assignedToUser)
            || ($user->can('diagnostic.edit') && $unassigned)
            ? Response::allow()
            : Response::deny("désolé vous n'avez pas l'autorisation de diagnostiquer ce ticket .");
    }

    public function canStoreDiagnose(User $user, Ticket $ticket)
    {
        return ($user->can('diagnostic.edit') && $ticket->technicien()->is($user))
            || ($user->can('diagnostic.manage_assigned') && $ticket->user_id !== null)
            ? Response::allow()
            : Response::deny("désolé vous n'avez pas l'autorisation de diagnostiquer ce ticket .");
    }

    public function canConfirme(User $user, Ticket $ticket)
    {
        return $user->can('diagnostic.confirm')
            && $ticket->user_id !== null
            && $ticket->status == Status::EN_ATTENTE_DE_BON_DE_COMMAND
            ? Response::allow()
            : Response::deny("désolé vous n'avez pas l'autorisation de confirmer  ce ticket il faut crée le devis avant confirmé .");
    }

    public function canRepear(User $user, Ticket $ticket)
    {
        $assignedToUser = $ticket->technicien()->is($user);

        return $user->can('reparations.browse')
            || (($user->can('reparations.assigned.browse') || $user->can('reparations.edit')) && $assignedToUser)
            || ($user->can('reparations.manage_assigned') && $ticket->user_id !== null)
            ? Response::allow()
            : Response::deny("désolé vous n'avez pas l'autorisation de Réparer  ce ticket .");
    }

    public function canRepearStore(User $user, Ticket $ticket)
    {
        $assignedToUser = $ticket->technicien()->is($user);
        $inRepair = $ticket->status == Status::EN_COURS_DE_REPARATION;

        return ($user->can('reparations.edit') && $assignedToUser && $inRepair)
            || ($user->can('reparations.manage_assigned') && $ticket->user_id !== null && $inRepair)
            ? Response::allow()
            : Response::deny("désolé vous n'avez pas l'autorisation de Réparer  ce ticket .");
    }

    /**
     * Determine whether Super Admin can reassign tickets to different technicians.
     *
     * @return Response|bool
     */
    public function canReassign(User $user, Ticket $ticket)
    {
        return $user->can('ticket.reassign')
            ? Response::allow()
            : Response::deny("désolé vous n'avez pas l'autorisation de réassigner ce ticket .");
    }
}
