<?php

namespace App\Http\Controllers\Commercial\Bill;

use App\Http\Controllers\Controller;
use App\Http\Requests\Commercial\Bill\BillFormRequest;
use App\Http\Requests\Commercial\Bill\BillInvoiceFormRequest;
use App\Models\Finance\Bill;
use App\Models\Finance\Invoice;
use Illuminate\Http\Request;

class BillController extends Controller
{

    public function index()
    {
        $bills = Bill::all();

        return view('theme.pages.Commercial.Bill.__datatable.index', compact('bills'));
    }

    public function addBill(Request $request)
    {

        validator($request->route()->parameters(), [

            'invoice' => ['required', 'uuid']

        ])->validate();

        $invoice = Invoice::whereUuid($request->invoice)->firstOrFail();

        return view('theme.pages.Commercial.Bill.__create.index', compact('invoice'));
    }

    public function storeBill(BillFormRequest $request, Invoice $invoice)
    {
        // dd($request->all(), "###", $invoice);

        $bill = new Bill();

        $bill->bill_date = $request->date('bill_date');
        $bill->bill_mode = $request->bill_mode;
        $bill->price_ht = $invoice->price_ht;
        $bill->price_total = $invoice->price_total;
        $bill->price_tva = $invoice->total_tva;

        $bill->ref = $request->ref;
        $bill->notes = $request->notes;

        $bill->invoice()->associate($invoice);
        $bill->company()->associate($invoice->company);
        $bill->client()->associate($invoice->client);
        $bill->save();

        $invoice->update(['status' => 'paid']);

        return redirect()->route('commercial:bills.index');
    }

    public function delete(Request $request)
    {
        $request->validate(['billId' => 'required|uuid']);

        $bill = Bill::whereUuid($request->billId)->firstOrFail();

        $invoice = $bill->invoice()->firstOrFail();

        if ($bill && $invoice) {

            $bill->delete();

            $invoice->update(['status' => 'non-paid']);

            return redirect()->back()->with('success', "Le reglement  a éte supprimer avec success");
        }
        return redirect()->back()->with('success', "erreur . . . ");
    }
}
