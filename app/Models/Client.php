<?php

namespace App\Models;

use App\Domain\Support\SaveModel\Contract\CanBeSavedInterface;
use App\Domain\Support\SaveModel\Fields\BooleanField;
use App\Domain\Support\SaveModel\Fields\DatetimeField;
use App\Domain\Support\SaveModel\Fields\EmailField;
use App\Domain\Support\SaveModel\Fields\ImageField;
use App\Domain\Support\SaveModel\Fields\IntegerField;
use App\Domain\Support\SaveModel\Fields\NumericField;
use App\Domain\Support\SaveModel\Fields\PhoneField;
use App\Domain\Support\SaveModel\Fields\StringField;
use App\Traits\UuidGenerator;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Client extends Model implements CanBeSavedInterface
{

    use HasFactory, UuidGenerator;


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

    public function tickets()
    {
        return $this->hasMany(Ticket::class);
    }

    public function category()
    {
        return $this->belongsTo(Category::class);
    }

    public function saveableFields(): array
    {

        return [

            'entreprise' => StringField::new(),
            'contact' => StringField::new(),
            'addresse' => StringField::new(),
            'email' => EmailField::new(),
            'telephone' => PhoneField::new(),
            'rc' => IntegerField::new(),
            'ice' => IntegerField::new(),
            'logo' => ImageField::new(),
            'description' => StringField::new(),
            'category' => IntegerField::new()
        ];
    }
}
