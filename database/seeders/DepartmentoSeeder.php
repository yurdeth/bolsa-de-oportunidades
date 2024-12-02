<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class DepartmentoSeeder extends Seeder {
    public function run(): void {
        $departments = [
            ['id' => 1, 'nombre_departamento' => 'Ingeniería y Arquitectura', 'created_at' => now(), 'updated_at' => now()],
            ['id' => 2, 'nombre_departamento' => 'Medicina', 'created_at' => now(), 'updated_at' => now()],
            ['id' => 3, 'nombre_departamento' => 'Humanidades', 'created_at' => now(), 'updated_at' => now()],
            ['id' => 4, 'nombre_departamento' => 'Agronomía', 'created_at' => now(), 'updated_at' => now()],
            ['id' => 5, 'nombre_departamento' => 'Economía', 'created_at' => now(), 'updated_at' => now()],
            ['id' => 6, 'nombre_departamento' => 'Jurisprudencia', 'created_at' => now(), 'updated_at' => now()],
            ['id' => 7, 'nombre_departamento' => 'Ciencias Naturales y Matemática', 'created_at' => now(), 'updated_at' => now()],
            ['id' => 8, 'nombre_departamento' => 'Química y Farmacia', 'created_at' => now(), 'updated_at' => now()],
        ];

        // Insert all the departments at once
        DB::table('departamento')->insert($departments);
    }
}
