<?php

declare(strict_types=1);

namespace App\Models\Finance;

use App\Models\Client;
use App\Models\Utilities\History;
use App\Scopes\DefaultCompanyTrait;
use App\Traits\GetModelByUuid;
use App\Traits\UuidGenerator;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Carbon;
use Illuminate\Database\Eloquent\Builder;

class BLivraison extends Model
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
        'date_bl' => 'date:Y-m-d',
        'is_send' => 'boolean',
        'price_ht' => 'float',
        'price_total' => 'float',
        'price_tva' => 'float',
    ];

    public function client()
    {
        return $this->belongsTo(Client::class);
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
        return route('commercial:blivraison.edit', $this->uuid);
    }

    public function getUrlAttribute()
    {
        return route('commercial:blivraison.single', $this->uuid);
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
        return $query->whereDate('date_bl', Carbon::createFromFormat('d-m-Y', $from)->format('Y-m-d'));
    }

    public function scopeFiltersProviders(Builder $query, $client)
    {
        return $query->where('client_id', $client);
    }

    public function scopeFiltersCompanies(Builder $query, $company)
    {
        //$company = Company::whereUuid($company)->firstOrFail()->id;

        return $query->where('company_id', $company);
    }

    public static function boot()
    {
        parent::boot();

        static::creating(function ($model) {
            if ($model->company->bLivraisons->count() <= 0) {
                //dd('OOO empty');
                $number = $model->company?->blivraison_start_number;
            } else {
                //dd('Not empty ooo');
                $number = ($model->company?->bLivraisons->max('code') + 1);
            }

            $code = str_pad("$number", 5, "0", STR_PAD_LEFT);

            $model->code = $code;

            $model->full_number = $model->company?->prefix_blivraison . $code;
        });
    }
}
