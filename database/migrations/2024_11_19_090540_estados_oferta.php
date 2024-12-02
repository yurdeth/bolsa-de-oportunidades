<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    /**
     * Crea la tabla 'estados_oferta' en la base de datos.
     *
     * Esta función define la estructura de la tabla `estados_oferta`, que almacenará los diferentes estados que puede tener una oferta en el sistema. La tabla tiene los siguientes campos:
     *
     * - `id`: clave primaria de tipo entero autoincremental que identifica de manera única a cada estado de oferta.
     * - `nombre_estado`: campo de tipo cadena de texto con una longitud máxima de 50 caracteres, que almacena el nombre del estado de la oferta. Este campo es único, es decir, no pueden existir dos estados con el mismo nombre.
     * - `timestamps`: campos `created_at` y `updated_at` que almacenan las fechas de creación y actualización del registro, respectivamente.
     *
     * @return void
     */
    public function up(): void {
        Schema::create('estados_oferta', function (Blueprint $table) {
            $table->id();
            $table->string('nombre_estado', 50)->unique();
            $table->timestamps();
        });
    }

    public function down(): void {
        Schema::dropIfExists('estados_oferta');
    }
};
