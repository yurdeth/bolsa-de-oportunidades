<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    /**
     * Crea la tabla 'proyectos' en la base de datos.
     *
     * La tabla 'proyectos' almacena la información relacionada con los proyectos disponibles
     * para estudiantes. Cada proyecto está asociado a una empresa, un estado de oferta, una modalidad
     * de trabajo, un tipo de proyecto y una carrera.
     *
     * Campos de la tabla:
     * - id: Clave primaria, identificador único del proyecto.
     * - id_empresa: Clave foránea que hace referencia a la tabla 'empresas', indicando la empresa que ofrece el proyecto.
     * - titulo: Título del proyecto, de hasta 200 caracteres.
     * - descripcion: Descripción detallada del proyecto.
     * - requisitos: Requisitos que deben cumplir los estudiantes para aplicar al proyecto.
     * - id_estado_oferta: Clave foránea que hace referencia a la tabla 'estados_oferta', indicando el estado actual de la oferta.
     * - id_modalidad: Clave foránea que hace referencia a la tabla 'modalidades_trabajo', indicando la modalidad de trabajo del proyecto.
     * - fecha_inicio: Fecha de inicio del proyecto.
     * - fecha_fin: Fecha de finalización del proyecto.
     * - fecha_limite_aplicacion: Fecha límite para que los estudiantes apliquen al proyecto, puede ser nula.
     * - estado_proyecto: Estado del proyecto (activo/inactivo), con un valor por defecto de 'true' (activo).
     * - cupos_disponibles: Número de vacantes disponibles para el proyecto, con un valor por defecto de 1.
     * - id_tipo_proyecto: Clave foránea que hace referencia a la tabla 'tipos_proyecto', indicando el tipo de proyecto.
     * - ubicacion: Ubicación del proyecto, puede ser física o virtual.
     * - id_carrera: Clave foránea que hace referencia a la tabla 'carreras', indicando la carrera para la cual está destinado el proyecto.
     * - created_at: Marca de tiempo cuando se creó el registro del proyecto.
     * - updated_at: Marca de tiempo cuando se actualizó el registro del proyecto.
     */
    public function up(): void {
        Schema::create('proyectos', function (Blueprint $table) {
            $table->id();
            $table->foreignId('id_empresa')->constrained('empresas')
                ->onUpdate('cascade')->onDelete('cascade');
            $table->string('titulo', 200);
            $table->text('descripcion');
            $table->text('requisitos');
            $table->foreignId('id_estado_oferta')->constrained('estados_oferta')
                ->onUpdate('cascade')->onDelete('cascade');
            $table->foreignId('id_modalidad')->constrained('modalidades_trabajo')
                ->onUpdate('cascade')->onDelete('cascade');
            $table->date('fecha_inicio');
            $table->date('fecha_fin');
            $table->date('fecha_limite_aplicacion')->nullable();
            $table->boolean('estado_proyecto')->default(true);
            $table->integer('cupos_disponibles')->default(1);
            $table->foreignId('id_tipo_proyecto')->constrained('tipos_proyecto')
                ->onUpdate('cascade')->onDelete('cascade');
            $table->text('ubicacion');
            $table->foreignId('id_carrera')->constrained('carreras')
                ->onUpdate('cascade')->onDelete('cascade');
            $table->timestamps();
        });
    }

    public function down(): void {
        Schema::dropIfExists('proyectos');
    }
};
