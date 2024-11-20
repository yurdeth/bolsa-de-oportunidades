<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('coordinadores', function (Blueprint $table) {
            $table->id();
            $table->foreignId('id_usuario')->constrained('usuarios')
                ->onUpdate('cascade')->onDelete('cascade');
            $table->string('nombres', 100);
            $table->string('apellidos', 100);
//            $table->foreignId('id_departamento')->constrained('departamento')
            $table->foreignId('id_carrera')->constrained('carreras')
                ->onUpdate('cascade')->onDelete('cascade');
            $table->string('telefono', 20)->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('coordinadores');
    }
};
