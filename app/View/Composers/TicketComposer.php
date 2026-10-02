<?php

namespace App\View\Composers;

use App\Models\Ticket;
use Carbon\Carbon;
use Illuminate\Cache\CacheManager;
use Illuminate\View\View;

class TicketComposer
{
    protected Ticket $ticket;

    protected CacheManager $cache;

    public function __construct(Ticket $ticket, CacheManager $cache)
    {
        $this->ticket = $ticket;

        $this->cache = $cache;
    }

    /**
     * Bind data to the view.
     *
     * @return void
     */
    public function compose(View $view)
    {
        if ($view->getName() === 'theme.pages.Commercial.Invoice.index') {
            $view->with('tickets_invoiceable', $this->ticket->ticketsInvoiceable());

            return;
        }

        $user = auth()->user();

        $view->with('new_tickets', $user->can('ticket.browse') ? $this->ticket->newTickets() : 0);
        if ($user->can('diagnostic.browse') || $user->can('diagnostic.manage_assigned')) {
            $view->with('new_tickets_diagnostic', $this->ticket->newTicketsDiagnostic());
        }
        if ($user->can('diagnostic.assigned.browse') || $user->can('diagnostic.edit')) {
            $view->with('new_tickets_diagnostic_tech', $this->ticket->newTicketsDiagnosticTech());
        }
        $canBrowseDelivery = $user->can('ticket.delivery.browse') || $user->can('ticket.delivery.browse_all');
        $view->with('tickets_livrable', $canBrowseDelivery ? $this->ticket->ticketsLivrable(true) : 0);

        /*$view->with('categoriesMenu', $this->cache->remember('categoriesMenu', $this->timeToLive(), function () {
             return $this->categories->categoryInMenu();
         })); */
    }

    private function timeToLive()
    {
        return Carbon::now()->addDays(30);
    }
}
