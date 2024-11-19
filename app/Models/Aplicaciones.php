<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Aplicaciones extends Model
{
    protected $table = 'aplicaciones';

    protected $fillable = [
        'id_estudiante',
        'id_proyecto',
        'id_estado_aplicacion',
        'comentarios_empresa',
        'comentarios_estudiante',
        'fecha_inicio',
        'fecha_fin',
        'horas_completadas',
    ];
}
