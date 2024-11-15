<?php

namespace Database\Seeders;

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Database\Seeder;

class MainManagerSeeder extends Seeder {
    /**
     * Run the database seeds.
     */
    public function run(): void {
        $mainManager = [
            'career_id' => 1,
            'user_id' => 1
        ];

        DB::table('managers')->insert($mainManager);
    }
}
