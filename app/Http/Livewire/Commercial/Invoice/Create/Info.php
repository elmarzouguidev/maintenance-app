<?php

namespace App\Http\Livewire\Commercial\Invoice\Create;

use App\Models\Finance\Company;
use App\Models\Ticket;
use App\Repositories\Client\ClientInterface;
use App\Repositories\Company\CompanyInterface;
use Livewire\Component;

class Info extends Component
{
    protected $listeners = [
        'selectedClientItem',
        'selectedCompanyItem',
    ];

    public function hydrate()
    {
        $this->emit('select2');
    }

    public $companies;

    public $clients;

    public $tickets;
    public $invoiceCode;
    public $invoicePrefix;

    public function render()
    {
        return view('theme.livewire.commercial.invoice.create.info');
    }

    public function mount()
    {
        $this->companies = app(CompanyInterface::class)->getCompanies(['id', 'name']);

        $this->clients = app(ClientInterface::class)->getClients(['id', 'entreprise', 'contact']);

        $this->tickets = [];

        $this->invoiceCode = '0000';

        $this->invoicePrefix = 'FACTURE-';
    }

    public function selectedClientItem($item)
    {
        if (is_numeric($item)) {
            $this->tickets = $this->clients[intval($item) - 1]->tickets;
        } else {
            $this->tickets = [];
        }

    }

    public function selectedCompanyItem($item)
    {
        if (is_numeric($item)) {
            $number = $this->companies[$item - 1]->invoice_start_number + ($this->companies[$item - 1]->invoices->count() + 1);

            $this->invoiceCode = str_pad($number, 5, 0, STR_PAD_LEFT);

            $this->invoicePrefix = $this->companies[$item - 1]->prefix_invoice;
        }
    }
}
