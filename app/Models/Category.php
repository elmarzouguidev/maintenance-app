<?php

namespace App\Models;

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

            'name' => 'string',
            'slug' => 'string',
            'logo'=>'image'
        ];
    }
}
