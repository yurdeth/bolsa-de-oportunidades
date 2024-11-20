<?php

namespace Database\Seeders;

use Carbon\Carbon;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class SectoresIndustriaSeeder extends Seeder
{
    /**
     * Run the database seeds.
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
