<?php

namespace App\Models\Utilities;

use App\Domain\Support\SaveModel\Contract\CanBeSavedInterface;
use App\Domain\Support\SaveModel\Fields\StringField;
use App\Models\Authentification\Technicien;
use App\Models\Ticket;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Report extends Model implements CanBeSavedInterface
{
    use HasFactory;


    protected $fillable = [
        'ticket',
        'content',
        'type',
        'etat',
        'technicien_id',
        'ticket_id',
        'active'
    ];

    protected $casts = [
        'active' => 'boolean',
        'technicien_id' => 'integer',
        'ticket_id' => 'integer',
    ];

    public function ticket()
    {
        return $this->belongsTo(Ticket::class);
    }

    public function technicien()
    {
        return $this->belongsTo(Technicien::class);
    }

    public function saveableFields(): array
    {

        return [

            'ticket' => StringField::new(),
            'content' => StringField::new(),
            'type' => StringField::new(),
            'etat' => StringField::new(),

        ];
    }
}
