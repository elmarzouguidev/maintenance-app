<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Client extends Model
{

    use HasFactory;


    public function saveableFields()
    {

        return [

            'nom' => 'string',
            'prenom' => 'string',
            'slug' => 'string',
            'address' => 'string',
            'email' => 'string',
            'gsm' => 'string',
            'telephone' => 'string',
            'ste_name' => 'string',
            'ste_ice' => 'integer',
            'ste_rc' => 'integer',
            'ste_logo' => 'string',
            'active' => 'string',

        ];
    }
}
