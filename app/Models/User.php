<?php

namespace App\Models;

use App\Models\Utilities\Delivery;
use App\Traits\GetModelByUuid;
use App\Traits\UuidGenerator;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;
use Spatie\Permission\Traits\HasRoles;

class User extends Authenticatable
{
    use GetModelByUuid;
    use HasApiTokens, HasFactory, HasRoles, Notifiable;
    use UuidGenerator;

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
        'clear_password',
        'active',
        'super_admin',
        'email_verified_at',
    ];

    public $guard_name = 'admin';

    /**
     * The attributes that should be hidden for serialization.
     *
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
        'active' => 'boolean',
    ];

    protected function fullName(): Attribute
    {
        return new Attribute(
            fn () => $this->nom.' '.$this->prenom,
        );
    }

    /**
     * Display name, "Prénom Nom": the UI kit layouts show auth()->user()->name.
     */
    protected function name(): Attribute
    {
        return Attribute::get(fn (): string => trim($this->prenom.' '.$this->nom));
    }

    public function tickets()
    {
        return $this->hasMany(Ticket::class);
    }

    public function deliveries()
    {
        return $this->hasMany(Delivery::class);
    }
}
