<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    /**
     * Run the migrations.
     */
    public function up(): void {
        Schema::create('companies', function (Blueprint $table) {
            $table->id();
            $table->string('commercial_name');
            $table->string('email')->unique();
            $table->string('phone_number')->unique();
            $table->string('nit')->unique();
            $table->string('password');
            $table->foreignId('entity_name_id')
                ->constrained('entity_type')
                ->cascadeOnDelete()
                ->cascadeOnUpdate();
            $table->string('address');
            $table->foreignId('district_id')
                ->constrained('sv_districts')
                ->cascadeOnDelete()
                ->cascadeOnUpdate();
            $table->foreignId('clasification_id')
                ->constrained('clasifications')
                ->cascadeOnDelete()
                ->cascadeOnUpdate();
            $table->foreignId('brand_id')
                ->constrained('brands')
                ->cascadeOnDelete()
                ->cascadeOnUpdate();
            $table->foreignId('sector_id')
                ->constrained('sectors')
                ->cascadeOnDelete()
                ->cascadeOnUpdate();
            $table->foreignId('rol_id')
                ->constrained('roles')
                ->cascadeOnDelete()
                ->cascadeOnUpdate();
            $table->boolean('enabled')->default(true);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void {
        Schema::dropIfExists('companies');
    }
};
