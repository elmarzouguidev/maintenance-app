<?php

namespace App\Http\Controllers\Commercial\Bill;

use App\Http\Controllers\Controller;
use App\Http\Requests\Commercial\Bill\BillFormRequest;
use App\Http\Requests\Commercial\Bill\BillUpdateFormRequest;
use App\Models\Finance\Bill;
use App\Models\Finance\Company;
use App\Models\Finance\Invoice;
use App\Repositories\Client\ClientInterface;
use Illuminate\Http\Request;
use Spatie\QueryBuilder\AllowedFilter;
use Spatie\QueryBuilder\QueryBuilder;

class BillController extends Controller
{
    public function index()
    {
        if (request()->has('appFilter') && request()->filled('appFilter')) {
            $bills = QueryBuilder::for(Bill::class)
                ->allowedFilters([
                    AllowedFilter::scope('GetCompany', 'filters_companies'),
                    AllowedFilter::scope('GetClient', 'filters_clients'),
                    AllowedFilter::scope('GetPaymentMode', 'filters_payment_mode'),
                    AllowedFilter::scope('DateBetween', 'filters_date'),
                    AllowedFilter::scope('GetPeriod', 'filters_periods'),
                ])
                ->with(['billable', 'company'])
                ->latest()
                ->get();
        } else {
            $bills = Bill::with('billable')->latest()->get();
        }

        $invoices = Invoice::select('id', 'uuid', 'code', 'price_total', 'full_number')
            ->doesntHave('bill')
            ->doesntHave('avoir')
            ->get();

        $clients = app(ClientInterface::class)->getClients(['id', 'uuid', 'entreprise', 'contact']);
        $companies = Company::select(['id', 'name', 'uuid'])->get();

        return view('theme.pages.Commercial.Bill.__datatable.index', compact('bills', 'invoices', 'clients', 'companies'));
    }

    public function create()
    {
        $this->authorize('create', Bill::class);

        return view('theme.pages.Commercial.Bill.__create_normal.index');
    }

    public function edit(Bill $bill)
    {
        //$bill->load('invoice');
        $this->authorize('update', $bill);

        return view('theme.pages.Commercial.Bill.__edit.index', compact('bill'));
    }

    public function update(BillUpdateFormRequest $request, Bill $bill)
    {
        $this->authorize('update', $bill);

        $bill->bill_date = $request->date('bill_date');
        $bill->bill_mode = $request->bill_mode;
        $bill->reference = $request->reference;
        $bill->notes = $request->notes;
        $bill->added_by = auth()->user()->full_name;

        $bill->save();

        return redirect(route('commercial:bills.index'))->with('success', 'Le règlement  a éte modifier avec success');
    }

    public function addBill(Request $request)
    {
        $this->authorize('create', Bill::class);

        validator($request->route()->parameters(), [

            'invoice' => ['required', 'uuid'],

        ])->validate();

        $invoice = Invoice::whereUuid($request->invoice)->firstOrFail();

        return view('theme.pages.Commercial.Bill.__create.index', compact('invoice'));
    }

    public function storeBill(BillFormRequest $request, Invoice $invoice)
    {
        $this->authorize('create', Bill::class);

        $biller = [
            'bill_date' => $request->date('bill_date'),
            'bill_mode' => $request->bill_mode,
            'reference' => $request->reference,
            'notes' => $request->notes,
            'price_ht' => $invoice->price_ht,
            'price_total' => $invoice->price_total,
            'price_tva' => $invoice->price_tva,
            'company_id' => $invoice->company?->id,
            'added_by' => auth()->user()->full_name,
        ];

        $invoice->bill()->create($biller);

        $invoice->update(['status' => 'paid', 'is_paid' => true]);

        return redirect(route('commercial:bills.index'))->with('success', 'Le règlement  a éte ajouter avec success');
    }

    public function store(BillFormRequest $request)
    {
        $this->authorize('create', Bill::class);

        $invoice = Invoice::whereUuid($request->invoice)->firstOrFail();

        $biller = [
            'bill_date' => $request->date('bill_date'),
            'bill_mode' => $request->bill_mode,
            'reference' => $request->reference,
            'notes' => $request->notes,
            'price_ht' => $invoice->price_ht,
            'price_total' => $invoice->price_total,
            'price_tva' => $invoice->price_tva,
            'company_id' => $invoice->company?->id,
            'added_by' => auth()->user()->full_name,
        ];

        $invoice->bill()->create($biller);

        $invoice->update(['status' => 'paid']);

        return redirect(route('commercial:bills.index'))->with('success', 'Le règlement  a éte ajouter avec success');
    }

    public function deleteBill(Request $request)
    {
        $request->validate(['billId' => 'required|uuid']);

        $bill = Bill::whereUuid($request->billId)->firstOrFail();

        $this->authorize('delete', $bill);

        $invoice = $bill->billable()->first();

        if ($bill) {
            if ($invoice) {
                $invoice->update(['status' => 'non-paid']);
            }

            $bill->delete();

            return redirect()->back()->with('success', 'Le règlement  a éte supprimer avec success');
        }

        return redirect()->back()->with('success', 'erreur . . . ');
    }
}
