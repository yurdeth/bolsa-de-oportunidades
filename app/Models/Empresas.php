<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Empresas extends Model
{
    protected $table = 'empresas';

    protected $fillable = [
        'id_usuario',
        'id_sector',
        'nombre',
        'direccion',
        'telefono',
        'sitio_web',
        'descripcion',
        'logo_url',
        'verificada',
    ];
}
