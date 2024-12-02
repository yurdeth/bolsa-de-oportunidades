<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    /**
     * Crea la tabla 'proyectos_asignados' en la base de datos.
     *
     * La tabla 'proyectos_asignados' vincula a los estudiantes con los proyectos a los que han sido asignados.
     * Cada registro indica qué proyecto ha sido asignado a un estudiante específico.
     *
     * Campos de la tabla:
     * - id: Clave primaria, identificador único del registro de asignación.
     * - id_proyecto: Clave foránea que hace referencia a la tabla 'proyectos', indicando el proyecto que ha sido asignado.
     * - id_estudiante: Clave foránea que hace referencia a la tabla 'estudiantes', indicando el estudiante al que se le ha asignado el proyecto.
     * - created_at: Marca de tiempo cuando se creó el registro de asignación.
     * - updated_at: Marca de tiempo cuando se actualizó el registro de asignación.
     */
    public function up(): void {
        Schema::create('proyectos_asignados', function (Blueprint $table) {
            $table->id();
            $table->foreignId('id_proyecto')->constrained('proyectos')
                ->onUpdate('cascade')
                ->onDelete('cascade');
            $table->foreignId('id_estudiante')->constrained('estudiantes')
                ->onUpdate('cascade')
                ->onDelete('cascade');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void {
        Schema::dropIfExists('proyectos_asignados');
    }
};
