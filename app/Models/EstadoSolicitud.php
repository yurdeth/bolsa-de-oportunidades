<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;

/**
 * @property integer $id
 * @property integer $id_proyecto
 * @property integer $id_estudiante
 * @property integer $aprobado
 */
class EstadoSolicitud extends Model {
    protected $table = 'proyectos_aprobados';

    /**
     * Atributos asignables.
     *
     * @var array
     */
    protected $fillable = [
        'id_proyecto',
        'id_estudiante',
        'id_estado_aplicacion',
    ];

    /**
     * Obtener el estado de una solicitud.
     *
     * @param integer $id
     * @return Collection
     */
    public function getEstadoSolicitud($id): Collection {
        if (!is_null($id)) {
            $data = DB::table('proyectos_aprobados')
                ->select(
                    'proyectos_aprobados.id as id_solicitud',
                    'proyectos_aprobados.id_proyecto as id_proyecto',
                    'proyectos_aprobados.id_estudiante as id_estudiante',
                    'proyectos_aprobados.aprobado as aprobado',
                )
                ->where('proyectos_aprobados.id', '=', $id)
                ->get();
        } else {
            $data = DB::table('proyectos_aprobados')
                ->select(
                    'proyectos_aprobados.id as id_solicitud',
                    'proyectos_aprobados.id_proyecto as id_proyecto',
                    'proyectos_aprobados.id_estudiante as id_estudiante',
                    'proyectos_aprobados.aprobado as aprobado',
                )
                ->get();
        }
        return $data;
    }
}
