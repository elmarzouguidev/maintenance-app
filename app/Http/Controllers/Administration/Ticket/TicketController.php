<?php

namespace App\Http\Controllers\Administration\Ticket;

use App\Domain\Support\SaveModel\SaveModel;
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
        return view('theme.pages.Ticket.__create.index');
    }
    public function store(TicketFormRequest $request)
    {
        
        $data = $request->withoutHoneypot();
        $data['slug'] = $request->product;

        (new SaveModel(new Ticket(), $data))->execute();

        return redirect()->back()->with('success', "L'ajoute a éte effectuer avec success");
    }

    public function show(Ticket $ticket)
    {

        return view('theme.pages.Ticket.__single.index', compact('ticket'));
    }
}
