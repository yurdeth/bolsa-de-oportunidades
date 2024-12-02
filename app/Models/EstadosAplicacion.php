<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * @property int $id
 * @property string $nombre
 * @property string $descripcion
 */
class EstadosAplicacion extends Model {
    protected $table = 'estados_aplicacion';

    /**
     * Elementos que se pueden asignar de manera masiva
     *
     * @var array
     */
    protected $fillable = ['nombre', 'descripcion'];
}
