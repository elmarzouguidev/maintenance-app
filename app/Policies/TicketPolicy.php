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
        return $user->checkPermissionTo('ticket.read')
            ? Response::allow()
            : Response::deny("désolé vous n'avez pas l'autorisation de voir ce ticket .");
    }

    public function create(User $user)
    {
        return $user->checkPermissionTo('ticket.create')
            ? Response::allow()
            : Response::deny("désolé vous n'avez pas l'autorisation de crée un ticket .");
    }

    public function update(User $user, Ticket $ticket)
    {
        return $user->checkPermissionTo('ticket.edit')
            ? Response::allow()
            : Response::deny("désolé vous n'avez pas l'autorisation de modifier ce ticket .");
    }

    public function delete(User $user, Ticket $ticket)
    {
        return $user->checkPermissionTo('ticket.delete')
            ? Response::allow()
            : Response::deny("désolé vous n'avez pas l'autorisation de supprimer ce ticket .");
    }

    public function restore(User $user, Ticket $ticket)
    {
        //
    }

    public function forceDelete(User $user, Ticket $ticket)
    {
        return $user->checkPermissionTo('ticket.delete')
            ? Response::allow()
            : Response::deny("désolé vous n'avez pas l'autorisation de supprimer ce ticket .");
    }

    /**
     * Open the processing page: technicians (ticket.work), those who manage every technician's
     * diagnostics, and those who answer for the customer (diagnostic.confirm).
     */
    public function canDiagnose(User $user, Ticket $ticket)
    {
        return $user->worksOnTickets() || $user->checkPermissionTo('diagnostic.manage_assigned') || $user->checkPermissionTo('diagnostic.confirm')
            ? Response::allow()
            : Response::deny("désolé vous n'avez pas l'autorisation de diagnostiquer ce ticket .");
    }

    public function canStoreDiagnose(User $user, Ticket $ticket)
    {
        return $user->writesDiagnosticOf($ticket)
            ? Response::allow()
            : Response::deny("désolé vous n'avez pas l'autorisation de diagnostiquer ce ticket .");
    }

    public function canConfirme(User $user, Ticket $ticket)
    {
        return $user->checkPermissionTo('diagnostic.confirm')
            && $ticket->user_id !== null
            && $ticket->status == Status::EN_ATTENTE_DE_BON_DE_COMMAND
            ? Response::allow()
            : Response::deny("désolé vous n'avez pas l'autorisation de confirmer  ce ticket il faut crée le devis avant confirmé .");
    }

    public function canRepear(User $user, Ticket $ticket)
    {
        return $user->repairs($ticket)
            ? Response::allow()
            : Response::deny("désolé vous n'avez pas l'autorisation de Réparer  ce ticket .");
    }

    public function canRepearStore(User $user, Ticket $ticket)
    {
        return $user->repairs($ticket) && $ticket->status == Status::EN_COURS_DE_REPARATION
            ? Response::allow()
            : Response::deny("désolé vous n'avez pas l'autorisation de Réparer  ce ticket .");
    }

    public function canReassign(User $user, Ticket $ticket)
    {
        return $user->checkPermissionTo('ticket.reassign')
            ? Response::allow()
            : Response::deny("désolé vous n'avez pas l'autorisation de réassigner ce ticket .");
    }
}
