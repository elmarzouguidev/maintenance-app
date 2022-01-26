<?php

namespace App\Models\Finance;

use App\Traits\UuidGenerator;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class EstimateArticle extends Model
{
    use HasFactory;
    
    use UuidGenerator;

    protected $fillable = [

        'invoice_id',
        'designation',
        'description',
        'quantity',
        'prix_unitaire',
        'montant_ht',
    ];

    protected $casts = [
        'invoice_id' => 'integer',
        'quantity' => 'integer',
        'montant_ht' => 'float',
        'prix_unitaire' => 'float'
    ];

    public function invoice()
    {
        return $this->belongsTo(Estimate::class);
    }

    public function getFormatedMontantHtAttribute()
    {
        return number_format($this->montant_ht, 2);
    }
}
