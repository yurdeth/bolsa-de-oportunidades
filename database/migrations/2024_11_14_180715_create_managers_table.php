<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    /**
     * Run the migrations.
     */
    public function up(): void {
        Schema::create('managers', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('email')->unique();
            $table->string('phone_number')->unique();
            $table->string('password');
            $table->foreignId('career_id')
                ->constrained('careers')
                ->cascadeOnDelete()
                ->cascadeOnUpdate();
            $table->foreignId('rol_id')
                ->constrained('roles')
                ->cascadeOnDelete()
                ->cascadeOnUpdate();
            $table->boolean('enabled'); // <- Para permitir desactivar o no el inicio de sesión del usuario 👍🏼
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void {
        Schema::dropIfExists('managers');
    }
};
