<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ProyectosAsignados extends Model {
    protected $table = 'proyectos_asignados';

    protected $fillable = [
        'id_proyecto',
        'id_estudiante',
    ];

    public function proyecto() {
        return $this->belongsTo(Proyectos::class, 'id_proyecto');
    }
}
