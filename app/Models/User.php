<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Passport\HasApiTokens;

class User extends Authenticatable
{
    /** @use HasFactory<\Database\Factories\UserFactory> */
    use HasFactory, Notifiable, HasApiTokens;

    protected $table = 'usuarios';

    protected $fillable = [
        'email',
        'password',
        'id_tipo_usuario',
        'estado_usuario',
        'fecha_registro',
        'token_recuperacion',
        'token_expiracion',
    ];

    protected $hidden = [
        'password',
        'token_recuperacion',
    ];

    public function tipoUsuario()
    {
        return $this->belongsTo("App\Models\TiposUsuarios", "id_tipo_usuario", "id");
    }
}
