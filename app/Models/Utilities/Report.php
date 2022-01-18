<?php

namespace App\Models\Utilities;

use App\Collections\Report\ReportCollection;
use App\Domain\Support\SaveModel\Contract\CanBeSavedInterface;
use App\Domain\Support\SaveModel\Fields\StringField;
use App\Models\Authentification\Technicien;
use App\Models\Ticket;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Carbon;

class Report extends Model implements CanBeSavedInterface
{
    use HasFactory;


    protected $fillable = [
        'external_id',
        'ticket',
        'content',
        'type',
        'etat',
        'status',
        'reparation_status',
        'technicien_id',
        'ticket_id',
        'active',
        'envoyer_at',
        'ouvert_at',
        'annuler_at',
        'attentdevis_at',
        'confirme_at'
    ];

    protected $casts = [
        'active' => 'boolean',
        'technicien_id' => 'integer',
        'ticket_id' => 'integer',
    ];

    protected $with = ['getTicket'];

    public function getFullDateAttribute()
    {
        $date = Carbon::createFromFormat('Y-m-d H:i:s', $this->created_at);

        return $date->format('d') . ' ' . $date->format('F') . ' ' . $date->format('Y');
    }

    public function getTicketUrlAttribute()
    {
        return route('admin:tickets.diagnose', ['slug' => $this->getTicket->external_id]);
    }

    public function getSingleUrlAttribute()
    {
        return route('admin:reparations.single', ['slug' => $this->getTicket->external_id]);
    }

    public function getConfirmeDateAttribute()
    {
        $date = Carbon::createFromFormat('Y-m-d H:i:s', $this->confirme_at);
        return $date->format('d') . ' ' . $date->format('F') . ' ' . $date->format('Y');
    }

    public function getTicket()
    {
        return $this->belongsTo(Ticket::class, 'ticket_id')->withDefault();
    }

    public function technicien()
    {
        return $this->belongsTo(Technicien::class);
    }

    public function newCollection(array $models = [])
    {
        return new ReportCollection($models);
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
