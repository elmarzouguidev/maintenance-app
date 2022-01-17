<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Reparation extends Model
{
    use HasFactory;

    protected $fillable = [
        'product',
        'details',
        'technicien_id',
        'ticket_id',
        'active',
        'status',
    ];
}
