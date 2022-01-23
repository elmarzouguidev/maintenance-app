<?php

namespace App\Models\Finance;

use App\Models\Client;
use App\Models\Ticket;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Carbon;

class Invoice extends Model
{
    use HasFactory;

    public function client()
    {
        return $this->belongsTo(Client::class);
    }

    public function ticket()
    {
        return $this->belongsTo(Ticket::class);
    }

    public function company()
    {
        return $this->belongsTo(Company::class);
    }

    public function articles()
    {
        return $this->hasMany(Article::class);
    }

    public function getIsPublishedAttribute(): bool
    {
        return $this->published_at->lessThanOrEqualTo(Carbon::now());
    }
}
