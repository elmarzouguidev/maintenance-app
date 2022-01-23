<?php

namespace App\Models\Finance;

use App\Traits\UuidGenerator;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Company extends Model
{
    use HasFactory;
    use UuidGenerator;


    public function getEditUrlAttribute()
    {
        return route('commercial:companies.edit', $this->uuid);
    }


    public function getRouteKeyName()
    {
        return 'uuid';
    }
}
