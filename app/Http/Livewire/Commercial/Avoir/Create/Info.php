<?php

namespace App\Http\Livewire\Commercial\Avoir\Create;

use App\Models\Client;
use App\Models\Finance\Invoice;
use Livewire\Component;

class Info extends Component
{
    protected $listeners = [
        'selectedAvoirItem',
    ];

    public $invoices;
    public $company;
    public $client;
    public $avoirNumber;

    public function render()
    {
        return view('theme.livewire.commercial.avoir.create.info');
    }

    public function mount()
    {
        $this->avoirNumber = '0000';
        $this->company = null;
        $this->client = null;
    }

    public function selectedAvoirItem($item)
    {
        $this->company = Invoice::whereId($item)->first()->company;
        $this->client = Invoice::whereId($item)->first()->client;

        $this->avoirNumber = str_pad($this->company->invoicesAvoir->count() + 1, 5, 0, STR_PAD_LEFT);

    }
}
