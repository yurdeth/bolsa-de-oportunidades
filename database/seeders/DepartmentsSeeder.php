<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class DepartmentsSeeder extends Seeder {
    public function run(): void {
        $departments = [
            ['department_name' => 'Ingeniería y Arquitectura'],
            ['department_name' => 'Medicina'],
            ['department_name' => 'Humanidades'],
            ['department_name' => 'Agronomía'],
            ['department_name' => 'Economía'],
            ['department_name' => 'Jurisprudencia'],
            ['department_name' => 'Ciencias Naturales y Matemática'],
            ['department_name' => 'Química y Farmacia'],
        ];

        // Insert all the departments at once
        DB::table('departments')->insert($departments);
    }
}
