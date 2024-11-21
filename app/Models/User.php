<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Laravel\Passport\HasApiTokens;

class User extends Authenticatable {
    /** @use HasFactory<\Database\Factories\UserFactory> */
    use HasFactory, Notifiable, HasApiTokens;

    protected $table = 'usuarios';

    protected $fillable = [
        'email',
        'password',
        'id_tipo_usuario',
        'estado_usuario',
        'fecha_registro',
        'token_recuperacion',
        'token_expiracion',
    ];

    protected $hidden = [
        'password',
        'token_recuperacion',
    ];

    public function tipoUsuario() {
        return $this->belongsTo("App\Models\TiposUsuarios", "id_tipo_usuario", "id");
    }

    public function getStudentInfo($id) {
        if (!is_null($id)){
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
            ->get();
    }
}
