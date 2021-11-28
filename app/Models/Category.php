<?php

namespace App\Models;

use App\Domain\Support\SaveModel\BooleanField;
use App\Domain\Support\SaveModel\Contract\CanBeSavedInterface;
use App\Domain\Support\SaveModel\ImageField;
use App\Domain\Support\SaveModel\StringField;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\UploadedFile;

class Category extends Model implements CanBeSavedInterface
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


    protected $casts = [
        'active' => 'boolean',
    ];

    public function saveableFields(): array
    {

        return [

            'name' => StringField::new(),
            'slug' => StringField::new(),
            'is_published' => BooleanField::new(),
            //'logo' => ImageField::new(),
            'logo' => ImageField::new()
                ->storeToFolder('categories-photos'),
           /* 'logo' => ImageField::new()
                ->storeToFolder('categories-photos')
                ->fileName(function (UploadedFile $uploadedFile) {
                    //  dd($uploadedFile);
                    return $uploadedFile->getClientOriginalName();

                })*/
        ];
    }
}