<?php

namespace App\Http\Livewire\Dasboard;

use App\Models\Client;
use Livewire\Component;

class Dashboard extends Component
{
    public function render()
    {
        $clientsData = Client::has('invoices')
            ->withSum('invoices', 'price_total')
            ->withSum(['invoices as price_total_paid' => function ($query) {
                $query->has('bill');
            }], 'price_ht')
            ->limit(15)
            ->get();

        $clients = $clientsData->sortBy([['invoices_sum_price_total', 'desc']]);

        return view('theme.livewire.dasboard.dashboard', compact('clients'));
    }
}
