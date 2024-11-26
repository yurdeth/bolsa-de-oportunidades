<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;

class Proyectos extends Model {
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

    public function getProyetos($id): Collection {

        /*
         * Campos obtenidos:
         * - id_proyecto
         * - nombre_empresa
         * - titulo_proyecto
         * - descripcion_proyeto
         * - requisitos_proyecto
         * - estado_oferta
         * - modalidad
         * - fecha_inicio_proyecto
         * - fecha_fin_proyecto
         * - fecha_limite_aplicacion
         * - estado_proyecto
         * - cupos_disponibles
         * - tipo_proyeto (horas sociales o pasantía)
         * - ubicacion_proyecto
         * - nombre_carrera (carrera_ideal)
         * */

        if (!is_null($id)){
            $data = DB::table('proyectos')
                ->select(
                    'proyectos.id as id_proyecto',
                    'empresas.nombre as nombre_empresa',
                    'proyectos.titulo as titulo_proyecto',
                    'proyectos.descripcion as descripcion_proyeto',
                    'proyectos.requisitos as requisitos_proyecto',
                    'estados_oferta.nombre_estado as estado_oferta',
                    'modalidades_trabajo.nombre as modalidad',
                    'proyectos.fecha_inicio as fecha_inicio_proyecto',
                    'proyectos.fecha_fin as fecha_fin_proyecto',
                    'proyectos.fecha_limite_aplicacion as fecha_limite_aplicacion',
                    'proyectos.estado_proyecto as estado_proyecto',
                    'proyectos.cupos_disponibles as cupos_disponibles',
                    'tipos_proyecto.nombre as tipo_proyecto',
                    'proyectos.ubicacion as ubicacion_proyecto',
                    'carreras.nombre_carrera as nombre_carrera'
                )
                ->join('empresas', 'proyectos.id_empresa', '=', 'empresas.id')
                ->join('estados_oferta', 'proyectos.id_estado_oferta', '=', 'estados_oferta.id')
                ->join('modalidades_trabajo', 'proyectos.id_modalidad', '=', 'modalidades_trabajo.id')
                ->join('tipos_proyecto', 'proyectos.id_tipo_proyecto', '=', 'tipos_proyecto.id')
                ->join('carreras', 'proyectos.id_carrera', '=', 'carreras.id')
                ->where('proyectos.id', $id)
                ->get();

            return $data->map(function ($item) {
                $item->requisitos_proyecto = explode(',', $item->requisitos_proyecto);
                return $item;
            });
        }

        $data =  DB::table('proyectos')
            ->select(
                'proyectos.id as id_proyecto',
                'empresas.nombre as nombre_empresa',
                'proyectos.titulo as titulo_proyecto',
                'proyectos.descripcion as descripcion_proyeto',
                'proyectos.requisitos as requisitos_proyecto',
                'estados_oferta.nombre_estado as estado_oferta',
                'modalidades_trabajo.nombre as modalidad',
                'proyectos.fecha_inicio as fecha_inicio_proyecto',
                'proyectos.fecha_fin as fecha_fin_proyecto',
                'proyectos.fecha_limite_aplicacion as fecha_limite_aplicacion',
                'proyectos.estado_proyecto as estado_proyecto',
                'proyectos.cupos_disponibles as cupos_disponibles',
                'tipos_proyecto.nombre as tipo_proyecto',
                'proyectos.ubicacion as ubicacion_proyecto',
                'carreras.nombre_carrera as nombre_carrera'
            )
            ->join('empresas', 'proyectos.id_empresa', '=', 'empresas.id')
            ->join('estados_oferta', 'proyectos.id_estado_oferta', '=', 'estados_oferta.id')
            ->join('modalidades_trabajo', 'proyectos.id_modalidad', '=', 'modalidades_trabajo.id')
            ->join('tipos_proyecto', 'proyectos.id_tipo_proyecto', '=', 'tipos_proyecto.id')
            ->join('carreras', 'proyectos.id_carrera', '=', 'carreras.id')
            ->get();

        return $data->map(function ($item) {
            $item->requisitos_proyecto = explode(',', $item->requisitos_proyecto);
            return $item;
        });
    }

    public function getEstudiantesInteresadosEnProyecto($id): Collection {
        return DB::table('favoritos')
            ->select(
                'estudiantes.nombres',
                'estudiantes.apellidos',
                'estudiantes.carnet',
                'usuarios.email',
                'carreras.nombre_carrera',
                'estudiantes.telefono',
                'estudiantes.direccion'
            )
            ->join('estudiantes', 'favoritos.id_estudiante', '=', 'estudiantes.id')
            ->join('usuarios', 'estudiantes.id_usuario', '=', 'usuarios.id')
            ->join('carreras', 'estudiantes.id_carrera', '=', 'carreras.id')
            ->where('favoritos.id_proyecto', $id)
            ->get();
    }

    public function empresa_table(): BelongsTo
    {
        return $this->belongsTo(Empresas::class, 'id_empresa');
    }

    public function estado_oferta_table(): BelongsTo
    {
        return $this->belongsTo(EstadosOferta::class, 'id_estado_oferta');
    }

    public function modalidad_trabajo_table(): BelongsTo
    {
        return $this->belongsTo(ModalidadesTrabajo::class, 'id_modalidad');
    }

    public function tipo_proyecto_table(): BelongsTo
    {
        return $this->belongsTo(TiposProyecto::class, 'id_tipo_proyecto');
    }

    public function carrera_table(): BelongsTo
    {
        return $this->belongsTo(Carreras::class, 'id_carrera');
    }

}
