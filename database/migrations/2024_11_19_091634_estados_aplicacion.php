<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    /**
     * Crea la tabla 'estados_aplicacion' en la base de datos.
     *
     * La tabla 'estados_aplicacion' almacena los diferentes estados posibles para las aplicaciones
     * de los estudiantes a los proyectos. Cada estado tiene un nombre único y una descripción
     * opcional que detalla el significado del estado.
     *
     * Campos de la tabla:
     * - id: Clave primaria, identificador único del estado de la aplicación.
     * - nombre: Nombre del estado de la aplicación, debe ser único.
     * - descripcion: Descripción opcional que proporciona detalles sobre el estado de la aplicación.
     * - created_at: Marca de tiempo cuando se creó el registro del estado.
     * - updated_at: Marca de tiempo cuando se actualizó el registro del estado.
     */
    public function up(): void {
        Schema::create('estados_aplicacion', function (Blueprint $table) {
            $table->id();
            $table->string('nombre', 50)->unique();
            $table->text('descripcion')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void {
        Schema::dropIfExists('estados_aplicacion');
    }
};
