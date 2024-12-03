<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

/**
 * @property int id
 * @property int id_empresa
 * @property string titulo
 * @property string descripcion
 * @property string requisitos
 * @property int id_estado_oferta
 * @property int id_modalidad
 * @property string fecha_inicio
 * @property string fecha_fin
 * @property string fecha_limite_aplicacion
 * @property string estado_proyecto
 * @property int cupos_disponibles
 * @property int id_tipo_proyecto
 * @property string ubicacion
 * @property int id_carrera
 */
class Proyectos extends Model {
    protected $table = 'proyectos';

    /**
     * Elementos que pueden ser asignados masivamente
     *
     * @var array
     */
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

    /**
     * Obtiene los proyectos
     *
     * @param int|null $id
     * @return Collection
     */
    public function getProyectos($id): Collection {
        if (Auth::user()->id_tipo_usuario == 2) {
            $infoCoordinador = Auth::user()->info_coordinador;
            $idCarrera = $infoCoordinador[0]->id_carrera;

            if (!is_null($id)) {
                return $this->fetchProyectos($id, $idCarrera);
            }

            return $this->fetchProyectos(null, $idCarrera);
        }

        if (Auth::user()->id_tipo_usuario == 4) {
            $idEmpresa = Auth::user()->info_empresa[0]->id;
            if (!is_null($id)) {
                return $this->fetchProyectos($id, null);
            }

            return $this->fetchProyectos(null, null);
        }

        if (!is_null($id)) {
            return $this->fetchProyectos($id, null);
        }

        return $this->fetchProyectos();
    }

    /**
     * Obtiene los proyectos de la base de datos
     *
     * @param int|null $id
     * @param int|null $idCarrera
     * @return Collection
     */
    private function fetchProyectos($id = null, $idCarrera = null): Collection {
        if(Auth::user()->id_tipo_usuario == 2){
            $infoCoordinador = Auth::user()->info_coordinador;
            $id_carrera = $infoCoordinador[0]->id_carrera;
        }

        if (Auth::user()->id_tipo_usuario == 2) {
            $query = DB::table('proyectos')
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
                ->join('aplicaciones', 'aplicaciones.id_proyecto', '=', 'proyectos.id')
                ->join('estudiantes', 'aplicaciones.id_estudiante', '=', 'estudiantes.id')
                ->where('aplicaciones.id_estado_aplicacion', 2)
                ->where('estudiantes.id_carrera', $id_carrera);

            $data = $query->get();

            return $data->map(function ($item) {
                $item->requisitos_proyecto = explode(',', $item->requisitos_proyecto);
                return $item;
            });
        }

        $query = DB::table('proyectos')
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
            ->join('carreras', 'proyectos.id_carrera', '=', 'carreras.id');

        if (!is_null($id)) {
            $query->where('proyectos.id', $id);
        }

        if (!is_null($idCarrera)) {
            $query->where('proyectos.id_carrera', $idCarrera);
        }

        if (Auth::user()->id_tipo_usuario != 2 && Auth::user()->id_tipo_usuario != 4) {
            $query->where('proyectos.cupos_disponibles', '>', 0);
        }

        if (Auth::user()->id_tipo_usuario == 2) {
            $query->where('proyectos.id_carrera', $id_carrera);
        }

        $data = $query->get();

        return $data->map(function ($item) {
            $item->requisitos_proyecto = explode(',', $item->requisitos_proyecto);
            return $item;
        });
    }

    /**
     * Obtiene los estudiantes que estan interesados en un proyecto
     *
     * @param int $id_proyecto
     * @return Collection
     */
    private function getEstudiantesEnProyecto($id_proyecto, $estadoAplicacion, $id_carrera = null): Collection {
        if (Auth::user()->id_tipo_usuario == 2) {
            $infoCoordinador = Auth::user()->info_coordinador;
            $id_carrera = $infoCoordinador[0]->id_carrera;
        }

        if (!is_null($id_carrera)) {
            return DB::table('aplicaciones')
                ->select(
                    'estudiantes.id as id_estudiante',
                    'estudiantes.nombres',
                    'estudiantes.apellidos',
                    'usuarios.email',
                    'carreras.nombre_carrera',
                    'estudiantes.telefono',
                    'estudiantes.direccion',
                    'estudiantes.anio_estudio',
                )
                ->join('estudiantes', 'aplicaciones.id_estudiante', '=', 'estudiantes.id')
                ->join('usuarios', 'estudiantes.id_usuario', '=', 'usuarios.id')
                ->join('carreras', 'estudiantes.id_carrera', '=', 'carreras.id')
                ->where('aplicaciones.id_proyecto', $id_proyecto)
                ->where('id_estado_aplicacion', '=', $estadoAplicacion)
                ->where('estudiantes.id_carrera', $id_carrera)
                ->get();
        }

        return DB::table('aplicaciones')
            ->select(
                'estudiantes.id as id_estudiante',
                'estudiantes.nombres',
                'estudiantes.apellidos',
                'usuarios.email',
                'carreras.nombre_carrera',
                'estudiantes.telefono',
                'estudiantes.direccion',
                'estudiantes.anio_estudio',
            )
            ->join('estudiantes', 'aplicaciones.id_estudiante', '=', 'estudiantes.id')
            ->join('usuarios', 'estudiantes.id_usuario', '=', 'usuarios.id')
            ->join('carreras', 'estudiantes.id_carrera', '=', 'carreras.id')
            ->where('aplicaciones.id_proyecto', $id_proyecto)
            ->where('id_estado_aplicacion', '=', $estadoAplicacion)
            ->get();
    }

    /**
     * Obtiene los estudiantes interesados en un proyecto
     *
     * @param int $id_proyecto
     * @return Collection
     */
    public function getEstudiantesInteresadosEnProyecto($id_proyecto): Collection {
        return $this->getEstudiantesEnProyecto($id_proyecto, 1);
    }

    /**
     * Obtiene los estudiantes aprobados en un proyecto
     *
     * @param int $id_proyecto
     * @return Collection
     */
    public function getEstudiantesAprobadosEnProyecto($id_proyecto, $id_carrera): Collection {
        return $this->getEstudiantesEnProyecto($id_proyecto, 2, $id_carrera);
    }

    /**
     * Relacion con la tabla empresas
     *
     * @param int $id_proyecto
     * @return Collection
     */
    public function empresa_table(): BelongsTo {
        return $this->belongsTo(Empresas::class, 'id_empresa');
    }

    /**
     * Relacion con la tabla estados_oferta
     *
     * @param int $id_proyecto
     * @return Collection
     */
    public function estado_oferta_table(): BelongsTo {
        return $this->belongsTo(EstadosOferta::class, 'id_estado_oferta');
    }

    /**
     * Relacion con la tabla modalidades_trabajo
     *
     * @param int $id_proyecto
     * @return Collection
     */
    public function modalidad_trabajo_table(): BelongsTo {
        return $this->belongsTo(ModalidadesTrabajo::class, 'id_modalidad');
    }

    /**
     * Relacion con la tabla tipos_proyecto
     *
     * @param int $id_proyecto
     * @return Collection
     */
    public function tipo_proyecto_table(): BelongsTo {
        return $this->belongsTo(TiposProyecto::class, 'id_tipo_proyecto');
    }

    /**
     * Relacion con la tabla carreras
     *
     * @param int $id_proyecto
     * @return Collection
     */
    public function carrera_table(): BelongsTo {
        return $this->belongsTo(Carreras::class, 'id_carrera');
    }

}
