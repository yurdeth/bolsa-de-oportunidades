<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class CarrerasSeeder extends Seeder {
    public function run(): void {
        DB::table('carreras')->insert([
            ['id_departamento' => 1, 'codigo_carrera' => 'I50515', 'nombre_carrera' => 'Ingeniería de Sistemas Informáticos', 'created_at' => now(), 'updated_at' => now()],
            ['id_departamento' => 1, 'codigo_carrera' => 'I50516', 'nombre_carrera' => 'Ingeniería Eléctrica', 'created_at' => now(), 'updated_at' => now()],
            ['id_departamento' => 1, 'codigo_carrera' => 'I50517', 'nombre_carrera' => 'Ingeniería Mecánica', 'created_at' => now(), 'updated_at' => now()],
            ['id_departamento' => 1, 'codigo_carrera' => 'I50518', 'nombre_carrera' => 'Arquitectura', 'created_at' => now(), 'updated_at' => now()],
            ['id_departamento' => 1, 'codigo_carrera' => 'I50519', 'nombre_carrera' => 'Ingeniería Industrial', 'created_at' => now(), 'updated_at' => now()],
            ['id_departamento' => 2, 'codigo_carrera' => 'M50520', 'nombre_carrera' => 'Laboratorio Clínico', 'created_at' => now(), 'updated_at' => now()],
            ['id_departamento' => 2, 'codigo_carrera' => 'M50521', 'nombre_carrera' => 'Doctorado en Medicina', 'created_at' => now(), 'updated_at' => now()],
            ['id_departamento' => 2, 'codigo_carrera' => 'M50522', 'nombre_carrera' => 'Anestesiología e Inhaloterapia', 'created_at' => now(), 'updated_at' => now()],
            ['id_departamento' => 2, 'codigo_carrera' => 'M50523', 'nombre_carrera' => 'Fisioterapia y terapia ocupacional', 'created_at' => now(), 'updated_at' => now()],
            ['id_departamento' => 3, 'codigo_carrera' => 'H50524', 'nombre_carrera' => 'Licenciatura en Inglés', 'created_at' => now(), 'updated_at' => now()],
            ['id_departamento' => 3, 'codigo_carrera' => 'H50525', 'nombre_carrera' => 'Licenciatura en Psicología', 'created_at' => now(), 'updated_at' => now()],
            ['id_departamento' => 3, 'codigo_carrera' => 'H50526', 'nombre_carrera' => 'Licenciatura en Educación', 'created_at' => now(), 'updated_at' => now()],
            ['id_departamento' => 3, 'codigo_carrera' => 'H50527', 'nombre_carrera' => 'Licenciatura en Sociología', 'created_at' => now(), 'updated_at' => now()],
            ['id_departamento' => 3, 'codigo_carrera' => 'H50528', 'nombre_carrera' => 'Licenciatura en Letras', 'created_at' => now(), 'updated_at' => now()],
            ['id_departamento' => 3, 'codigo_carrera' => 'H50529', 'nombre_carrera' => 'Licenciatura en Filosofía', 'created_at' => now(), 'updated_at' => now()],
            ['id_departamento' => 3, 'codigo_carrera' => 'H50530', 'nombre_carrera' => 'Licenciatura en Inglés', 'created_at' => now(), 'updated_at' => now()],
            ['id_departamento' => 4, 'codigo_carrera' => 'A50531', 'nombre_carrera' => 'Ingeniería Agronómica', 'created_at' => now(), 'updated_at' => now()],
            ['id_departamento' => 5, 'codigo_carrera' => 'E50532', 'nombre_carrera' => 'Administración', 'created_at' => now(), 'updated_at' => now()],
            ['id_departamento' => 5, 'codigo_carrera' => 'E50533', 'nombre_carrera' => 'Contaduría Pública', 'created_at' => now(), 'updated_at' => now()],
            ['id_departamento' => 5, 'codigo_carrera' => 'E50534', 'nombre_carrera' => 'Ciencias Económicas', 'created_at' => now(), 'updated_at' => now()],
            ['id_departamento' => 6, 'codigo_carrera' => 'J50535', 'nombre_carrera' => 'Licenciatura en Ciencias Jurídicas', 'created_at' => now(), 'updated_at' => now()],
            ['id_departamento' => 7, 'codigo_carrera' => 'N50536', 'nombre_carrera' => 'Licenciatura en Biología', 'created_at' => now(), 'updated_at' => now()],
            ['id_departamento' => 7, 'codigo_carrera' => 'N50537', 'nombre_carrera' => 'Licenciatura en Matemática', 'created_at' => now(), 'updated_at' => now()],
            ['id_departamento' => 7, 'codigo_carrera' => 'N50538', 'nombre_carrera' => 'Licenciatura en Estadística', 'created_at' => now(), 'updated_at' => now()],
            ['id_departamento' => 7, 'codigo_carrera' => 'N50539', 'nombre_carrera' => 'Licenciatura en Física', 'created_at' => now(), 'updated_at' => now()],
            ['id_departamento' => 7, 'codigo_carrera' => 'N50540', 'nombre_carrera' => 'Licenciatura en Biología', 'created_at' => now(), 'updated_at' => now()],
            ['id_departamento' => 7, 'codigo_carrera' => 'N50541', 'nombre_carrera' => 'Profesorado en Ciencias Naturales', 'created_at' => now(), 'updated_at' => now()],
            ['id_departamento' => 7, 'codigo_carrera' => 'N50542', 'nombre_carrera' => 'Profesorado en Matemática', 'created_at' => now(), 'updated_at' => now()],
            ['id_departamento' => 7, 'codigo_carrera' => 'N50543', 'nombre_carrera' => 'Licenciatura en Química', 'created_at' => now(), 'updated_at' => now()],
            ['id_departamento' => 8, 'codigo_carrera' => 'Q50544', 'nombre_carrera' => 'Licenciatura en Química y Farmacia', 'created_at' => now(), 'updated_at' => now()],
        ]);
    }
}
