<?php

namespace App\Models\Finance;

use App\Traits\UuidGenerator;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Provider extends Model
{
    use HasFactory;
    use UuidGenerator;


    public static function boot()
    {

        parent::boot();

        $prefixer = config('app-config.providers.prefix');

        static::creating(function ($model) use ($prefixer) {

            $number = (self::max('id') + 1);

            $model->provider_ref = $prefixer . str_pad($number, 5, 0, STR_PAD_LEFT);
        });
    }
}
