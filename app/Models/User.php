<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;
use Laravel\Passport\HasApiTokens;

/**
 * @property int $id
 * @property string $email
 * @property string $password
 * @property int $id_tipo_usuario
 * @property int $estado_usuario
 * @property string $fecha_registro
 * @property string $token_recuperacion
 * @property string $token_expiracion
 * @property Collection $info_estudiante
 * @property Collection $info_empresa
 * @property Collection $info_coordinador
 */
class User extends Authenticatable {
    /** @use HasFactory<\Database\Factories\UserFactory> */
    use HasFactory, Notifiable, HasApiTokens;

    protected $table = 'usuarios';

    /**
     * Atributos asignables
     *
     * @var array<string>
     */
    protected $fillable = [
        'email',
        'password',
        'id_tipo_usuario',
        'estado_usuario',
        'fecha_registro',
        'token_recuperacion',
        'token_expiracion',
    ];

    /**
     * Atributos ocultos
     *
     * @var array<string>
     */
    protected $hidden = [
        'password',
        'token_recuperacion',
    ];

    /**
     * Atributos que serán agregados a la instancia del modelo
     *
     * @var array<string>
     */
    protected $appends = ['info_estudiante', 'info_empresa', 'info_coordinador'];

    /**
     * Relación con el modelo TiposUsuarios
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function tipoUsuario() {
        return $this->belongsTo("App\Models\TiposUsuarios", "id_tipo_usuario", "id");
    }

    /**
     * Obtiene la información de un estudiante o de todos los estudiantes.
     *
     * Este método recupera la información de un estudiante específico o de todos los estudiantes, dependiendo de si
     * se proporciona un ID. La información recuperada incluye el nombre, correo electrónico, carrera, teléfono,
     * dirección y más, vinculando varias tablas relacionadas como 'usuarios', 'estudiantes', 'carreras' y 'departamento'.
     *
     * @param int|null $id El ID del estudiante para obtener información de un estudiante específico. Si es null, se obtienen todos los estudiantes.
     * @return \Illuminate\Support\Collection Una colección de información de los estudiantes.
     */
    public function getInfoEstudiante($id): Collection {
        if (!is_null($id)) {
            return DB::table('usuarios')
                ->join('estudiantes', 'usuarios.id', '=', 'estudiantes.id_usuario')
                ->join('carreras', 'estudiantes.id_carrera', '=', 'carreras.id')
                ->join('departamento', 'carreras.id_departamento', '=', 'departamento.id')
                ->select(
                    'usuarios.id',
                    'usuarios.email',
                    'usuarios.id_tipo_usuario',
                    'estudiantes.nombres',
                    'estudiantes.apellidos',
                    'estudiantes.carnet',
                    'estudiantes.anio_estudio',
                    'estudiantes.telefono',
                    'estudiantes.direccion',
                    'estudiantes.id_carrera',
                    'carreras.nombre_carrera',
                    'carreras.id_departamento',
                    'departamento.nombre_departamento'
                )
                ->where('usuarios.id', $id)
                ->get();
        }

        return DB::table('usuarios')
            ->join('estudiantes', 'usuarios.id', '=', 'estudiantes.id_usuario')
            ->join('carreras', 'estudiantes.id_carrera', '=', 'carreras.id')
            ->join('departamento', 'carreras.id_departamento', '=', 'departamento.id')
            ->select(
                'usuarios.id',
                'usuarios.email',
                'usuarios.id_tipo_usuario',
                'estudiantes.nombres',
                'estudiantes.apellidos',
                'estudiantes.carnet',
                'estudiantes.anio_estudio',
                'estudiantes.telefono',
                'estudiantes.direccion',
                'estudiantes.id_carrera',
                'carreras.nombre_carrera',
                'carreras.id_departamento',
                'departamento.nombre_departamento'
            )
            ->orderBy('usuarios.id', 'asc')
            ->get();
    }

    /**
     * Obtiene la información de un estudiante o de todos los estudiantes.
     *
     * Este método recupera la información de un estudiante específico o de todos los estudiantes, dependiendo de si
     * se proporciona un ID. La información recuperada incluye el nombre, correo electrónico, carrera, teléfono,
     * dirección y más, vinculando varias tablas relacionadas como 'usuarios', 'estudiantes', 'carreras' y 'departamento'.
     *
     * @return \Illuminate\Support\Collection Una colección de información de los estudiantes.
     */
    public function getInfoEstudianteAttribute(): Collection {
        return $this->getInfoEstudiante($this->id);
    }

    /**
     * Obtiene la información de una empresa o de todas las empresas.
     *
     * Este método recupera la información de una empresa específica o de todas las empresas, dependiendo de si
     * se proporciona un ID. La información recuperada incluye el nombre, correo electrónico, dirección, teléfono,
     * sitio web, descripción, logo y más, vinculando varias tablas relacionadas como 'usuarios', 'empresas' y 'sectores_industria'.
     *
     * @param int|null $id El ID de la empresa para obtener información de una empresa específica. Si es null, se obtienen todas las empresas.
     * @return \Illuminate\Support\Collection Una colección de información de las empresas.
     */
    public function getInfoEmpresa($id): Collection {
        if (!is_null($id)) {
            return DB::table('usuarios')
                ->join('empresas', 'usuarios.id', '=', 'empresas.id_usuario')
                ->join('sectores_industria', 'empresas.id_sector', '=', 'sectores_industria.id')
                ->select(
                    'usuarios.id',
                    'usuarios.email',
                    'usuarios.id_tipo_usuario',
                    'empresas.nombre',
                    'empresas.direccion',
                    'empresas.telefono',
                    'empresas.sitio_web',
                    'empresas.descripcion',
                    'empresas.logo_url',
                    'empresas.id_sector',
                    'sectores_industria.nombre as sector'
                )
                ->where('usuarios.id', $id)
                ->get();
        }

        return DB::table('usuarios')
            ->join('empresas', 'usuarios.id', '=', 'empresas.id_usuario')
            ->join('sectores_industria', 'empresas.id_sector', '=', 'sectores_industria.id')
            ->select(
                'usuarios.id',
                'usuarios.email',
                'usuarios.id_tipo_usuario',
                'empresas.nombre',
                'empresas.direccion',
                'empresas.telefono',
                'empresas.sitio_web',
                'empresas.descripcion',
                'empresas.logo_url',
                'empresas.id_sector',
                'sectores_industria.nombre as sector'
            )
            ->orderBy('usuarios.id', 'asc')
            ->get();
    }

    /**
     * Obtiene la información de una empresa o de todas las empresas.
     *
     * Este método recupera la información de una empresa específica o de todas las empresas, dependiendo de si
     * se proporciona un ID. La información recuperada incluye el nombre, correo electrónico, dirección, teléfono,
     * sitio web, descripción, logo y más, vinculando varias tablas relacionadas como 'usuarios', 'empresas' y 'sectores_industria'.
     *
     * @return \Illuminate\Support\Collection Una colección de información de las empresas.
     */
    public function getInfoEmpresaAttribute(): Collection {
        return $this->getInfoEmpresa($this->id);
    }

    /**
     * Obtiene la información de un coordinador o de todos los coordinadores.
     *
     * Este método recupera la información de un coordinador específico o de todos los coordinadores, dependiendo de si
     * se proporciona un ID. La información recuperada incluye el nombre, correo electrónico, teléfono, carrera y más,
     * vinculando varias tablas relacionadas como 'usuarios', 'coordinadores', 'carreras' y 'departamento'.
     *
     * @param int|null $id El ID del coordinador para obtener información de un coordinador específico. Si es null, se obtienen todos los coordinadores.
     * @return \Illuminate\Support\Collection Una colección de información de los coordinadores.
     */
    public function getInfoCoordinador($id): Collection {
        if (!is_null($id)) {
            return DB::table('usuarios')
                ->join('coordinadores', 'usuarios.id', '=', 'coordinadores.id_usuario')
                ->join('carreras', 'coordinadores.id_carrera', '=', 'carreras.id')
                ->join('departamento', 'carreras.id_departamento', '=', 'departamento.id')
                ->select(
                    'usuarios.id',
                    'usuarios.email',
                    'usuarios.id_tipo_usuario',
                    'coordinadores.nombres',
                    'coordinadores.apellidos',
                    'coordinadores.telefono',
                    'coordinadores.id_carrera',
                    'carreras.nombre_carrera',
                    'carreras.id_departamento',
                    'departamento.nombre_departamento'
                )
                ->where('usuarios.id', $id)
                ->get();
        }

        return DB::table('usuarios')
            ->join('coordinadores', 'usuarios.id', '=', 'coordinadores.id_usuario')
            ->join('carreras', 'coordinadores.id_carrera', '=', 'carreras.id')
            ->join('departamento', 'carreras.id_departamento', '=', 'departamento.id')
            ->select(
                'usuarios.id',
                'usuarios.email',
                'usuarios.id_tipo_usuario',
                'coordinadores.nombres',
                'coordinadores.apellidos',
                'coordinadores.telefono',
                'coordinadores.id_carrera',
                'carreras.nombre_carrera',
                'carreras.id_departamento',
                'departamento.nombre_departamento'
            )
            ->orderBy('usuarios.id', 'asc')
            ->get();
    }

    /**
     * Obtiene la información de un coordinador o de todos los coordinadores.
     *
     * Este método recupera la información de un coordinador específico o de todos los coordinadores, dependiendo de si
     * se proporciona un ID. La información recuperada incluye el nombre, correo electrónico, teléfono, carrera y más,
     * vinculando varias tablas relacionadas como 'usuarios', 'coordinadores', 'carreras' y 'departamento'.
     *
     * @return \Illuminate\Support\Collection Una colección de información de los coordinadores.
     */
    public function getInfoCoordinadorAttribute(): Collection {
        return $this->getInfoCoordinador($this->id);
    }
}
