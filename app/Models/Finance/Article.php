<?php

namespace App\Models\Finance;

use App\Traits\UuidGenerator;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Article extends Model
{
    use HasFactory;
    
    use UuidGenerator;

    protected $table = 'invoices_articles';

    protected $fillable = [

        'invoice_id',
        'client_id',
        'ticket_id',
        'designation',
        'description',
        'quantity',
        'prix_unitaire',
        'montant_ht',
    ];

    protected $casts = [
        'invoice_id' => 'integer',
        'client_id' => 'integer',
        'ticket_id' => 'integer',
        'quantity' => 'integer',
        'montant_ht' => 'float',
        'prix_unitaire' => 'float'
    ];

    public function invoice()
    {
        return $this->belongsTo(Invoice::class);
    }

    public function getFormatedMontantHtAttribute()
    {
        return number_format($this->montant_ht, 2);
    }

    public function getFormatedPrixUnitaireAttribute()
    {
        return number_format($this->prix_unitaire, 2);
    }
}
