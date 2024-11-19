<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ModalidadesTrabajo extends Model
{
    protected $table = 'modalidades_trabajo';
    protected $fillable = ['nombre', 'descripcion'];
}
