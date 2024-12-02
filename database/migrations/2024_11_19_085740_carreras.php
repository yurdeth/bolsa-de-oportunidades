<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    /**
     * Crea la tabla 'carreras' en la base de datos.
     *
     * Esta función define la estructura de la tabla `carreras`, que almacenará los datos sobre las carreras académicas en el sistema. La tabla tiene los siguientes campos:
     *
     * - `id`: clave primaria de tipo entero autoincremental que identifica de manera única cada carrera.
     * - `id_departamento`: campo de tipo entero que establece una relación de clave foránea con la tabla `departamento`. Este campo vincula la carrera con un departamento específico. La acción `onUpdate('cascade')` garantiza que los cambios en la tabla `departamento` se propaguen a esta tabla, y `onDelete('cascade')` asegura que si se elimina un departamento, se eliminen las carreras asociadas.
     * - `codigo_carrera`: campo de tipo cadena de texto con una longitud máxima de 10 caracteres, que almacena el código único de la carrera. Este campo es único en la base de datos.
     * - `nombre_carrera`: campo de tipo cadena de texto con una longitud máxima de 100 caracteres, que almacena el nombre de la carrera.
     * - `timestamps`: campos `created_at` y `updated_at` que almacenan las fechas de creación y actualización del registro, respectivamente.
     *
     * @return void
     */
    public function up(): void {
        Schema::create('carreras', function (Blueprint $table) {
            $table->id();
            $table->foreignId('id_departamento')->constrained('departamento')
                ->onUpdate('cascade')->onDelete('cascade');
            $table->string('codigo_carrera', 10)->unique();
            $table->string('nombre_carrera', 100);
            $table->timestamps();
        });
    }

    public function down(): void {
        Schema::dropIfExists('carreras');
    }
};
