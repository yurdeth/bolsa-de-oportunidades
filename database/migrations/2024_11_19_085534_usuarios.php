<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('usuarios', function (Blueprint $table) {
            $table->id();
            $table->string('email', 255)->unique();
            $table->string('password_hash', 255);
            $table->foreignId('id_tipo_usuario')->constrained('tipos_usuario')
                ->onUpdate('cascade')->onDelete('cascade');
            $table->boolean('estado_usuario')->default(true);
            $table->timestamp('fecha_registro')->useCurrent();
            $table->string('token_recuperacion', 255)->nullable();
            $table->timestamp('token_expiracion')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('usuarios');
    }
};
