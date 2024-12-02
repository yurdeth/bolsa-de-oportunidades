<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    /**
     * Crea la tabla 'favoritos' en la base de datos.
     *
     * La tabla 'favoritos' registra los proyectos que un estudiante marca como favoritos.
     * Cada registro vincula un estudiante con un proyecto específico, permitiendo que el estudiante guarde sus proyectos de interés.
     *
     * Campos de la tabla:
     * - id: Clave primaria, identificador único del favorito.
     * - id_estudiante: Clave foránea que hace referencia a la tabla 'estudiantes', indicando el estudiante que marca el proyecto como favorito.
     * - id_proyecto: Clave foránea que hace referencia a la tabla 'proyectos', indicando el proyecto que el estudiante marca como favorito.
     * - created_at: Marca de tiempo cuando se creó el registro del favorito.
     * - updated_at: Marca de tiempo cuando se actualizó el registro del favorito.
     */
    public function up(): void {
        Schema::create('favoritos', function (Blueprint $table) {
            $table->id();
            $table->foreignId('id_estudiante')->constrained('estudiantes')
                ->onUpdate('cascade')->onDelete('cascade');
            $table->foreignId('id_proyecto')->constrained('proyectos')
                ->onUpdate('cascade')->onDelete('cascade');

            $table->timestamps();
        });
    }

    public function down(): void {
        Schema::dropIfExists('favoritos');
    }
};
