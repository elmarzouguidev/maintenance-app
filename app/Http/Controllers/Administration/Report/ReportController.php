<?php

namespace App\Http\Controllers\Administration\Report;

use App\Domain\Support\SaveModel\SaveModel;
use App\Http\Controllers\Controller;
use App\Http\Requests\Application\Report\ReportFormRequest;
use App\Models\Ticket;
use App\Models\Utilities\Report;
use Illuminate\Http\Request;

class ReportController extends Controller
{


    public function index()
    {
    }

    public function store(ReportFormRequest $request, $slug)
    {
        //dd($request->send);

        $data = $request->withoutHoneypot();

        $ticket = Ticket::whereExternalId($slug)->firstOrFail();

        $report = $ticket->reports()->updateOrCreate(
            [
                'ticket' => $ticket->product,
                'technicien_id' => auth('technicien')->user()->id,
                'type' => $data['type'],
                'etat' => $data['etat']
            ],
            [
                'ticket' => $ticket->product,
                'content' => $data['content'],
                'type' => $data['type'],
                'etat' => $data['etat'],
                'technicien_id' => auth('technicien')->user()->id
            ]
        );

        if ($report) {
            $ticket->technicien()->associate(auth('technicien')->user()->id)->save();
        }
        $request->whenFilled('send', function ($input) use ($report) {
            $report->update(['status' => 'envoyer']);
        });

        return redirect()->back()->with('success', "Le rapport a éte crée  avec success");
    }
}
