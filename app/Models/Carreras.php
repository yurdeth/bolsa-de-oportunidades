<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * Modelo para la tabla 'carreras'
 */
class Carreras extends Model {
    protected $table = 'carreras';

    /**
     * Atributos que son asignables masivamente
     *
     * @var array
     */
    protected $fillable = ['id_departamento', 'codigo_carrera', 'nombre_carrera'];
}
