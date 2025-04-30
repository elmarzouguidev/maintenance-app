<?php

namespace App\Http\Livewire\Commercial\BL;

use App\Repositories\Client\ClientInterface;
use App\Repositories\Company\CompanyInterface;
use Livewire\Component;

class BLInfo extends Component
{
 

    public $companies;

    public $clients;

    public $bCommandCode;

    public $bCommandPrefix;

    public $selectCompany;



    public function render()
    {
        return view('theme.livewire.commercial.b-l.b-l-info');
    }

    public function mount()
    {
        $this->companies = app(CompanyInterface::class)->getCompanies(['id', 'name']);

        $this->clients = app(ClientInterface::class)->getClients();

        $this->bCommandCode = '0000';

        $this->bCommandPrefix = 'BL-';
    }

    public function updatedSelectCompany()
    {

        if (is_numeric($this->selectCompany)) {
            if ($this->companies[$this->selectCompany - 1]->bCommands->count() <= 0) {
                $number = $this->companies[$this->selectCompany - 1]->blivraison_start_number;
            } else {
                $number = ($this->companies[$this->selectCompany - 1]->bCommands->max('code') + 1);
            }

            $this->bCommandCode = str_pad($number, 5, 0, STR_PAD_LEFT);

            $this->bCommandPrefix = $this->companies[$this->selectCompany - 1]->prefix_blivraison;
        }
    }
}
