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
        'leido',
    ];

    public function getNotificaciones(): Collection {
        return DB::table('notificaciones')
            ->select('notificaciones.id as id_notificacion',
                'tipo_notificacion.nombre',
                'tipo_notificacion.descripcion',
                'notificaciones.mensaje',
                'estudiantes.nombres',
                'estudiantes.apellidos')
            ->join('tipo_notificacion', 'notificaciones.id_tipo_notificacion', '=', 'tipo_notificacion.id')
            ->join('usuarios', 'notificaciones.id_usuario', '=', 'usuarios.id')
            ->join('estudiantes', 'usuarios.id', '=', 'estudiantes.id_usuario')
            ->get();

    }
}
