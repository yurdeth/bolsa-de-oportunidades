<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    /**
     * Crea la tabla 'tipos_proyecto' en la base de datos.
     *
     * La tabla 'tipos_proyecto' almacena los diferentes tipos de proyectos que pueden ser asignados
     * en el sistema, cada tipo de proyecto tiene un nombre único y un número de horas asociado.
     *
     * Campos de la tabla:
     * - id: Clave primaria, identificador único del tipo de proyecto.
     * - nombre: Nombre del tipo de proyecto, debe ser único.
     * - numero_horas: Número de horas asignadas al tipo de proyecto.
     * - created_at: Marca de tiempo cuando se creó el registro del tipo de proyecto.
     * - updated_at: Marca de tiempo cuando se actualizó el registro del tipo de proyecto.
     */
    public function up(): void {
        Schema::create('tipos_proyecto', function (Blueprint $table) {
            $table->id();
            $table->string('nombre', 50)->unique();
            $table->integer('numero_horas');
            $table->timestamps();
        });
    }

    public function down(): void {
        Schema::dropIfExists('tipos_proyecto');
    }
};
