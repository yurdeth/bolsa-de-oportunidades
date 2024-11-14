<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class SvDepartmentsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run(): void
    {
        $departments = [
            ['department_name' => 'San Miguel'],
            ['department_name' => 'San Salvador'],
            ['department_name' => 'Chalatenango'],
            ['department_name' => 'Usulután'],
            ['department_name' => 'Cuscatlán'],
            ['department_name' => 'San Vicente'],
            ['department_name' => 'Santa Ana'],
            ['department_name' => 'Ahuachapán'],
            ['department_name' => 'Morazán'],
            ['department_name' => 'La Unión'],
            ['department_name' => 'Sonsonate'],
            ['department_name' => 'La Libertad'],
            ['department_name' => 'La Paz'],
            ['department_name' => 'Cabañas']
        ];

        // Insert all departments at once
        DB::table('sv_departments')->insert($departments);
    }
}
