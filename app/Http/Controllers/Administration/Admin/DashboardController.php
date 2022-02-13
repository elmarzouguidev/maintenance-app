<?php

namespace App\Http\Controllers\Administration\Admin;

use App\Http\Controllers\Controller;
use App\Models\Client;
use App\Models\Finance\Bill;
use App\Models\Finance\Company;
use App\Models\Finance\Estimate;
use App\Models\Finance\Invoice;
use App\Models\Ticket;
use Illuminate\Http\Request;
use Spatie\QueryBuilder\AllowedFilter;
use Spatie\QueryBuilder\QueryBuilder;
use Spatie\QueryBuilder\QueryBuilderRequest;

class DashboardController extends Controller
{


    public function index()
    {

        //dd(now()->addDays(10)->toDateString());
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

        if (request()->has('appFilter') && request()->filled('appFilter')) {
            // QueryBuilderRequest::setArrayValueDelimiter('|');

            $invoices = QueryBuilder::for(Invoice::dashboard())
                ->allowedFilters([
                    AllowedFilter::scope('GetPeriod', 'filters_periods'),
                    AllowedFilter::scope('DateBetween', 'filters_date'),
                    AllowedFilter::scope('GetCompany', 'filters_companies'),
                ]);

            $allInvoices = $invoices->get();

            $invoicesNotPaid = $allInvoices->filter(function ($invoice) {
                return $invoice->status === 'non-paid';
            })->count();

            $invoicesRetard = $allInvoices->filter(function ($invoice) {
               // dd($invoice->date_due->isPast(),now()->toDateString());
                return $invoice->date_due->isPast() && $invoice->status === 'non-paid';
            })->count();

            $chiffreAff = collect($allInvoices)->filter(function ($item, $key) {
                return $item->status === 'paid';
            })->sum('price_total');

            $chiffreTVA = collect($allInvoices)->filter(function ($item, $key) {
                return $item->status === 'paid';
            })->sum('total_tva');

            $chiffreBills = $allInvoices->filter(function ($invoice) {
                return $invoice->status === 'paid';
            })->sum('price_total');

            $latest = [
                'invoices' => $allInvoices->take(5),
                'estimates' => Estimate::latest()->select(['id', 'uuid', 'full_number', 'created_at'])->take(5)->get(),
                'clients' => Client::latest()->select(['id', 'uuid', 'client_ref', 'created_at', 'entreprise'])->take(5)->get(),
            ];

            $allInvoices = $allInvoices->count();
        } else {
            $chiffreAff = Invoice::whereMonth('created_at', '=', date('m'))->whereStatus('paid')->sum('price_total');
            $chiffreBills = Bill::whereMonth('created_at', '=', date('m'))->sum('price_total');
            $chiffreTVA = Invoice::whereMonth('created_at', '=', date('m'))->whereStatus('paid')->sum('total_tva');

            $allInvoices = Invoice::count();

            $invoicesNotPaid = Invoice::whereStatus('non-paid')->count();
            $invoicesRetard = Invoice::whereStatus('non-paid')->whereDate('date_due', '<', now()->toDateString())->count();

            $latest = [
                'invoices' => Invoice::latest()->select(['id', 'uuid', 'full_number', 'created_at'])->take(5)->get(),
                'estimates' => Estimate::latest()->select(['id', 'uuid', 'full_number', 'created_at'])->take(5)->get(),
                'clients' => Client::latest()->select(['id', 'uuid', 'client_ref', 'created_at', 'entreprise'])->take(5)->get(),
            ];
        }

        $companies = Company::select(['id','uuid','name'])->get();

        return view('theme.pages.Home.index', compact('tickets', 'ticketsCount', 'ticketsLast', 'ticketsPret', 'latest', 'chiffreAff', 'chiffreBills', 'chiffreTVA', 'invoicesNotPaid', 'invoicesRetard', 'allInvoices','companies'));
    }
}
