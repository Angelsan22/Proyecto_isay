<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;


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
