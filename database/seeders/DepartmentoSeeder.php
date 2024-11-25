<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class DepartmentoSeeder extends Seeder {
    public function run(): void {
        $departments = [
            ['nombre_departamento' => 'Ingeniería y Arquitectura', 'created_at' => now(), 'updated_at' => now()],
            ['nombre_departamento' => 'Medicina', 'created_at' => now(), 'updated_at' => now()],
            ['nombre_departamento' => 'Humanidades', 'created_at' => now(), 'updated_at' => now()],
            ['nombre_departamento' => 'Agronomía', 'created_at' => now(), 'updated_at' => now()],
            ['nombre_departamento' => 'Economía', 'created_at' => now(), 'updated_at' => now()],
            ['nombre_departamento' => 'Jurisprudencia', 'created_at' => now(), 'updated_at' => now()],
            ['nombre_departamento' => 'Ciencias Naturales y Matemática', 'created_at' => now(), 'updated_at' => now()],
            ['nombre_departamento' => 'Química y Farmacia', 'created_at' => now(), 'updated_at' => now()],
        ];

        // Insert all the departments at once
        DB::table('departamento')->insert($departments);
    }
}
