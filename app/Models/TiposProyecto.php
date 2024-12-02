<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * @property int $id
 * @property string $nombre
 * @property int $numero_horas
 */
class TiposProyecto extends Model {
    protected $table = 'tipos_proyecto';

    /**
     * Atributos asignables
     *
     * @var array
     */
    protected $fillable = ['nombre', 'numero_horas'];
}
