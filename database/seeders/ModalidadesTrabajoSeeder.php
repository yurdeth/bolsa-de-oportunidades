<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

/**
 * Clase ModalidadesTrabajoSeeder
 *
 * Seeder para insertar las modalidades de trabajo en la base de datos.
 */
class ModalidadesTrabajoSeeder extends Seeder {
    /**
     * Ejecuta el seeder para insertar las modalidades de trabajo en la base de datos.
     *
     * Este método inserta registros en la tabla 'modalidades_trabajo', que describe las
     * diferentes modalidades disponibles para el trabajo en una empresa. Las modalidades
     * de trabajo incluyen:
     * - Presencial: El trabajo se realiza en las instalaciones de la empresa.
     * - Remoto: El trabajo se realiza de forma remota.
     * - Mixto: El trabajo se realiza de forma presencial y remota.
     *
     * La fecha de creación y actualización se establece con el valor actual.
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
