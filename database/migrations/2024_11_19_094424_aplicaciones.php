<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    /**
     * Crea la tabla 'aplicaciones' en la base de datos.
     *
     * La tabla 'aplicaciones' registra las aplicaciones de los estudiantes a los proyectos disponibles.
     * Cada registro vincula un estudiante con un proyecto, incluyendo el estado de la aplicación y comentarios de la empresa.
     *
     * Campos de la tabla:
     * - id: Clave primaria, identificador único de la aplicación.
     * - id_estudiante: Clave foránea que hace referencia a la tabla 'estudiantes', indicando el estudiante que realiza la aplicación.
     * - id_proyecto: Clave foránea que hace referencia a la tabla 'proyectos', indicando el proyecto al cual el estudiante se aplica.
     * - id_estado_aplicacion: Clave foránea que hace referencia a la tabla 'estados_aplicacion', indicando el estado actual de la aplicación (ej. pendiente, aceptada, rechazada).
     * - comentarios_empresa: Comentarios de la empresa respecto a la aplicación, puede ser nulo si no se ha proporcionado ningún comentario.
     * - created_at: Marca de tiempo cuando se creó el registro de la aplicación.
     * - updated_at: Marca de tiempo cuando se actualizó el registro de la aplicación.
     */
    public function up(): void {
        Schema::create('aplicaciones', function (Blueprint $table) {
            $table->id();
            $table->foreignId('id_estudiante')->constrained('estudiantes')
                ->onUpdate('cascade')->onDelete('cascade');
            $table->foreignId('id_proyecto')->constrained('proyectos')
                ->onUpdate('cascade')->onDelete('cascade');
            $table->foreignId('id_estado_aplicacion')->constrained('estados_aplicacion')
                ->onUpdate('cascade')->onDelete('cascade');
            $table->text('comentarios_empresa')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void {
        Schema::dropIfExists('aplicaciones');
    }
};
