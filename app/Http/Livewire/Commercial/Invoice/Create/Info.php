<?php

namespace App\Http\Livewire\Commercial\Invoice\Create;

use App\Models\Ticket;
use App\Repositories\Client\ClientInterface;
use App\Repositories\Company\CompanyInterface;
use Livewire\Component;

class Info extends Component
{
    public $companies;

    public $clients;

    public $tickets;

    public $selectCompany;
    public $selectClient;
    public $selectTicket;

    public function render()
    {
        return view('theme.livewire.commercial.invoice.create.info');
    }

    public function mount()
    {
        $this->companies = app(CompanyInterface::class)->getCompanies(['id', 'name']);
        $this->clients = app(ClientInterface::class)->getClients(['id', 'entreprise', 'contact']);

        $this->tickets = [];
    }

    public function updatingSelectClient()
    {
        //dd('Ouiii');
        
    }

    public function updatedSelectClient()
    {
        $this->tickets = Ticket::whereClientId($this->selectClient)->get();
    }
}
