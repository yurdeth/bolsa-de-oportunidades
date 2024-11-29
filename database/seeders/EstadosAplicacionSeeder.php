<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class EstadosAplicacionSeeder extends Seeder {
    /**
     * Run the database seeds.
     */
    public function run(): void {
        $estados_aplicacion = [
            ['id' => 1, 'nombre' => 'Pendiente', 'descripcion' => 'Tu solicitud está en proceso de revisión', 'created_at' => now(), 'updated_at' => now()],
            ['id' => 2, 'nombre' => 'Aceptado', 'descripcion' => 'Tu solicitud ha sido aceptada por la empresa', 'created_at' => now(), 'updated_at' => now()],
            ['id' => 3, 'nombre' => 'Aprobada', 'descripcion' => 'Tu solicitud ha sido aprobada por el coordinador', 'created_at' => now(), 'updated_at' => now()],
            ['id' => 5, 'nombre' => 'Denegada', 'descripcion' => 'Tu solicitud ha sido denegada por la empresa', 'created_at' => now(), 'updated_at' => now()],
            ['id' => 4, 'nombre' => 'Rechazada', 'descripcion' => 'Tu solicitud ha sido rechazada por el coordinador', 'created_at' => now(), 'updated_at' => now()],
        ];

        DB::table('estados_aplicacion')->insert($estados_aplicacion);
    }
}
