<?php

namespace App\Models\Finance;

use App\Models\Client;
use App\Models\Ticket;
use App\Traits\GetModelByUuid;
use App\Traits\UuidGenerator;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class InvoiceAvoir extends Model
{
    use HasFactory;
    use SoftDeletes;
    use GetModelByUuid;
    use UuidGenerator;


    public function invoice()
    {
        return $this->belongsTo(Invoice::class);
    }

    public function client()
    {
        return $this->belongsTo(Client::class);
    }

    public function ticket()
    {
        return $this->belongsTo(Ticket::class)->withDefault();
    }

    public function company()
    {
        return $this->belongsTo(Company::class);
    }

    public function articles()
    {
        return $this->morphMany(Article::class, 'articleable');
    }

    public function bill()
    {
        return $this->morphOne(Bill::class, 'billable')->withDefault();
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
        return number_format($this->price_tva, 2);
    }

    public static function boot()
    {

        parent::boot();

        static::creating(function ($model) {

            $number = ($model->company->invoice_avoir_start_number) + ($model->company->invoicesAvoir->count() + 1);

            $invoiceCode = str_pad($number, 5, 0, STR_PAD_LEFT);

            $model->invoice_code = $invoiceCode;

            $model->full_number = $model->company->prefix_invoice_avoir . $invoiceCode;
        });
    }
}
