<?php

namespace App\Http\Livewire\Commercial\Estimate\Create;

use App\Models\Client;
use App\Repositories\Client\ClientInterface;
use Livewire\Component;

class Tickets extends Component
{

    protected $listeners = [
        'selectedClientItem',
    ];

    public $clientTickets;

    public function hydrate()
    {
        $this->emit('select2');
    }

    public function render()
    {
        return view('theme.livewire.commercial.estimate.create.tickets');
    }

    public function mount()
    {
        $this->clientTickets = [];
    }

    public function selectedClientItem($item)
    {

        if (is_numeric($item)) {
            $this->clientTickets = Client::whereId($item)->first()->tickets;
            //dd($this->clientTickets,'ff');
        } else {
            $this->clientTickets = [];
        }
    }
}
