<?php

namespace App\Traits;

use Illuminate\Support\Str;
use Illuminate\Support\Facades\Schema;

trait UuidGenerator
{

    public static function bootUuidGenerator()
    {

        static::creating(function ($model) {
            if (Schema::hasColumn($model->getTable(), 'external_id')) {
                $model->external_id = Str::uuid();
            }
        });
    }
}
