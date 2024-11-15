<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class SvMunicipalitiesSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run(): void
    {
        $municipalities = [
            ['municipality_name' => 'San Miguel Norte', 'sv_department_id' => 1],
            ['municipality_name' => 'San Miguel Centro', 'sv_department_id' => 1],
            ['municipality_name' => 'San Miguel Oeste', 'sv_department_id' => 1],
            ['municipality_name' => 'San Salvador Norte', 'sv_department_id' => 2],
            ['municipality_name' => 'San Salvador Oeste', 'sv_department_id' => 2],
            ['municipality_name' => 'San Salvador Este', 'sv_department_id' => 2],
            ['municipality_name' => 'San Salvador Centro', 'sv_department_id' => 2],
            ['municipality_name' => 'San Salvador Sur', 'sv_department_id' => 2],
            ['municipality_name' => 'Chalatenango Norte', 'sv_department_id' => 3],
            ['municipality_name' => 'Chalatenango Centro', 'sv_department_id' => 3],
            ['municipality_name' => 'Chalatenango Sur', 'sv_department_id' => 3],
            ['municipality_name' => 'Usulután Norte', 'sv_department_id' => 4],
            ['municipality_name' => 'Usulután Este', 'sv_department_id' => 4],
            ['municipality_name' => 'Usulután Oeste', 'sv_department_id' => 4],
            ['municipality_name' => 'Cuscatlán Norte', 'sv_department_id' => 5],
            ['municipality_name' => 'Cuscatlán Sur', 'sv_department_id' => 5],
            ['municipality_name' => 'San Vicente Norte', 'sv_department_id' => 6],
            ['municipality_name' => 'San Vicente Sur', 'sv_department_id' => 6],
            ['municipality_name' => 'Santa Ana Norte', 'sv_department_id' => 7],
            ['municipality_name' => 'Santa Ana Centro', 'sv_department_id' => 7],
            ['municipality_name' => 'Santa Ana Este', 'sv_department_id' => 7],
            ['municipality_name' => 'Santa Ana Oeste', 'sv_department_id' => 7],
            ['municipality_name' => 'Ahuachapán Norte', 'sv_department_id' => 8],
            ['municipality_name' => 'Ahuachapán Centro', 'sv_department_id' => 8],
            ['municipality_name' => 'Ahuachapán Sur', 'sv_department_id' => 8],
            ['municipality_name' => 'Morazán Norte', 'sv_department_id' => 9],
            ['municipality_name' => 'Morazán Sur', 'sv_department_id' => 9],
            ['municipality_name' => 'La Unión Norte', 'sv_department_id' => 10],
            ['municipality_name' => 'La Unión Sur', 'sv_department_id' => 10],
            ['municipality_name' => 'Sonsonate Norte', 'sv_department_id' => 11],
            ['municipality_name' => 'Sonsonate Centro', 'sv_department_id' => 11],
            ['municipality_name' => 'Sonsonate Este', 'sv_department_id' => 11],
            ['municipality_name' => 'Sonsonate Oeste', 'sv_department_id' => 11],
            ['municipality_name' => 'La Libertad Norte', 'sv_department_id' => 12],
            ['municipality_name' => 'La Libertad Centro', 'sv_department_id' => 12],
            ['municipality_name' => 'La Libertad Oeste', 'sv_department_id' => 12],
            ['municipality_name' => 'La Libertad Este', 'sv_department_id' => 12],
            ['municipality_name' => 'La Libertad Costa', 'sv_department_id' => 12],
            ['municipality_name' => 'La Libertad Sur', 'sv_department_id' => 12],
            ['municipality_name' => 'La Paz Oeste', 'sv_department_id' => 13],
            ['municipality_name' => 'La Paz Centro', 'sv_department_id' => 13],
            ['municipality_name' => 'La Paz Este', 'sv_department_id' => 13],
            ['municipality_name' => 'Cabañas Este', 'sv_department_id' => 14],
            ['municipality_name' => 'Cabañas Oeste', 'sv_department_id' => 14],
        ];

        // Insert all municipalities at once
        DB::table('sv_municipalities')->insert($municipalities);
    }
}
