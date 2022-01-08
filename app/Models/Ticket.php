<?php

namespace App\Models;

use App\Models\Authentification\Admin;
use App\Models\Authentification\Reception;
use App\Models\Authentification\Technicien;
use App\Models\Utilities\Comment;
use App\Traits\UuidGenerator;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Carbon;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Str;
use Spatie\MediaLibrary\HasMedia;
use Spatie\MediaLibrary\InteractsWithMedia;
use Spatie\MediaLibrary\MediaCollections\Models\Media;

class Ticket extends Model implements HasMedia
{

    use HasFactory, UuidGenerator;
    use InteractsWithMedia;
    // use SoftDeletes;

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

    public function comments()
    {
        return $this->hasMany(Comment::class);
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
    public function getUniqueCodeAttribute()
    {
        return "#TK".$this->attributes['unique_code'];
    }

    protected function shortDescription(): Attribute
    {
        return new Attribute(
            fn () => Str::limit($this->description, 100, ' (...)'),
        );
    }

    public function registerMediaConversions(Media $media = null): void
    {
        $this->addMediaConversion('thumb')
            ->width(400)
            ->height(400)
            ->sharpen(10);
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
