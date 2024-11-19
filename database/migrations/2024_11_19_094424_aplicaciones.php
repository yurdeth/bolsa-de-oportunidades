<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('aplicaciones', function (Blueprint $table) {
            $table->id();
            $table->foreignId('id_estudiante')->constrained('estudiantes')
                ->onUpdate('cascade')->onDelete('cascade');
            $table->foreignId('id_proyecto')->constrained('proyectos')
                ->onUpdate('cascade')->onDelete('cascade');
            $table->foreignId('id_estado_aplicacion')->constrained('estados_aplicacion')
                ->onUpdate('cascade')->onDelete('cascade');
            $table->text('comentarios_empresa')->nullable();
            $table->text('comentarios_estudiante')->nullable();
            $table->date('fecha_inicio')->nullable();
            $table->date('fecha_fin')->nullable();
            $table->integer('horas_completadas')->default(0);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('aplicaciones');
    }
};
