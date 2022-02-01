<?php

namespace App\Http\Livewire\Commercial\Estimate\Create;

use App\Models\Finance\Company;
use App\Models\Ticket;
use App\Repositories\Client\ClientInterface;
use App\Repositories\Company\CompanyInterface;
use Livewire\Component;

class Info extends Component
{

    public $companies;
    public $clients;
    public $tickets;
    public $estimateCode;
    public $estimatePrefix;
    public $selectCompany;
    public $selectClient;
    public $selectTicket;

    public function render()
    {
        return view('theme.livewire.commercial.estimate.create.info');
    }

    public function mount()
    {
        $this->companies = app(CompanyInterface::class)->getCompanies(['id', 'name']);

        $this->clients = app(ClientInterface::class)->getClients(['id', 'entreprise', 'contact']);

        $this->tickets = [];

        $this->estimateCode = '0000';

        $this->estimatePrefix = 'DEVIS-';
    }

    public function updatedSelectClient()
    {
        if (is_numeric($this->selectClient)) {
            $this->tickets = $this->clients[intval($this->selectClient) - 1]->tickets;
        }
    }

    public function updatedSelectCompany()
    {
        //dd($this->companies[$this->selectCompany - 1]->invoices->count());
        if (is_numeric($this->selectCompany)) {
            $number = $this->companies[$this->selectCompany - 1]->estimate_start_number + ($this->companies[$this->selectCompany - 1]->estimates->count() + 1);

            $this->estimateCode = str_pad($number, 5, 0, STR_PAD_LEFT);

            $this->estimatePrefix = $this->companies[$this->selectCompany - 1]->prefix_estimate;
        }
    }
}
