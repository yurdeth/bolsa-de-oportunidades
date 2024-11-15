<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class MainSeeder extends Seeder {
    /**
     * Run the database seeds.
     */
    public function run(): void {
        // Llamar a todos los seeders:
        $this->call([
            EntityTypeSeeder::class,
            RolesSeeder::class,
            DepartmentsSeeder::class,
            CareersSeeder::class,
            SvDepartmentsSeeder::class,
            SvMunicipalitiesSeeder::class,
            SvDistrictsSeeder::class,
            ClasificationSeeder::class,
            SectorSeeder::class,
            BrandSeeder::class,
            UsersSeeder::class,
            MainManagerSeeder::class,
        ]);
    }
}
