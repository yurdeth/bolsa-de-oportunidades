<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * @property int $id
 * @property string $nombre
 */
class TipoUsuario extends Model {
    protected $table = 'tipos_usuario';

    /**
     * @var array
     */
    protected $fillable = ['nombre'];
}
