<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    /**
     * Run the migrations.
     */
    public function up(): void {
        Schema::create('sv_municipalities', function (Blueprint $table) {
            $table->id();
            $table->string('municipality_name');
            $table->foreignId('sv_department_id')
                ->constrained('sv_departments')
                ->cascadeOnUpdate()
                ->cascadeOnUpdate();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void {
        Schema::dropIfExists('sv_municipalities');
    }
};
