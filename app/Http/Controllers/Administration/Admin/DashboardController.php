<?php

namespace App\Http\Controllers\Administration\Admin;

use App\Http\Controllers\Controller;
use App\Models\Client;
use App\Models\Finance\Estimate;
use App\Models\Finance\Invoice;
use App\Models\Ticket;
use Illuminate\Http\Request;
use Spatie\QueryBuilder\AllowedFilter;
use Spatie\QueryBuilder\QueryBuilder;

class DashboardController extends Controller
{


    public function index()
    {

        $allTicket = Ticket::all(['stat', 'etat', 'pret_a_facture']);

        $ticketsLast = $allTicket->filter(function ($ticket) {
            return $ticket->technicien_id === null
                &&
                $ticket->stat === 'non-traite'
                ||
                $ticket->etat === 'non-diagnostiquer';
        })->count();

        $ticketsPret = $allTicket->filter(function ($ticket) {
            return $ticket->pret_a_facture === true
                &&
                $ticket->stat === 'pret-a-livre';
        })->count();

        $tickets = $allTicket->filter(function ($ticket) {
            return $ticket->pret_a_facture === true
                &&
                $ticket->stat === 'finalizer-reparation';
        })->count();

        $ticketsCount = $allTicket->count();

        $latest = [
            'invoices' => Invoice::latest()->select(['id', 'uuid', 'full_number', 'created_at'])->take(5)->get(),
            'estimates' => Estimate::latest()->select(['id', 'uuid', 'full_number', 'created_at'])->take(5)->get(),
            'clients' => Client::latest()->select(['id', 'uuid', 'client_ref', 'created_at', 'entreprise'])->take(5)->get(),
        ];

        if (request()->has('appFilter') && request()->filled('appFilter')) {

            $invoices = QueryBuilder::for(Invoice::class)
                ->allowedFilters([
                    AllowedFilter::scope('GetPeriod', 'filters_periods'),
                ]);

            $chiffreAff = $invoices->sum('price_ht');
            $chiffreTVA = $invoices->sum('total_tva');
            $chiffreBills = $invoices->has('bill')->sum('price_total');
            
        } else {
            $chiffreAff = Invoice::whereMonth('created_at', '=', date('m'))->sum('price_ht');
            $chiffreBills = Invoice::has('bill')->whereMonth('created_at', '=', date('m'))->sum('price_total');
            $chiffreTVA = Invoice::whereMonth('created_at', '=', date('m'))->sum('total_tva');
        }

        return view('theme.pages.Home.index', compact('tickets', 'ticketsCount', 'ticketsLast', 'ticketsPret', 'latest', 'chiffreAff', 'chiffreBills', 'chiffreTVA'));
    }
}
