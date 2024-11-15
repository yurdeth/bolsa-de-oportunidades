<?php

namespace Database\Seeders;

use App\Models\User;

// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder {
    /**
     * Seed the application's database.
     */
    public function run(): void {
        // User::factory(10)->create();

        $this->call([
            SvDepartmentsSeeder::class,
            SvMunicipalitiesSeeder::class,
            SvDistrictsSeeder::class,
            RolesSeeder::class,
            ClasificationSeeder::class,
            BrandSeeder::class,
            SectorSeeder::class,
            EntityTypeSeeder::class,
            DepartmentsSeeder::class,
            UsersSeeder::class,
            CareersSeeder::class,
            MainManagerSeeder::class
        ]);
    }
}
