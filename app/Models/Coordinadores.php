<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * @property integer $id
 * @property integer $id_usuario
 * @property string $nombres
 * @property string $apellidos
 * @property integer $id_carrera
 * @property string $telefono
 * @property User $usuario
 * @property Carreras $carreras
 */
class Coordinadores extends Model {
    protected $table = 'coordinadores';

    /**
     * Elementos asignables en masa
     *
     * @var string
     */
    protected $fillable = ['id_usuario', 'nombres', 'apellidos', 'id_carrera', 'telefono'];

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function usuario() {
        return $this->belongsTo("App\Models\User", "id_usuario", "id");
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function carreras() {
        return $this->belongsTo("App\Models\Carreras", "id_carrera", "id");
    }
}
