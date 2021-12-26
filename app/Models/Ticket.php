<?php

namespace App\Models;

use App\Domain\Support\SaveModel\Contract\CanBeSavedInterface;
use App\Domain\Support\SaveModel\Fields\BooleanField;
use App\Domain\Support\SaveModel\Fields\ImageField;
use App\Domain\Support\SaveModel\Fields\StringField;
use App\Models\Authentification\Admin;
use App\Models\Authentification\Reception;
use App\Models\Authentification\Technicien;
use App\Traits\UuidGenerator;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Ticket extends Model implements CanBeSavedInterface
{

    use HasFactory, UuidGenerator;


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
