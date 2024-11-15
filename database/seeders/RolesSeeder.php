<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class RolesSeeder extends Seeder {
    /**
     * Run the database seeds.
     */
    public function run(): void {
        $roles = [
            ['id' => '1', 'role_name' => 'Admin'],
            ['id' => '2', 'role_name' => 'Manager'],
            ['id' => '3', 'role_name' => 'Company'],
            ['id' => '4', 'role_name' => 'Student'],
        ];

        DB::table('roles')->insert($roles);
    }
}
