<?php

namespace App\Models\Finance;

use App\Models\Utilities\Email;
use App\Models\Utilities\Telephone;
use App\Traits\GetModelByUuid;
use App\Traits\UuidGenerator;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Provider extends Model
{
    use GetModelByUuid;
    use HasFactory;
    use UuidGenerator;

    /**
     * The suppliers list and its exports: filtered by a part of the code, company, phone, e-mail,
     * ICE or RC (every supplier when $search is empty), code descending as the former DataTables list.
     */
    public function scopeListing(Builder $query, string $search = ''): void
    {
        $query->when($search !== '', fn (Builder $query) => $query->where(function (Builder $query) use ($search): void {
            foreach (['code', 'entreprise', 'telephone', 'email', 'ice', 'rc'] as $column) {
                $query->orWhere($column, 'like', '%'.$search.'%');
            }
        }))
            ->orderByDesc('code')
            ->orderByDesc('id');
    }

    public function getEditAttribute()
    {
        return route('commercial:providers.edit', $this->uuid);
    }

    public function bCommands()
    {
        return $this->hasMany(BCommand::class);
    }

    public function telephones()
    {
        return $this->morphMany(Telephone::class, 'telephoneable');
    }

    public function emails()
    {
        return $this->morphMany(Email::class, 'emailable');
    }

    public static function boot()
    {
        parent::boot();

        $prefixer = config('app-config.providers.prefix');

        static::creating(function ($model) use ($prefixer) {
            $number = (self::max('id') + 1);

            $model->code = $prefixer.str_pad($number, 5, 0, STR_PAD_LEFT);
        });
    }
}
