<?php

namespace App\Traits;

use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Schema;

trait UuidGenerator
{

    public static function bootUuidGenerator()
    {

        static::creating(function ($model) {
            if (Schema::hasColumn($model->getTable(), 'external_id')) {
                $model->external_id = Str::uuid();
            } else {
                // dd('Im here inside call migrate');
               // Artisan::call('migrate', ['--path' => '/database/migrations/2021_12_17_001853_add_external_id_to_all_table.php']);
               // $model->external_id = Str::uuid();
            }
        });
    }
}
