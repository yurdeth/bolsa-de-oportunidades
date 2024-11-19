<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Carreras extends Model
{
    protected $table = 'carreras';
    protected $fillable = ['id_departamento', 'codigo_carrera', 'nombre_carrera'];
}
