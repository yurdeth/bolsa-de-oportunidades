<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;

class Notificaciones extends Model {
    protected $table = 'notificaciones';

    protected $fillable = [
        'id_tipo_notificacion',
        'id_usuario',
        'mensaje',
        'id_proyecto',
        'leido',
    ];

    public function getNotificacionesEmpresa($id_empresa): Collection {
        return DB::table('aplicaciones')
            ->select('aplicaciones.id_estado_aplicacion',
                'aplicaciones.id as id_aplicacion',
                'estudiantes.nombres',
                'estudiantes.apellidos',
                'estados_aplicacion.nombre as estado_aplicacion',
                'proyectos.titulo')
            ->join('estudiantes', 'aplicaciones.id_estudiante', '=', 'estudiantes.id')
            ->join('proyectos', 'aplicaciones.id_proyecto', '=', 'proyectos.id')
            ->join('estados_aplicacion', 'aplicaciones.id_estado_aplicacion', '=', 'estados_aplicacion.id')
            ->where('proyectos.id_empresa', $id_empresa)
            ->where('aplicaciones.id_estado_aplicacion', 1)
            ->get();
    }

    public function getNotificacionesCoordinador($id_carrera): Collection {
        return DB::table('aplicaciones')
            ->select('aplicaciones.id as id_aplicacion',
                'estudiantes.nombres',
                'estudiantes.apellidos',
                'proyectos.titulo',
                'estados_aplicacion.nombre as estado_aplicacion')
            ->join('estudiantes', 'aplicaciones.id_estudiante', '=', 'estudiantes.id')
            ->join('proyectos', 'aplicaciones.id_proyecto', '=', 'proyectos.id')
            ->join('estados_aplicacion', 'aplicaciones.id_estado_aplicacion', '=', 'estados_aplicacion.id')
            ->where('estudiantes.id_carrera', $id_carrera)
            ->where('aplicaciones.id_estado_aplicacion', 2)
            ->get();
    }
}
