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
            ['id' => 1, 'nombre' => 'Activa', 'descripcion' => 'Estudiantes han aplicado', 'created_at' => now(), 'updated_at' => now()],
            ['id' => 2, 'nombre' => 'Inactiva', 'descripcion' => 'Estudiantes aun no han aplicado', 'created_at' => now(), 'updated_at' => now()],
            ['id' => 3, 'nombre' => 'Finalizada', 'descripcion' => 'Proceso de aplicación finalizado', 'created_at' => now(), 'updated_at' => now()],
            ['id' => 4, 'nombre' => 'Tomada', 'descripcion' => 'Proceso ade aplicación ya tomado', 'created_at' => now(), 'updated_at' => now()],
            ['id' => 5, 'nombre' => 'Cancelada', 'descripcion' => 'El proceso de aplicación se ha cancelado', 'created_at' => now(), 'updated_at' => now()],
        ];

        DB::table('estados_aplicacion')->insert($estados_aplicacion);
    }
}
