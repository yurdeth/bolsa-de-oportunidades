<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\User;
use App\Models\Empresas;
use App\Models\Estudiantes;
use Carbon\Carbon;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\DB;


class AuthController extends Controller {
    /*
        Recibir: email, password
        Retornar: token, token_type, expires_at, información del usuario
    */
    public function login(Request $r) {
        $user = $r->email;
        $pass = $r->password;

        $user_current = User::where('email', $user)->first();
        if (!$user_current) {
            return response()->json([
                'errors' => [
                    'email' => ['Correo no registrado']
                ],
                'status' => false
            ], 401);
        }

        if (Auth::attempt(['email' => $user, 'password' => $pass])) {
            $tokenResult = Auth::user()->createToken('personal Access Token');
            $data['user'] = $user_current;

            if (!$user_current->estado_usuario) {
                return response()->json([
                    'errors' => [
                        'email' => ['Usuario deshabilitado']
                    ],
                    'status' => false
                ], 401);
            }

            if ($user_current->id_tipo_usuario == 4) {
                $id_buscado = Auth::user()->id;
                $data['empresa_id'] = Empresas::where('id_usuario', $id_buscado)->first()->id;
            }

            if ($user_current->id_tipo_usuario == 3) {
                $id_buscado = Auth::user()->id;
                $data['estudiante_id'] = Estudiantes::where('id_usuario', $id_buscado)->first()->id;
            }

            $user = $r->user();
            $queried_user = User::select('usuarios.*', 'proyectos_asignados.id_proyecto as id_proyecto_asignado')
                ->join('estudiantes', 'usuarios.id', '=', 'estudiantes.id_usuario')
                ->leftJoin('proyectos_asignados', 'estudiantes.id', '=', 'proyectos_asignados.id_estudiante')
                ->where('usuarios.id', $user->id)
                ->first();

            $id_proyecto_asignado = $queried_user->id_proyecto_asignado;

            $data['token'] = $tokenResult->accessToken;
            $data['token_type'] = 'Bearer';
            $data['expires_at'] = $tokenResult->token->expires_at;
            $data['id_proyecto_asignado'] = $id_proyecto_asignado;

            return response()->json(['data' => $data, 'status' => true], 200);
        } else {
            return response()->json([
                'errors' => [
                    'password' => ['Contraseña incorrecta']
                ],
                'status' => false
            ], 401);
        }
    }

    /* Verifica que el token almacenado este vigente y sea valido y este relacionado a un usuario */
    public function access_token(Request $r) {
        // Obtener el usuario autenticado
        $user = $r->user();

        // Generar un nuevo token personal
        $tokenResult = $user->createToken('Personal Access Token');

        // Configurar la expiración del token
        $token = $tokenResult->token;
        $token->expires_at = Carbon::now()->addWeeks(1);
        $token->save();

        // Preparar la respuesta
        $data = [
            'user' => $user,
            'token' => $tokenResult->accessToken, // Token de acceso
            'token_type' => 'Bearer',
            'expires_at' => $token->expires_at, // Fecha de expiración
        ];

        return response()->json(['data' => $data, 'status' => true], 200);
    }

    public function logout(Request $r) {
        $r->user()->token()->revoke();
        return response()->json(['message' => 'Sesión finalizada', 'status' => true], 200);
    }

    public function me(Request $r) {
        $user = $r->user();
        $queried_user = User::select('usuarios.*', 'proyectos_asignados.id_proyecto as id_proyecto_asignado')
            ->join('estudiantes', 'usuarios.id', '=', 'estudiantes.id_usuario')
            ->leftJoin('proyectos_asignados', 'estudiantes.id', '=', 'proyectos_asignados.id_estudiante')
            ->where('usuarios.id', $user->id)
            ->first();

//        return response()->json(['user' => $r->user(), 'status' => true], 200);
        return response()->json(['user' => $queried_user, 'status' => true]);
    }

    public function register(Request $r) {
        /*
            Recibir: user_type (estudiante = 3, empresa = 4)
        */

        if ($r->has('user_type') && $r->user_type == 'estudiante' || $r->has('id_tipo_usuario') && $r->id_tipo_usuario == '3') {
            return (new EstudiantesController)->store($r);
            /**
             * Para evitar la duplicidad de código, se puede llamar al método store del controlador EstudiantesController
             * Datos requeridos para registrar estudiantes:
             * - user_type (estudiante), o id_tipo_usuario (3)
             * - email
             * - password
             * - id_tipo_usuario (3)
             * - estado_usuario (true)
             * - fecha_registro (hoy)
             * - token_recuperacion (cualquier cosa, no se ocupa en sí)
             * - token_expiracion (cualquier cosa, no se ocupa en sí)
             * - carnet
             * - nombres
             * - apellidos
             * - id_carrera (traer la lista de carreras a partir del filtrado de departamentos: /api/departamentos, /api/carreras/{id})
             * - año de estudio
             * - teléfono de contacto
             * - dirección de residencia
             */
        }

        if ($r->has('user_type') && $r->user_type == 'empresa' || $r->has('id_tipo_usuario') && $r->id_tipo_usuario == '4') {
            return (new EmpresasController())->store($r);
            /**
             * Para evitar la duplicidad de código, se puede llamar al método store del controlador EmpresasController
             * Datos requeridos para registrar empresas:
             * - user_type (empresa), o id_tipo_usuario (4)
             * - email
             * - password
             * - id_tipo_usuario (4)
             * - estado_usuario (true)
             * - fecha_registro (hoy)
             * - token_recuperacion (cualquier cosa, no se ocupa en sí)
             * - token_expiracion (cualquier cosa, no se ocupa en sí)
             * - id_sector (traer la lista de sectores de industria: /api/sectores-industria)
             * - nombre
             * - dirección
             * - teléfono
             * - sitio web
             * - descripción
             * - logo_url (imagen)
             */
        }

        return response()->json([
            'message' => 'Tipo de usuario no válido',
            'status' => false
        ], 400);
    }
}
