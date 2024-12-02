<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * @property integer $id
 * @property integer $id_usuario
 * @property string $carnet
 * @property string $nombres
 * @property string $apellidos
 * @property integer $id_carrera
 * @property integer $anio_estudio
 * @property string $telefono
 * @property string $direccion
 * @property User $usuario
 * @property Carreras $carrera
 * @property Aplicaciones[] $aplicaciones
 * @property Proyectos[] $proyectos
 */
class Estudiantes extends Model {
    protected $table = 'estudiantes';

    /**
     * Elementos que se pueden modificar
     *
     * @var string
     */
    protected $fillable = ['id_usuario', 'carnet', 'nombres', 'apellidos', 'id_carrera', 'anio_estudio', 'telefono', 'direccion'];

    /**
     * Relación con el modelo User
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function usuario() {
        return $this->belongsTo("App\Models\User", "id_usuario", "id");
    }

    /**
     * Relación con el modelo Carreras
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function carrera() {
        return $this->belongsTo("App\Models\Carreras", "id_carrera", "id");
    }

    /**
     * Relación con el modelo Aplicaciones
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function aplicaciones() {
        return $this->hasMany("App\Models\Aplicaciones", "id_estudiante", "id");
    }

    /**
     * Relación con el modelo Proyectos
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany
     */
    public function proyectos() {
        return $this->belongsToMany("App\Models\Proyectos", "estudiantes_proyectos", "id_estudiante", "id_proyecto");
    }
}
