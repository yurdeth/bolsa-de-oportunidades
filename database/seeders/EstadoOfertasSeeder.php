<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

/**
 * Class EstadoOfertasSeeder
 */
class EstadoOfertasSeeder extends Seeder {
    /**
     * Ejecuta el seeder para insertar estados de oferta en la base de datos.
     *
     * Este método inserta varios registros en la tabla 'estados_oferta' de la base de datos.
     * Los estados de oferta que se insertan incluyen diferentes etapas de una oferta como:
     * - Activo
     * - Inactivo
     * - Finalizado
     * - Tomado
     * - Cancelado
     *
     * La fecha de creación y actualización para todos los registros se establece con el valor actual.
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
