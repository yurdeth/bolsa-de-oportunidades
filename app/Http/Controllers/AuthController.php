<?php

namespace App\Http\Controllers;

use App\Mail\bolsadeoportunidades;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Mail;
use App\Models\User;
use App\Models\Empresas;
use App\Models\Estudiantes;
use Carbon\Carbon;
use Exception;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\DB;


class AuthController extends Controller {
    /**
     * Inicia sesión un usuario y genera un token de acceso.
     *
     * Este método autentica al usuario con las credenciales proporcionadas. Si las credenciales
     * son válidas, se genera un token de acceso personal y se devuelven datos adicionales como
     * el tipo de usuario, el proyecto asignado, y la expiración del token. También realiza
     * validaciones adicionales como verificar si el usuario está habilitado o pertenece a un
     * tipo específico (empresa o estudiante). En caso de fallas en la autenticación, se
     * devuelve un mensaje de error con el estado correspondiente.
     *
     * @param \Illuminate\Http\Request $r La solicitud HTTP que contiene el correo y la contraseña del usuario.
     * @return \Illuminate\Http\JsonResponse Respuesta JSON con el token de acceso y datos del usuario,
     * o mensajes de error en caso de autenticación fallida.
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

            $id_proyecto_asignado = $queried_user->id_proyecto_asignado ?? null;

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

    /**
     * Genera un nuevo token de acceso personal para el usuario autenticado.
     *
     * Este método genera un nuevo token de acceso personal para el usuario autenticado. El token
     * generado tiene una fecha de expiración de una semana. Si el token se genera correctamente,
     * se devuelven los datos del usuario, el token de acceso, el tipo de token, y la fecha de
     * expiración. En caso de fallas en la generación del token, se devuelve un mensaje de error
     * con el estado correspondiente.
     *
     * @param \Illuminate\Http\Request $r La solicitud HTTP que contiene el usuario autenticado.
     * @return \Illuminate\Http\JsonResponse Respuesta JSON con el token de acceso y datos del usuario,
     * o mensajes de error en caso de fallas en la generación del token.
     */
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

    /**
     * Cierra la sesión del usuario autenticado y revoca el token de acceso.
     *
     * Este método cierra la sesión del usuario autenticado y revoca el token de acceso personal
     * asociado a él. Si la sesión se cierra correctamente, se devuelve un mensaje de éxito con
     * el estado correspondiente. En caso de fallas en el cierre de sesión, se devuelve un mensaje
     * de error con el estado correspondiente.
     *
     * @param \Illuminate\Http\Request $r La solicitud HTTP que contiene el usuario autenticado.
     * @return \Illuminate\Http\JsonResponse Respuesta JSON con un mensaje de éxito o error.
     */
    public function logout(Request $r) {
        $r->user()->token()->revoke();
        return response()->json(['message' => 'Sesión finalizada', 'status' => true], 200);
    }

    /**
     * Obtener perfil del usuario
     *
     * Este método obtiene el perfil del usuario autenticado. Si el usuario es un estudiante, se
     * devuelven los datos del estudiante y el proyecto asignado. Si el usuario es una empresa,
     * se devuelven los datos de la empresa. En caso de fallas en la obtención del perfil, se
     * devuelve un mensaje de error con el estado correspondiente.
     *
     * @param \Illuminate\Http\Request $r La solicitud HTTP que contiene el usuario autenticado.
     * @return \Illuminate\Http\JsonResponse Respuesta JSON con los datos del usuario o mensajes de error.
     */
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

    /**
     * Registrar un nuevo usuario
     *
     * Este método registra un nuevo usuario en la base de datos. Los datos requeridos para el
     * registro de un estudiante son: email, password, id_tipo_usuario, estado_usuario, fecha_registro,
     * token_recuperacion, token_expiracion, carnet, nombres, apellidos, id_carrera, año_estudio,
     * telefono_contacto, direccion_residencia. Los datos requeridos para el registro de una empresa
     * son: email, password, id_tipo_usuario, estado_usuario, fecha_registro, token_recuperacion,
     * token_expiracion, id_sector, nombre, direccion, telefono, sitio_web, descripcion, logo_url.
     * En caso de fallas en el registro, se devuelve un mensaje de error con el estado correspondiente.
     *
     * @param \Illuminate\Http\Request $r La solicitud HTTP que contiene los datos del usuario a registrar.
     * @return \Illuminate\Http\JsonResponse Respuesta JSON con un mensaje de éxito o error.
     */
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

    /**
     * Enviar correo de recuperación de contraseña
     *
     * Este método envía un correo de recuperación de contraseña al usuario que lo solicite. Se
     * genera un token de recuperación de contraseña y se envía al correo del usuario. En caso
     * de fallas en el envío del correo, se devuelve un mensaje de error con el estado correspondiente.
     *
     * @param \Illuminate\Http\Request $r La solicitud HTTP que contiene el correo del usuario.
     * @return \Illuminate\Http\JsonResponse Respuesta JSON con un mensaje de éxito o error.
     */
    public function verifyEmail(Request $r) {
        $user = $r->email;

        $user_current = User::where('email', $user)->first();
        if (!$user_current) {
            return response()->json([
                'errors' => [
                    'email' => ['Correo no registrado']
                ],
                'status' => false
            ], 200);
        } else {
            return response()->json([
                'status' => true
            ], 200);
        }
    }

    /**
     * Enviar correo de recuperación de contraseña
     *
     * Este método envía un correo de recuperación de contraseña al usuario que lo solicite. Se
     * genera un token de recuperación de contraseña y se envía al correo del usuario. En caso
     * de fallas en el envío del correo, se devuelve un mensaje de error con el estado correspondiente.
     *
     * @param \Illuminate\Http\Request $r La solicitud HTTP que contiene el correo del usuario.
     * @return \Illuminate\Http\JsonResponse Respuesta JSON con un mensaje de éxito o error.
     */
    public function changePassword(Request $request) {
        try {
            $user = User::where('email', $request->email)->first();
            $user->password = Hash::make($request->newPassword);
            $user->save();
        } catch (Exception $e) {

            return response()->json(['success' => false, 'message' => $e->getMessage()], 400);
        }
        return response()->json(['success' => true], 200);
    }
}
