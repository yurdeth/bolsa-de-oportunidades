<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * @property int $id
 * @property int $id_usuario
 * @property int $id_sector
 * @property string $nombre
 * @property string $direccion
 * @property string $telefono
 * @property string $sitio_web
 * @property string $descripcion
 * @property string $logo_url
 * @property boolean $verificada
 */
class Empresas extends Model {
    protected $table = 'empresas';

    /**
     * Elementos asignables.
     *
     * @var string
     */
    protected $fillable = [
        'id_usuario',
        'id_sector',
        'nombre',
        'direccion',
        'telefono',
        'sitio_web',
        'descripcion',
        'logo_url',
        'verificada',
    ];
}
