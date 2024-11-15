<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\DB;


class UsersSeeder extends Seeder {
    public function run(): void {
        $users = [
            'name' => 'Admin',
            'email' => env('MANAGER_EMAIL'),
            'phone_number' => '911',
            'password' => Hash::make(env('MANAGER_PASSWORD')),
            'enabled' => true,
            'rol_id' => 1,
        ];

        DB::table('users')->insert($users);
    }
}
