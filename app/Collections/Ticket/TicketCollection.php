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

            if ($ticket->technicien_id == auth('technicien')->id() && $ticket->status === 'encours-diagnostique') {
                return 'ouvert';
            }
            if ($ticket->technicien_id == auth('technicien')->id() && $ticket->status === 'en-attent-de-devis') {
                return 'en-attent-de-devis';
            }
            if ($ticket->technicien_id == auth('technicien')->id() &&  $ticket->status === 'retour-devis-non-confirme') {
                return 'annuler';
            }

            return 'normal';
        });
    }

    public function groupByReparEtat(): TicketCollection
    {

        return $this->groupBy(function ($ticket) {

            if ($ticket->status === 'a-preparer') {
                return 'a-preparer';
            }

            if ($ticket->status === 'encours-de-reparation') {
                return 'encours-de-reparation';
            }
            if ($ticket->status === 'pret-a-livre') {
                return 'pret-a-livre';
            }
            return 'normal';
        });
    }
}
