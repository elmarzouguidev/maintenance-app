<?php

namespace App\Models;

use App\Domain\Support\SaveModel\ImageField;
use App\Domain\Support\SaveModel\StringField;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Category extends Model
{
    use HasFactory;

    /**
     * @var string[]
     */
    protected $fillable = [
        'name',
        'slug',
        'logo',
        'active',
    ];

    /**
     * @var string[]
     */
    protected $casts = [
        'active'=>'boolean',
    ];

    public function saveableFields(): array
    {

        return [

            'name' => StringField::new(),
            'slug' => StringField::new(),
            'logo' => ImageField::new()
        ];
    }
}
