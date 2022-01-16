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

        $data = $request->withoutHoneypot();

        $ticket = Ticket::whereExternalId($slug)->firstOrFail();

        $ticket->reports()->create([
            'ticket' => $ticket->product,
            'content' => $data['content'],
            'type' => $data['type'],
            'etat' => $data['etat'],
            'technicien_id' => auth('technicien')->user()->id
        ]);

        return redirect()->back()->with('success', "Le rapport a éte crée  avec success");
    }
}
