<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

/*
 * -- auto-generated definition
create table coordinadores
(
    id_coordinador  serial
        primary key,
    id_usuario      integer
        references usuarios,
    nombres         varchar(100) not null,
    apellidos       varchar(100) not null,
    id_departamento integer
        references departamento,
    telefono        varchar(20)
);

alter table coordinadores
    owner to postgres;

*/

return new class extends Migration {
    public function up(): void
    {
        Schema::create('coordinadores', function (Blueprint $table) {
            $table->id();
            $table->foreignId('id_usuario')->constrained('usuarios')
                ->onUpdate('cascade')->onDelete('cascade');
            $table->string('nombres', 100);
            $table->string('apellidos', 100);
            $table->foreignId('id_departamento')->constrained('departamento')
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
