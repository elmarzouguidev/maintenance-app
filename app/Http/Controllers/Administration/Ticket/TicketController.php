<?php

namespace App\Http\Controllers\Administration\Ticket;

use App\Domain\Support\SaveModel\SaveModel;
use App\Http\Controllers\Controller;
use App\Http\Requests\Application\Ticket\TicketFormRequest;
use App\Http\Requests\Application\Ticket\TicketUpdateFormRequest;
use App\Models\Ticket;
use App\Repositories\Ticket\TicketInterface;
use Illuminate\Http\Request;

class TicketController extends Controller
{


    public function index()
    {
        $tickets = app(TicketInterface::class)->getTickets();

        return view('theme.pages.Ticket.index', compact('tickets'));
    }

    public function create()
    {
        return view('theme.pages.Ticket.__create.index');
    }
    public function store(TicketFormRequest $request)
    {

        $data = $request->withoutHoneypot();

        $data['slug'] = $request->product;

        $ticket = (new SaveModel(new Ticket(), $data))->execute();

        $ticket->admin()->associate($request->user('admin')->id)->save();

        return redirect()->back()->with('success', "L'ajoute a éte effectuer avec success");
    }

    public function show(string $slug)
    {
        $ticket = app(TicketInterface::class)->getTicketByExternalId($slug)->firstOrFail();

        return view('theme.pages.Ticket.__single.index', compact('ticket'));
    }

    public function edit($id)
    {
        $ticket = app(TicketInterface::class)->getTicketById($id)->firstOrFail();

        return view('theme.pages.Ticket.__edit.index', compact('ticket'));
    }

    public function update(TicketUpdateFormRequest $request, $id)
    {
        $data = $request->withoutHoneypot();

        (new SaveModel(Ticket::find($id), $data))->execute();

        return redirect()->back()->with('success', "La modification a éte effectuer avec success");
    }
}
