<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * @property int $id
 * @property string $nombre
 * @property string $descripcion
 */
class ModalidadesTrabajo extends Model {
    protected $table = 'modalidades_trabajo';

    /**
     *
     * Elementos que se pueden asignar masivamente
     * @var array
     */
    protected $fillable = ['nombre', 'descripcion'];
}
