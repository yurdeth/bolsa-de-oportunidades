<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class ModalidadesTrabajoSeeder extends Seeder {
    /**
     * Run the database seeds.
     */
    public function run(): void {
        $modalidades_trabajo = [
            ['id' => 1, 'nombre' => 'Presencial', 'descripcion' => 'El trabajo se realiza en las instalaciones de la empresa', 'created_at' => now(), 'updated_at' => now()],
            ['id' => 2, 'nombre' => 'Remoto', 'descripcion' => 'El trabajo se realiza de forma remota', 'created_at' => now(), 'updated_at' => now()],
            ['id' => 3, 'nombre' => 'Mixto', 'descripcion' => 'El trabajo se realiza de forma presencial y remota', 'created_at' => now(), 'updated_at' => now()],
        ];

        DB::table('modalidades_trabajo')->insert($modalidades_trabajo);
    }
}
