<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class SectoresIndustria extends Model
{
    protected $table = 'sectores_industria';
    protected $fillable = ['nombre', 'descripcion'];

}
