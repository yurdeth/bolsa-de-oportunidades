<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

/**
 * Class CarrerasSeeder
 * @package Database\Seeders
 */
class CarrerasSeeder extends Seeder {

    /**
     * Inserta registros de ejemplo en la tabla 'carreras'.
     *
     * Esta función se utiliza para poblar la tabla 'carreras' con datos iniciales de las diferentes carreras ofrecidas en distintos departamentos de una universidad.
     * Cada carrera está asociada a un departamento, un código único de carrera y su nombre.
     *
     * Los datos insertados incluyen las siguientes carreras:
     * - Ingenierías, Arquitectura, Medicina, Ciencias Sociales, Humanidades, Economía, Derecho, Ciencias Naturales y Química y Farmacia.
     *
     * La función utiliza el método 'insert' de Laravel para agregar múltiples registros a la base de datos.
     * Los campos 'created_at' y 'updated_at' son asignados con la fecha y hora actual en el momento de la inserción.
     *
     * Las carreras insertadas tienen asignados los siguientes campos:
     * - id: Identificador único para cada carrera.
     * - id_departamento: Referencia al departamento al que pertenece la carrera.
     * - codigo_carrera: Código único que identifica la carrera.
     * - nombre_carrera: Nombre de la carrera.
     * - created_at y updated_at: Marcas de tiempo que indican cuando fue creada y actualizada la carrera, respectivamente.
     */
    public function run(): void {
        DB::table('carreras')->insert([
            ['id' =>1, 'id_departamento' => 1, 'codigo_carrera' => 'I50515', 'nombre_carrera' => 'Ingeniería de Sistemas Informáticos', 'created_at' => now(), 'updated_at' => now()],
            ['id' =>2, 'id_departamento' => 1, 'codigo_carrera' => 'I50516', 'nombre_carrera' => 'Ingeniería Eléctrica', 'created_at' => now(), 'updated_at' => now()],
            ['id' =>3, 'id_departamento' => 1, 'codigo_carrera' => 'I50517', 'nombre_carrera' => 'Ingeniería Mecánica', 'created_at' => now(), 'updated_at' => now()],
            ['id' =>4, 'id_departamento' => 1, 'codigo_carrera' => 'I50518', 'nombre_carrera' => 'Arquitectura', 'created_at' => now(), 'updated_at' => now()],
            ['id' =>5, 'id_departamento' => 1, 'codigo_carrera' => 'I50519', 'nombre_carrera' => 'Ingeniería Industrial', 'created_at' => now(), 'updated_at' => now()],
            ['id' =>6, 'id_departamento' => 2, 'codigo_carrera' => 'M50520', 'nombre_carrera' => 'Laboratorio Clínico', 'created_at' => now(), 'updated_at' => now()],
            ['id' =>7, 'id_departamento' => 2, 'codigo_carrera' => 'M50521', 'nombre_carrera' => 'Doctorado en Medicina', 'created_at' => now(), 'updated_at' => now()],
            ['id' =>8, 'id_departamento' => 2, 'codigo_carrera' => 'M50522', 'nombre_carrera' => 'Anestesiología e Inhaloterapia', 'created_at' => now(), 'updated_at' => now()],
            ['id' =>9, 'id_departamento' => 2, 'codigo_carrera' => 'M50523', 'nombre_carrera' => 'Fisioterapia y terapia ocupacional', 'created_at' => now(), 'updated_at' => now()],
            ['id' => 10, 'id_departamento' => 3, 'codigo_carrera' => 'H50524', 'nombre_carrera' => 'Licenciatura en Inglés', 'created_at' => now(), 'updated_at' => now()],
            ['id' => 11, 'id_departamento' => 3, 'codigo_carrera' => 'H50525', 'nombre_carrera' => 'Licenciatura en Psicología', 'created_at' => now(), 'updated_at' => now()],
            ['id' => 12, 'id_departamento' => 3, 'codigo_carrera' => 'H50526', 'nombre_carrera' => 'Licenciatura en Educación', 'created_at' => now(), 'updated_at' => now()],
            ['id' => 13, 'id_departamento' => 3, 'codigo_carrera' => 'H50527', 'nombre_carrera' => 'Licenciatura en Sociología', 'created_at' => now(), 'updated_at' => now()],
            ['id' => 14, 'id_departamento' => 3, 'codigo_carrera' => 'H50528', 'nombre_carrera' => 'Licenciatura en Letras', 'created_at' => now(), 'updated_at' => now()],
            ['id' => 15, 'id_departamento' => 3, 'codigo_carrera' => 'H50529', 'nombre_carrera' => 'Licenciatura en Filosofía', 'created_at' => now(), 'updated_at' => now()],
            ['id' => 16, 'id_departamento' => 3, 'codigo_carrera' => 'H50530', 'nombre_carrera' => 'Licenciatura en Inglés', 'created_at' => now(), 'updated_at' => now()],
            ['id' => 17, 'id_departamento' => 4, 'codigo_carrera' => 'A50531', 'nombre_carrera' => 'Ingeniería Agronómica', 'created_at' => now(), 'updated_at' => now()],
            ['id' => 18, 'id_departamento' => 5, 'codigo_carrera' => 'E50532', 'nombre_carrera' => 'Administración', 'created_at' => now(), 'updated_at' => now()],
            ['id' => 19, 'id_departamento' => 5, 'codigo_carrera' => 'E50533', 'nombre_carrera' => 'Contaduría Pública', 'created_at' => now(), 'updated_at' => now()],
            ['id' => 20, 'id_departamento' => 5, 'codigo_carrera' => 'E50534', 'nombre_carrera' => 'Ciencias Económicas', 'created_at' => now(), 'updated_at' => now()],
            ['id' => 21, 'id_departamento' => 6, 'codigo_carrera' => 'J50535', 'nombre_carrera' => 'Licenciatura en Ciencias Jurídicas', 'created_at' => now(), 'updated_at' => now()],
            ['id' => 22, 'id_departamento' => 7, 'codigo_carrera' => 'N50536', 'nombre_carrera' => 'Licenciatura en Biología', 'created_at' => now(), 'updated_at' => now()],
            ['id' => 23, 'id_departamento' => 7, 'codigo_carrera' => 'N50537', 'nombre_carrera' => 'Licenciatura en Matemática', 'created_at' => now(), 'updated_at' => now()],
            ['id' => 24, 'id_departamento' => 7, 'codigo_carrera' => 'N50538', 'nombre_carrera' => 'Licenciatura en Estadística', 'created_at' => now(), 'updated_at' => now()],
            ['id' => 25, 'id_departamento' => 7, 'codigo_carrera' => 'N50539', 'nombre_carrera' => 'Licenciatura en Física', 'created_at' => now(), 'updated_at' => now()],
            ['id' => 26, 'id_departamento' => 7, 'codigo_carrera' => 'N50540', 'nombre_carrera' => 'Licenciatura en Biología', 'created_at' => now(), 'updated_at' => now()],
            ['id' => 27, 'id_departamento' => 7, 'codigo_carrera' => 'N50541', 'nombre_carrera' => 'Profesorado en Ciencias Naturales', 'created_at' => now(), 'updated_at' => now()],
            ['id' => 28, 'id_departamento' => 7, 'codigo_carrera' => 'N50542', 'nombre_carrera' => 'Profesorado en Matemática', 'created_at' => now(), 'updated_at' => now()],
            ['id' => 29, 'id_departamento' => 7, 'codigo_carrera' => 'N50543', 'nombre_carrera' => 'Licenciatura en Química', 'created_at' => now(), 'updated_at' => now()],
            ['id' => 30, 'id_departamento' => 8, 'codigo_carrera' => 'Q50544', 'nombre_carrera' => 'Licenciatura en Química y Farmacia', 'created_at' => now(), 'updated_at' => now()],
        ]);
    }
}
