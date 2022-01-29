<?php

namespace App\Models\Authentification;

use App\Domain\Support\SaveModel\Contract\CanBeSavedInterface;
use App\Domain\Support\SaveModel\Fields\ArrayField;
use App\Domain\Support\SaveModel\Fields\PasswordField;
use App\Domain\Support\SaveModel\Fields\PhoneField;
use App\Domain\Support\SaveModel\Fields\StringField;
use App\Models\Ticket;
use App\Models\Utilities\Report;
use App\Notifications\Auth\Technicien\TechnicienResetPasswordNotification;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Elmarzougui\Roles\Builders\HasRoles;

class Technicien extends Authenticatable implements CanBeSavedInterface
{
    use HasFactory, Notifiable, HasRoles;


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
        'active'
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

    public $guard_name = 'technicien';
    
    /**
     * The attributes that should be cast.
     *
     * @var array
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
        'active' => 'boolean',
    ];

    public function tickets()
    {
        return $this->hasMany(Ticket::class);
    }

    public function reports()
    {
        return $this->hasMany(Report::class);
    }

    protected function fullName(): Attribute
    {
        return new Attribute(
            fn () => $this->nom . ' ' . $this->prenom,
        );
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
            'permissions' => ArrayField::new()
        ];
    }


       /*****Notifications */

       public function sendPasswordResetNotification($token)
       {
           $this->notify(new TechnicienResetPasswordNotification($token));
       }
}
