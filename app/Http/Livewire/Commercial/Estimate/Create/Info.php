<?php

namespace App\Http\Livewire\Commercial\Estimate\Create;

use App\Constants\Etat;
use App\Constants\Status;
use App\Models\Client;
use App\Models\Finance\Company;
use App\Repositories\Client\ClientInterface;
use App\Repositories\Company\CompanyInterface;
use Livewire\Component;

class Info extends Component
{
    protected $listeners = [
        //'selectedClientItem',
        'selectedCompanyItem',
    ];

    public $companies;

    public $clients;

    public $tickets;

    public $estimateCode;

    public $estimatePrefix;

    public function hydrate()
    {
        $this->emit('select2');
    }

    public function render()
    {
        return view('theme.livewire.commercial.estimate.create.new_info');
    }

    public function mount()
    {
        $this->companies = app(CompanyInterface::class)->getCompanies(['id', 'name', 'is_default','estimate_start_number','prefix_estimate']);
        // $this->companies = Company::where('is_default',true)->get();

        $this->clients = app(ClientInterface::class)->getClients(['id', 'entreprise', 'contact']);

        $this->tickets = [];

        $this->estimateCode = '00000';

        $this->estimatePrefix = 'DEVIS-';

        $this->selectedCompanyItem();
    }

    public function selectedClientItem($item)
    {
        if (is_numeric($item)) {
            //$this->tickets = Client::whereId($item)->first()->tickets;
            $this->tickets = Client::whereId($item)->first()?->tickets()
                ->where('etat', Etat::REPARABLE)
                ->where('status', Status::EN_ATTENTE_DE_DEVIS)
                ->get();
        } else {
            $this->tickets = [];
        }
    }

    public function selectedCompanyItem($item = null)
    {
        if (is_null($item)) {
            
            if ($this->companies[0]->estimates->count() <= 0) {
                $number = $this->companies[0]->estimate_start_number;
            } else {
                $number = ($this->companies[0]->estimates->max('code') + 1);
            }

            $this->estimateCode = str_pad($number, 5, 0, STR_PAD_LEFT);

            $this->estimatePrefix = $this->companies[0]->prefix_estimate;
        } elseif (is_numeric($item)) {
            if ($this->companies[$item - 1]->estimates->count() <= 0) {
                $number = $this->companies[$item - 1]->estimate_start_number;
            } else {
                $number = ($this->companies[$item - 1]->estimates->max('code') + 1);
            }

            $this->estimateCode = str_pad($number, 5, 0, STR_PAD_LEFT);

            $this->estimatePrefix = $this->companies[$item - 1]->prefix_estimate;
        }
    }
}
