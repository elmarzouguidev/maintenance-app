<?php

namespace App\Http\Livewire\Dasboard;

use App\Models\Client;
use Carbon\CarbonImmutable;
use Illuminate\Support\Carbon;
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
            ->limit(10)
            ->get();

        $clients = $clientsData->sortBy([['invoices_sum_price_total', 'desc']]);

        return view('theme.livewire.dasboard.dashboard', compact('clients'));
    }

    public function mount()
    {
        
        $date = CarbonImmutable::now();

        $firstOfMonth = $date->firstOfMonth();

        $lastOfMonth = $date->lastOfMonth();

        $this->startDate = $firstOfMonth->format('Y-m-d');

        $this->endDate = $lastOfMonth->format('Y-m-d');
    }

    /*public function updatedStartDate()
    {
        $this->startDate = Carbon::createFromFormat('Y-m-d', $this->startDate);
        $this->mount();
    }

    public function updatedEndDate()
    {
        $this->endDate = Carbon::createFromFormat('Y-m-d', $this->endDate);
        $this->mount();
    }*/
}
