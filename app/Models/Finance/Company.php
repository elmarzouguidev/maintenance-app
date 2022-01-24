<?php

namespace App\Models\Finance;

use App\Models\Client;
use App\Traits\GetModelByUuid;
use App\Traits\UuidGenerator;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Company extends Model
{

    use HasFactory;
    use UuidGenerator;
    use GetModelByUuid;

    public function clients()
    {
        return $this->hasMany(Client::class);
    }

    public function invoices()
    {
        return $this->hasMany(Invoice::class);
    }

    public function getEditUrlAttribute()
    {
        return route('commercial:companies.edit', $this->uuid);
    }
}
