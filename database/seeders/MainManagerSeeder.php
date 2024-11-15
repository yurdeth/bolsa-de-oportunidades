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
            'name' => 'Main Manager',
            'email' => env('MANAGER_EMAIL'),
            'phone_number' => '911',
            'password' => Hash::make(env('MANAGER_PASSWORD')),
            'career_id' => 1,
            'rol_id' => 1,
            'enabled' => true,
        ];

        DB::table('managers')->insert($mainManager);
    }
}
