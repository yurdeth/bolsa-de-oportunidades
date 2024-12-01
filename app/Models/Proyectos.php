<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Auth;
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

    public function getProyectos($id): Collection {
        if (Auth::user()->id_tipo_usuario == 2) {
            $infoCoordinador = Auth::user()->info_coordinador;
            $idCarrera = !empty($infoCoordinador) ? $infoCoordinador[0]->id_carrera : null;

            if (!is_null($id)) {
                return $this->fetchProyectos($id, $idCarrera);
            }

            return $this->fetchProyectos(null, $idCarrera);
        }

        return $this->fetchProyectos();
    }

    private function fetchProyectos($id = null, $idCarrera = null): Collection {
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

        $data = $query->get();

        return $data->map(function ($item) {
            $item->requisitos_proyecto = explode(',', $item->requisitos_proyecto);
            return $item;
        });
    }

    private function getEstudiantesEnProyecto($id_proyecto, $estadoAplicacion): Collection {
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

    public function getEstudiantesInteresadosEnProyecto($id_proyecto): Collection {
        return $this->getEstudiantesEnProyecto($id_proyecto, 1);
    }

    public function getEstudiantesAprobadosEnProyecto($id_proyecto): Collection {
        return $this->getEstudiantesEnProyecto($id_proyecto, 2);
    }

    public function empresa_table(): BelongsTo {
        return $this->belongsTo(Empresas::class, 'id_empresa');
    }

    public function estado_oferta_table(): BelongsTo {
        return $this->belongsTo(EstadosOferta::class, 'id_estado_oferta');
    }

    public function modalidad_trabajo_table(): BelongsTo {
        return $this->belongsTo(ModalidadesTrabajo::class, 'id_modalidad');
    }

    public function tipo_proyecto_table(): BelongsTo {
        return $this->belongsTo(TiposProyecto::class, 'id_tipo_proyecto');
    }

    public function carrera_table(): BelongsTo {
        return $this->belongsTo(Carreras::class, 'id_carrera');
    }

}
