<?php

namespace App\Models\Finance;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Article extends Model
{
    use HasFactory;

    protected $fillable = [
        'invoice_id',
        'designation',
        'description',
        'quantity',
        'prix_unitaire',
        'montant_ht',
        'taxe'

    ];

    protected $casts = [
        'invoice_id' => 'integer',
        'quantity' => 'integer',
        'montant_ht' => 'float',
        'prix_unitaire' => 'float'
    ];

    public function invoice()
    {
        return $this->belongsTo(Invoice::class);
    }
}
