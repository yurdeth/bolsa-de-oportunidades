<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use App\Models\TipoUsuario;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('tipos_usuario', function (Blueprint $table) {
            $table->id();
            $table->string('nombre', 50)->unique();
            $table->timestamps();
        });

        TipoUsuario::create(['nombre' => 'Administrador']);
        TipoUsuario::create(['nombre' => 'Coordinador']);
        TipoUsuario::create(['nombre' => 'Estudiante']);
        TipoUsuario::create(['nombre' => 'Empresa']);
    }

    public function down(): void
    {
        Schema::dropIfExists('tipos_usuario');
    }
};
