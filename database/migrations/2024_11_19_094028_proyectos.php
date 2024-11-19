<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
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

    public function down(): void
    {
        Schema::dropIfExists('proyectos');
    }
};
