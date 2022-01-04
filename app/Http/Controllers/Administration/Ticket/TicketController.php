<?php

namespace App\Http\Controllers\Administration\Ticket;

use App\Domain\Support\SaveModel\SaveModel;
use App\Http\Controllers\Controller;
use App\Http\Requests\Application\Ticket\TicketAttachementsFormRequest;
use App\Http\Requests\Application\Ticket\TicketFormRequest;
use App\Http\Requests\Application\Ticket\TicketUpdateFormRequest;
use App\Models\Ticket;
use App\Repositories\Client\ClientInterface;
use App\Repositories\Ticket\TicketInterface;
use Illuminate\Http\Request;
use Spatie\MediaLibrary\Support\MediaStream;

class TicketController extends Controller
{

    public function index()
    {
        $tickets = app(TicketInterface::class)->getTickets();

        return view('theme.pages.Ticket.index', compact('tickets'));
    }

    public function create()
    {
        $clients = app(ClientInterface::class)->select(['id', 'nom', 'prenom', 'ste_name'])->get();

        return view('theme.pages.Ticket.__create.index', compact('clients'));
    }

    public function store(TicketFormRequest $request)
    {

        $data = $request->withoutHoneypot();

        $data['slug'] = $request->product;

        $ticket = (new SaveModel(new Ticket(), $data))->ignoreFields(['client'])->execute();

        $ticket->admin()->associate($request->user('admin')->id)->save();
        
        $request->whenFilled('client', function ($input) use ($ticket) {
            $ticket->client()->associate($input)->save();
        });


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

    public function attachements(TicketAttachementsFormRequest $request, $id)
    {

        $ticket = Ticket::find($id);

        foreach ($request->file('photos') as $image) {
            $ticket->addMedia($image)->toMediaCollection('tickets-images');
        }

        return redirect()->back()->with('success', "Les fichiers sont attaché avec success");
    }

    public function downloadFiles(Request $request)
    {
        $request->validate(['ticket' => 'required|uuid']);
        $ticket = Ticket::whereExternalId($request->ticket)->firstOrFail();
        $downloads = $ticket->getMedia('tickets-images');

        // Download the files associated with the media in a streamed way.
        // No prob if your files are very large.
        return MediaStream::create('tickets-images.zip')->addMedia($downloads);
    }


    public function delete(Request $request)
    {
        $request->validate(['ticket' => 'required|integer']);

        $id = $request->ticket;

        $ticket = Ticket::findOrFail($id);

        $ticket->delete();

        return redirect()->back()->with('success', "La supprission a été effectué  avec success");
    }
}
