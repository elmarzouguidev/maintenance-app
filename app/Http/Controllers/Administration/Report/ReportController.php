<?php

declare(strict_types=1);

namespace App\Http\Controllers\Administration\Report;

use App\Http\Controllers\Controller;
use App\Models\Client;
use Illuminate\Http\Request;
use Spatie\QueryBuilder\AllowedFilter;
use Spatie\QueryBuilder\QueryBuilder;
use Spatie\QueryBuilder\QueryBuilderRequest;
use Illuminate\Database\Eloquent\Builder;

class ReportController extends Controller
{


    public function index()
    {

        if (request()->has('appFilter') && request()->filled('appFilter')) {
            // QueryBuilderRequest::setArrayValueDelimiter('|');

            $clients = QueryBuilder::for(Client::class)
                ->allowedFilters([
                    AllowedFilter::callback('GetPeriod', function (Builder $query, $value) {

                        $query->with(['invoices' => function ($query) use ($value) {
                            
                            $query->filtersPeriods($value);
                        }]);
                    }),
                ]);
           // dd($clients->get());
        } else {

            $clients = Client::select('id', 'uuid', 'code', 'contact', 'entreprise')->with(['invoices' => function ($query) {
                $query->withSum('bill', 'price_total');
            }])
                ->get();

            $total = $clients->pluck('invoices')
                ->flatten();
            //->sum('bill_sum_price_total');

            //dd($clients, $total);
        }
        return view('theme.pages.Report.__datatable.index', compact('clients', 'total'));
    }
}
