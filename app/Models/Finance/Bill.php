<?php

namespace App\Models\Finance;

use App\Models\Client;
use App\Scopes\DefaultCompanyTrait;
use App\Traits\GetModelByUuid;
use App\Traits\UuidGenerator;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Carbon;

class Bill extends Model
{
    use HasFactory;
    use UuidGenerator;
    use GetModelByUuid;
    use DefaultCompanyTrait;

    protected $fillable = [
        'bill_date',
        'bill_mode',
        'reference',
        'notes',
        'price_ht',
        'price_total',
        'price_tva',
        'billable_id',
        'billable_type',
        'company_id',
    ];

    protected $casts = [
        'bill_date' => 'date:Y-m-d',
    ];

    public function billable()
    {
        return $this->morphTo();
    }

    public function invoice()
    {
        return $this->belongsTo(Invoice::class, 'billable_id');
    }

    public function company()
    {
        return $this->belongsTo(Company::class);
    }

    /* public function client()
    {
        return $this->belongsTo(Client::class);
    }*/

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

    public function scopeDashboard(Builder $query)
    {
        return $query->where('billable_type', 'App\Models\Finance\Invoice');
    }

    public function scopeFiltersCompanies(Builder $query, $company)
    {
        //$company = Company::whereUuid($company)->firstOrFail()->id;

        return $query->where('company_id', $company);
    }

    public function scopeFiltersDate(Builder $query, $from, $to): Builder
    {
        $startDate = Carbon::createFromFormat('Y-m-d', $from)->startOfDay();
        $endDate = Carbon::createFromFormat('Y-m-d', $to)->endOfDay();

        return $query->whereBetween('bill_date', [$startDate, $endDate]);
    }

    public function scopeFiltersPeriods(Builder $query, $period): Builder
    {
        //dd($period,"dd");
        if ($period == 1) {
            return $query->whereBetween(
                'bill_date',
                [
                    now()->startOfYear()->startOfQuarter(),
                    now()->startOfYear()->endOfQuarter(),
                ]
            );
        }
        if ($period == 2) {
            return $query->whereBetween(
                'bill_date',
                [
                    now()->startOfYear()->addMonths(3)->startOfQuarter(),
                    now()->startOfYear()->addMonths(3)->endOfQuarter(),
                ]
            );
        }
        if ($period == 3) {
            return $query->whereBetween(
                'bill_date',
                [
                    now()->startOfYear()->addMonths(6)->startOfQuarter(),
                    now()->startOfYear()->addMonths(6)->endOfQuarter(),
                ]
            );
        }
        if ($period == 4) {
            return $query->whereBetween(
                'bill_date',
                [
                    now()->startOfYear()->addMonths(9)->startOfQuarter(),
                    now()->startOfYear()->addMonths(9)->endOfQuarter(),
                ]
            );
        }
    }

    public static function boot()
    {
        parent::boot();

        static::creating(function ($model) {
            /*$model->bill_code = $model->invoice->invoice_code;

            $model->full_number = 'REGL-' . $model->invoice->full_number;*/

            $number = self::max('id') + 1;
            $model->code = str_pad($number, 5, 0, STR_PAD_LEFT);
            $model->full_number = 'REGL-' . str_pad($number, 5, 0, STR_PAD_LEFT);
        });
    }
}
