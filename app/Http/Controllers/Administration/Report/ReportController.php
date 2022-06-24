<?php

declare(strict_types=1);

namespace App\Http\Controllers\Administration\Report;

use App\Http\Controllers\Controller;
use App\Models\Client;
use Illuminate\Http\Request;

class ReportController extends Controller
{


    public function index()
    {
        $clients = Client::select('id', 'uuid', 'code', 'contact', 'entreprise')->with(['invoices' => function ($query) {
            $query->withSum('bill', 'price_total');
        }])
            ->get();

        $total = $clients->pluck('invoices')
            ->flatten();
        //->sum('bill_sum_price_total');

        //dd($clients, $total);

        return view('theme.pages.Report.__datatable.index', compact('clients', 'total'));
    }
}
