<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * @property int $id
 * @property string $nombre
 * @property string $descripcion
 */
class SectoresIndustria extends Model {
    protected $table = 'sectores_industria';

    /**
     * Elementos que se pueden asignar de manera masiva
     *
     * @var string
     */
    protected $fillable = ['nombre', 'descripcion'];

}
