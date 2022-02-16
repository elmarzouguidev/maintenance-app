<?php

namespace App\Models;

use App\Collections\Ticket\TicketCollection;

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

use Spatie\Activitylog\Traits\LogsActivity;


class Ticket extends Model implements HasMedia
{

    use HasFactory;
    use UuidGenerator;
    use GetModelByUuid;
    use InteractsWithMedia;
    use LogsActivity;
    use SoftDeletes;

    protected $fillable = [
        'etat',
        'status',
        'user_id',
        'can_invoiced'
    ];

    protected $casts = [
        'user_id' => 'boolean',
        'can_invoiced' => 'boolean',
    ];

    protected static array $logAttributes = ['etat', 'status'];

    public function client()
    {
        return $this->belongsTo(Client::class);
    }

    public function estimate()
    {
        return $this->hasOne(Estimate::class);
    }

    public function invoice()
    {
        return $this->hasOne(Invoice::class)->withDefault();
    }

    public function user()
    {
        return $this->belongsTo(User::class)->withDefault();
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

    public function getFullDateAttribute()
    {
        $date = Carbon::createFromFormat('Y-m-d H:i:s', $this->created_at);
        return $date->translatedFormat('d') . ' ' . $date->translatedFormat('F') . ' ' . $date->translatedFormat('Y');
    }

    protected function shortDescription(): Attribute
    {
        return new Attribute(
            fn () => Str::limit($this->description, 100, ' (...)'),
        );
    }

    public function registerMediaConversions(Media $media = null): void
    {
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
        parent::boot();

        $prefixer = config('app-config.tickets.prefix');

        static::creating(function ($model) use ($prefixer) {

            $number = (self::max('id') + 1);

            $model->code = $prefixer . str_pad($number, 5, 0, STR_PAD_LEFT);
        });
    }
}
