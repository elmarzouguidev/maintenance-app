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

    protected $fillable = ['is_invoiced'];

    protected $with = ['invoice'];
    
    public function invoice()
    {
        return $this->hasOne(Invoice::class);
    }

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

    public function getInvoiceUrlAttribute()
    {
        return route('commercial:invoices.single', $this->invoice->uuid);
    }

    public function getIsPublishedAttribute(): bool
    {
        return $this->published_at->lessThanOrEqualTo(Carbon::now());
    }

    public static function boot()
    {

        parent::boot();

        static::creating(function ($model) {

            $number = ($model->company->estimate_start_number) + ($model->company->estimates->count() + 1);

            $estimateCode = str_pad($number, 5, 0, STR_PAD_LEFT);

            $model->estimate_code = $estimateCode;

            $model->full_number = $model->company->prefix_estimate . $estimateCode;
        });
    }
}
