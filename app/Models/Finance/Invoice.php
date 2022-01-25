<?php

namespace App\Models\Finance;

use App\Models\Client;
use App\Models\Ticket;
use App\Traits\GetModelByUuid;
use App\Traits\UuidGenerator;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Carbon;

class Invoice extends Model
{

    use HasFactory;
    use UuidGenerator;
    use GetModelByUuid;

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
        return $this->hasMany(Article::class);
    }

    public function getUrlAttribute()
    {
        return route('commercial:invoices.single', $this->uuid);
    }

    public function getEditUrlAttribute()
    {
        return route('commercial:invoices.edit', $this->uuid);
    }
    public function getUpdateUrlAttribute()
    {
        return route('commercial:invoices.update', $this->uuid);
    }

    public function getIsPublishedAttribute(): bool
    {
        return $this->published_at->lessThanOrEqualTo(Carbon::now());
    }

    public static function boot()
    {
        //dd('First 1');
        parent::boot();

        $prefixer = config('app-config.invoices.prefix');

        static::creating(function ($model) use ($prefixer) {

            $number = self::count() > 0;
            $increment =  $number ? self::max('invoice_code') + 1 : config('app-config.invoices.start_from') + 1;
            //dd($increment);

            //$model->invoice_code = $prefixer . str_pad($number, 5, 0, STR_PAD_LEFT);
            $model->invoice_code = $increment;
        });
    }
}
