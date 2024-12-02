<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * Modelo para la tabla 'aplicaciones'
 */
class Aplicaciones extends Model {
    protected $table = 'aplicaciones';

    /**
     * Atributos que son asignables masivamente
     *
     * @var array
     */
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
