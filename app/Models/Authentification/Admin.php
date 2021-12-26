<?php

namespace App\Models\Authentification;

use App\Collections\Admin\AdminCollection;
use App\Domain\Support\SaveModel\Fields\BooleanField;
use App\Domain\Support\SaveModel\Contract\CanBeSavedInterface;
use App\Domain\Support\SaveModel\Fields\PasswordField;
use App\Domain\Support\SaveModel\Fields\PhoneField;
use App\Domain\Support\SaveModel\Fields\StringField;
use App\Traits\UuidGenerator;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Elmarzougui\Roles\Builders\HasRoles;
use Illuminate\Database\Eloquent\Casts\Attribute;

class Admin extends Authenticatable  implements CanBeSavedInterface
{
    use HasFactory, Notifiable, HasRoles, UuidGenerator;

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


    public $guard_name = 'admin';


   /* public function getFullNameAttribute()
    {
        return $this->nom . ' ' . $this->prenom;
    }*/

    protected function fullName(): Attribute
    {
        return new Attribute(
            fn ($value) => $this->nom . ' ' . $this->prenom,
        );
    }
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
            'telephone' => PhoneField::new(),
            'email' => StringField::new(),
            'password' => PasswordField::new(),
            'super_admin' => BooleanField::new()
        ];
    }
}
