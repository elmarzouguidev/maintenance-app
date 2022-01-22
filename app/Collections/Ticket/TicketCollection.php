<?php

namespace App\Collections\Ticket;

use Illuminate\Database\Eloquent\Collection;

class TicketCollection extends Collection
{

    /**
     * @return TicketCollection
     */
    public function groupByStatus(): TicketCollection
    {

        return $this->groupBy(function ($ticket) {

            if ($ticket->status === 'ouvert') {
                return 'ouvert';
            }
            if ($ticket->status === 'envoyer') {
                return 'envoyer';
            }
            if ($ticket->status === 'annuler') {
                return 'annuler';
            }
            if ($ticket->status === 'confirme') {
                return 'confirme';
            }
            if ($ticket->status === 'en-attent-de-devis') {
                return 'en-attent-de-devis';
            }
            return 'normal';
        });
    }

    public function groupByReparEtat(): TicketCollection
    {

        return $this->groupBy(function ($ticket) {

            if ($ticket->status === 'confirme') {
                return 'confirme';
            }

            if ($ticket->status === 'encours-reparation') {
                return 'encours-reparation';
            }
            if ($ticket->status === 'finalizer-reparation') {
                return 'finalizer-reparation';
            }
            return 'normal';
        });
    }
}
