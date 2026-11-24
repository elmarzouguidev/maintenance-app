<?php

namespace App\Models\Utilities;

use App\Models\Ticket;
use App\Traits\GetModelByUuid;
use App\Traits\UuidGenerator;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Warranty extends Model
{
    use GetModelByUuid;
    use HasFactory;
    use UuidGenerator;

    protected $fillable = [
        'ticket_id',
        'start_at',
        'end_at',
        'description',
        'active',
        'notify_admin',
        'notify_client',
    ];

    protected $casts = [

        'start_at' => 'date:d-m-Y',
        'end_at' => 'date:d-m-Y',
        'active' => 'boolean',
        'notify_admin' => 'boolean',
        'notify_client' => 'boolean',
    ];

    public function ticket()
    {
        return $this->belongsTo(Ticket::class);
    }

    /**
     * The warranties list and its exports: the ticket loaded, filtered by a part of the ticket
     * code (every warranty when $search is empty), newest first.
     */
    public function scopeListing(Builder $query, string $search = ''): void
    {
        $query->with('ticket:id,code,status')
            ->when($search !== '', fn (Builder $query) => $query->whereHas('ticket', fn (Builder $ticket) => $ticket->where('code', 'like', '%'.$search.'%')))
            ->latest('start_at')
            ->latest('id');
    }

    /***** */
}
