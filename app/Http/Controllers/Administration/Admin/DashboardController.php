<?php

namespace App\Http\Controllers\Administration\Admin;

use App\Constants\Etat;
use App\Constants\Status;
use App\Http\Controllers\Controller;
use App\Http\Requests\Application\Ticket\TicketLivrableFormRequest;
use App\Models\Client;
use App\Models\Finance\Bill;
use App\Models\Finance\Company;
use App\Models\Finance\Estimate;
use App\Models\Finance\Invoice;
use App\Models\Ticket;
use App\Models\Utilities\Delivery;
use App\Repositories\Client\ClientInterface;
use Illuminate\Http\Request;
use Illuminate\Support\Collection;
use Illuminate\Support\Str;
use Spatie\QueryBuilder\AllowedFilter;
use Spatie\QueryBuilder\QueryBuilder;
use Spatie\QueryBuilder\QueryBuilderRequest;

class DashboardController extends Controller
{
    private const TICKETS_PER_PAGE = 30;

    public function index()
    {
        $allTicket = Ticket::all(['status', 'etat', 'can_invoiced']);

        $ticketsLast = $allTicket->filter(function ($ticket) {
            return $ticket->user_id == null
                &&
                $ticket->status == Status::NON_TRAITE
                ||
                $ticket->etat == Etat::NON_DIAGNOSTIQUER;
        })->count();

        $ticketsPret = $allTicket->filter(function ($ticket) {
            return $ticket->pret_a_facture == true
                &&
                $ticket->status == Status::PRET_A_ETRE_LIVRE;
        })->count();

        $tickets = $allTicket->filter(function ($ticket) {
            return $ticket->can_invoiced == true;
        })->count();

        $ticketsCount = $allTicket->count();

        if (request()->has('appFilter') && request()->filled('appFilter')) {
            // QueryBuilderRequest::setArrayValueDelimiter('|');

            $invoices = QueryBuilder::for(Invoice::dashboard())
                ->allowedFilters(
                    AllowedFilter::scope('GetPeriod', 'filters_periods'),
                    AllowedFilter::scope('DateBetween', 'filters_date'),
                    AllowedFilter::scope('GetCompany', 'filters_companies'),
                );

            $bills = QueryBuilder::for(Bill::dashboard())
                ->allowedFilters(
                    AllowedFilter::scope('GetPeriod', 'filters_periods'),
                    AllowedFilter::scope('DateBetween', 'filters_date'),
                    AllowedFilter::scope('GetCompany', 'filters_companies'),
                );

            $estimates = QueryBuilder::for(Estimate::dashboard())
                ->allowedFilters(
                    AllowedFilter::scope('GetPeriod', 'filters_periods'),
                    AllowedFilter::scope('DateBetween', 'filters_date'),
                    AllowedFilter::scope('GetCompany', 'filters_companies'),
                );

            $allInvoices = $invoices->get();

            $allbills = $bills->get();

            $allEstimates = $estimates->get();

            $estimatesNotInvoiced = $allEstimates->filter(function ($estimate) {
                return ! $estimate->is_invoiced;
            })->count();

            $estimatesExpired = $allEstimates->filter(function ($estimate) {
                return $estimate->due_date->isPast() && ! $estimate->is_invoiced;
            })->count();

            $invoicesNotPaid = $allInvoices->filter(function ($invoice) {
                return $invoice->status == 'non-paid' && ! $invoice->due_date->isPast();
            })->count();

            $invoicesPaid = $allInvoices->filter(function ($invoice) {
                return $invoice->status == 'paid';
            })->count();

            $invoicesRetard = $allInvoices->filter(function ($invoice) {
                // dd($invoice->due_date->isPast(),now()->toDateString());
                return $invoice->due_date->isPast() && $invoice->status == 'non-paid';
            })->count();

            $chiffreAff = collect($allInvoices)->sum('price_ht');

            $chiffreAffTTC = collect($allInvoices)->sum('price_total');

            /*$chiffreTVA = collect($bills)->filter(function ($bill, $key) {
                return $bill->bill()->exists();
            })->sum('price_tva');*/
            $chiffreTVA = collect($allbills)->sum('price_tva');
            // dd($chiffreTVA);

            /*$chiffreBills = $allInvoices->filter(function ($invoice) {
                return $invoice->bill()->exists();
            })->sum('price_total');*/

            $chiffreBills = collect($allbills)->sum('price_total');

            $latest = [
                'invoices' => $allInvoices->take(5),
                'estimates' => $allEstimates->take(5),
                'clients' => Client::latest()->select(['id', 'uuid', 'created_at', 'entreprise'])->take(5)->get(),
            ];

            $allInvoices = $allInvoices->count();
            $allEstimates = $allEstimates->count();
        } else {
            $chiffreAff = Invoice::defaultCompany()->sum('price_ht');
            $chiffreAffTTC = Invoice::defaultCompany()->sum('price_total');
            $chiffreBills = Bill::defaultCompany()->sum('price_total');
            $chiffreTVA = Bill::defaultCompany()->sum('price_tva');

            $allInvoices = Invoice::defaultCompany()->count();

            $allEstimates = Estimate::defaultCompany()->count();

            $invoicesNotPaid = Invoice::defaultCompany()->doesntHave('bill')->count();
            $invoicesRetard = Invoice::defaultCompany()->doesntHave('bill')->whereDate('due_date', '<=', now()->toDateString())->count();

            $invoicesPaid = Invoice::defaultCompany()->whereStatus('paid')->has('bill')->count();

            $estimatesExpired = Estimate::defaultCompany()->where('is_invoiced', false)->whereDate('due_date', '<', now()->toDateString())->count();
            $estimatesNotInvoiced = Estimate::defaultCompany()->where('is_invoiced', false)->count();

            $latest = [
                'invoices' => Invoice::defaultCompany()->latest()->select(['id', 'uuid', 'full_number', 'created_at'])->take(5)->get(),
                'estimates' => Estimate::defaultCompany()->latest()->select(['id', 'uuid', 'full_number', 'created_at'])->take(5)->get(),
                'clients' => Client::latest()->select(['id', 'uuid', 'created_at', 'entreprise'])->take(5)->get(),
            ];
        }

        $companies = Company::select(['id', 'uuid', 'name'])->get();

        $months = collect(range(1, 12))->map(fn (int $month): string => Str::ucfirst(now()->startOfYear()->month($month)->translatedFormat('F')))->all();
        $revenueChart = ['title' => "Chiffre d'affaires HT ".now()->year, 'type' => 'line', 'labels' => $months, 'values' => $this->getChartData()->pluck('price')->map(fn ($price): float => round((float) $price, 2))->all()];
        $paymentsChart = ['title' => 'Encaissements TTC '.now()->year, 'type' => 'bar', 'labels' => $months, 'values' => $this->getChartDataBills()->pluck('price')->map(fn ($price): float => round((float) $price, 2))->all()];

        [$topFrom, $topTo, $topClients] = $this->topClients();

        return view(
            'theme.pages.Home.index',
            compact(
                'tickets',
                'ticketsCount',
                'ticketsLast',
                'ticketsPret',
                'latest',
                'chiffreAff',
                'chiffreAffTTC',
                'chiffreBills',
                'chiffreTVA',
                'invoicesPaid',
                'invoicesNotPaid',
                'invoicesRetard',
                'allInvoices',
                'companies',
                'allEstimates',
                'estimatesNotInvoiced',
                'estimatesExpired',
                'revenueChart',
                'paymentsChart',
                'topFrom',
                'topTo',
                'topClients'
            )
        );
    }

    /**
     * The ten clients with invoices, by invoiced total (TTC) over the period, with what was paid
     * (HT of the paid invoices): replaces the Livewire component Dasboard\Dashboard. The period
     * comes from ?top_from=&top_to= (Y-m-d), the current month by default.
     *
     * @return array{0: string, 1: string, 2: Collection<int, Client>}
     */
    private function topClients(): array
    {
        $validated = validator(request()->only(['top_from', 'top_to']), [
            'top_from' => ['nullable', 'date_format:Y-m-d'],
            'top_to' => ['nullable', 'date_format:Y-m-d'],
        ])->valid();

        $from = $validated['top_from'] ?? now()->startOfMonth()->format('Y-m-d');
        $to = $validated['top_to'] ?? now()->endOfMonth()->format('Y-m-d');

        $clients = Client::has('invoices')
            ->withSum(['invoices' => fn ($query) => $query->filtersDate($from, $to)], 'price_total')
            ->withSum(['invoices as price_total_paid' => fn ($query) => $query->filtersDate($from, $to)->has('bill')], 'price_ht')
            ->limit(10)
            ->get()
            ->sortByDesc('invoices_sum_price_total')
            ->values();

        return [$from, $to, $clients];
    }

    public function getChartData()
    {
        $data = Invoice::selectRaw('MONTH(invoice_date) as month, SUM(price_ht) as price')
            ->whereYear('invoice_date', now()->year)
            ->groupBy('month')
            // ->orderBy('month', 'asc')
            ->get();

        // Create an array to store the data for all months (initialize with zero values)
        $chartData = array_fill_keys(range(1, 12), 0);

        // Fill in the data for the existing months
        foreach ($data as $item) {
            $chartData[$item->month] = $item->price;
        }

        // Convert the array to an array of objects
        $formattedData = collect($chartData)->map(function ($value, $key) {
            return [
                'month' => $key,
                'price' => $value,
            ];
        })->values();

        // return response()->json($formattedData);
        return $formattedData;
    }

    public function getChartDataBills()
    {
        $data = Bill::selectRaw('MONTH(bill_date) as month, SUM(price_total) as price')
            ->whereYear('bill_date', now()->year)
            ->groupBy('month')
            // ->orderBy('month', 'asc')
            ->get();

        // Create an array to store the data for all months (initialize with zero values)
        $chartData = array_fill_keys(range(1, 12), 0);

        // Fill in the data for the existing months
        foreach ($data as $item) {
            $chartData[$item->month] = $item->price;
        }

        // Convert the array to an array of objects
        $formattedData = collect($chartData)->map(function ($value, $key) {
            return [
                'month' => $key,
                'price' => $value,
            ];
        })->values();

        return $formattedData;
    }

    /**
     * Tickets ready to leave (repaired, not repairable or estimate refused): every one with
     * ticket.delivery.browse_all, only those an admin released (livrable) with
     * ticket.delivery.browse unless filtering, as before; no access without either.
     */
    public function ticketLivrable()
    {
        $user = auth()->user();
        abort_unless($user->can('ticket.delivery.browse') || $user->can('ticket.delivery.browse_all'), 403);
        $filtered = request()->has('appFilter') && request()->filled('appFilter');

        $query = $filtered
            ? QueryBuilder::for(Ticket::class)
                ->allowedFilters(
                    AllowedFilter::scope('GetStartDate', 'filters_start_date'),
                    AllowedFilter::scope('GetEndDate', 'filters_end_date'),
                    AllowedFilter::scope('GetStatus', 'filters_status'),
                    AllowedFilter::scope('GetClient', 'filters_client'),
                    AllowedFilter::scope('GetEtat', 'filters_etat'),
                    AllowedFilter::scope('GetRetour', 'filters_retour'),
                    AllowedFilter::scope('GetPeriod', 'filters_periods'),
                )
            : Ticket::query();

        if (! $filtered && ! $user->can('ticket.delivery.browse_all')) {
            $query->whereLivrable(true);
        }

        $tickets = $query
            ->whereIn('etat', [Etat::REPARABLE, Etat::NON_REPARABLE])
            ->whereIn('status', [Status::PRET_A_ETRE_LIVRE, Status::RETOUR_NON_REPARABLE, Status::RETOUR_DEVIS_NON_CONFIRME])
            ->with(['client:id,entreprise', 'technicien:id,nom,prenom'])
            ->withCount('delivery')
            ->latest()
            ->paginate(self::TICKETS_PER_PAGE)
            ->withQueryString();

        $clients = app(ClientInterface::class)->select(['id', 'entreprise', 'uuid'])->get();

        return view('theme.pages.Ticket.__pret_livre.__datatable.index', compact('tickets', 'clients'));
    }

    public function confirmLivrable(TicketLivrableFormRequest $request)
    {
        $ticket = Ticket::whereUuid($request->ticket)->firstOrFail();

        if ($ticket) {
            $delivery = new Delivery;
            $delivery->date_end = $request->date_end;
            $delivery->mode = $request->mode;
            $delivery->info_client = $request->info_client;
            $delivery->notes = $request->notes;
            $delivery->ticket_id = $ticket->id;
            $delivery->user_id = auth()->id();
            $delivery->save();
            $ticket->update(['status' => Status::LIVRE]);
            $ticket->statuses()->attach(
                Status::LIVRE,
                [
                    'user_id' => auth()->id(),
                    'start_at' => now(),
                    'description' => __('status.history.'.Status::LIVRE, ['user' => auth()->user()->full_name]),
                ]
            );

            if ($ticket->etat == Etat::REPARABLE && $ticket->status == Status::LIVRE) {
                $ticket->warranty()->create([
                    'start_at' => now(),
                    'end_at' => now()->addMonths(3),
                    'description' => 'la garantie a été commancé ',
                ]);
            }
        }

        return redirect()->back()->with('success', 'Ticket  été Livré');
    }

    public function confirmLivrableAdmin(Request $request)
    {
        $request->validate(['ticket' => 'required|uuid']);

        $ticket = Ticket::whereUuid($request->ticket)->firstOrFail();
        if ($ticket) {
            $ticket->update(['livrable' => true]);
        }

        return redirect()->back()->with('success', 'La livraison a été confrimé pour ce ticket');
    }

    public function invoiceable()
    {
        $tickets = Ticket::whereEtat(Etat::REPARABLE)
            ->whereIn('status', [Status::PRET_A_ETRE_LIVRE, Status::LIVRE])
            ->where('can_invoiced', true)
            ->with('client:id,entreprise', 'technicien:id,nom,prenom')
            ->withCount('invoice')
            ->get();

        $title = 'Tickets en attente de facturation';

        return view('theme.pages.Ticket.__invoiceable.__datatable.index', compact('tickets', 'title'));
    }

    public function invoiceable2()
    {
        $tickets = Ticket::whereEtat(Etat::REPARABLE)
            ->whereIn('status', [Status::LIVRE])
            ->whereDoesntHave('invoice')
            ->whereDoesntHave('invoices')
            ->where('can_invoiced', true)
            ->with('client:id,entreprise', 'technicien:id,nom,prenom')
            ->latest()
            ->paginate(self::TICKETS_PER_PAGE)
            ->withQueryString();

        return view('theme.pages.Ticket.__invoiceable.__datatable.index', compact('tickets'));
    }
}
