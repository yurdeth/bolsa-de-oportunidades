<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    /**
     * Crea la tabla 'departamento' en la base de datos.
     *
     * Esta función define la estructura de la tabla `departamento`, que almacenará los departamentos del sistema. La tabla tiene los siguientes campos:
     *
     * - `id`: clave primaria de tipo entero autoincremental que identifica de manera única cada departamento.
     * - `nombre_departamento`: campo de tipo cadena de texto con una longitud máxima de 255 caracteres, que almacena el nombre del departamento. Este campo tiene una restricción de unicidad, lo que asegura que no existan dos departamentos con el mismo nombre.
     * - `timestamps`: campos `created_at` y `updated_at` que almacenan las fechas de creación y actualización del registro, respectivamente.
     *
     * @return void
     */
    public function up(): void {
        Schema::create('departamento', function (Blueprint $table) {
            $table->id();
            $table->string('nombre_departamento', 255)->unique();
            $table->timestamps();
        });
    }

    public function down(): void {
        Schema::dropIfExists('departamento');
    }
};
