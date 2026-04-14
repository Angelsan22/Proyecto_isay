<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * Modelo placeholder para el cliente.
 * Cuando se conecte a la BD real, se añadirán relaciones y atributos.
 */
class Cliente extends Model
{
    protected $fillable = [
        'nombre',
        'apellidos',
        'email',
        'password',
    ];

    protected $hidden = [
        'password',
    ];
}
