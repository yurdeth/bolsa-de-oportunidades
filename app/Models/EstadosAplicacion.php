<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class EstadosAplicacion extends Model
{
    protected $table = 'estados_aplicacion';
    protected $fillable = ['nombre', 'descripcion'];
}
