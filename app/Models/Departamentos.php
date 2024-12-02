<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * @property integer $id
 * @property string $nombre_departamento
 * @property Coordinadores[] $coordinadores
 * @property Carreras[] $carreras
 */
class Departamentos extends Model {
    protected $table = 'departamento';

    /**
     * Elemento asignable.
     *
     * @var string
     */
    protected $fillable = ['nombre_departamento'];

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function coordinadores() {
        return $this->hasMany("App\Models\Coordinadores", "id_departamento", "id");
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function carreras() {
        return $this->hasMany("App\Models\Carreras", "id_departamento", "id");
    }
}
