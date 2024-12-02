<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    /**
     * Crea la tabla 'estudiantes' en la base de datos.
     *
     * La tabla 'estudiantes' almacena la información personal y académica de los estudiantes
     * que se encuentran registrados en el sistema. Cada estudiante está asociado a un usuario,
     * a una carrera y tiene un número único de carnet.
     *
     * Campos de la tabla:
     * - id: Clave primaria, identificador único del estudiante.
     * - id_usuario: Clave foránea que hace referencia a la tabla 'usuarios', relacionada con el usuario registrado en el sistema.
     * - carnet: Número de carnet único asignado al estudiante.
     * - nombres: Nombre(s) del estudiante.
     * - apellidos: Apellido(s) del estudiante.
     * - id_carrera: Clave foránea que hace referencia a la tabla 'carreras', indicando la carrera a la que pertenece el estudiante.
     * - anio_estudio: Año de estudio del estudiante (por ejemplo, primer año, segundo año, etc.).
     * - telefono: Número de teléfono del estudiante, opcional.
     * - direccion: Dirección del estudiante.
     * - created_at: Marca de tiempo cuando se creó el registro del estudiante.
     * - updated_at: Marca de tiempo cuando se actualizó el registro del estudiante.
     */
    public function up(): void {
        Schema::create('estudiantes', function (Blueprint $table) {
            $table->id();
            $table->foreignId('id_usuario')->constrained('usuarios')
                ->onUpdate('cascade')->onDelete('cascade');
            $table->string('carnet', 10)->unique();
            $table->string('nombres', 100);
            $table->string('apellidos', 100);
            $table->foreignId('id_carrera')->constrained('carreras')
                ->onUpdate('cascade')->onDelete('cascade');
            $table->integer('anio_estudio');
            $table->string('telefono', 20)->nullable();
            $table->text('direccion');
            $table->timestamps();
        });
    }

    public function down(): void {
        Schema::dropIfExists('estudiantes');
    }
};
