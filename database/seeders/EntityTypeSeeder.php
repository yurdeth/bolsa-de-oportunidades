<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class EntityTypeSeeder extends Seeder {
    /**
     * Run the database seeds.
     */
    public function run(): void {
        $entityType = [
            ['id' => 1, 'entity_name' => 'Persona natural'],
            ['id' => 2, 'entity_name' => 'Persona jurídica'],
        ];

        DB::table('entity_type')->insert($entityType);
    }
}
