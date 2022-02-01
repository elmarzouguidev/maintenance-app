<?php

namespace App\Models\Finance;

use App\Traits\GetModelByUuid;
use App\Traits\UuidGenerator;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class BCommand extends Model
{
    use HasFactory;
    use UuidGenerator;
    use GetModelByUuid;


    public function provider()
    {
        return $this->belongsTo(Provider::class);
    }

    public function company()
    {
        return $this->belongsTo(Company::class);
    }
}
