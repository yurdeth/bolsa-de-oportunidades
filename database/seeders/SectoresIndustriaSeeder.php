<?php

namespace Database\Seeders;

use Carbon\Carbon;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

/**
 * Class SectoresIndustriaSeeder
 */
class SectoresIndustriaSeeder extends Seeder {
    /**
     * Ejecuta el seeder para insertar los sectores de la industria en la base de datos.
     *
     * Este método inserta registros en la tabla 'sectores_industria', que describe los diferentes
     * sectores industriales a los que pueden pertenecer las empresas. Los sectores incluyen:
     * - Municipalidad
     * - Autonoma
     * - Sector_Gobierno
     * - Industria
     * - Comercio
     * - Servicios
     * - Agricultura
     *
     * Cada sector tiene una descripción pendiente que puede ser completada más tarde.
     * La fecha de creación y actualización se establece con el valor actual usando Carbon.
     */
    public function run(): void {
        $sectors = [
            ['id' => 1, 'nombre' => 'Municipalidad', 'descripcion' => 'Pendiente...', 'created_at' => Carbon::now(), 'updated_at' => Carbon::now()],
            ['id' => 2, 'nombre' => 'Autonoma', 'descripcion' => 'Pendiente...', 'created_at' => Carbon::now(), 'updated_at' => Carbon::now()],
            ['id' => 3, 'nombre' => 'Sector_Gobierno', 'descripcion' => 'Pendiente...', 'created_at' => Carbon::now(), 'updated_at' => Carbon::now()],
            ['id' => 4, 'nombre' => 'Industria', 'descripcion' => 'Pendiente...', 'created_at' => Carbon::now(), 'updated_at' => Carbon::now()],
            ['id' => 5, 'nombre' => 'Comercio', 'descripcion' => 'Pendiente...', 'created_at' => Carbon::now(), 'updated_at' => Carbon::now()],
            ['id' => 6, 'nombre' => 'Servicios', 'descripcion' => 'Pendiente...', 'created_at' => Carbon::now(), 'updated_at' => Carbon::now()],
            ['id' => 7, 'nombre' => 'Agricultura', 'descripcion' => 'Pendiente...', 'created_at' => Carbon::now(), 'updated_at' => Carbon::now()],
        ];

        DB::table('sectores_industria')->insert($sectors);
    }
}
