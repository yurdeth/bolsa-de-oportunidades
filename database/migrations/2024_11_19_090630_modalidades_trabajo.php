<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    /**
     * Crea la tabla 'modalidades_trabajo' en la base de datos.
     *
     * La tabla 'modalidades_trabajo' se utiliza para almacenar los diferentes tipos de modalidades de trabajo
     * disponibles. Cada modalidad tiene un nombre único y una descripción opcional.
     *
     * Campos de la tabla:
     * - id: Clave primaria, identificador único de la modalidad de trabajo.
     * - nombre: Nombre de la modalidad de trabajo, debe ser único.
     * - descripcion: Descripción opcional de la modalidad de trabajo.
     * - created_at: Marca de tiempo cuando se creó la modalidad.
     * - updated_at: Marca de tiempo cuando se actualizó la modalidad.
     *
     * El método también asegura que los campos 'nombre' sean únicos para evitar duplicados.
     */
    public function up(): void {
        Schema::create('modalidades_trabajo', function (Blueprint $table) {
            $table->id();
            $table->string('nombre', 50)->unique();
            $table->text('descripcion')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void {
        Schema::dropIfExists('modalidades_trabajo');
    }
};
