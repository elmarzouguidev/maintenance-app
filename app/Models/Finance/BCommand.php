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

    public function articles()
    {
        return $this->morphMany(Article::class, 'articleable');
    }

    public static function boot()
    {

        parent::boot();

        static::creating(function ($model) {

            $number = ($model->company->bcommand_start_number) + ($model->company->bCommands->count() + 1);

            $code = str_pad($number, 5, 0, STR_PAD_LEFT);

            $model->b_code = $code;

            $model->full_number = $model->company->prefix_bcommand . $code;
        });
    }
}
