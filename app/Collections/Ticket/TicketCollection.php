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

            if ($ticket->stat === 'encours-diagnostique') {
                return 'ouvert';
            }
            if ($ticket->stat === 'en-attent-de-devis') {
                return 'en-attent-de-devis';
            }
            if ($ticket->stat === 'retour-devis-non-confirme') {
                return 'annuler';
            }


            if ($ticket->stat === 'a-preparer') {
                return 'a-preparer';
            }

            if ($ticket->stat === 'encours-de-reparation') {
                return 'encours-de-reparation';
            }
            if ($ticket->stat === 'pret-a-livre') {
                return 'pret-a-livre';
            }

            return 'normal';
        });
    }

    public function groupByReparEtat(): TicketCollection
    {

        return $this->groupBy(function ($ticket) {

            if ($ticket->stat === 'en-attent-de-devis') {
                return 'en-attent-de-devis';
            }

            if ($ticket->stat === 'retour-non-reparable') {
                return 'retour-non-reparable';
            }

            return 'normal';
        });
    }
}
