<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Departamentos extends Model
{
    protected $table = 'departamentos';
    protected $fillable = ['nombre'];


    public function coordinadores()
    {
        return $this->hasMany("App\Models\Coordinadores", "id_departamento", "id");
    }

    public function carreras()
    {
        return $this->hasMany("App\Models\Carreras", "id_departamento", "id");
    }
}
