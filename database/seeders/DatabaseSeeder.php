<?php

namespace Database\Seeders;

use App\Models\User;

// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

/**
 * Class DatabaseSeeder
 * @package Database\Seeders
 */
class DatabaseSeeder extends Seeder {

    /**
     * Ejecuta los seeders para poblar la base de datos con datos iniciales.
     *
     * Esta función es utilizada para ejecutar varios seeders que insertan datos de prueba en distintas tablas de la base de datos.
     * Cada seeder se llama mediante el método 'call' de Laravel.
     *
     * Los seeders ejecutados son:
     * - DepartmentoSeeder: Inserta datos en la tabla de departamentos.
     * - CarrerasSeeder: Inserta datos en la tabla de carreras.
     * - SectoresIndustriaSeeder: Inserta datos en la tabla de sectores industriales.
     * - TiposProyectoSeeder: Inserta datos en la tabla de tipos de proyectos.
     * - ModalidadesTrabajoSeeder: Inserta datos en la tabla de modalidades de trabajo.
     * - EstadoOfertasSeeder: Inserta datos en la tabla de estados de oferta.
     * - EstadosAplicacionSeeder: Inserta datos en la tabla de estados de aplicación.
     *
     * Esta función garantiza que se generen datos consistentes y necesarios para realizar pruebas de la aplicación.
     */
    public function run(): void {
        $this->call([
            DepartmentoSeeder::class,
            CarrerasSeeder::class,
            SectoresIndustriaSeeder::class,
            TiposProyectoSeeder::class,
            ModalidadesTrabajoSeeder::class,
            EstadoOfertasSeeder::class,
            EstadosAplicacionSeeder::class,
        ]);
    }
}
