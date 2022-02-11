<?php

namespace App\Models;

use App\Collections\Ticket\TicketCollection;
use App\Models\Authentification\Admin;
use App\Models\Authentification\Reception;
use App\Models\Authentification\Technicien;
use App\Models\Finance\Estimate;
use App\Models\Finance\Invoice;
use App\Models\Utilities\Comment;
use App\Models\Utilities\Report;
use App\Traits\GetModelByUuid;
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
use Spatie\ModelStatus\HasStatuses;

class Ticket extends Model implements HasMedia
{

    use HasFactory;
    use UuidGenerator;
    use GetModelByUuid;
    use InteractsWithMedia;
    use HasStatuses;
    // use SoftDeletes;

    protected $fillable = [
        'etat',
        'stat',
        'technicien_id',
        'admin_id',
        'pret_a_facture'
    ];

    protected $casts = [
        'admin_id' => 'boolean',
        'technicien_id' => 'boolean',
        'pret_a_facture' => 'boolean',
    ];

    protected $with = [];


    public function client()
    {
        return $this->belongsTo(Client::class);
    }

    public function bills()
    {
        return $this->hasMany(Bill::class);
    }

    public function estimate()
    {
        return $this->hasOne(Estimate::class);
    }

    public function invoice()
    {
        return $this->hasOne(Invoice::class);
    }

    public function admin()
    {
        return $this->belongsTo(Admin::class)->withDefault();
    }

    public function reception()
    {
        return $this->belongsTo(Reception::class)->withDefault();
    }

    public function technicien()
    {
        return $this->belongsTo(Technicien::class)->withDefault();
    }

    public function comments()
    {
        return $this->hasMany(Comment::class);
    }

    public function reports()
    {
        return $this->hasMany(Report::class);
    }

    public function diagnoseReports()
    {
        return $this->hasOne(Report::class)->where('type', 'diagnostique');
    }

    public function reparationReports()
    {
        return $this->hasOne(Report::class)->where('type', 'reparation');
    }

    public function getUrlAttribute()
    {
        return  route('admin:tickets.single', ['slug' => $this->uuid]);
    }
    public function getEditAttribute()
    {
        return route('admin:tickets.edit', $this->id);
    }

    public function getUpdateUrlAttribute()
    {
        return route('admin:tickets.update', ['id' => $this->id]);
    }

    public function getTicketUrlAttribute()
    {
        return route('admin:tickets.diagnose', ['slug' => $this->uuid]);
    }


    public function getDiagnoseUrlAttribute()
    {
        return route('admin:tickets.diagnose', ['slug' => $this->uuid]);
    }

    public function getSendReportUrlAttribute()
    {
        return route('admin:tickets.diagnose.send-report', ['slug' => $this->uuid]);
    }

    public function getRepearUrlAttribute()
    {
        return route('admin:reparations.single', ['slug' => $this->uuid]);
    }


    public function getMediaUrlAttribute()
    {
        return route('admin:tickets.media', $this->uuid);
    }

    public function getHistoricalUrlAttribute()
    {
        return route('admin:tickets.historical', $this->uuid);
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
    /*public function getUniqueCodeAttribute()
    {
        return "#TK" . $this->attributes['unique_code'];
    }*/

    protected function shortDescription(): Attribute
    {
        return new Attribute(
            fn () => Str::limit($this->description, 100, ' (...)'),
        );
    }

    public function registerMediaConversions(Media $media = null): void
    {
        /*$this->addMediaConversion('thumb')
            ->width(400)
            ->height(400)
            ->sharpen(10);*/
        $this->addMediaConversion('normal')
            ->width(800)
            ->height(800)
            ->sharpen(10);
    }



    public function newCollection(array $models = [])
    {
        return new TicketCollection($models);
    }

    /***** */

    public static function boot()
    {
        //dd('First 1');
        parent::boot();

        $prefixer = config('app-config.tickets.prefix');

        static::creating(function ($model) use ($prefixer) {

            $number = (self::max('id') + 1);

            $model->unique_code = $prefixer . str_pad($number, 5, 0, STR_PAD_LEFT);

            // $model->uuid = Str::uuid() . '-' . $model->unique_code;
        });
    }
}
