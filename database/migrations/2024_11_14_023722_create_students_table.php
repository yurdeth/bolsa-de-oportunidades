<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    /**
     * Run the migrations.
     */
    public function up(): void {
        Schema::create('students', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('carnet')->unique();
            $table->string('email')->unique();
            $table->string('phone_number')->unique();
            $table->string('password');
            $table->foreignId('career_id')
                ->constrained('careers')
                ->cascadeOnDelete()
                ->cascadeOnUpdate();
            $table->boolean('enabled');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void {
        Schema::dropIfExists('students');
    }
};
