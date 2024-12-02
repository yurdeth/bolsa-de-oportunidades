<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    /**
     * Crea la tabla 'sectores_industria' en la base de datos.
     *
     * Esta función define la estructura de la tabla `sectores_industria`, que almacenará los sectores industriales del sistema. La tabla tiene los siguientes campos:
     *
     * - `id`: clave primaria de tipo entero autoincremental que identifica de manera única cada sector industrial.
     * - `nombre`: campo de tipo cadena de texto con una longitud máxima de 100 caracteres, que almacena el nombre del sector industrial. Este campo tiene una restricción de unicidad, asegurando que no existan dos sectores con el mismo nombre.
     * - `descripcion`: campo de tipo texto que almacena una descripción del sector. Este campo es opcional, ya que puede ser nulo.
     * - `timestamps`: campos `created_at` y `updated_at` que almacenan las fechas de creación y actualización del registro, respectivamente.
     *
     * @return void
     */
    public function up(): void {
        Schema::create('sectores_industria', function (Blueprint $table) {
            $table->id();
            $table->string('nombre', 100)->unique();
            $table->text('descripcion')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void {
        Schema::dropIfExists('sectores_industria');
    }
};
