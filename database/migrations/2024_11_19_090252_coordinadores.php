<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    /**
     * Crea la tabla 'coordinadores' en la base de datos.
     *
     * Esta función define la estructura de la tabla `coordinadores`, que almacenará información sobre los coordinadores de carreras en el sistema. La tabla tiene los siguientes campos:
     *
     * - `id`: clave primaria de tipo entero autoincremental que identifica de manera única a cada coordinador.
     * - `id_usuario`: campo de tipo entero que establece una relación de clave foránea con la tabla `usuarios`. Este campo vincula al coordinador con un usuario específico. La acción `onUpdate('cascade')` garantiza que los cambios en la tabla `usuarios` se propaguen a esta tabla, y `onDelete('cascade')` asegura que si se elimina un usuario, se elimine el coordinador asociado.
     * - `nombres`: campo de tipo cadena de texto con una longitud máxima de 100 caracteres, que almacena el nombre del coordinador.
     * - `apellidos`: campo de tipo cadena de texto con una longitud máxima de 100 caracteres, que almacena los apellidos del coordinador.
     * - `id_carrera`: campo de tipo entero que establece una relación de clave foránea con la tabla `carreras`. Este campo vincula al coordinador con una carrera específica. La acción `onUpdate('cascade')` garantiza que los cambios en la tabla `carreras` se propaguen a esta tabla, y `onDelete('cascade')` asegura que si se elimina una carrera, se elimine el coordinador asociado.
     * - `telefono`: campo de tipo cadena de texto con una longitud máxima de 20 caracteres, que almacena el número de teléfono del coordinador. Este campo es opcional (`nullable()`).
     * - `timestamps`: campos `created_at` y `updated_at` que almacenan las fechas de creación y actualización del registro, respectivamente.
     *
     * @return void
     */
    public function up(): void {
        Schema::create('coordinadores', function (Blueprint $table) {
            $table->id();
            $table->foreignId('id_usuario')->constrained('usuarios')
                ->onUpdate('cascade')->onDelete('cascade');
            $table->string('nombres', 100);
            $table->string('apellidos', 100);
            $table->foreignId('id_carrera')->constrained('carreras')
                ->onUpdate('cascade')->onDelete('cascade');
            $table->string('telefono', 20)->nullable();
            $table->timestamps();
        });
    }

    public function down(): void {
        Schema::dropIfExists('coordinadores');
    }
};
