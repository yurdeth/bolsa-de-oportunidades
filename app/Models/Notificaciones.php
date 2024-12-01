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

    public function getNotificacionesCoordinador($id_empresa): Collection {
        return DB::table('notificaciones')
            ->select('notificaciones.id as id_notificacion',
                'tipo_notificacion.nombre as estado_solicitud',
                'estudiantes.nombres',
                'estudiantes.apellidos',
                'estudiantes.telefono',
                'estudiantes.direccion',
                'carreras.nombre_carrera',
                'proyectos.id as id_proyecto',
                'proyectos.titulo',
                'empresas.nombre')
            ->join('tipo_notificacion', 'notificaciones.id_tipo_notificacion', '=', 'tipo_notificacion.id')
            ->join('usuarios', 'notificaciones.id_usuario', '=', 'usuarios.id')
            ->join('estudiantes', 'usuarios.id', '=', 'estudiantes.id_usuario')
            ->join('carreras', 'estudiantes.id_carrera', '=', 'carreras.id')
            ->join('proyectos', 'notificaciones.id_proyecto', '=', 'proyectos.id')
            ->join('empresas', 'proyectos.id_empresa', '=', 'empresas.id')
            ->where('notificaciones.leido', false)
            ->where('proyectos.id_empresa', $id_empresa)
            ->get();
    }
}
