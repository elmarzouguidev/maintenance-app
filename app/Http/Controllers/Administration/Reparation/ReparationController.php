<?php

namespace App\Http\Controllers\Administration\Reparation;

use App\Constants\Status;
use App\Http\Controllers\Controller;
use App\Http\Requests\Application\Reparation\ReparationFormRequest;
use App\Models\Ticket;
use Illuminate\Contracts\View\View;

class ReparationController extends Controller
{
    public function index(): View
    {
        $user = auth()->user();

        $tickets = Ticket::query()
            ->whereIn('status', [
                Status::A_REPARER,
                Status::EN_COURS_DE_REPARATION,
                Status::PRET_A_ETRE_LIVRE,
            ]);

        if (! $user->can('reparations.browse')) {
            if ($user->can('reparations.manage_assigned')) {
                $tickets->whereNotNull('user_id');
            } else {
                abort_unless($user->can('reparations.assigned.browse'), 403);
                $tickets->where('user_id', $user->id);
            }
        }

        $tickets = $tickets->get()->groupBy(function (Ticket $ticket) {
            return match ($ticket->status) {
                Status::A_REPARER => 'a-preparer',
                Status::EN_COURS_DE_REPARATION => 'encours-de-reparation',
                Status::PRET_A_ETRE_LIVRE => 'pret-a-livre',
            };
        });

        return view('theme.pages.Reparation.index', compact('tickets'));
    }

    public function single(Ticket $ticket)
    {
        $this->authorize('canRepear', $ticket);

        if (auth()->user()->can('reparations.assigned.browse')
            && $ticket->technicien()->is(auth()->user())
            && $ticket->reparationReports()->count() > 0
            && $ticket->reparationReports->close_report) {
            return redirect()->route('admin:tickets.list.old');
        }

        $ticket->with(['diagnoseReports:id,content', 'reparationReports:id,content', 'technicien:id,nom,prenom']);

        $canStartAssignedRepair = auth()->user()->can('reparations.edit')
            && $ticket->technicien()->is(auth()->user());
        $canStartManagedRepair = auth()->user()->can('reparations.manage_assigned')
            && $ticket->user_id !== null;

        if (($canStartAssignedRepair || $canStartManagedRepair)
            && $ticket->status !== Status::EN_COURS_DE_REPARATION) {
            $ticket->update(['status' => Status::EN_COURS_DE_REPARATION]);

            $ticket->statuses()->attach(
                Status::EN_COURS_DE_REPARATION,
                [
                    'user_id' => auth()->id(),
                    'start_at' => now(),
                    'description' => __('status.history.'.Status::EN_COURS_DE_REPARATION, ['user' => auth()->user()->full_name]),
                ]
            );
        }

        return view('theme.pages.Reparation.__single.index', compact('ticket'));
    }

    public function store(ReparationFormRequest $request, Ticket $ticket)
    {
        if ($request->input('reparation_done') === 'reparation_done') {
            abort_unless(auth()->user()->can('reparations.complete'), 403);
        }

        $this->authorize('canRepearStore', $ticket);

        if ($ticket->reparationReports()->count() <= 0) {
            $ticket->statuses()->attach(
                Status::EN_COURS_DE_REPARATION,
                [
                    'user_id' => auth()->id(),
                    'start_at' => now(),
                    'description' => __('status.history.rediger_le_rapport_de_rep', ['user' => auth()->user()->full_name]),
                ]
            );
        }

        $ticket->reparationReports()->updateOrCreate(
            [
                'ticket_id' => $ticket->id,
                'type' => 'reparation',
            ],
            [
                'content' => $request->content,
                'type' => 'reparation',
                'user_id' => auth()->id(),
            ]
        );

        $message = 'Le rapport a éte enregistrer  avec success';

        if ($request->has('reparation_done') && $request->filled('reparation_done') && $request->reparation_done == 'reparation_done') {
            $ticket->update(['status' => Status::PRET_A_ETRE_LIVRE]);

            $ticket->update([
                'can_invoiced' => true,
                'finished_at' => now(),
            ]);

            $ticket->statuses()->attach(
                Status::PRET_A_ETRE_LIVRE,
                [
                    'user_id' => auth()->id(),
                    'start_at' => now(),
                    'description' => __('status.history.'.Status::PRET_A_ETRE_LIVRE, ['user' => auth()->user()->full_name]),
                ]
            );

            $message = 'La réparation a éte terminé  avec success';

            $ticket->reparationReports()->update(['close_report' => true]);

            return redirect(route('admin:diagnostic.index'))->with('success', $message);
        }

        return redirect()->back()->with('success', $message);
    }

    public function reapearComplet(ReparationFormRequest $request, Ticket $ticket)
    {
        abort_unless(auth()->user()->can('reparations.complete'), 403);

        $request->merge(['reparation_done' => 'reparation_done']);

        return $this->store($request, $ticket);
    }
}
