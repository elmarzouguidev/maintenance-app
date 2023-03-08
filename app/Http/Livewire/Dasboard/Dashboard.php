<?php

namespace App\Http\Livewire\Dasboard;

use App\Models\Client;
use Carbon\CarbonImmutable;
use Livewire\Component;

class Dashboard extends Component
{

    public $startDate;

    public $endDate;

    public function render()
    {
        $clientsData = Client::has('invoices')
            ->withSum(['invoices' => function ($query) {
                $query->filtersDate($this->startDate, $this->endDate);
            }], 'price_total')
            ->withSum(['invoices as price_total_paid' => function ($query) {
                $query->filtersDate($this->startDate, $this->endDate)->has('bill');
            }], 'price_ht')
            ->limit(20)
            ->get();

        $clients = $clientsData->sortBy([['invoices_sum_price_total', 'desc']]);

        return view('theme.livewire.dasboard.dashboard', compact('clients'));
    }

    public function mount()
    {
        $date = CarbonImmutable::now();

        $firstOfQuarter = $date->firstOfQuarter();

        $lastOfQuarter = $date->lastOfQuarter();

        $this->startDate = $firstOfQuarter->format('Y-m-d');

        $this->endDate = $lastOfQuarter->format('Y-m-d');
    }

    /*public function updated()
    {
        //dd('fefg');
        dd($this->startDate, $this->endDate);
    }*/
}
