<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class BrandSeeder extends Seeder {
    /**
     * Run the database seeds.
     */
    public function run(): void {
        $brand = [
            ['section' => 'A', 'description' => "AGRICULTURA, GANADERÍA, SILVICULTURA Y PESCA"],
            ['section' => 'B', 'description' => "EXPLOTACIÓN DE MINAS Y CANTERAS"],
            ['section' => 'C', 'description' => "INDUSTRIAS MANUFACTURERAS"],
            ['section' => 'D', 'description' => "SUMINISTROS DE ELECTRICIDAD, GAS, VAPOR Y AIRE ACONDICIONADO"],
            ['section' => 'E', 'description' => "SUMINISTRO DE AGUA Y ALCANTARILLADO, GESTIÓN DE DESECHOS Y ACTIVIDADES DE SANEAMIENTO"],
            ['section' => 'F', 'description' => "CONSTRUCCIÓN"],
            ['section' => 'G', 'description' => "COMERCIO AL POR MAYOR Y AL POR MENOR; REPARACIÓN DE VEHÍCULOS AUTOMOTORES Y MOTOCICLETAS"],
            ['section' => 'H', 'description' => "TRANSPORTE Y ALMACENAMIENTO"],
            ['section' => 'I', 'description' => "ALOJAMIENTO Y SERVICIOS DE COMIDA"],
            ['section' => 'J', 'description' => "INFORMACIÓN Y COMUNICACIÓN"],
            ['section' => 'K', 'description' => "ACTIVIDADES FINANCIERAS Y DE SEGUROS"],
            ['section' => 'L', 'description' => "ACTIVIDADES INMOBILIARIAS"],
            ['section' => 'M', 'description' => "ACTIVIDADES DE SERVICIOS PROFESIONALES, CIENTÍFICOS Y TÉCNICOS"],
            ['section' => 'N', 'description' => "ACTIVIDADES ADMINISTRATIVAS Y SERVICIOS DE APOYO"],
            ['section' => 'O', 'description' => "ADMINISTRACIÓN PÚBLICA Y DEFENSA; PLANES DE SEGURIDAD SOCIAL DE AFILIACIÓN OBLIGATORIA"],
            ['section' => 'P', 'description' => "ENSEÑANZA"],
            ['section' => 'Q', 'description' => "SERVICIOS SOCIALES Y RELACIONADO CON LA SALUD HUMANA"],
            ['section' => 'R', 'description' => "ARTE, ESPARCIMIENTO Y OCIO"],
            ['section' => 'S', 'description' => "ACTIVIDADES DE SERVICIOS NCP"],
            ['section' => 'T', 'description' => "ACTIVIDADES DE LOS HOGARES EN CALIDAD DE EMPLEADORES, ACTIVIDADES INDIFERENCIADAS DE PRODUCCIÓN DE BIENES Y SERVICIOS DE LOS HOGARES PARA USO PROPIO"],
            ['section' => 'U', 'description' => "ACTIVIDADES DE ORGANIZACIONES Y ÓRGANOS EXTRATERRITORIALES"],
        ];

        DB::table('brands')->insert($brand);
    }
}
