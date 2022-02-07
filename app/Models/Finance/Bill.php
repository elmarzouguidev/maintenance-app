<?php

namespace App\Models\Finance;

use App\Traits\GetModelByUuid;
use App\Traits\UuidGenerator;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Bill extends Model
{
    
    use HasFactory;
    use UuidGenerator;
    use GetModelByUuid;


    public function invoice()
    {
        return $this->belongsTo(Invoice::class);
    }
}
