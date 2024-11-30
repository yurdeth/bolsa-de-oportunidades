<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class TipoNotificacionSeeder extends Seeder {
    /**
     * Run the database seeds.
     */
    public function run(): void {
        $table = 'tipo_notificacion';

        $tipo_notificacion = [
            ['id' => 1, 'nombre' => 'Nuevo proyecto', 'descripcion' => 'Se ha publicado un nuevo proyecto', 'created_at' => now(), 'updated_at' => now()],
            ['id' => 2, 'nombre' => 'Proyecto actualizado', 'descripcion' => 'Se ha actualizado un proyecto', 'created_at' => now(), 'updated_at' => now()],
            ['id' => 3, 'nombre' => 'Proyecto eliminado', 'descripcion' => 'Se ha eliminado un proyecto', 'created_at' => now(), 'updated_at' => now()],
            ['id' => 4, 'nombre' => 'Solicitud pendiente', 'descripcion' => 'Tu solicitud está en proceso de revisión', 'created_at' => now(), 'updated_at' => now()],
            ['id' => 5, 'nombre' => 'Solicitud aceptada', 'descripcion' => 'Tu solicitud ha sido aceptada por la empresa', 'created_at' => now(), 'updated_at' => now()],
            ['id' => 6, 'nombre' => 'Solicitud aprobada', 'descripcion' => 'Tu solicitud ha sido aprobada por el coordinador', 'created_at' => now(), 'updated_at' => now()],
            ['id' => 7, 'nombre' => 'Solicitud denegada', 'descripcion' => 'Tu solicitud ha sido denegada por la empresa', 'created_at' => now(), 'updated_at' => now()],
            ['id' => 8, 'nombre' => 'Solicitud rechazada', 'descripcion' => 'Tu solicitud ha sido rechazada por el coordinador', 'created_at' => now(), 'updated_at' => now()],
            ['id' => 9, 'nombre' => 'Solicitud de expulsión', 'descripcion' => 'Se ha solicitado la expulsión del estudiante de este proyecto', 'created_at' => now(), 'updated_at' => now()],
            ['id' => 9, 'nombre' => 'Expulsado', 'descripcion' => 'Has sido expulsado de este proyecto', 'created_at' => now(), 'updated_at' => now()],
        ];

    }
}
