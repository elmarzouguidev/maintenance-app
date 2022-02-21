<?php

namespace App\Models\Utilities;

use App\Models\Ticket;
use App\Traits\GetModelByUuid;
use App\Traits\UuidGenerator;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Delivery extends Model
{
    use HasFactory;
    use GetModelByUuid;
    use UuidGenerator;

    public function ticket()
    {
        return $this->belongsTo(Ticket::class);
    }
}
