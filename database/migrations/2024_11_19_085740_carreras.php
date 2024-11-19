<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('carreras', function (Blueprint $table) {
            $table->id();
            $table->foreignId('id_departamento')->constrained('departamento')
                ->onUpdate('cascade')->onDelete('cascade');
            $table->string('codigo_carrera', 10)->unique();
            $table->string('nombre_carrera', 100);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('carreras');
    }
};
