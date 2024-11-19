<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
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
            $table->boolean('verificada')->default(false);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('empresas');
    }
};
