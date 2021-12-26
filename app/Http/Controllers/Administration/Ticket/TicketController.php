<?php

namespace App\Http\Controllers\Administration\Ticket;

use App\Http\Controllers\Controller;
use App\Http\Requests\Application\Ticket\TicketFormRequest;
use App\Models\Ticket;
use Illuminate\Http\Request;

class TicketController extends Controller
{


    public function index()
    {
        return view('theme.pages.Ticket.index');
    }

    public function create()
    {
    }
    public function store(TicketFormRequest $request)
    {
    }

    public function show(Ticket $ticket)
    {
       
        return view('theme.pages.Ticket.__single.index', compact('ticket'));
    }
}
