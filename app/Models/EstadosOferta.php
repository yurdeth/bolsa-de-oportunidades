<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * @property int $id
 * @property string $nombre_estado
 */
class EstadosOferta extends Model {
    protected $table = 'estados_oferta';

    /**
     * Elemento a llenar
     *
     * @var array
     */
    protected $fillable = ['nombre_estado'];
}
