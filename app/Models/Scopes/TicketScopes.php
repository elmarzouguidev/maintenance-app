<?php

namespace App\Models\Scopes;

use \App\Constants\Status as TicketStatus;
use App\Constants\Etat;

trait TicketScopes
{


    public function scopeFiltersStatus($query, $status)
    {
        return $query
            ->whereIn('etat', [Etat::NON_REPARABLE, Etat::REPARABLE])
            ->where('status',  $status);
    }
}
