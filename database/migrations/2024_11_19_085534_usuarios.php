<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Schema;
use App\Models\User;

return new class extends Migration {
    /**
     * Crea la tabla 'usuarios' en la base de datos.
     *
     * Esta función define la estructura de la tabla `usuarios`, que almacenará los datos de los usuarios en el sistema. La tabla tiene los siguientes campos:
     *
     * - `id`: clave primaria de tipo entero autoincremental que identifica de manera única a cada usuario.
     * - `email`: campo de tipo cadena de texto con una longitud máxima de 255 caracteres, que almacena el correo electrónico del usuario. Este campo es único.
     * - `password`: campo de tipo cadena de texto con una longitud máxima de 255 caracteres, que almacena la contraseña del usuario.
     * - `id_tipo_usuario`: campo de tipo entero que establece una relación de clave foránea con la tabla `tipos_usuario`, indicando el tipo de usuario (Administrador, Coordinador, Estudiante, Empresa). La acción `onUpdate('cascade')` garantiza que los cambios en la tabla `tipos_usuario` se propaguen a esta tabla, y `onDelete('cascade')` asegura que si se elimina un tipo de usuario, se eliminen los usuarios asociados.
     * - `estado_usuario`: campo booleano que indica el estado del usuario (activo o inactivo), con un valor por defecto de `true` (activo).
     * - `fecha_registro`: campo de tipo timestamp que registra la fecha y hora en que se creó el usuario. Este campo se establece automáticamente al valor actual mediante `useCurrent()`.
     * - `token_recuperacion`: campo opcional de tipo cadena de texto que almacena el token de recuperación de contraseña, si está presente.
     * - `token_expiracion`: campo opcional de tipo timestamp que almacena la fecha y hora de expiración del token de recuperación de contraseña.
     * - `timestamps`: campos `created_at` y `updated_at` que almacenan las fechas de creación y actualización del registro, respectivamente.
     *
     * Además, se crea un usuario inicial con el correo y la contraseña proporcionados en las variables de entorno `MANAGER_EMAIL` y `MANAGER_PASSWORD`. Este usuario es asignado como un administrador con el tipo de usuario `1`.
     *
     * @return void
     */
    public function up(): void {
        Schema::create('usuarios', function (Blueprint $table) {
            $table->id();
            $table->string('email', 255)->unique();
            $table->string('password', 255);
            $table->foreignId('id_tipo_usuario')->constrained('tipos_usuario')
                ->onUpdate('cascade')->onDelete('cascade');
            $table->boolean('estado_usuario')->default(true);
            $table->timestamp('fecha_registro')->useCurrent();
            $table->string('token_recuperacion', 255)->nullable();
            $table->timestamp('token_expiracion')->nullable();
            $table->timestamps();
        });

        User::create([
            'email' => env('MANAGER_EMAIL'),
            'password' => Hash::make(env('MANAGER_PASSWORD')),
            'id_tipo_usuario' => 1,
            'estado_usuario' => true,
        ]);
    }

    public function down(): void {
        Schema::dropIfExists('usuarios');
    }
};
