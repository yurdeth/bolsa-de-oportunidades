<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class ProyectosAsignados extends Model {
    protected $table = 'proyectos_asignados';

    protected $fillable = [
        'id_proyecto',
        'id_estudiante',
    ];

    public function proyecto() {
        return $this->belongsTo(Proyectos::class, 'id_proyecto');
    }

    public function getProyectosAsignados($id = null) {
        if (!is_null($id)) {
            return DB::table('proyectos_asignados')
                ->join('estudiantes', 'proyectos_asignados.id_estudiante', '=', 'estudiantes.id')
                ->join('usuarios', 'estudiantes.id_usuario', '=', 'usuarios.id')
                ->join('proyectos', 'proyectos_asignados.id_proyecto', '=', 'proyectos.id')
                ->join('modalidades_trabajo', 'proyectos.id_modalidad', '=', 'modalidades_trabajo.id')
                ->join('empresas', 'proyectos.id_empresa', '=', 'empresas.id')
                ->select('proyectos_asignados.id as id_asignado',
                    'estudiantes.id as id_estudiante',
                    'estudiantes.nombres',
                    'estudiantes.apellidos',
                    'usuarios.email',
                    'proyectos.id as id_proyecto',
                    'proyectos.titulo',
                    'modalidades_trabajo.nombre as modalidad',
                    'proyectos.ubicacion',
                    'empresas.nombre as empresa')
                ->where('proyectos_asignados.id', $id)
                ->first();
        }

        return DB::table('proyectos_asignados')
            ->join('estudiantes', 'proyectos_asignados.id_estudiante', '=', 'estudiantes.id')
            ->join('usuarios', 'estudiantes.id_usuario', '=', 'usuarios.id')
            ->join('proyectos', 'proyectos_asignados.id_proyecto', '=', 'proyectos.id')
            ->join('modalidades_trabajo', 'proyectos.id_modalidad', '=', 'modalidades_trabajo.id')
            ->join('empresas', 'proyectos.id_empresa', '=', 'empresas.id')
            ->select('proyectos_asignados.id as id_asignado',
                'estudiantes.id as id_estudiante',
                'estudiantes.nombres',
                'estudiantes.apellidos',
                'usuarios.email',
                'proyectos.id as id_proyecto',
                'proyectos.titulo',
                'proyectos.descripcion',
                'modalidades_trabajo.nombre as modalidad',
                'proyectos.ubicacion',
                'empresas.nombre as empresa')
            ->get();
    }
}
