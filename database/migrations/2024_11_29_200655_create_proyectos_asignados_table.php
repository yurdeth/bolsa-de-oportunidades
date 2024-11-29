<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    /**
     * Run the migrations.
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
