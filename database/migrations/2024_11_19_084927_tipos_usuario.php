<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use App\Models\TipoUsuario;

return new class extends Migration {
    /**
     * Crea la tabla 'tipos_usuario' y agrega los registros iniciales de tipos de usuario.
     *
     * Esta función define la estructura de la tabla `tipos_usuario` que almacenará los diferentes tipos de usuarios en el sistema.
     * La tabla tiene los siguientes campos:
     *
     * - `id`: clave primaria de tipo entero autoincremental que identifica de manera única cada tipo de usuario.
     * - `nombre`: campo de tipo cadena de texto con una longitud máxima de 50 caracteres, que almacena el nombre del tipo de usuario. Este campo tiene una restricción de unicidad.
     * - `timestamps`: campos `created_at` y `updated_at` para almacenar las fechas de creación y actualización del registro.
     *
     * Después de crear la tabla, se insertan cuatro registros predeterminados:
     * - 'Administrador'
     * - 'Coordinador'
     * - 'Estudiante'
     * - 'Empresa'
     *
     * Estos registros iniciales representan los tipos de usuario básicos para el sistema.
     *
     * @return void
     */
    public function up(): void {
        Schema::create('tipos_usuario', function (Blueprint $table) {
            $table->id();
            $table->string('nombre', 50)->unique();
            $table->timestamps();
        });

        TipoUsuario::create(['nombre' => 'Administrador']);
        TipoUsuario::create(['nombre' => 'Coordinador']);
        TipoUsuario::create(['nombre' => 'Estudiante']);
        TipoUsuario::create(['nombre' => 'Empresa']);
    }

    public function down(): void
    {
        Schema::dropIfExists('tipos_usuario');
    }
};
