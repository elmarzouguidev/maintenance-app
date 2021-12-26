<?php

namespace App\Models;

use App\Domain\Support\SaveModel\Contract\CanBeSavedInterface;
use App\Domain\Support\SaveModel\Fields\BooleanField;
use App\Domain\Support\SaveModel\Fields\ImageField;
use App\Domain\Support\SaveModel\Fields\SlugField;
use App\Domain\Support\SaveModel\Fields\StringField;
use App\Models\Authentification\Admin;
use App\Models\Authentification\Reception;
use App\Models\Authentification\Technicien;
use App\Traits\UuidGenerator;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Carbon;
use Spatie\MediaLibrary\HasMedia;
use Spatie\MediaLibrary\InteractsWithMedia;

class Ticket extends Model implements CanBeSavedInterface, HasMedia
{

    use HasFactory, UuidGenerator;

    use InteractsWithMedia;


    public function client()
    {
        return $this->belongsTo(Client::class);
    }

    public function admin()
    {
        return $this->belongsTo(Admin::class);
    }

    public function reception()
    {
        return $this->belongsTo(Reception::class);
    }

    public function technicien()
    {
        return $this->belongsTo(Technicien::class);
    }

    public function getUrlAttribute()
    {
        return  route('admin:tickets.single', ['slug' => $this->external_id]);
    }
    public function getEditAttribute()
    {
        return route('admin:tickets.edit', $this->id);
    }

    public function getUpdateUrlAttribute()
    {
        return route('admin:tickets.update', ['id' => $this->id]);
    }

    public function getImageAttribute()
    {
        return  \ticketApp::image($this->photo);
    }
    public function getAllImagesAttribute()
    {
        $images =  json_decode($this->photos) ?? [];

        $collection = collect($images);

        $imagesPaths = $collection->map(function ($item, $key) {

            return \ticketApp::image($item);
        });

        return $imagesPaths->all();
        //return $images;
    }

    public function getFullDateAttribute()
    {
        $date = Carbon::createFromFormat('Y-m-d H:i:s', $this->created_at);
        return $date->format('d') . ' ' . $date->format('F') . ' ' . $date->format('Y');
    }

    public function saveableFields(): array
    {
        return [

            'product' => StringField::new(),
            'description' => StringField::new(),
            'slug' => SlugField::new(),
            'photo' => ImageField::new()->storeToFolder('tickets-images')->DeletePreviousImage(),
            'photos' => ImageField::new()->storeToFolder('tickets-images')->isMultipleFile(),
            'active' => BooleanField::new()
        ];
    }


    /***** */




    public static function boot()
    {
        parent::boot();

        static::creating(function ($model) {
            $number = self::max('id') + 1;
            $model->unique_code = str_pad($number, 6, 0, STR_PAD_LEFT);
        });
    }
}
