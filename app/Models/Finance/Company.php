<?php

namespace App\Models\Finance;

use App\Models\Client;
use App\Traits\GetModelByUuid;
use App\Traits\UuidGenerator;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Spatie\MediaLibrary\HasMedia;
use Spatie\MediaLibrary\InteractsWithMedia;
use Spatie\MediaLibrary\MediaCollections\Models\Media;

class Company extends Model implements HasMedia
{
    use GetModelByUuid;
    use HasFactory;
    use InteractsWithMedia;
    use UuidGenerator;

    protected $fillable = [
        'name',
        'website',
        'logo',
        'city',
        'addresse',
        'telephone',
        'email',
        'rc',
        'ice',
        'cnss',
        'patente',
        'if',
        'prefix_invoice',
        'is_default',
    ];

    protected $casts = [
        'is_default' => 'boolean',
    ];

    public function clients()
    {
        return $this->hasMany(Client::class);
    }

    /**
     * The number the next invoice of this company will get (Invoice::creating assigns it the same way).
     */
    public function nextInvoiceNumber(): string
    {
        $number = $this->invoices()->exists() ? (int) $this->invoices()->max('code') + 1 : (int) $this->invoice_start_number;

        return $this->prefix_invoice.str_pad((string) $number, 5, '0', STR_PAD_LEFT);
    }

    /**
     * The number the next estimate of this company will get (Estimate::creating assigns it the same way).
     */
    public function nextEstimateNumber(): string
    {
        $number = $this->estimates()->exists() ? (int) $this->estimates()->max('code') + 1 : (int) $this->estimate_start_number;

        return $this->prefix_estimate.str_pad((string) $number, 5, '0', STR_PAD_LEFT);
    }

    /**
     * The number the next credit note of this company will get (InvoiceAvoir::creating assigns it the same way).
     */
    public function nextInvoiceAvoirNumber(): string
    {
        $number = $this->invoicesAvoir()->exists() ? (int) $this->invoicesAvoir()->max('code') + 1 : (int) $this->invoice_avoir_start_number;

        return $this->prefix_invoice_avoir.str_pad((string) $number, 5, '0', STR_PAD_LEFT);
    }

    public function invoices()
    {
        return $this->hasMany(Invoice::class);
    }

    public function invoicesAvoir()
    {
        return $this->hasMany(InvoiceAvoir::class);
    }

    public function estimates()
    {
        return $this->hasMany(Estimate::class);
    }

    public function bCommands()
    {
        return $this->hasMany(BCommand::class);
    }

    public function bLivraisons()
    {
        return $this->hasMany(BLivraison::class);
    }

    public function bills()
    {
        return $this->hasMany(Bill::class);
    }

    public function getEditUrlAttribute()
    {
        return route('commercial:companies.edit', $this->uuid);
    }

    public function registerMediaConversions(?Media $media = null): void
    {
        $this->addMediaConversion('thumb')
            ->width(200)
            ->height(180)
            ->sharpen(10);
    }
}
