<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class EstadoOfertasSeeder extends Seeder {
    /**
     * Run the database seeds.
     */
    public function run(): void {
        $estados_oferta = [
            ['id' => 1, 'nombre_estado' => 'Actio', 'created_at' => now(), 'updated_at' => now()],
            ['id' => 2, 'nombre_estado' => 'Inactivo', 'created_at' => now(), 'updated_at' => now()],
            ['id' => 3, 'nombre_estado' => 'Finalizado', 'created_at' => now(), 'updated_at' => now()],
            ['id' => 4, 'nombre_estado' => 'Tomado', 'created_at' => now(), 'updated_at' => now()],
            ['id' => 5, 'nombre_estado' => 'Cancelado', 'created_at' => now(), 'updated_at' => now()],
        ];

        DB::table('estados_oferta')->insert($estados_oferta);
    }
}
