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
use App\Domain\Support\SaveModel\Fields\SlugField;
use App\Domain\Support\SaveModel\Fields\StringField;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Client extends Model implements CanBeSavedInterface
{

    use HasFactory;


    public function saveableFields(): array
    {

        return [

            'nom' => StringField::new(),
            'prenom' => StringField::new(),
            'slug' => SlugField::new(),
            'address' => StringField::new(),
            'email' => EmailField::new(),
            'gsm' => NumericField::new(),
            'telephone' => PhoneField::new(),
            'ste_name' => StringField::new(),
            'ste_ice' => IntegerField::new(),
            'ste_rc' => IntegerField::new(),
            'ste_logo' => ImageField::new(),
            'active' => BooleanField::new(),
            'published_at' => DatetimeField::new()

        ];
    }
}
