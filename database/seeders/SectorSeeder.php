<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class SectorSeeder extends Seeder {
    /**
     * Run the database seeds.
     */
    public function run(): void {
        $sectors = [
            ['sector_name' => 'Municipalidad'],
            ['sector_name' => 'Autonoma'],
            ['sector_name' => 'Sector_Gobierno'],
            ['sector_name' => 'Industria'],
            ['sector_name' => 'Comercio'],
            ['sector_name' => 'Servicios'],
            ['sector_name' => 'Agricultura'],
        ];

        DB::table('sectors')->insert($sectors);
    }
}
