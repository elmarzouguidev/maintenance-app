<?php

namespace App\Models\Finance;

use App\Models\Client;
use App\Models\Ticket;
use App\Traits\GetModelByUuid;
use App\Traits\UuidGenerator;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Carbon;
use Spatie\ModelStatus\HasStatuses;

class Invoice extends Model
{

    use HasFactory;
    use UuidGenerator;
    use GetModelByUuid;
    //use HasStatuses;
    //use SoftDeletes;

    protected $fillable = ['status'];
    
    public function estimate()
    {
        return $this->belongsTo(Estimate::class);
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
        return $this->hasOne(Bill::class);
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

    public function getPdfUrlAttribute()
    {
        return route('commercial:invoices.pdf.build', $this->uuid);
    }

    public function getAddBillAttribute()
    {
        return route('commercial:bills.addBill', $this->uuid);
    }

    public function getIsPublishedAttribute(): bool
    {
        return $this->published_at->lessThanOrEqualTo(Carbon::now());
    }

    /*******Filters */
    public function scopeFiltersCompanies(Builder $query, $company)
    {
        $company = Company::whereUuid($company)->firstOrFail()->id;

        return $query->where('company_id', $company);
    }

    public function scopeFiltersStatus(Builder $query, $status)
    {
        return $query->whereStatus($status);
    }

    public function scopeFiltersColor(Builder $query, $color)
    {
        $colorId = Company::whereSlug($color)->firstOrFail()->id;

        $query->whereHas('colors', function ($q) use ($colorId) {
            $q->where('color_id', $colorId);
        });
    }

    public function scopeFromTo(Builder $query, $dateFrom, $dateTo): Builder
    {
        return $query->whereBetween(
            'created_at',
            [
                Carbon::createFromFormat('m/d/Y', $dateFrom)->format('Y-m-d'),
                Carbon::createFromFormat('m/d/Y', $dateTo)->format('Y-m-d')
            ]
        );
    }

    public static function boot()
    {

        parent::boot();

        static::creating(function ($model) {

            $number = ($model->company->invoice_start_number) + ($model->company->invoices->count() + 1);

            $invoiceCode = str_pad($number, 5, 0, STR_PAD_LEFT);

            $model->invoice_code = $invoiceCode;

            $model->full_number = $model->company->prefix_invoice . $invoiceCode;
        });
    }
}
