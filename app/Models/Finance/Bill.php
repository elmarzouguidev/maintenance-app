<?php

namespace App\Models\Finance;

use App\Models\Client;
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

    public function company()
    {
        return $this->belongsTo(Company::class);
    }

    public function client()
    {
        return $this->belongsTo(Client::class);
    }


    public function getFormatedPriceHtAttribute()
    {
        return number_format($this->price_ht, 2);
    }

    public function getFormatedPriceTotalAttribute()
    {
        return number_format($this->price_total, 2);
    }

    public function getFormatedPriceTvaAttribute()
    {
        return number_format($this->price_tva, 2);
    }
    public function getEditUrlAttribute()
    {
        return route('commercial:bills.edit', $this->uuid);
    }

    public static function boot()
    {

        parent::boot();

        static::creating(function ($model) {

            $model->bill_code = $model->invoice->invoice_code;

            $model->full_number = 'REGL-' . $model->invoice->full_number;
        });
    }
}
