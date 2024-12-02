<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

/**
 * Clase EstadosAplicacionSeeder
 *
 * Seeder para insertar los diferentes estados de aplicación en la base de datos.
 */
class EstadosAplicacionSeeder extends Seeder {
    /**
     * Ejecuta el seeder para insertar los diferentes estados de aplicación en la base de datos.
     *
     * Este método inserta registros en la tabla 'estados_aplicacion', representando diferentes
     * estados posibles en el proceso de solicitud a un proyecto. Los estados incluyen:
     * - Pendiente: Solicitud en proceso de revisión.
     * - Aceptado: Solicitud aceptada por la empresa.
     * - Aprobada: Solicitud aprobada por el coordinador.
     * - Rechazada: Solicitud rechazada por el coordinador.
     * - Denegada: Solicitud denegada por la empresa.
     * - Falta grave: La empresa solicita la expulsión del estudiante.
     * - Expulsado: Estudiante expulsado del proyecto.
     *
     * La fecha de creación y actualización se establece con el valor actual.
     */
    public function run(): void {
        $estados_aplicacion = [
            ['id' => 1,
                'nombre' => 'Pendiente',
                'descripcion' => 'Tu solicitud está en proceso de revisión',
                'created_at' => now(), 'updated_at' => now()],
            ['id' => 2,
                'nombre' => 'Aceptado',
                'descripcion' => 'Tu solicitud ha sido aceptada por la empresa',
                'created_at' => now(), 'updated_at' => now()],
            ['id' => 3,
                'nombre' => 'Aprobada',
                'descripcion' => 'Tu solicitud ha sido aprobada por el coordinador',
                'created_at' => now(), 'updated_at' => now()],
            ['id' => 4,
                'nombre' => 'Rechazada',
                'descripcion' => 'Tu solicitud ha sido rechazada por el coordinador',
                'created_at' => now(), 'updated_at' => now()],
            ['id' => 5,
                'nombre' => 'Denegada',
                'descripcion' => 'Tu solicitud ha sido denegada por la empresa',
                'created_at' => now(), 'updated_at' => now()],
            ['id' => 6,
                'nombre' => 'Falta grave',
                'descripcion' => 'La empresa ha solicitado la expulsión de este estudiante',
                'created_at' => now(), 'updated_at' => now()],
            ['id' => 7,
                'nombre' => 'Expulsado',
                'descripcion' => 'Has sido expulsado de este proyecto',
                'created_at' => now(), 'updated_at' => now()],
        ];

        DB::table('estados_aplicacion')->insert($estados_aplicacion);
    }
}
