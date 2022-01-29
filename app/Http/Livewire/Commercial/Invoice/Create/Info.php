<?php

namespace App\Http\Livewire\Commercial\Invoice\Create;

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
    public $invoiceCode = 0000;
    public $invoicePrefix = '0000';
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
        $this->tickets = Ticket::whereClientId($this->selectClient)->get();
    }

    public function updatedSelectCompany()
    {
        //dd($this->companies);
        
        $this->invoiceCode = $this->companies[$this->selectCompany - 1]

            ->invoice_start_number + ($this->companies[$this->selectCompany - 1]->invoices->count() + 1);

        $this->invoicePrefix = $this->companies[$this->selectCompany - 1]->prefix_invoice;
    }
}
