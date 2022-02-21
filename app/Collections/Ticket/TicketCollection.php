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

            if ($ticket->status === 'en-cours-de-diagnostic' && $ticket->user_id === auth()->id()) {
                return 'ouvert';
            }
            if ($ticket->status === 'en-attent-de-devis' && $ticket->user_id === auth()->id()) {
                return 'en-attent-de-devis';
            }
            if ($ticket->status === 'retour-devis-non-confirme' && $ticket->user_id === auth()->id()) {
                return 'annuler';
            }


            if ($ticket->status === 'a-reparer' && $ticket->user_id === auth()->id()) {
                return 'a-preparer';
            }

            if ($ticket->status === 'encours-de-reparation' && $ticket->user_id === auth()->id()) {
                return 'encours-de-reparation';
            }
            if ($ticket->status === 'pret-a-etre-livre' && $ticket->user_id === auth()->id()) {
                return 'pret-a-etre-livre';
            }
            return 'normal';
        });
    }

    public function groupByReparEtat(): TicketCollection
    {

        return $this->groupBy(function ($ticket) {

            if ($ticket->status === 'en-attent-de-devis') {
                return 'en-attent-de-devis';
            }

            if ($ticket->status === 'retour-non-reparable') {
                return 'retour-non-reparable';
            }

            return 'normal';
        });
    }
}
