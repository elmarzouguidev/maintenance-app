<?php

namespace App\Traits;

use Illuminate\Support\Str;

trait UuidGenerator
{

    public static function bootUuidGenerator()
    {

        static::creating(function ($model) {

            $model->external_id = Str::uuid();

        });
    }
}
