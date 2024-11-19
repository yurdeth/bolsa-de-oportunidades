<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Proyectos extends Model
{
    protected $table = 'proyectos';

    protected $fillable = [
        'id_empresa',
        'titulo',
        'descripcion',
        'requisitos',
        'id_estado_oferta',
        'id_modalidad',
        'fecha_inicio',
        'fecha_fin',
        'fecha_limite_aplicacion',
        'estado_proyecto',
        'cupos_disponibles',
        'id_tipo_proyecto',
        'ubicacion',
        'id_carrera',
    ];
}
