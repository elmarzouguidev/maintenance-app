<?php

namespace App\Models;


use App\Models\Finance\Company;
use App\Models\Utilities\Telephone;
use App\Traits\GetModelByUuid;
use App\Traits\UuidGenerator;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

use Spatie\MediaLibrary\HasMedia;
use Spatie\MediaLibrary\InteractsWithMedia;
use Spatie\MediaLibrary\MediaCollections\Models\Media;

class Client extends Model implements HasMedia
{

    use HasFactory;
    use UuidGenerator;
    use InteractsWithMedia;
    use GetModelByUuid;

    //protected $with = ['tickets'];

    protected function fullName(): Attribute
    {
        return new Attribute(
            fn () => $this->contact,
        );
    }
    protected function allPhone(): Attribute
    {
        return new Attribute(
            fn () =>  $this->telephone,
        );
    }


    public function telephones()
    {
        return $this->morphMany(Telephone::class, 'telephoneable');
    }

    public function tickets()
    {
        return $this->hasMany(Ticket::class);
    }

    public function bills()
    {
        return $this->hasMany(Bill::class);
    }

    public function category()
    {
        return $this->belongsTo(Category::class)->withDefault();
    }

    public function company()
    {
        return $this->belongsTo(Company::class)->withDefault();
    }

    public function setEntrepriseAttribute($value)
    {
        $this->attributes['entreprise'] = $value;
        $this->attributes['slug'] = Str::slug($value);
    }

    public function getEditAttribute()
    {
        return route('admin:client.edit', $this->id);
    }

    public function getUpdateAttribute()
    {
        return route('admin:client.update', $this->id);
    }

    public function getUrlAttribute()
    {
        return  route('admin:clients.show', ['slug' => $this->uuid]);
    }

    public function registerMediaConversions(Media $media = null): void
    {
        $this->addMediaConversion('thumb')
            ->width(100)
            ->height(100)
            ->sharpen(10);
    }

    public static function boot()
    {

        parent::boot();

        $prefixer = config('app-config.clients.prefix');

        static::creating(function ($model) use ($prefixer) {

            $number = (self::max('id') + 1);

            $model->client_ref = $prefixer . str_pad($number, 5, 0, STR_PAD_LEFT);

            // $model->uuid = Str::uuid() . '-' . $model->client_ref;
        });
    }
}
