<?php

namespace App\Models\Authentification;

use App\Collections\Admin\AdminCollection;
use App\Domain\Support\SaveModel\BooleanField;
use App\Domain\Support\SaveModel\Contract\CanBeSavedInterface;
use App\Domain\Support\SaveModel\NumericField;
use App\Domain\Support\SaveModel\PasswordField;
use App\Domain\Support\SaveModel\StringField;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Contracts\Auth\MustVerifyEmail;

class Admin extends Authenticatable implements CanBeSavedInterface
{
    use HasFactory, Notifiable;


    /**
     * The attributes that are mass assignable.
     *
     * @var string[]
     */
    protected $fillable = [
        'nom',
        'prenom',
        'telephone',
        'email',
        'password',
        'active',
        'super_admin',
        'email_verified_at'
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
        'super_admin' => 'boolean',
        'active' => 'boolean',
    ];

    /**
     * @param array $models
     * @return Collection
     */
    public function newCollection(array $models = []): Collection
    {
        return new AdminCollection($models);
    }

    /**
     * @return array
     */
    public function saveableFields(): array
    {
        return [
            'nom' => StringField::new(),
            'prenom' => StringField::new(),
            'telephone' => NumericField::new(),
            'email' => StringField::new(),
            'password' => PasswordField::new(),
            'super_admin' => BooleanField::new()
        ];
    }
}
