<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class EstadosOferta extends Model
{
    protected $table = 'estados_oferta';
    protected $fillable = ['nombre_estado'];
}
