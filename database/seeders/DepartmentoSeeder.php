<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

/**
 * Class DepartmentoSeeder
 * @package Database\Seeders
 */
class DepartmentoSeeder extends Seeder {
    /**
     * Ejecuta el seeder para insertar departamentos en la base de datos.
     *
     * Este método inserta varios registros en la tabla 'departamento' de la base de datos.
     * Los departamentos que se insertan incluyen áreas como Ingeniería, Medicina, Humanidades, entre otros.
     *
     * Los departamentos insertados son:
     * - Ingeniería y Arquitectura
     * - Medicina
     * - Humanidades
     * - Agronomía
     * - Economía
     * - Jurisprudencia
     * - Ciencias Naturales y Matemática
     * - Química y Farmacia
     *
     * La fecha de creación y actualización para todos los registros se establece con el valor actual.
     */
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
