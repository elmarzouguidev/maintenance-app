<?php

namespace App\Models;

use App\Domain\Support\SaveModel\DatetimeField;
use App\Domain\Support\SaveModel\ImageField;
use App\Domain\Support\SaveModel\IntegerField;
use App\Domain\Support\SaveModel\NumericField;
use App\Domain\Support\SaveModel\StringField;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Client extends Model
{

    use HasFactory;


    public function saveableFields(): array
    {

        return [

            'nom' => StringField::new(),
            'prenom' => StringField::new(),
            'slug' => StringField::new(),
            'address' => StringField::new(),
            'email' => StringField::new(),
            'gsm' => NumericField::new(),
            'telephone' => NumericField::new(),
            'ste_name' => StringField::new(),
            'ste_ice' => IntegerField::new(),
            'ste_rc' => IntegerField::new(),
            'ste_logo' => ImageField::new(),
            'active' => StringField::new(),
            'published_at' => DatetimeField::new()

        ];
    }
}
