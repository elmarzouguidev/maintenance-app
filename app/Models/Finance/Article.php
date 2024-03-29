<?php

namespace App\Models\Finance;

use App\Traits\UuidGenerator;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Article extends Model
{
    use HasFactory;
    use UuidGenerator;

    //use SoftDeletes;

    protected $fillable = [
        'articleable_id',
        'articleable_type',
        'designation',
        //'description',
        'quantity',
        'prix_unitaire',
        'montant_ht',
        'remise_fix',
        'remise',
        'taux_remise',
    ];

    protected $casts = [
        'quantity' => 'integer',
        'remise_fix' => 'boolean',
        //'montant_ht' => 'integer',
        //'prix_unitaire' => 'integer'
    ];

    public function articleable()
    {
        return $this->morphTo();
    }

    public function getFormatedMontantHtAttribute()
    {
        return number_format($this->montant_ht, 2);
    }

    public function getFormatedPrixUnitaireAttribute()
    {
        return number_format($this->prix_unitaire, 2);
    }

    public function getFormatedPriceRemiseAttribute()
    {
        return number_format($this->taux_remise, 2);
    }

   /* public function setDescriptionAttribute($value)
    {
        $this->attributes['description'] = nl2br($value);
    }*/

    public function setDesignationAttribute($value)
    {
        $this->attributes['designation'] = nl2br($value);
    }
}
