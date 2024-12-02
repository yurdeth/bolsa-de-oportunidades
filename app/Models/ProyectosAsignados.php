<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;

/**
 * @property integer $id
 * @property integer $id_proyecto
 * @property integer $id_estudiante
 * @property Proyectos $proyecto
 */
class ProyectosAsignados extends Model {
    protected $table = 'proyectos_asignados';

    /**
     * Atributos que son asignables
     *
     * @var array
     */
    protected $fillable = [
        'id_proyecto',
        'id_estudiante',
    ];

    /**
     * Relación con la tabla proyectos
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function proyecto() {
        return $this->belongsTo(Proyectos::class, 'id_proyecto');
    }

    /**
     * Obtiene los proyectos asignados a los estudiantes.
     *
     * Este método recupera los proyectos asignados a los estudiantes. Si se pasa un ID específico como parámetro,
     * se recupera un único proyecto asignado, con información detallada sobre el estudiante, el proyecto,
     * la modalidad de trabajo, el tipo de proyecto y la empresa. Si no se pasa un ID, se recuperan todos los proyectos
     * asignados con la misma información.
     *
     * @param int|null $id El ID del proyecto asignado para obtener un proyecto específico. Si es null, se obtienen todos los proyectos asignados.
     * @return \Illuminate\Support\Collection|object Una colección de proyectos asignados o un único proyecto asignado.
     */
    public function getProyectosAsignados($id = null) {
        if (!is_null($id)) {
            return DB::table('proyectos_asignados')
                ->join('estudiantes', 'proyectos_asignados.id_estudiante', '=', 'estudiantes.id')
                ->join('usuarios', 'estudiantes.id_usuario', '=', 'usuarios.id')
                ->join('proyectos', 'proyectos_asignados.id_proyecto', '=', 'proyectos.id')
                ->join('modalidades_trabajo', 'proyectos.id_modalidad', '=', 'modalidades_trabajo.id')
                ->join('empresas', 'proyectos.id_empresa', '=', 'empresas.id')
                ->join('tipos_proyecto', 'proyectos.id_tipo_proyecto', '=', 'tipos_proyecto.id')
                ->select('proyectos_asignados.id as id_asignado',
                    'estudiantes.id as id_estudiante',
                    'estudiantes.nombres',
                    'estudiantes.apellidos',
                    'usuarios.email',
                    'proyectos.id as id_proyecto',
                    'proyectos.titulo',
                    'modalidades_trabajo.nombre as modalidad',
                    'proyectos.ubicacion',
                    'tipos_proyecto.nombre as tipo_proyecto',
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
            ->join('tipos_proyecto', 'proyectos.id_tipo_proyecto', '=', 'tipos_proyecto.id')
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
                'tipos_proyecto.nombre as tipo_proyecto',
                'empresas.nombre as empresa')
            ->get();
    }

    /**
     * Filtra los proyectos asignados por empresa.
     *
     * Este método recupera los proyectos asignados a los estudiantes que pertenecen a una empresa específica.
     *
     * @param int $id_empresa El ID de la empresa para filtrar los proyectos asignados.
     * @return \Illuminate\Support\Collection Una colección de proyectos asignados.
     */
    public function filterByEmpresa($id_empresa): Collection {
        return DB::table('proyectos_asignados')
            ->join('estudiantes', 'proyectos_asignados.id_estudiante', '=', 'estudiantes.id')
            ->join('usuarios', 'estudiantes.id_usuario', '=', 'usuarios.id')
            ->join('proyectos', 'proyectos_asignados.id_proyecto', '=', 'proyectos.id')
            ->join('modalidades_trabajo', 'proyectos.id_modalidad', '=', 'modalidades_trabajo.id')
            ->join('empresas', 'proyectos.id_empresa', '=', 'empresas.id')
            ->join('tipos_proyecto', 'proyectos.id_tipo_proyecto', '=', 'tipos_proyecto.id')
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
                'tipos_proyecto.nombre as tipo_proyecto',
                'empresas.nombre as empresa')
            ->where('proyectos.id_empresa', $id_empresa)
            ->get();
    }
}
