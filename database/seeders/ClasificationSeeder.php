<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class ClasificationSeeder extends Seeder {
    /**
     * Run the database seeds.
     */
    public function run(): void {
        $clasifications = [
            ['clasification_name' => 'Publico'],
            ['clasification_name' => 'Privado'],
        ];

        DB::table('clasification')->insert($clasifications);
    }
}
