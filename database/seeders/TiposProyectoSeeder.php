<?php

namespace Database\Seeders;

use Carbon\Carbon;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

/**
 * Class TiposProyectoSeeder
 */
class TiposProyectoSeeder extends Seeder {
    /**
     * Ejecuta el seeder para insertar los tipos de proyecto en la base de datos.
     *
     * Este método inserta registros en la tabla 'tipos_proyecto', que describe los diferentes tipos
     * de proyectos que los estudiantes pueden realizar. Los tipos incluyen:
     * - Servicio Social
     * - Pasantía
     *
     * Cada tipo de proyecto tiene un campo para el número de horas asociadas, que se usa para
     * especificar la cantidad de horas que el estudiante debe dedicar al proyecto.
     * La fecha de creación y actualización se establece con el valor actual usando Carbon.
     */
    public function run(): void {
        $tipos_proyecto = [
            ['id' => 1, 'nombre' => 'Servicio Social', 'numero_horas' => 500, 'created_at' => Carbon::now(), 'updated_at' => Carbon::now()],
            ['id' => 2, 'nombre' => 'Pasantía', 'numero_horas' => 0, 'created_at' => Carbon::now(), 'updated_at' => Carbon::now()],
            /*['id' => 3, 'nombre' => 'Proyecto de Investigación', 'numero_horas' => 500, 'created_at' => Carbon::now(), 'updated_at' => Carbon::now()],
            ['id' => 4, 'nombre' => 'Proyecto de Innovación', 'numero_horas' => 500, 'created_at' => Carbon::now(), 'updated_at' => Carbon::now()],
            ['id' => 5, 'nombre' => 'Proyecto de Desarrollo', 'numero_horas' => 500, 'created_at' => Carbon::now(), 'updated_at' => Carbon::now()],
            ['id' => 6, 'nombre' => 'Proyecto de Emprendimiento', 'numero_horas' => 500, 'created_at' => Carbon::now(), 'updated_at' => Carbon::now()],
            ['id' => 7, 'nombre' => 'Proyecto de Servicio', 'numero_horas' => 500, 'created_at' => Carbon::now(), 'updated_at' => Carbon::now()],*/
        ];

        DB::table('tipos_proyecto')->insert($tipos_proyecto);
    }
}
