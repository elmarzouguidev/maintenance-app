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
        // Only the invoice list is composed (ViewComposerServiceProvider): the sidebar counts of the
        // former Bootstrap layout are in App\Support\AdminNavigation now.
        $view->with('tickets_invoiceable', $this->ticket->ticketsInvoiceable());
    }

    private function timeToLive()
    {
        return Carbon::now()->addDays(30);
    }
}
