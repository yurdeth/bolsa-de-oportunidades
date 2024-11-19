<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Coordinadores extends Model
{
    protected $table = 'coordinadores';
    protected $fillable = ['id_usuario', 'nombres', 'apellidos', 'id_departamento', 'telefono'];

    public function usuario()
    {
        return $this->belongsTo("App\Models\User", "id_usuario", "id");
    }

    public function departamento()
    {
        return $this->belongsTo("App\Models\Departamentos", "id_departamento", "id");
    }
}
