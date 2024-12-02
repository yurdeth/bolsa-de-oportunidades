<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    /**
     * Crea la tabla 'empresas' en la base de datos.
     *
     * La tabla 'empresas' almacena información sobre las empresas registradas en el sistema.
     * Cada empresa está asociada a un usuario y a un sector industrial, y contiene información
     * detallada como el nombre, dirección, contacto, sitio web, descripción y logo de la empresa.
     *
     * Campos de la tabla:
     * - id: Clave primaria, identificador único de la empresa.
     * - id_usuario: Clave foránea que refiere al usuario dueño de la empresa (relacionada con la tabla 'usuarios').
     * - nombre: Nombre de la empresa.
     * - id_sector: Clave foránea que refiere al sector industrial al que pertenece la empresa (relacionada con la tabla 'sectores_industria').
     * - direccion: Dirección física de la empresa.
     * - telefono: Número de teléfono de la empresa, opcional.
     * - sitio_web: URL del sitio web de la empresa, opcional.
     * - descripcion: Descripción detallada de la empresa.
     * - logo_url: URL del logo de la empresa, opcional.
     * - verificada: Indica si la empresa ha sido verificada, por defecto es verdadera (true).
     * - created_at: Marca de tiempo cuando se creó el registro de la empresa.
     * - updated_at: Marca de tiempo cuando se actualizó el registro de la empresa.
     *
     * Las claves foráneas 'id_usuario' y 'id_sector' tienen restricciones de actualización y eliminación en cascada,
     * lo que significa que si un usuario o sector es actualizado o eliminado, estos cambios se reflejarán automáticamente
     * en las empresas asociadas.
     */
    public function up(): void {
        Schema::create('empresas', function (Blueprint $table) {
            $table->id();
            $table->foreignId('id_usuario')->constrained('usuarios')
                ->onUpdate('cascade')->onDelete('cascade');
            $table->string('nombre', 200);
            $table->foreignId('id_sector')->constrained('sectores_industria')
                ->onUpdate('cascade')->onDelete('cascade');
            $table->text('direccion');
            $table->string('telefono', 20)->nullable();
            $table->string('sitio_web', 255)->nullable();
            $table->text('descripcion');
            $table->string('logo_url', 255)->nullable();
            $table->boolean('verificada')->default(true);
            $table->timestamps();
        });
    }

    public function down(): void {
        Schema::dropIfExists('empresas');
    }
};
