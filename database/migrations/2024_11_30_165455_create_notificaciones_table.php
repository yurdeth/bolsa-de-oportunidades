<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    /**
     * Run the migrations.
     */
    public function up(): void {
        Schema::create('notificaciones', function (Blueprint $table) {
            $table->id();
            $table->foreignId('id_tipo_notificacion')->constrained('tipo_notificacion')
                ->onUpdate('cascade')
                ->onDelete('cascade');
            $table->foreignId('id_usuario')->constrained('usuarios')
                ->onUpdate('cascade')
                ->onDelete('cascade');
            $table->string('mensaje', 255);
            $table->boolean('leido')->default(false);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void {
        Schema::dropIfExists('notificaciones');
    }
};
