<?php

namespace Modules\SGA\Entities;

use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    use Notifiable;

    // Para que funcione correctamente con Spatie Permission en módulos
    protected $guard_name = 'web';

    /**
     * Atributos que se pueden asignar masivamente.
     */
    protected $fillable = [
        'name',
        'email',
        'password',
    ];

    /**
     * Atributos ocultos para arrays.
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * Casts para tipos de datos.
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
    ];
}
