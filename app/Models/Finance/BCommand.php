<?php

namespace App\Models\Finance;

use App\Models\Utilities\History;
use App\Scopes\DefaultCompanyTrait;
use App\Traits\GetModelByUuid;
use App\Traits\UuidGenerator;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Carbon;

class BCommand extends Model
{
    use HasFactory;
    use UuidGenerator;
    use GetModelByUuid;
    use DefaultCompanyTrait;

    protected $fillable = [
        'is_send',
        'condition_general',
    ];

    protected $casts = [
        'date_command' => 'date:Y-m-d',
        'is_send' => 'boolean',
    ];

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
        return $this->morphMany(Article::class, 'articleable')->orderBy('created_at', 'ASC');
    }

    public function histories()
    {
        return $this->morphMany(History::class, 'historyable')->orderBy('created_at', 'ASC');
    }

    public function setConditionGeneralAttribute($value)
    {
        $this->attributes['condition_general'] = nl2br($value);
    }

    public function getConditionAttribute()
    {
        //dd($this->condition_general);
        return str_replace('<br />', "\n", $this->attributes['condition_general']);
    }

    public function getEditUrlAttribute()
    {
        return route('commercial:bcommandes.edit', $this->uuid);
    }

    public function getUrlAttribute()
    {
        return route('commercial:bcommandes.single', $this->uuid);
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

    public function scopeFiltersDateBc(Builder $query, $from): Builder
    {
        return $query->whereDate('date_command', Carbon::createFromFormat('d-m-Y', $from)->format('Y-m-d'));
    }

    public function scopeFiltersProviders(Builder $query, $client)
    {
        return $query->where('provider_id', $client);
    }

    public function scopeFiltersCompanies(Builder $query, $company)
    {
        //$company = Company::whereUuid($company)->firstOrFail()->id;

        return $query->where('company_id', $company);
    }

    public function scopeFiltersStatus(Builder $query, $status)
    {
        return $query->where('status', $status);
    }

    public function scopeFiltersDate(Builder $query, $from, $to)
    {
        if (isset($from) && isset($to)) {
            $startDate = Carbon::createFromFormat('Y-m-d', $from)->startOfDay();
            $endDate = Carbon::createFromFormat('Y-m-d', $to)->endOfDay();

            return $query->whereBetween('date_command', [$startDate, $endDate]);
        }

        return $query;
    }

    public function scopeFiltersPeriods(Builder $query, $period): Builder
    {
        if ($period == 1) {
            return $query->whereBetween(
                'date_command',
                [
                    now()->startOfYear()->startOfQuarter(),
                    now()->startOfYear()->endOfQuarter(),
                ]
            );
        }
        if ($period == 2) {
            return $query->whereBetween(
                'date_command',
                [
                    now()->startOfYear()->addMonths(3)->startOfQuarter(),
                    now()->startOfYear()->addMonths(3)->endOfQuarter(),
                ]
            );
        }
        if ($period == 3) {
            return $query->whereBetween(
                'date_command',
                [
                    now()->startOfYear()->addMonths(6)->startOfQuarter(),
                    now()->startOfYear()->addMonths(6)->endOfQuarter(),
                ]
            );
        }
        if ($period == 4) {
            return $query->whereBetween(
                'date_command',
                [
                    now()->startOfYear()->addMonths(9)->startOfQuarter(),
                    now()->startOfYear()->addMonths(9)->endOfQuarter(),
                ]
            );
        }
        
        return $query;
    }

    public static function boot()
    {
        parent::boot();

        static::creating(function ($model) {
            if ($model->company->bCommands->count() <= 0) {
                //dd('OOO empty');
                $number = $model->company?->bcommand_start_number;
            } else {
                //dd('Not empty ooo');
                $number = ($model->company?->bCommands->max('code') + 1);
            }

            $code = str_pad($number, 5, 0, STR_PAD_LEFT);

            $model->code = $code;

            $model->full_number = $model->company?->prefix_bcommand.$code;
        });
    }
}
