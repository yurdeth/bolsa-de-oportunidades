<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Estudiantes extends Model
{
    protected $table = 'estudiantes';

    protected $fillable = ['id_usuario', 'carnet', 'nombres', 'apellidos', 'id_carrera', 'anio_estudio', 'telefono', 'direccion'];

    public function usuario()
    {
        return $this->belongsTo("App\Models\User", "id_usuario", "id");
    }

    public function carrera()
    {
        return $this->belongsTo("App\Models\Carreras", "id_carrera", "id");
    }

    public function aplicaciones()
    {
        return $this->hasMany("App\Models\Aplicaciones", "id_estudiante", "id");
    }

    public function proyectos()
    {
        return $this->belongsToMany("App\Models\Proyectos", "estudiantes_proyectos", "id_estudiante", "id_proyecto");
    }
}
