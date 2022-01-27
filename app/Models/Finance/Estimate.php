<?php

namespace App\Models\Finance;

use App\Models\Client;
use App\Models\Ticket;
use App\Traits\GetModelByUuid;
use App\Traits\UuidGenerator;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Carbon;

class Estimate extends Model
{
    use HasFactory;
    use UuidGenerator;
    use GetModelByUuid;
    //use SoftDeletes;

    public function client()
    {
        return $this->belongsTo(Client::class);
    }

    public function ticket()
    {
        return $this->belongsTo(Ticket::class);
    }

    public function company()
    {
        return $this->belongsTo(Company::class);
    }

    public function articles()
    {
        return $this->hasMany(EstimateArticle::class);
    }

    public function getFormatedPriceHtAttribute()
    {
        return number_format($this->price_ht, 2);
    }

    public function getFormatedPriceTotalAttribute()
    {
        return number_format($this->price_total, 2);
    }

    public function getFormatedTotalTvaAttribute()
    {
        return number_format($this->total_tva, 2);
    }

    public function getUrlAttribute()
    {
        return route('commercial:estimates.single', $this->uuid);
    }

    public function getEditUrlAttribute()
    {
        return route('commercial:estimates.edit', $this->uuid);
    }
    public function getUpdateUrlAttribute()
    {
        return route('commercial:estimates.update', $this->uuid);
    }

    public function getCreateInvoiceUrlAttribute()
    {
        return route('commercial:estimates.create.invoice', $this->uuid);
    }

    public function getIsPublishedAttribute(): bool
    {
        return $this->published_at->lessThanOrEqualTo(Carbon::now());
    }

    public static function boot()
    {
        //dd('First 1');
        parent::boot();

        $prefixer = config('app-config.estimates.prefix');

        static::creating(function ($model) use ($prefixer) {

            $number = self::count() > 0;
            $increment =  $number ? self::max('estimate_code') + 1 : config('app-config.estimates.start_from') + 1;
            //dd($increment);

            //$model->invoice_code = $prefixer . str_pad($number, 5, 0, STR_PAD_LEFT);
            $model->estimate_code = $increment;
        });
    }
}