<?php

namespace App\Models;

use App\Domain\Support\SaveModel\Contract\CanBeSavedInterface;
use App\Domain\Support\SaveModel\Fields\BooleanField;
use App\Domain\Support\SaveModel\Fields\ImageField;
use App\Domain\Support\SaveModel\Fields\StringField;
use App\Traits\UuidGenerator;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Ticket extends Model implements CanBeSavedInterface
{

    use HasFactory, UuidGenerator;

    public function saveableFields(): array
    {
        return [

            'product' => StringField::new(),
            'description' => StringField::new(),
            'photo' => ImageField::new(),
            'photos' => ImageField::new(),
            'active' => BooleanField::new()
        ];
    }
}
