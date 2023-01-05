<?php

namespace App\Http\Controllers\Administration\Ticket;

use App\Constants\Etat;
use App\Constants\Status;
use App\Http\Controllers\Controller;
use App\Http\Requests\Application\Ticket\TicketAttachementsFormRequest;
use App\Http\Requests\Application\Ticket\TicketFormRequest;
use App\Http\Requests\Application\Ticket\TicketUpdateFormRequest;
use App\Models\Finance\Estimate;
use App\Models\Ticket;
use App\Repositories\Client\ClientInterface;
use App\Repositories\Ticket\TicketInterface;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use Spatie\MediaLibrary\Support\MediaStream;
use Spatie\QueryBuilder\AllowedFilter;
use Spatie\QueryBuilder\QueryBuilder;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use TicketSettings;

class TicketController extends Controller
{
    public function index(Request $request)
    {
        if (request()->has('appFilter') && request()->filled('appFilter')) {
            $tickets = QueryBuilder::for(app(TicketInterface::class)->__instance())
                ->allowedFilters([
                    AllowedFilter::scope('GetTicketDate', 'filters_date_ticket'),
                    AllowedFilter::scope('GetStatus', 'filters_status'),
                    AllowedFilter::scope('GetClient', 'filters_client'),
                    AllowedFilter::scope('GetEtat', 'filters_etat'),
                    AllowedFilter::scope('GetRetour', 'filters_retour'),

                ])
                ->with(['client:id,uuid,entreprise', 'technicien:id,nom,prenom'])
                ->withCount('technicien')
                ->latest()->get();
            //->paginate(20)
            //->appends(request()->query());
        } else {
            $tickets = app(TicketInterface::class)->__instance()
                ->with(['client:id,uuid,entreprise', 'technicien:id,nom,prenom'])
                ->withCount('technicien')
                ->latest('created_at')
                ->whereEtat(Etat::NON_DIAGNOSTIQUER)
                ->whereStatus(Status::NON_TRAITE)
                ->latest()->get();
            //->paginate(20)
            //->appends(request()->query());
        }

        $clients = app(ClientInterface::class)->select(['id', 'entreprise', 'uuid'])->get();
        $title = 'Nouveau Tickets';

        return view('theme.pages.Ticket.index', compact('tickets', 'clients', 'title'));
    }

    public function old()
    {
        /*$tickets = Ticket::oldTickets()
            ->paginate(20)
            ->appends(request()->query());*/
        $tickets = Ticket::oldTickets()->get();

        $clients = app(ClientInterface::class)->select(['id', 'entreprise', 'uuid'])->get();
        $title = 'Tous les Tickets';

        return view('theme.pages.Ticket.index', compact('tickets', 'clients', 'title'));
    }

    public function oldTow()
    {
        if (request()->has('appFilter') && request()->filled('appFilter')) {
            $tickets = QueryBuilder::for(app(TicketInterface::class)->__instance())
                ->allowedFilters([
                    AllowedFilter::scope('GetTicketDate', 'filters_date_ticket'),
                    AllowedFilter::scope('GetStatus', 'filters_status'),
                    AllowedFilter::scope('GetClient', 'filters_client'),
                    AllowedFilter::scope('GetEtat', 'filters_etat'),
                    AllowedFilter::scope('GetRetour', 'filters_retour'),

                ])
                ->with(['client:id,uuid,entreprise', 'technicien:id,nom,prenom'])
                ->withCount('technicien')
                ->oldest()->get();
            //->paginate(20)
            //->appends(request()->query());
        } else {
            $tickets = app(TicketInterface::class)->__instance()
                ->with(['client:id,uuid,entreprise', 'technicien:id,nom,prenom'])
                ->withCount('technicien')
                ->oldest()->get();
            //->paginate(20)
            //->appends(request()->query());
        }

        $clients = app(ClientInterface::class)->select(['id', 'entreprise', 'uuid'])->get();
        $title = 'Tous les Tickets';

        return view('theme.pages.Ticket.index', compact('tickets', 'clients', 'title'));
    }

    public function create()
    {
        $this->authorize('create', Ticket::class);

        $clients = app(ClientInterface::class)->select(['id', 'entreprise'])->get();

        $tickets = app(TicketInterface::class)->__instance()
            ->select(['id', 'uuid', 'code', 'retour_number', 'article', 'client_id'])
            ->where('is_retour', false)
            ->get();
        $title = 'Tickets';

        return view('theme.pages.Ticket.__create.index', compact('clients', 'tickets', 'title'));
    }

    public function store(TicketFormRequest $request)
    {
        //dd($request->all());
        $this->authorize('create', Ticket::class);

        DB::transaction(function () use ($request) {

            $ticket = new Ticket();

            $ticket->article = $request->article;
            $ticket->description = $request->description;

            $ticket->client()->associate($request->client);

            if ($request->hasFile('photo')) {
                $ticket->addMediaFromRequest('photo')->toMediaCollection('tickets-images');
            }

            $ticket->save();

            if ($request->has(['is_retour', 'ticket_retoure']) && $request->filled(['is_retour', 'ticket_retoure'])) {

                $parentTicket = Ticket::find($request->ticket_retoure);

                if ($parentTicket->estimates()->exists()) {

                    $estimate = Estimate::find($parentTicket->estimates[0]?->id);

                    $estimate->tickets()->syncWithoutDetaching([$ticket->id]);
                }
                if ($parentTicket->statuses()->exists()) {
                    foreach ($parentTicket->statuses as $status) {

                        $ticket->statuses()->attach(
                            $status->id,
                            [
                                'user_id' => $status->pivot->user_id,
                                'start_at' => $status->pivot->start_at,
                                'description' => $status->pivot->description,
                            ]
                        );
                    }
                }
                $ticket->update(['status' => $parentTicket->status, 'etat' => $parentTicket->etat]);
            }

            if (!$request->has(['is_retour', 'ticket_retoure']) && !$request->filled(['is_retour', 'ticket_retoure'])) {

                $ticket->statuses()->attach(
                    Status::NON_TRAITE,
                    [
                        'user_id' => auth()->id(),
                        'start_at' => now(),
                        'description' => __('status.history.' . Status::NON_TRAITE, ['user' => auth()->user()->full_name]),
                    ]
                );
            }
        });
        return redirect(route('admin:tickets.list'))->with('success', "L'ajoute a éte effectuer avec success");
    }

    public function show(Ticket $ticket)
    {
        $this->authorize('view', $ticket);

        $ticket->load(['media' => function ($q) {
            $q->take(5);
        }, 'technicien:id,nom,prenom', 'client:id,entreprise', 'statuses']);
        $ticket->load('delivery', 'invoice', 'estimate')
            ->loadCount('delivery', 'invoice', 'estimate');

        //dd($ticket->statuses()->first()->name);
        return view('theme.pages.Ticket.__single_v2.index', compact('ticket'));
    }

    public function edit(Ticket $ticket)
    {
        $this->authorize('update', $ticket);

        $ticket->load('statuses');
        $title = 'Tickets';

        return view('theme.pages.Ticket.__edit.index', compact('ticket', 'title'));
    }

    public function update(TicketUpdateFormRequest $request, Ticket $ticket)
    {
        $this->authorize('update', $ticket);

        $createdAt = Carbon::createFromFormat('Y-m-d H:i:s', $request->date('created_at'))->format('Y-m-d H:i:s');

        $ticket->update($request->validated() + ['created_at' => $createdAt]);

        return redirect($ticket->edit)->with('success', 'La modification a éte effectuer avec success');
    }

    public function attachements(TicketAttachementsFormRequest $request, Ticket $ticket)
    {
        //$this->authorize('update', $ticket);

        foreach ($request->file('photos') as $image) {
            $ticket->addMedia($image)->toMediaCollection('tickets-images');
        }

        return redirect()->back()->with('success', 'Les fichiers sont attaché avec success');
    }

    public function downloadFiles(Request $request)
    {
        $request->validate(['ticket' => 'required|uuid']);

        $ticket = Ticket::whereUuid($request->ticket)->firstOrFail();

        $downloads = $ticket->getMedia('tickets-images');

        // Download the files associated with the media in a streamed way.
        // No prob if your files are very large.
        $fileName = 'ticket-' . Str::slug($ticket->article) . '-files.zip';

        return MediaStream::create($fileName)->addMedia($downloads);
    }

    public function delete(Request $request)
    {
        $request->validate(['ticket' => 'required|uuid']);

        $ticket = Ticket::whereUuid($request->ticket)->firstOrFail();

        $this->authorize('delete', $ticket);

        if ($ticket) {

            $ticket->statuses()->detach();

            $ticket->estimates()->detach();

            $ticket->invoices()->detach();

            $ticket->warranty()->delete();

            $ticket->delivery()->delete();

            $ticket->reports()->delete();

            /*if (!$ticket->is_retour && $ticket->retour_number > 0) {
                $ticket->decrement('retour_number');
            }*/

            $ticket->delete();

            return redirect(route('admin:tickets.list'))->with('success', 'La supprission a été effectué  avec success');
        }

        return redirect()->back()->with('error', 'Error !!!');
    }

    public function media(Ticket $ticket)
    {
        $ticket->loadCount('media');

        $title = 'Tickets';

        return view('theme.pages.Ticket.__media.index', compact('ticket', 'title'));
    }

    public function deleteMedia(Request $request, Ticket $ticket)
    {
        $request->validate(['mediaId' => 'required|integer']);

        if ($ticket) {
            $ticket->deleteMedia($request->mediaId);

            return redirect()->back()->with('success', 'La supprission a été effectué  avec success');
        }

        return redirect()->back()->with('success', 'La supprission Probleùm');

        //$toDeleteIds = $request->mediaId;
        /*if(count($toDeleteIds)) {
                $mediaTodelete = Media::whereIn('id', $toDeleteIds)->delete();
        }*/
    }

    public function historical(Ticket $ticket)
    {
        return view('theme.pages.Ticket.__historical.index', compact('ticket'));
    }

    public function ticketSettings(TicketSettings $setting, Request $request)
    {
        // dd(\ticketApp::ticketSetting()->start_from);
        $setting->start_from = (int) $request->input('start_from');
        $setting->prefix = '#TCK';

        $setting->save();

        return redirect()->back()->with('success', 'La configuration a été effectué  avec success');
    }
}
