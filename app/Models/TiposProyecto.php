<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class TiposProyecto extends Model
{
    protected $table = 'tipos_proyecto';
    protected $fillable = ['nombre', 'numero_horas'];
}
