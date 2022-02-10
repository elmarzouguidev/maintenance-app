<?php

namespace App\Http\Controllers\Administration\Ticket;

use App\Models\Ticket;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\App;
use App\Http\Controllers\Controller;
use App\Repositories\Client\ClientInterface;
use App\Repositories\Ticket\TicketInterface;
use Spatie\MediaLibrary\Support\MediaStream;
use App\Http\Requests\Application\Ticket\TicketFormRequest;
use App\Http\Requests\Application\Ticket\TicketUpdateFormRequest;
use App\Http\Requests\Application\Ticket\TicketAttachementsFormRequest;
use Spatie\MediaLibrary\MediaCollections\Models\Media;
use Spatie\QueryBuilder\AllowedFilter;
use Spatie\QueryBuilder\QueryBuilder;

class TicketController extends Controller
{

    public function index(Request $request)
    {
        if (request()->has('appFilter') && request()->filled('appFilter')) {
            $tickets = QueryBuilder::for(app(TicketInterface::class)->__instance())

                ->allowedFilters([
                    'etat', 'status', 'unique_code',
                    AllowedFilter::exact('etat')
                    //AllowedFilter::exact('GetCategory', 'filters_category'),
                    //AllowedFilter::scope('GetCategory', 'filters_category'),
                    // AllowedFilter::scope('GetColor', 'filters_color'),

                ])
                ->with(['client:id,entreprise', 'technicien:id,nom,prenom'])
                ->withCount('technicien')
                ->Without('media')
                ->get()
                ->appends(request()->query());
            //->get();
        } else {
            $tickets = app(TicketInterface::class)
                ->With(['client:id,entreprise', 'technicien:id,nom,prenom'])
                ->withCount('technicien')
                ->Without('media')
                ->latest('created_at')
                ->get();
        }

        return view('theme.pages.Ticket.index', compact('tickets'));
    }

    public function create()
    {
        $clients = app(ClientInterface::class)->select(['id', 'entreprise'])->get();

        return view('theme.pages.Ticket.__create.index', compact('clients'));
    }

    public function store(TicketFormRequest $request)
    {

        // dd($request->all());
        $user = \ticketApp::activeGuard();

        $ticket = new Ticket();
        $ticket->article = $request->article;
        $ticket->description = $request->description;
        $ticket->slug = Str::slug($request->article);


        $ticket->{$user}()->associate($request->user()->id)->save();

        if ($request->hasFile('photo')) {

            $ticket->addMediaFromRequest('photo')
                ->toMediaCollection('tickets-images');
        }

        $ticket->save();

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

        $ticket = Ticket::find($id);
        $ticket->product = $request->product;
        $ticket->description = $request->description;
        $ticket->save();
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
        $fileName = "ticket-" . $ticket->slug . "-files.zip";
        return MediaStream::create($fileName)->addMedia($downloads);
    }


    public function delete(Request $request)
    {
        $request->validate(['ticket' => 'required|integer']);

        $id = $request->ticket;

        $ticket = Ticket::findOrFail($id);

        //$files = $ticket->getMedia('tickets-images');
        //dd($files);

        //$ticket->delete();

        return redirect()->back()->with('success', "La supprission a été effectué  avec success");
    }


    public function media(Ticket $uuid)
    {
        $ticket = $uuid->loadCount('media');

        return view('theme.pages.Ticket.__media.index', compact('ticket'));
    }

    public function deleteMedia(Request $request, $uuid)
    {
        $request->validate(['mediaId' => 'required|integer']);

        $ticket = Ticket::whereUuid($uuid)->firstOrFail();

        if ($ticket) {

            $ticket->deleteMedia($request->mediaId);

            return redirect()->back()->with('success', "La supprission a été effectué  avec success");
        }
        return redirect()->back()->with('success', "La supprission Probleùm");

        //$toDeleteIds = $request->mediaId;
        /*if(count($toDeleteIds)) {
                $mediaTodelete = Media::whereIn('id', $toDeleteIds)->delete();
        }*/
    }

    public function historical(Ticket $uuid)
    {
        $ticket = $uuid->loadCount('statuses');

        return view('theme.pages.Ticket.__historical.index', compact('ticket'));
    }
}
